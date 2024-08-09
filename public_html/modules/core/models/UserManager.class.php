<?php

class UserManager
{

    /**
     * Current user
     *
     * @var User
     */
    protected static $currentUser;

    /**
     * Retrieve the current user
     *
     * @return \User
     */
    public static function getCurrentUser()
    {
        if (!static::$currentUser) {
            static::$currentUser = http_session('oCurrentUser', null);
        }

        return static::$currentUser;
    }

    /**
     * get a user by userId
     *
     * @param int $iUserId
     *
     * @return User
     */
    public static function getUserById($iUserId)
    {
        $sQuery = ' SELECT
                        `u`.*
                    FROM
                        `users` AS `u`
                    WHERE `u`.`userId` = ' . db_int($iUserId) . '
                ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'User');
    }

    /**
     * update twoStepSecret
     *
     * @param int $iUserId
     */
    public static function updateUserGoogleAuthCode($iUserId, $sGoogleAuthCode)
    {
        $sQuery = ' UPDATE
                        `users`
                    SET
                        `twoStepSecret` = ' . db_str($sGoogleAuthCode) . '
                    WHERE
                        `userId` = ' . db_int($iUserId) . '
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update twoStepSecret_verified
     *
     * @param int $iUserId
     */
    public static function updateUserGoogleAuthVerified($iUserId, $iValue)
    {
        $sQuery = ' UPDATE
                        `users`
                    SET
                        `twoStepSecretVerified` = ' . db_str($iValue) . ',
                        `modified` = `modified`
                    WHERE
                        `userId` = ' . db_int($iUserId) . '
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * Checks if user exists and isn't blocked
     *
     * @param int $iUserId
     *
     * @return bool
     */
    public static function isUserAccessAllowed($iUserId)
    {
        $sQuery = ' SELECT
                        COUNT(*) as `iTotalUserResult`
                    FROM
                         `users` as `u`
                    WHERE
                        `u`.`userId` = ' . db_int($iUserId) . '
                    AND
                        `u`.`deactivation` = ' . db_bool(false) . '
                    ;';

        $oDb     = DBConnections::get();
        $oResult = $oDb->query($sQuery, QRY_UNIQUE_OBJECT);

        return ($oResult->iTotalUserResult > 0);
    }

    /**
     * Check login for 2fa
     *
     * @param $sUsername
     * @param $sPassword
     *
     * @return User
     */
    public static function checkLogin($sUsername, $sPassword)
    {
        $sQuery = ' SELECT
                        `u`.*
                    FROM
                        `users` as `u`
                    WHERE
                        `u`.`username` = ' . db_str($sUsername) . '
                    AND
                        `u`.`password` = ' . db_str(hashPasswordForDb($sPassword)) . '
                    ' . (!isDeveloper() ? 'AND (`u`.`locked` IS NULL OR `u`.`locked` <= ' . db_date(
                    Date::strToDate('now')
                        ->addMinutes(-1 * AccessLogManager::account_locked_time)
                        ->format(Date::FORMAT_DB_F)
                ) . ')' : '') . '
                    ;';

        $oDb   = DBConnections::get();
        $oUser = $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'User');

        if ($oUser) {
            return $oUser;
        }

        /* no user found return 0 */

        return null;
    }

    /**
     * get a user by username
     *
     * @param string $sUsername
     *
     * @return User
     */
    public static function getUserByUsername($sUsername)
    {
        $sQuery = ' SELECT
                        `u`.*
                    FROM
                        `users` AS `u`
                    WHERE `u`.`username` = ' . db_str($sUsername) . '
                ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'User');
    }

    /**
     * return users filtered by a few options
     *
     * @global User $oCurrentUser
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Module
     */
    public static function getUsersByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`u`.`name`' => 'ASC'])
    {
        $oCurrentUser = static::getCurrentUser();
        $sFrom        = '';
        $sWhere       = '';

        $sFrom .= 'JOIN `user_access_groups` AS `uag` ON `uag`.`userAccessGroupId` = `u`.`userAccessGroupId`';

        // only admin can see admin
        if (!$oCurrentUser->isSuperAdmin() && empty($_SESSION['fastLoginEnabled'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`uag`.`name` != ' . db_str(UserAccessGroup::userAccessGroup_administrators) . ' OR `uag`.`name` IS NULL)';
        }

        // only admin client can see admin client
        if (!$oCurrentUser->isSuperAdmin() && !$oCurrentUser->isClientAdmin() && empty($_SESSION['fastLoginEnabled'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`uag`.`name` != ' . db_str(UserAccessGroup::userAccessGroup_administrators_client) . ' OR `uag`.`name` IS NULL)';
        }

        if (!empty($aFilter['userAccessGroupId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`u`.`userAccessGroupId` = ' . db_str($aFilter['userAccessGroupId']);
        }

        # handle order by
        $sOrderBy = '';
        if (count($aOrderBy) > 0) {
            foreach ($aOrderBy AS $sColumn => $sOrder) {
                $sOrderBy .= ($sOrderBy !== '' ? ',' : '') . $sColumn . ' ' . $sOrder;
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
                        `u`.*
                    FROM
                        `users` AS `u`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `u`.`userId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb    = DBConnections::get();
        $aUsers = $oDb->query($sQuery, QRY_OBJECT, 'User');
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aUsers;
    }

    /**
     * get user by email and pass and set in session
     *
     * @param string $sUsername
     * @param string $sPassword
     *
     * @return mixed User/false
     */
    public static function login($sUsername, $sPassword)
    {
        $sQuery = ' SELECT
                        `u`.*
                    FROM
                        `users` as `u`
                    WHERE
                        `u`.`username` = ' . db_str($sUsername) . '
                    AND
                        `u`.`password` = ' . db_str(hashPasswordForDb($sPassword)) . '
                    ' . (!isDeveloper() ? 'AND (`u`.`locked` IS NULL OR `u`.`locked` <= ' . db_date(
                    Date::strToDate('now')
                        ->addMinutes(-1 * AccessLogManager::account_locked_time)
                        ->format(Date::FORMAT_DB_F)
                ) . ')' : '') . '
                    ;';

        $oDb   = DBConnections::get();
        $oUser = $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'User');

        if ($oUser) {
            self::loginUser($oUser);
            self::updateLastLogin($oUser->userId); //update last login date and time

            return true;
        }

        /* no user found return false */

        return false;
    }

    /**
     * login user by object
     *
     * @param User $oUser
     *
     * @return boolean
     */
    public static function loginByUser(User $oUser)
    {
        self::loginUser($oUser);
        self::updateLastLogin($oUser->userId); //update last login date and time

        return true;
    }

    /**
     * do actually login user in session and mask password
     *
     * @param User $oUser
     */
    private static function loginUser(User $oUser)
    {
        $oUser->maskPass(); // mask pass XXX for session
        sysTranslations::reset(); // reset system translations
        $_SESSION['oCurrentUser'] = $oUser; // set user in session
        CSRFSynchronizerToken::get(true); // force a new CSRF token
    }

    /**
     * update current user from database into session
     */
    public static function updateUserInSession(){
        $oCurrentUser = self::getCurrentUser();
        if($oCurrentUser){
            $oUser = self::getUserById($oCurrentUser->userId);
            self::loginUser($oUser);
        }
    }

    /**
     * Logout user
     *
     * @param string $sRedirectLocation (redirect location)
     */
    public static function logout($sRedirectLocation)
    {

        unset($_SESSION['oCurrentUser']);
        unset($_SESSION['fastLoginEnabled']);
        sysTranslations::reset(); // reset system translations
        CSRFSynchronizerToken::get(true); // force a new CSRF token
        http_redirect($sRedirectLocation); //go to redirect location
    }

    /**
     * save User object
     *
     * @param User $oUser
     */
    public static function saveUser(User $oUser)
    {

        $oDb = DBConnections::get();
        /* new user */
        if (!$oUser->userId) {
            $sQuery = ' INSERT INTO
                            `users`
                            (
                                `name`,
                                `username`,
                                `administrator`,
                                `accountmanager`,
                                `seo`,
                                `userAccessGroupId`,
                                `systemLanguageId`,
                                `twoStepEnabled`,
                                `created`,
                                `locked`,
                                `lockedReason`,
                                `password`
                            )
                            VALUES
                            (
                                ' . db_str($oUser->name) . ',
                                ' . db_str($oUser->username) . ',
                                ' . db_int($oUser->getAdministrator()) . ',
                                ' . db_int($oUser->getAccountmanager()) . ',
                                ' . db_int($oUser->getSeo()) . ',
                                ' . db_int($oUser->userAccessGroupId) . ',
                                ' . db_int($oUser->systemLanguageId) . ',
                                ' . db_int($oUser->twoStepEnabled) . ',
                                ' . 'NOW()' . ',
                                ' . db_date($oUser->locked) . ',
                                ' . db_str($oUser->lockedReason) . ',
                                ' . db_str(hashPasswordForDb($oUser->password)) . '
                            )
                        ;';

            $oDb->query($sQuery, QRY_NORESULT);

            /* get new userId */
            $oUser->userId = $oDb->insert_id;
        } else {
            /* Password needs a hash!! */
            $sQuery = ' UPDATE
                            `users`
                        SET
                            `name` = ' . db_str($oUser->name) . ',
                            `username` = ' . db_str($oUser->username) . ',
                            `administrator` = ' . db_int($oUser->getAdministrator()) . ',
                            `accountmanager` = ' . db_int($oUser->getAccountmanager()) . ',
                            `seo` = ' . db_int($oUser->getSeo()) . ',
                            `userAccessGroupId` = ' . db_int($oUser->userAccessGroupId) . ',
                            `systemLanguageId` = ' . db_int($oUser->systemLanguageId) . ',
                            `twoStepEnabled` = ' . db_int($oUser->twoStepEnabled) . ',
                            `password` = ' . db_str($oUser->password) . ',
                            `locked` = ' . db_date($oUser->locked) . ',
                            `lockedReason` = ' . db_str($oUser->lockedReason) . '
                        WHERE
                            `userId` = ' . db_int($oUser->userId) . '
                        ;';
            $oDb->query($sQuery, QRY_NORESULT);

        }

        $oCurrentUser = UserManager::getCurrentUser();
        if ($oCurrentUser->userId === $oUser->userId) {
            self::loginUser($oUser);
        }
    }

    /**
     * delete user
     *
     * @param User $oUser
     *
     * @return boolean
     */
    public static function deleteUser(User $oUser)
    {

        $oDb = DBConnections::get();

        $sQuery = 'DELETE FROM `users` WHERE `userId` = ' . db_int($oUser->userId) . ';';
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * get accountmanagers
     */
    public static function getAccountmanagers()
    {
        $sQuery = ' SELECT * FROM `users` WHERE `accountmanager` = 1';
        $oDb               = DBConnections::get();
        $aAccountmanagers = $oDb->query($sQuery, QRY_OBJECT, 'User');

        return $aAccountmanagers;
    }



    /**
     * update last login timestamp
     *
     * @param int $iUserId
     */
    private static function updateLastLogin($iUserId)
    {
        $sQuery = ' UPDATE
                        `users`
                    SET
                        `lastLogin` = NOW(),
                        `modified` = `modified`
                    WHERE
                        `userId` = ' . db_int($iUserId) . '
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * lock user by username
     *
     * @param string $sUsername
     * @param string $sReason
     */
    public static function lockUserByUsername($sUsername, $sReason)
    {
        $sQuery = ' UPDATE
                        `users`
                    SET
                        `locked` = NOW(),
                        `lockedReason` = ' . db_str($sReason) . '
                    WHERE
                        `username` = ' . db_str($sUsername) . '
                    AND
                        `locked` IS NULL
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * Reset 2fa
     *
     * @param int $iUserId
     */
    public static function reset2Fa($iUserId)
    {
        $sQuery = ' UPDATE
                        `users`
                    SET
                        `twoStepSecret` = NULL,
                        `twoStepSecretVerified` = ' . db_int(0) . '
                    WHERE
                        `userId` = ' . db_int($iUserId) . '
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * unlock user
     *
     * @param User   $oUser
     * @param string $sReason
     */
    public static function unlockUser(User $oUser, $sReason)
    {
        $sQuery = ' UPDATE
                        `users`
                    SET
                        `locked` = NULL,
                        `lockedReason` = ' . db_str($sReason) . '
                    WHERE
                        `userId` = ' . db_int($oUser->userId) . '
                    AND
                        `locked` IS NOT NULL
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * @param       $bDeactivation
     * @param \User $oUser
     * @param       $sLockedReason
     * @param       $sDeactivationDate
     *
     * @return bool
     */
    public static function updateDeactivationByUser($bDeactivation, User $oUser, $sLockedReason, $sDeactivationDate)
    {
        $sQuery = ' UPDATE
                            `users`
                        SET
                            `deactivation` = ' . db_int($bDeactivation) . ',
                            `lockedReason`  = ' . db_str($sLockedReason) . ',
                            `deactivationDate`  = ' . db_date($sDeactivationDate) . '
                        WHERE
                            `userId` = ' . db_int($oUser->userId) . '
                        ;';

                
        $oDb = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * Update User with the related imageId
     *
     * @param Int $iUserId
     * @param Int $iImageId
     */
    public static function saveUserImageRelation($iUserId, $iImageId)
    {
        $sQuery = ' UPDATE
                        `users`
                    SET
                        `imageId` = ' . db_int($iImageId) . '
                    WHERE
                          `userId` = ' . db_int($iUserId) . '
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

?>