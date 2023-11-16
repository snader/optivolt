<!-- Footer pusher -->
<div class="footer-pusher"></div>
<!-- /Footer pusher -->

<footer id="footer" class="cf">
    <div class="footer-container cf">
        <div class="container">
            <div class="row">
                <div class="col-33 no-margin-bottom">
                    <!-- Address -->
                    <div class="footer-address cf">
                        <?= _e(Settings::get("clientStreet")) ?><br/>
                        <?= _e(Settings::get("clientPostalCode")) ?><br/>
                        <?= _e(Settings::get("clientCity")) ?><br/>
                    </div>
                    <!-- /Address -->

                    <!-- Social media -->
                    <div class="footer-socials cf">
                        <?php if (!empty(Settings::get('facebookLink'))) { ?>
                            <a href="<?= _e(Settings::get('facebookLink')) ?>" target="_blank" class="facebook">
                                <i class="fab fa-facebook"></i>
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
                                <i class="fab fa-linkedin"></i>
                            </a>
                        <?php } ?>
                    </div>
                    <!-- /Social media -->
                </div>

                <div class="col-33 no-margin-bottom">
                    <!-- Contact info -->
                    <div class="footer-contact cf">
                        <a href="<?= $sCTAPhoneUrl ?>" class="footer-contact-phone">
                            <i class="fas fa-phone" aria-hidden="true"></i>
                            <?= $sCTAPhone ?>
                        </a><br/>
                        <?php
                        if ($sCTAEmail) {
                            ?>
                            <a href="<?= $sCTAEmailUrl ?>" class="footer-contact-email">
                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                <?= $sCTAEmail ?>
                            </a>
                        <?php } ?>
                    </div>
                    <!-- /Contact info -->
                </div>
                <!-- Links -->

                <!-- Footer links -->
                <?php

                $aFooterLinks = PageManager::getPagesByFilter(['inFooter' => 1, 'languageId' => Locales::language()]);
                if (!empty($aFooterLinks)) {
                    ?>
                    <div class="col-33">
                        <div class="footer-links">
                            <?php

                            foreach ($aFooterLinks as $oFooterLink) { ?>
                                <a href="<?= $oFooterLink->getBaseUrlPath() ?>">
                                    <?= $oFooterLink->getShortTitle() ?>
                                </a>
                            <?php }
                            ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- Footer links -->
            </div>
        </div>
    </div>
    <!-- Copyright -->
    <div class="footer-copyright cf">
        <div class="container">
            <div class="row">
                <div class="col-50 no-margin-bottom cf">
                    &copy; <?= _e(CLIENT_NAME) ?> - <?= date('Y') ?>
                </div>
                <div class="col-50 no-margin-bottom cf">
                    <div class="footer-copyright-lv">
                        <?= _e(SiteTranslations::get('site_design_realization')) ?>: <a href="https://lv.com" target="_blank" rel="nofollow">lv</a> - 2020
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Copyright -->

    <!-- Footer header pusher -->
    <div class="footer-header-pusher"></div>
    <!-- /Footer header pusher -->
</footer>
