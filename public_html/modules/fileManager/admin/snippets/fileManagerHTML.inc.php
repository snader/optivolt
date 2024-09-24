<div class="fm_container file_upload_container" id="fm_<?= $this->iContainerIDAddition ?>" data-prefix="fm_" data-addition="<?= $this->iContainerIDAddition ?>">
    <?php if (!$this->bListView) { ?>
        <p class="maxFilesError error <?= $this->iMaxFiles > 0 && (count($this->aFiles) >= $this->iMaxFiles) ? '' : 'hide' ?>">
            <i><?= sysTranslations::get('global_file_limit') ?> <?= $this->iMaxFiles ?> <?= sysTranslations::get('global_file_limit_2') ?></i>
        </p>
        <form action="<?= $this->sUploadUrl ?>" id="fm_form_<?= $this->iContainerIDAddition ?>" class="validateForm <?= $this->iMaxFiles > 0 && (count($this->aFiles) >= $this->iMaxFiles) ? 'hide' : '' ?> uploadForm" method="POST"
              enctype="multipart/form-data">
            <?= CSRFSynchronizerToken::field() ?>
            <input id="fileHiddenAction_<?= $this->iContainerIDAddition ?>" type="hidden" name="action" value="<?= $this->sHiddenAction ?>"/>
            <div>
                <label for="fileTitle_<?= $this->iContainerIDAddition ?>"><?= sysTranslations::get('global_file_description') ?> <?= $this->bTitleRequired ? '*' : '' ?></label>
            </div>
            <div>
                <input id="fileTitle_<?= $this->iContainerIDAddition ?>" maxlength="255" name="title" class="default <?= $this->bTitleRequired ? 'required' : '' ?>" <?= $this->sTitleTitle ? 'title="' . $this->sTitleTitle . '"' : '' ?>
                       type="text" value=""/>
            </div>
            <div>
                <input required id="fileFile_<?= $this->iContainerIDAddition ?>" style="margin: 5px 0;" type="file"
                       title="<?= $this->sValidateFile ? sysTranslations::get('global_select_extensions') . ': ' . $this->sValidateFile : sysTranslations::get('global_select_file') ?>" name="file"/>
                <div class="uploadsDropZone hide" onclick="$('#fileFile_<?= $this->iContainerIDAddition ?>').click();">
                    <div class="dropZoneBrowseHolder"><a class="" onclick="return false;" href="#"><i class="fa fa-upload" aria-hidden="true"></i> <?= sysTranslations::get('global_browse') ?>...</a></div>
                    <div class="dropZoneTextHolder"><?= sysTranslations::get('global_drop_your_files_here') ?></div>
                </div>
            </div>
            <div>
                <div class="progressInfo"></div>
            </div>
            <div>
                <input <?= $this->iMaxFiles > 0 && (count($this->aFiles) >= $this->iMaxFiles) ? 'DISABLED' : '' ?> id="saveFileBtn_<?= $this->iContainerIDAddition ?>" type="submit" name="saveFile"
                                                                                                                   value="<?= sysTranslations::get('global_upload_file') ?>"/>
            </div>
        </form>
        <hr>
        <?php

        $sTooltipTitle = '';
        $sTooltipTitle .= ($sTooltipTitle != '' ? '<br />' : '') . sysTranslations::get('global_drag_lines');
        if ($this->iMaxFiles > 0) {
            $sTooltipTitle .= ($sTooltipTitle != '' ? '<br />' : '') . sysTranslations::get('global_max_files') . $this->iMaxFiles . sysTranslations::get('global_max_files_2');
        }
        ?>
    <?php } ?>
    <h3><?= sysTranslations::get('global_already_uploaded') ?><?= $this->iMaxFiles > 0 ? ' (<span class="filesCountLeft">' . count($this->aFiles) . '/' . $this->iMaxFiles . '</span>) ' : '' ?>
        <div class="hasTooltip tooltip" title="<?= $sTooltipTitle ?>">&nbsp;</div>
    </h3>
    <ul class="files <?= $this->sortable ? 'sortable' : '' ?>" data-security="<?= CSRFSynchronizerToken::get() ?>" data-security="<?= CSRFSynchronizerToken::get() ?>">
        <?php

        foreach ($this->aFiles as $oFile) {
            ?>
            <li id="placeholder-<?= $oFile->mediaId ?>" data-mediaid="<?= $oFile->mediaId ?>" data-title="<?= _e($oFile->title) ?>" class="placeholder">
                <div class="filePlaceholder" title="<?= $oFile->name ?>">
                    <span class="fileType <?= $oFile->getExtension() ?>"></span><a class="title" target="_blank" href="<?= $oFile->link ?>"><?= $oFile->title ? $oFile->title : $oFile->name ?></a>
                </div>
                <div class="actionsPlaceholder">
                    <?php

                    if (!$this->bListView && $this->onlineChangeable) {
                        if ($oFile->isOnlineChangeable()) {
                            echo '<a class="action_icon ' . ($oFile->online ? 'online' : 'offline') . '_icon onlineOfflineBtn" onclick="setOnlineFile(this); return false;" data-online="' . ($oFile->online ? 0 : 1) . '" href="' . $this->changeOnlineLink . '"></a>';
                        } else {
                            echo '<a onclick="return false;" class="action_icon grey ' . ($oFile->online ? 'online' : 'offline') . '_icon onlineOfflineBtn"  href="#"></a>';
                        }
                    }
                    if (!$this->bListView && $this->editable) {
                        if ($oFile->isEditable()) {
                            echo '<a class="action_icon edit_icon" onclick="showEditFile(this); return false;" href="' . $this->editLink . '"></a>';
                        } else {
                            echo '<a class="action_icon grey edit_icon" onclick="return false;" href="#"></a>';
                        }
                    }
                    if (!$this->bListView && $this->deletable) {
                        if ($oFile->isDeletable()) {
                            echo '<a class="action_icon delete_icon" onclick="deleteFile(this); return false;" href="' . $this->deleteLink . '"></a>';
                        } else {
                            echo '<a class="action_icon grey delete_icon" onclick="return false;" href="#"></a>';
                        }
                    }
                    ?>
                </div>
            </li>
            <?php

        }
        ?>
    </ul>
