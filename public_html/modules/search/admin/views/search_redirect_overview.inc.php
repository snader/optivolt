<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend><?= sysTranslations::get('global_filter') ?></legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 116px;"><label for="q"><?= sysTranslations::get('global_search_word') ?></label></td>
                <td><input type="text" class="default" id="q" name="searchRedirectFilter[q]" value="<?= _e($aSearchRedirectFilter['q']) ?>"/></td>
            </tr>
            <tr>
                <td class="withLabel" style="width: 116px;"><label for="withlink"><?= sysTranslations::get('searchredirect_withlink') ?></label></td>
                <td>
                    <select class="default" id="withlink" name="searchRedirectFilter[withlink]">
                        <option value=""><?= sysTranslations::get('searchredirect_showall') ?></option>
                        <option value="1"><?= sysTranslations::get('global_yes') ?></option>
                        <option value="0"><?= sysTranslations::get('global_no') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterSearchRedirects" value="Filter"/> <input type="submit" name="resetFilter" value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
            </tr>
        </table>
    </fieldset>
</form>

<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="5"><h2><?= sysTranslations::get('searchredirect_all_items') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('searchredirect_add') ?>"
                   alt="<?= sysTranslations::get('searchredirect_add') ?>"><?= sysTranslations::get('searchredirect_add') ?></a><br/>
            </div>
        </td>
    </tr>
    <tr>
        <th><?= sysTranslations::get('searchredirect_searchword') ?></th>
        <th><?= sysTranslations::get('global_link') ?></th>
        <th>Hits</th>
        <th><?= sysTranslations::get('locales_language') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody id="tableContents">
    <?php

    foreach ($aAllSearchRedirects AS $oSearchRedirect) {
        echo '<tr data-id="' . $oSearchRedirect->searchId . '">';
        echo '<td>' . _e($oSearchRedirect->searchword) . '</td>';
        echo '<td>' . $oSearchRedirect->getLinkLocation() . '</td>';
        echo '<td>' . $oSearchRedirect->hits . '</td>';
        echo '<td>' . $oSearchRedirect->getLanguage()->nativeName . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('searchredirect_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oSearchRedirect->searchId . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('searchredirect_delete') . '" onclick="return confirmChoice(\'' . _e($oSearchRedirect->searchword) . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/verwijderen/' . $oSearchRedirect->searchId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aAllSearchRedirects)) {
        echo '<tr><td colspan="6"><i>' . sysTranslations::get('searchredirect_no_items') . '</i></td></tr>';
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