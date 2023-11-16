<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?= $oPageLayout->sWindowTitle ?></title>
    <link rel="shortcut icon" href="/favicon.ico"/>

    <?php
    $oPageLayout->addStylesheet(getSiteCss('layout', 'core'), 1);
    echo '<link rel="stylesheet" href="' . _e($oPageLayout->getCombinedStyles($sCacheControllerKey, 'print')) . '" media="print" />' . PHP_EOL;
    ?>
    <?= '<style>' . file_get_contents(DOCUMENT_ROOT . $oPageLayout->getCombinedStyles($sCacheControllerKey)) . '</style>' ?>
    <link rel="stylesheet" href="<?= getSiteCss('browserwarning') ?>"/>
</head>
<body class="browser-warning">
<div class="container">
    <div class="columns is-centered">
        <div class="column is-two-thirds">
            <div class="browserwarning-info">
                <b><?= _e(SiteTranslations::get('site_outdated_browser')) ?></b>
                <br/><br/>
                <p><?= _e(SiteTranslations::get('site_please_update_browser')) ?></p>
            </div>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            <a class="browserwarning-tile" href="http://www.google.com/chrome" target="_blank"
               title="Download Google Chrome">
                <img src="<?= getSiteImage('chrome.png', 'browserwarning') ?>" alt="Google Chrome"/>
                <div class="browser-name">
                    Google Chrome
                </div>
            </a>
        </div>
        <div class="column">
            <a class="browserwarning-tile" href="http://www.mozilla.com/firefox/" target="_blank"
               title="Download Mozilla Firefox">
                <img src="<?= getSiteImage('mozilla.png', 'browserwarning') ?>" alt="Mozilla Firefox"/>

                <div class="browser-name">
                    Mozilla Firefox
                </div>
            </a>
        </div>
        <div class="column">
            <a class="browserwarning-tile" href="http://www.apple.com/safari/" target="_blank" title="Download Safari">
                <img src="<?= getSiteImage('safari.png', 'browserwarning') ?>" alt="Safari"/>

                <div class="browser-name">
                    Safari
                </div>
            </a>
        </div>
    </div>
    <div class="columns is-centered">
        <div class="has-text-centered">
            <a class="button-browserwarning" href="?action=dontShowBrowserWarning">
                <?= _e(SiteTranslations::get('site_visit_site_old_browser')) ?><br/>
            </a>
        </div>
    </div>
</div>