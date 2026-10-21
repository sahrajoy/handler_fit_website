<?php
require_once 'lib/bootstrap.php';
require_once 'components/sections.php';

$loc = '';
$page = 'personal';
$title = 'Personal Training';
$description = 'Personal Training mit Handler Fit: Muskelaufbau, Krafttraining und Bauch-Beine-Po Workouts, persönlich betreut und auf deine Ziele abgestimmt.';

$sections = [
    [
        'id' => 'was-ist-personal-training',
        'kicker' => 'Überblick',
        'title' => 'Was ist Personal Training?',
        'image' => 'two_mens.jpg',
        'icon' => 'noun-fitness-5568533.png',
        'paragraphs' => [
            'Beim Personal Training trainieren wir gemeinsam vor Ort, eins zu eins. Du hast meine volle Aufmerksamkeit: Ich achte auf jede Wiederholung, korrigiere deine Technik direkt und sorge dafür, dass du sicher und effektiv trainierst.',
            'Jede Einheit ist auf dein Ziel und deine Tagesform abgestimmt. Ob du gerade erst anfängst oder schon lange trainierst und neue Reize suchst: Gemeinsam holen wir mehr aus jedem Training heraus, als du alleine schaffen würdest.',
        ],
        'bullets' => [
            '100 % Aufmerksamkeit für dich und deine Technik',
            'Trainingseinheiten, die auf dein Ziel abgestimmt sind',
            'Verletzungsprävention durch saubere Ausführung',
            'Motivation und Verbindlichkeit durch feste Termine',
        ],
    ],
    [
        'id' => 'hypertrophie',
        'kicker' => 'Personal Training',
        'title' => 'Hypertrophie (Muskelaufbau)',
        'image' => 'man_with_barebell.jpg',
        'icon' => 'noun-fitness-4874119.png',
        'paragraphs' => [
            'Beim Hypertrophietraining geht es um gezielten Muskelaufbau. Mit der richtigen Kombination aus Trainingsvolumen, Intensität und Übungsauswahl setzen wir genau die Reize, die deine Muskeln zum Wachsen bringen.',
            'Wir arbeiten an einer sauberen Technik, einer kontrollierten Bewegungsausführung und einer durchdachten Progression. Ergänzt durch Tipps zu Ernährung und Regeneration baust du so sichtbar und nachhaltig Muskulatur auf.',
        ],
        'bullets' => [
            'Optimales Trainingsvolumen für maximales Muskelwachstum',
            'Fokus auf Technik und Mind-Muscle-Connection',
            'Strukturierte Progression statt Zufallstraining',
            'Ernährungstipps für den Muskelaufbau',
        ],
    ],
    [
        'id' => 'krafttraining',
        'kicker' => 'Personal Training',
        'title' => 'Krafttraining',
        'image' => 'barebell_bank.jpg',
        'icon' => 'noun-fitness-5568533.png',
        'paragraphs' => [
            'Beim Krafttraining steht die Leistung im Mittelpunkt: Wir machen dich stärker in den Grundübungen wie Kniebeuge, Kreuzheben und Bankdrücken, und damit auch im Alltag belastbarer und weniger anfällig für Verletzungen.',
            'Schritt für Schritt erarbeiten wir die richtige Technik und steigern die Gewichte mit System. So erlebst du, wie dein Körper von Woche zu Woche mehr leisten kann.',
        ],
        'bullets' => [
            'Sichere Technik in den Grundübungen',
            'Periodisierte Steigerung der Gewichte',
            'Mehr Kraft, Stabilität und eine bessere Haltung',
            'Geeignet für Einsteiger und Fortgeschrittene',
        ],
    ],
    [
        'id' => 'bauch-beine-po',
        'kicker' => 'Personal Training',
        'title' => 'Bauch-Beine-Po Workout',
        'image' => 'woman_back_with_barebell.jpg',
        'icon' => 'noun-fitness-3606247.png',
        'paragraphs' => [
            'Das Bauch-Beine-Po Workout konzentriert sich auf eine starke Körpermitte, kräftige Beine und einen definierten Po. Mit gezielten Übungen formen und straffen wir genau die Bereiche, die dir besonders wichtig sind.',
            'Wir kombinieren klassische Übungen wie Hip Thrusts, Ausfallschritte und Kniebeugen mit effektivem Core-Training. Das Ergebnis: mehr Stabilität, eine bessere Haltung und ein Körpergefühl, mit dem du dich wohlfühlst.',
        ],
        'bullets' => [
            'Gezieltes Training für Bauch, Beine und Po',
            'Starke Körpermitte für eine gesunde Haltung',
            'Abwechslungsreiche Übungen mit und ohne Gewichte',
            'Anpassbar an jedes Fitnesslevel',
        ],
    ],
];

require 'components/head.php';

page_header($loc, 'Personal Training', 'Eins-zu-eins-Training mit voller Aufmerksamkeit für deine Technik, deine Ziele und deinen Fortschritt.', 'imgPersonal.png');
section_nav($sections);
foreach ($sections as $i => $s) {
    feature_section($loc, $s, $i % 2 === 1);
}
cta_band($loc, 'Lust auf dein erstes Training?', 'Melde dich für einen Termin an oder vereinbare zuerst ein kostenloses Erstgespräch.');

require 'components/footer.php';
