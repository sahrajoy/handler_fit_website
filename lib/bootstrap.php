<?php
// Wird von jeder Seite als Erstes eingebunden: Konfiguration, Session, Datenbank und Hilfsfunktionen.

$configFile = __DIR__ . '/../config.php';
if (!file_exists($configFile)) {
    http_response_code(500);
    exit('config.php fehlt, bitte config.sample.php nach config.php kopieren und anpassen.');
}
$GLOBALS['config'] = require $configFile;

date_default_timezone_set(config('timezone') ?? 'Europe/Berlin');

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/mailer.php';
require_once __DIR__ . '/bookings.php';

// Liest einen Konfigurationswert, verschachtelte Schlüssel mit Punkt: config('mail.transport')
function config(string $key)
{
    $value = $GLOBALS['config'];
    foreach (explode('.', $key) as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return null;
        }
        $value = $value[$part];
    }
    return $value;
}

// HTML-sichere Ausgabe
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Absolute URL für Links in E-Mails
function absolute_url(string $path): string
{
    return rtrim(config('base_url'), '/') . '/' . ltrim($path, '/');
}

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('sqlite:' . config('db_path'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA foreign_keys = ON');
        $pdo->exec('CREATE TABLE IF NOT EXISTS trainings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT NOT NULL DEFAULT \'\',
            location TEXT NOT NULL DEFAULT \'\',
            starts_at TEXT NOT NULL,
            duration_min INTEGER NOT NULL DEFAULT 60,
            capacity INTEGER NOT NULL DEFAULT 1,
            created_at TEXT NOT NULL
        )');
        $pdo->exec('CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            training_id INTEGER NOT NULL REFERENCES trainings(id) ON DELETE CASCADE,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT NOT NULL DEFAULT \'\',
            message TEXT NOT NULL DEFAULT \'\',
            status TEXT NOT NULL DEFAULT "pending",
            admin_token TEXT NOT NULL UNIQUE,
            customer_token TEXT NOT NULL UNIQUE,
            created_at TEXT NOT NULL,
            decided_at TEXT
        )');
    }
    return $pdo;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid(): bool
{
    return isset($_POST['csrf']) && hash_equals(csrf_token(), (string)$_POST['csrf']);
}

// Einmalige Meldung für die nächste Seite (nach einem Redirect)
function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function render_flash(): string
{
    $html = '';
    foreach ($_SESSION['flash'] ?? [] as $f) {
        $html .= '<div class="alert alert-' . e($f['type']) . '" role="alert">' . e($f['message']) . '</div>';
    }
    unset($_SESSION['flash']);
    return $html;
}

// Spam-Schutz für öffentliche Formulare: verstecktes Feld muss leer bleiben,
// und zwischen Seitenaufruf und Absenden müssen ein paar Sekunden liegen.
function spam_fields(): string
{
    return '<div class="hp-field" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>'
        . '<input type="hidden" name="form_time" value="' . time() . '">';
}

function looks_like_spam(): bool
{
    $elapsed = time() - (int)($_POST['form_time'] ?? 0);
    return !empty($_POST['website']) || $elapsed < 3;
}

// Einzeiliger Text ohne Zeilenumbrüche (z. B. für Namen in Betreffzeilen)
function clean_line(?string $value, int $max = 200): string
{
    $value = trim(preg_replace('/\s+/u', ' ', $value ?? ''));
    return mb_substr($value, 0, $max);
}

function clean_text(?string $value, int $max = 5000): string
{
    return mb_substr(trim(str_replace("\r\n", "\n", $value ?? '')), 0, $max);
}

const WEEKDAYS_DE = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
const MONTHS_DE = [1 => 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

// "Dienstag, 4. November 2026, 18:00 Uhr"
function format_datetime_de(string $datetime): string
{
    $ts = strtotime($datetime);
    return WEEKDAYS_DE[(int)date('w', $ts)] . ', ' . date('j', $ts) . '. ' . MONTHS_DE[(int)date('n', $ts)]
        . ' ' . date('Y', $ts) . ', ' . date('H:i', $ts) . ' Uhr';
}
