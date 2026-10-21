<?php
// Ziel des Links aus der Kunden-E-Mail: Anmeldung zurückziehen.
require_once 'lib/bootstrap.php';

$loc = '';
$page = 'booking';
$title = 'Anmeldung zurückziehen';
$noindex = true;

$token = (string)($_GET['t'] ?? $_POST['t'] ?? '');
$booking = $token !== '' ? find_booking_by('customer_token', $token) : null;
$error = null;
$done = false;

if ($booking && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $error = 'Die Sitzung ist abgelaufen. Bitte versuche es erneut.';
    } else {
        $error = cancel_booking_by_customer($booking);
        $done = $error === null;
        $booking = find_booking_by('customer_token', $token);
    }
}

$isPast = $booking && strtotime($booking['starts_at']) < time();

require 'components/head.php';
?>

<section class="py-5">
    <div class="container py-lg-4" style="max-width: 720px">
        <h1 class="h2 mb-4">Anmeldung zurückziehen</h1>

        <?php if (!$booking): ?>
            <div class="alert alert-warning">Diese Anmeldung wurde nicht gefunden. Vielleicht wurde der Termin inzwischen abgesagt.</div>
        <?php else: ?>
            <?php if ($done): ?>
                <div class="alert alert-success">Deine Anmeldung wurde zurückgezogen. Schade, vielleicht klappt es beim nächsten Mal!</div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?php require 'components/booking_details.php'; ?>

            <?php if (in_array($booking['status'], ['pending', 'confirmed'], true) && !$isPast): ?>
                <p class="mt-4">Möchtest du deine Anmeldung für diesen Termin wirklich zurückziehen?</p>
                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="t" value="<?= e($token) ?>">
                    <button class="btn btn-outline-danger btn-lg">Ja, Anmeldung zurückziehen</button>
                </form>
            <?php endif; ?>

            <p class="mt-4"><a class="link-arrow" href="booking.php">Zu den aktuellen Terminen <?= icon('arrow', 18) ?></a></p>
        <?php endif; ?>
    </div>
</section>

<?php require 'components/footer.php'; ?>
