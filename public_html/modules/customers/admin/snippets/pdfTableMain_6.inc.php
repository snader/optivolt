<?php

/**
 * Actief Harmonisch Filter (AHF) - OBSOLETE
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
        $sMyImagesHTML .= ' <img src="' . DOCUMENT_ROOT . '/' . $oImage->getImageFileByReference('detail')->link . '" style="max-width: 65mm; max-height: 75mm;" />';
        $sImageNr .= (empty($sImageNr) ? '' : ', ') . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId);
        $sMyImagesHTML .= ' <div class="imgnr">' . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId) . '</div>';
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

if (!$oSystem->online) {
  echo '<div>Vervallen</div>';
}

$aList = explode(PHP_EOL, trim($oSystem->notice ?? ''));
foreach ($aList as $sListItem) {  
  if (substr_count($sListItem, '(' . $iYear . ')') > 0) {
    echo '<div>' . _e($sListItem) . '</div>';
  }
} ?>
  </td>
</tr>

<?php

if (!empty($sMyImagesHTML)) {
  $sImagesHTML .= '' . $sMyImagesHTML . '' . PHP_EOL;
}
