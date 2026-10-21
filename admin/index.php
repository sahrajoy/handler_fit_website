<?php
// Adminbereich: Trainings anlegen/bearbeiten/absagen und Anmeldungen verwalten.
require_once '../lib/bootstrap.php';

$loc = '../';
$page = '';
$title = 'Admin';
$noindex = true;

$isAdmin = !empty($_SESSION['is_admin']);
$action = $_POST['action'] ?? '';

// --- Login / Logout ---------------------------------------------------------
if ($action === 'login') {
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    if ($_SESSION['login_attempts'] > 5) {
        sleep(2);
    }
    $hash = config('admin_password_hash');
    $plain = config('admin_password');
    $input = (string)($_POST['password'] ?? '');
    $valid = ($hash && password_verify($input, $hash)) || ($plain && hash_equals($plain, $input));
    if (csrf_valid() && $valid) {
        session_regenerate_id(true);
        $_SESSION['is_admin'] = true;
        $_SESSION['login_attempts'] = 0;
        redirect('index.php');
    }
    flash('danger', $hash || $plain ? 'Falsches Passwort.' : 'Es ist noch kein Admin-Passwort hinterlegt.');
    redirect('index.php');
}

if ($action === 'logout' && $isAdmin && csrf_valid()) {
    $_SESSION = [];
    session_destroy();
    redirect('index.php');
}

// --- Aktionen (nur eingeloggt) ----------------------------------------------
$errors = [];
$form = null;

if ($isAdmin && $action !== '' && !csrf_valid()) {
    flash('danger', 'Die Sitzung ist abgelaufen. Bitte erneut versuchen.');
    redirect('index.php');
}

