<?php
// Vorlage: als config.php kopieren und Werte anpassen. config.php wird nicht ins Git eingecheckt.

return [
    // Öffentliche Adresse der Seite ohne abschließenden Slash, wird für Links in E-Mails verwendet
    'base_url' => 'http://localhost:8000',

    'timezone' => 'Europe/Berlin',

    // An diese Adresse gehen Kontaktanfragen und Terminanmeldungen
    'office_email' => 'office@sahrajoy.com',

    // Adresse, die auf der Website angezeigt wird
    'display_email' => 'office@handlerfit.com',

    'mail' => [
        // 'log'  = E-Mails nur in db/mail.log schreiben (für die lokale Entwicklung)
        // 'smtp' = über das Postfach unten versenden (für den Live-Betrieb empfohlen)
        // 'mail' = PHP mail() des Servers verwenden
        'transport' => 'log',
        'from' => 'office@sahrajoy.com',
        'from_name' => 'Handler Fit',
        'smtp_host' => '',
        'smtp_port' => 587,
        'smtp_user' => '',
        'smtp_pass' => '',
        'smtp_secure' => 'tls', // 'tls' (Port 587) oder 'ssl' (Port 465)
    ],

    // Passwort für /admin, Hash erzeugen mit:
    // php -r "echo password_hash('dein-passwort', PASSWORD_DEFAULT);"
    'admin_password_hash' => '',

    'db_path' => __DIR__ . '/db/handlerfit.sqlite',
];
