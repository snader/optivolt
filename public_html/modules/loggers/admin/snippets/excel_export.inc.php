<?php

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$aColumns[1] = 'A';
$aColumns[2] = 'B';
$aColumns[3] = 'C';
$aColumns[4] = 'D';
$aColumns[5] = 'E';
$aColumns[6] = 'F';
$aColumns[7] = 'G';
$aColumns[8] = 'H';
$aColumns[9] = 'I';
$aColumns[10] = 'J';
$aColumns[11] = 'K';
$aColumns[12] = 'L';
$aColumns[13] = 'M';
$aColumns[14] = 'N';
$aColumns[15] = 'O';
$aColumns[16] = 'P';
$aColumns[17] = 'Q';
$aColumns[18] = 'R';
$aColumns[19] = 'S';
$aColumns[20] = 'T';
$aColumns[21] = 'U';
$aColumns[22] = 'V';
$aColumns[23] = 'W';
$aColumns[24] = 'X';
$aColumns[25] = 'Y';
$aColumns[26] = 'Z';
$aColumns[27] = 'AA';
$aColumns[28] = 'AB';
$aColumns[29] = 'AC';
$aColumns[30] = 'AD';
$aColumns[31] = 'AE';
$aColumns[32] = 'AF';
$aColumns[33] = 'AG';
$aColumns[34] = 'AH';
$aColumns[35] = 'AI';
$aColumns[36] = 'AJ';
$aColumns[37] = 'AK';
$aColumns[38] = 'AL';
$aColumns[39] = 'AM';
$aColumns[40] = 'AN';

$aColor[1] = 'ffffb5';
$aColor[2] = 'abdee6';
$aColor[3] = 'ffccb6';
$aColor[4] = 'cbaacb';
$aColor[5] = 'f3b0c3';
$aColor[6] = 'c6d8da';
$aColor[7] = 'fee1e8';
$aColor[8] = 'f6eac2';
$aColor[9] = 'ff968a';
$aColor[10] = '8fcaca';
$aColor[11] = 'cce2cb';
$aColor[12] = '97c1a9';
$aColor[13] = '55cbcd';
$aColor[14] = '9fdd78';
$aColor[15] = 'df5ed4';
$aColor[16] = '4cddce';
$aColor[17] = 'dbbc5d';
$aColor[18] = 'df5d4c';

$aAccountManagerColor[1] = 'FF0000';
$aAccountManagerColor[2] = '00b050';
$aAccountManagerColor[3] = 'ff00ff';
$aAccountManagerColor[4] = '0000ff';

$sTitle = 'Export_Loggersplanning-' . date('d-m-Y H-i-s');

$helper = new Sample();
if ($helper->isCli()) {
  $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

  return;
}

// set layout
$locale = 'nl';
$validLocale = \PhpOffice\PhpSpreadsheet\Settings::setLocale($locale);
if (!$validLocale) {
  echo 'Unable to set locale to ' . $locale . " - reverting to en_us<br />\n";
}
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator($oCurrentUser->name)
  ->setLastModifiedBy($oCurrentUser->name)
  ->setTitle('Export Loggersplanning')
  ->setSubject(date('d-m-Y H-i-s'))
  ->setDescription($sTitle)
  ->setKeywords('Optivolt')
  ->setCategory('Loggers');

$spreadsheet->getDefaultStyle()->getFont()->setName('Century Gothic');
$spreadsheet->getDefaultStyle()->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getColumnDimension($aColumns[1])->setAutoSize(TRUE);
$spreadsheet->getActiveSheet()->getColumnDimension($aColumns[2])->setWidth(25); //->setAutoSize(TRUE);;
$spreadsheet->getActiveSheet()->getColumnDimension($aColumns[3])->setWidth(25); //->setAutoSize(TRUE);;
$spreadsheet->getActiveSheet()->getColumnDimension($aColumns[4])->setWidth(25); //->setAutoSize(TRUE);;
$spreadsheet->getActiveSheet()->getColumnDimension($aColumns[5])->setWidth(25); //->setAutoSize(TRUE);;
$spreadsheet->getActiveSheet()->getColumnDimension($aColumns[6])->setWidth(25); //->setAutoSize(TRUE);;
$styleArray1 = [
  'borders' => [
    'allBorders' => [
      'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
      'color' => array('rgb' => 'aaaaaa')
    ]
  ]
];


// get all possible combinations of accountmanagers
$aAccountmanagerCombos = array();

// first month starts on $iRowNr = 6;
$iRowNr = 8;
$iColumnsOffset = 6;

$spreadsheet->getActiveSheet()->getStyle('A1' . ':' . 'AK500')->applyFromArray($styleArray1);

