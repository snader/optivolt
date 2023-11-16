<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('pages_copy_structure') ?> (<?= AdminLocales::getAdminLocale()
                    ->getLanguage()->nativeName ?>)
            </legend>
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="copyStructure" name="action"/>
                <table class="withForm" style="width: 100%;">
                    <tr>
                        <td class="withLabel" style="width: 120px;"><label for="languageId"><?= sysTranslations::get('pages_copy_to') ?> *</label></td>
                        <td>
                            <select id="languageId" class="required default" name="languageId" title="<?= sysTranslations::get('pages_languageId_title') ?>">
                                <?php if (count($aLanguages) > 1) { ?>
                                    <option value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                <?php } ?>
                                <?php

                                foreach ($aLanguages AS $oLanguage) {
                                    echo '<option ' . ($oLanguage->languageId == http_post('languageId') ? 'selected' : '') . ' value="' . $oLanguage->languageId . '">' . $oLanguage->nativeName . '</option>';
                                }
                                ?>
                            </select>
                        </td>
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
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>