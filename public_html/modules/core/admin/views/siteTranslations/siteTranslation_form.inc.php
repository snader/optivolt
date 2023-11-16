<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('siteTrans_siteTranslation') ?></legend>
            <form method="POST" action="<?= getCurrentUrl() ?>" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <table class="withForm">
                    <?php if ($oCurrentUser->isAdmin()) { ?>
                        <tr>
                            <td nowrap><?= ucfirst(sysTranslations::get('global_editable')) ?> *&nbsp;</td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_editable_tooltip') ?>" type="radio" <?= $oSiteTranslation->getEditable() ? 'CHECKED' : '' ?> id="editable_1" name="editable" value="1"/> <label
                                        for="editable_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_editable_tooltip') ?>" type="radio" <?= !$oSiteTranslation->getEditable() ? 'CHECKED' : '' ?> id="editable_0"
                                       name="editable" value="0"/> <label for="editable_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oSiteTranslation->isPropValid("editable") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="withLabel" nowrap><label for="languageId"><?= sysTranslations::get('sysTrans_language') ?> *</label></td>
                        <td>
                            <select class="required default" id="languageId" name="languageId" title="<?= _e(sysTranslations::get('sysTrans_enter_language_tooltip')) ?>">
                                <option value="">-- <?= sysTranslations::get('sysTrans_choose_language') ?> --</option>
                                <?php

                                foreach (LanguageManager::getLanguagesByFilter(['hasLocale' => true]) AS $oLanguage) {
                                    echo '<option ' . ($oLanguage->languageId == $oSiteTranslation->languageId ? 'selected' : '') . ' value="' . $oLanguage->languageId . '">' . $oLanguage->nativeName . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oSiteTranslation->isPropValid("languageId") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel" nowrap><label for="label"><?= sysTranslations::get('sysTrans_label') ?> *</label></td>
                        <td><input id="label" class="required default" maxlength="50" name="label" style="width: 400px;" title="<?= _e(sysTranslations::get('sysTrans_enter_label_tooltip')) ?>" type="text"
                                   value="<?= $oSiteTranslation->label ?>"/></td>
                        <td><span class="error"><?= $oSiteTranslation->isPropValid("label") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel" nowrap><label for="text"><?= sysTranslations::get('sysTrans_text') ?> *</label> <div class="hasTooltip tooltip" title="<?= sysTranslations::get('sysTrans_random_tooltip') ?>">&nbsp;</div></td>
                        <td>
                            <textarea class="required default" id="text" name="text" style="width: 580px; height: 26px;" title="<?= _e(sysTranslations::get('sysTrans_enter_text_tooltip')) ?>"><?= $oSiteTranslation->text ?></textarea>
                        </td>
                        <td><span class="error"><?= $oSiteTranslation->isPropValid("text") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2">
                            <input type="submit" value="<?= _e(sysTranslations::get('global_save')) ?>" name="save"/>
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </div>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$sAdminFolder                = ADMIN_FOLDER;
$sController                 = http_get('controller');
$iSiteTranslationId          = $oSiteTranslation->siteTranslationId;
$sSiteTranslationRemoteError = sysTranslations::get('sysTrans_already_exists');
$sJavascript                 = <<<EOT
<script>
    $('#label').rules('add', {
        remote: {
            url: '$sAdminFolder/$sController/ajax-checkLabel?siteTranslationId=$iSiteTranslationId',
            data: {
                languageId: function() {
                    return $('#languageId').val();
                }
            }
        },
         messages: {
            remote: "$sSiteTranslationRemoteError"
        }
    });
        
    $('#languageId').rules('add', {
        remote: {
            url: '$sAdminFolder/$sController/ajax-checkLabel?siteTranslationId=$iSiteTranslationId',
            data: {
                label: function() {
                    return $('#label').val();
                }
            }
        },
         messages: {
            remote: "$sSiteTranslationRemoteError"
        }
    });        
</script>        
EOT;
$oPageLayout->addJavascript($sJavascript);
?>
