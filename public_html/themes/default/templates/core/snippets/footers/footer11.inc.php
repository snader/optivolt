<footer id="footer">
    <div class="footer-cta">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column has-text-centered">
                    <a href="<?= $sCTAEmailUrl ?>" class="cta">
                        <div class="icon">
                            <i class="far fa-envelope"></i>
                        </div>
                        <span><?= _e(SiteTranslations::get('site_send_email')) ?></span>
                        <span class="action"><?= $sCTAEmail ?></span>
                    </a>
                </div>
                <div class="column has-text-centered">
                    <a href="<?= $sCTAPhoneUrl ?>" class="cta">
                        <div class="icon">
                            <i class="fas fa-phone" aria-hidden="true"></i>
                        </div>
                        <span><?= _e(SiteTranslations::get('site_call_us')) ?></span>
                        <span class="action"><?= $sCTAPhone ?></span>
                    </a>
                </div>
                <div class="column has-text-centered">
                    <a href="#" class="cta">
                        <div class="icon">
                            <i class="fas fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <span><?= _e(SiteTranslations::get('site_visit_us')) ?></span>
                        <span class="action"><?= _e(Settings::get("clientStreet")) ?>, <?= _e(Settings::get("clientCity")) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-container">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <div class="footer-col">
                        <!-- Logo -->
                        <div class="logo">
                            <a href="<?= getUrlProtocol() . Locales::getCurrentURLFormat() ?>" itemprop="url">
                                <img src="<?= getSiteImage('logo.svg') ?>" alt="<?= _e(CLIENT_HTTP_URL) ?>" width="400" height="200">
                            </a>
                        </div>
                        <!-- /Logo -->
                    </div>
                </div>
                <?php
                $aFooterLinks = PageManager::getPagesByFilter(['inFooter' => 1, 'languageId' => Locales::language()]);
                if (!empty($aFooterLinks)) {
                    ?>
                    <div class="column">
                        <div class="footer-col">
                            <!-- Footer links -->
                            <div class="links">
                                <?php foreach ($aFooterLinks as $oFooterLink) { ?>
                                    <a href="<?= $oFooterLink->getBaseUrlPath() ?>">
                                        <?= $oFooterLink->getShortTitle() ?>
                                    </a>
                                <?php } ?>
                            </div>
                            <!-- Footer links -->
                        </div>
                    </div>
                <?php } ?>

                <div class="column">
                    <div class="footer-col">
                        <!-- Social media -->
                        <div class="socials">
                            <?php if (!empty(Settings::get('facebookLink'))) { ?>
                                <a href="<?= _e(Settings::get('facebookLink')) ?>" target="_blank" class="facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            <?php } ?>
                            <?php if (!empty(Settings::get('twitterLink'))) { ?>
                                <a href="<?= _e(Settings::get('twitterLink')) ?>" target="_blank" class="twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            <?php } ?>
                            <?php if (!empty(Settings::get('instagramLink'))) { ?>
                                <a href="<?= _e(Settings::get('instagramLink')) ?>" target="_blank" class="instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php } ?>
                            <?php if (!empty(Settings::get('youtubeLink'))) { ?>
                                <a href="<?= _e(Settings::get('youtubeLink')) ?>" target="_blank" class="youtube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            <?php } ?>
                            <?php if (!empty(Settings::get('googleLink'))) { ?>
                                <a href="<?= _e(Settings::get('googleLink')) ?>" target="_blank" class="google">
                                    <i class="fab fa-google"></i>
                                </a>
                            <?php } ?>
                            <?php if (!empty(Settings::get('linkedInLink'))) { ?>
                                <a href="<?= _e(Settings::get('linkedInLink')) ?>" target="_blank" class="linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            <?php } ?>
                        </div>
                        <!-- /Social media -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>