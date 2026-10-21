<?php
// Seitenanfang inkl. Navigation.
// Vor dem Einbinden setzen: $loc (Pfad zum Hauptverzeichnis), $title, $page (aktiver Menüpunkt),
// optional $description und $noindex.

require_once __DIR__ . '/icons.php';

$pageTitle = isset($title) ? "$title | Handler Fit" : 'Handler Fit, Personal Trainer';
$description ??= 'Handler Fit, Online Coaching und Personal Training: individuelle Trainingspläne, Ernährungstipps und persönliche Betreuung für deine Ziele.';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <?php if (!empty($noindex)): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>
    <link rel="icon" href="<?= $loc ?>assets/images/handler.png">
    <link rel="stylesheet" href="<?= $loc ?>assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $loc ?>css/main.css">
</head>
<body>
<?php require __DIR__ . '/navbar.php'; ?>
<main>
