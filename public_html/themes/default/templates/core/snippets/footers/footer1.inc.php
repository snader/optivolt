<footer id="footer">
    <div class="footer-container">
        <div class="container">
            <div class="columns">
                <div class="column is-hidden-mobile">
                    <div class="footer-col">
                        <?php
                        $aFooterLinks = PageManager::getPagesByFilter(['inFooter' => 1, 'languageId' => Locales::language()]);

                        if (moduleExists('newsItems')) {
                            $aNewsItemsFooter = NewsItemManager::getNewsItemsByFilter([], 1);
                            if (!empty($aNewsItemsFooter)) { ?>

                                <h3><?= _e(SiteTranslations::get('site_latest_news')) ?></h3>
                                <div class="news">
                                    <?php foreach ($aNewsItemsFooter AS $oNewsItemFooter) { ?>
                                        <div class="item">
                                            <a class="item-title" href="<?= $oNewsItemFooter->getBaseUrlPath() ?>" title="<?= $oNewsItemFooter->getShortTitle() ?>">
                                                <?= $oNewsItemFooter->getShortTitle() ?>
                                            </a>
                                            <span class="item-date"><?= Date::strToDate($oNewsItemFooter->date)->format('%d-%m-%Y') ?></span>
                                            <p><?= firstXCharacters($oNewsItemFooter->intro, 50); ?></p>
                                            <a href="<?= $oNewsItemFooter->getBaseUrlPath() ?>">
                                                <?= _e(SiteTranslations::get('site_read_more')) ?> <i class="far fa-chevron-right"></i>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>

                            <?php }
                        } ?>
                        <!-- Footer links -->
                        <?php if (!empty($aFooterLinks)) { ?>
                            <div class="title is-hidden-mobile">
                                <?= _e(SiteTranslations::get('site_links')) ?>
                            </div>
                            <div class="links">
                                <?php foreach ($aFooterLinks as $oFooterLink) { ?>
                                    <a href="<?= $oFooterLink->getBaseUrlPath() ?>">
                                        <?= $oFooterLink->getShortTitle() ?>
                                    </a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <!-- Footer links -->
                    </div>
                </div>

                <div class="column">
                    <div class="footer-col">
                        <h3><?= _e(SiteTranslations::get('site_newsletter')) ?></h3>
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

                <div class="column">
                    <!-- Address & contact -->
                    <div class="footer-col has-background">
                        <h3><?= _e(SiteTranslations::get('site_contact')) ?></h3>
                        <div class="address">
                            <?= _e(Settings::get("clientStreet")) ?><br/>
                            <?= _e(Settings::get("clientPostalCode")) ?>
                            <?= _e(Settings::get("clientCity")) ?>
                        </div>
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
                    </div>
                    <!-- /Address & contact -->
                </div>

            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-copyright">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <div class="copyright">
                        &copy; <?= date('Y') ?> - <?= _e(CLIENT_NAME) ?>
                    </div>
                </div>
                <div class="column">
                    <div class="copyright-lv">
                        <?= _e(SiteTranslations::get('site_design_realization')) ?>: <a href="https://lv.com" target="_blank" rel="nofollow">lv</a> - 2020
                    </div>
                </div>
                <div class="column">
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
    <!-- /Copyright -->
</footer>
