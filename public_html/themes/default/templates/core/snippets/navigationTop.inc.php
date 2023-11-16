<div class="navigation-top is-hidden-mobile">
    <div class="container">
        <div class="phone">
            <a href="tel:<?= $sCTAPhoneUrl ?>"><i class="far fa-phone"></i> <?= $sCTAPhone ?></a>
        </div>

        <?php
        function makeTopMenu($aTopPages) {

            if (count($aTopPages) > 0) {
                echo '<ul>';
                foreach ($aTopPages as $oTopPage) {
                    echo '<li><a href="' . $oTopPage->getBaseUrlPath() . '">' . $oTopPage->getShortTitle() . '</a></li>';
                }
                echo '</ul>';
            }

        }
        makeTopMenu(PageManager::getPagesByFilter(['level' => 1, 'inMenu' => 1, 'languageId' => Locales::language()], 4));
        ?>
    </div>
</div>