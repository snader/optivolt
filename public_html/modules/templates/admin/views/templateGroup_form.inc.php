<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="contentColumn">
    <form method="POST" action="" class="validateForm">
        <?= CSRFSynchronizerToken::field() ?>
        <input type="hidden" value="save" name="action"/>
        <fieldset>
            <legend><?= sysTranslations::get('templateGroups_templateGroup') ?></legend>
            <table class="withForm">
                <tr>
                    <td class="withLabel" style="width: 120px;"><label for="templateGroupName"><?= sysTranslations::get('templateGroups_templateGroupName') ?> *</label></td>
                    <td><input id="description" class="required autofocus default" title="<?= sysTranslations::get('templateGroups_templateGroupName_tooltip') ?>" type="text" name="templateGroupName"
                               value="<?= $oTemplateGroup->templateGroupName ?>"/></td>
                    <td><span class="error"><?= $oTemplateGroup->isPropValid("templateGroupName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                </tr>
                <tr>
                    <td class="withLabel"><label for="templateVariables"><?= sysTranslations::get('templateGroups_templateVariables') ?></label></td>
                    <td>
                        <textarea id="description" class="required autofocus default" name="templateVariables" style="height: 350px;"><?= $oTemplateGroup->templateVariables ?></textarea>
                    </td>
                    <td><span class="error"><?= $oTemplateGroup->isPropValid("templateVariables") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                </tr>
                <?php if ($oCurrentUser->isAdmin()) { ?>
                    <tr>
                        <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('global_admin_settings') ?></h2></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="name"><?= sysTranslations::get('templates_group_unique_name') ?></label></td>
                        <td><input id="description" class="default" data-rule-remote="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/ajax-checkName?templateGroupId=<?= $oTemplateGroup->templateGroupId ?>&<?= CSRFSynchronizerToken::query() ?>"
                                   title="<?= sysTranslations::get('templates_group_unique_name_tooltip') ?>" type="text" name="name" value="<?= $oTemplateGroup->name ?>"/></td>
                        <td><span class="error"><?= $oTemplateGroup->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?> </span></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3">
                        <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