// iterate through 5 upcoming months
for ($iMonthCount = 0; $iMonthCount <= 5; $iMonthCount++) {

  $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . ($iRowNr + 1), 'Klantnaam');

  // create colors and centering
  for ($i = 7; $i <= 37; $i++) {
    $spreadsheet->getActiveSheet()->getColumnDimension($aColumns[$i])->setWidth(4);
    $spreadsheet->getActiveSheet()->getStyle($aColumns[$i] . $iRowNr)->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('FF00B050');

    $spreadsheet->getActiveSheet()->getStyle($aColumns[$i] . $iRowNr)
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  }
  for ($i = 1; $i <= 37; $i++) {
    $spreadsheet->getActiveSheet()->getStyle($aColumns[$i] . ($iRowNr + 1))->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('FFF4B183');
    $spreadsheet->getActiveSheet()->getStyle($aColumns[$i] . ($iRowNr + 1))
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  }

  // Add some data
  $aDayNrsList = array();
  $aDaysList = array();
  $d = new DateTime(date('Y-m-d'));
  $d->modify('first day of +' . $iMonthCount . ' month');
  $iMonth = $d->format('m');
  $sMonth = $d->format('F');
  $iYear = $d->format('Y');

  $iDaysInMonth = cal_days_in_month(
    CAL_GREGORIAN,
    $iMonth,
    $iYear
  );


  for (
    $d = 1;
    $d <= $iDaysInMonth;
    $d++
  ) {
    $time = mktime(12, 0, 0, $iMonth, $d, $iYear);
    if (date('m', $time) == $iMonth) {
      $aDayNrsList[] = date('d', $time);
      $aDaysList[] = substr(date('l', $time), 0, 1);
    }
  }
  //Month
  $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $iRowNr, $sMonth . '-' . $iYear);
  $spreadsheet->getActiveSheet()->getStyle('A' . $iRowNr)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF385724');
  $spreadsheet->getActiveSheet()->getStyle('A' . $iRowNr)
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

  // days first letter
  $spreadsheet->getActiveSheet()
  ->fromArray(
    $aDaysList,
    NULL,
    'G' . $iRowNr
  );
  // daynr
  $spreadsheet->getActiveSheet()
  ->fromArray(
    $aDayNrsList,
    NULL,
    'G' . ($iRowNr + 1)
  );


  $aLoggersFilter['online'] = true;
  $aLoggersFilter['startDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($iYear . '-' . $iMonth . '-01'))));
  $aLoggersFilter['endDate'] =  date('Y-m-d', strtotime(date("Y-m-d", strtotime($aLoggersFilter['startDate'])) . "+1 months"));

  // all planning loggers

  $aLoggers = LoggerManager::getLoggersByFilter($aLoggersFilter);
  $aPlanning = PlanningManager::getPlanningByFilter($aLoggersFilter);

  // get possible accountmanager/combos
  foreach ($aPlanning as $oPlanning) {

    $aKeys = [];
    $aNames = [];
    foreach ($oPlanning->getAccountManagers() as $oUser) {
      $aKeys[] = $oUser->userId;
      $aNames[] = $oUser->name;
    }
    if (!empty($aKeys)) {
      $aAccountmanagerCombos[implode(';', $aKeys)] = implode(' & ', $aNames);
    }
  }

  $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Accountmanagers');
  $iRowCount = 0;
  foreach ($aAccountmanagerCombos as $iAmKeys => $sNames) {
    $iRowCount++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $iRowCount, $sNames);
    $spreadsheet->getActiveSheet()->getStyle('B' . $iRowCount)->getFont()->setBold(true)->getColor()
      ->setRGB($aAccountManagerColor[$iRowCount]);
  }
  // end accountmanagers/combos

  $iRunRowNr = $iRowNr;

  ///////////////////
  ///////////////////
  ///////////////////
  $iRowNr = $iRunRowNr + 1;
  $iPreviousLoggerId = '';
  $iCustomerColumnCount  = 0;
  $iRemember = [];


  foreach ($aLoggers as $oLogger) {


    if (
      $oLogger->mainLoggerId == $iPreviousLoggerId
    ) {
      // same logger line

    } else {
      // new logger line
      $iCustomerColumnCount  = 1; // start with column B
      $iRowNr++;

      // loggername Column A
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $iRowNr, 'Logger ' . $oLogger->name);
      $spreadsheet->getActiveSheet()->getStyle('A' . $iRowNr)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FF00B050');

      // klanten achtergrondkleuren in Columns B-F
      $iCount = 1; // start with column B
      do {
        // empty columns coloring
        $iCount++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($aColumns[$iCount] . $iRowNr, '     ');
        $spreadsheet->getActiveSheet()->getStyle($aColumns[$iCount] . $iRowNr)->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FF' . $aColor[$iCount]);
      } while ($iCount <= ($iColumnsOffset - 1));
    }

    $iPreviousLoggerId = $oLogger->mainLoggerId;
  }

  // skip 2 rows for next month
  $iRowNr = $iRowNr + 2;
}