if ($isAdmin && $action === 'save_training') {
    $form = [
        'id' => (int)($_POST['id'] ?? 0),
        'title' => clean_line($_POST['title'] ?? '', 150),
        'date' => (string)($_POST['date'] ?? ''),
        'time' => (string)($_POST['time'] ?? ''),
        'duration_min' => (int)($_POST['duration_min'] ?? 60),
        'capacity' => (int)($_POST['capacity'] ?? 1),
        'location' => clean_line($_POST['location'] ?? '', 200),
        'description' => clean_text($_POST['description'] ?? '', 3000),
        'repeat_weeks' => max(0, min(12, (int)($_POST['repeat_weeks'] ?? 0))),
    ];
    $startsAt = DateTime::createFromFormat('Y-m-d H:i', $form['date'] . ' ' . $form['time']);
    $existing = $form['id'] ? find_training($form['id']) : null;

    if ($form['title'] === '') {
        $errors[] = 'Bitte einen Titel angeben.';
    }
    if (!$startsAt) {
        $errors[] = 'Bitte Datum und Uhrzeit angeben.';
    }
    if ($form['duration_min'] < 10 || $form['duration_min'] > 600) {
        $errors[] = 'Die Dauer muss zwischen 10 und 600 Minuten liegen.';
    }
    if ($form['capacity'] < 1 || $form['capacity'] > 200) {
        $errors[] = 'Die Teilnehmerzahl muss zwischen 1 und 200 liegen.';
    }
    if ($existing && $form['capacity'] < (int)$existing['confirmed_count']) {
        $errors[] = 'Es gibt bereits ' . $existing['confirmed_count'] . ' bestätigte Teilnehmer, die Teilnehmerzahl kann nicht kleiner sein.';
    }

    if (!$errors) {
        if ($existing) {
            db()->prepare('UPDATE trainings SET title = ?, description = ?, location = ?, starts_at = ?, duration_min = ?, capacity = ? WHERE id = ?')
                ->execute([$form['title'], $form['description'], $form['location'], $startsAt->format('Y-m-d H:i'), $form['duration_min'], $form['capacity'], $existing['id']]);
            flash('success', 'Training gespeichert. Hinweis: Bereits angemeldete Teilnehmer werden über Änderungen nicht automatisch informiert.');
        } else {
            $insert = db()->prepare('INSERT INTO trainings (title, description, location, starts_at, duration_min, capacity, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
            for ($w = 0; $w <= $form['repeat_weeks']; $w++) {
                $date = (clone $startsAt)->modify("+$w week");
                $insert->execute([$form['title'], $form['description'], $form['location'], $date->format('Y-m-d H:i'), $form['duration_min'], $form['capacity'], date('Y-m-d H:i:s')]);
            }
            $count = $form['repeat_weeks'] + 1;
            flash('success', $count === 1 ? 'Training angelegt.' : "$count Trainings angelegt.");
        }
        redirect('index.php');
    }
}

if ($isAdmin && $action === 'reset_demo' && is_demo()) {
    db()->exec('DELETE FROM bookings; DELETE FROM trainings; DELETE FROM demo_mails;');
    seed_demo_trainings(db());
    flash('success', 'Demo wurde zurückgesetzt.');
    redirect('index.php');
}

if ($isAdmin && $action === 'cancel_training') {
    cancel_training((int)($_POST['id'] ?? 0));
    flash('success', 'Training abgesagt und gelöscht. Alle Angemeldeten wurden per E-Mail informiert.');
    redirect('index.php');
}

if ($isAdmin && in_array($action, ['confirm', 'decline'], true)) {
    $booking = find_booking_by('id', (string)(int)($_POST['booking_id'] ?? 0));
    if ($booking) {
        $error = $action === 'confirm' ? confirm_booking($booking) : decline_booking($booking);
        $error
            ? flash('danger', $error)
            : flash('success', ($action === 'confirm' ? 'Bestätigt' : 'Abgelehnt') . ', ' . $booking['name'] . ' wurde per E-Mail informiert.');
    }
    redirect('index.php#training-' . ($booking['training_id'] ?? ''));
}

// Formular zum Bearbeiten vorbefüllen
if ($isAdmin && $form === null && isset($_GET['edit'])) {
    $t = find_training((int)$_GET['edit']);
    if ($t) {
        $form = [
            'id' => (int)$t['id'],
            'title' => $t['title'],
            'date' => substr($t['starts_at'], 0, 10),
            'time' => substr($t['starts_at'], 11, 5),
            'duration_min' => (int)$t['duration_min'],
            'capacity' => (int)$t['capacity'],
            'location' => $t['location'],
            'description' => $t['description'],
            'repeat_weeks' => 0,
        ];
    }
}
$form ??= ['id' => 0, 'title' => '', 'date' => '', 'time' => '', 'duration_min' => 60, 'capacity' => 1, 'location' => '', 'description' => '', 'repeat_weeks' => 0];

require '../components/head.php';
?>

<section class="py-5 admin">
    <div class="container">
        <?php if (!$isAdmin): ?>
            <div class="mx-auto" style="max-width: 420px">
                <h1 class="h2 mb-4">Admin-Login</h1>
                <?php if (is_demo() && config('admin_password')): ?>
                    <div class="alert alert-info">
                        <strong>Demo-Zugang:</strong> Passwort <code><?= e(config('admin_password')) ?></code>
                    </div>
                <?php endif; ?>
                <?= render_flash() ?>
                <form method="post" class="card-form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="login">
                    <label class="form-label" for="password">Passwort</label>
                    <input class="form-control mb-3" type="password" id="password" name="password" required autofocus autocomplete="current-password">
                    <button class="btn btn-primary w-100">Einloggen</button>
                </form>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">Trainings &amp; Anmeldungen</h1>
                <form method="post">
                    <?= csrf_field() ?>
                    <button class="btn btn-outline-secondary btn-sm" name="action" value="logout">Logout</button>
                </form>
            </div>
            <?= render_flash() ?>

            <div class="row g-5">
                <div class="col-lg-4">
                    <form method="post" class="card-form admin-form" id="training-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="save_training">
                        <input type="hidden" name="id" value="<?= (int)$form['id'] ?>">
                        <h2 class="h4 mb-3"><?= $form['id'] ? 'Training bearbeiten' : 'Neues Training' ?></h2>
                        <?php if ($errors): ?>
                            <div class="alert alert-danger"><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div>
                        <?php endif; ?>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="title">Titel *</label>
                                <input class="form-control" id="title" name="title" required maxlength="150" value="<?= e($form['title']) ?>" placeholder="z. B. Personal Training, Krafttraining">
                            </div>
                            <div class="col-7">
                                <label class="form-label" for="date">Datum *</label>
                                <input class="form-control" type="date" id="date" name="date" required value="<?= e($form['date']) ?>">
                            </div>
                            <div class="col-5">
                                <label class="form-label" for="time">Uhrzeit *</label>
                                <input class="form-control" type="time" id="time" name="time" required value="<?= e($form['time']) ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="duration_min">Dauer (Min.)</label>
                                <input class="form-control" type="number" id="duration_min" name="duration_min" min="10" max="600" step="5" value="<?= (int)$form['duration_min'] ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="capacity">Plätze</label>
                                <input class="form-control" type="number" id="capacity" name="capacity" min="1" max="200" value="<?= (int)$form['capacity'] ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="location">Ort</label>
                                <input class="form-control" id="location" name="location" maxlength="200" value="<?= e($form['location']) ?>" placeholder="z. B. Studio XY oder Online (Zoom)">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="description">Beschreibung</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= e($form['description']) ?></textarea>
                            </div>
                            <?php if (!$form['id']): ?>
                                <div class="col-12">
                                    <label class="form-label" for="repeat_weeks">Wöchentlich wiederholen</label>
                                    <select class="form-select" id="repeat_weeks" name="repeat_weeks">
                                        <option value="0">Nicht wiederholen</option>
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?= $i ?>"<?= $form['repeat_weeks'] === $i ? ' selected' : '' ?>>+ <?= $i ?> weitere Woche<?= $i > 1 ? 'n' : '' ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-primary"><?= $form['id'] ? 'Speichern' : 'Anlegen' ?></button>
                                <?php if ($form['id']): ?>
                                    <a class="btn btn-outline-secondary" href="index.php">Abbrechen</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-8">
                    <?php $upcoming = upcoming_trainings(); ?>
                    <h2 class="h4 mb-3">Kommende Trainings (<?= count($upcoming) ?>)</h2>
                    <?php if (!$upcoming): ?>
                        <p class="text-muted">Noch keine Trainings eingetragen. Leg links dein erstes Training an.</p>
                    <?php endif; ?>

                    <?php foreach ($upcoming as $t):
                        $bookings = bookings_for_training((int)$t['id']); ?>
                        <div class="admin-training" id="training-<?= (int)$t['id'] ?>">
                            <div class="d-flex flex-wrap justify-content-between gap-2">
                                <div>
                                    <h3 class="h5 mb-1"><?= e($t['title']) ?></h3>
                                    <div class="text-muted small">
                                        <?= e(format_datetime_de($t['starts_at'])) ?> · <?= (int)$t['duration_min'] ?> Min.
                                        <?= $t['location'] !== '' ? ' · ' . e($t['location']) : '' ?>
                                    </div>
                                    <div class="small mt-1">
                                        <strong><?= (int)$t['confirmed_count'] ?>/<?= (int)$t['capacity'] ?></strong> bestätigt
                                        <?php if ($t['pending_count'] > 0): ?>
                                            · <span class="badge text-bg-warning"><?= (int)$t['pending_count'] ?> offen</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 align-items-start">
                                    <a class="btn btn-outline-secondary btn-sm" href="index.php?edit=<?= (int)$t['id'] ?>#training-form">Bearbeiten</a>
                                    <form method="post" onsubmit="return confirm('Training wirklich absagen? Alle Angemeldeten werden per E-Mail informiert und das Training wird gelöscht.')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                        <button class="btn btn-outline-danger btn-sm" name="action" value="cancel_training">Absagen</button>
                                    </form>
                                </div>
                            </div>

                            <?php if ($bookings): ?>
                                <div class="table-responsive mt-3">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead><tr><th>Name</th><th>Kontakt</th><th>Status</th><th></th></tr></thead>
                                        <tbody>
                                        <?php foreach ($bookings as $b): ?>
                                            <tr class="<?= in_array($b['status'], ['declined', 'cancelled'], true) ? 'text-muted' : '' ?>">
                                                <td>
                                                    <?= e($b['name']) ?>
                                                    <?php if ($b['message'] !== ''): ?>
                                                        <div class="small text-muted"><?= nl2br(e($b['message'])) ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="small">
                                                    <a href="mailto:<?= e($b['email']) ?>"><?= e($b['email']) ?></a>
                                                    <?= $b['phone'] !== '' ? '<br>' . e($b['phone']) : '' ?>
                                                </td>
                                                <td><span class="badge <?= ['pending' => 'text-bg-warning', 'confirmed' => 'text-bg-success'][$b['status']] ?? 'text-bg-secondary' ?>"><?= e(BOOKING_STATUS[$b['status']]) ?></span></td>
                                                <td class="text-end text-nowrap">
                                                    <?php if (in_array($b['status'], ['pending', 'confirmed'], true)): ?>
                                                        <form method="post" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                                                            <?php if ($b['status'] === 'pending'): ?>
                                                                <button class="btn btn-primary btn-sm" name="action" value="confirm">Bestätigen</button>
                                                            <?php endif; ?>
                                                            <button class="btn btn-outline-danger btn-sm" name="action" value="decline"
                                                                    onclick="return confirm('<?= $b['status'] === 'confirmed' ? 'Bestätigte Anmeldung absagen?' : 'Anmeldung ablehnen?' ?> Der Kunde wird per E-Mail informiert.')">
                                                                <?= $b['status'] === 'confirmed' ? 'Absagen' : 'Ablehnen' ?>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="small text-muted mt-2 mb-0">Noch keine Anmeldungen.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php $past = past_trainings(); ?>
                    <?php if ($past): ?>
                        <details class="mt-4">
                            <summary class="h5">Vergangene Trainings</summary>
                            <ul class="list-unstyled mt-3">
                                <?php foreach ($past as $t): ?>
                                    <li class="small mb-1"><?= e(format_datetime_de($t['starts_at'])) ?>, <?= e($t['title']) ?>
                                        (<?= (int)$t['confirmed_count'] ?>/<?= (int)$t['capacity'] ?> Teilnehmer)</li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php endif; ?>

                    <?php if (is_demo()):
                        $mails = db()->query('SELECT * FROM demo_mails ORDER BY id DESC LIMIT 50')->fetchAll(); ?>
                        <div class="demo-mails mt-5" id="demo-mails">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <h2 class="h4 mb-0">Versendete E-Mails (Demo)</h2>
                                <form method="post" onsubmit="return confirm('Alle Termine, Anmeldungen und E-Mails löschen und die Beispieltermine neu anlegen?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-outline-secondary btn-sm" name="action" value="reset_demo">Demo zurücksetzen</button>
                                </form>
                            </div>
                            <p class="small text-muted">Im Demo-Modus werden keine echten E-Mails verschickt. Hier siehst du, was Betreiber und Kunden
                                bekommen hätten. Die Links darin (z. B. „Anmeldung bestätigen“) funktionieren.</p>
                            <?php if (!$mails): ?>
                                <p class="text-muted">Noch keine E-Mails. Melde dich z. B. unter <a href="../booking.php">Termine</a> für ein Training an.</p>
                            <?php endif; ?>
                            <?php foreach ($mails as $m): ?>
                                <details class="demo-mail">
                                    <summary>
                                        <span class="small text-muted"><?= e(date('d.m. H:i', strtotime($m['created_at']))) ?> · an <?= e($m['recipient']) ?></span><br>
                                        <strong><?= e($m['subject']) ?></strong>
                                    </summary>
                                    <div class="demo-mail-body"><?= preg_replace('~(https?://[^\s<]+)~', '<a href="$1">$1</a>', nl2br(e($m['body']))) ?></div>
                                </details>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require '../components/footer.php'; ?>
