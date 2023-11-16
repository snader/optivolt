<div class="cf" style="min-width: 650px;">
    <form method="POST" action="<?= getCurrentUrl() ?>" id="moduleActionForm">
        <input type="hidden" value="saveModuleAction" name="action"/>
        <h1><?= sysTranslations::get('moduleActions_moduleAction') ?></h1>
        <?php include_once getAdminSnippet('errorbox') ?>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 160px;"><label for="displayName"><?= sysTranslations::get('moduleActions_displayName') ?> *</label></td>
                <td><input id="displayName" class="required autofocus default" title="<?= sysTranslations::get('moduleActions_displayName_tooltip') ?>" type="text" autocomplete="off" name="displayName"
                           value="<?= $oModuleAction->displayName ?>"/></td>
                <td><span class="error"><?= $oModuleAction->isPropValid("displayName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
            </tr>
            <?php if ($oCurrentUser->isSuperAdmin()) { ?>
                <tr>
                    <td class="withLabel"><label for="name"><?= sysTranslations::get('moduleActions_systemName') ?> *</label></td>
                    <td><input id="name" class="required default" title="<?= sysTranslations::get('moduleActions_name_tooltip') ?>" type="text" autocomplete="off" name="name" value="<?= $oModuleAction->name ?>"/></td>
                    <td><span class="error"><?= $oModuleAction->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                </tr>
                <tr>
                    <td><?= sysTranslations::get('global_editable') ?></td>
                    <td>
                        <input class="alignRadio" title="<?= sysTranslations::get('pages_editable_tooltip') ?>" type="radio" <?= $oModuleAction->getEditable() ? 'CHECKED' : '' ?> id="editable_1" name="editable" value="1"/> <label
                                for="editable_1"><?= sysTranslations::get('global_yes') ?></label>
                        <input style="margin-left: 5px;" class="alignRadio" type="radio" <?= !$oModuleAction->getEditable() ? 'CHECKED' : '' ?> id="editable_0" name="editable" value="0"/> <label for="editable_0"><?= sysTranslations::get(
                                'global_no'
                            ) ?></label>
                    </td>
                    <td><span class="error"><?= $oModuleAction->isPropValid("editable") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                </tr>
                <tr>
                    <td><?= sysTranslations::get('global_deletable') ?></td>
                    <td>
                        <input class="alignRadio" type="radio" <?= $oModuleAction->getDeletable() ? 'CHECKED' : '' ?> id="deletable_1" name="deletable" value="1"/> <label for="deletable_1"><?= sysTranslations::get('global_yes') ?></label>
                        <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_deletable_tooltip') ?>" type="radio" <?= !$oModuleAction->getDeletable() ? 'CHECKED' : '' ?> id="deletable_0"
                               name="deletable" value="0"/> <label for="deletable_0"><?= sysTranslations::get('global_no') ?></label>
                    </td>
                    <td><span class="error"><?= $oModuleAction->isPropValid("deletable") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                </tr>
            <?php } ?>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2">
                    <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    var submithandlerModuleForm = function (form) {
        $.ajax({
            type: 'POST',
            cache: false,
            async: false,
            url: $(form).attr('action'),
            data: $(form).serializeArray(),
            success: function (data) {
                $.fancybox.open(data);
            }
        });
        return false;
    };

    setValidateForform('#moduleActionForm', submithandlerModuleForm);
</script>