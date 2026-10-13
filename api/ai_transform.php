<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

/**
 * POST /api/ai_transform.php
 * Ações de transformação inteligente dentro da nota:
 * - improve: Melhora a clareza, coesão e corrige gramática
 * - to_checklist: Transforma texto livre em lista estruturada de tarefas/itens
 * - summarize: Resume o texto em tópicos concisos
 * - translate: Traduz o texto (ex: para Inglês ou Espanhol)
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
    jsonError('Créditos insuficientes. Recarregue para usar as transformações de IA.', 402);
}

$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$action = trim($body['action'] ?? '');
$text   = trim($body['text'] ?? '');
$lang   = trim($body['target_lang'] ?? 'en');

if ($text === '') {
    jsonError('Texto para transformação não fornecido.');
}

if (!in_array($action, ['improve', 'to_checklist', 'summarize', 'translate'])) {
    jsonError('Ação de transformação inválida.');
}

$systemPrompt = match ($action) {
    'improve' => "Você é um assistente de escrita de alto nível. Seu objetivo é aprimorar o texto fornecido pelo usuário, corrigindo erros gramaticais e ortográficos, melhorando a pontuação e fluidez, sem alterar o sentido original. Retorne APENAS o texto aprimorado, sem introduções ou explicações.",
    'to_checklist' => "Você é um organizador de tarefas. Converta o texto do usuário em uma lista de itens diretos e claros para checklist. Retorne APENAS um array JSON de strings no formato [\"item 1\", \"item 2\", ...], sem markdown ou explicações.",
    'summarize' => "Você é um especialista em síntese. Crie um resumo objetivo e estruturado em tópicos (bullet points com hífen) das informações mais importantes do texto. Retorne APENAS os tópicos, sem introduções ou cumprimentos.",
    'translate' => "Você é um tradutor fluente. Traduza o texto do usuário com precisão natural para o idioma alvo: " . ($lang === 'es' ? 'Espanhol' : 'Inglês') . ". Retorne APENAS o texto traduzido, sem introduções ou explicações."
};

$requestBody = json_encode([
    'model'       => OPENAI_MODEL,
    'max_tokens'  => 800,
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

if ($curlError || $httpCode !== 200) {
    jsonError('Falha ao processar transformação com IA.', 502);
}

$aiData = json_decode($response, true);
$rawResult = trim($aiData['choices'][0]['message']['content'] ?? '');

$result = $rawResult;
if ($action === 'to_checklist') {
    // Tenta decodificar JSON se o modelo retornou array JSON
    $parsed = json_decode($rawResult, true);
    if (is_array($parsed)) {
        $result = $parsed;
    } else {
        // Fallback: quebra por linhas
        $lines = array_filter(array_map('trim', explode("\n", $rawResult)));
        $items = [];
        foreach ($lines as $line) {
            $cleaned = ltrim($line, "-*•0123456789.) ");
            if ($cleaned) $items[] = $cleaned;
        }
        $result = !empty($items) ? $items : [$text];
    }
}

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
    'action'            => $action,
    'result'            => $result,
    'credits_used'      => CREDITS_PER_AI_CALL,
    'credits_remaining' => $creditsRemaining,
]);
