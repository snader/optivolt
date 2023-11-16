<!DOCTYPE html>
<html class="no-js" lang="<?= Locales::getLocale()->getLanguage()->code ?>">
<head>

    <meta charset="utf-8">

    <!-- Changeable page title -->
    <title><?= _e($oPageLayout->sWindowTitle) ?> | <?= _e(CLIENT_NAME) ?></title>

    <?php
    if(moduleExists('cookieConsent')){
        include_once getSiteSnippet('init', 'cookieConsent');
    }
    # add viewport
    if (!empty($oPageLayout->sViewport)) {
        echo '<meta name="viewport" content="' . _e($oPageLayout->sViewport) . '">' . PHP_EOL;
    }

    # add meta tags
    if (!empty($oPageLayout->sMetaDescription)) {
        echo '<meta name="description" content="' . _e($oPageLayout->sMetaDescription) . '">' . PHP_EOL;
    }
    if (!empty($oPageLayout->sMetaKeywords)) {
        echo '<meta name="keywords" content="' . _e($oPageLayout->sMetaKeywords) . '">' . PHP_EOL;
    }

    # add social tags
    if (!empty($oPageLayout->sOGTitle)) {
        echo '<meta property="og:title" content="' . _e($oPageLayout->sOGTitle) . '"/>' . PHP_EOL;
    }
    if (!empty($oPageLayout->sOGType)) {
        echo '<meta property="og:type" content="' . _e($oPageLayout->sOGType) . '"/>' . PHP_EOL;
    }
    if (!empty($oPageLayout->sOGUrl)) {
        echo '<meta property="og:url" content="' . _e($oPageLayout->sOGUrl) . '"/>' . PHP_EOL;
    }
    if (!empty($oPageLayout->sOGDescription)) {
        echo '<meta property="og:description" content="' . _e($oPageLayout->sOGDescription) . '"/>' . PHP_EOL;
    }
    if (!empty($oPageLayout->sOGImage)) {
        echo '<meta property="og:image" content="' . _e($oPageLayout->sOGImage) . '"/>' . PHP_EOL;
    }
    if (!empty($oPageLayout->sOGImageWidth)) {
        echo '<meta property="og:image:width" content="' . _e($oPageLayout->sOGImageWidth) . '"/>' . PHP_EOL;
    }
    if (!empty($oPageLayout->sOGImageHeight)) {
        echo '<meta property="og:image:height" content="' . _e($oPageLayout->sOGImageHeight) . '"/>' . PHP_EOL;
    }

    # add canonical
    if (!empty($oPageLayout->sCanonical) && $oPageLayout->sCanonical != getCurrentUrl()) {
        echo '<link rel="canonical" href="' . _e($oPageLayout->sCanonical) . '">' . PHP_EOL;
    }

    # add prev/next canonical
    if (!empty($oPageLayout->sRelPrev)) {
        echo '<link rel="prev" href="' . _e($oPageLayout->sRelPrev) . '" />' . PHP_EOL;
    }
    if (!empty($oPageLayout->sRelNext)) {
        echo '<link rel="next" href="' . _e($oPageLayout->sRelNext) . '" />' . PHP_EOL;
    }

    # add no index field
    if (empty($oPageLayout->bIndexable)) {
        echo '<meta name="robots" content="noindex, nofollow">' . PHP_EOL;
    }

    # add structured data
    if (!empty($oPageLayout->sStructuredData)) {
        echo '<script type="application/ld+json">' . $oPageLayout->sStructuredData . '</script>' . PHP_EOL;
    }

    # Required stylesheets
    $oPageLayout->addStylesheet(getVendorPath('fancybox/jquery.fancybox.min.css'), 1);
    $oPageLayout->addStylesheet(getSiteCss('layout'), 2);
    $oPageLayout->addStylesheet(getSiteCss('print'), 3, 'print');

    echo '<link rel="stylesheet" href="' . _e($oPageLayout->getCombinedStyles($sCacheControllerKey, 'print')) . '" media="print" />' . PHP_EOL;
    ?>
    <?= '<style>' . file_get_contents(DOCUMENT_ROOT . $oPageLayout->getCombinedStyles($sCacheControllerKey)) . '</style>' ?>

    <!-- Meta tags -->
    <meta name="author" content="<?= _e(CLIENT_NAME) ?>"/>
    <meta name="theme-color" content="<?= Settings::get('themeColor') ?>">

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="icon" type="image/png" href="<?= getSiteImage('icons/favicon/favicon-16x16.png') ?>" sizes="16x16">
    <link rel="icon" type="image/png" href="<?= getSiteImage('icons/favicon/favicon-32x32.png') ?>" sizes="32x32">
    <link rel="icon" type="image/png" href="<?= getSiteImage('icons/favicon/favicon-96x96.png') ?>" sizes="96x96">

    <!-- Apple touch icons -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-57x57.png') ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-60x60.png') ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-72x72.png') ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-76x76.png') ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-114x114.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-120x120.png') ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-144x144.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-152x152.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= getSiteImage('icons/apple-touch/apple-touch-icon-180x180.png') ?>">

    <!-- Android Chrome -->
    <link rel="icon" type="image/png" href="<?= getSiteImage('icons/android/android-chrome-192x192.png') ?>" sizes="192x192">

    <!-- Windows tiles -->
    <meta name="application-name" content="<?= _e(CLIENT_NAME) ?>"/>
    <meta name="msapplication-TileColor" content="#ffffff"/>
    <meta name="msapplication-square70x70logo" content="<?= getSiteImage('icons/windows-tiles/smalltile.png') ?>"/>
    <meta name="msapplication-square150x150logo" content="<?= getSiteImage('icons/windows-tiles/mediumtile.png') ?>"/>
    <meta name="msapplication-wide310x150logo" content="<?= getSiteImage('icons/windows-tiles/widetile.png') ?>"/>
    <meta name="msapplication-square310x310logo" content="<?= getSiteImage('icons/windows-tiles/largetile.png') ?>"/>

    <?php if (empty($_SESSION['bDontShowBrowserWarning'])) { ?>
        <!--[if lte IE 9]>
            <script>window.location = '<?= getUrlProtocol() . Locales::getCurrentURLFormat() ?>/browserwarning';</script>
        <![endif]-->

        <!-- since IE10 ignores conditional comments, we use this -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.querySelector('html').classList.contains('no-borderimage')) {
                    window.location = '<?= getUrlProtocol() . Locales::getCurrentURLFormat() ?>/browserwarning';
                }
            });
        </script>
    <?php } ?>

    <?php
    // cached top javascript combined to one
    $oPageLayout->addJavascript(getVendorPath('modernizr/modernizr-custom.min.js'), 1, 'top-cached');
    $oPageLayout->addJavascript(getVendorPath('picturefill/picturefill.min.js'), 2, 'top-cached');
    $oPageLayout->addJavascript(getVendorPath('respond/respond.min.js'), 3, 'top-cached');
    $oPageLayout->addJavascript(getVendorPath('lazySizes/lazysizes.min.js'), 4, 'top-cached');
    $oPageLayout->addJavascript(getVendorPath('lazySizes/plugins/unveilhooks/ls.unveilhooks.min.js'), 5, 'top-cached');
    $oPageLayout->addJavascript(getVendorPath('lazy-lv.min.js'), 6, 'top-cached');
    ?>

    <?= '<script>' . file_get_contents(DOCUMENT_ROOT . $oPageLayout->getCombinedJavascript('main', 'top-cached')) . '</script>' ?>

    <?php
    /**
     * @var \PageLayoutJavascript $oJavascript
     */
    # javascript top stuff
    foreach ($oPageLayout->getJavascripts('conversions_top') AS $oJavascript) {
        if ($oJavascript->getLocation()) {
            echo '<script src="' . _e($oJavascript->getLocation()) . '"></script>' . PHP_EOL;
        } else {
            echo '<script>' . $oJavascript->getScript() . '</script>' . PHP_EOL;
        }
    }
    ?>
    
    <!-- Head scripts -->
    <?php

    if (Settings::exists('headScripts')) {
        echo Settings::get('headScripts');
    }

    ?>

    <!-- /Head scripts -->
