<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('log') ?></legend>
                <table class="withForm" style="width: 100%;">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('log_set_online_tooltip') ?>" type="radio" <?= $oLog->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('log_set_online_tooltip') ?>" type="radio" <?= !$oLog->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oLog->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel" style="width: 120px;"><label for="title"><?= sysTranslations::get('global_title') ?> *</label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('log_title_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td><input id="title" class="required autofocus default" title="<?= sysTranslations::get('log_enter_title_tooltip') ?>" type="text" autocomplete="off" name="title" value="<?= _e($oLog->title) ?>"/></td>
                        <td><span class="error"><?= $oLog->isPropValid("title") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    
                    <tr>
                        <td colspan="3" style="padding-top: 20px;"><label for="intro"><?= sysTranslations::get('log_intro') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('log_intro_tooltip') ?>">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea name="intro" id="intro" class="tiny_MCE_default tiny_MCE intro"><?= $oLog->intro ?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-top: 20px;"><label for="content"><?= sysTranslations::get('log_content') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('log_content_tooltip') ?>">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea name="content" id="content" class="tiny_MCE_default tiny_MCE"><?= $oLog->content ?></textarea></td>
                    </tr>
                    

                    <tr>
                        <td colspan="3">
                            <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
    <?php ?>



</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$jsLang = empty($oCurrentUser) ? 'nl' : $oCurrentUser->getLanguage()->abbr;
$oPageLayout->addJavascript(
    '
<script>
    initTinyMCE(".tiny_MCE_default.intro", "/dashboard/paginas/link-list" , undefined, undefined, undefined, undefined, "' . $jsLang . '");
    initTinyMCE(".tiny_MCE_default:not(.intro)", "/dashboard/paginas/link-list", "/dashboard/logs/image-list/' . $oLog->logId . '", undefined, undefined, undefined, "' . $jsLang . '");
</script>
'
);
?>