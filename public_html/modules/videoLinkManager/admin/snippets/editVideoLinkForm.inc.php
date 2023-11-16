<div class="hide">
    <div id="editVideoLinkForm">
        <h2><?= sysTranslations::get('global_change_video_link') ?></h2>
        <form onsubmit="saveVideoLink(this); return false;" method="POST" action="#">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" name="mediaId" value=""/>
            <label for="videoLinkTitle"><?= sysTranslations::get('global_title') ?></label><br/>
            <input type="text" class="default" id="videoLinkTitle" name="title" value=""/><br/>
            <label for="videoLinkLink">URL</label><br/>
            <input type="text" class="default" id="videoLinkLink" name="link" value=""/><br/>
            <input type="submit" name="" value="<?= sysTranslations::get('global_save_video_link') ?>"/>
        </form>
    </div>
    <a id="editVideoLinkFormLink" class="fancyBoxLink" href="#editVideoLinkForm"></a>
</div>