<?php
// Buchungssystem: Der Betreiber legt Trainings an, Kunden melden sich vor,
// der Betreiber bestätigt oder lehnt per Link aus der E-Mail (oder im Adminbereich) ab.

const BOOKING_STATUS = [
    'pending' => 'Angefragt',
    'confirmed' => 'Bestätigt',
    'declined' => 'Abgelehnt',
    'cancelled' => 'Storniert',
];

const TRAINING_COUNTS_SQL = '
    (SELECT COUNT(*) FROM bookings b WHERE b.training_id = t.id AND b.status = \'confirmed\') AS confirmed_count,
    (SELECT COUNT(*) FROM bookings b WHERE b.training_id = t.id AND b.status = \'pending\') AS pending_count';

function upcoming_trainings(?int $limit = null): array
{
    $sql = 'SELECT t.*, ' . TRAINING_COUNTS_SQL . ' FROM trainings t WHERE t.starts_at >= :now ORDER BY t.starts_at';
    if ($limit) {
        $sql .= ' LIMIT ' . (int)$limit;
    }
    $stmt = db()->prepare($sql);
    $stmt->execute(['now' => date('Y-m-d H:i')]);
    return $stmt->fetchAll();
}

function past_trainings(int $limit = 20): array
{
    $stmt = db()->prepare('SELECT t.*, ' . TRAINING_COUNTS_SQL . ' FROM trainings t WHERE t.starts_at < :now ORDER BY t.starts_at DESC LIMIT ' . $limit);
    $stmt->execute(['now' => date('Y-m-d H:i')]);
    return $stmt->fetchAll();
}

function find_training(int $id): ?array
{
    $stmt = db()->prepare('SELECT t.*, ' . TRAINING_COUNTS_SQL . ' FROM trainings t WHERE t.id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function free_spots(array $training): int
{
    return max(0, (int)$training['capacity'] - (int)$training['confirmed_count']);
}

function bookings_for_training(int $trainingId): array
{
    $stmt = db()->prepare('SELECT * FROM bookings WHERE training_id = ? ORDER BY created_at');
    $stmt->execute([$trainingId]);
    return $stmt->fetchAll();
}

function find_booking_by(string $column, string $value): ?array
{
    $column = match ($column) {
        'id' => 'b.id',
        'admin_token' => 'b.admin_token',
        'customer_token' => 'b.customer_token',
    };
    $stmt = db()->prepare("SELECT b.*, t.title, t.starts_at, t.duration_min, t.location, t.capacity
        FROM bookings b JOIN trainings t ON t.id = b.training_id WHERE $column = ?");
    $stmt->execute([$value]);
    return $stmt->fetch() ?: null;
}

function training_summary(array $row): string
{
    return $row['title'] . "\n"
        . format_datetime_de($row['starts_at']) . ' (' . $row['duration_min'] . " Min.)\n"
        . ($row['location'] !== '' ? 'Ort: ' . $row['location'] . "\n" : '');
}

// Legt eine Voranmeldung an. Gibt eine Fehlermeldung zurück oder null bei Erfolg.
function create_booking(array $training, string $name, string $email, string $phone, string $message): ?string
{
    if (strtotime($training['starts_at']) < time()) {
        return 'Dieser Termin liegt bereits in der Vergangenheit.';
    }
    if (free_spots($training) === 0) {
        return 'Dieser Termin ist leider schon ausgebucht.';
    }
    $dup = db()->prepare('SELECT COUNT(*) FROM bookings WHERE training_id = ? AND lower(email) = lower(?) AND status IN (\'pending\', \'confirmed\')');
    $dup->execute([$training['id'], $email]);
    if ($dup->fetchColumn() > 0) {
        return 'Mit dieser E-Mail-Adresse gibt es für diesen Termin bereits eine Anmeldung.';
    }

    $adminToken = bin2hex(random_bytes(24));
    $customerToken = bin2hex(random_bytes(24));
    db()->prepare('INSERT INTO bookings (training_id, name, email, phone, message, admin_token, customer_token, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
        ->execute([$training['id'], $name, $email, $phone, $message, $adminToken, $customerToken, date('Y-m-d H:i:s')]);

    $summary = training_summary($training);
    $spots = free_spots($training);

    send_mail(
        config('office_email'),
        "Neue Voranmeldung: $name, {$training['title']}",
        "Neue Voranmeldung über die Website:\n\n"
        . $summary
        . "Freie Plätze (bestätigt gezählt): $spots von {$training['capacity']}\n\n"
        . "Name:    $name\nE-Mail:  $email\n" . ($phone !== '' ? "Telefon: $phone\n" : '')
        . ($message !== '' ? "\nNachricht:\n$message\n" : '')
        . "\nAnmeldung bestätigen oder ablehnen:\n" . absolute_url('booking_manage.php?t=' . $adminToken) . "\n",
        $email,
        $name
    );

    send_mail(
        $email,
        "Deine Voranmeldung: {$training['title']}",
        "Hallo $name,\n\n"
        . "danke für deine Voranmeldung! Ich habe sie erhalten:\n\n"
        . $summary
        . "\nWichtig: Der Termin ist erst fix, wenn ich ihn dir per E-Mail bestätigt habe. "
        . "Ich melde mich so schnell wie möglich bei dir.\n\n"
        . "Falls du doch nicht kannst, kannst du die Anmeldung hier zurückziehen:\n"
        . absolute_url('booking_cancel.php?t=' . $customerToken) . "\n\n"
        . "Sportliche Grüße\nHandler Fit\n"
    );

    return null;
}

// Bestätigt eine Anmeldung. Gibt eine Fehlermeldung zurück oder null bei Erfolg.
function confirm_booking(array $booking): ?string
{
    if ($booking['status'] === 'confirmed') {
        return null;
    }
    if ($booking['status'] !== 'pending') {
        return 'Diese Anmeldung wurde bereits ' . strtolower(BOOKING_STATUS[$booking['status']]) . '.';
    }
    $training = find_training((int)$booking['training_id']);
    if (free_spots($training) === 0) {
        return 'Der Termin ist bereits voll (' . $training['capacity'] . ' bestätigte Plätze). Erhöhe die Teilnehmerzahl im Adminbereich oder lehne die Anmeldung ab.';
    }

    set_booking_status((int)$booking['id'], 'confirmed');
    send_mail(
        $booking['email'],
        "Termin bestätigt: {$booking['title']}",
        "Hallo {$booking['name']},\n\n"
        . "dein Termin ist bestätigt, ich freue mich auf dich!\n\n"
        . training_summary($booking)
        . "\nFalls dir etwas dazwischenkommt, sag bitte rechtzeitig über diesen Link ab:\n"
        . absolute_url('booking_cancel.php?t=' . $booking['customer_token']) . "\n\n"
        . "Sportliche Grüße\nHandler Fit\n"
    );
    return null;
}

function decline_booking(array $booking): ?string
{
    if ($booking['status'] === 'declined') {
        return null;
    }
    if ($booking['status'] !== 'pending' && $booking['status'] !== 'confirmed') {
        return 'Diese Anmeldung wurde bereits ' . strtolower(BOOKING_STATUS[$booking['status']]) . '.';
    }

    set_booking_status((int)$booking['id'], 'declined');
    send_mail(
        $booking['email'],
        "Termin leider nicht möglich: {$booking['title']}",
        "Hallo {$booking['name']},\n\n"
        . "leider kann ich deine Anmeldung für diesen Termin nicht bestätigen:\n\n"
        . training_summary($booking)
        . "\nSchau gerne nach einem anderen Termin:\n" . absolute_url('booking.php') . "\n\n"
        . "Oder schreib mir einfach, dann finden wir gemeinsam eine Alternative.\n\n"
        . "Sportliche Grüße\nHandler Fit\n"
    );
    return null;
}

function cancel_booking_by_customer(array $booking): ?string
{
    if ($booking['status'] === 'cancelled') {
        return null;
    }
    if ($booking['status'] === 'declined') {
        return 'Diese Anmeldung wurde bereits abgelehnt.';
    }

    set_booking_status((int)$booking['id'], 'cancelled');
    send_mail(
        config('office_email'),
        "Absage: {$booking['name']}, {$booking['title']}",
        "{$booking['name']} ({$booking['email']}) hat die Anmeldung zurückgezogen"
        . ($booking['status'] === 'confirmed' ? ' (war bereits bestätigt, der Platz ist wieder frei)' : '') . ":\n\n"
        . training_summary($booking),
        $booking['email'],
        $booking['name']
    );
    return null;
}

// Sagt ein Training ab: alle offenen und bestätigten Teilnehmer werden informiert.
function cancel_training(int $trainingId): void
{
    $training = find_training($trainingId);
    if (!$training) {
        return;
    }
    foreach (bookings_for_training($trainingId) as $b) {
        if ($b['status'] === 'pending' || $b['status'] === 'confirmed') {
            send_mail(
                $b['email'],
                "Termin abgesagt: {$training['title']}",
                "Hallo {$b['name']},\n\n"
                . "leider muss ich folgenden Termin absagen:\n\n"
                . training_summary($training)
                . "\nEs tut mir leid! Hier findest du weitere Termine:\n" . absolute_url('booking.php') . "\n\n"
                . "Sportliche Grüße\nHandler Fit\n"
            );
        }
    }
    db()->prepare('DELETE FROM trainings WHERE id = ?')->execute([$trainingId]);
}

function set_booking_status(int $id, string $status): void
{
    db()->prepare('UPDATE bookings SET status = ?, decided_at = ? WHERE id = ?')
        ->execute([$status, date('Y-m-d H:i:s'), $id]);
}
