<?php if (!empty($aVideos)) { ?>
    <!--  Videos -->
    <div class="videos">
        <div class="columns">
            <?php foreach ($aVideos AS $oVideo) { ?>
                <div class="column is-one-fifth-desktop is-one-third-tablet">
                    <a href="<?= $oVideo->getEmbedLink() ?>" class="video js-fancybox fancybox.iframe" title="<?= _e($oVideo->title) ?>">
                        <img src="<?= $oVideo->getThumbLink('hqdefault') ?>" alt="<?= _e($oVideo->title) ?>" width="480" height="360"/>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- /Videos -->
<?php } ?>