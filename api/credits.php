<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

/**
 * GET  /api/credits.php  → return balance + transactions
 * POST /api/credits.php  → developer backdoor credit injection
 */

$user = verifyAuthToken();
$uid = (int)$user['id'];
$pdo = Database::get();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->prepare('SELECT credits FROM users WHERE id = ?');
    $stmt->execute([$uid]);
    $user = $stmt->fetch();

    $txStmt = $pdo->prepare('
        SELECT package_label, amount_brl, credits_added, status, created_at
        FROM transactions
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 20
    ');
    $txStmt->execute([$uid]);
    $transactions = $txStmt->fetchAll();

    jsonResponse([
        'credits'      => (int) ($user['credits'] ?? 0),
        'transactions' => $transactions,
    ]);
}

if ($method === 'POST') {
    $body     = json_decode(file_get_contents('php://input'), true) ?? [];
    $password = $body['dev_password'] ?? '';
    $amount   = (int) ($body['amount'] ?? DEV_CREDIT_BONUS);

    // Verify developer password
    if (!password_verify($password, DEV_PASSWORD_HASH)) {
        jsonError('Senha inválida.', 403);
    }

    if ($amount < 1 || $amount > 9999) {
        jsonError('Quantidade inválida.');
    }

    $pdo->prepare('UPDATE users SET credits = credits + ?, updated_at = datetime("now") WHERE id = ?')
        ->execute([$amount, $uid]);

    // Log it
    $pdo->prepare('
        INSERT INTO transactions (user_id, mp_payment_id, package_label, amount_brl, credits_added, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ')->execute([$uid, 'DEV-' . time(), 'Developer Bonus', 0, $amount, 'approved']);

    $stmt = $pdo->prepare('SELECT credits FROM users WHERE id = ?');
    $stmt->execute([$uid]);
    $credits = (int) $stmt->fetch()['credits'];

    jsonResponse([
        'success'        => true,
        'credits_added'  => $amount,
        'credits_total'  => $credits,
    ]);
}

jsonError('Method not allowed.', 405);
