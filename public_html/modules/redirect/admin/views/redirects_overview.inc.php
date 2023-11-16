<table class="sorted withActionIcons" style="float: left; margin-right: 20px;">
    <thead>
    <tr class="topRow">
        <td colspan="4"><h2><?= sysTranslations::get('redirectsSpecific') ?></h2>
            <div class="right">
                <a class="addBtn textLeft" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen?type=1" title="<?= sysTranslations::get('redirectsAdd') ?>"
                   alt="<?= sysTranslations::get('redirectsAdd') ?>"><?= sysTranslations::get('redirectsAdd') ?></a><br/><a id="importBatch" class="exportExcelBtn textLeft right" href="/admin/redirect-import"
                                                                                                                            title="<?= sysTranslations::get('redirectsImportBatch') ?>"
                                                                                                                            alt="<?= sysTranslations::get('redirectsImportBatch') ?>"><?= sysTranslations::get('redirectsImportBatch') ?></a>
                <input id="filePopUp" type="file" style="display: none;"/>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline"></th>
        <th><?= sysTranslations::get('redirectsPattern') ?></th>
        <th><?= sysTranslations::get('redirectsNewUrl') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody id="tableContents">
    <?php

    foreach ($aAllRedirects AS $oRedirect) {
        echo '<tr data-id="' . $oRedirect->redirectId . '" class="movable">';
        echo '<td>';
        # online offline button
        echo '<a id="Redirect_' . $oRedirect->redirectId . '_online_1" title="' . sysTranslations::get(
                'redirectsOnlineTooltip'
            ) . '" class="action_icon ' . ($oRedirect->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oRedirect->redirectId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="Redirect_' . $oRedirect->redirectId . '_online_0" title="' . sysTranslations::get(
                'redirectsOnlineTooltip'
            ) . '" class="action_icon ' . ($oRedirect->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oRedirect->redirectId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . $oRedirect->pattern . '</td>';
        echo '<td>' . $oRedirect->newUrl . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('redirectsEditTooltip') . '" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/bewerken/' . $oRedirect->redirectId . '&type=' . $oRedirect->type . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get(
                'redirectsDeleteTooltip'
            ) . '" onclick="return confirmChoice(\'' . $oRedirect->pattern . ' -> ' . $oRedirect->newUrl . '\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oRedirect->redirectId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aAllRedirects)) {
        echo '<tr><td colspan="4"><i>' . sysTranslations::get('redirectsNoRedirects') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>
<div class="contentColumn">
    <table class="sorted withActionIcons" style="float: left; width: 100%;">
        <thead>
        <tr class="topRow">
            <td colspan="4"><h2><?= sysTranslations::get('redirectsRegularExpressions') ?></h2>
                <div class="right">
                    <a class="addBtn textLeft" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen?type=2" title="<?= sysTranslations::get('redirectsAdd') ?>"
                       alt="<?= sysTranslations::get('redirectsAdd') ?>"><?= sysTranslations::get('redirectsAdd') ?></a><br/>
                </div>
            </td>
        </tr>
        <tr>
            <th class="{sorter:false} nonSorted onlineOffline"></th>
            <th><?= sysTranslations::get('redirectsPattern') ?></th>
            <th><?= sysTranslations::get('redirectsNewUrl') ?></th>
            <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
        </tr>
        </thead>
        <tbody id="tableContents">
        <?php

        foreach ($aAllExpressionRedirects AS $oRedirect) {
            echo '<tr data-id="' . $oRedirect->redirectId . '" class="movable">';
            echo '<td>';
            # online offline button
            echo '<a id="Redirect_' . $oRedirect->redirectId . '_online_1" title="' . sysTranslations::get(
                    'redirectsOnlineTooltip'
                ) . '" class="action_icon ' . ($oRedirect->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oRedirect->redirectId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
            echo '<a id="Redirect_' . $oRedirect->redirectId . '_online_0" title="' . sysTranslations::get(
                    'redirectsOnlineTooltip'
                ) . '" class="action_icon ' . ($oRedirect->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oRedirect->redirectId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
            echo '</td>';
            echo '<td>' . $oRedirect->pattern . '</td>';
            echo '<td>' . $oRedirect->newUrl . '</td>';
            echo '<td>';
            echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('redirectsEditTooltip') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oRedirect->redirectId . '"></a>';
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get(
                    'redirectsDeleteTooltip'
                ) . '" onclick="return confirmChoice(\'' . $oRedirect->pattern . ' -> ' . $oRedirect->newUrl . '\');" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oRedirect->redirectId . '?'. CSRFSynchronizerToken::query() .'"></a>';
            echo '</td>';
            echo '</tr>';
        }
        if (empty($aAllExpressionRedirects)) {
            echo '<tr><td colspan="4"><i>' . sysTranslations::get('redirectsNoRedirects') . '</i></td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<?php

# create necessary javascript
$sController            = http_get('controller');
$sOnlineMessage         = sysTranslations::get('redirectsOnlineMessage');
$sOfflineMessage        = sysTranslations::get('redirectsOfflineMessage');
$sNothingChangesMessage = sysTranslations::get('redirectsNothingChangedMessage');
$sBottomJavascript      = <<<EOT
<script type="text/javascript">
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            data: 'ajax=1',
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $("#Redirect_"+dataObj.redirectId+"_online_0").hide(); // hide offline button
                    $("#Redirect_"+dataObj.redirectId+"_online_1").hide(); // hide online button
                    $("#Redirect_"+dataObj.redirectId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$sOfflineMessage");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$sOnlineMessage");                            
                } else {
                    showStatusUpdate("$sNothingChangesMessage");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>