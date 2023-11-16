<?php /* @var \VideoLinkManagerHTML $this */ ?>

    <div id="ym_<?= $this->iContainerIDAddition ?>" class="ym_container" data-addition="<?= $this->iContainerIDAddition ?>">
        <p class="maxVideoLinksError error <?= $this->iMaxVideoLinks > 0 && (count($this->aVideoLinks) >= $this->iMaxVideoLinks) ? '' : 'hide' ?>">
            <i><?= sysTranslations::get('global_file_limit') ?> <?= $this->iMaxVideoLinks ?> <?= sysTranslations::get('global_videolinks_limit') ?></i>
        </p>
        <form method="POST" action="<?= $this->sUploadUrl ?>" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" name="action" value="<?= $this->sHiddenAction ?>"/>
            <table class="withForm insertForm" <?= $this->iMaxVideoLinks > 0 && (count($this->aVideoLinks) >= $this->iMaxVideoLinks) ? 'hide' : '' ?>>
                <tr>
                    <td class="withLabel" style="width: 45px;"><label for="videoLinkTitle_<?= $this->iContainerIDAddition ?>"><?= sysTranslations::get('global_title') ?> *</label></td>
                    <td><input title="<?= sysTranslations::get('global_video_title_tooltip') ?>" class="required default" id="videoLinkTitle_<?= $this->iContainerIDAddition ?>" maxlength="255" type="text" name="title" value=""/></td>
                </tr>
                <tr>
                    <td class="withLabel"><label for="videoLinkLink_<?= $this->iContainerIDAddition ?>">URL *</label></td>
                    <td><input id="videoLinkLinkTitle_<?= $this->iContainerIDAddition ?>" title="<?= sysTranslations::get('global_video_url_tooltip') ?>" class="default required" type="text" name="link" value=""/></td>
                </tr>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" id="saveVideoLinkBtn_<?= $this->iContainerIDAddition ?>" name="send" value="<?= sysTranslations::get('global_video_save') ?>"/></td>
                </tr>
            </table>
        </form>
        <hr>

        <?php

        $sTooltipTitle = '';
        $sTooltipTitle .= ($sTooltipTitle != '' ? '<br />' : '') . sysTranslations::get('global_video_drag');
        if ($this->iMaxVideoLinks > 0) {
            $sTooltipTitle .= ($sTooltipTitle != '' ? '<br />' : '') . sysTranslations::get('global_max_files') . $this->iMaxVideoLinks . sysTranslations::get('global_max_videolinks');
        }
        ?>
        <div class="videoLinksText<?= count($this->aVideoLinks) ? '' : ' hide' ?>">
            <h3><?= sysTranslations::get('global_video_links_already_added') ?> <?= $this->iMaxVideoLinks > 0 ? ' (<span class="videoLinksCountLeft">' . count($this->aVideoLinks) . '/' . $this->iMaxVideoLinks . '</span>) ' : '' ?>
                <div class="hasTooltip tooltip" title="<?= $sTooltipTitle ?>">&nbsp;</div>
            </h3>
        </div>
        <div class="noVideoLinksText<?= count($this->aVideoLinks) ? ' hide' : '' ?>">
            <i><?= sysTranslations::get('global_no_videolinks_uploaded') ?></i>
        </div>

        <ul class="videoLinks <?= $this->sortable ? 'sortable' : '' ?>" data-security="<?= CSRFSynchronizerToken::get() ?>">
            <?php

            /* @var \VideoLink $oVideoLink */
            foreach ($this->aVideoLinks AS $oVideoLink) {
                // Get the correct class, as we need this for a few functions
                $oVideoLink = VideoLinkManager::getVideoLinkById($oVideoLink->mediaId);

                ?>
                <li id="placeholder-<?= $oVideoLink->mediaId ?>" data-mediaid="<?= $oVideoLink->mediaId ?>" data-title="<?= _e($oVideoLink->title) ?>" data-link="<?= _e($oVideoLink->link) ?>" class="placeholder">
                    <div class="videoLinkPlaceholder" title="<?= $oVideoLink->link ?>">
                        <span class="mediaType videoLink"></span><a class="fancyBoxLink fancybox.iframe title" href="<?= $oVideoLink->getEmbedLink(false) ?>"><?= $oVideoLink->title ? $oVideoLink->title : $oVideoLink->link ?></a>
                    </div>
                    <div class="videoThumbsPlaceholder">
                        <?php if ($oVideoLink instanceof VimeoLink) { ?>
                            <img class="thumb1" src="<?= $oVideoLink->getThumbLink() ?>"/>
                        <?php } elseif ($oVideoLink instanceof VideoLink) { ?>
                            <img class="thumb1" src="<?= $oVideoLink->getThumbLink(1) ?>"/>
                            <img class="thumb2" src="<?= $oVideoLink->getThumbLink(2) ?>"/>
                            <img class="thumb3" src="<?= $oVideoLink->getThumbLink(3) ?>"/>
                        <?php } ?>
                    </div>
                    <div class="actionsPlaceholder">
                        <?php

                        if ($this->onlineChangeable) {
                            if ($oVideoLink->isOnlineChangeable()) {
                                echo '<a class="action_icon ' . ($oVideoLink->online ? 'online' : 'offline') . '_icon onlineOfflineBtn" onclick="setOnlineVideoLink(this); return false;" data-online="' . ($oVideoLink->online ? 0 : 1) . '" href="' . $this->changeOnlineLink . '"></a>';
                            } else {
                                echo '<a onclick="return false;" class="action_icon grey ' . ($oVideoLink->online ? 'online' : 'offline') . '_icon onlineOfflineBtn"  href="#"></a>';
                            }
                        }
                        if ($this->editable) {
                            if ($oVideoLink->isEditable()) {
                                echo '<a class="action_icon edit_icon" onclick="showEditVideoLink(this); return false;" href="' . $this->editLink . '"></a>';
                            } else {
                                echo '<a class="action_icon grey edit_icon" onclick="return false;" href="#"></a>';
                            }
                        }
                        if ($this->deletable) {
                            if ($oVideoLink->isDeletable()) {
                                echo '<a class="action_icon delete_icon" onclick="deleteVideoLink(this); return false;" href="' . $this->deleteLink . '"></a>';
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

# include editLinkForm once
include_once $this->sEditVideoLinkFormLocation;

# add sortable javascript initiation code
$sLinkManagerJavascript = <<<EOT
</script>
$("div#ym_{$this->iContainerIDAddition} ul.videoLinks.sortable").sortable({
    items: '> li',
    placeholder: 'ui-state-highlight placeholder',
    forcePlaceholderSize: true,
    tolerance: 'pointer',
    update: function(event, ui) {
        var mediaIds = new Array();
        ui.item.closest('ul.videoLinks').find('> li').each(function(index, value){
            mediaIds[index] = $(value).data('mediaid');
        });
        updateVideoLinkOrder(mediaIds, '{$this->saveOrderLink}', 'ym_{$this->iContainerIDAddition}');
    }
});
// count current amount of Videolinks and check if upload should be disabled
globalFunctions.checkMaxVideoLinks{$this->iContainerIDAddition} = function (){
    var videoLinksManagerID = '#ym_{$this->iContainerIDAddition}';
    var videoLinksCount = $(videoLinksManagerID).find('.videoLinks .placeholder').size();
    var maxVideoLinks = '{$this->iMaxVideoLinks}';
    if(maxVideoLinks>0){    
        if((maxVideoLinks-videoLinksCount) <= 0){
            $('#saveVideoLinkBtn_{$this->iContainerIDAddition}').prop('disabled', true);
            $(videoLinksManagerID).find('.videoLinksCountLeft').html(videoLinksCount+'/'+maxVideoLinks);
            $(videoLinksManagerID).find('.maxVideoLinksError').show();
            $(videoLinksManagerID).find('.insertForm').hide();
        } else {
            $('#saveVideoLinkBtn_{$this->iContainerIDAddition}').prop('disabled', false);
            $(videoLinksManagerID).find('.videoLinksCountLeft').html(videoLinksCount+'/'+maxVideoLinks);
            $(videoLinksManagerID).find('.maxVideoLinksError').hide();
            $(videoLinksManagerID).find('.insertForm').show();
        }
    }
}
</script>
EOT;
$oPageLayout->addJavascript('<script>' . $sLinkManagerJavascript . '</script>');
?>