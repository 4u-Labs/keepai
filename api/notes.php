<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

/**
 * Notes API (Stateless)
 * Local-First + Google Drive Master Storage Architecture.
 * Zero user notes or backups are stored on the Hostinger server.
 */

$user = verifyAuthToken();
$uid  = (int) $user['id'];

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : (int)(microtime(true) * 1000);

if ($method === 'GET') {
    jsonResponse(['notes' => []]);
}

jsonResponse([
    'success' => true,
    'id'      => $id,
    'message' => 'Operação processada localmente.'
]);
