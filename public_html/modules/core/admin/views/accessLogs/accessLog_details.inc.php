<div style="width: 750px;">
    <table style="width: 100%;">
        <tr>
            <td colspan="2" style="white-space: nowrap;"><h1><?= sysTranslations::get('accessLog_details') ?>: `<?= $oAccessLog->ip ?>`</h1></td>
        </tr>
        <tr>
            <td style="padding-right: 20px;"><b><?= sysTranslations::get('accessLogs_blocked') ?></b></td>
            <td><?= $oAccessLog->blocked ? Date::strToDate($oAccessLog->blocked)
                    ->format('%d-%m-%Y %H:%M:%S') : '' ?></td>
        </tr>
        <tr>
            <td style="padding-right: 20px;"><b><?= sysTranslations::get('accessLogs_reason') ?></b></td>
            <td><?= $oAccessLog->reason ?></td>
        </tr>
        <tr>
            <td style="padding-right: 20px;"><b><?= sysTranslations::get('accessLogs_login_fail') ?></b></td>
            <td><?= $oAccessLog->loginFails ?></td>
        </tr>
        <tr>
            <td style="padding-right: 20px;"><b><?= sysTranslations::get('accessLogs_login_last_fail') ?></b></td>
            <td><?= $oAccessLog->lastLoginFail ? Date::strToDate($oAccessLog->lastLoginFail)
                    ->format('%d-%m-%Y %H:%M:%S') : '' ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr/>
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>Server Info</b></td>
        </tr>
        <tr>
            <td style="padding-right: 20px;"><b>User Agent</b></td>
            <td><?= $oAccessLog->userAgent ?></td>
        </tr>
        <tr>
            <td style="padding-right: 20px;"><b>Extra Info</b></td>
            <td><?= $oAccessLog->extraInfo ?></td>
        </tr>
        <?php

        if (is_array($oAccessLog->getServerInfo())) {
            foreach ($oAccessLog->getServerInfo() AS $sInputName => $sValue) {
                echo '<tr><td style="padding-right: 20px;"><b>' . $sInputName . '</b></td><td>' . $sValue . '</td></tr>';
            }
        }
        ?>
    </table>
</div>