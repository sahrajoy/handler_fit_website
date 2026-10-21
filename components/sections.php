<?php
// Wiederverwendbare Seitenbausteine

function page_header(string $loc, string $title, string $subtitle, string $image): void
{ ?>
    <header class="page-header" style="background-image: url('<?= $loc ?>assets/images/<?= e($image) ?>')">
        <div class="page-header-overlay">
            <div class="container">
                <h1><?= e($title) ?></h1>
                <p class="lead"><?= e($subtitle) ?></p>
            </div>
        </div>
    </header>
<?php }

// Sprungnavigation zu den Abschnitten einer Seite
function section_nav(array $sections): void
{ ?>
    <nav class="section-nav" aria-label="Abschnitte">
        <div class="container">
            <?php foreach ($sections as $s): ?>
                <a href="#<?= e($s['id']) ?>"><?= e($s['title']) ?></a>
            <?php endforeach; ?>
        </div>
    </nav>
<?php }

// Abschnitt mit Bild und Text; $s: id, kicker, title, image, icon, paragraphs[], bullets[]
function feature_section(string $loc, array $s, bool $reverse): void
{ ?>
    <section class="feature-section<?= $reverse ? ' feature-alt' : '' ?>" id="<?= e($s['id']) ?>">
        <div class="container">
            <div class="row align-items-center g-5<?= $reverse ? ' flex-lg-row-reverse' : '' ?>">
                <div class="col-lg-6">
                    <div class="feature-image">
                        <img src="<?= $loc ?>assets/images/<?= e($s['image']) ?>" alt="<?= e($s['title']) ?>" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <?php if (!empty($s['icon'])) echo noun_icon($loc, $s['icon']); ?>
                        <span class="kicker"><?= e($s['kicker']) ?></span>
                    </div>
                    <h2><?= e($s['title']) ?></h2>
                    <?php foreach ($s['paragraphs'] as $p): ?>
                        <p><?= e($p) ?></p>
                    <?php endforeach; ?>
                    <?php if (!empty($s['bullets'])): ?>
                        <ul class="check-list">
                            <?php foreach ($s['bullets'] as $b): ?>
                                <li><?= icon('check', 20) ?><span><?= e($b) ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php }

function cta_band(string $loc, string $title, string $text): void
{ ?>
    <section class="cta-band">
        <div class="container text-center">
            <h2><?= e($title) ?></h2>
            <p><?= e($text) ?></p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a class="btn btn-light btn-lg" href="<?= $loc ?>contact.php?anliegen=erstgespraech">Kostenloses Erstgespräch</a>
                <a class="btn btn-outline-light btn-lg" href="<?= $loc ?>booking.php">Termine ansehen</a>
            </div>
        </div>
    </section>
<?php }
