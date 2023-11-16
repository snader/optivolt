<?php

/* @var \Page $oPageForMenu */
if (!empty($oPageForMenu) && count($oPageForMenu->getSubPages()) > 0) { ?>
    <div class="column is-one-quarter">
        <nav class="menu">
            <p class="menu-label"><?= SiteTranslations::get('site_navigate') ?></p>
            <?php

            $aUrl    = [];
            $aUrl[1] = '/' . http_get('controller'); // set level 1 becaus of submenu only

            function makeSubListTree($aPages)
            {
                global $aUrl;
                /* @var \Page[] $aPages */
                if (count($aPages) > 0) {
                    echo '<ul class="menu-list">';
                    foreach ($aPages AS $oPage) {
                        $iLevel        = $oPage->level;
                        $aUrl[$iLevel] = $aUrl[$iLevel - 1] . '/' . http_get('param' . ($iLevel - 1));
                        $bIsActive     = $aUrl[$iLevel] == $oPage->getUrlPath() . (!$oPage->getIncludeParentInUrlPath() ? '/' : '');
                        echo '<li><a class="' . ($bIsActive ? 'is-active' : '') . (count($oPage->getSubPages()) > 0 ? ' has-sub' : '') . '" href="' . $oPage->getBaseUrlPath() . '">' . $oPage->getShortTitle() . '</a>';
                        if ($bIsActive) {
                            makeSubListTree($oPage->getSubPages()); //call function recursive
                        }
                        echo '</li>';
                    }
                    echo '</ul>';
                }
            }

            makeSubListTree($oPageForMenu->getSubPages());
            ?>
        </nav>
    </div>
<?php } ?>