<?php
declare(strict_types=1);

/**
 * POST /api/mp_webhook.php
 * Mercado Pago IPN (Instant Payment Notification) handler.
 * Verifies payment status and credits user's account automatically.
 */

// Load config WITHOUT the CORS/JSON headers (webhook must return plain 200)
define('BASE_DIR', dirname(__DIR__));
define('DB_PATH', BASE_DIR . '/database/keepai.db');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Mercado Pago sends notifications like:
// {"type":"payment","data":{"id":"1234567890"}}
// Or as query params: ?topic=payment&id=1234567890

$body      = json_decode(file_get_contents('php://input'), true) ?? [];

// Optional Mercado Pago x-signature HMAC-SHA256 verification when MP_WEBHOOK_SECRET is set
if (defined('MP_WEBHOOK_SECRET') && MP_WEBHOOK_SECRET !== '') {
    $_mpPid = (string) ($body['data']['id'] ?? ($_GET['data_id'] ?? ($_GET['id'] ?? '')));
    $_xSig  = (string) ($_SERVER['HTTP_X_SIGNATURE'] ?? '');
    $_xReq  = (string) ($_SERVER['HTTP_X_REQUEST_ID'] ?? '');
    $_ts = ''; $_v1 = '';
    foreach (explode(',', $_xSig) as $_part) {
        $_kv = explode('=', trim($_part), 2);
        if (count($_kv) === 2) { if ($_kv[0] === 'ts') $_ts = $_kv[1]; if ($_kv[0] === 'v1') $_v1 = $_kv[1]; }
    }
    $_exp = hash_hmac('sha256', "id:{$_mpPid};request-id:{$_xReq};ts:{$_ts};", MP_WEBHOOK_SECRET);
    if ($_mpPid !== '' && ($_v1 === '' || !hash_equals($_exp, $_v1))) { http_response_code(401); exit('Invalid signature'); }
}
$topic     = $body['type'] ?? ($_GET['topic'] ?? '');
$paymentId = $body['data']['id'] ?? ($_GET['id'] ?? null);

if ($topic !== 'payment' || !$paymentId) {
    // Not a payment notification — acknowledge and exit
    http_response_code(200);
    echo 'OK';
    exit;
}

// Fetch payment from Mercado Pago to verify status
$ch = curl_init("https://api.mercadopago.com/v1/payments/{$paymentId}");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . MP_ACCESS_TOKEN,
    ],
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => true,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(200); // Always 200 to prevent MP retries
    exit;
}

$payment = json_decode($response, true);

if (($payment['status'] ?? '') !== 'approved') {
    // Payment not approved yet — update status if it changed
    $pdo = Database::get();
    $pdo->prepare('UPDATE transactions SET status = ? WHERE mp_payment_id = ?')
        ->execute([$payment['status'] ?? 'pending', (string) $paymentId]);
    http_response_code(200);
    echo 'OK';
    exit;
}

// Payment approved — process credits
$userId       = $payment['external_reference'] ?? null;
$creditsToAdd = (int) ($payment['metadata']['credits'] ?? 0);
$label        = $payment['metadata']['package_label'] ?? 'Recarga PIX';
$amountBRL    = (float) ($payment['transaction_amount'] ?? 0);

if (!$userId || $creditsToAdd <= 0) {
    http_response_code(200);
    exit;
}

$pdo = Database::get();

$pdo->exec('PRAGMA busy_timeout = 5000');
$pdo->exec('BEGIN IMMEDIATE');
try {
// Idempotency check — don't process the same payment twice
$checkStmt = $pdo->prepare('SELECT status FROM transactions WHERE mp_payment_id = ?');
$checkStmt->execute([(string) $paymentId]);
$existing = $checkStmt->fetch();

if ($existing && $existing['status'] === 'approved') {
    // Already processed
    $pdo->exec('COMMIT');
    http_response_code(200);
    echo 'OK';
    exit;
}

// Add credits to user
$pdo->prepare('UPDATE users SET credits = credits + ?, updated_at = datetime("now") WHERE id = ?')
    ->execute([$creditsToAdd, $userId]);

// Update or insert transaction record
if ($existing) {
    $pdo->prepare('UPDATE transactions SET status = "approved", credits_added = ?, amount_brl = ? WHERE mp_payment_id = ?')
        ->execute([$creditsToAdd, $amountBRL, (string) $paymentId]);
} else {
    $pdo->prepare('
        INSERT INTO transactions (user_id, mp_payment_id, package_label, amount_brl, credits_added, status)
        VALUES (?, ?, ?, ?, ?, "approved")
    ')->execute([$userId, (string) $paymentId, $label, $amountBRL, $creditsToAdd]);
}

$pdo->exec('COMMIT');
} catch (Throwable $_e) {
    if ($pdo->inTransaction()) { $pdo->exec('ROLLBACK'); }
    http_response_code(500); exit;
}

// Always respond 200 to Mercado Pago
http_response_code(200);
echo 'OK';
