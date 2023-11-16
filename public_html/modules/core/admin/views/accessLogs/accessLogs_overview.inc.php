<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend>Filter</legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 116px;"><label for="ip">IP</label></td>
                <td><input class="default" type="text" name="accessLogFilter[ip]" id="ip" value="<?= $aAccessLogFilter['ip'] ?>"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterAccessLogs" value="<?= sysTranslations::get('accessLogs_filter') ?>"/> <input type="submit" name="resetFilter" value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
            </tr>
        </table>
    </fieldset>
</form>
<table class="sorted" style="width: 100%;">
    <thead>
    <tr class="topRow">
        <td colspan="10"><h2><?= sysTranslations::get('accessLogs_all') ?> (<?= (count($aAccessLogs) != 0 ? ($iStart + 1) : $iStart) . '-' . ($iStart + count($aAccessLogs)) ?>/<?= $iFoundRows ?>)</h2></td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted" style="width: 30px;">&nbsp;</th>
        <th style="width: 100px;">IP</th>
        <th style="width: 100px;"><?= sysTranslations::get('accessLogs_blocked') ?></th>
        <th><?= sysTranslations::get('accessLogs_reason') ?></th>
        <th style="width: 60px;"><?= sysTranslations::get('accessLogs_login_fail') ?></th>
        <th style="width: 100px;"><?= sysTranslations::get('accessLogs_login_last_fail') ?></th>
        <th><?= sysTranslations::get('accessLogs_extra_info') ?></th>
        <th>User Agent</th>
        <th style="width: 100px;"><?= sysTranslations::get('accessLogs_modified') ?></th>
        <?php if (isDeveloper()) { ?>
            <th class="{sorter:false} nonSorted" style="width: 30px;"></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aAccessLogs AS $oAccessLog) {
        echo '<tr>';
        echo '<td>';
        # online offline button
        if ($oAccessLog->blocked) {
            echo '<a title="' . sysTranslations::get('accessLogs_grand_access') . '" class="action_icon offline_icon" href="#" onclick="return unlock(' . $oAccessLog->accessLogId . ', \'' . $oAccessLog->ip . '\');"></a>';
        } else {
            echo '<a title="' . sysTranslations::get('accessLogs_deny_access') . '" class="action_icon online_icon" href="#" onclick="return block(' . $oAccessLog->accessLogId . ', \'' . $oAccessLog->ip . '\');"></a>';
        }
        echo '</td>';
        echo '<td>' . _e($oAccessLog->ip) . '</td>';
        echo '<td>' . _e(
                $oAccessLog->blocked ? Date::strToDate($oAccessLog->blocked)
                    ->format('%d-%m-%Y %H:%M:%S') : ''
            ) . '</td>';
        echo '<td>' . _e($oAccessLog->reason) . '</td>';
        echo '<td>' . _e($oAccessLog->loginFails) . '</td>';
        echo '<td>' . _e($oAccessLog->lastLoginFail) . '</td>';
        echo '<td>' . _e($oAccessLog->extraInfo) . '</td>';
        echo '<td>' . _e($oAccessLog->userAgent) . '</td>';
        echo '<td>' . _e(
                Date::strToDate($oAccessLog->modified ? $oAccessLog->modified : $oAccessLog->created)
                    ->format('%d-%m-%Y %H:%M:%S')
            ) . '</td>';
        if (isDeveloper()) {
            echo '<td><a class="action_icon magnifying_glass_icon fancyBoxLink fancybox.ajax" href="' . getCurrentUrlPath() . '/ajax-getServerInfo/' . $oAccessLog->accessLogId . '"></a></td>';
        }
        echo '</tr>';
    }
    if (empty($aAccessLogs)) {
        echo '<tr><td colspan="10"><i>' . sysTranslations::get('accessLogs_no_results') . '</i></td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bottomRow">
        <td colspan="10">
            <form method="POST">
                <?= generatePaginationHTML($iPageCount, $iCurrPage) ?>
                <input type="hidden" name="setPerPage" value="1"/>
                <select name="perPage" onchange="$(this).closest('form').submit();">
                    <option value="<?= $iNrOfRecords ?>">alle</option>
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
<div style="display: none;">
    <div id="blockAccessForm" style="width: 500px;">
        <form method="POST">
            <input class="blocked_accessLogId" name="accessLogId" type="hidden" value=""/>
            <input name="action" type="hidden" value="blockAccess"/>
            <table class="withForm">
                <tr>
                    <td colspan="2">
                        <h2 style="margin-bottom: 5px;"><?= sysTranslations::get('accessLogs_deny_access_for') ?> `<span class="blocked_ip"></span>`</h2>
                    </td>
                </tr>
                <tr>
                    <td class="withLabel" style="width: 120px;"><label for="block_reason"><?= sysTranslations::get('accessLogs_deny_access_reason') ?></label></td>
                    <td>
                        <input id="block_reason" name="reason" style="width: 300px;" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td class="withLabel"><label for="block_extraInfo"><?= sysTranslations::get('accessLogs_extra_info') ?></label></td>
                    <td>
                        <input id="block_extraInfo" name="extraInfo" style="width: 300px;" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="<?= sysTranslations::get('accessLogs_save_access') ?>"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div id="unlockAccessForm" style="width: 500px;">
        <form method="POST">
            <input class="blocked_accessLogId" name="accessLogId" type="hidden" value=""/>
            <input name="action" type="hidden" value="unlockAccess"/>
            <table class="withForm">
                <tr>
                    <td colspan="2">
                        <h2 style="margin-bottom: 5px;"><?= sysTranslations::get('accessLogs_grand_access_for') ?> `<span class="blocked_ip"></span>`</h2>
                    </td>
                </tr>
                <tr>
                    <td class="withLabel" style="width: 120px;"><label for="block_reason"><?= sysTranslations::get('accessLogs_grand_access_reason') ?></label></td>
                    <td>
                        <input id="block_reason" name="reason" style="width: 300px;" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td class="withLabel"><label for="block_extraInfo"><?= sysTranslations::get('accessLogs_extra_info') ?></label></td>
                    <td>
                        <input id="block_extraInfo" name="extraInfo" style="width: 300px;" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="<?= sysTranslations::get('accessLogs_save_access') ?>"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
    function block(accessLogId, ip) {
        $('.blocked_accessLogId').val(accessLogId);
        $('.blocked_ip').html(ip);
        $.fancybox.open({href: '#blockAccessForm'});
        return false;
    }

    function unlock(accessLogId, ip) {
        $('.blocked_accessLogId').val(accessLogId);
        $('.blocked_ip').html(ip);
        $.fancybox.open({href: '#unlockAccessForm'});
        return false;
    }
</script>