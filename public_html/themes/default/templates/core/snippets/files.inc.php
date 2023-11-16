<?php if (!empty($aFiles)) { ?>
    <!-- Files -->
    <div class="box files">
        <div class="column">
            <h2 class="title is-size-4"><?= _e(SiteTranslations::get('site_files')) ?></h2>
            <ul>
                <?php foreach ($aFiles AS $oFile) { ?>
                    <li>
                        <a href="<?= $oFile->link ?>" target="_blank">
                            <i class="fas fa-file"></i> <?= $oFile->title ? _e($oFile->title) : _e($oFile->name) ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- /Files -->
<?php } ?>