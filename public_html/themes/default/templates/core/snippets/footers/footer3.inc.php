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

                <div class="column">
                    <div class="footer-col">
                        <div class="title">
                            <?= _e(SiteTranslations::get('site_contact')) ?>
                        </div>

                        <!-- Address -->
                        <div class="address">
                            <?= _e(Settings::get("clientStreet")) ?><br/>
                            <?= _e(Settings::get("clientPostalCode")) ?>
                            <?= _e(Settings::get("clientCity")) ?>
                        </div>
                        <!-- /Address -->

                        <!-- Contact -->
                        <div class="contact">
                            <a href="<?= $sCTAPhoneUrl ?>" class="footer-contact-phone">
                                <i class="fas fa-phone" aria-hidden="true"></i>
                                <?= $sCTAPhone ?>
                            </a><br/>
                            <?php if ($sCTAEmail) { ?>
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
                        <div class="title">
                            <?= _e(SiteTranslations::get('site_newsletter')) ?>
                        </div>
                        <div class="newsletter">
                            <p><?= _e(SiteTranslations::get('site_newsletter_intro')) ?></p>
                            <form class="validateFormInline" method="post">
                                <input type="hidden" name="action" value="sendNewsletterForm"/>
                                <div class="field has-addons">
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= SiteTranslations::get('site_email') ?> *" class="input" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_email')) ?>" data-rule-email="true" data-msg-email="<?= _e(SiteTranslations::get('site_email_not_valid')) ?>" id="newsletterEmail" name="newsletterEmail" type="email" value="<?= _e(http_post('newsletterEmail')) ?>"/>
                                    </div>
                                    <div class="control">
                                        <button class="button is-primary"><i class="fa fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="footer-copyright">
                        <div class="columns">
                            <?php
                            $aFooterLinks = PageManager::getPagesByFilter(['inFooter' => 1, 'languageId' => Locales::language()]);
                            if (!empty($aFooterLinks)) {
                                ?>
                                <div class="column">
                                    <!-- Footer links -->
                                    <div class="links">
                                        <?php foreach ($aFooterLinks as $oFooterLink) { ?>
                                            <a href="<?= $oFooterLink->getBaseUrlPath() ?>">
                                                <?= $oFooterLink->getShortTitle() ?>
                                            </a>
                                        <?php }
                                        ?>
                                    </div>
                                    <!-- Footer links -->
                                </div>
                            <?php } ?>
                            <div class="column">
                                <div class="copyright">
                                    &copy; <?= date('Y') ?> <?= _e(CLIENT_NAME) ?> |
                                </div>
                                <div class="copyright-lv">
                                    <?= _e(SiteTranslations::get('site_created_by')) ?> <a href="https://lv.com" target="_blank" rel="nofollow">lv</a> - 2020
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>