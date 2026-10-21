<?php
require_once 'lib/bootstrap.php';

$loc = '';
$page = '';
$title = 'Impressum & Datenschutz';

require 'components/head.php';
?>

<!-- TODO: Alle Angaben in [eckigen Klammern] durch die echten Daten ersetzen und die Texte rechtlich prüfen lassen
     (z. B. mit einem Generator wie e-recht24.de oder datenschutz-generator.de). -->
<section class="py-5 legal">
    <div class="container" style="max-width: 820px">
        <h1 class="h2">Impressum</h1>
        <p>Angaben gemäß § 5 DDG</p>
        <p>
            [Vor- und Nachname]<br>
            Handler Fit, Personal Training<br>
            [Straße und Hausnummer]<br>
            [PLZ Ort]<br>
            [Land]
        </p>

        <h2 class="h4">Kontakt</h2>
        <p>
            E-Mail: <a href="mailto:<?= e(config('office_email')) ?>"><?= e(config('display_email')) ?></a><br>
            Telefon: [Telefonnummer]
        </p>

        <h2 class="h4">Umsatzsteuer</h2>
        <p>[Umsatzsteuer-Identifikationsnummer gemäß § 27a UStG, oder Hinweis auf Kleinunternehmerregelung]</p>

        <h2 class="h4">Verantwortlich für den Inhalt</h2>
        <p>[Vor- und Nachname, Anschrift wie oben]</p>

        <h2 class="h4">Bildnachweise</h2>
        <p>Icons von <a href="https://thenounproject.com" target="_blank" rel="noopener">Noun Project</a>:
            Jino, DinosoftLabs, Icogenix und Tarjo Sugiono (CC BY 3.0).</p>

        <hr class="my-5">

        <h1 class="h2" id="datenschutz">Datenschutzerklärung</h1>

        <h2 class="h4">1. Verantwortlicher</h2>
        <p>Verantwortlich für die Datenverarbeitung auf dieser Website ist [Vor- und Nachname, Anschrift],
            E-Mail: <?= e(config('display_email')) ?>.</p>

        <h2 class="h4">2. Hosting und Server-Logfiles</h2>
        <p>Diese Website wird bei [Name des Hosting-Anbieters] gehostet. Beim Aufruf der Seite speichert der Server
            automatisch technische Informationen (z. B. IP-Adresse, Datum und Uhrzeit, aufgerufene Seite, Browser).
            Diese Daten sind zur sicheren Bereitstellung der Website erforderlich (Art. 6 Abs. 1 lit. f DSGVO) und
            werden nach [Anzahl] Tagen gelöscht.</p>

        <h2 class="h4">3. Kontaktformular</h2>
        <p>Wenn du mir über das Kontaktformular schreibst, verarbeite ich deine Angaben (Name, E-Mail-Adresse,
            optional Telefonnummer und deine Nachricht), um deine Anfrage zu beantworten (Art. 6 Abs. 1 lit. b DSGVO).
            Die Daten werden per E-Mail an mich übermittelt und gelöscht, sobald die Anfrage abgeschlossen ist und
            keine gesetzlichen Aufbewahrungspflichten bestehen.</p>

        <h2 class="h4">4. Terminbuchung</h2>
        <p>Für die Voranmeldung zu Trainings speichere ich deinen Namen, deine E-Mail-Adresse, optional deine
            Telefonnummer und Anmerkungen sowie den gewählten Termin. Die Daten werden ausschließlich zur Organisation
            der Trainings und zur Kommunikation mit dir verwendet (Art. 6 Abs. 1 lit. b DSGVO) und [Anzahl] Monate
            nach dem Termin gelöscht.</p>

        <h2 class="h4">5. Cookies</h2>
        <p>Diese Website verwendet nur ein technisch notwendiges Sitzungs-Cookie, das für den Schutz der Formulare
            benötigt wird und beim Schließen des Browsers gelöscht wird. Es werden keine Tracking- oder Analyse-Cookies
            eingesetzt.</p>

        <h2 class="h4">6. Externe Inhalte</h2>
        <p>Schriftarten und Bibliotheken werden lokal von diesem Server geladen; es findet keine Verbindung zu Google
            Fonts oder anderen Drittanbietern statt. Beim Klick auf den Instagram-Link verlässt du diese Website; es
            gelten dann die Datenschutzbestimmungen von Meta.</p>

        <h2 class="h4">7. Deine Rechte</h2>
        <p>Du hast das Recht auf Auskunft, Berichtigung, Löschung und Einschränkung der Verarbeitung deiner Daten,
            auf Datenübertragbarkeit sowie auf Widerspruch gegen die Verarbeitung. Außerdem kannst du dich bei einer
            Datenschutz-Aufsichtsbehörde beschweren. Wende dich dazu einfach an <?= e(config('display_email')) ?>.</p>
    </div>
</section>

<?php require 'components/footer.php'; ?>
