<?php if (!empty($aImages) || !empty($aVideos)) { ?>
    <div class="columns">
        <div class="column is-half is-offset-one-quarter">
            <div class="media-slider-container">
                <div class="media-slider-loader">
                    <?php if (!empty($aVideos)) {
                        $oVideo = $aVideos[0];
                        ?>
                        <div class="media-slide">
                            <div class="media-image" style="background-image: url('<?= $oVideo->getThumbLink('maxresdefault', '') ?>')"></div>
                        </div>
                    <?php } elseif (!empty($aImages)) {
                        // Get crops for loader
                        $oImage     = $aImages[0];
                        $oCropSmall = $oImage->getImageFileByReference('crop_small');

                        if (!empty($oCropSmall)) { ?>
                            <!-- Image -->
                            <div class="media-slide">
                                <div class="media-image" style="background-image: url('<?= $oCropSmall->link ?>')"></div>
                            </div>
                            <!-- /Image -->
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="media-slider">
                    <?php if (!empty($aVideos)) {
                        foreach ($aVideos AS $oVideo) {
                            if ($oVideo->online) {
                                ?>
                                <div class="media-slide">
                                    <a href="<?= $oVideo->getEmbedLink() ?>" class="fancyBoxLink" data-fancybox="media">
                                        <div class="media-image" style="background-image: url('<?= $oVideo->getThumbLink('maxresdefault', '') ?>')"></div>
                                        <div class="media-play"></div>
                                    </a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

                    <?php if (!empty($aImages)) {
                        foreach ($aImages as $oImage) {
                            if ($oImage->online) {
                                $oDetail    = $oImage->getImageFileByReference('detail');
                                $oCropSmall = $oImage->getImageFileByReference('crop_small');
                                if (!empty($oDetail) && !empty($oCropSmall)) {
                                    ?>
                                    <div class="media-slide" data-type="image">
                                        <a href="<?= $oDetail->link ?>" class="media-image fancyBoxLink cf" data-fancybox="media">
                                            <!-- Image -->
                                            <div class="media-image" style="background-image: url('<?= $oCropSmall->link ?>')"></div>
                                            <!-- /Image -->
                                        </a>
                                    </div>
                                    <?php

                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>