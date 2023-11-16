<?php

class SystemReportManager
{

    /**
     * get the full SystemReport object by id
     *
     * @param int $iSystemId
     * @param int $iLocaleId
     *
     * @return System
     */
    public static function getSystemReportById($iSystemReportId)
    {
        $sQuery = ' SELECT
                        `sr`.*
                    FROM
                        `system_reports` AS `sr`
                    WHERE
                        `sr`.`systemReportId` = ' . db_int($iSystemReportId) . ' AND `sr`.`deleted` = ' . db_int(0) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "SystemReport");
    }

    /**
     *
     */
    public static function getSubSystemReports($iSystemReportId)
    {
        $sQuery = ' SELECT
                        `sr`.*
                    FROM
                        `system_reports` AS `sr`
                    WHERE
                        `sr`.`parentId` = ' . db_int($iSystemReportId) . ' AND `sr`.`deleted` = ' . db_int(0) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "SystemReport");
    }

    /**
     * save System object
     *
     * @param System $oSystem
     */
    public static function saveSystemReport(SystemReport $oSystemReport)
    {
        # save item
        $sQuery = ' INSERT INTO `system_reports` (
                        `systemReportId`,
                        `parentId`,
                        `systemId`,
                        `userId`,
                        `wideInfo`,
                        `columnA`,
                        `faseA`,
                        `faseB`,
                        `faseC`,
                        `faseD`,
                        `faseE`,
                        `faseF`,
                        `notice`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oSystemReport->systemReportId) . ',
                        ' . db_int($oSystemReport->parentId) . ',
                        ' . db_int($oSystemReport->systemId) . ',
                        ' . db_int($oSystemReport->userId) . ',
                        ' . db_str($oSystemReport->wideInfo) . ',
                        ' . db_str($oSystemReport->columnA) . ',
                        ' . db_deci($oSystemReport->faseA) . ',
                        ' . db_deci($oSystemReport->faseB) . ',
                        ' . db_deci($oSystemReport->faseC) . ',
                        ' . db_deci($oSystemReport->faseD) . ',
                        ' . db_deci($oSystemReport->faseE) . ',
                        ' . db_deci($oSystemReport->faseF) . ',
                        ' . db_str($oSystemReport->notice) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `systemId`=VALUES(`systemId`),
                        `parentId`=VALUES(`parentId`),
                        `userId`=VALUES(`userId`),
                        `wideInfo`=VALUES(`wideInfo`),
                        `columnA`=VALUES(`columnA`),
                        `faseA`=VALUES(`faseA`),
                        `faseB`=VALUES(`faseB`),
                        `faseC`=VALUES(`faseC`),
                        `faseD`=VALUES(`faseD`),
                        `faseE`=VALUES(`faseE`),
                        `faseF`=VALUES(`faseF`),
                        `notice`=VALUES(`notice`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oSystemReport->systemReportId === null) {
            $oSystemReport->systemReportId = $oDb->insert_id;
        }

        $oSystem = SystemManager::updateLastReportDate($oSystemReport->systemId, substr($oSystemReport->created, 0, 10));


    }

    /**
     * delete item and all media
     *
     * @param System $oSystem
     *
     * @return Boolean
     */
    public static function deleteSystemReport(SystemReport $oSystemReport)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oSystemReport->isDeletable()) {

            # get and delete images
            foreach ($oSystemReport->getImages('all') as $oImage) {
                //ImageManager::deleteImage($oImage);
            }

            $sQuery = "UPDATE `system_reports` SET `deleted` = 1 WHERE `systemReportId` = " . db_int($oSystemReport->systemReportId) . ";";
            //$sQuery = "DELETE FROM `system_reports` WHERE `systemReportId` = " . db_int($oSystemReport->systemReportId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }




    /**
     * return System reports filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array System
     */
    public static function getSystemReportsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`s`.`created`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
        `s`.`deleted` = 0
    ';

        # get by systemId
        if (isset($aFilter['systemId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`systemId` = ' . db_int($aFilter['systemId']);
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`parentId` IS NULL ';
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`notice` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `s`.`columnA` LIKE ' . db_str('%' . $aFilter['q'] . '%')
            . ' OR `s`.`faseA` LIKE ' . db_str('' . $aFilter['q'] . '')
            . ' OR `s`.`faseB` LIKE ' . db_str('' . $aFilter['q'] . '')
            . ' OR `s`.`faseC` LIKE ' . db_str('' . $aFilter['q'] . '') .
            ')';
        }

        # search for year
        if (!empty($aFilter['year'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`created` LIKE ' . db_str('' . $aFilter['year'] . '-%') . ')';
            $iLimit = 1;
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`s`.`modified`, `s`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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
                        `s`.*
                    FROM
                        `system_reports` AS `s`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aSystemReports = $oDb->query($sQuery, QRY_OBJECT, "SystemReport");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aSystemReports;
    }


    /**
     * save connection between a System and a file
     *
     * @param int $iSystemId
     * @param int $iMediaId
     */
    public static function saveSystemFileRelation($iSystemReportId, $iMediaId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `system_reports_files`
                    (
                        `systemReportId`,
                        `mediaId`
                    )
                    VALUES
                    (
                        ' . db_int($iSystemReportId) . ',
                        ' . db_int($iMediaId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get files for System by filter
     *
     * @param int   $iSystemId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array File
     */
    public static function getFilesByFilter($iSystemReportId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `m`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `m`.*,
                        `f`.*
                    FROM
                        `files` AS `f`
                    JOIN
                        `system_reports_files` AS `pf` USING (`mediaId`)
                    JOIN
                        `media` AS `m` USING (`mediaId`)
                    WHERE
                        `pf`.`systemReportId` = ' . db_int($iSystemReportId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `m`.`order` ASC, `m`.`mediaId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'File');
    }



    /**
     * save connection between a System and an image
     *
     * @param int $iSystemId
     * @param int $iImageId
     */
    public static function saveSystemImageRelation($iSystemReportId, $iImageId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `system_reports_images`
                    (
                        `systemReportId`,
                        `imageId`
                    )
                    VALUES
                    (
                        ' . db_int($iSystemReportId) . ',
                        ' . db_int($iImageId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get images for System by filter
     *
     * @param int   $iSystemId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Image
     */
    public static function getImagesByFilter($iSystemReportId, array $aFilter = [], $iLimit = null)
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `i`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `i`.*
                    FROM
                        `images` AS `i`
                    JOIN
                        `system_reports_images` AS `pi` USING (`imageId`)
                    WHERE
                        `pi`.`systemReportId` = ' . db_int($iSystemReportId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `i`.`order` ASC, `i`.`imageId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'Image');
    }



}