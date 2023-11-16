<?php

class PlanningManager
{

  /**
   * get the full Planning object by id
   *
   * @param int $iPlanningId
   *
   * @return Planning
   */
  public static function getPlanningById($iPlanningId)
  {
    $sQuery = ' SELECT
                        `p`.*
                    FROM
                        `planning` AS `p`
                    WHERE
                        `p`.`planningId` = ' . db_int($iPlanningId) . '
                    ;';

    $oDb = DBConnections::get();

    return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Planning");
  }

  /**
   * @return Planning Array
   */
  public static function getChildsByParentId($iParentPlanningId)
  {

    $sQuery = ' SELECT
                          `p`.*
                      FROM
                          `planning` AS `p`
                      WHERE
                          `p`.`parentPlanningId` = ' . db_int($iParentPlanningId) . '
                      ;';

    $oDb = DBConnections::get();

    return $oDb->query($sQuery, QRY_OBJECT, "Planning");

  }


  /**
   * get the Planningitems by loggerid
   *
   * @param int $iLoggerId
   *
   * @return Planning
   */
  public static function getPlanningItemsByLoggerId($iLoggerId)
  {
    $sQuery = ' SELECT
                        `p`.*
                    FROM
                        `planning` AS `p`
                    WHERE
                        `p`.`loggerId` = ' . db_int($iLoggerId) . '
                        LIMIT 0,50
                    ;';

    $oDb = DBConnections::get();

    return $oDb->query($sQuery, QRY_OBJECT, "Planning");
  }


  /**
   * get the Planningitems by customerId
   *
   * @param int $iCustomerId
   *
   * @return Planning
   */
  public static function getPlanningItemsByCustomerId($iCustomerId)
  {
    $sQuery = ' SELECT
                        `p`.*
                    FROM
                        `planning` AS `p`
                    WHERE
                        `p`.`customerId` = ' . db_int($iCustomerId) . '
                        LIMIT 0,10
                    ;';

    $oDb = DBConnections::get();

    return $oDb->query($sQuery, QRY_OBJECT, "Planning");
  }

  /**
   * save Planning object
   *
   * @param Planning $oPlanning
   */
  public static function savePlanning(Planning $oPlanning)
  {



    # save item
    $sQuery = ' INSERT INTO `planning` (
                        `planningId`,
                        `parentPlanningId`,
                        `loggerId`,
                        `customerId`,
                        `comment`,
                        `color`,
                        `startDate`,
                        `endDate`,
                        `days`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oPlanning->planningId) . ',
                        ' . db_int($oPlanning->parentPlanningId) . ',
                        ' . db_int($oPlanning->loggerId) . ',
                        ' . db_int($oPlanning->customerId) . ',
                        ' . db_str($oPlanning->comment) . ',
                        ' . db_int($oPlanning->color) . ',
                        ' . db_str($oPlanning->startDate) . ',
                        ' . db_str($oPlanning->endDate) . ',
                        ' . db_int($oPlanning->days) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `planningId`=VALUES(`planningId`),
                        `parentPlanningId`=VALUES(`parentPlanningId`),
                        `loggerId`=VALUES(`loggerId`),
                        `customerId`=VALUES(`customerId`),
                        `comment`=VALUES(`comment`),
                        `color`=VALUES(`color`),
                        `startDate`=VALUES(`startDate`),
                        `endDate`=VALUES(`endDate`),
                        `days`=VALUES(`days`)
                    ;';

    $oDb = DBConnections::get();
    $oDb->query($sQuery, QRY_NORESULT);

    if ($oPlanning->planningId === null) {
      $oPlanning->planningId = $oDb->insert_id;
    }

