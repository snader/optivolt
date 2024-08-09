<div class="cf">
    <div class="contentColumn">
        <form method="POST">
            <input type="hidden" name="filterForm" value="1"/>
            <fieldset style="margin-bottom: 20px;">
                <legend><?= sysTranslations::get('global_filter') ?></legend>
                <table class="withForm">
                    <tr>
                        <td class="withLabel" style="width: 116px;"><label for="label"><?= sysTranslations::get('sysTrans_label') ?></label></td>
                        <td><input type="text" class="default" id="label" name="siteTranslationFilter[label]" value="<?= _e($aSiteTranslationFilter['label']) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="text"><?= sysTranslations::get('sysTrans_text') ?></label></td>
                        <td><input type="text" class="default" id="text" name="siteTranslationFilter[text]" value="<?= _e($aSiteTranslationFilter['text']) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="languageId"><?= sysTranslations::get('global_language') ?></label></td>
                        <td>
                            <select class="default" id="languageId" name="siteTranslationFilter[languageId]">
                                <option value="">-- <?= sysTranslations::get('sysTrans_all_languages') ?> --</option>
                                <?php

                                foreach (LanguageManager::getLanguagesByFilter(['hasLocale' => true]) as $oLanguage) {
                                    echo '<option ' . ($oLanguage->languageId == $aSiteTranslationFilter['languageId'] ? 'selected' : '') . ' value="' . $oLanguage->languageId . '">' . $oLanguage->nativeName . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="filterSiteTranslations" value="<?= sysTranslations::get('sysTrans_filter_systemTranslations') ?>"/> <input type="submit" name="resetFilter"
                                                                                                                                                                  value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('siteTrans_full_site_translations') ?></legend>
            <ul style="padding-left: 15px; margin-bottom: 10px;">
                <?php

                foreach (LanguageManager::getLanguagesByFilter(['hasLocale' => true]) as $oLanguage) {
                    echo '<li><a href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/edit-full-site-translation/' . $oLanguage->languageId . '">' . $oLanguage->nativeName . '</a></li>';
                }
                ?>
            </ul>
        </fieldset>
    </div>
</div>
<table class="sorted" style="width: 100%;">
    <thead>
    <tr class="topRow">
        <td colspan="4">
            <h2><?= sysTranslations::get('sysTrans_all') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('sysTrans_add_tooltip') ?>"
                   alt="<?= sysTranslations::get('sysTrans_add_tooltip') ?>"><?= sysTranslations::get('sysTrans_add') ?></a><br/>
                <a class="bookQuestionBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/missing-translations" title="<?= sysTranslations::get('sysTrans_missing') ?>"
                   alt="<?= sysTranslations::get('sysTrans_missing') ?>"><?= sysTranslations::get('sysTrans_missing') ?></a><br/>
                <a class="exportBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/export" title="<?= sysTranslations::get('sysTrans_export') ?>"
                   alt="<?= sysTranslations::get('sysTrans_export') ?>"><?= sysTranslations::get('sysTrans_export') ?></a><br/>
            </div>
        </td>
    </tr>
    <tr>
        <th><?= sysTranslations::get('sysTrans_language') ?></th>
        <th><?= sysTranslations::get('sysTrans_label') ?></th>
        <th><?= sysTranslations::get('sysTrans_text') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aSiteTranslations as $oSiteTranslation) {
        echo '<tr>';
        echo '<td>' . $oSiteTranslation->getLanguage()->nativeName . '</td>';
        echo '<td>' . $oSiteTranslation->label . '</td>';
        echo '<td>' . nl2br(_e($oSiteTranslation->text)) . '</td>';
        echo '<td>';

        if ($oSiteTranslation->isEditable()) {
            echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('sysTrans_edit_systemTranslation') . '" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/bewerken/' . $oSiteTranslation->siteTranslationId . '"></a>';
        }
        if ($oCurrentUser->isAdmin()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('sysTrans_delete_systemTranslation') . '" onclick="return confirmChoice(\'' . strtolower(
                    sysTranslations::get('sysTrans_systemTranslation') ?? ''
                ) . ' ' . $oSiteTranslation->label . '\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oSiteTranslation->siteTranslationId . '?' . CSRFSynchronizerToken::query() . '"></a>';
        }
        echo '</td>';
        echo '</tr>';
    }
    if (count($aSiteTranslations) == 0) {
        echo '<tr><td colspan="4">' . sysTranslations::get('sysTrans_no_systemTranslations') . '</i></td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bottomRow">
        <td colspan="6">
            <form method="POST">
                <?= generatePaginationHTML($iPageCount, $iCurrPage) ?>
                <input type="hidden" name="setPerPage" value="1"/>
                <select name="perPage" onchange="$(this).closest('form').submit();">
                    <option value="<?= $iNrOfRecords ?>"><?= sysTranslations::get('global_all') ?></option>
                    <option <?= $iPerPage == 10 ? 'SELECTED' : '' ?> value="10">10</option>
                    <option <?= $iPerPage == 25 ? 'SELECTED' : '' ?> value="25">25</option>
                    <option <?= $iPerPage == 50 ? 'SELECTED' : '' ?> value="50">50</option>
                    <option <?= $iPerPage == 100 ? 'SELECTED' : '' ?> value="100">100</option>
                </select> <?= sysTranslations::get('global_per_page') ?>
            </form>
        </td>
    </tr>
    </tfoot>
</table>
