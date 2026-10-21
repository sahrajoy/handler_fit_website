<?php
// Karte für ein Training. Erwartet $t (Zeile aus upcoming_trainings) und $loc.
$spots = free_spots($t);
?>
<article class="training-card">
    <div class="training-date">
        <span class="day"><?= date('d', strtotime($t['starts_at'])) ?></span>
        <span class="month"><?= mb_substr(MONTHS_DE[(int)date('n', strtotime($t['starts_at']))], 0, 3) ?></span>
    </div>
    <div class="training-info">
        <h3><?= e($t['title']) ?></h3>
        <ul class="training-meta">
            <li><?= icon('clock', 16) ?> <?= e(format_datetime_de($t['starts_at'])) ?> · <?= (int)$t['duration_min'] ?> Min.</li>
            <?php if ($t['location'] !== ''): ?>
                <li><?= icon('pin', 16) ?> <?= e($t['location']) ?></li>
            <?php endif; ?>
            <li><?= icon('users', 16) ?>
                <?= $spots > 0 ? ($spots === 1 ? 'Noch 1 Platz frei' : "Noch $spots Plätze frei") : 'Ausgebucht' ?>
            </li>
        </ul>
        <?php if (!empty($showDescription) && $t['description'] !== ''): ?>
            <p class="training-desc"><?= nl2br(e($t['description'])) ?></p>
        <?php endif; ?>
        <?php if ($spots > 0): ?>
            <a class="btn btn-primary btn-sm" href="<?= $loc ?>booking.php?training=<?= (int)$t['id'] ?>#anmeldung">Voranmelden</a>
        <?php else: ?>
            <span class="btn btn-outline-secondary btn-sm disabled">Ausgebucht</span>
        <?php endif; ?>
    </div>
</article>
