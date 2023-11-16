<?php

class LoggersDaysManager
{

  /**
   * get the full Logger object by id
   *
   * @param int $iLoggerDayId
   *
   * @return LoggersDay
   */
  public static function getLoggersDayById($iLoggerDayId)
  {
    $sQuery = ' SELECT
                        `ld`.*
                    FROM
                        `logger_days` AS `ld`
                    WHERE
                        `ld`.`loggerDayId` = ' . db_int($iLoggerDayId) . '
                    ;';

    $oDb = DBConnections::get();

    return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "LoggersDay");
  }


  /**
   * get the full LoggersDay object by sName
   *
   * @param int $sName
   *
   * @return LoggersDay
   */
  public static function getLoggersDayByName($sName)
  {
    $sQuery = ' SELECT
                        `ld`.*
                    FROM
                        `logger_days` AS `ld`
                    WHERE
                        `ld`.`name` = ' . db_str($sName) . '
                    ;';

    $oDb = DBConnections::get();

    return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "LoggersDay");
  }

  /**
   * save LoggersDay object
   *
   * @param LoggersDay $oLoggersDay
   */
  public static function saveLoggersDay(LoggersDay $oLoggersDay)
  {
    # save item
    $sQuery = ' INSERT INTO `logger_days` (
                        `loggerDayId`,
                        `name`,
                        `online`,
                        `dayNumber`,
                        `date`
                    )
                    VALUES (
                        ' . db_int($oLoggersDay->loggerDayId) . ',
                        ' . db_str($oLoggersDay->name) . ',
                        ' . db_int($oLoggersDay->online) . ',
                        ' . db_int($oLoggersDay->dayNumber) . ',
                        ' . db_date($oLoggersDay->date) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `loggerDayId`=VALUES(`loggerDayId`),
                        `name`=VALUES(`name`),
                        `online`=VALUES(`online`),
                        `dayNumber`=VALUES(`dayNumber`),
                        `date`=VALUES(`date`)
                    ;';

    $oDb = DBConnections::get();
    $oDb->query($sQuery, QRY_NORESULT);

    if ($oLoggersDay->loggerDayId === null) {
      $oLoggersDay->loggerDayId = $oDb->insert_id;
    }
  }

  /**
   * delete item and all media
   *
   * @param LoggersDay $oLoggersDay
   *
   * @return Boolean
   */
  public static function deleteLoggersDay(LoggersDay $oLoggersDay)
  {
    $oDb = DBConnections::get();

    /* check if item exists and is deletable */
    if ($oLoggersDay->isDeletable()) {

      $sQuery = "DELETE FROM `logger_days` WHERE `loggerDayId` = " . db_int($oLoggersDay->loggerDayId) . ";";
      $oDb->query($sQuery, QRY_NORESULT);

      return true;
    }

    return false;
  }

  /**
   * update online by Logger item id
   *
   * @param int $bOnline
   * @param int $iLoggerDayId
   *
   * @return boolean
   */
  public static function updateOnlineByLoggerId($bOnline, $iLoggerDayId)
  {
    $sQuery =
      ' UPDATE
                        `logger_days`
                    SET
                        `online` = ' . db_int($bOnline) .
      '
                    WHERE
                        `loggerDayId` = ' . db_int($iLoggerDayId) .
      '
                    ;
        ';
    $oDb    = DBConnections::get();

    $oDb->query($sQuery, QRY_NORESULT);

    # check if something happened
    return $oDb->affected_rows > 0;
  }



  /**
   * return Logger items filtered by a few options
   *
   * @param array $aFilter    filter properties (checkOnline)
   * @param int   $iLimit     limit number of records returned
   * @param int   $iStart     start from this record
   * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
   * @param array $aOrderBy   array(database column name => order) add order by columns and orders
   *
   * @return array LoggersDay
   */
  public static function getLoggersDaysByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`ld`.`name`' => 'ASC'])
  {
    $sFrom    = '';
    $sWhere   = '';
    $sGroupBy = '';



    // no show all? only show online items
    if (empty($aFilter['showAll'])) {
      $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `ld`.`online` = 1
                        ';
    }

    if (!empty($aFilter['onlyDays'])) {
      $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`ld`.`dayNumber` IS NOT NULL) ';
    }

    # search for q
    if (!empty($aFilter['q'])) {
      $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`ld`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
    }

    # handle order by
    $sOrderBy = '';
    if (count($aOrderBy) > 0) {
      foreach ($aOrderBy as $sColumn => $sOrder) {
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
                        `ld`.*

                    FROM
                        `logger_days` AS `ld`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

    //_d($sQuery);

    $oDb        = DBConnections::get();
    $aLoggersDays = $oDb->query($sQuery, QRY_OBJECT, "LoggersDay");
    if ($iFoundRows !== false) {
      $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
    }

    return $aLoggersDays;
  }




  /**
   * return Planning items filtered by a few options
   *
   * @param array $aFilter    filter properties (checkOnline)
   * @param int   $iLimit     limit number of records returned
   * @param int   $iStart     start from this record
   * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
   * @param array $aOrderBy   array(database column name => order) add order by columns and orders
   *
   * @return array Planning
   */
  public static function getLoggersDatesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`ld`.`date`' => 'ASC'])
  {
    $sFrom    = '';
    $sWhere   = '';
    $sGroupBy = '';

    //$sFrom    .=  'LEFT JOIN `planning` AS `ld` ON `ld`.`loggerId` = `l`.`loggerId`' . PHP_EOL;
    if (isset($aFilter['startDate']) && isset($aFilter['endDate'])) {
      $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(

        ( (' . db_str($aFilter['startDate']) . ' >= `ld` . `date`) AND (' . db_str($aFilter['startDate']) . ' <= `ld`.`date`) ) OR
        ( (' . db_str($aFilter['endDate']) . ' >= `ld` . `date`) AND (' . db_str($aFilter['endDate']) . ' <= `ld`.`date`) ) OR
        ( (' . db_str($aFilter['startDate']) . ' <= `ld` . `date`) AND (' . db_str($aFilter['endDate']) . ' >= `ld`.`date`) )

        )';
    }


    # handle order by
    $sOrderBy = '';
    if (count($aOrderBy) > 0) {
      foreach ($aOrderBy as $sColumn => $sOrder) {
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


                        `ld`.*
                    FROM
                        `logger_days` AS `ld`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

    $oDb        = DBConnections::get();
    $aLoggers = $oDb->query($sQuery, QRY_OBJECT, "LoggersDay");
    if ($iFoundRows !== false) {
      $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
    }

    return $aLoggers;
  }
}