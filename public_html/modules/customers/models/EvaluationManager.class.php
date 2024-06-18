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
     * save Evaluation object
     *
     * @param Evaluation $oEvaluation
     */
    public static function saveEvaluation(Evaluation $oEvaluation)
    {

        public  $evaluationId;
        public  $customerId;
        public  $installSat     = null;
        public  $anyDetails     = null;
        public  $conMeasured    = null;
        public  $workSat        = null;
        public  $answers        = null;
        public  $friendlyHelpfull = null;
        public  $remarks = null;
        public  $customerRelName = null;
        public  $signatureDate = null;
        public  $digitalSigned = 0;    

        # save item
        $sQuery = ' INSERT INTO `evaluations` (
                        `evaluationId`,
                        `customerId`,                        
                        `installSat`,
                        `anyDetails`,
                        `conMeasured`,
                        `workSat`,
                        `answers`,
                        `friendlyHelpfull`,
                        `remarks`,
                        `customerRelName`,
                        `signatureDate`,
                        `digitalSigned`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oEvaluation->evaluationId) . ',
                        ' . db_int($oEvaluation->customerId) . ',
                        ' . db_int($oEvaluation->installSat) . ',                        
                        ' . db_int($oEvaluation->anyDetails) . ',
                        ' . db_int($oEvaluation->conMeasured) . ',
                        ' . db_int($oEvaluation->workSat) . ',
                        ' . db_int($oEvaluation->answers) . ',
                        ' . db_int($oEvaluation->friendlyHelpfull) . ',
                        ' . db_int($oEvaluation->remarks) . ',
                        ' . db_int($oEvaluation->customerRelName) . ',
                        ' . db_int($oEvaluation->signatureDate) . ',
                        ' . db_int($oEvaluation->digitalSigned) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `customerId`=VALUES(`customerId`),
                        `installSat`=VALUES(`installSat`),
                        `anyDetails`=VALUES(`anyDetails`),
                        `anyDetails`=VALUES(`conMeasured`),
                        `anyDetails`=VALUES(`workSat`),
                        `anyDetails`=VALUES(`answers`),
                        `anyDetails`=VALUES(`friendlyHelpfull`),
                        `anyDetails`=VALUES(`remarks`),
                        `anyDetails`=VALUES(`customerRelName`),
                        `anyDetails`=VALUES(`signatureDate`),
                        `anyDetails`=VALUES(`digitalSigned`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oEvaluation->evaluationId === null) {
            $oEvaluation->evaluationId = $oDb->insert_id;
        }

    }

    /**
     * delete item and all media
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

            return true;
        }

        return false;
    }

    
    /**
     * return Evaluation items filtered by a few options
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

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
        `e`.`deleted` = 0
    ';

        # get by customerId
        if (isset($aFilter['customerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`e`.`customerId` = ' . db_int($aFilter['customerId']);
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`e`.`customerRelName` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `e`.`remarks` LIKE ' . db_str(
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
                        `e`.*
                    FROM
                        `evaluations` AS `e`
                    ' . $sFrom . '
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