</head>
<body itemscope itemtype="http://schema.org/WebPage">
	 <!-- Body scripts -->
    <?php

    if (Settings::exists('bodyScripts')) {
        echo Settings::get('bodyScripts');
    } ?>
    <!-- /Body scripts -->

    <div class="site-container">

        <!-- Template overlay -->
        <div class="template-overlay"></div>
        <!-- /Template overlay -->

        <?php if (moduleExists('orders')) { ?>
            <!-- Mini basket -->
            <?php include getSiteSnippet('basket', 'orders') ?>
            <!-- /Mini basket -->
        <?php } ?>

        <?php

        // CTA information
        $sCTAPhoneUrl = 'tel:' . Settings::get('clientPhone');
        $sCTAPhone = Settings::get('clientPhone');
        $sCTAEmailUrl = 'mailto:' . Settings::get('clientEmail');
        $sCTAEmail = Settings::get('clientEmail');
        ?>

        <!-- Navigation off canvas -->
        <?php include getSiteSnippet('navigationOffCanvas'); ?>
        <!-- /Navigation off canvas -->

        <!-- Header -->
        <?php include getSiteSnippet('headers/header1'); ?>
        <!-- /Header -->

        <!-- Changeable content -->
        <div class="page-wrapper">
            <?php

            // include the actual page with changable content
            include_once $oPageLayout->sViewPath;
            ?>
        </div>
        <!-- /Changeable content -->

        <!-- Footer -->
        <?php include getSiteSnippet('footers/footer2'); ?>
        <!-- /Footer -->

        <?php
        if (ENVIRONMENT != 'production' && empty(Cookie::get('hideEnvironmentNotification'))) {
            include getSiteSnippet('environmentNotification');
        } ?>
    </div>