</div>
<?php

if (!$this->bListView) {
# include editImageForm once
    include_once $this->sEditFileFormLocation;

# add sortable javascript initiation code
    $sFileManagerJavascript = <<<EOT
$( "div#fm_{$this->iContainerIDAddition} ul.files.sortable").sortable({
    items: '> li',
    placeholder: 'ui-state-highlight placeholder',
    forcePlaceholderSize: true,
    tolerance: 'pointer',
    update: function(event, ui) {
        var mediaIds = new Array();
        ui.item.closest('ul.files').find('> li').each(function(index, value){
            mediaIds[index] = $(value).data('mediaid');
        });
        updateFileOrder(mediaIds, '{$this->saveOrderLink}', 'fm_{$this->iContainerIDAddition}', ui.item.closest('ul').data('security'));
    }
});

// count current amount of files and check if upload should be disabled
globalFunctions.checkMaxFiles{$this->iContainerIDAddition} = function (){
    var fileManagerID = '#fm_{$this->iContainerIDAddition}';
    var fileCount = $(fileManagerID).find('.placeholder').size();
    var maxFiles = '{$this->iMaxFiles}';
    if(maxFiles>0){
        if((maxFiles-fileCount) <= 0){
            $('#saveFileBtn_{$this->iContainerIDAddition}').prop('disabled', true);
            $(fileManagerID).find('.filesCountLeft').html(fileCount+'/'+maxFiles);
            $(fileManagerID).find('.maxFilesError').removeClass('hide');
            $(fileManagerID).find('.uploadForm').addClass('hide');
        }else{
            $('#saveFileBtn_{$this->iContainerIDAddition}').prop('disabled', false);
            $(fileManagerID).find('.filesCountLeft').html(fileCount+'/'+maxFiles);
            $(fileManagerID).find('.maxFilesError').addClass('hide');
            $(fileManagerID).find('.uploadForm').removeClass('hide');
        }
    }
}

EOT;
    if ($this->bAjax == 1) {
        echo '<script>' . $sFileManagerJavascript . '</script>';
    } else {
        $oPageLayout->addJavascript('<script>' . $sFileManagerJavascript . '</script>');
    }
    $iMaxFiles          = $this->iMaxFiles > 0 ? $this->iMaxFiles : 0;
    $sAllowedExtensions = implode(',', $this->aMultipleFileUploadAllowedExtensions);
    $iMaxFileSize       = $this->sMultipleFileUploadFileSizeLimit ? $this->sMultipleFileUploadFileSizeLimit : 0;
    $sMFUpload          = <<<EOT
$('#fileFile_{$this->iContainerIDAddition}').mfupload({
    upload_url: '$this->sUploadUrl',
    max_files: {$iMaxFiles},
    max_file_size: {$iMaxFileSize},
    allowed_extensions: '{$sAllowedExtensions}',
    place_holder_selector_for_count: '.placeholder',
    callback_check_max_uploads: function(){
        globalFunctions.checkMaxFiles{$this->iContainerIDAddition}();
    },
    callback_file_upload_success: function(dataObj){
        addFile(dataObj.file, {
            onlineChangeable: '{$this->onlineChangeable}',
            changeOnlineLink: '{$this->changeOnlineLink}',
            editable: '{$this->editable}',
            editLink: '{$this->editLink}',
            deletable: '{$this->deletable}',
            deleteLink: '{$this->deleteLink}',
            containerIDAddition: '{$this->iContainerIDAddition}'
        });
    },
    callback_additional_post_values: function(){
        return {
            action: $('#fileHiddenAction_{$this->iContainerIDAddition}').val(),
            MFUpload: true,
            title: $('#fileTitle_{$this->iContainerIDAddition}').size() > 0 ? $('#fileTitle_{$this->iContainerIDAddition}').val() : null,
            SecurityID: $('#fileHiddenAction_{$this->iContainerIDAddition}').closest('form').find('[name="SecurityID"]').val()
        }
    }
});

// enable dropzone when javascript enabled
$('#fm_{$this->iContainerIDAddition} .uploadsDropZone').removeClass('hide');

// disble upload button
$('#saveFileBtn_{$this->iContainerIDAddition}').addClass('hide');
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
