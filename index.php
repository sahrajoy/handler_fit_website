<?php
require_once 'lib/bootstrap.php';
require_once 'components/sections.php';

$loc = '';
$page = 'home';
$title = null;

$offers = [
    [
        'title' => 'Online Coaching',
        'href' => 'onlinecoaching.php',
        'image' => 'imgOnline.png',
        'text' => 'Flexibel trainieren, wann und wo du willst, mit Plan, Betreuung und regelmäßigem Feedback.',
        'links' => [
            'onlinecoaching.php#abnehm-coaching' => 'Abnehm-Coaching',
            'onlinecoaching.php#fitness-ernaehrungstipps' => 'Fitness- & Ernährungstipps',
            'onlinecoaching.php#trainingsplaene' => 'Trainingspläne',
        ],
    ],
    [
        'title' => 'Personal Training',
        'href' => 'personaltraining.php',
        'image' => 'imgPersonal.png',
        'text' => 'Eins-zu-eins-Training mit voller Aufmerksamkeit für deine Technik, deine Ziele und deinen Fortschritt.',
        'links' => [
            'personaltraining.php#hypertrophie' => 'Hypertrophie (Muskelaufbau)',
            'personaltraining.php#krafttraining' => 'Krafttraining',
            'personaltraining.php#bauch-beine-po' => 'Bauch-Beine-Po Workout',
        ],
    ],
];

$steps = [
    ['icon' => 'noun-fitness-5568533.png', 'title' => '1. Erstgespräch', 'text' => 'Wir lernen uns kostenlos kennen, sprechen über deine Ziele, deinen Alltag und deine Erfahrung.'],
    ['icon' => 'noun-weight-5817075.png', 'title' => '2. Analyse & Plan', 'text' => 'Ich erstelle dir einen Plan, der zu dir passt, Training und Ernährung, realistisch und machbar.'],
    ['icon' => 'noun-fitness-4874119.png', 'title' => '3. Gemeinsam ans Ziel', 'text' => 'Wir trainieren, messen Fortschritte und passen den Plan laufend an, bis du dein Ziel erreichst.'],
];

$nextTrainings = upcoming_trainings(3);

require 'components/head.php';
?>

<!-- Hero -->
<section id="hero">
    <div class="hero-overlay">
        <div class="container text-center">
            <h1 class="hero-title">Dein Weg zu einem fitteren Leben</h1>
            <p class="hero-sub">mit einem persönlichen Fitness-Trainer, der dich motiviert und unterstützt!</p>
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                <a class="btn btn-primary btn-lg" href="onlinecoaching.php">Online Coaching</a>
                <a class="btn btn-primary btn-lg" href="personaltraining.php">Personal Training</a>
            </div>
        </div>
    </div>
</section>

<!-- Willkommen -->
<section id="welcome">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-7 py-5">
                <span class="kicker">Willkommen</span>
                <h2 class="display-title">in meiner Welt des leidenschaftlichen Trainings!</h2>
                <p>Als erfahrener Fitness-Trainer begleite ich dich auf deiner Fitnessreise, mit maßgeschneiderten
                    Trainingsmethoden, professioneller Anleitung und grenzenloser Motivation.</p>
                <p>Entdecke das volle Potenzial deines Körpers und erreiche deine ganz persönlichen Ziele.
                    Bist du bereit für die Veränderung? Lass uns gemeinsam starten!</p>
                <a class="link-arrow" href="about.php">Mehr über mich <?= icon('arrow', 18) ?></a>
            </div>
            <div class="col-lg-5 welcome-image">
                <img src="assets/images/about-pic.png" alt="Dein Trainer">
            </div>
        </div>
    </div>
</section>

<!-- Angebot -->
<section id="offert">
    <div class="container">
        <div class="text-center mb-5">
            <span class="kicker">Mein Angebot</span>
            <h2>Wie möchtest du trainieren?</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($offers as $offer): ?>
                <div class="col-md-6">
                    <div class="offer-card">
                        <a class="offer-image" href="<?= $offer['href'] ?>" style="background-image: url('assets/images/<?= $offer['image'] ?>')">
                            <span class="offer-title"><?= e($offer['title']) ?></span>
                            <span class="offer-hover"><?= e($offer['title']) ?></span>
                        </a>
                        <div class="offer-body">
                            <p><?= e($offer['text']) ?></p>
                            <ul class="offer-links">
                                <?php foreach ($offer['links'] as $href => $label): ?>
                                    <li><a href="<?= $href ?>"><?= e($label) ?> <?= icon('arrow', 16) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a class="btn btn-primary btn-lg px-5" href="contact.php?anliegen=erstgespraech">Hol dir dein kostenloses Erstgespräch</a>
        </div>
    </div>
</section>

<!-- So funktioniert's -->
<section class="py-5 my-lg-4">
    <div class="container">
        <div class="text-center mb-5">
            <span class="kicker">So funktioniert's</span>
            <h2>In drei Schritten zu deinem Ziel</h2>
        </div>
        <div class="row g-4 text-center">
            <?php foreach ($steps as $step): ?>
                <div class="col-md-4">
                    <div class="step">
                        <?= noun_icon($loc, $step['icon']) ?>
                        <h3><?= e($step['title']) ?></h3>
                        <p><?= e($step['text']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($nextTrainings): ?>
    <!-- Nächste Termine -->
    <section class="py-5 bg-sand">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
                <div>
                    <span class="kicker">Termine</span>
                    <h2 class="mb-0">Nächste Trainings</h2>
                </div>
                <a class="link-arrow" href="booking.php">Alle Termine <?= icon('arrow', 18) ?></a>
            </div>
            <div class="row g-4">
                <?php foreach ($nextTrainings as $t): ?>
                    <div class="col-md-4">
                        <?php require 'components/training_card.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php cta_band($loc, 'Bereit für die Veränderung?', 'Lass uns in einem unverbindlichen Gespräch herausfinden, wie ich dich am besten unterstützen kann.'); ?>

<?php require 'components/footer.php'; ?>
