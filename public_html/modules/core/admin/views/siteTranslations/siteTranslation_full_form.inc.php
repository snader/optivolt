<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<form method="POST">
    <?= CSRFSynchronizerToken::field() ?>
    <input name="action" type="hidden" value="save"/>
    <table class="sorted" style="width: 100%;">
        <thead>
        <tr class="topRow">
            <td colspan="3">
                <h2><?= sysTranslations::get('sysTrans_all_translations_for_language') ?> <?= $oLanguage->nativeName ?></h2>
                <div style="float: right;">
                    <?= sysTranslations::get('sysTrans_compare_with_language') ?>
                    <select id="compareLanguageId" name="compareLanguageId">
                        <option value="">-- <?= sysTranslations::get('sysTrans_choose_language') ?> --</option>
                        <?php

                        foreach (LanguageManager::getLanguagesByFilter(['hasLocale' => true]) AS $oLanguageForMenu) {
                            if ($oLanguageForMenu->languageId == http_get('param2')) {
                                continue;
                            }
                            echo '<option ' . ($oLanguageForMenu->languageId == $oCompareLanguage->languageId ? 'selected' : '') . ' value="' . $oLanguageForMenu->languageId . '">' . $oLanguageForMenu->nativeName . '</option>';
                        }
                        ?>
                    </select>
                </div>

            </td>
        </tr>
        <tr>
            <th style="width: 33.33333%;"><?= sysTranslations::get('sysTrans_label') ?></th>
            <th><?= sysTranslations::get('sysTrans_text') ?> <?= $oLanguage->nativeName ?></th>
            <?php if (!empty($oCompareLanguage)) { ?>
                <th style="width: 33.33333%;"><?= sysTranslations::get('sysTrans_text') ?> <?= $oCompareLanguage->nativeName ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($aSiteTranslations AS $oSiteTranslation) {
            // take text or if empty and not empty compare, take that one
            $sText = !empty($oSiteTranslation->text) ? $oSiteTranslation->text : (!empty($aCompareTextsByLabel[$oSiteTranslation->label]) ? $aCompareTextsByLabel[$oSiteTranslation->label] : '');

            preg_match_all('#\n#', $sText, $aMatches);
            $iRows = 1;
            if (isset($aMatches[0])) {
                $iRows += count($aMatches[0]);
            }
            echo '<tr>';
            echo '<td>' . $oSiteTranslation->label . '</td>';
            echo '<td>';
            echo '<input type="hidden" name="siteTranslations[labels][]" value="' . $oSiteTranslation->label . '" />';
            echo '<textarea name="siteTranslations[texts][]" style="height: ' . (15 * $iRows) . 'px; width: 95%;">' . $oSiteTranslation->text . '</textarea>';
            echo '</td>';
            // set text to compare if wanted
            if (!empty($oCompareLanguage)) {
                echo '<td>' . (!empty($aCompareTextsByLabel[$oSiteTranslation->label]) ? nl2br(_e($aCompareTextsByLabel[$oSiteTranslation->label])) : '') . '</td>';
            }
            echo '</tr>';
        }
        if (count($aSiteTranslations) == 0) {
            echo '<tr><td colspan="3">' . sysTranslations::get('siteTrans_no_siteTranslations') . '</i></td></tr>';
        }
        ?>
        </tbody>
        <?php if (count($aSiteTranslations) > 0) { ?>
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
<?php

$sConfirm        = sysTranslations::get('sysTrans_change_language_will_empty_form');
$sCurrentUrlPath = getCurrentUrlPath();
$sJavascript     = <<<EOT
<script>
    var lastCompareLanguageId = $('#compareLanguageId').val();
    $('#compareLanguageId').change(function(e) {
        if (confirm('$sConfirm')) {
            window.location = '$sCurrentUrlPath?compareLanguageId=' + $('#compareLanguageId').val();
        }else{
            $('#compareLanguageId').val(lastCompareLanguageId);
           e.preventDefault();
        }
    });
</script>
EOT;
$oPageLayout->addJavascript($sJavascript);
?>
