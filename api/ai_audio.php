<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

/**
 * POST /api/ai_audio.php
 * Transcrição de áudio nativa via OpenAI Whisper + Geração Estruturada de Notas.
 * Deduz 1 crédito por chamada.
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed.', 405);
}

$user = verifyAuthToken();
$uid  = (int) $user['id'];

checkRateLimit((string)$uid);

$pdo = Database::get();

// Verificar créditos
$stmt = $pdo->prepare('SELECT credits FROM users WHERE id = ?');
$stmt->execute([$uid]);
$userRow = $stmt->fetch();

if (!$userRow || (int)$userRow['credits'] < CREDITS_PER_AI_CALL) {
    jsonError('Créditos insuficientes. Recarregue para usar a gravação de voz com IA.', 402);
}

if (!isset($_FILES['audio']) || $_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
    jsonError('Nenhum arquivo de áudio enviado ou falha no upload.');
}

$tmpPath = $_FILES['audio']['tmp_name'];
$originalName = $_FILES['audio']['name'] ?? 'audio.webm';
$mimeType = $_FILES['audio']['type'] ?? 'audio/webm';

// Limite de 20MB para áudio
if (filesize($tmpPath) > 20 * 1024 * 1024) {
    jsonError('Arquivo de áudio muito grande. Máximo 20MB.');
}

// 1. Chamar OpenAI Whisper API
$cFile = curl_file_create($tmpPath, $mimeType, $originalName);
$postData = [
    'file'     => $cFile,
    'model'    => 'whisper-1',
    'language' => 'pt',
    'response_format' => 'json'
];

$ch = curl_init('https://api.openai.com/v1/audio/transcriptions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $postData,
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . OPENAI_API_KEY,
    ],
    CURLOPT_TIMEOUT        => 45,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError || $httpCode !== 200) {
    jsonError('Falha ao transcrever o áudio com o Whisper. Tente novamente.', 502);
}

$whisperData = json_decode($response, true);
$transcribedText = trim($whisperData['text'] ?? '');

if ($transcribedText === '') {
    jsonError('Não foi possível identificar falas no áudio.');
}

// 2. Processar a transcrição para gerar notas estruturadas (igual ai.php)
$systemPrompt = <<<PROMPT
Você é um assistente de produtividade especializado em organizar notas e compromissos a partir de comandos de voz transcritos.

Analise a transcrição de áudio do usuário e retorne APENAS um JSON válido com um array de notas identificadas.

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
  - Para "shopping-list": {"items": [{"text": "item1", "done": false}, ...]}
  - Para "event": {"date": "YYYY-MM-DD", "time": "HH:MM", "location": ""}
  - Para "appointment": {"date": "YYYY-MM-DD", "time": "HH:MM", "person": ""}
  - Para "generic": {}

Regras:
1. Se o texto mencionar compras, ingredientes ou itens, crie "shopping-list"
2. Se mencionar data/hora com local ou celebração, crie "event"
3. Se mencionar reunião, consulta ou encontro com alguém, crie "appointment"
4. Retorne APENAS o JSON, sem markdown ou explicações.
PROMPT;

$requestBody = json_encode([
    'model'       => OPENAI_MODEL,
    'max_tokens'  => OPENAI_MAX_TOKENS,
    'temperature' => 0.3,
    'messages'    => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user',   'content' => $transcribedText],
    ],
]);

$ch2 = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch2, [
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

$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

$notes = [];
if ($httpCode2 === 200) {
    $aiData = json_decode($response2, true);
    $aiText = $aiData['choices'][0]['message']['content'] ?? '';
    
    // Clean markdown code blocks and parse JSON
    $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($aiText));
    $parsed = json_decode(trim($cleanJson), true);

    if (isset($parsed['notes']) && is_array($parsed['notes'])) {
        $notes = $parsed['notes'];
    } elseif (is_array($parsed) && isset($parsed[0])) {
        $notes = $parsed;
    } elseif (is_array($parsed) && (isset($parsed['type']) || isset($parsed['title']))) {
        $notes = [$parsed];
    }
}

if (empty($notes)) {
    $notes = [[
        'type'    => 'generic',
        'title'   => 'Nota de Voz',
        'content' => $transcribedText,
        'color'   => '#1e293b',
        'tags'    => ['voz'],
        'extra'   => new stdClass(),
    ]];
}

// Salvar notas no banco de dados
$createdNotes = [];
$nowMs = (int)(microtime(true) * 1000);
foreach ($notes as $idx => $note) {
    $type    = in_array($note['type'] ?? '', ['generic', 'shopping-list', 'event', 'appointment', 'checklist', 'agenda'])
               ? $note['type'] : 'generic';
    $title   = trim((string)($note['title'] ?? ''));
    if ($title === '') {
        $title = ($type === 'shopping-list' || $type === 'checklist') ? 'Lista de Compras' : 'Nota de Voz';
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
        $content = $transcribedText;
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
        'tags'       => $note['tags'] ?? ['voz'],
        'extra'      => $extraObj,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $createdNotes[] = $saved;
}

// Deduzir 1 crédito (isentando VIPs)
$isVip = in_array(strtolower($user['email'] ?? ''), ['fbr4g4@gmail.com', 'fb4g4@gmail.com']);
if (!$isVip) {
    $pdo->prepare('UPDATE users SET credits = credits - ?, updated_at = datetime("now") WHERE id = ?')
        ->execute([CREDITS_PER_AI_CALL, $uid]);
}

$credStmt = $pdo->prepare('SELECT credits FROM users WHERE id = ?');
$credStmt->execute([$uid]);
$creditsRemaining = (int)$credStmt->fetchColumn();

jsonResponse([
    'success'           => true,
    'transcription'     => $transcribedText,
    'notes'             => $createdNotes,
    'credits_used'      => CREDITS_PER_AI_CALL,
    'credits_remaining' => $creditsRemaining,
], 201);
