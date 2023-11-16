<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST">
            <input type="hidden" name="filterForm" value="1"/>
            <fieldset style="margin-bottom: 20px;">
                <legend><?= sysTranslations::get('global_filter') ?></legend>
                <table class="withForm">
                    <tr>
                        <td class="withLabel" style="width: 116px;"><?= sysTranslations::get('sysTrans_label') ?></td>
                        <td><input class="default" type="text" name="siteTranslationFilter[label]" value="<?= _e($aSiteTranslationFilter['label']) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><?= sysTranslations::get('sysTrans_text') ?></td>
                        <td><input class="default" type="text" name="siteTranslationFilter[text]" value="<?= _e($aSiteTranslationFilter['text']) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><?= sysTranslations::get('global_language') ?></td>
                        <td>
                            <select class="default" name="siteTranslationFilter[languageId]">
                                <option value="">-- <?= sysTranslations::get('sysTrans_all_languages') ?> --</option>
                                <?php

                                foreach (LanguageManager::getLanguagesByFilter(['hasLocale' => true]) AS $oLanguage) {
                                    echo '<option ' . ($oLanguage->languageId == $aSiteTranslationFilter['languageId'] ? 'selected' : '') . ' value="' . $oLanguage->languageId . '">' . $oLanguage->nativeName . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="filterSiteTranslations" value="<?= sysTranslations::get('sysTrans_filter_Translations') ?>"/> <input type="submit" name="resetFilter"
                                                                                                                                                            value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
</div>
<div>
    <a href="<?= getCurrentUrlPath() ?>?export-type=install">Voor installatie files</a><br/>
    <a href="<?= getCurrentUrlPath() ?>?export-type=database">Voor database import</a><br/>
</div>
<?php

if (http_get('export-type') == 'install') {
    echo '<fieldset>';
    echo '<legend>Export for install</legend>';
    echo '<textarea class="default" style="width: 100%; height: 450px;" onclick="copyToCliboard(this);">';
    echo '$aNeededSiteTranslations = array(' . PHP_EOL;
    foreach ($aSiteTranslationsByLanguageId AS $iLanguageId => $aTranslations) {
        echo "\t" . db_int($iLanguageId) . ' => array(' . PHP_EOL;
        foreach ($aTranslations AS $oTranslation) {
            echo "\t\t" . 'array(\'label\' => ' . db_str($oTranslation->label) . ', \'text\' => ' . db_str($oTranslation->text) . ', \'editable\' => ' . db_int($oTranslation->editable) . '),' . PHP_EOL;
        }
        echo "\t" . '),' . PHP_EOL;
    }
    echo ');';
    echo '</textarea>';
    echo '</fieldset>';
}

if (http_get('export-type') == 'database') {
    echo '<fieldset>';
    echo '<legend>Export for database</legend>';
    echo '<pre>';
    echo 'INSERT IGNORE INTO `site_translations` (`languageId`, `label`, `text`, `editable`, `created`, `createdBy`) VALUES' . PHP_EOL;
    $oTranslation = new SiteTranslation();
    foreach ($aSiteTranslations AS $iKey => $oTranslation) {
        echo ($iKey != 0 ? ',' : ' ') . '(';
        echo db_int($oTranslation->languageId) . ', ' . db_str($oTranslation->label) . ', ' . db_str($oTranslation->text) . ', ' . db_int($oTranslation->editable) . ', NOW(), ' . db_str('A-side admin');
        echo ')' . PHP_EOL;
    }
    echo ';';
    echo '</pre>';
    echo '</fieldset>';
}
?>
<script>
    function copyToCliboard(element) {
        element.select();
        var succeeded;
        try {
            // Copy it to the clipboard
            succeeded = document.execCommand('copy');
        } catch (e) {
            succeeded = false;
        }
        if (succeeded) {
            alert('Copied to clipboard');
        } else {
            alert('Could not copy to clipboard');
        }
    }
</script>