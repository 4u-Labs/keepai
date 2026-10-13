<?php
declare(strict_types=1);

/**
 * POST /api/paypal_webhook.php
 * PayPal Webhook Handler (Asynchronous Instant Notification)
 * Guarantees credits are added even if the user closes the browser before onApprove completes.
 */

define('BASE_DIR', dirname(__DIR__));
define('DB_PATH', BASE_DIR . '/database/keepai.db');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

$rawBody = file_get_contents('php://input');
$body = json_decode($rawBody, true) ?? [];

$eventType = $body['event_type'] ?? '';
$resource  = $body['resource'] ?? [];

// Only process completed capture payments
if ($eventType !== 'PAYMENT.CAPTURE.COMPLETED') {
    http_response_code(200);
    echo 'IGNORED';
    exit;
}

$status = $resource['status'] ?? '';
if ($status !== 'COMPLETED') {
    http_response_code(200);
    echo 'NOT_COMPLETED';
    exit;
}

$captureId = $resource['id'] ?? '';
$customId  = $resource['custom_id'] ?? '';
$amountUSD = (float) ($resource['amount']['value'] ?? 0);

$userId = null;
$creditsToAdd = 0;

if (!empty($customId)) {
    $parts = explode(':', $customId);
    if (count($parts) >= 3) {
        $userId = (int) $parts[0];
        $creditsToAdd = (int) $parts[2];
    }
}

if (!$userId || $creditsToAdd <= 0) {
    http_response_code(200);
    echo 'NO_USER_OR_CREDITS';
    exit;
}

$pdo = Database::get();

// Idempotency check: check if already approved
$checkStmt = $pdo->prepare('SELECT status FROM transactions WHERE mp_payment_id = ?');
$checkStmt->execute(['PAYPAL-' . $captureId]);
$existing = $checkStmt->fetch();

if ($existing && $existing['status'] === 'approved') {
    http_response_code(200);
    echo 'ALREADY_PROCESSED';
    exit;
}

// Add credits to user
$pdo->prepare('UPDATE users SET credits = credits + ?, updated_at = datetime("now") WHERE id = ?')
    ->execute([$creditsToAdd, $userId]);

// Update or insert transaction
if ($existing) {
    $pdo->prepare('UPDATE transactions SET status = "approved", credits_added = ? WHERE mp_payment_id = ?')
        ->execute([$creditsToAdd, 'PAYPAL-' . $captureId]);
} else {
    $pdo->prepare('
        INSERT INTO transactions (user_id, mp_payment_id, package_label, amount_brl, credits_added, status)
        VALUES (?, ?, ?, ?, ?, "approved")
    ')->execute([$userId, 'PAYPAL-' . $captureId, "PayPal Webhook ({$creditsToAdd} credits)", $amountUSD, $creditsToAdd]);
}

http_response_code(200);
echo 'OK';
