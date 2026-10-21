<?php
// Vorlage: als config.php kopieren und Werte anpassen. config.php wird nicht ins Git eingecheckt.
// Gibt es keine config.php (z. B. auf Render), wird diese Datei verwendet und die Werte
// kommen aus Umgebungsvariablen.

$env = fn(string $name, $default = null) => getenv($name) !== false && getenv($name) !== '' ? getenv($name) : $default;

return [
    // Öffentliche Adresse der Seite ohne abschließenden Slash, wird für Links in E-Mails verwendet
    'base_url' => $env('BASE_URL', $env('RENDER_EXTERNAL_URL', 'http://localhost:8000')),

    'timezone' => 'Europe/Berlin',

    // Demo-Modus: keine echten E-Mails, stattdessen Ansicht im Adminbereich,
    // Beispieltermine und Hinweis-Banner. Für Präsentationen gedacht.
    'demo' => (bool)$env('DEMO_MODE', false),

    // An diese Adresse gehen Kontaktanfragen und Terminanmeldungen
    'office_email' => $env('OFFICE_EMAIL', 'office@sahrajoy.com'),

    // Adresse, die auf der Website angezeigt wird
    'display_email' => $env('DISPLAY_EMAIL', 'office@handlerfit.com'),

    'mail' => [
        // 'log'  = E-Mails nur in db/mail.log schreiben (für die lokale Entwicklung)
        // 'smtp' = über das Postfach unten versenden (für den Live-Betrieb empfohlen)
        // 'mail' = PHP mail() des Servers verwenden
        'transport' => $env('MAIL_TRANSPORT', 'log'),
        'from' => $env('MAIL_FROM', 'office@sahrajoy.com'),
        'from_name' => 'Handler Fit',
        'smtp_host' => $env('SMTP_HOST', ''),
        'smtp_port' => (int)$env('SMTP_PORT', 587),
        'smtp_user' => $env('SMTP_USER', ''),
        'smtp_pass' => $env('SMTP_PASS', ''),
        'smtp_secure' => $env('SMTP_SECURE', 'tls'), // 'tls' (Port 587) oder 'ssl' (Port 465)
    ],

    // Passwort für /admin als Hash, erzeugen mit:
    // php -r "echo password_hash('dein-passwort', PASSWORD_DEFAULT);"
    'admin_password_hash' => $env('ADMIN_PASSWORD_HASH', ''),

    // Alternativ Klartext-Passwort, nur für den Demo-Modus gedacht (wird auf der Login-Seite angezeigt)
    'admin_password' => $env('ADMIN_PASSWORD', ''),

    'db_path' => __DIR__ . '/db/handlerfit.sqlite',
];
