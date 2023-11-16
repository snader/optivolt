<form action="<?= $this->sUploadUrl ?>" id="form_<?= $this->iContainerIDAddition ?>" class="validateForm" method="POST" enctype="multipart/form-data">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" id="imageHiddenAction_<?= $this->iContainerIDAddition ?>" name="action" value="<?= $this->sHiddenAction ?>" />
    <div class="clearfix">
        <div style="float: left; width: 65%">
            <?php if (!$this->bListView) { ?>
                <div class="uploadForm <?= $this->iMaxImages > 0 && (count($this->aImages) >= $this->iMaxImages) ? 'hide' : '' ?> withForm" style="width: 100%;">
                    <div>
                        <?php

                        if (!empty($this->sExtraUploadLine)) {
                            echo '<p>' . $this->sExtraUploadLine . '</p>';
                        }
                        ?>
                    </div>
                    <div>
                        <label for="imageTitle_<?= $this->iContainerIDAddition ?>"><?= sysTranslations::get('global_image_description') ?></label>
                    </div>
                    <div>
                        <input id="imageTitle_<?= $this->iContainerIDAddition ?>" maxlength="255" class="form-control" name="title" type="text" value="" />
                    </div>
                    <div>
                        <input id="imageFile_<?= $this->iContainerIDAddition ?>" type="file" name="image" style="margin: 5px 0" />
                        <div class="uploadsDropZone hide" onclick="$('#imageFile_<?= $this->iContainerIDAddition ?>').click();">
                            <div class="dropZoneBrowseHolder"><a class="" onclick="return false;" href="#"><i class="fa fa-upload" aria-hidden="true"></i> <?= sysTranslations::get('global_browse') ?>...</a></div>
                            <div class="dropZoneTextHolder"><?= sysTranslations::get('global_drop_your_files_here') ?></div>
                        </div>
                    </div>
                    <div>
                        <div class="progressInfo"></div>
                    </div>

                    <?php

                    # optional extra options for after upload
                    if (is_array($this->getExtraOptions())) {
                        echo '<div><select id="imageExtraOptions_' . $this->iContainerIDAddition . '" name="extra-option">';
                        foreach ($this->getExtraOptions() as $aExtraOption) {
                            list($sOptionLabel, $sOptionValue) = $aExtraOption;
                            echo '<option value="' . $sOptionValue . '">' . $sOptionLabel . '</option>';
                        }
                        echo '</select></div>';
                    }

                    if ($this->bShowCropAfterUploadOption) {
                    ?>
                        <div>
                            <input id="imageCropAfterUpload_<?= $this->iContainerIDAddition ?>" class="alignCheckbox" type="checkbox" <?= $this->bCropAfterUploadChecked ? 'CHECKED' : '' ?> name="cropAfterUpload" value="1" /> <label for="imageCropAfterUpload_<?= $this->iContainerIDAddition ?>"><?= sysTranslations::get('global_image_direct_cut') ?></label>
                        </div>
                    <?php

                    }
                    ?>
                    <div>
                        <input <?= $this->iMaxImages > 0 && (count($this->aImages) >= $this->iMaxImages) ? 'DISABLED' : '' ?> id="saveImageBtn_<?= $this->iContainerIDAddition ?>" type="submit" class="btn btn-primary" name="saveImage" value="<?= sysTranslations::get('global_upload_image') ?>" />
                    </div>
                    <?php

                    ?>
                </div>
            <?php } ?>
        </div>
        <?php if ($this->bCoverImageShow) { ?>
            <div class="coverImageContainer<?= $this->oCoverImageImageFile ? '' : ' hide' ?>" style="float: right; max-width: 135px;">
                <div class="placeholder">
                    <div class="placeholderTitle"><b><?= $this->sCoverImageTitle ?></b>
                        <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_replace_image') ?>">&nbsp;</div>
                    </div>
                    <div class="imagePlaceholder">
                        <?php if ($this->oCoverImageImageFile) { ?>
                            <img data-imageid="<?= $this->oCoverImageImageFile->imageId ?>" src="<?= $this->oCoverImageImageFile->link ?>" />
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</form>