<aside class="navigation-off-canvas slide-right">
    <?php
    // Get search page
    $oSearchPage = PageManager::getPageByName('search');

    if (!empty($oSearchPage)) { ?>
        <div class="search cf">
            <form method="get" action="<?= $oSearchPage->getBaseUrlPath() ?>">
                <div class="field has-addons">
                    <div class="control is-expanded">
                        <input class="input" name="q" placeholder="<?= SiteTranslations::get('site_search') ?>..." value="<?= isset($_SESSION['search_word']) ? $_SESSION['search_word'] : '' ?>">
                    </div>
                    <div class="control">
                        <button class="button is-primary"><i class="far fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>

    <?php
    $aUrl = [];
    function makeOffCanvasListTree($aPages, $sClass = null) {
        global $aUrl;
        if (count($aPages) > 0) {
            $oFirstInstance = $aPages[0];
            echo '<ul class="' . ($oFirstInstance->level == 1 ? 'off-canvas-list' : 'sub-navigation-off-canvas') . (!empty($sClass) ? ' ' . $sClass : '') . '">';
            $iInstanceCounter = 0;
            foreach ($aPages AS $oPage) {
                $iInstanceCounter++;
                $iLevel = $oPage->level;
                if ($iLevel == 1) {
                    $aUrl[$iLevel] = '/' . http_get('controller');
                } else {
                    $aUrl[$iLevel] = $aUrl[$iLevel - 1] . '/' . http_get('param' . ($iLevel - 1));
                    if ($iInstanceCounter == 1) {
                        echo '<li class="back"><a href="#" rel="nofollow"><i class="fas fa-chevron-left"></i>' . SiteTranslations::get('site_back') . '</a></li>';
                        $oMainPage = $oPage->getParent();
                        if (!empty($oMainPage) && $oMainPage->online) {
                            echo '<li class="' . ($aUrl[$oMainPage->level] == $oMainPage->getUrlPath() || '/' . http_get('controller') == $oMainPage->getUrlPath() . 'home' ? 'is-active' : '') . '"><a href="' . $oMainPage->getBaseUrlPath() . '">' . $oMainPage->getShortTitle() . '</a></li>';
                        }
                    }
                }
                // check if controller equals page urlPath be aware of special treathment for homepage!!
                echo '<li class="' . ($aUrl[$oPage->level] == $oPage->getUrlPath() || '/' . http_get('controller') == $oPage->getUrlPath() . 'home' ? 'is-active' : '') . ' ' . (!empty(
                    $oPage->getSubPages()
                    ) ? 'has-sub-navigation' : '') . '"><a href="' . $oPage->getBaseUrlPath() . '">' . $oPage->getShortTitle() . (!empty($oPage->getSubPages()) ? '<i class="fas is-arrow-right fa-chevron-right"></i>' : '') . '</a>';
                makeOffCanvasListTree($oPage->getSubPages()); //call function recursive
                echo '</li>';
            }
            echo '</ul>';
        }
    }

    makeOffCanvasListTree(PageManager::getPagesByFilter(['level' => 1, 'inMenu' => 1, 'languageId' => Locales::language()]));
    ?>

    <!-- Locales -->
    <?php
    $aLocales = LocaleManager::getLocalesByFilter();
    if (count($aLocales) > 1) { ?>
        <div class="languages">
            <?php foreach ($aLocales as $oLocale) { ?>
                <a class="language <?= (Locales::locale() == $oLocale->localeId ? 'is-active' : '') ?> cf" href="/change-locale?localeId=<?= $oLocale->localeId ?>">
                    <?= _e($oLocale->getLanguage()->code) ?>
                </a>
            <?php } ?>
        </div>
    <?php } ?>
    <!-- /Locales -->
</aside>