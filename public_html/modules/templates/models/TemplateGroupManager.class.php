<?php

class TemplateGroupManager
{

    /**
     * get templateGroup by templateGroupId
     *
     * @param int $iTemplateGroupId
     *
     * @return TemplateGroup
     */
    public static function getTemplateGroupById($iTemplateGroupId)
    {
        $sQuery = ' SELECT 
                        `tg`.*
                    FROM
                        `template_groups` AS `tg`
                    WHERE
                        `tg`.`templateGroupId` = ' . db_int($iTemplateGroupId) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "TemplateGroup");
    }

    /**
     * get templateGroup by name
     *
     * @param int $sName
     *
     * @return TemplateGroup
     */
    public static function getTemplateGroupByName($sName)
    {
        $sQuery = ' SELECT 
                        `tg`.*
                    FROM
                        `template_groups` AS `tg`
                    WHERE
                        `tg`.`name` = ' . db_str($sName) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "TemplateGroup");
    }

    /**
     * get templateGroup by group name
     *
     * @param int $sGroupName
     *
     * @return TemplateGroup
     */
    public static function getTemplateGroupByGroupName($sGroupName)
    {
        $sQuery = ' SELECT 
                        `tg`.*
                    FROM
                        `template_groups` AS `tg`
                    WHERE
                        `tg`.`templateGroupName` = ' . db_str($sGroupName) . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "TemplateGroup");
    }

    /**
     * return templateGroups filtered by a few options
     *
     * @param array $aFilter    filter properties (description)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array TemplateGroup objects
     */
    public static function getTemplateGroupsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`tg`.`templateGroupName`' => 'ASC'])
    {

        $sWhere = '';
        $sFrom  = '';

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

        $sQuery = ' SELECT SQL_CALC_FOUND_ROWS
                        `tg`.*
                    FROM
                        `template_groups` AS `tg`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb             = DBConnections::get();
        $aTemplateGroups = $oDb->query($sQuery, QRY_OBJECT, "TemplateGroup");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aTemplateGroups;
    }

    /**
     * save TemplateGroup object
     *
     * @param TemplateGroup $oTemplateGroup
     */
    public static function saveTemplateGroup(TemplateGroup $oTemplateGroup)
    {

        $sQuery = ' INSERT INTO `template_groups` (
                        `templateGroupId`,
                        `name`,
                        `templateGroupName`,
                        `templateVariables`
                    ) 
                    VALUES (
                        ' . db_int($oTemplateGroup->templateGroupId) . ',
                        ' . db_str($oTemplateGroup->name) . ',
                        ' . db_str($oTemplateGroup->templateGroupName) . ',
                        ' . db_str($oTemplateGroup->templateVariables) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `name`=VALUES(`name`),
                        `templateGroupName`=VALUES(`templateGroupName`),
                        `templateVariables`=VALUES(`templateVariables`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oTemplateGroup->templateGroupId === null) {
            $oTemplateGroup->templateGroupId = $oDb->insert_id;
        }
    }

    /**
     * delete templateGroup
     *
     * @param TemplateGroup $oTemplateGroup
     *
     * @return Boolean
     */
    public static function deleteTemplateGroup(TemplateGroup $oTemplateGroup)
    {
        if ($oTemplateGroup->isDeletable()) {
            $sQuery = 'DELETE FROM `template_groups` WHERE `templateGroupId` = ' . db_int($oTemplateGroup->templateGroupId) . ';';
            $oDb    = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        } else {
            return false;
        }
    }

}

?>