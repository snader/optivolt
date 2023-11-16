<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="contentColumn">
    <form method="POST" action="" class="validateForm">
        <?= CSRFSynchronizerToken::field() ?>
        <input type="hidden" value="save" name="action"/>
        <?php if (is_numeric($oTemplate->templateId)) { ?>
            <input type="hidden" value="<?= $oTemplate->type ?>" name="type"/>
            <input type="hidden" value="<?= $oTemplate->templateGroupId ?>" name="templateGroupId"/>
        <?php } ?>
        <fieldset>
            <legend><?= sysTranslations::get('templates_template') ?></legend>
            <table class="withForm" style="width: 100%;">
                <tr>
                    <td class="withLabel" style="width: 120px;"><label for="description"><?= sysTranslations::get('templates_definition_name') ?> *</label></td>
                    <td><input id="description" class="required autofocus default" title="<?= sysTranslations::get('templates_enter_template_description') ?>" type="text" name="description" value="<?= $oTemplate->description ?>"/></td>
                    <td><span class="error"><?= $oTemplate->isPropValid("description") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                </tr>
                <tr>
                    <td class="withLabel" style="width: 120px;"><label for="type"><?= sysTranslations::get('global_message_type') ?> *</label></td>
                    <?php if (!is_numeric($oTemplate->templateId)) { ?>
                        <td>
                            <select onchange="changeMessageFieldOptions(this.value);" id="type" class="required" title="<?= sysTranslations::get('templates_choose_template_type') ?>" name="type">
                                <?php

                                # get unique type values
                                foreach (TemplateManager::getTemplateTypes() AS $sTemplateType) {
                                    echo '<option ' . ($oTemplate->type == $sTemplateType ? 'SELECTED' : '') . ' value="' . $sTemplateType . '">' . _e($sTemplateType) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    <?php } else { ?>
                        <td class="withLabel">
                            <?= _e($oTemplate->type) ?>
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td class="withLabel"><label for="templateGroupId"><?= sysTranslations::get('templates_template_group') ?> *</label></td>
                    <?php if (!is_numeric($oTemplate->templateId)) { ?>
                        <td>
                            <select onchange="setTemplateVariables(this.value);" id="templateGroupId" class="required" title="<?= sysTranslations::get('templates_choose_template_group') ?>" name="templateGroupId">
                                <?php

                                foreach (TemplateManager::getAllTemplateGroups() AS $oTemplateGroup) {
                                    echo '<option ' . ($oTemplate->templateGroupId == $oTemplateGroup->templateGroupId ? 'SELECTED' : '') . ' value="' . $oTemplateGroup->templateGroupId . '">' . $oTemplateGroup->templateGroupName . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    <?php } else { ?>
                        <td class="withLabel">
                            <?php

                            $oTemplateGroup = TemplateManager::getTemplateGroupById($oTemplate->templateGroupId);
                            echo _e($oTemplateGroup->templateGroupName);
                            ?>
                        </td>
                    <?php } ?>
                    <td><span class="error"><?= $oTemplate->isPropValid("templateGroupId") ? '' : sysTranslations::get('templates_field_not_completed') ?></span></td>
                </tr>
                <tr>
                    <td class="withLabel"><label for="subject"><?= sysTranslations::get('templates_topic') ?></label></td>
                    <td><input id="subject" size="46" type="text" name="subject" value="<?= $oTemplate->subject ?>"/></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3"><label for="template"><?= sysTranslations::get('global_message') ?></label></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <textarea name="template" id="template" class="tiny_MCE_default tiny_MCE template_email"><?= $oTemplate->template ?></textarea>
                    </td>
                </tr>
                <?php if ($oCurrentUser->isAdmin()) { ?>
                    <tr>
                        <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('global_admin_settings') ?></h2></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="name"><?= sysTranslations::get('templates_unique_name') ?></label></td>
                        <td><input id="description" class="default" data-rule-remote="<?= ADMIN_FOLDER ?>/templates-beheer/ajax-checkName?templateId=<?= $oTemplate->templateId ?>&<?= CSRFSynchronizerToken::query() ?>"
                                   title="<?= sysTranslations::get('template_unique_name_tooltip') ?>" type="text" name="name" value="<?= $oTemplate->name ?>"/></td>
                        <td><span class="error"><?= $oTemplate->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?> </span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('global_editable') ?></td>
                        <td>
                            <input class="alignRadio" title="<?= sysTranslations::get('templates_editable_tooltip') ?>" type="radio" <?= $oTemplate->getEditable() ? 'CHECKED' : '' ?> id="editable_1" name="editable" value="1"/> <label
                                    for="editable_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('templates_editable_tooltip') ?>" type="radio" <?= !$oTemplate->getEditable() ? 'CHECKED' : '' ?> id="editable_0"
                                   name="editable" value="0"/> <label for="editable_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oTemplate->isPropValid("editable") ? '' : sysTranslations::get('global_field_not_completed') ?> </span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('global_deletable') ?></td>
                        <td>
                            <input class="alignRadio" title="<?= sysTranslations::get('templates_deletable_tooltip') ?>" type="radio" <?= $oTemplate->getDeletable() ? 'CHECKED' : '' ?> id="deletable_1" name="deletable" value="1"/> <label
                                    for="deletable_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('templates_deletable_tooltip') ?>" type="radio" <?= !$oTemplate->getDeletable() ? 'CHECKED' : '' ?> id="deletable_0"
                                   name="deletable" value="0"/> <label for="deletable_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oTemplate->isPropValid("deletable") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
<div class="contentColumn">
    <fieldset>
        <legend><?= sysTranslations::get('templates_variables') ?>
            <div class="tooltip hasTooltip" title="<?= sysTranslations::get('templates_variables_tooltip') ?>">&nbsp;</div>
        </legend>
        <p id="templateVariables">

        </p>
    </fieldset>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$sAdminFolder = ADMIN_FOLDER;
$sController  = http_get('controller');
$sJavascript  = '';

if (is_numeric(http_get('param2')) && $oTemplate->type == 'email') {
    $sJavascript .= <<<EOT
<script>
    initTinyMCE(".tiny_MCE_default", "/dashboard/paginas/link-list", "", "", '100%');
</script>
EOT;
}

$sNoRetreiveMsg = sysTranslations::get('templates_cannot_retrieve_vars');

$sJavascript .= <<<EOT
<script>
    function setTemplateVariables(templateGroupId){
        if(!templateGroupId){
            templateGroupId = $('#templateGroupId').val();
        }

        $.ajax({
            url: '$sAdminFolder/$sController/ajax-getTemplateVariables?templateGroupId='+templateGroupId,
            success: function(data){
                var dataObj = eval('('+data+')');
                if(dataObj.success){
                    $('#templateVariables').html(dataObj.sHtml);
                }else{
                    alert('$sNoRetreiveMsg');
                }
            }
        });
    }

    setTemplateVariables('{$oTemplate->templateGroupId}');

    // alter show/hide behaviour message field for template type
    function changeMessageFieldOptions(value) {
        var tinyMceId = $(document).find('textarea').prop('id');
        if(value == 'sms') {
            tinymce.remove("#"+tinyMceId);
        } else if(value == 'email') {
            initTinyMCE(".tiny_MCE_default", "/dashboard/paginas/link-list", "", "", '100%');
        }
    }
    // initialise message field
    changeMessageFieldOptions('{$oTemplate->type}');

</script>
EOT;
$oPageLayout->addJavascript($sJavascript);
?>