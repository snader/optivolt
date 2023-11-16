<?php

/* Models and managers used by the UserAccessGroupManager model */
require_once 'UserAccessGroup.class.php';

class UserAccessGroupManager
{

    /**
     * get a userAccessGroup by userAccessGroupId
     *
     * @param int $iUserAccessGroupId
     *
     * @return UserAccessGroup
     */
    public static function getUserAccessGroupById($iUserAccessGroupId)
    {
        $sQuery = ' SELECT
                        `uag`.*
                    FROM
                        `user_access_groups` AS `uag`
                    WHERE
                        `uag`.`userAccessGroupId` = ' . db_int($iUserAccessGroupId) . '
                ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'UserAccessGroup');
    }

    /**
     * get a userAccessGroup by name
     *
     * @param string $sName
     *
     * @return UserAccessGroup
     */
    public static function getUserAccessGroupByName($sName)
    {
        $sQuery = ' SELECT
                        `uag`.*
                    FROM
                        `user_access_groups` AS `uag`
                    WHERE
                        `uag`.`name` = ' . db_str($sName) . '
                ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'UserAccessGroup');
    }

    /**
     * return userAccessGroups filtered by a few options
     * $global User $oCurrentUser
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Module
     */
    public static function getUserAccessGroupsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`uag`.`displayName`' => 'ASC'])
    {
        $oCurrentUser = UserManager::getCurrentUser();
        $sFrom        = '';
        $sWhere       = '';

        // only admin can see admin
        if (!$oCurrentUser->isSuperAdmin()) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`uag`.`name` != ' . db_str(UserAccessGroup::userAccessGroup_administrators) . ' OR `uag`.`name` IS NULL)';
        }

        if (!$oCurrentUser->isSuperAdmin() && !$oCurrentUser->isClientAdmin()) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`uag`.`name` != ' . db_str(UserAccessGroup::userAccessGroup_administrators_client) . ' OR `uag`.`name` IS NULL)';
        }

        if (!empty($aFilter['userAccessGroupId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`uag`.`userAccessGroupId` = ' . db_int($aFilter['userAccessGroupId']);
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
                        `uag`.*
                    FROM
                        `user_access_groups` AS `uag`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb               = DBConnections::get();
        $aUserAccessGroups = $oDb->query($sQuery, QRY_OBJECT, 'UserAccessGroup');
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aUserAccessGroups;
    }

    /**
     * save UserAccessGroup object
     *
     * @global User           $oCurrentUser
     *
     * @param UserAccessGroup $oUserAccessGroup
     */
    public static function saveUserAccessGroup(UserAccessGroup $oUserAccessGroup)
    {
        $oCurrentUser = UserManager::getCurrentUser();
        $oDb          = DBConnections::get();
        /* new userAccessGroup */
        if (!$oUserAccessGroup->userAccessGroupId) {
            $oUserAccessGroup->createdBy = $oCurrentUser->name;
            $sQuery                      = ' INSERT INTO
                            `user_access_groups`
                        (
                            `displayName`,
                            `name`,
                            `deletable`,
                            `editable`,
                            `created`,
                            `createdBy`
                        )
                        VALUES
                        (
                            ' . db_str($oUserAccessGroup->displayName) . ',
                            ' . db_str($oUserAccessGroup->name) . ',
                            ' . db_int($oUserAccessGroup->getDeletable()) . ',
                            ' . db_int($oUserAccessGroup->getEditable()) . ',
                            NOW(),
                            ' . db_str($oUserAccessGroup->createdBy) . '
                        )
                        ;';

            $oDb->query($sQuery, QRY_NORESULT);

            /* get new userAccessGroupId */
            $oUserAccessGroup->userAccessGroupId = $oDb->insert_id;
        } else {
            $oUserAccessGroup->modifiedBy = $oCurrentUser->name;
            $sQuery                       = ' UPDATE
                            `user_access_groups`
                        SET
                            `displayName` = ' . db_str($oUserAccessGroup->displayName) . ',
                            `name` = ' . db_str($oUserAccessGroup->name) . ',
                            `deletable` = ' . db_int($oUserAccessGroup->getDeletable()) . ',
                            `editable` = ' . db_int($oUserAccessGroup->getEditable()) . ',
                            `modified` = NOW(),
                            `modifiedBy` = ' . db_str($oUserAccessGroup->modifiedBy) . '
                        WHERE
                            `userAccessGroupId` = ' . db_int($oUserAccessGroup->userAccessGroupId) . '
                        ;';
            $oDb->query($sQuery, QRY_NORESULT);
        }

        /* save module action relations */
        self::saveModuleActions($oUserAccessGroup);
    }

    /**
     * Save the moduleAction relations of a userAccessGroup
     *
     * @global $oCurrentUser
     *
     * @param ModuleAction object
     */
    private static function saveModuleActions(UserAccessGroup $oUserAccessGroup)
    {
        $oCurrentUser = UserManager::getCurrentUser();
        $oDb          = DBConnections::get();

        # define the NOT IN and VALUES part of the DELETE and INSERT query
        $sDeleteNotIn  = '';
        $sInsertValues = '';
        foreach ($oUserAccessGroup->getModuleActions() as $oModuleAction) {
            $oModuleAction->createdBy = $oCurrentUser->name;
            $sDeleteNotIn             .= ($sDeleteNotIn == '' ? '' : ',') . db_int($oModuleAction->moduleActionId);
            $sInsertValues            .= ($sInsertValues == '' ? '' : ', ') . '(' . db_int($oUserAccessGroup->userAccessGroupId) . ', ' . db_int($oModuleAction->moduleActionId) . ', NOW(), ' . db_str($oModuleAction->createdBy) . ')';
        }

        # delete the old objects
        $sQuery = ' DELETE FROM
                        `user_access_groups_module_actions`
                    WHERE
                        `userAccessGroupId` = ' . db_int($oUserAccessGroup->userAccessGroupId) . '
                        ' . ($sDeleteNotIn != '' ? 'AND `moduleActionId` NOT IN (' . $sDeleteNotIn . ')' : '') . '
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);

        if (!empty($sInsertValues)) {
            # save the objects
            $sQuery = ' INSERT IGNORE INTO `user_access_groups_module_actions`(
                        `userAccessGroupId`,
                        `moduleActionId`,
                        `created`,
                        `createdBy`
                    )
                    VALUES ' . $sInsertValues . '
                    ;';

            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * delete userAccessGroup
     *
     * @param UserAccessGroup $oUserAccessGroup
     *
     * @return boolean
     */
    public static function deleteUserAccessGroup(UserAccessGroup $oUserAccessGroup)
    {
        if ($oUserAccessGroup->isDeletable()) {
            $oDb    = DBConnections::get();
            $sQuery = ' DELETE FROM
                        `user_access_groups`
                    WHERE
                        `userAccessGroupId` = ' . db_int($oUserAccessGroup->userAccessGroupId) . '
                    ;';
            $oDb->query($sQuery, QRY_NORESULT);

            return $oDb->affected_rows;
        } else {
            return false;
        }
    }

}
