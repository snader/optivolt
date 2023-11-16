<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="6">
            <?php include_once getAdminSnippet('localeSelect'); ?>
        </td>
    </tr>
    <tr class="topRow">
        <td colspan="3"><h2><?= sysTranslations::get('system_all_categories') ?></h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/toevoegen" title="<?= sysTranslations::get('system_category_add') ?>"
                alt="<?= sysTranslations::get('system_category_add') ?>"><?= sysTranslations::get('system_category_add') ?></a><br/><a class="changeOrderBtn textRight"
                href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/volgorde-wijzigen"
                title="<?= sysTranslations::get('global_change_order') ?>"
                alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get(
                'global_change_order'
                ) ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline">&nbsp;</th>
        <th><?= sysTranslations::get('system_category_name') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aSystemCategories AS $oSystemCategory) {
        echo '<tr>';
        echo '<td>';
        # online offline button
        echo '<a id="systemCategory_' . $oSystemCategory->systemCategoryId . '_online_1" title="' . sysTranslations::get(
                'system_category_set_offline_tooltip'
            ) . '" class="action_icon ' . ($oSystemCategory->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/ajax-setOnline/' . $oSystemCategory->systemCategoryId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="systemCategory_' . $oSystemCategory->systemCategoryId . '_online_0" title="' . sysTranslations::get(
                'system_category_set_online_tooltip'
            ) . '" class="action_icon ' . ($oSystemCategory->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/ajax-setOnline/' . $oSystemCategory->systemCategoryId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . _e($oSystemCategory->name) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('system_category_edit') . '" href="' . ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oSystemCategory->systemCategoryId . '"></a>';

        if ($oSystemCategory->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('system_category_delete') . '" onclick="return confirmChoice(\'' . _e($oSystemCategory->name) . '\');" href="' . ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/verwijderen/' . $oSystemCategory->systemCategoryId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="' . sysTranslations::get('system_category_not_deletable') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aSystemCategories)) {
        echo '<tr><td colspan="3"><i>' . sysTranslations::get('system_no_categories') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>
<?php

# create necessary javascript
$sSystemCatOnlineMsg     = sysTranslations::get('system_category_online');
$sSystemCatOfflineMsg    = sysTranslations::get('system_category_offline');
$sSystemCatnotchangedMsg = sysTranslations::get('system_category_not_changed');
$sController           = Request::getControllerSegment();
$sBottomJavascript     = <<<EOT
<script>
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: $(this).prop('href'),
            data: 'ajax=1',
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $("#systemCategory_"+dataObj.systemCategoryId+"_online_0").hide(); // hide offline button
                    $("#systemCategory_"+dataObj.systemCategoryId+"_online_1").hide(); // hide online button
                    $("#systemCategory_"+dataObj.systemCategoryId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)
                        showStatusUpdate("$sSystemCatOfflineMsg");
                    if(dataObj.online == 1)
                        showStatusUpdate("$sSystemCatOnlineMsg");
                } else {
                    showStatusUpdate("$sSystemCatnotchangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>