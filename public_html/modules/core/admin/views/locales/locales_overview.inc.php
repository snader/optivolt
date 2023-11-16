<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="6"><h2><?= sysTranslations::get('locales_all_locales') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('locales_add') ?>"
                   alt="<?= sysTranslations::get('locales_add') ?>"><?= sysTranslations::get('locales_add') ?></a><br/>
                <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen" title="<?= sysTranslations::get('global_change_order') ?>"
                   alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get('global_change_order') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline">&nbsp;</th>
        <th><?= sysTranslations::get('locales_locale') ?></th>
        <th><?= sysTranslations::get('locales_languageId') ?></th>
        <th><?= sysTranslations::get('locales_countryId') ?></th>
        <th><?= sysTranslations::get('locales_URLFormat') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aLocales as $oLocale) {
        echo '<tr>';
        echo '<td>';
        # online offline button
        echo '<a id="locale_' . $oLocale->localeId . '_online_1" title="' . sysTranslations::get(
                'locales_set_offline_tooltip'
            ) . '" class="action_icon ' . ($oLocale->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oLocale->localeId . '/?online=0"></a>';
        echo '<a id="locale_' . $oLocale->localeId . '_online_0" title="' . sysTranslations::get(
                'locales_set_online_tooltip'
            ) . '" class="action_icon ' . ($oLocale->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oLocale->localeId . '/?online=1"></a>';
        echo '</td>';
        echo '<td>' . _e($oLocale->getLocale()) . '</td>';
        echo '<td>' . _e($oLocale->getLanguage()
                ->getTranslations()->name) . '</td>';
        echo '<td>' . _e($oLocale->getCountry()
                ->getTranslations()->name) . '</td>';
        echo '<td>' . _e($oLocale->getURLFormat()) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('locales_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oLocale->localeId . '"></a>';

        if ($oLocale->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('locales_delete') . '" onclick="return confirmChoice(\'' . _e($oLocale->getLocale()) . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oLocale->localeId . '?' . CSRFSynchronizerToken::query() . '"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="' . sysTranslations::get('locales_not_deletable') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aLocales)) {
        echo '<tr><td colspan="6"><i>' . sysTranslations::get('locales_no_locales') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>
<?php

# create necessary javascript
$sLocaleOnlineMsg     = sysTranslations::get('locales_online');
$sLocaleOfflineMsg    = sysTranslations::get('locales_offline');
$sLocaleNotChangedMsg = sysTranslations::get('locales_not_changed');
$sController          = http_get('controller');
$sBottomJavascript    = <<<EOT
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
                    $("#locale_"+dataObj.localeId+"_online_0").hide(); // hide offline button
                    $("#locale_"+dataObj.localeId+"_online_1").hide(); // hide online button
                    $("#locale_"+dataObj.localeId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)
                        showStatusUpdate("$sLocaleOfflineMsg");
                    if(dataObj.online == 1)
                        showStatusUpdate("$sLocaleOnlineMsg");
                } else {
                    showStatusUpdate("$sLocaleNotChangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
