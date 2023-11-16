<?php

class LoggersDefaultsManager
{

  /**
   * get the full Logger object by id
   *
   * @param int $iLoggerDefaultId
   *
   * @return LoggersDefault
   */
  public static function getLoggersDefaultById($iLoggerDefaultId)
  {
    $sQuery = ' SELECT
                        `ld`.*
                    FROM
                        `logger_defaults` AS `ld`
                    WHERE
                        `ld`.`loggerDefaultId` = ' . db_int($iLoggerDefaultId) . '
                    ;';

    $oDb = DBConnections::get();

    return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "LoggersDefault");
  }


  /**
   * get the full Logger object by sName
   *
   * @param int $sName
   *
   * @return LoggersDefault
   */
  public static function getLoggersDefaultByName($sName)
  {
    $sQuery = ' SELECT
                        `ld`.*
                    FROM
                        `logger_defaults` AS `ld`
                    WHERE
                        `ld`.`name` = ' . db_str($sName) . '
                    ;';

    $oDb = DBConnections::get();

    return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "LoggersDefault");
  }

  /**
   * save Logger object
   *
   * @param LoggersDefault $oLoggersDefault
   */
  public static function saveLoggersDefault(LoggersDefault $oLoggersDefault)
  {

    $oLoggersDefault->specialDayWarning = 1;
    # save item
    $sQuery = ' INSERT INTO `logger_defaults` (
                        `loggerDefaultId`,
                        `name`,
                        `online`,
                        `days`,
                        `specialDayWarning`
                    )
                    VALUES (
                        ' . db_int($oLoggersDefault->loggerDefaultId) . ',
                        ' . db_str($oLoggersDefault->name) . ',
                        ' . db_int($oLoggersDefault->online) . ',
                        ' . db_int($oLoggersDefault->days) . ',
                        ' . db_int($oLoggersDefault->specialDayWarning) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `loggerDefaultId`=VALUES(`loggerDefaultId`),
                        `name`=VALUES(`name`),
                        `online`=VALUES(`online`),
                        `days`=VALUES(`days`),
                        `specialDayWarning`=VALUES(`specialDayWarning`)
                    ;';
    $oDb = DBConnections::get();
    $oDb->query($sQuery, QRY_NORESULT);

    if ($oLoggersDefault->loggerDefaultId  === null) {
      $oLoggersDefault->loggerDefaultId  = $oDb->insert_id;
    }
  }

  /**
   * delete item and all media
   *
   * @param LoggersDefault $oLoggersDefault
   *
   * @return Boolean
   */
  public static function deleteLoggersDefault(LoggersDefault $oLoggersDefault)
  {
    $oDb = DBConnections::get();

    /* check if item exists and is deletable */
    if ($oLoggersDefault->isDeletable()) {

      $sQuery = "DELETE FROM `logger_defaults` WHERE `loggerDefaultId` = " . db_int($oLoggersDefault->loggerDefaultId) . ";";
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
                        `logger_defaults`
                    SET
                        `online` = ' . db_int($bOnline) .
    '
                    WHERE
                        `loggerDefaultId` = ' . db_int($iLoggerDayId) .
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
   * @return array LoggersDefault
   */
  public static function getLoggersDefaultsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`ld`.`name`' => 'ASC'])
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
                        `logger_defaults` AS `ld`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

    //_d($sQuery);

    $oDb        = DBConnections::get();
    $aLoggersDefaults = $oDb->query($sQuery, QRY_OBJECT, "LoggersDefault");
    if ($iFoundRows !== false) {
      $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
    }

    return $aLoggersDefaults;
  }
}
