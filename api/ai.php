<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

/**
 * POST /api/ai.php
 * Receives raw text, calls OpenAI GPT-4o-mini, returns typed note cards JSON.
 * Deducts 1 credit per call.
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed.', 405);
}

$user = verifyAuthToken();
$uid = (int)$user['id'];

checkRateLimit((string)$uid);

$pdo = Database::get();

// Check credits
$stmt = $pdo->prepare('SELECT credits FROM users WHERE id = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch();

if (!$user || (int) $user['credits'] < CREDITS_PER_AI_CALL) {
    jsonError('Créditos insuficientes. Recarregue para continuar usando a IA.', 402);
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$text = trim($body['text'] ?? '');

if ($text === '') {
    jsonError('Texto vazio. Por favor, escreva ou fale algo.');
}

if (mb_strlen($text) > 2000) {
    jsonError('Texto muito longo. Máximo 2000 caracteres.');
}

// --- Call OpenAI ---
$systemPrompt = <<<PROMPT
Você é um assistente de produtividade especializado em organizar notas e compromissos.

Analise o texto do usuário e retorne APENAS um JSON válido com um array de notas identificadas.

Cada nota deve ter os seguintes campos:
- "type": um dos valores: "generic", "shopping-list", "event", "appointment"
- "title": título curto e descritivo em português
- "content": conteúdo detalhado da nota
- "color": use EXATAMENTE estas cores baseadas no tipo:
  - Para "shopping-list": "#10b981" (verde)
  - Para "event": "#3b82f6" (azul)
  - Para "appointment": "#f59e0b" (amarelo/âmbar)
  - Para "generic": "#1e293b" (padrão)
- "tags": array de tags relevantes (máximo 3, em português)
- "extra": objeto com campos adicionais:
  - Para "shopping-list": {"items": ["item1", "item2", ...]}
  - Para "event": {"date": "YYYY-MM-DD", "time": "HH:MM", "location": ""}
  - Para "appointment": {"date": "YYYY-MM-DD", "time": "HH:MM", "person": ""}
  - Para "generic": {}

Regras importantes:
1. Se o texto mencionar compras, ingredientes ou itens, crie uma nota "shopping-list"
2. Se mencionar data/hora com local ou celebração, crie uma nota "event"  
3. Se mencionar reunião, consulta ou encontro com alguém, crie uma nota "appointment"
4. Um texto pode gerar múltiplas notas de tipos diferentes
5. Retorne APENAS o JSON, sem texto adicional, sem markdown, sem explicações
6. Sempre responda em português

Exemplo de saída:
[{"type":"shopping-list","title":"Lista de Compras","content":"Itens para comprar no mercado","color":"#1a3a2a","tags":["mercado","compras"],"extra":{"items":["leite","pão","ovos"]}}]
PROMPT;

$requestBody = json_encode([
    'model'       => OPENAI_MODEL,
    'max_tokens'  => OPENAI_MAX_TOKENS,
    'temperature' => 0.3,
    'messages'    => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user',   'content' => $text],
    ],
]);

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $requestBody,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY,
    ],
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    jsonError('Falha na conexão com a IA. Tente novamente.', 503);
}

if ($httpCode !== 200) {
    jsonError('Erro ao processar com IA (HTTP ' . $httpCode . '). Tente novamente.', 502);
}

$aiData = json_decode($response, true);
$aiText = $aiData['choices'][0]['message']['content'] ?? '';

// Clean markdown code blocks and parse JSON from AI response
$cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($aiText));
$parsed = json_decode(trim($cleanJson), true);

$notes = [];
if (isset($parsed['notes']) && is_array($parsed['notes'])) {
    $notes = $parsed['notes'];
} elseif (is_array($parsed) && isset($parsed[0])) {
    $notes = $parsed;
} elseif (is_array($parsed) && (isset($parsed['type']) || isset($parsed['title']))) {
    $notes = [$parsed];
}

if (empty($notes)) {
    // Fallback: create a generic note
    $notes = [[
        'type'    => 'generic',
        'title'   => 'Nova Nota',
        'content' => $text,
        'color'   => '#1e293b',
        'tags'    => [],
        'extra'   => new stdClass(),
    ]];
}

// Deduct credit (exempting VIP accounts)
$isVip = in_array(strtolower($user['email'] ?? ''), ['fbr4g4@gmail.com', 'fb4g4@gmail.com']);
if (!$isVip) {
    $pdo->prepare('UPDATE users SET credits = credits - ?, updated_at = datetime("now") WHERE id = ?')
        ->execute([CREDITS_PER_AI_CALL, $uid]);
}

// Format notes for client (stateless: client saves to IndexedDB + Google Drive)
$createdNotes = [];
$nowMs = (int)(microtime(true) * 1000);
foreach ($notes as $idx => $note) {
    $type    = in_array($note['type'] ?? '', ['generic', 'shopping-list', 'event', 'appointment', 'checklist', 'agenda'])
               ? $note['type'] : 'generic';
    $title   = trim((string)($note['title'] ?? ''));
    if ($title === '') {
        $title = ($type === 'shopping-list' || $type === 'checklist') ? 'Lista de Compras' : 'Nova Nota';
    }
    $title   = substr($title, 0, 200);

    $content = trim((string)($note['content'] ?? ''));
    $color   = preg_match('/^#[0-9a-fA-F]{6}$/', $note['color'] ?? '') ? $note['color'] : '#1e293b';
    
    $extraObj = $note['extra'] ?? [];
    if (!is_array($extraObj)) {
        $extraObj = (array)$extraObj;
    }

    // Normalize checklist items if present
    if (!empty($extraObj['items']) && is_array($extraObj['items'])) {
        $normalizedItems = [];
        foreach ($extraObj['items'] as $item) {
            if (is_array($item)) {
                $normalizedItems[] = [
                    'text' => trim((string)($item['text'] ?? '')),
                    'done' => !empty($item['done'])
                ];
            } else {
                $normalizedItems[] = [
                    'text' => trim((string)$item),
                    'done' => false
                ];
            }
        }
        $extraObj['items'] = $normalizedItems;

        // If content is empty, build from items
        if ($content === '') {
            $texts = array_filter(array_column($normalizedItems, 'text'));
            $content = implode("\n", $texts);
        }
    }

    // Fallback if content is still empty
    if ($content === '') {
        $content = $text;
    }

    if (empty($extraObj['image'])) {
        $defaults = [
            'shopping-list' => 'lista.png',
            'checklist'     => 'lista.png',
            'event'         => 'evento.png',
            'appointment'   => 'evento.png',
            'agenda'        => 'evento.png',
            'generic'       => 'notas.png'
        ];
        $extraObj['image'] = $defaults[$type] ?? 'notas.png';
    }

    $saved = [
        'id'         => $nowMs + $idx,
        'user_id'    => $uid,
        'title'      => $title,
        'content'    => $content,
        'type'       => $type,
        'color'      => $color,
        'pinned'     => false,
        'archived'   => false,
        'in_trash'   => false,
        'position'   => 0,
        'tags'       => $note['tags'] ?? [],
        'extra'      => $extraObj,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $createdNotes[] = $saved;
}

// Get updated credits
$credStmt = $pdo->prepare('SELECT credits FROM users WHERE id = ?');
$credStmt->execute([$uid]);
$credits = (int) $credStmt->fetch()['credits'];

jsonResponse([
    'success'         => true,
    'notes'           => $createdNotes,
    'credits_used'    => CREDITS_PER_AI_CALL,
    'credits_remaining' => $credits,
], 201);
