<div class="navigation-wrapper">
    <nav id="navigation" class="prio-menu align-center">
        <?php
        $aNavPrioUrl = [];
        function makeNavPrio($aPages, $bIsParent)
        {
            global $aNavPrioUrl;

            if (count($aPages) > 0) {
                echo '<ul class="pageLinks">';
                foreach ($aPages AS $oPage) {
                    $iLevel = $oPage->level;

                    if ($iLevel == 1) {
                        $aNavPrioUrl[$iLevel] = '/' . http_get('controller');
                    } else {
                        $aNavPrioUrl[$iLevel] = $aNavPrioUrl[$iLevel - 1] . '/' . http_get('param' . ($iLevel - 1));
                    }

                    // check if controller equals page urlPath be aware of special treathment for homepage!!
                    echo '<li class="' . ($aNavPrioUrl[$oPage->level] == $oPage->getUrlPath() || '/' . http_get('controller') == $oPage->getUrlPath() . 'home' ? 'is-active' : '') . ($oPage->getSubPages()  ? ' has-sub' : '') . '"><a href="' . $oPage->getBaseUrlPath() . '">' . $oPage->getShortTitle() . '</a>';
                    makeNavPrio($oPage->getSubPages(), false); //call function recursive
                    echo '</li>';

                }
                if ($bIsParent) {
                    echo '<li class="more hide has-sub" data-width="80">';
                    echo '<a href="#">' . SiteTranslations::get('site_more') . '</a>';
                    echo '<ul></ul>';
                    echo '</li>';
                }
                echo '</ul>';
            }
        }

        makeNavPrio(PageManager::getPagesByFilter(['level' => 1, 'inMenu' => 1, 'languageId' => Locales::language()]), true);
        ?>
    </nav>
</div>