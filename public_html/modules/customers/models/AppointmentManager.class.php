<?php

class AppointmentManager
{

    /**
     * get the full Appointment object by id
     *
     * @param int $iAppointmentId
     * @param int $iLocaleId
     *
     * @return Appointment
     */
    public static function getAppointmentById($iAppointmentId)
    {
        $sQuery = ' SELECT
                        `s`.*
                    FROM
                        `users_customers` AS `s`
                    WHERE
                        `s`.`appointmentId` = ' . db_int($iAppointmentId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Appointment");
    }

    

    /**
     * return Appointment items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database column name => order) add order by columns and orders
     *
     * @return array Appointment
     */
    public static function getAppointmentsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`s`.`visitDate`' => 'ASC', '`s`.`modified`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        // zichtbaar voor klant of niet
        if (isset($aFilter['customer'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`customer` = ' . db_int($aFilter['customer']);
        }

        # get by customerId
        if (isset($aFilter['customerId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`customerId` = ' . db_int($aFilter['customerId']);
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`visitDate` LIKE ' . db_str('%' . $aFilter['q'] . '%') . 
            ' OR `s`.`signatureName` LIKE ' . db_str('%' . $aFilter['q'] . '%') . 
            ' OR `s`.`signature` = ' . db_str('' . $aFilter['q'] . '') . 
            ')';
        }

        # get items with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`s`.`visitDate`, `s`.`modified`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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
                        `users_customers` AS `s`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aAppointments = $oDb->query($sQuery, QRY_OBJECT, "Appointment");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aAppointments;
    }




}