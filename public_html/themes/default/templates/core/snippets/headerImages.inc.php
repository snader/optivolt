<?php if (!empty($aImages)) { ?>
    <div class="header-images-container">
        <?php
        // Get crop small for loader
        $oHeaderImage     = $aImages[0];
        $oHeaderImageSmall = $oHeaderImage->getImageFileByReference('crop_small');
        $oHeaderImageLarge = $oHeaderImage->getImageFileByReference('crop_large');
        if (!empty($oHeaderImageSmall) && !empty($oHeaderImageLarge)) {
            ?>
            <div class="header-images-loader">
                <!-- Image -->
                <div class="header-image-slide">
                    <div class="header-image large" style="background-image: url('<?= $oHeaderImageLarge->link ?>')"></div>
                    <div class="header-image small" style="background-image: url('<?= $oHeaderImageSmall->link ?>')"></div>
                </div>
                <!-- /Image -->
            </div>
        <?php } ?>
        <div class="header-images">
            <?php foreach ($aImages as $oHeaderImage) {
                if ($oHeaderImage->online && ($oHeaderImageSmall = $oHeaderImage->getImageFileByReference('crop_small')) && ($oHeaderImageLarge = $oHeaderImage->getImageFileByReference('crop_large'))) {
                    ?>
                    <div class="header-image-slide">
                        <div class="header-image large" style="background-image: url('<?= $oHeaderImageLarge->link ?>')"></div>
                        <div class="header-image small" style="background-image: url('<?= $oHeaderImageSmall->link ?>')"></div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
<?php } ?>