    self::saveAccountManagers($oPlanning);
  }

  /**
   * Save the User relations of a planning
   *
   * @param User object
   */
  private static function saveAccountManagers(Planning $oPlanning)
  {
    $aAccountmanagers = $oPlanning->getAccountManagers();

    // Delete all customerGroup relations of this customer
    $sQuery = "DELETE FROM `planning_users` WHERE `planningId` = " . db_int($oPlanning->planningId);
    $oDb    = DBConnections::get();
    $oDb->query($sQuery, QRY_NORESULT);

    // Insert all customerGroup relations of this customer
    $sQueryValues = '';
    foreach ($aAccountmanagers as $oUser) {
      $sQueryValues .= (!empty($sQueryValues) ? ',' : '') . '(' . db_int($oUser->userId) . ',' . db_int($oPlanning->planningId) . ')';
    }

    /* save User Module relation */
    if (!empty($sQueryValues)) {
      $sQuery = " INSERT IGNORE INTO
                            `planning_users`
                        (
                            `userId`,
                            `planningId`
                        )
                        VALUES " . $sQueryValues . "
                        ;";

      $oDb->query($sQuery, QRY_NORESULT);
    }
  }

  /**
   * delete item and all media
   *
   * @param Planning $oPlanning
   *
   * @return Boolean
   */
  public static function deletePlanning(Planning $oPlanning)
  {
    $oDb = DBConnections::get();

    /* check if item exists and is deletable */
    if ($oPlanning->isDeletable()) {

      $sQuery = "DELETE FROM `planning` WHERE `planningId` = " . db_int($oPlanning->planningId) . ";";
      $oDb->query($sQuery, QRY_NORESULT);

      return true;
    }

    return false;
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
  public static function getPlanningByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`p`.`loggerId`' => 'ASC', '`p`.`startDate`' => 'ASC'])
  {
    $sFrom    = '';
    $sWhere   = '';
    $sGroupBy = '';


    //$sFrom    .=  'LEFT JOIN `planning` AS `p` ON `p`.`loggerId` = `l`.`loggerId`' . PHP_EOL;
    if (isset($aFilter['startDate']) && isset($aFilter['endDate'])) {

      if (isset($aFilter['startDate'])) {
        //$aFilter['startDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($aFilter['startDate'])) . "-10 day")); // OFFSET

      }

      $sWhere .= ($sWhere != '' ? ' AND ' : '') .
      '(

        ( (`p` . `startDate` >= ' . db_str($aFilter['startDate']) . ') AND (`p` . `startDate` <= ' . db_str($aFilter['endDate']) . ')  ) OR
        ( (`p` . `endDate` >= ' . db_str($aFilter['startDate']) . ') AND (`p` . `endDate` <= ' . db_str($aFilter['endDate']) . ') ) OR
        ( (`p` . `startDate` <= ' . db_str($aFilter['startDate']) . ') AND (`p` . `endDate` >= ' . db_str($aFilter['endDate']) .
      ') )

        )';
    }
    /*
    ( (' . db_str($aFilter['startDate']) . ' >= `p` . `startDate`) AND (' . db_str($aFilter['startDate']) . ' <= `p`.`endDate`) ) OR
        ( (' . db_str($aFilter['endDate']) . ' >= `p` . `startDate`) AND (' . db_str($aFilter['endDate']) . ' <= `p`.`endDate`) ) OR
        ( (' . db_str($aFilter['startDate']) . ' <= `p` . `startDate`) AND (' . db_str($aFilter['endDate']) . ' >= `p`.`endDate`) )

    */

    $aOrderBy['`p`.`startDate`'] = 'ASC';
    $sFrom    .= 'LEFT JOIN `customers` AS `c` ON `c`.`customerId` = `p`.`customerId`' . PHP_EOL;

    # get only loggerId
    if (!empty($aFilter['loggerId'])) {
      if (is_array($aFilter['loggerId'])) {

        $aTmp = [];
        foreach ($aFilter['loggerId'] as $iLoggerId) {
          $aTmp[$iLoggerId] = '(`p`.`loggerId` = ' . db_int($iLoggerId) . ')';
        }
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(' . implode(' OR ', $aTmp) . ') ';
      } else {
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`p`.`loggerId` = ' . db_int($aFilter['loggerId']) . ')';
      }
    }

    # don't query this planningId
    if (!empty($aFilter['notPlanningId'])) {
      $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`p`.`planningId` != ' . db_int($aFilter['notPlanningId']) . ')';
      if (!empty($aFilter['checkParentPlanningId'])) {
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`p`.`parentPlanningId` != ' . db_int($aFilter['checkParentPlanningId']) . ')';
      }

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

                        `c`.`companyName`,
                        `p`.*
                    FROM
                        `planning` AS `p`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

 //   _d($sQuery);

    $oDb        = DBConnections::get();
    $aLoggers = $oDb->query($sQuery, QRY_OBJECT, "Planning");
    if ($iFoundRows !== false) {
      $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
    }

    return $aLoggers;
  }


  public static function getAccountmanagersByPlanningId($iPlanningId)
  {

    $sFrom    = '';
    $sWhere   = ' WHERE `pu`.`planningId` = ' . db_int($iPlanningId);
    $sGroupBy = '';

    $sFrom  .= ' JOIN `users` AS `u` ON `u`.`userId` = `pu`.`userId`';

    $sQuery = 'SELECT * FROM `planning_users` AS `pu` ' . $sFrom . $sWhere . ' order by `u`.`userId`';

    $oDb        = DBConnections::get();

    return $oDb->query($sQuery, QRY_OBJECT, "User");
  }



}
