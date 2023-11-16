<?php

// Models and managers used by this class
require_once 'Redirect.class.php';

class RedirectManager
{

    /**
     * get a Redirect by id
     *
     * @param int $iredirectId
     *
     * @return Redirect
     */
    public static function getRedirectById($iredirectId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `redirects`
                    WHERE
                        `redirectId` = ' . db_int($iredirectId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Redirect");
    }

    /**
     * get a Redirect by id
     *
     * @param int $iredirectId
     *
     * @return Redirect
     */
    public static function getRedirectByPatternAndType($sPattern, $iType)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `redirects`
                    WHERE
                        `pattern` = ' . db_str($sPattern) . '
                    AND 
                        `type` = ' . db_int($iType) . ' 
                    AND `online` = 1 
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Redirect");
    }

    public static function getRedirectByPattern($sPattern)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `redirects`
                    WHERE
                        `pattern` = ' . db_str($sPattern) . ' 
                    AND `online` = 1 
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Redirect");
    }

    public static function updateForImport($sNewUrl, $iredirectId)
    {
        $sQuery = ' UPDATE
                        `redirects`
                    SET
                        `newUrl` = ' . db_str($sNewUrl) . '
                    WHERE
                        `redirectId` = ' . db_int($iredirectId) . ' 
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    public static function getAllredirectsByType($iType)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `redirects`
                    WHERE
                        `type` = ' . db_int($iType) . ' 
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "Redirect");
    }

    /**
     * return redirect items filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Redirect
     */
    public static function getredirectsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`ri`.`redirectId`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        // default is not to show all items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`ri`.`online` = 1';
        }

        if (!empty($aFilter['type'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`ri`.`type` = ' . db_int($aFilter['type']);
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
                        `ri`.*
                    FROM
                        `redirects` AS `ri`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aredirects = $oDb->query($sQuery, QRY_OBJECT, "Redirect");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aredirects;
    }

    /**
     * save a Redirect
     *
     * @param Redirect $oRedirect
     */
    public static function saveRedirect(Redirect $oRedirect)
    {
        $oDb = DBConnections::get();
        /* new user */
        if (!$oRedirect->redirectId) {
            $sQuery = ' INSERT INTO `redirects`(
                        `redirectId`,
                        `type`,
                        `pattern`,
                        `newUrl`,
                        `online`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oRedirect->redirectId) . ',
                        ' . db_int($oRedirect->type) . ',
                        ' . db_str($oRedirect->pattern) . ',
                        ' . db_str($oRedirect->newUrl) . ',
                        ' . db_int($oRedirect->online) . ',
                        NOW()
                    )
                    ;';

            $oDb->query($sQuery, QRY_NORESULT);

            /* get new redirectId */
            if ($oRedirect->redirectId === null) {
                $oRedirect->redirectId = $oDb->insert_id;
            }

        } else {
            $sQuery = " UPDATE
                            `redirects`
                        SET
                            `type` = " . db_int($oRedirect->type) . ",
                            `pattern` = " . db_str($oRedirect->pattern) . ",
                            `newUrl` = " . db_str($oRedirect->newUrl) . ",
                            `online` = " . db_int($oRedirect->online) . "
                        WHERE
                            `redirectId` = " . db_int($oRedirect->redirectId) . "
                        ;";
            $oDb->query($sQuery, QRY_NORESULT);
        }

    }

    /**
     * update online status of Redirect by id
     *
     * @param int $bOnline
     * @param int $iredirectId
     *
     * @return bool
     */
    public static function updateOnlineByredirectId($bOnline, $iredirectId)
    {
        $sQuery = ' UPDATE
                        `redirects`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `redirectId` = ' . db_int($iredirectId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * delete a Redirect
     *
     * @param Redirect $oRedirect
     *
     * @return bool true
     */
    public static function deleteRedirect(Redirect $oRedirect)
    {
        # delete object
        $sQuery = ' DELETE FROM
                        `redirects`
                    WHERE
                        `redirectId` = ' . db_int($oRedirect->redirectId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}

?>
