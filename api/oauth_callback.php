<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

/**
 * GET /api/oauth_callback.php
 * Handles the redirect from Google OAuth 2.0.
 * Exchanges 'code' for tokens and saves them to the DB.
 */

header('Content-Type: text/html; charset=utf-8');

$code  = $_GET['code'] ?? null;
$error = $_GET['error'] ?? null;
$state = $_GET['state'] ?? null; // Contains base64 encoded {uid, note}

if ($error) {
    die("Erro na autorização do Google: " . htmlspecialchars($error));
}

if (!$code || !$state) {
    die("Requisição inválida (falta code ou state).");
}

$stateData = json_decode(base64_decode($state), true);
$uid       = $stateData['uid'] ?? null;

if (!$uid) {
    die("Estado inválido (falta UID do usuário).");
}

// Exchange code for token
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'code'          => $code,
        'client_id'     => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri'  => GOOGLE_REDIRECT_URI,
        'grant_type'    => 'authorization_code',
    ]),
    CURLOPT_TIMEOUT        => 15,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    die("Erro ao trocar código por token (HTTP $httpCode). " . $response);
}

$data = json_decode($response, true);
$token = [
    'access_token'  => $data['access_token'],
    'refresh_token' => $data['refresh_token'] ?? null, // Only sent on first time or with prompt=consent
    'expires_at'    => time() + ($data['expires_in'] ?? 3600),
];

// Save token to DB
$pdo = Database::get();
$pdo->prepare('UPDATE users SET google_token = ?, updated_at = datetime("now") WHERE id = ?')
    ->execute([json_encode($token), $uid]);

// Success! Redirect back to the app with a success message
echo "
    <div style='font-family: sans-serif; text-align: center; padding-top: 50px;'>
        <h2 style='color: #34d399;'>✅ Autorização concluída!</h2>
        <p>Você já pode fechar esta aba e voltar ao Keep AI para criar seu evento.</p>
        <script>
            setTimeout(() => window.close(), 3000);
        </script>
    </div>
";
