<?php
declare(strict_types=1);

// Keep AI / 4uLabs - Secure Central AI Vision Proxy Endpoint
// Deducts 1 credit per call from the user's shared Keep AI SQLite account.

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed. Only POST requests are accepted."]);
    exit;
}

try {
    require_once __DIR__ . '/database.php';

    // 1. Get Payload and Token (Supports Header and JSON Body)
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    $token = '';

    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    }

    // Fallback to JSON payload token
    if (!$token && !empty($input['token'])) {
        $token = $input['token'];
    }

    if (!$token) {
        http_response_code(401);
        echo json_encode(["error" => "Token de autorização Bearer obrigatório."]);
        exit;
    }

    $pdo = Database::get();

    // 2. Validate token and user credits in keepai.db
    $stmt = $pdo->prepare("SELECT id, email, credits FROM users WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(403);
        echo json_encode(["error" => "Token inválido ou expirado. Faça login novamente."]);
        exit;
    }

    $credits = (int)$user['credits'];
    $isVip = in_array(strtolower($user['email'] ?? ''), ['fbr4g4@gmail.com', 'fb4g4@gmail.com']);
    if ($isVip) {
        $credits = 999999;
    }

    if ($credits < 1) {
        http_response_code(402);
        echo json_encode([
            "error" => "user_limit_reached",
            "credits" => $credits,
            "message" => "Créditos insuficientes. Adquira mais créditos para continuar usando os recursos de IA."
        ]);
        exit;
    }

    // 3. Get vision inputs
    $prompt = trim($input['prompt'] ?? '');
    $rawImage = trim($input['image'] ?? '');

    if (!$prompt || !$rawImage) {
        http_response_code(400);
        echo json_encode(["error" => "Payload incompleto: 'prompt' e 'image' (base64) são obrigatórios."]);
        exit;
    }

    // Format image URL (supports raw base64 or full data URL)
    if (str_starts_with($rawImage, 'data:image/')) {
        $imageUrl = $rawImage;
    } else {
        $imageUrl = 'data:image/jpeg;base64,' . $rawImage;
    }

    // 4. Call OpenAI API using the Vision model (gpt-4o-mini) and Keep AI Key
    $openaiUrl = "https://api.openai.com/v1/chat/completions";
    $postFields = [
        "model" => OPENAI_MODEL,
        "messages" => [
            [
                "role" => "user",
                "content" => [
                    ["type" => "text", "text" => $prompt],
                    ["type" => "image_url", "image_url" => ["url" => $imageUrl]]
                ]
            ]
        ],
        "max_tokens" => 1000
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $openaiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postFields),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . OPENAI_API_KEY
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        http_response_code(503);
        echo json_encode(["error" => "Falha na conexão com o servidor de IA. Tente novamente."]);
        exit;
    }

    if ($httpCode !== 200) {
        $errorDetails = json_decode($response, true);
        http_response_code(502);
        echo json_encode([
            "error" => "Erro ao processar na OpenAI (HTTP " . $httpCode . ").",
            "details" => $errorDetails
        ]);
        exit;
    }

    $responseData = json_decode($response, true);
    $aiText = $responseData['choices'][0]['message']['content'] ?? '';

    // 5. Update credits balance
    if ($isVip) {
        $newCredits = 999999;
    } else {
        $newCredits = $credits - 1;
        $stmt = $pdo->prepare("UPDATE users SET credits = ? WHERE id = ?");
        $stmt->execute([$newCredits, $user['id']]);
    }

    // Opcional: registrar transação de debito se desejado no banco Keep AI
    // No Keep AI a tabela 'transactions' é principalmente para recargas PIX. Por simplicidade não inserimos transações de débito lá.

    // 6. Return response
    echo json_encode([
        "success" => true,
        "text" => $aiText,
        "credits_remaining" => $newCredits
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Erro global técnico detectado no servidor: " . $e->getMessage()
    ]);
}
?>
