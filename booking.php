<?php
require_once 'lib/bootstrap.php';
require_once 'components/sections.php';

$loc = '';
$page = 'booking';
$title = 'Termine';
$description = 'Aktuelle Trainingstermine von Handler Fit, jetzt unverbindlich voranmelden.';

$trainings = upcoming_trainings();
$bookable = array_filter($trainings, fn($t) => free_spots($t) > 0);

$errors = [];
$form = [
    'training' => (int)($_GET['training'] ?? 0),
    'name' => '',
    'email' => '',
    'phone' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = [
        'training' => (int)($_POST['training'] ?? 0),
        'name' => clean_line($_POST['name'] ?? '', 100),
        'email' => clean_line($_POST['email'] ?? '', 200),
        'phone' => clean_line($_POST['phone'] ?? '', 50),
        'message' => clean_text($_POST['message'] ?? '', 2000),
    ];
    $training = $form['training'] ? find_training($form['training']) : null;

    if (!csrf_valid()) {
        $errors[] = 'Die Sitzung ist abgelaufen. Bitte sende das Formular erneut.';
    }
    if (!$training) {
        $errors[] = 'Bitte wähle einen Termin aus.';
    }
    if ($form['name'] === '') {
        $errors[] = 'Bitte gib deinen Namen an.';
    }
    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Bitte gib eine gültige E-Mail-Adresse an.';
    }
    if (empty($_POST['privacy'])) {
        $errors[] = 'Bitte stimme der Verarbeitung deiner Daten zu.';
    }

    if (!$errors) {
        $success = 'Danke für deine Voranmeldung! Du bekommst gleich eine Eingangsbestätigung per E-Mail. '
            . 'Fix ist der Termin, sobald ich ihn dir bestätigt habe.';
        if (looks_like_spam()) {
            flash('success', $success);
            redirect('booking.php');
        }
        $error = create_booking($training, $form['name'], $form['email'], $form['phone'], $form['message']);
        if ($error === null) {
            flash('success', $success);
            redirect('booking.php');
        }
        $errors[] = $error;
    }
}

require 'components/head.php';
page_header($loc, 'Termine', 'Hier findest du alle aktuellen Trainings. Melde dich unverbindlich vor, ich bestätige dir deinen Platz per E-Mail.', 'barebell_bank.jpg');
?>

<section class="py-5">
    <div class="container py-lg-3">
        <?= render_flash() ?>

        <div class="booking-steps row g-3 mb-5 text-center">
            <div class="col-md-4"><div class="booking-step"><strong>1</strong> Termin aussuchen und voranmelden</div></div>
            <div class="col-md-4"><div class="booking-step"><strong>2</strong> Eingangsbestätigung per E-Mail erhalten</div></div>
            <div class="col-md-4"><div class="booking-step"><strong>3</strong> Termin wird von mir bestätigt, fertig!</div></div>
        </div>

        <?php if (!$trainings): ?>
            <div class="empty-state text-center">
                <?= icon('calendar', 40) ?>
                <h2>Aktuell sind keine Termine eingetragen</h2>
                <p>Schreib mir gerne direkt, wir finden bestimmt einen passenden Termin für dich.</p>
                <a class="btn btn-primary" href="contact.php?anliegen=personal">Termin anfragen</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php $showDescription = true;
                foreach ($trainings as $t): ?>
                    <div class="col-md-6 col-xl-4">
                        <?php require 'components/training_card.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($bookable): ?>
    <section class="py-5 bg-sand" id="anmeldung">
        <div class="container py-lg-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-4">
                        <span class="kicker">Voranmeldung</span>
                        <h2>Melde dich für ein Training an</h2>
                        <p class="mx-auto" style="max-width: 560px">Deine Anmeldung ist erst verbindlich, wenn ich sie dir per E-Mail bestätigt habe.</p>
                    </div>

                    <?php if ($errors): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php foreach ($errors as $err): ?>
                                <div><?= e($err) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="booking.php#anmeldung" class="card-form" novalidate>
                        <?= csrf_field() ?>
                        <?= spam_fields() ?>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="training">Termin *</label>
                                <select class="form-select" id="training" name="training" required>
                                    <option value="">Bitte auswählen …</option>
                                    <?php foreach ($bookable as $t): ?>
                                        <option value="<?= (int)$t['id'] ?>"<?= $form['training'] === (int)$t['id'] ? ' selected' : '' ?>>
                                            <?= e($t['title'] . ', ' . format_datetime_de($t['starts_at'])) ?>
                                        </option>
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
                                <label class="form-label" for="message">Anmerkungen <span class="text-muted">(optional)</span></label>
                                <textarea class="form-control" id="message" name="message" rows="3"
                                          placeholder="z. B. Vorerfahrung, Verletzungen oder Wünsche"><?= e($form['message']) ?></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" value="1" required>
                                    <label class="form-check-label" for="privacy">
                                        Ich bin einverstanden, dass meine Angaben zur Terminverwaltung gespeichert und verwendet werden.
                                        Mehr dazu in der <a href="imprint.php#datenschutz">Datenschutzerklärung</a>. *
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary btn-lg px-5" type="submit">Unverbindlich voranmelden</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php require 'components/footer.php'; ?>
