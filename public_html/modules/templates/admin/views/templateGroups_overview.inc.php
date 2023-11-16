<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="6">
            <h2><?= sysTranslations::get('templateGroups_found_templateGroups') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('templateGroups_add_templateGroup') ?>"
                   alt="<?= sysTranslations::get('templateGroups_add_templateGroup') ?>"><?= sysTranslations::get('templateGroups_add_templateGroup') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_name') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('templates_technical_name') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aTemplateGroups AS $oTemplateGroup) {
        echo '<tr>';
        echo '<td>' . $oTemplateGroup->templateGroupName . '</td>';
        echo '<td>' . $oTemplateGroup->name . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('templateGroups_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oTemplateGroup->templateGroupId . '"></a>';
        if ($oTemplateGroup->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('templateGroups_remove') . '" onclick="return confirmChoice(\'' . $oTemplateGroup->templateGroupName . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oTemplateGroup->templateGroupId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey"></span>';
        }
        echo '</td>';
        echo '</tr>';
    }
    if (count($aTemplateGroups) == 0) {
        echo '<tr><td colspan="5"><i>' . sysTranslations::get('templateGroups_no_templateGroups') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>