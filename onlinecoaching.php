<?php
require_once 'lib/bootstrap.php';
require_once 'components/sections.php';

$loc = '';
$page = 'online';
$title = 'Online Coaching';
$description = 'Online Coaching mit Handler Fit: Abnehm-Coaching, Fitness- und Ernährungstipps und individuelle Trainingspläne, flexibel, persönlich und ortsunabhängig.';

$sections = [
    [
        'id' => 'was-ist-online-coaching',
        'kicker' => 'Überblick',
        'title' => 'Was ist Online Coaching?',
        'image' => 'two_mens.jpg',
        'icon' => 'noun-fitness-5568533.png',
        'paragraphs' => [
            'Beim Online Coaching bekommst du die Betreuung eines Personal Trainers, ganz unabhängig davon, wo du wohnst und wann du trainierst. Du trainierst im Studio, zuhause oder unterwegs, ich begleite dich digital auf jedem Schritt.',
            'Am Anfang steht ein ausführliches Gespräch über deine Ziele, deinen Alltag und deine Trainingserfahrung. Darauf aufbauend bekommst du einen Plan, der wirklich zu dir passt, und regelmäßige Check-ins, in denen wir deinen Fortschritt besprechen und den Plan anpassen.',
        ],
        'bullets' => [
            'Individueller Trainingsplan statt Plan von der Stange',
            'Regelmäßige Check-ins und Feedback zu deiner Technik per Video',
            'Erreichbarkeit für deine Fragen, auch zwischen den Terminen',
            'Volle Flexibilität: trainiere, wann und wo es für dich passt',
        ],
    ],
    [
        'id' => 'abnehm-coaching',
        'kicker' => 'Online Coaching',
        'title' => 'Abnehm-Coaching',
        'image' => 'vegtables.jpg',
        'icon' => 'noun-weight-5817075.png',
        'paragraphs' => [
            'Nachhaltig abnehmen heißt nicht hungern und auch nicht jeden Tag stundenlang Cardio. Im Abnehm-Coaching kombinieren wir ein passendes Training mit einer Ernährung, die in deinen Alltag passt, damit die Kilos nicht nur runtergehen, sondern auch unten bleiben.',
            'Wir setzen realistische Etappenziele, verfolgen deinen Fortschritt nicht nur auf der Waage, sondern auch über Umfänge, Fotos und dein Körpergefühl, und arbeiten an Gewohnheiten, die langfristig funktionieren.',
        ],
        'bullets' => [
            'Kaloriendefizit, das sich nicht nach Verzicht anfühlt',
            'Krafttraining zum Erhalt der Muskulatur und für einen straffen Körper',
            'Wöchentliche Auswertung und Anpassung',
            'Motivation und Unterstützung, wenn es mal schwerfällt',
        ],
    ],
    [
        'id' => 'fitness-ernaehrungstipps',
        'kicker' => 'Online Coaching',
        'title' => 'Fitness- & Ernährungstipps',
        'image' => 'mealplan.jpg',
        'icon' => 'noun-fitness-3606247.png',
        'paragraphs' => [
            'Im Fitnessbereich kursieren unzählige Mythen. Ich helfe dir, die Grundlagen zu verstehen: Wie viel Eiweiß brauchst du wirklich? Wie oft solltest du trainieren? Und wie wichtig sind Schlaf und Regeneration für deinen Erfolg?',
            'Du bekommst praxisnahe Tipps, die du sofort umsetzen kannst, von einfachen Meal-Prep-Ideen über die richtige Einkaufsliste bis hin zu kleinen Gewohnheiten, die im Alltag einen großen Unterschied machen.',
        ],
        'bullets' => [
            'Einfache, alltagstaugliche Rezepte und Meal-Prep-Ideen',
            'Makronährstoffe verständlich erklärt',
            'Tipps zu Regeneration, Schlaf und Stressmanagement',
            'Ehrliche Einschätzung zu Supplements',
        ],
    ],
    [
        'id' => 'trainingsplaene',
        'kicker' => 'Online Coaching',
        'title' => 'Trainingspläne',
        'image' => 'barbell_on_floor.jpg',
        'icon' => 'noun-fitness-4874119.png',
        'paragraphs' => [
            'Ein guter Trainingsplan ist auf dich zugeschnitten: auf dein Ziel, deinen Leistungsstand, die Zeit, die du hast, und das Equipment, das dir zur Verfügung steht. Genau so einen Plan erstelle ich für dich.',
            'Jede Übung ist mit Sätzen, Wiederholungen und Pausen klar beschrieben. Durch gezielte Progression wird der Plan mit dir mitwachsen, damit du nicht stagnierst, sondern dich kontinuierlich verbesserst.',
        ],
        'bullets' => [
            'Für Studio, Homegym oder Training mit dem eigenen Körpergewicht',
            'Klare Anleitungen und Übungsvideos',
            'Geplante Progression für stetigen Fortschritt',
            'Regelmäßige Überarbeitung nach deinem Feedback',
        ],
    ],
];

require 'components/head.php';

page_header($loc, 'Online Coaching', 'Professionelle Betreuung, egal wo du bist, flexibel, persönlich und auf deine Ziele zugeschnitten.', 'imgOnline.png');
section_nav($sections);
foreach ($sections as $i => $s) {
    feature_section($loc, $s, $i % 2 === 1);
}
cta_band($loc, 'Starte jetzt mit deinem Online Coaching', 'Im kostenlosen Erstgespräch finden wir heraus, welches Coaching am besten zu dir passt.');

require 'components/footer.php';
