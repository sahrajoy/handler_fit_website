<?php
// Seitenende: Footer, Skripte und schließende Tags. Gegenstück zu head.php.
?>
</main>

<footer id="footer">
    <div class="footer-stripe"></div>
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <a href="<?= $loc ?>index.php"><img src="<?= $loc ?>assets/images/handler.png" alt="Handler Personal Trainer" class="footer-logo"></a>
                    <p class="footer-claim">Dein Weg zu einem fitteren Leben, mit persönlicher Betreuung, die dich motiviert und unterstützt.</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <h4>Angebot</h4>
                <ul>
                    <li><a href="<?= $loc ?>onlinecoaching.php">Online Coaching</a></li>
                    <li><a href="<?= $loc ?>personaltraining.php">Personal Training</a></li>
                    <li><a href="<?= $loc ?>booking.php">Termine buchen</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <h4>Info</h4>
                <ul>
                    <li><a href="<?= $loc ?>about.php">Über mich</a></li>
                    <li><a href="<?= $loc ?>contact.php">Kontakt</a></li>
                    <li><a href="<?= $loc ?>imprint.php">Impressum</a></li>
                    <li><a href="<?= $loc ?>imprint.php#datenschutz">Datenschutz</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4>Kontakt</h4>
                <ul class="footer-contact">
                    <li><a href="mailto:<?= e(config('office_email')) ?>"><?= icon('mail') ?> <?= e(config('display_email')) ?></a></li>
                    <li><a href="https://www.instagram.com/handler_fit/" target="_blank" rel="noopener"><?= icon('instagram') ?> @handler_fit</a></li>
                </ul>
                <a class="btn btn-primary mt-2" href="<?= $loc ?>contact.php?anliegen=erstgespraech">Kostenloses Erstgespräch</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container text-center">&copy; <?= date('Y') ?> Sahra Joy Stursa</div>
    </div>
</footer>

<script src="<?= $loc ?>assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