///////////////////////
///////////////////////
///////////////////////
// PARENTS
$iRowNr = 9;
// iterate through 5 upcoming months
for ($iMonthCount = 0; $iMonthCount <= 5; $iMonthCount++) {

  $d = new DateTime(date('Y-m-d'));
  $d->modify('first day of +' . $iMonthCount . ' month');
  $iMonth = $d->format('m');
  $sMonth = $d->format('F');
  $iYear = $d->format('Y');

  $aLoggersFilter['online'] = true;
  $aLoggersFilter['startDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($iYear . '-' . $iMonth . '-01'))));
  $aLoggersFilter['endDate'] =  date('Y-m-d', strtotime(date("Y-m-d", strtotime($aLoggersFilter['startDate'])) . "+1 months"));

  // all loggers
  $aLoggers = LoggerManager::getLoggersByFilter($aLoggersFilter);


  $iPreviousLoggerId = '';
  $iCustomerColumnCount  = 0;
  $iRemember = [];


  foreach ($aLoggers as $oLogger) {
    $bParent = true;
    if (
      !$oLogger->isParent() && !$oLogger->parentPlanningId
    ) {
      $bParent = false;
    }

    if (
      $oLogger->mainLoggerId == $iPreviousLoggerId
    ) {
      // same logger line

    } else {
      // new logger line
      $iCustomerColumnCount  = 1; // start with column B
      $iRowNr++;
    }

    if (
      $oLogger->companyName && strtotime($oLogger->endDate) > strtotime($aLoggersFilter['startDate'])
    ) {

      if (strtotime($oLogger->startDate) >= strtotime(date($iYear . '-' . ($iMonth + 1) . '-01'))) {
        continue;
      }

      $iCustomerColumnCount++;

      // default column number
      $iColumnNumber = $iCustomerColumnCount;

      // check cell value that we want to write to
      $sCellValue = "";

      $sCellValue = trim($spreadsheet->setActiveSheetIndex(0)->getCell($aColumns[$iColumnNumber] . $iRowNr)->getCalculatedValue());

      if (trim($sCellValue) != "") {
        $iColumnNumber++;
        $iCustomerColumnCount++;
      }

      if ($iCustomerColumnCount > 6) {
        $iCustomerColumnCount = 2;
        $iColumnNumber = 2;
      }

      // is this a multi logger planning? Yes, set in remember
      if ($oLogger->isParent()) {
        $iRemember[$oLogger->planningId] = $iCustomerColumnCount;
        //$oLogger->companyName = $oLogger->companyName . $iCustomerColumnCount;
      }

      // was this a child of multi logger planning, use rememberd columnnumber
      if (isset($iRemember[$oLogger->parentPlanningId])) {
        //$oLogger->companyName = $oLogger->companyName . $iRemember[$oLogger->parentPlanningId];
        $iColumnNumber = $iRemember[$oLogger->parentPlanningId];
      }

      // company name
      if ($bParent) {
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($aColumns[$iColumnNumber] . $iRowNr, $oLogger->companyName);
      }

      // get text color
      $aKeys = array();
      foreach ($oLogger->getAccountManagers() as $oUser) {
        $aKeys[$oUser->userId] = $oUser->userId;
      }
      if (!empty($aKeys)) {
        $sMyAccountmanagerComboKey = implode(';', $aKeys);

        $iComboCount = 0;
        foreach ($aAccountmanagerCombos as $skeyCombo => $sName) {

          $iComboCount++;
          if ($skeyCombo == $sMyAccountmanagerComboKey) {

            if ($bParent) {
              $spreadsheet->getActiveSheet()->getStyle($aColumns[$iColumnNumber] . $iRowNr)->getFont()->setBold(true)->getColor()
                ->setRGB($aAccountManagerColor[$iComboCount]);
              break;
            }
          }
        }
      }
      // //


      // find correct column to add planning, based on startdate and days
      $iColumnsFromLeft = daysBetween($aLoggersFilter['startDate'], $oLogger->startDate) + $iColumnsOffset + 1;

      for ($i = $iColumnsFromLeft; $i <= ($iColumnsFromLeft + $oLogger->days - 1); $i++) {
        if ($i > $iColumnsOffset && ($i <= ($iDaysInMonth + $iColumnsOffset)) && isset($aColumns[$i])) {

          if ($bParent) {
            $spreadsheet->getActiveSheet()->getStyle($aColumns[$i] . $iRowNr)->getFill()
              ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FF' . $aColor[($iColumnNumber)]);
          }
        }
      }

      //
    }

    $iPreviousLoggerId = $oLogger->mainLoggerId;
  }

  // skip 2 rows for next month
  $iRowNr = $iRowNr + 2;
}



/////////////////////////
/////////////////////////
// CHILDS
/////////////////////////
$iRowNr = 9;
// iterate through 5 upcoming months
for ($iMonthCount = 0; $iMonthCount <= 5; $iMonthCount++) {

  $d = new DateTime(date('Y-m-d'));
  $d->modify('first day of +' . $iMonthCount . ' month');
  $iMonth = $d->format('m');
  $sMonth = $d->format('F');
  $iYear = $d->format('Y');

  $aLoggersFilter['online'] = true;
  $aLoggersFilter['startDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($iYear . '-' . $iMonth . '-01'))));
  $aLoggersFilter['endDate'] =  date('Y-m-d', strtotime(date("Y-m-d", strtotime($aLoggersFilter['startDate'])) . "+1 months"));

  // all loggers
  $aLoggers = LoggerManager::getLoggersByFilter($aLoggersFilter);


  $iPreviousLoggerId = '';
  $iCustomerColumnCount  = 0;
  $iRemember = [];


  foreach ($aLoggers as $oLogger) {
    $bParent = true;
    if (
      !$oLogger->isParent() && !$oLogger->parentPlanningId
    ) {
      $bParent = false;
    }

    if (
      $oLogger->mainLoggerId == $iPreviousLoggerId
    ) {
      // same logger line

    } else {
      // new logger line
      $iCustomerColumnCount  = 1; // start with column B
      $iRowNr++;
    }

    if (
      $oLogger->companyName && strtotime($oLogger->endDate) > strtotime($aLoggersFilter['startDate'])
    ) {

      if (strtotime($oLogger->startDate) >= strtotime(date($iYear . '-' . ($iMonth + 1) . '-01'))) {
        continue;
      }

      $iCustomerColumnCount++;

      // default column number
      $iColumnNumber = $iCustomerColumnCount;

      // check cell value that we want to write to
      $sCellValue = "";

      $sCellValue = trim($spreadsheet->setActiveSheetIndex(0)->getCell($aColumns[$iColumnNumber] . $iRowNr)->getCalculatedValue());

      if (trim($sCellValue) != "") {
        $iColumnNumber++;
        $iCustomerColumnCount++;
      }

      if ($iCustomerColumnCount > 6) {
        $iCustomerColumnCount = 2;
        $iColumnNumber = 2;
      }

      // is this a multi logger planning? Yes, set in remember
      if ($oLogger->isParent()) {
        $iRemember[$oLogger->planningId] = $iCustomerColumnCount;
        //$oLogger->companyName = $oLogger->companyName . $iCustomerColumnCount;
      }

      // was this a child of multi logger planning, use rememberd columnnumber
      if (isset($iRemember[$oLogger->parentPlanningId])) {
        //$oLogger->companyName = $oLogger->companyName;// . $iRemember[$oLogger->parentPlanningId];
        $iColumnNumber = $iRemember[$oLogger->parentPlanningId];
      }

      // company name
      if (!$bParent) {
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($aColumns[$iColumnNumber] . $iRowNr, $oLogger->companyName);
      }

      // get text color
      $aKeys = array();
      foreach ($oLogger->getAccountManagers() as $oUser) {
        $aKeys[$oUser->userId] = $oUser->userId;
      }
      if (!empty($aKeys)) {
        $sMyAccountmanagerComboKey = implode(';', $aKeys);

        $iComboCount = 0;
        foreach ($aAccountmanagerCombos as $skeyCombo => $sName) {

          $iComboCount++;
          if ($skeyCombo == $sMyAccountmanagerComboKey) {

            if (!$bParent) {
              $spreadsheet->getActiveSheet()->getStyle($aColumns[$iColumnNumber] . $iRowNr)->getFont()->setBold(true)->getColor()
              ->setRGB($aAccountManagerColor[$iComboCount]);
            break;
            }
          }
        }
      }
      // //


      // find correct column to add planning, based on startdate and days
      $iColumnsFromLeft = daysBetween($aLoggersFilter['startDate'], $oLogger->startDate) + $iColumnsOffset + 1;

      for ($i = $iColumnsFromLeft; $i <= ($iColumnsFromLeft + $oLogger->days - 1); $i++) {
        if ($i > $iColumnsOffset && ($i <= ($iDaysInMonth + $iColumnsOffset)) && isset($aColumns[$i])) {

          if (!$bParent) {
          $spreadsheet->getActiveSheet()->getStyle($aColumns[$i] . $iRowNr)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FF' . $aColor[($iColumnNumber)]);
          }
        }
      }

      //
    }

    $iPreviousLoggerId = $oLogger->mainLoggerId;
  }

  // skip 2 rows for next month
  $iRowNr = $iRowNr + 2;

}


// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('export');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $sTitle . '.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
die;
