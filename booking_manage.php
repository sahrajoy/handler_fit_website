<?php
// Ziel des Links aus der Betreiber-E-Mail: Anmeldung bestätigen oder ablehnen.
// Die Aktion passiert erst per Klick auf einen Button (POST), damit Link-Scanner
// von Mailprogrammen nicht versehentlich etwas auslösen.
require_once 'lib/bootstrap.php';

$loc = '';
$page = 'booking';
$title = 'Anmeldung verwalten';
$noindex = true;

$token = (string)($_GET['t'] ?? $_POST['t'] ?? '');
$booking = $token !== '' ? find_booking_by('admin_token', $token) : null;
$error = null;
$done = null;

if ($booking && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if (!csrf_valid()) {
        $error = 'Die Sitzung ist abgelaufen. Bitte versuche es erneut.';
    } elseif ($action === 'confirm') {
        $error = confirm_booking($booking);
        $done = $error === null ? 'Die Anmeldung ist bestätigt. Der Kunde wurde per E-Mail informiert.' : null;
    } elseif ($action === 'decline') {
        $error = decline_booking($booking);
        $done = $error === null ? 'Die Anmeldung wurde abgelehnt. Der Kunde wurde per E-Mail informiert.' : null;
    }
    $booking = find_booking_by('admin_token', $token);
}

require 'components/head.php';
?>

<section class="py-5">
    <div class="container py-lg-4" style="max-width: 720px">
        <h1 class="h2 mb-4">Anmeldung verwalten</h1>

        <?php if (!$booking): ?>
            <div class="alert alert-warning">Diese Anmeldung wurde nicht gefunden. Vielleicht wurde der Termin inzwischen gelöscht.</div>
        <?php else: ?>
            <?php if ($done): ?>
                <div class="alert alert-success"><?= e($done) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?php $showContact = true;
            require 'components/booking_details.php'; ?>

            <?php if ($booking['status'] === 'pending'): ?>
                <form method="post" class="d-flex flex-wrap gap-3 mt-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="t" value="<?= e($token) ?>">
                    <button class="btn btn-primary btn-lg" name="action" value="confirm">Bestätigen</button>
                    <button class="btn btn-outline-danger btn-lg" name="action" value="decline">Ablehnen</button>
                </form>
            <?php elseif ($booking['status'] === 'confirmed'): ?>
                <form method="post" class="mt-4" onsubmit="return confirm('Bestätigte Anmeldung wirklich absagen? Der Kunde wird per E-Mail informiert.')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="t" value="<?= e($token) ?>">
                    <button class="btn btn-outline-danger" name="action" value="decline">Doch absagen</button>
                </form>
            <?php endif; ?>

            <p class="mt-4"><a class="link-arrow" href="admin/index.php">Zum Adminbereich <?= icon('arrow', 18) ?></a></p>
        <?php endif; ?>
    </div>
</section>

<?php require 'components/footer.php'; ?>
