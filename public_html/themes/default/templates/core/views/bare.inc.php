<!DOCTYPE html>
<html class="no-js" lang="nl">
<head>

    <meta charset="utf-8">

    <!-- Changeable page title -->
    <title><?= _e($oPageLayout->sWindowTitle) ?> | <?= _e(CLIENT_NAME) ?></title>

    <?php

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

    ?>

    <!-- Meta tags -->
    <meta name="author" content="<?= _e(CLIENT_NAME) ?>"/>
    <meta name="theme-color" content="#828282">

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

</head>
<body itemscope itemtype="http://schema.org/WebPage">
<?php

// include the actual page with changable content
include_once $oPageLayout->sViewPath;
?>
</body>
</html>
