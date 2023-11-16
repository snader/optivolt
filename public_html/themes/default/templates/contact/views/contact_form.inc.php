<div class="section">
    <!-- Navigation Breadcrumbs -->
    <?php include getSiteSnippet('navigationBreadcrumbs'); ?>
    <!-- /Navigation Breadcrumbs -->
    <div class="container">
        <div class="columns">
            <div class="column">
                <!-- Map -->
                <div class="contact-google-maps" id="contact-map"><?= _e(SiteTranslations::get('site_google_maps_loading')) ?></div>
                <!-- /Map -->
            </div>
        </div>

        <div class="columns">
            <!-- Content -->
            <div class="column">
                <h1 class="title is-size-2"><?= _e($oPage->title) ?></h1>
                <?= $oPage->intro ?>
                <?= $oPage->content ?>
            </div>
            <!-- /Content -->

            <!-- Form -->
            <div class="column">
                <form method="post" class="validateFormInline">
                    <?php include getSiteSnippet('errorBox'); ?>

                    <div class="columns is-desktop">
                        <div class="column">
                            <label class="label" for="name"><?= _e(SiteTranslations::get('site_name')) ?> *</label>
                            <div class="control has-icons-right">
                                <input placeholder="<?= _e(SiteTranslations::get('site_name')) ?> *" class="input" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_name')) ?>" id="name" name="name" type="text" value="<?= _e(http_post('name')) ?>"/>
                            </div>
                        </div>
                        <div class="column">
                            <label class="label" for="organisation"><?= _e(SiteTranslations::get('site_organization')) ?></label>
                            <div class="control has-icons-right">
                                <input placeholder="<?= _e(SiteTranslations::get('site_organization')) ?>" class="input" id="organisation" name="organisation" type="text" value="<?= _e(http_post('organisation')) ?>"/>
                            </div>
                        </div>
                    </div>

                    <div class="columns is-desktop">
                        <div class="column">
                            <label class="label" for="email"><?= _e(SiteTranslations::get('site_email')) ?> *</label>
                            <div class="control has-icons-left has-icons-right">
                                <input placeholder="<?= SiteTranslations::get('site_email') ?> *" class="input"
                                       data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_email')) ?>"
                                       data-rule-email="true" data-msg-email="<?= _e(SiteTranslations::get('site_email_not_valid')) ?>" id="email" name="email" type="email" value="<?= _e(http_post('email')) ?>"/>
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </div>
                        </div>
                        <div class="column">
                            <label class="label" for="phone"><?= _e(SiteTranslations::get('site_phone')) ?></label>
                            <div class="control has-icons-left has-icons-right">
                                <input placeholder="<?= _e(SiteTranslations::get('site_phone')) ?>" class="input" id="phone" name="phone" type="tel" value="<?= _e(http_post('phone')) ?>"/>
                                <span class="icon is-small is-left"><i class="fas fa-phone"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label" for="subject"><?= _e(SiteTranslations::get('site_subject')) ?></label>
                                <div class="control has-icons-right">
                                    <input placeholder="<?= _e(SiteTranslations::get('site_subject')) ?>" class="input" id="subject" name="subject" type="text" value="<?= _e(http_post('subject', http_get('subject'))) ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label" for="message"><?= _e(SiteTranslations::get('site_message')) ?> *</label>
                                <div class="control has-icons-right">
                                    <textarea placeholder="<?= _e(SiteTranslations::get('site_message')) ?> *" class="textarea" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_message')) ?>" id="message" name="message"><?= _e(http_post('message', http_get('message'))) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div style="display: none;">
                        <label><?= _e(SiteTranslations::get('site_spam_check')) ?></label>
                        <input autocomplete="off" type="text" name="rumpelstiltskin-empty" value=""/>
                    </div>
                    <div style="display: none;">
                        <label><?= _e(SiteTranslations::get('site_spam_check')) ?></label>
                        <input autocomplete="off" type="text" name="rumpelstiltskin-filled" value="repelsteeltje"/>
                    </div>

                    <input type="hidden" name="action" value="sendForm"/>

                    <div class="field">
                        <div class="control has-text-right">
                            <?php
                            if (SettingManager::getSettingByName('reCaptchaSecretKey') && SettingManager::getSettingByName('reCaptchaWebsiteKey') && Settings::get('reCaptchaSecretKey') && Settings::get('reCaptchaWebsiteKey')) {
                                $oCaptcha = new Captcha('button is-primary', 'done', _e(SiteTranslations::get('site_send')));
                                $oPageLayout->addJavascript($oCaptcha->getJs(), 0, 'captcha-bottom');
                                echo $oCaptcha->getHtml();
                            } else { ?>
                                <button class="button is-primary"><?= _e(SiteTranslations::get('site_send')) ?></button>
                            <?php } ?>
                        </div>
                    </div>

                </form>
            </div>
            <!-- /Form -->
        </div>
    </div>
</div>