<!-- Bottom javascripts -->
<?php
// cached javascript combined to one
$oPageLayout->addJavascript(getVendorPath('jquery/jquery.min.js'), 1, 'bottom-cached');
$oPageLayout->addJavascript(getVendorPath('fancybox/jquery.fancybox.min.js'), 2, 'bottom-cached');
$oPageLayout->addJavascript(getVendorPath('autoNumeric.min.js'), 3, 'bottom-cached');
$oPageLayout->addJavascript(getVendorPath('validate/jquery.validate.min.js'), 4, 'bottom-cached');
$oPageLayout->addJavascript(getSitePath('js/base_functions.min.js'), 5, 'bottom-cached');
$oPageLayout->addJavascript(getVendorPath('matchHeight/jquery.matchHeight.min.js'), 6, 'bottom-cached');
$oPageLayout->addJavascript(getVendorPath('stickyfill/stickyfill.min.js'), 7, 'bottom-cached');
$oPageLayout->addJavascript(getVendorPath('slickslider/slick.min.js'), 8, 'bottom-cached');
$oPageLayout->addJavascript(getSitePath('js/main.min.js'), 9, 'bottom-cached');

# module based cached javascript
if (moduleExists('brandboxItems')) {
    $oPageLayout->addJavascript(getSitePath('js/brandbox.min.js', 'brandboxItems'), 15, 'bottom-cached');
}

if (moduleExists('orders')) {
    $oPageLayout->addJavascript(getSitePath('js/order_functions.min.js', 'orders'), 15, 'bottom-cached');
}

if (moduleExists('reservations')) {
    $oPageLayout->addJavascript(getVendorPath('ui/jquery-ui.min.js'), 15, 'bottom-cached');
}
?>
<script>
    var onloadCallback = null;

    <?php
    foreach ($oPageLayout->getJavascripts('bottom-top-scripts') AS $oJavascript) {
        if ($oJavascript->getLocation()) {
            // location set, then real file
            echo 'getScript(\'' . $oJavascript->getLocation() . '\',function(){});' . PHP_EOL;
        } else {
            // no location set, then plain text
            echo $oJavascript->getScript() . PHP_EOL;
        }
    }
    ?>

    (function () {
        function getScript(url, success) {
            var script = document.createElement('script');
            script.src = url;
            var head = document.getElementsByTagName('head')[0],
                done = false;
            script.onload = script.onreadystatechange = function () {
                if (!done && (!this.readyState || this.readyState === 'loaded' || this.readyState === 'complete')) {
                    done = true;
                    success();
                    script.onload = script.onreadystatechange = null;
                    head.removeChild(script);
                }
            };
            head.appendChild(script);
        }

        getScript('<?= $oPageLayout->getCombinedJavascript('main', 'bottom-cached') ?>', function () {
            <?php
            foreach ($oPageLayout->getJavascripts() AS $oJavascript) {
                if ($oJavascript->getLocation()) {
                    // location set, then real file
                    echo 'getScript(\'' . $oJavascript->getLocation() . '\',function(){});' . PHP_EOL;
                } else {
                    // no location set, then plain text
                    echo $oJavascript->getScript() . PHP_EOL;
                }
            }
            ?>

            <?php
            if(class_exists('Captcha') && !empty($oPageLayout->getJavascripts('captcha-bottom'))){?>
            onloadCallback = function () {
                <?php
                foreach ($oPageLayout->getJavascripts('captcha-bottom') AS $oJavascript) {
                    if ($oJavascript->getLocation()) {
                        // location set, then real file
                        echo 'getScript(\'' . $oJavascript->getLocation() . '\',function(){});' . PHP_EOL;
                    } else {
                        // no location set, then plain text
                        echo $oJavascript->getScript() . PHP_EOL;
                    }
                }
                ?>
            };

            getScript('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit', function () {

            });
            <?php
            }?>
        });
    })();
</script>
<!-- /Bottom javascripts -->
</body>
</html>
