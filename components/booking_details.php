<?php
// Übersicht einer Anmeldung. Erwartet $booking (aus find_booking_by).
$statusClass = [
    'pending' => 'text-bg-warning',
    'confirmed' => 'text-bg-success',
    'declined' => 'text-bg-secondary',
    'cancelled' => 'text-bg-secondary',
][$booking['status']];
?>
<div class="booking-details">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <h2 class="h4 mb-0"><?= e($booking['title']) ?></h2>
        <span class="badge <?= $statusClass ?>"><?= e(BOOKING_STATUS[$booking['status']]) ?></span>
    </div>
    <dl class="row mb-0">
        <dt class="col-sm-4">Termin</dt>
        <dd class="col-sm-8"><?= e(format_datetime_de($booking['starts_at'])) ?> (<?= (int)$booking['duration_min'] ?> Min.)</dd>
        <?php if ($booking['location'] !== ''): ?>
            <dt class="col-sm-4">Ort</dt>
            <dd class="col-sm-8"><?= e($booking['location']) ?></dd>
        <?php endif; ?>
        <dt class="col-sm-4">Name</dt>
        <dd class="col-sm-8"><?= e($booking['name']) ?></dd>
        <?php if (!empty($showContact)): ?>
            <dt class="col-sm-4">E-Mail</dt>
            <dd class="col-sm-8"><a href="mailto:<?= e($booking['email']) ?>"><?= e($booking['email']) ?></a></dd>
            <?php if ($booking['phone'] !== ''): ?>
                <dt class="col-sm-4">Telefon</dt>
                <dd class="col-sm-8"><?= e($booking['phone']) ?></dd>
            <?php endif; ?>
            <?php if ($booking['message'] !== ''): ?>
                <dt class="col-sm-4">Nachricht</dt>
                <dd class="col-sm-8"><?= nl2br(e($booking['message'])) ?></dd>
            <?php endif; ?>
        <?php endif; ?>
    </dl>
</div>
