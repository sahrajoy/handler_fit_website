<?php
require_once 'lib/bootstrap.php';

$loc = '';
$page = 'contact';
$title = 'Kontakt';

$topics = [
    'erstgespraech' => 'Kostenloses Erstgespräch',
    'online' => 'Online Coaching',
    'personal' => 'Personal Training',
    'sonstiges' => 'Sonstiges',
];

$errors = [];
$form = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'topic' => array_key_exists($_GET['anliegen'] ?? '', $topics) ? $_GET['anliegen'] : 'erstgespraech',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = [
        'name' => clean_line($_POST['name'] ?? '', 100),
        'email' => clean_line($_POST['email'] ?? '', 200),
        'phone' => clean_line($_POST['phone'] ?? '', 50),
        'topic' => array_key_exists($_POST['topic'] ?? '', $topics) ? $_POST['topic'] : 'sonstiges',
        'message' => clean_text($_POST['message'] ?? ''),
    ];

    if (!csrf_valid()) {
        $errors[] = 'Die Sitzung ist abgelaufen. Bitte sende das Formular erneut.';
    }
    if ($form['name'] === '') {
        $errors[] = 'Bitte gib deinen Namen an.';
    }
    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Bitte gib eine gültige E-Mail-Adresse an.';
    }
    if (mb_strlen($form['message']) < 10) {
        $errors[] = 'Bitte schreib mir eine kurze Nachricht (mindestens 10 Zeichen).';
    }
    if (empty($_POST['privacy'])) {
        $errors[] = 'Bitte stimme der Verarbeitung deiner Daten zu.';
    }

    if (!$errors) {
        if (looks_like_spam()) {
            // Bots bekommen dieselbe Erfolgsmeldung, es wird aber nichts versendet
            flash('success', 'Danke für deine Nachricht! Ich melde mich so schnell wie möglich bei dir.');
            redirect('contact.php');
        }
        $sent = send_mail(
            config('office_email'),
            'Kontaktanfrage: ' . $topics[$form['topic']] . ', ' . $form['name'],
            "Neue Nachricht über das Kontaktformular:\n\n"
            . "Anliegen: {$topics[$form['topic']]}\n"
            . "Name:     {$form['name']}\n"
            . "E-Mail:   {$form['email']}\n"
            . ($form['phone'] !== '' ? "Telefon:  {$form['phone']}\n" : '')
            . "\nNachricht:\n{$form['message']}\n",
            $form['email'],
            $form['name']
        );
        if ($sent) {
            flash('success', 'Danke für deine Nachricht! Ich melde mich so schnell wie möglich bei dir.');
            redirect('contact.php');
        }
        $errors[] = 'Die Nachricht konnte leider nicht gesendet werden. Bitte schreib mir direkt an ' . config('display_email') . '.';
    }
}

require 'components/head.php';
require_once 'components/sections.php';
page_header($loc, 'Kontakt', 'Du hast Fragen oder möchtest direkt starten? Schreib mir, ich freue mich auf deine Nachricht!', 'barbell_on_floor.jpg');
?>

<section class="py-5">
    <div class="container py-lg-4">
        <div class="row g-5">
            <div class="col-lg-7">
                <?= render_flash() ?>
                <?php if ($errors): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php foreach ($errors as $err): ?>
                            <div><?= e($err) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="contact.php" class="card-form" novalidate>
                    <?= csrf_field() ?>
                    <?= spam_fields() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="topic">Worum geht es?</label>
                            <select class="form-select" id="topic" name="topic">
                                <?php foreach ($topics as $value => $label): ?>
                                    <option value="<?= $value ?>"<?= $form['topic'] === $value ? ' selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="name">Name *</label>
                            <input class="form-control" id="name" name="name" required maxlength="100" autocomplete="name" value="<?= e($form['name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">E-Mail *</label>
                            <input class="form-control" type="email" id="email" name="email" required maxlength="200" autocomplete="email" value="<?= e($form['email']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Telefon <span class="text-muted">(optional)</span></label>
                            <input class="form-control" type="tel" id="phone" name="phone" maxlength="50" autocomplete="tel" value="<?= e($form['phone']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="message">Nachricht *</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required
                                      placeholder="Erzähl mir kurz von deinen Zielen und deiner Trainingserfahrung."><?= e($form['message']) ?></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="privacy" name="privacy" value="1" required>
                                <label class="form-check-label" for="privacy">
                                    Ich bin einverstanden, dass meine Angaben zur Bearbeitung meiner Anfrage verwendet werden.
                                    Mehr dazu in der <a href="imprint.php#datenschutz">Datenschutzerklärung</a>. *
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary btn-lg px-5" type="submit">Nachricht senden</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-5">
                <div class="contact-aside">
                    <h2>So erreichst du mich</h2>
                    <ul class="footer-contact">
                        <li><a href="mailto:<?= e(config('office_email')) ?>"><?= icon('mail') ?> <?= e(config('display_email')) ?></a></li>
                        <li><a href="https://www.instagram.com/handler_fit/" target="_blank" rel="noopener"><?= icon('instagram') ?> @handler_fit</a></li>
                    </ul>
                    <p>Ich antworte in der Regel innerhalb von 24 Stunden.</p>
                    <hr>
                    <h3>Lieber direkt einen Termin?</h3>
                    <p>Schau dir die aktuellen Trainingstermine an und melde dich unverbindlich vor.</p>
                    <a class="link-arrow" href="booking.php">Zu den Terminen <?= icon('arrow', 18) ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'components/footer.php'; ?>
