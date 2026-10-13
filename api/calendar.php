<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

/**
 * POST /api/calendar.php
 * Creates a Google Calendar event for the authenticated user.
 * Body: { title, description, date (YYYY-MM-DD), time (HH:MM), location? }
 *
 * Uses Google OAuth 2.0. On first call, redirects user to authorize.
 * Token is stored in the DB per user.
 */

// If Calendar is not configured, return graceful message
if (!GOOGLE_CLIENT_ID || !GOOGLE_CLIENT_SECRET) {
    jsonResponse([
        'success'  => false,
        'disabled' => true,
        'message'  => 'Google Calendar não configurado. Adicione as credenciais OAuth em api/config.php.',
    ]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed.', 405);
}

$user = verifyAuthToken();
$uid = (int) $user['id'];

$body        = json_decode(file_get_contents('php://input'), true) ?? [];
$title       = trim($body['title'] ?? 'Evento');
$description = trim($body['description'] ?? '');
$date        = $body['date'] ?? '';
$time        = $body['time'] ?? '09:00';
$location    = trim($body['location'] ?? '');

if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    jsonError('Data inválida. Use o formato YYYY-MM-DD.');
}

// Build ISO 8601 datetime
$startDateTime = "{$date}T{$time}:00-03:00";
$endDateTime   = date('Y-m-d\TH:i:sP', strtotime($startDateTime) + 3600); // 1 hour event

// Get stored Google OAuth token for this user
$pdo      = Database::get();
$tokenRow = $pdo->prepare('SELECT google_token FROM users WHERE id = ?');
$tokenRow->execute([$uid]);
$tokenData = $tokenRow->fetch();

if (!$tokenData || !$tokenData['google_token']) {
    // Need authorization — return auth URL
    $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
        'client_id'     => GOOGLE_CLIENT_ID,
        'redirect_uri'  => GOOGLE_REDIRECT_URI,
        'response_type' => 'code',
        'scope'         => 'https://www.googleapis.com/auth/calendar.events',
        'access_type'   => 'offline',
        'prompt'        => 'consent',
        'state'         => base64_encode(json_encode([
            'uid'   => $uid,
            'note'  => $body,
        ])),
    ]);

    jsonResponse([
        'success'      => false,
        'needs_auth'   => true,
        'auth_url'     => $authUrl,
        'message'      => 'Autorize o acesso ao Google Calendar para continuar.',
    ]);
}

$token = json_decode($tokenData['google_token'], true);

// Refresh token if expired
if (time() >= ($token['expires_at'] ?? 0)) {
    $refreshResponse = refreshGoogleToken($token['refresh_token']);
    if (!$refreshResponse) {
        jsonError('Falha ao renovar token do Google. Reautorize o Calendar.', 401);
    }
    $token = array_merge($token, $refreshResponse);
    $pdo->prepare('UPDATE users SET google_token = ? WHERE id = ?')
        ->execute([json_encode($token), $uid]);
}

// Create Calendar event
$eventPayload = [
    'summary'     => $title,
    'description' => $description,
    'location'    => $location,
    'start'       => ['dateTime' => $startDateTime, 'timeZone' => 'America/Sao_Paulo'],
    'end'         => ['dateTime' => $endDateTime,   'timeZone' => 'America/Sao_Paulo'],
    'reminders'   => ['useDefault' => true],
];

$ch = curl_init('https://www.googleapis.com/calendar/v3/calendars/primary/events');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($eventPayload),
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token['access_token'],
    ],
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => true,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 && $httpCode !== 201) {
    jsonError('Erro ao criar evento no Google Calendar. Tente novamente.', 502);
}

$event    = json_decode($response, true);
$eventUrl = $event['htmlLink'] ?? '';

jsonResponse([
    'success'   => true,
    'event_id'  => $event['id'] ?? '',
    'event_url' => $eventUrl,
    'message'   => 'Evento criado com sucesso no Google Calendar!',
]);

// ----------------------------------------------------------------

function refreshGoogleToken(string $refreshToken): ?array
{
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token',
        ]),
        CURLOPT_TIMEOUT        => 10,
    ]);
    $response = curl_exec($ch);
    $code     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 200) {
        return null;
    }

    $data = json_decode($response, true);
    return [
        'access_token' => $data['access_token'],
        'expires_at'   => time() + ($data['expires_in'] ?? 3600),
    ];
}
