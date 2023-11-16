<?php

class ModuleManager
{

    /**
     * get module by moduleId
     *
     * @param int $iModuleId
     *
     * @return Module
     */
    public static function getModuleById($iModuleId)
    {
        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `modules` AS `m`
                    WHERE
                        `m`.`moduleId` = ' . db_int($iModuleId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Module");
    }

    /**
     * get module by name
     *
     * @param string $sName
     *
     * @return Module
     */
    public static function getModuleByName($sName)
    {
        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `modules` AS `m`
                    WHERE
                        `m`.`name` = ' . db_str($sName) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Module");
    }

    /**
     * save Module object
     *
     * @param Module $oModule
     */
    public static function saveModule(Module $oModule)
    {

        $sQuery = ' INSERT INTO `modules` (
                        `moduleId`,
                        `name`,
                        `collapseName`,
                        `linkName`,
                        `icon`,
                        `showInMenu`,
                        `parentModuleId`,
                        `order`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oModule->moduleId) . ',
                        ' . db_str($oModule->name) . ',
                        ' . db_str($oModule->collapseName) . ',
                        ' . db_str($oModule->linkName) . ',
                        ' . db_str($oModule->icon) . ',
                        ' . db_str($oModule->showInMenu) . ',
                        ' . db_int($oModule->parentModuleId) . ',
                        ' . db_int($oModule->order) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `name`=VALUES(`name`),
                        `collapseName`=VALUES(`collapseName`),
                        `linkName`=VALUES(`linkName`),
                        `icon`=VALUES(`icon`),
                        `showInMenu`=VALUES(`showInMenu`),
                        `parentModuleId`=VALUES(`parentModuleId`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oModule->moduleId === null) {
            $oModule->moduleId = $oDb->insert_id;
        }
    }

    /**
     * delete module
     *
     * @param Module $oModule
     *
     * @return Boolean
     *
     */
    public static function deleteModule(Module $oModule)
    {
        $oDb = DBConnections::get();

        $sQuery = 'DELETE FROM `modules` WHERE `moduleId` = ' . db_int($oModule->moduleId) . ';';
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * return modules filtered by a few options
     *
     * @global      $oCurrentUser
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Module
     */
    public static function getModulesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`m`.`order`' => 'ASC', '`m`.`moduleId`' => 'ASC'])
    {
        $oCurrentUser = UserManager::getCurrentUser();
        $sFrom        = '';
        $sWhere       = '';

        if (empty($aFilter['showAll'])) {
            $sFrom  .= 'JOIN `module_actions` AS `ma` ON `ma`.`moduleId` = `m`.`moduleId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`m`.`active` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`m`.`showInMenu` = 1';

            // also check on userAccessGroupId
            $aFilter['userAccessGroupId'] = $oCurrentUser->userAccessGroupId;
        } elseif (isset($aFilter['userAccessGroupId'])) {
            $sFrom .= 'JOIN `module_actions` AS `ma` ON `ma`.`moduleId` = `m`.`moduleId`';
        }

        // get modules with parenModuleId
        if (isset($aFilter['parentModuleId'])) {
            if ($aFilter['parentModuleId'] === -1) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`m`.`parentModuleId` IS NULL';
            } else {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`m`.`parentModuleId` = ' . db_int($aFilter['parentModuleId']);
            }
        }

        // get modules with showInMenu
        if (isset($aFilter['showInMenu'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`m`.`showInMenu` = ' . db_int($aFilter['showInMenu']);
        }

        // get modules with userAccessGroupId
        if (isset($aFilter['userAccessGroupId'])) {
            $sFrom  .= 'JOIN `user_access_groups_module_actions` AS `uagma` ON `uagma`.`moduleActionId` = `ma`.`moduleActionId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`uagma`.`userAccessGroupId` = ' . db_int($aFilter['userAccessGroupId']);
        }

        // get modules with active
        if (isset($aFilter['active'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`m`.`active` = ' . db_int($aFilter['active']);
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
            $sLimit .= $iLimit;
        }
        if ($sLimit !== '') {
            $sLimit = (is_numeric($iStart) ? $iStart . ',' : '0,') . $sLimit;
        }
        $sLimit = ($sLimit !== '' ? 'LIMIT ' : '') . $sLimit;

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `m`.*
                    FROM
                        `modules` AS `m`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `m`.`moduleId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb      = DBConnections::get();
        $aModules = $oDb->query($sQuery, QRY_OBJECT, "Module");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aModules;
    }

    /**
     * check if module is active
     *
     * @param type $sName
     *
     * @return Module
     */
    public static function isActive($sName)
    {
        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `modules` AS `m`
                    WHERE
                        `m`.`name` = ' . db_str($sName) . '
                    AND
                        `m`.`active` = 1
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Module") != null;
    }

    /**
     * update active  by module names
     *
     * @param array $aNames
     * @param type  $bActive
     */
    public static function setActiveByNames(array $aNames = [], $bActive)
    {

        $sNames = '';
        foreach ($aNames AS $sName) {
            $sNames .= ($sNames != '' ? ',' : '') . db_str($sName);
        }

        if (!empty($sNames)) {
            $sQuery = ' UPDATE
                        `modules`
                    SET
                        `active` = ' . db_int($bActive) . '
                    WHERE
                        `name` IN (' . $sNames . ')
                    ;';
            $oDb    = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

}

?>