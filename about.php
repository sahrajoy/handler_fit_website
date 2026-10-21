<?php
require_once 'lib/bootstrap.php';
require_once 'components/sections.php';

$loc = '';
$page = 'about';
$title = 'Über mich';

$values = [
    ['icon' => 'noun-fitness-5568533.png', 'title' => 'Gesundheit zuerst', 'text' => 'Saubere Technik und ein durchdachter Aufbau sind die Basis. Fortschritt ja, aber nie auf Kosten deiner Gesundheit.'],
    ['icon' => 'noun-fitness-4874119.png', 'title' => 'Individuell statt Standard', 'text' => 'Jeder Mensch ist anders. Darum bekommst du keinen Plan von der Stange, sondern ein Training, das zu deinem Leben passt.'],
    ['icon' => 'noun-fitness-3606247.png', 'title' => 'Nachhaltige Ergebnisse', 'text' => 'Keine Crash-Diäten, keine leeren Versprechen. Wir bauen Gewohnheiten auf, die dich langfristig ans Ziel bringen.'],
];

require 'components/head.php';
?>

<section id="welcome" class="about-intro">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-7 py-5">
                <span class="kicker">Über mich</span>
                <h1 class="display-title">Willkommen in meiner Welt des leidenschaftlichen Trainings!</h1>
                <p>Als erfahrener Fitness-Trainer begleite ich dich auf deiner Fitnessreise, mit maßgeschneiderten
                    Trainingsmethoden, professioneller Anleitung und grenzenloser Motivation.</p>
                <p>Training ist für mich mehr als nur Sport: Es ist der Weg zu mehr Energie, Selbstvertrauen und
                    Lebensqualität. Genau das möchte ich an dich weitergeben. Egal ob du gerade erst anfängst, nach
                    einer Pause wieder einsteigen willst oder als Fortgeschrittener neue Ziele verfolgst, ich hole
                    dich dort ab, wo du stehst.</p>
                <p>Entdecke das volle Potenzial deines Körpers und erreiche deine ganz persönlichen Ziele. Bist du
                    bereit für die Veränderung? Lass uns gemeinsam starten!</p>
            </div>
            <div class="col-lg-5 welcome-image">
                <img src="assets/images/about-pic.png" alt="Dein Trainer">
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-sand">
    <div class="container py-lg-4">
        <div class="text-center mb-5">
            <span class="kicker">Meine Philosophie</span>
            <h2>Wofür ich stehe</h2>
        </div>
        <div class="row g-4 text-center">
            <?php foreach ($values as $v): ?>
                <div class="col-md-4">
                    <div class="step">
                        <?= noun_icon($loc, $v['icon']) ?>
                        <h3><?= e($v['title']) ?></h3>
                        <p><?= e($v['text']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-lg-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="feature-image">
                    <img src="assets/images/man_with_barebell.jpg" alt="Training mit der Langhantel" loading="lazy">
                </div>
            </div>
            <div class="col-lg-6">
                <span class="kicker">Qualifikationen</span>
                <h2>Ausbildung &amp; Erfahrung</h2>
                <!-- TODO: echte Ausbildungen, Zertifikate und Erfahrung des Trainers eintragen -->
                <ul class="check-list">
                    <li><?= icon('check', 20) ?><span>Ausbildung / Trainerlizenz eintragen</span></li>
                    <li><?= icon('check', 20) ?><span>Weiterbildung, z. B. Ernährungsberatung</span></li>
                    <li><?= icon('check', 20) ?><span>Jahre Erfahrung als Trainer</span></li>
                    <li><?= icon('check', 20) ?><span>Weitere Zertifikate / Spezialisierungen</span></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php cta_band($loc, 'Lass uns kennenlernen', 'Im kostenlosen Erstgespräch sprechen wir über deine Ziele und wie ich dich dabei unterstützen kann.'); ?>

<?php require 'components/footer.php'; ?>
