<?php

/* Models and managers used by the ModuleActionManager model */
require_once 'ModuleAction.class.php';

class ModuleActionManager
{

    /**
     * get a moduleAction by moduleActionId
     *
     * @param int $iModuleActionId
     *
     * @return ModuleAction
     */
    public static function getModuleActionById($iModuleActionId)
    {
        $sQuery = ' SELECT
                        `ma`.*
                    FROM
                        `module_actions` AS `ma`
                    WHERE
                        `ma`.`moduleActionId` = ' . db_int($iModuleActionId) . '
                ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'ModuleAction');
    }

    /**
     * get a moduleAction by name
     *
     * @param string $sName
     *
     * @return ModuleAction
     */
    public static function getModuleActionByName($sName)
    {
        $sQuery = ' SELECT
                        `ma`.*
                    FROM
                        `module_actions` AS `ma`
                    WHERE
                        `ma`.`name` = ' . db_str($sName) . '
                ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'ModuleAction');
    }

    /**
     * return moduleActions filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Module
     */
    public static function getModuleActionsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`ma`.`displayName`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        $sFrom .= 'JOIN `modules` AS `m` ON `m`.`moduleId` = `ma`.`moduleId`';

        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`m`.`active` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`m`.`showInMenu` = 1';
        }

        if (isset($aFilter['moduleId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`ma`.`moduleId` = ' . db_int($aFilter['moduleId']);
        }

        // get module actions for a user group
        if (isset($aFilter['userAccessGroupId'])) {
            $sFrom  .= 'JOIN `user_access_groups_module_actions` AS `uagma` ON `uagma`.`moduleActionId` = `ma`.`moduleActionId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`uagma`.`userAccessGroupId` = ' . db_int($aFilter['userAccessGroupId']);
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
                        `ma`.*
                    FROM
                        `module_actions` AS `ma`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `ma`.`moduleActionId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb            = DBConnections::get();
        $aModuleActions = $oDb->query($sQuery, QRY_OBJECT, 'ModuleAction');
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aModuleActions;
    }

    /**
     * save ModuleAction object
     *
     * @global User        $oCurrentUser
     *
     * @param ModuleAction $oModuleAction
     */
    public static function saveModuleAction(ModuleAction $oModuleAction)
    {
        $oCurrentUser = UserManager::getCurrentUser();
        $oDb          = DBConnections::get();
        /* new moduleAction */
        if (!$oModuleAction->moduleActionId) {
            $oModuleAction->createdBy = $oCurrentUser->name;
            $sQuery                   = ' INSERT INTO
                            `module_actions`
                        (
                            `moduleId`,
                            `displayName`,
                            `name`,
                            `deletable`,
                            `editable`,
                            `created`,
                            `createdBy`
                        )
                        VALUES
                        (
                            ' . db_str($oModuleAction->moduleId) . ',
                            ' . db_str($oModuleAction->displayName) . ',
                            ' . db_str($oModuleAction->name) . ',
                            ' . db_int($oModuleAction->getDeletable()) . ',
                            ' . db_int($oModuleAction->getEditable()) . ',
                            NOW(),
                            ' . db_str($oModuleAction->createdBy) . '
                        )
                        ;';

            $oDb->query($sQuery, QRY_NORESULT);

            /* get new moduleActionId */
            $oModuleAction->moduleActionId = $oDb->insert_id;
        } else {
            $oModuleAction->modifiedBy = $oCurrentUser->name;
            $sQuery                    = ' UPDATE
                            `module_actions`
                        SET
                            `displayName` = ' . db_str($oModuleAction->displayName) . ',
                            `name` = ' . db_str($oModuleAction->name) . ',
                            `deletable` = ' . db_int($oModuleAction->getDeletable()) . ',
                            `editable` = ' . db_int($oModuleAction->getEditable()) . ',
                            `modified` = NOW(),
                            `modifiedBy` = ' . db_str($oModuleAction->modifiedBy) . '
                        WHERE
                            `moduleActionId` = ' . db_int($oModuleAction->moduleActionId) . '
                        ;';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * delete moduleAction
     *
     * @param ModuleAction $oModuleAction
     *
     * @return boolean
     */
    public static function deleteModuleAction(ModuleAction $oModuleAction)
    {
        if ($oModuleAction->isDeletable()) {
            $oDb    = DBConnections::get();
            $sQuery = ' DELETE FROM
                        `module_actions`
                    WHERE
                        `moduleActionId` = ' . db_int($oModuleAction->moduleActionId) . '
                    ;';
            $oDb->query($sQuery, QRY_NORESULT);

            return $oDb->affected_rows;
        } else {
            return false;
        }
    }

}
