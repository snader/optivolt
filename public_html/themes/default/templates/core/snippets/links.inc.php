<?php if (!empty($aLinks)) { ?>
    <!-- Links -->
    <div class="box links">
        <div class="column">
            <h2 class="title is-size-4"><?= _e(SiteTranslations::get('site_links')) ?></h2>
            <ul>
                <?php foreach ($aLinks AS $oLink) { ?>
                    <li>
                        <a href="<?= $oLink->link ?>" target="<?= getLinkTarget($oLink->link) ?>">
                            <i class="fas fa-link"></i> <?= $oLink->title ? _e($oLink->title) : _e($oLink->link) ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- /Links -->
<?php } ?>