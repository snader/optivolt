<?php

class AccessLogManager
{

    const max_login_attempts_account_lock = 5; // max failed attempts before blocking the account for login_blocked_time minutes
    const max_login_attempts_ip_block     = 15; // max failed attempts to login in a row
    const account_locked_time             = 20; // amount of minutes an account is blocked

    /**
     * get a AccessLog by Id
     *
     * @param int $iAccessLogId
     *
     * @return AccessLog
     */

    public static function getAccessLogById($iAccessLogId)
    {
        $sQuery = ' SELECT
                        `al`.*
                    FROM
                        `access_logs` AS `al`
                    WHERE
                        `al`.`accessLogId` = ' . db_int($iAccessLogId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "AccessLog");
    }

    /**
     * get a AccessLog by IP
     *
     * @param string $sIp
     *
     * @return AccessLog
     */
    public static function getAccessLogByIp($sIp)
    {
        $sQuery = ' SELECT
                        `al`.*
                    FROM
                        `access_logs` AS `al`
                    WHERE
                        `al`.`identifier` = ' . db_str(hashPasswordForDb($sIp . ACCESS_LOGS_SALT)) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "AccessLog");
    }

    /**
     * get AccessLog objects by filter
     *
     * @param array $aFilter
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array AccessLog
     */
    public static function getAccessLogsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['IFNULL(`al`.`modified`, `al`.`created`)' => 'DESC'])
    {
        $sWhere = '';
        $sFrom  = '';

        # get newsitems with that changed last hour
        if (!empty($aFilter['ip'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`al`.`identifier` LIKE ' . db_str('%' . $aFilter['ip'] . '%');
        }

        # handle order by
        $sOrderBy = '';
        if (count($aOrderBy) > 0) {
            foreach ($aOrderBy AS $sColumn => $sOrder) {
                $sOrderBy .= ($sOrderBy !== '' ? ',' : '') . '' . $sColumn . '' . ' ' . $sOrder;
            }
        }
        $sOrderBy = ($sOrderBy !== '' ? 'ORDER BY ' : '') . $sOrderBy;

        # handle start,limit
        $sLimit = '';
        if (is_numeric($iLimit)) {
            $sLimit .= db_int($iLimit);
        }
        if ($sLimit !== '') {
            $sLimit = (is_numeric($iStart) ? db_int($iStart) . ',' : '0,') . $sLimit;
        }
        $sLimit = ($sLimit !== '' ? 'LIMIT ' : '') . $sLimit;

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `al`.*
                    FROM
                        `access_logs` AS `al`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb = DBConnections::get();

        $aAccessLogs = $oDb->query($sQuery, QRY_OBJECT, "AccessLog");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aAccessLogs;
    }

    /**
     * save a AccessLog
     *
     * @param AccessLog $oAccessLog
     *
     * @return int
     */
    public static function saveAccessLog(AccessLog $oAccessLog)
    {

        // update useragent and server info
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $oAccessLog->userAgent  = $_SERVER['HTTP_USER_AGENT'];
            $oAccessLog->serverInfo = serialize($_SERVER);
        }

        // we are using INSERT IGNORE INTO instead of ON DUPLICATE KEY UPDATE to reduce database load
        // when multiple calls were fired, the database could be too slow to first detect if the instance had an id, so errors occurred
        if (empty($oAccessLog->accessLogId)) {
            $sQuery = ' INSERT IGNORE INTO `access_logs`(
                            `identifier`,
                            `blocked`,
                            `reason`,
                            `loginFails`,
                            `lastLoginFail`,
                            `extraInfo`,
                            `userAgent`,
                            `serverInfo`,
                            `created`
                        )
                        VALUES (
                            ' . db_str(hashPasswordForDb($oAccessLog->ip . ACCESS_LOGS_SALT)) . ',
                            ' . db_date($oAccessLog->blocked) . ',
                            ' . db_str($oAccessLog->reason) . ',
                            ' . db_int($oAccessLog->loginFails) . ',
                            ' . db_date($oAccessLog->lastLoginFail) . ',
                            ' . db_str($oAccessLog->extraInfo) . ',
                            ' . db_str($oAccessLog->userAgent) . ',
                            ' . db_str($oAccessLog->serverInfo) . ',
                            NOW()
                        );';
        } else {
            $sQuery = ' UPDATE
                            `access_logs`
                        SET
                            `blocked` = ' . db_date($oAccessLog->blocked) . ',
                            `reason` = ' . db_str($oAccessLog->reason) . ',
                            `loginFails` = ' . db_int($oAccessLog->loginFails) . ',
                            `lastLoginFail` = ' . db_date($oAccessLog->lastLoginFail) . ',
                            `extraInfo` = ' . db_str($oAccessLog->extraInfo) . ',
                            `userAgent` = ' . db_str($oAccessLog->userAgent) . ',
                            `serverInfo` = ' . db_str($oAccessLog->serverInfo) . ',
                            `modified` = NOW()
                        WHERE
                            `accessLogId` = ' . db_int($oAccessLog->accessLogId) . '
                        LIMIT 1
                        ;';
        }

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if (!$oAccessLog->accessLogId) {
            $oAccessLog->accessLogId = $oDb->insert_id;
        }

        return $oDb->affected_rows;
    }

    /**
     * generate access log entry
     *
     * @return AccessLog
     */
    public static function generateAccessLog()
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $sIP = $_SERVER['REMOTE_ADDR'];
        } else {
            $sIP = '-';
        }

        if (!($oAccessLog = self::getAccessLogByIp($sIP))) {
            $oAccessLog = new AccessLog(['ip' => $sIP]);
        }

        self::saveAccessLog($oAccessLog); // to save new one or update server info

        return $oAccessLog;
    }

    /**
     * reset login attempts
     *
     * @param AccessLog $oAccessLog
     */
    public static function resetLoginAttempts(AccessLog $oAccessLog)
    {
        $oAccessLog->lastLoginFail = null;
        $oAccessLog->loginFails    = 0;
        $oAccessLog->blocked       = null;
        $oAccessLog->reason        = null;
        $oAccessLog->extraInfo     = null;
        self::saveAccessLog($oAccessLog);
    }

    /**
     * add login attempts
     *
     * @param AccessLog $oAccessLog
     * @param string    $sExtraInfo
     *
     * @return boolean return if something should be locked
     */
    public static function addLoginAttempt(AccessLog $oAccessLog, $sExtraInfo = null)
    {
        $oAccessLog->lastLoginFail = strftime(Date::FORMAT_DB_F);
        $oAccessLog->loginFails    += 1;

        $bDoLock = false;

        // IP block, to many failed tries
        if ($oAccessLog->loginFails >= self::max_login_attempts_ip_block) {
            $oAccessLog->blocked   = strftime(Date::FORMAT_DB_F);
            $oAccessLog->reason    = 'To many failed login attempts';
            $oAccessLog->extraInfo = $sExtraInfo;

            ob_start();
            include getAdminView('accessLogs/accessLog_details');
            $sMail = ob_get_clean();

            // IP blocked, send email to default error email
            MailManager::sendMail(DEFAULT_ERROR_EMAIL, 'IP blocked after ' . $oAccessLog->loginFails . ' failed attempts', $sMail . '<p><a href="' . CLIENT_HTTP_URL . ADMIN_FOLDER . '/access-management">Go to admin</a></p>');

            $bDoLock = true;
        } elseif ($oAccessLog->loginFails != 0 && $oAccessLog->loginFails % self::max_login_attempts_account_lock == 0) {
            // block every max_login_attempts_account_lock times
            $bDoLock = true;
        }

        self::saveAccessLog($oAccessLog);

        // return if something needs to be blocked
        return $bDoLock;
    }

}

?>