<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<form method="POST" class="validateForm">
    <?= CSRFSynchronizerToken::field() ?>
    <input name="action" type="hidden" value="save"/>
    <table class="sorted" style="width: 100%;">
        <thead>
        <tr class="topRow">
            <td colspan="3">
                <h2>
                    <?= sysTranslations::get('sysTrans_all_translations_for_language') ?>
                    <select class="required default" title="<?=_e(sysTranslations::get('sysTrans_enter_language_tooltip')) ?>" id="languageId" name="languageId">
                        <option value="">-- <?= sysTranslations::get('sysTrans_choose_language') ?> --</option>
                        <?php

                        foreach (LanguageManager::getLanguagesByFilter(['hasLocale' => true]) AS $oLanguage) {
                            echo '<option value="' . $oLanguage->languageId . '">' . $oLanguage->nativeName . '</option>';
                        }
                        ?>
                    </select>
                </h2>
            </td>
        </tr>
        <tr>
            <th style="width: 33.33333%;"><?= sysTranslations::get('sysTrans_label') ?></th>
            <th><?= sysTranslations::get('sysTrans_text') ?> </th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($aMissingLabels AS $sLabel) {
            echo '<tr>';
            echo '<td>' . $sLabel . '</td>';
            echo '<td>';
            echo '<input type="hidden" name="siteTranslations[labels][]" value="' . $sLabel . '" />';
            echo '<textarea name="siteTranslations[texts][]" style="width: 95%;"></textarea>';
            echo '</td>';
            echo '</tr>';
        }
        if (count($aMissingLabels) == 0) {
            echo '<tr><td colspan="3">' . sysTranslations::get('siteTrans_no_siteTranslations') . '</i></td></tr>';
        }
        ?>
        </tbody>
        <?php if (count($aMissingLabels) > 0) { ?>
            <tfoot>
            <tr class="bottomRow">
                <td colspan="3">
                    <input type="submit" value="<?= sysTranslations::get('global_save') ?>"/>
                </td>
            </tr>
            </tfoot>
        <?php } ?>
    </table>
</form>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
