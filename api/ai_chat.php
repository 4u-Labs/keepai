<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

/**
 * POST /api/ai_chat.php
 * "Pergunte às suas Notas" - Chatbot / RAG leve sobre as notas do usuário.
 * Deduz 1 crédito por pergunta.
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed.', 405);
}

$user = verifyAuthToken();
$uid  = (int) $user['id'];

checkRateLimit((string)$uid);

$pdo = Database::get();

// Verificar saldo de créditos
$stmt = $pdo->prepare('SELECT credits FROM users WHERE id = ?');
$stmt->execute([$uid]);
$userRow = $stmt->fetch();

if (!$userRow || (int)$userRow['credits'] < CREDITS_PER_AI_CALL) {
    jsonError('Créditos insuficientes. Recarregue para conversar com suas notas.', 402);
}

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$query = trim($body['query'] ?? '');

if ($query === '') {
    jsonError('Por favor, digite sua pergunta.');
}

if (mb_strlen($query) > 1000) {
    jsonError('Pergunta muito longa. Máximo 1000 caracteres.');
}

// Obter notas enviadas pelo cliente (Zero armazenamento de notas privadas no servidor)
$clientNotes = $body['notes'] ?? null;

$contextNotes = [];
if (is_array($clientNotes) && !empty($clientNotes)) {
    foreach (array_slice($clientNotes, 0, 60) as $n) {
        $extra = $n['extra'] ?? ($n['extra_json'] ? json_decode($n['extra_json'], true) : []);
        $extraDetails = '';
        
        $type = $n['type'] ?? 'generic';
        if ($type === 'shopping-list' && !empty($extra['items'])) {
            $itemsStr = [];
            foreach ($extra['items'] as $it) {
                if (is_array($it)) {
                    $itemsStr[] = (($it['done'] ?? false) ? '[x] ' : '[ ] ') . ($it['text'] ?? '');
                } else {
                    $itemsStr[] = '[ ] ' . $it;
                }
            }
            $extraDetails = 'Itens da Lista: ' . implode(', ', $itemsStr);
        } elseif (($type === 'event' || $type === 'appointment' || $type === 'agenda') && !empty($extra['date'])) {
            $extraDetails = 'Data: ' . $extra['date'] . (!empty($extra['time']) ? ' às ' . $extra['time'] : '') . (!empty($extra['location']) ? ' em ' . $extra['location'] : '') . (!empty($extra['person']) ? ' com ' . $extra['person'] : '');
        }

        $id = $n['id'] ?? '';
        $title = $n['title'] ?? '';
        $content = $n['content'] ?? '';
        $entry = "ID: {$id} | Tipo: {$type} | Título: {$title} | Conteúdo: {$content}" . ($extraDetails ? " | $extraDetails" : "");
        $contextNotes[] = $entry;
    }
}

$notesBlob = !empty($contextNotes) 
    ? implode("\n", $contextNotes) 
    : "O usuário ainda não possui notas salvas no momento.";

$reqLang = strtolower(trim($body['lang'] ?? ''));
$isEnglish = ($reqLang === 'en');
if (preg_match('/(em ingl[eê]s|in english|vers[aã]o em ingl[eê]s|english version)/ui', $query)) {
    $isEnglish = true;
} elseif (!$isEnglish && $reqLang !== 'pt') {
    if (preg_match('/\b(what|how|who|where|when|list|show me|find|summarize)\b/i', $query)) {
        if (!preg_match('/\b(qual|quem|como|onde|resumo|explique|em português)\b/i', $query)) {
            $isEnglish = true;
        }
    }
}

if ($isEnglish) {
    $langInstruction = "1. Respond in clear, friendly, precise and concise English.";
    $notFoundMsg = "3. If the information is not in the notes, politely state that you could not find any record of it in current notes.";
} else {
    $langInstruction = "1. Responda em português de forma clara, amigável, precisa e concisa.";
    $notFoundMsg = "3. Se a informação não constar nas notas, informe educadamente que você não encontrou esse registro nas anotações atuais.";
}

$systemPrompt = <<<PROMPT
Você é a "Memória Digital" do Keep AI, um assistente de produtividade pessoal inteligente.
O usuário está conversando com você para tirar dúvidas sobre as próprias notas, listas, eventos e compromissos.

NOTAS DO USUÁRIO:
$notesBlob

INSTRUÇÕES:
$langInstruction
2. Quando encontrar a resposta em uma ou mais notas, cite o ID e o Título entre colchetes no formato [nota:ID:Título] (exemplo: [nota:42:Lista do Mercado]). O aplicativo transformará isso em links clicáveis para abrir a nota.
$notFoundMsg
4. Você pode sugerir ideias ou pequenos resumos baseados no que o usuário anotou.
PROMPT;

$history = $body['history'] ?? [];
$messages = [
    ['role' => 'system', 'content' => $systemPrompt]
];

// Adiciona até 4 mensagens anteriores de histórico para manter o contexto
if (is_array($history)) {
    $slice = array_slice($history, -4);
    foreach ($slice as $msg) {
        if (!empty($msg['role']) && !empty($msg['content']) && in_array($msg['role'], ['user', 'assistant'])) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => mb_substr((string)$msg['content'], 0, 500)
            ];
        }
    }
}

$messages[] = ['role' => 'user', 'content' => $query];

$requestBody = json_encode([
    'model'       => OPENAI_MODEL,
    'max_tokens'  => 600,
    'temperature' => 0.4,
    'messages'    => $messages,
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

if ($curlError || $httpCode !== 200) {
    jsonError('Erro ao consultar a IA da Memória Digital. Tente novamente.', 502);
}

$aiData = json_decode($response, true);
$reply  = $aiData['choices'][0]['message']['content'] ?? 'Desculpe, não consegui processar a resposta.';

// Deduzir 1 crédito (isentando contas VIP de desenvolvimento)
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
    'answer'            => $reply,
    'credits_used'      => CREDITS_PER_AI_CALL,
    'credits_remaining' => $creditsRemaining,
]);
