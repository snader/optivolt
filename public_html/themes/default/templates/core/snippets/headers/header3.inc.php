<?php

// Set extra header classes to do some magic
if (!isset($sHeaderClasses)) {
    $sHeaderClasses = '';
}
?>

<header id="header" class="full sticky s-position-bottom <?= $sHeaderClasses ?>">
    <div class="container">
        <div class="header-container">

            <!-- Phone -->
            <a class="phone" href="tel:<?= $sCTAPhoneUrl ?>">
                <i class="far fa-phone"></i>
                <span><?= $sCTAPhone ?></span>
            </a>
            <!-- /Phone -->

            <div class="logo-container">
                <a href="<?= getUrlProtocol() . Locales::getCurrentURLFormat() ?>" class="header-logo" itemprop="url">
                    <img src="<?= getSiteImage('logo.svg') ?>" alt="<?= _e(CLIENT_HTTP_URL) ?>" width="400" height="200">
                </a>
            </div>

            <!-- Locale select -->
            <?php
            $aLocales = LocaleManager::getLocalesByFilter();
            if (count($aLocales) > 1) {
                ?>
                <div class="languages">
                    <select name="localeSelect" id="localeSelect">
                        <?php

                        foreach (LocaleManager::getLocalesByFilter() as $oLocale) {
                            echo '<option value="' . $oLocale->localeId . '" ' . ($oLocale->localeId == Locales::locale() ? 'selected' : '') . '>' . _e($oLocale->getLanguage()->code) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php
            }
            ?>
            <!-- /Locale select -->

            <?php if (moduleExists('orders')) { ?>
                <!-- Basket -->
                <div class="basket-icon">
                    <i class="far fa-shopping-cart"></i>
                    <span class="basket-amount amount"><?= (empty($_SESSION['oBasket']) ? 0 : $_SESSION['oBasket']->countProducts()) ?></span>
                </div>
                <!-- /Basket -->
            <?php } ?>

            <?php if (moduleExists('customers')) { ?>
                <!-- Customer -->
                <?php
                $oAccountPage = PageManager::getPageByName('account');
                if (!empty($oAccountPage)) { ?>
                    <a class="account-icon" href="<?= $oAccountPage->getBaseUrlPath() ?>">
                        <i class="far fa-user"></i>
                    </a>
                <?php } ?>
                <!-- /Customer -->
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

    <?php include getSiteSnippet('navigation'); ?>

</header>