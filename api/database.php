<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Database connection singleton + schema bootstrap.
 */
class Database
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $dir = dirname(DB_PATH);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $pdo = new PDO('sqlite:' . DB_PATH);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec('PRAGMA journal_mode=WAL');
            $pdo->exec('PRAGMA foreign_keys=ON');

            self::bootstrap($pdo);
            self::$instance = $pdo;
        }

        return self::$instance;
    }

    private static function bootstrap(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id             INTEGER PRIMARY KEY AUTOINCREMENT,
                username       TEXT UNIQUE NOT NULL,
                email          TEXT,
                password_hash  TEXT NOT NULL,
                display_name   TEXT,
                photo_url      TEXT,
                credits        INTEGER NOT NULL DEFAULT 10,
                token          TEXT,
                created_at     TEXT NOT NULL DEFAULT (datetime('now')),
                updated_at     TEXT NOT NULL DEFAULT (datetime('now'))
            )
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS notes (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id     INTEGER NOT NULL,
                title       TEXT,
                content     TEXT,
                type        TEXT NOT NULL DEFAULT 'generic',
                color       TEXT NOT NULL DEFAULT '#1e293b',
                pinned      INTEGER NOT NULL DEFAULT 0,
                archived    INTEGER NOT NULL DEFAULT 0,
                position    INTEGER NOT NULL DEFAULT 0,
                extra_json  TEXT,
                created_at  TEXT NOT NULL DEFAULT (datetime('now')),
                updated_at  TEXT NOT NULL DEFAULT (datetime('now')),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        // Migration: Add position if it doesn't exist (for existing databases)
        try {
            $pdo->exec("ALTER TABLE notes ADD COLUMN position INTEGER NOT NULL DEFAULT 0");
        } catch (Exception $e) {
            // Column already exists, ignore
        }

        // Migration: Add in_trash if it doesn't exist
        try {
            $pdo->exec("ALTER TABLE notes ADD COLUMN in_trash INTEGER NOT NULL DEFAULT 0");
        } catch (Exception $e) {
            // Column already exists, ignore
        }

        $pdo->exec("
            CREATE INDEX IF NOT EXISTS idx_notes_user ON notes(user_id, in_trash, archived, pinned, position)
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS transactions (
                id             INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id        INTEGER NOT NULL,
                mp_payment_id  TEXT UNIQUE,
                package_label  TEXT,
                amount_brl     REAL,
                credits_added  INTEGER NOT NULL DEFAULT 0,
                status         TEXT NOT NULL DEFAULT 'pending',
                created_at     TEXT NOT NULL DEFAULT (datetime('now')),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS rate_limits (
                identifier TEXT PRIMARY KEY,
                last_call  TEXT NOT NULL,
                call_count INTEGER NOT NULL DEFAULT 1
            )
        ");
    }
}

/**
 * Helper: send JSON response and exit.
 */
function jsonResponse(mixed $data, int $status = 200): never
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Helper: send error JSON response and exit.
 */
function jsonError(string $message, int $status = 400): never
{
    jsonResponse(['error' => $message], $status);
}

if (!function_exists('getallheaders')) {
    function getallheaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * Helper: verify custom Auth token from header.
 */
function verifyAuthToken(): array
{
    $headers = getallheaders();
    $auth    = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    $token   = str_replace('Bearer ', '', $auth);

    if (empty($token)) {
        jsonError('Unauthorized: Token missing.', 401);
    }

    $pdo = Database::get();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE token = ?');
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        jsonError('Unauthorized: Invalid token.', 401);
    }

    // Dev VIP: Assegura créditos ilimitados para fbr4g4@gmail.com e fb4g4@gmail.com
    $email = strtolower($user['email'] ?? '');
    if (in_array($email, ['fbr4g4@gmail.com', 'fb4g4@gmail.com'])) {
        if ((int)$user['credits'] < 999999) {
            $pdo->prepare('UPDATE users SET credits = 999999, updated_at = datetime("now") WHERE id = ?')
                ->execute([$user['id']]);
            $user['credits'] = 999999;
        }
    }

    return $user;
}

/**
 * Helper: simple rate limiter per user UID.
 */
function checkRateLimit(string $identifier): void
{
    $pdo = Database::get();
    $now = time();

    $stmt = $pdo->prepare('SELECT last_call FROM rate_limits WHERE identifier = ?');
    $stmt->execute([$identifier]);
    $row = $stmt->fetch();

    if ($row) {
        $lastCall = (int) strtotime($row['last_call']);
        if (($now - $lastCall) < RATE_LIMIT_SECONDS) {
            jsonError('Too many requests. Please wait a moment.', 429);
        }
        $pdo->prepare('UPDATE rate_limits SET last_call = datetime("now"), call_count = call_count + 1 WHERE identifier = ?')
            ->execute([$identifier]);
    } else {
        $pdo->prepare('INSERT INTO rate_limits (identifier, last_call) VALUES (?, datetime("now"))')
            ->execute([$identifier]);
    }
}
