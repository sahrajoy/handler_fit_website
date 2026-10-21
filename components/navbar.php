<?php
// Hauptmenü. Die Unterpunkte springen zu den Abschnitten der jeweiligen Seite.

$menu = [
    'online' => [
        'label' => 'Online Coaching',
        'href' => 'onlinecoaching.php',
        'children' => [
            'onlinecoaching.php#was-ist-online-coaching' => 'Was ist Online Coaching?',
            'onlinecoaching.php#abnehm-coaching' => 'Abnehm-Coaching',
            'onlinecoaching.php#fitness-ernaehrungstipps' => 'Fitness- & Ernährungstipps',
            'onlinecoaching.php#trainingsplaene' => 'Trainingspläne',
        ],
    ],
    'personal' => [
        'label' => 'Personal Training',
        'href' => 'personaltraining.php',
        'children' => [
            'personaltraining.php#was-ist-personal-training' => 'Was ist Personal Training?',
            'personaltraining.php#hypertrophie' => 'Hypertrophie (Muskelaufbau)',
            'personaltraining.php#krafttraining' => 'Krafttraining',
            'personaltraining.php#bauch-beine-po' => 'Bauch-Beine-Po Workout',
        ],
    ],
    'booking' => ['label' => 'Termine', 'href' => 'booking.php'],
    'about' => ['label' => 'Über mich', 'href' => 'about.php'],
];
$page ??= '';
?>
<nav class="navbar navbar-expand-lg sticky-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="<?= $loc ?>index.php">
            <img src="<?= $loc ?>assets/images/handler.png" alt="Handler Personal Trainer" id="logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Menü öffnen">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php foreach ($menu as $key => $item):
                    $active = $page === $key ? ' active' : '';
                    $current = $page === $key ? ' aria-current="page"' : '';
                    if (isset($item['children'])): ?>
                        <li class="nav-item dropdown">
                            <div class="d-flex align-items-center justify-content-between">
                                <a class="nav-link<?= $active ?>"<?= $current ?> href="<?= $loc . $item['href'] ?>"><?= e($item['label']) ?></a>
                                <button type="button" class="nav-link dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Untermenü <?= e($item['label']) ?></span>
                                </button>
                            </div>
                            <ul class="dropdown-menu">
                                <?php foreach ($item['children'] as $href => $label): ?>
                                    <li><a class="dropdown-item" href="<?= $loc . $href ?>"><?= e($label) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link<?= $active ?>"<?= $current ?> href="<?= $loc . $item['href'] ?>"><?= e($item['label']) ?></a>
                        </li>
                    <?php endif;
                endforeach; ?>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary btn-sm px-3" href="<?= $loc ?>contact.php?anliegen=erstgespraech">Get in touch</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-icon" href="https://www.instagram.com/handler_fit/" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('instagram', 24) ?></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
