<div id="im_<?= $this->iContainerIDAddition ?>" class="im_container file_upload_container" data-prefix="im_" data-addition="<?= $this->iContainerIDAddition ?>">
    <p class="maxImagesError error <?= $this->iMaxImages > 0 && (count($this->aImages) >= $this->iMaxImages) ? '' : 'hide' ?>">
        <i><?= sysTranslations::get('global_file_limit') ?> <?= $this->iMaxImages ?> <?= sysTranslations::get('global_image_limit') ?></i>
    </p>

    <?php
    
        include_once getAdminSnippet('imageUploadFormHTML', 'imageManager');
    
    ?>

    <?php if (!$this->bListView) { ?>
        <hr>
        <?php

        $sTooltipTitle = '';
        $sTooltipTitle .= ($sTooltipTitle != '' ? '<br />' : '') . sysTranslations::get('global_drag_images');
        if ($this->iMaxImages > 0) {
            $sTooltipTitle .= ($sTooltipTitle != '' ? '<br />' : '') . sysTranslations::get('global_max_files') . $this->iMaxImages . sysTranslations::get('global_max_images');
        }
        ?>
    <?php } ?>
    <div class="imagesText<?= count($this->aImages) ? '' : ' hide' ?>">
        <h3 class="card-title"><?= sysTranslations::get('global_images_already_uploaded') ?> <?= $this->iMaxImages > 0 ? ' (<span class="imagesCountLeft">' . count($this->aImages) . '/' . $this->iMaxImages . '</span>) ' : '' ?></h3>
        <br /><?php

        if ($this->sExtraUploadedLine) {
            echo '<p>' . $this->sExtraUploadedLine . '</p>';
        }
        ?>
    </div>
    <div class="noImagesText<?= count($this->aImages) ? ' hide' : '' ?>">
        <i><?= sysTranslations::get('global_no_images_uploaded') ?></i>
    </div>
    <div class="images <?= $this->sortable ? 'sortable' : '' ?>" data-security="<?= CSRFSynchronizerToken::get() ?>">
        <?php

        foreach ($this->aImages as $oImage) {
            ?>
            <div id="placeholder-<?= $oImage->imageId ?>" imageId="<?= $oImage->imageId ?>" class="placeholder">
                <?php

                if (!$oImage->hasImageFiles($this->aNeededImageFileReferences)) {
                    echo '<img class="notAllCrops" src="' . SITE_ADMIN_CORE_FOLDER . '/images/icons/exclamation_icon.png" alt="' . sysTranslations::get('global_no_cutouts') . '" title="' . sysTranslations::get('global_no_cutouts') . '" />';
                }
                ?>
                <div class="imagePlaceholder">
                    <div class="centered">
                        <img src="<?= $oImage->getImageFileByReference('cms_thumb')->link . '?t=' . time() ?>" alt="<?= $oImage->getImageFileByReference('cms_thumb')->title ?>" <?= $oImage->getImageFileByReference(
                            'cms_thumb'
                        )->imageSizeAttr ?> title="<?= $oImage->getImageFileByReference('cms_thumb')->title ?>"/>
                    </div>
                </div>
                <div class="actionsPlaceholder">
                    <?php
                echo '<span style="float:left; font-size:13px;">' . $oImage->getImageFileByReference('cms_thumb')->title . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? ' (' . $oImage->getImageFileByReference('cms_thumb')->orgTitle . ')' : ' (' . $oImage->imageId . ')') . '</span>';
                    if (!$this->bListView && $this->onlineChangeable) {
                        if ($oImage->isOnlineChangeable()) {
                            echo '<a class="btn btn-success btn-xs action_icon ' . ($oImage->online ? 'online' : 'offline') . '_icon onlineOfflineBtn" onclick="setOnlineImage(this); return false;" online="' . ($oImage->getImageFileByReference(
                                    'original'
                                )->online ? 0 : 1) . '" href="' . $this->changeOnlineLink . '"><i class="fas fa-eye"></i></a>';
                        } else {
                            echo '<a onclick="return false;" class="btn btn-secondary btn-xs action_icon grey ' . ($oImage->online ? 'online' : 'offline') . '_icon onlineOfflineBtn"  href="#"><i class="fas fa-eye"></i></a>';
                        }
                    }
                    if (!$this->bListView && $this->cropable == true) {
                        if ($oImage->isCropable()) {
                            echo '<a class="btn btn-info btn-sm action_icon crop_icon" href="' . $this->cropLink . (strpos($this->cropLink, '?') ? '&' : '?') . 'imageId=' . $oImage->imageId . '"></a>';
                        } else {
                            echo '<a onclick="return false;" class="action_icon grey crop_icon" href="#"><i class="fas fa-pencil-alt"></i></a>';
                        }
                    }
                    if (!$this->bListView && $this->editable) {
                        if ($oImage->isEditable()) {
                            echo '<a class="action_icon edit_icon" onclick="showEditImage(this); return false;" href="' . $this->editLink . '"></a>';
                        } else {
                            echo '<a class="action_icon grey edit_icon" onclick="return false;" href="#"></a>';
                        }
                    }
                    if (!$this->bListView && $this->deletable) {
                        if ($oImage->isDeletable() && (UserManager::getCurrentUser()->isEngineer() || UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin())) {
                            echo '<a class="btn btn-danger btn-xs action_icon delete_icon" onclick="deleteImage(this); return false;" href="' . $this->deleteLink . '"><i class="fas fa-trash"></i></a>';
                        } else {
                            echo '<a class="btn btn-danger btn-xs disabled action_icon grey delete_icon" onclick="return false;" href="#"><i class="fas fa-trash"></i></a>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php

        }
        ?>
    </div>
</div>
<?php

if (!$this->bListView) {

# include editImageForm once
    include_once $this->sEditImageFormLocation;

# add sortable javascript initiation code
    $sImageManagerJavascript = <<<EOT
var updateSortableOnDrop = true;

$( "div#im_{$this->iContainerIDAddition} div.images.sortable").sortable({
    items: '> div',
    placeholder: 'ui-state-highlight placeholder',
    forcePlaceholderSize: true,
    tolerance: 'pointer',
    update: function(event, ui) {
        if(updateSortableOnDrop){
        var imageIds = new Array();
        ui.item.closest('div.images').find('> div').each(function(index, value){
            imageIds[index] = $(value).attr('imageId');
        });
        updateImageOrder(imageIds, '{$this->saveOrderLink}', 'im_{$this->iContainerIDAddition}', ui.item.closest('div.images').data('security'));
        }else{
            $( "div#im_{$this->iContainerIDAddition} div.images.sortable").sortable("cancel"); // cancel sortable update
            updateSortableOnDrop = true;
    }
    }
});

// count current amount of images and check if upload should be disabled
globalFunctions.checkMaxImages{$this->iContainerIDAddition} = function (){
    var imageManagerID = '#im_{$this->iContainerIDAddition}';
    var imageCount = $(imageManagerID).find('.images .placeholder').length();
    var maxImages = '{$this->iMaxImages}';

   if(maxImages>0){
        if((maxImages-imageCount) <= 0){
            $('#saveImageBtn_{$this->iContainerIDAddition}').prop('disabled', true);
            $(imageManagerID).find('.imagesCountLeft').html(imageCount+'/'+maxImages);
            $(imageManagerID).find('.maxImagesError').removeClass('hide');
            $(imageManagerID).find('.uploadForm').addClass('hide');
        }else{
            $('#saveImageBtn_{$this->iContainerIDAddition}').prop('disabled', false);
            $(imageManagerID).find('.imagesCountLeft').html(imageCount+'/'+maxImages);
            $(imageManagerID).find('.maxImagesError').addClass('hide');
            $(imageManagerID).find('.uploadForm').removeClass('hide');
        }
    }
    if(imageCount > 0){
        $(imageManagerID).find('.extraUploadedLine').removeClass('hide');
    }else{
        $(imageManagerID).find('.extraUploadedLine').addClass('hide');
    }
}

EOT;
    if ($this->bAjax == 1) {
        echo '<script>' . $sImageManagerJavascript . '</script>';
    } else {
        $oPageLayout->addJavascript('<script>' . $sImageManagerJavascript . '</script>');
    }

    if ($this->bCoverImageShow) {
        $sCSRFField = CSRFSynchronizerToken::FIELD;
        $sCRSFToken = CSRFSynchronizerToken::get();
        $sCoverImageJavascript = <<<EOT
$("div#im_{$this->iContainerIDAddition} .coverImageContainer .placeholder").droppable({
    drop: function(e, ui) {
        var jElementDroppable = $(this);
        var jElementDraggable = $(ui.draggable);
        $.ajax({
            type: 'POST',
            url: '{$this->sCoverImageUpdateLink}',
            data: 'imageId=' + jElementDraggable.attr('imageid') + '&$sCSRFField=$sCRSFToken',
            success: function(data){
                var dataObj = eval("("+data+")");
                if(dataObj.success){
                    showStatusUpdate('{$this->sCoverImageSuccessText}');
                    jElementDroppable.find('img').prop('src', dataObj.imageFile.link).data('imageid', dataObj.imageFile.imageId);
                    jElementDroppable.effect('highlight', 1500);
                }else{
                    showStatusUpdate('{$this->sCoverImageErrorText}');
                }
            }
        });
        updateSortableOnDrop = false;
    },
    hoverClass: 'ui-state-highlight',
    accept: 'div#im_{$this->iContainerIDAddition} .images .placeholder',
    tolerance: 'pointer'
});

/**
* @param imageId id of image that modified
*/
globalFunctions.updateCoverImageAfterDelete{$this->iContainerIDAddition} = function (imageId){
    var jElementDroppable = $('div#im_{$this->iContainerIDAddition} .coverImageContainer .placeholder');

    if($('div#im_{$this->iContainerIDAddition} .images .placeholder').size() == 0){
        $('div#im_{$this->iContainerIDAddition} .coverImageContainer').addClass('hide');
        return;
    }

    if(jElementDroppable.find('img').data('imageid') == imageId){
        $.ajax({
            url: '{$this->sCoverImageGetLink}',
            async: false,
            success: function(data){
                var dataObj = eval("("+data+")");
                if(dataObj.success){
                    jElementDroppable.find('img').prop('src', dataObj.imageFile.link).data('imageid', dataObj.imageFile.imageId);
                    jElementDroppable.effect('highlight', 1500);
                }
            }
        });
    }
}
EOT;
        if ($this->bAjax == 1) {
            echo '<script>' . $sCoverImageJavascript . '</script>';
        } else {
            $oPageLayout->addJavascript('<script>' . $sCoverImageJavascript . '</script>');
        }
    }

    $iMaxImages         = $this->iMaxImages > 0 ? $this->iMaxImages : 0;
    $sAllowedExtensions = implode(',', $this->aMultipleFileUploadAllowedExtensions);
    $iMaxFileSize       = $this->sMultipleFileUploadFileSizeLimit ? $this->sMultipleFileUploadFileSizeLimit : 0;
    $sMFUpload          = <<<EOT
$('#imageFile_{$this->iContainerIDAddition}').mfupload({
    upload_url: '$this->sUploadUrl',
    upload_post_name: 'image',
    show_thumb: true,
    max_files: {$iMaxImages},
    max_file_size: {$iMaxFileSize},
    allowed_extensions: '{$sAllowedExtensions}',
    place_holder_selector_for_count: '.images .placeholder',
    callback_check_max_uploads: function(){
        globalFunctions.checkMaxImages{$this->iContainerIDAddition}();

    },
    callback_file_upload_success: function(dataObj){
        addImage(dataObj.imageFile, {
            onlineChangeable: '{$this->onlineChangeable}',
            changeOnlineLink: '{$this->changeOnlineLink}',
            cropable: '{$this->cropable}',
            cropLink: '{$this->cropLink}',
            editable: '{$this->editable}',
            editLink: '{$this->editLink}',
            deletable: '{$this->deletable}',
            deleteLink: '{$this->deleteLink}',
            coverImageShow: '{$this->bCoverImageShow}',
            containerIDAddition: '{$this->iContainerIDAddition}',
        });
    },
    callback_additional_post_values: function(){

        //$('#imageTitle_{$this->iContainerIDAddition}').hide();
        return {
            action: $('#imageHiddenAction_{$this->iContainerIDAddition}').val(),
            MFUpload: true,
            title: $('#imageTitle_{$this->iContainerIDAddition}').val(),
            'extra-option': null,
            cropAfterUpload: null,
            SecurityID: $('#imageHiddenAction_{$this->iContainerIDAddition}').closest('form').find('[name="SecurityID"]').val()
        }

    }
});

// enable dropzone when javascript enabled
$('#im_{$this->iContainerIDAddition} .uploadsDropZone').removeClass('hide');

// disble upload button
$('#saveImageBtn_{$this->iContainerIDAddition}').addClass('hide');
EOT;
    if ($this->bMultipleFileUpload) {
        if ($this->bAjax == 1) {
            echo '<script>' . $sMFUpload . '</script>';
        } else {
            $oPageLayout->addJavascript('<script>' . $sMFUpload . '</script>');
        }
    }
}
?>
