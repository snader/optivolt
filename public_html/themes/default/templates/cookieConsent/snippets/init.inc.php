<?php
if (Settings::get('cc_enabled')) {
    //Do required checks before show CookieConsent
    $bConsentHasMissingSettings   = false;
    $bConsentHasMissingPolicyPage = false;

    if (!Settings::exists('cc_bgcolor') || !Settings::exists('cc_textcolor') || !Settings::exists('cc_btnbgcolor') || !Settings::exists('cc_btntextcolor')) {
        $bConsentHasMissingSettings = true;
    }

    if (!PageManager::getPageByName('cookiepolicy')) {
        $bConsentHasMissingPolicyPage = true;
    }

    if (!$bConsentHasMissingSettings && !$bConsentHasMissingPolicyPage) {
        $oPageLayout->addJavascript(getSitePath('js/cookieconsent.min.js'));
        ?>

        <script>
            //Init for cookieconsent
            window.addEventListener("load", function () {
                window.cookieconsent.initialise({
                    "palette": {
                        "popup": {
                            "background": "<?= Settings::get('cc_bgcolor') ?>",
                            "text": "<?= Settings::get('cc_textcolor') ?>",
                        },
                        "button": {
                            "background": "<?= Settings::get('cc_btnbgcolor') ?>",
                            "text": "<?= Settings::get('cc_btntextcolor') ?>"
                        }
                    },
                    "position": "bottom-left",
                    "theme": "edgeless",
                    "type": "opt-in",
                    "content": {
                        "message": "<?= _e(SiteTranslations::get('cc_text')) ?>",
                        "deny": "<?= _e(SiteTranslations::get('cc_dismiss')) ?>",
                        "allow": "<?= _e(SiteTranslations::get('cc_accept')) ?>",
                        "link": "<?= _e(SiteTranslations::get('cc_details')) ?>",
                        "href": "<?= PageManager::getPageByName('cookiepolicy')
                            ->getBaseUrlPath() ?>"
                    },
                })
            });
        </script>
    <?php }
} ?>