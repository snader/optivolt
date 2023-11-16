<?php

/**
 * MULTILINER
 */

?>

<tr>
  <td class="center width5"><?= $oSystem->pos ?></td>
  <td><?= _e($oSystem->locationName); ?></td>
  <td><?= _e($oSystem->floor); ?></td>
  <td class="center width15"><?= _e($oSystem->machineNr) ?>
  <td><?= _e($oSystem->name) ?></td>
  <td style="color: red;" class="center"><?= _e($oSystem->model) ?></td>

  <?php
  $aSystemReports = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId, 'year' => $iPrevYear]);
  ?>
  <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseA : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseB : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseC : '' ?></td>

  <?php
  // Current Year
  $aSystemReports = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId, 'year' => $iYear]);

  $sImageNr = '';
  $sMyImagesHTML = '';
  if (!empty($aSystemReports)) {
    $aImages = $aSystemReports[0]->getImages();

    if (!empty($aImages)) {

      $sMyImagesHTML .= '<div  style="width:25%; float: left; padding-bottom: 5mm;" >';
      foreach ($aImages as $oImage) {
        $sMyImagesHTML .= ' <img src="' . CLIENT_HTTP_URL . '/' . $oImage->getImageFileByReference('detail')->link . '" style="max-width: 65mm; max-height: 75mm;" />';
        $sImageNr .= (empty($sImageNr) ? '' : ', ') . $oImage->imageId;
        $sMyImagesHTML .= ' <div class="imgnr">' . $oImage->imageId . '</div>';
        $sMyImagesHTML .= '</div>' . PHP_EOL;
      }
      if (!empty($sImageNr)) {
      }
    }
  }


  ?>
  <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseA : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseA : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseA : '' ?></td>

  <td class="center"><?= $sImageNr ?></td>
  <td><?php
      $aList = explode(PHP_EOL, trim($oSystem->notice));
      if (isset($aList[0])) {
        echo _e($aList[0]);
      } ?>
  </td>
</tr>

<?php

if (!empty($sMyImagesHTML)) {
  $sImagesHTML .= '' . $sMyImagesHTML . '' . PHP_EOL;
}
