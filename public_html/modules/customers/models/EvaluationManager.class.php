<?php

class EvaluationManager
{

    /**
     * get the full Evaluation object by id
     *
     * @param int $iEvaluationId
     *
     * @return Evaluation
     */
    public static function getEvaluationById($iEvaluationId)
    {
        $sQuery = ' SELECT
                        `e`.*
                    FROM
                        `evaluations` AS `e`
                    WHERE
                        `e`.`evaluationId` = ' . db_int($iEvaluationId) . ' 
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Evaluation");
    }


    
    public static function getEvaluationByLoginHash($sLoginHash)
    {
        $sQuery = ' SELECT
                        `e`.*
                    FROM
                        `evaluations` AS `e`
                    WHERE
                        `e`.`loginHash` = ' . db_str($sLoginHash) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Evaluation");
    }

    /**
     *
     */
    public static function getEvaluationByCustomerId($iCustomerId)
    {
        $sQuery = ' SELECT
                        `e`.*
                    FROM
                        `evaluations` AS `e`
                    WHERE
                        `e`.`customerId` = ' . db_int($iCustomerId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Evaluation");
    }


    /**
     * Save Evaluation object
     *
     * @param Evaluation $oEvaluation
     */
    public static function saveEvaluation(Evaluation $oEvaluation)
    {
        $sQuery = ' INSERT INTO `evaluations` (
                        `evaluationId`,
                        `customerId`,                        
                        `installSat`,
                        `anyDetails`,
                        `conMeasured`,
                        `prepSat`,
                        `workSat`,
                        `answers`,
                        `friendlyHelpfull`,
                        `remarks`,
                        `nameSigned`,
                        `dateSend`,
                        `dateSigned`,
                        `digitalSigned`,
                        `loginHash`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oEvaluation->evaluationId) . ',
                        ' . db_int($oEvaluation->customerId) . ',
                        ' . db_int($oEvaluation->installSat) . ',                        
                        ' . db_str($oEvaluation->anyDetails) . ',
                        ' . db_int($oEvaluation->conMeasured) . ',
                        ' . db_int($oEvaluation->prepSat) . ',
                        ' . db_int($oEvaluation->workSat) . ',
                        ' . db_int($oEvaluation->answers) . ',
                        ' . db_int($oEvaluation->friendlyHelpfull) . ',
                        ' . db_str($oEvaluation->remarks) . ',
                        ' . db_str($oEvaluation->nameSigned) . ',
                        ' . db_str($oEvaluation->dateSend) . ',
                        ' . db_str($oEvaluation->dateSigned) . ',
                        ' . db_str($oEvaluation->digitalSigned) . ',
                        ' . db_str($oEvaluation->loginHash) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `customerId`=VALUES(`customerId`),
                        `installSat`=VALUES(`installSat`),
                        `anyDetails`=VALUES(`anyDetails`),
                        `conMeasured`=VALUES(`conMeasured`),
                        `workSat`=VALUES(`workSat`),
                        `answers`=VALUES(`answers`),
                        `friendlyHelpfull`=VALUES(`friendlyHelpfull`),
                        `remarks`=VALUES(`remarks`),
                        `nameSigned`=VALUES(`nameSigned`),
                        `dateSend`=VALUES(`dateSend`),
                        `dateSigned`=VALUES(`dateSigned`),
                        `digitalSigned`=VALUES(`digitalSigned`),
                        `loginHash`=VALUES(`loginHash`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oEvaluation->evaluationId === null) {
            $oEvaluation->evaluationId = $oDb->insert_id;
        }
    }

    /**
     * Delete Evaluation object and all media
     *
     * @param Evaluation $oEvaluation
     *
     * @return Boolean
     */
    public static function deleteEvaluation(Evaluation $oEvaluation)
    {
        $oDb = DBConnections::get();

        /* check if item exists and is deletable */
        if ($oEvaluation->isDeletable()) {

            $sQuery = ' DELETE FROM
                        `evaluations`
                    WHERE
                        `evaluationId` = ' . db_int($oEvaluation->evaluationId) . '
                    LIMIT 1
                    ;';

            $oDb->query($sQuery, QRY_NORESULT);
            return true;
        }

        return false;
    }

    /**
     * Return Evaluation items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Evaluation
     */
    public static function getEvaluationsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`e`.`created`' => 'ASC', '`e`.`evaluationId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        //$sWhere .= ($sWhere != '' ? ' AND ' : '') . ' ';

        # get by customerId
        if (isset($aFilter['customerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`e`.`customerId` = ' . db_int($aFilter['customerId']);
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`e`.`nameSigned` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `e`.`remarks` LIKE ' . db_str(
                    '%' . $aFilter['q'] . '%'
                ) . ')';
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`e`.`modified`, `e`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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
                        `e`.*,
                        `c`.*
                    FROM
                        `evaluations` AS `e`
                    ' . $sFrom . '
                    LEFT JOIN `customers` AS `c` ON `c`.`customerId` = `e`.`customerId` 
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aEvaluations = $oDb->query($sQuery, QRY_OBJECT, "Evaluation");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aEvaluations;
    }





}