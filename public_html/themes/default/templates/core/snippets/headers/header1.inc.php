<?php

// Set extra header classes to do some magic
if (!isset($sHeaderClasses)) {
    $sHeaderClasses = '';
}
?>

<header id="header" class="full sticky s-position-bottom <?= $sHeaderClasses ?>">
    <div class="container">
        <div class="header-container">
            <div class="logo-container">
                <a href="<?= getUrlProtocol() . Locales::getCurrentURLFormat() ?>" class="header-logo" itemprop="url">
                    <img src="<?= getSiteImage('logo.svg') ?>" alt="<?= _e(CLIENT_HTTP_URL) ?>" width="400" height="200">
                </a>
            </div>

            <?php include getSiteSnippet('navigation'); ?>

            <?php if (moduleExists('search')) { ?>
                <!-- Search -->
                <?php
                $oSearchPage = PageManager::getPageByName('search');
                if (!empty($oSearchPage)) { ?>
                    <a class="search-icon" href="<?= $oSearchPage->getBaseUrlPath() ?>">
                        <i class="far fa-search"></i>
                    </a>
                <?php } ?>
                <!-- /Search -->
            <?php } ?>

            <?php if (moduleExists('orders')) { ?>
                <!-- Basket -->
                <div class="basket-icon">
                    <i class="far fa-shopping-cart"></i>
                    <span class="basket-amount amount"><?= (empty($_SESSION['oBasket']) ? 0 : $_SESSION['oBasket']->countProducts()) ?></span>
                </div>
                <!-- /Basket -->
            <?php } ?>
            <?php if (moduleExists('customers')) { ?>
                <!-- Search -->
                <?php
                $oAccountPage = PageManager::getPageByName('account');
                if (!empty($oAccountPage)) { ?>
                    <a class="account-icon" href="<?= $oAccountPage->getBaseUrlPath() ?>">
                        <i class="far fa-user"></i>
                    </a>
                <?php } ?>
                <!-- /Search -->
            <?php } ?>

            <!-- Navigation trigger icon -->
            <div class="hamburger--spring navigation-icon">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
            <!-- /Navigation trigger icon -->
        </div>
    </div>
</header>