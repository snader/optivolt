<footer id="footer">
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
                <div class="column">
                    <div class="footer-col">
                        <!-- Address -->
                        <div class="address">
                            <?= _e(Settings::get("clientStreet")) ?>,
                            <?= _e(Settings::get("clientPostalCode")) ?>
                            <?= _e(Settings::get("clientCity")) ?>
                        </div>
                        <!-- /Address -->

                        <!-- Contact -->
                        <div class="contact">
                            <a href="<?= $sCTAPhoneUrl ?>" class="footer-contact-phone">
                                <i class="fas fa-phone" aria-hidden="true"></i>
                                <?= $sCTAPhone ?>
                            </a>
                            <?php if ($sCTAEmail) { ?>
                                |
                                <a href="<?= $sCTAEmailUrl ?>" class="footer-contact-email">
                                    <i class="far fa-envelope"></i>
                                    <?= $sCTAEmail ?>
                                </a>
                            <?php } ?>
                        </div>
                        <!-- /Contact -->
                    </div>
                </div>


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
            </div>
        </div>
    </div>
</footer>