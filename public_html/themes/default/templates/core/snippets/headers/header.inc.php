<?php

// Set extra header classes to do some magic
if (!isset($sHeaderClasses)) {
    $sHeaderClasses = '';
}
?>

<!-- Header pusher -->
<div class="header-pusher <?= $sHeaderClasses ?>"></div>
<!-- /Header pusher -->

<header id="header" class="full cf s-position-bottom sticky <?= $sHeaderClasses ?>">
    <div class="container cf">
        <div class="logo-container cf">
            <a href="<?= getUrlProtocol() . Locales::getCurrentURLFormat() ?>" class="header-logo" itemprop="url">
                <img src="<?= getSiteImage('logo.svg') ?>" alt="<?= _e(CLIENT_HTTP_URL) ?>" width="400" height="200">
            </a>
        </div>

        <?php if (moduleExists('orders')) {
            $oBasketPage = PageManager::getPageByName('shoppingcart');
            ?>
            <!-- Basket -->
            <div class="basket">
                <a href="<?= $oBasketPage->getBaseUrlPath() ?>" class="icon">
                    <i class="far fa-shopping-cart"></i>
                </a>
            </div>
            <!-- /Basket -->
        <?php } ?>

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

        <!-- Navigation -->
        <nav id="navigation" class="cf">
            <?php

            $aUrl = [];

            function makeListTree($aPages) {
                global $aUrl;
                if (count($aPages) > 0) {
                    echo '<ul class="pageLinks">';
                    foreach ($aPages AS $oPage) {
                        $iLevel = $oPage->level;

                        if ($iLevel == 1) {
                            $aUrl[$iLevel] = '/' . http_get('controller');
                        } else {
                            $aUrl[$iLevel] = $aUrl[$iLevel - 1] . '/' . http_get('param' . ($iLevel - 1));
                        }

                        // check if controller equals page urlPath be aware of special treathment for homepage!!
                        echo '<li class="' . ($aUrl[$oPage->level] == $oPage->getUrlPath() || '/' . http_get('controller') == $oPage->getUrlPath() . 'home' ? 'is-active' : '') . '"><a href="' . $oPage->getBaseUrlPath() . '">' . $oPage->getShortTitle() . '</a>';
                        makeListTree($oPage->getSubPages()); //call function recursive
                        echo '</li>';
                    }
                    echo '</ul>';
                }
            }

            makeListTree(PageManager::getPagesByFilter(['level' => 1, 'inMenu' => 1, 'languageId' => Locales::language()]));
            ?>
        </nav>
        <!-- /Navigation -->

        <!-- Navigation trigger icon -->
        <div class="hamburger--spring navigation-icon">
            <div class="hamburger-box">
                <div class="hamburger-inner"></div>
            </div>
        </div>
        <!-- /Navigation trigger icon -->
    </div>
</header>