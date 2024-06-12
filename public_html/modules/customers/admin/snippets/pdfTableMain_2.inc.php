<?php

/**
 * MULTILINER
 */

?>
<?php

$sMyImagesHTML = '';
$aSystemReports_prev = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId, 'year' => $iPrevYear]);
$aSystemReports_this = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId, 'year' => $iYear]);
?>


<tr>
  <td class="center width5"><?= $oSystem->pos ?></td>
  <td><?= _e($oSystem->locationName); ?></td>
  <td><?= _e($oSystem->floor); ?></td>
  <td><?= _e($oSystem->name) ?></td>
  <td style="color: red;" class="center"><?= _e($oSystem->model) ?><?= $oSystem->machineNr ? ' - ' . _e($oSystem->machineNr) : '' ?></td>
  <td style="color: red;" class="center"><?php

                                          if (!empty($aSystemReports_this) && !empty($aSystemReports_this[0])) {
                                            echo _e($aSystemReports_this[0]->columnA);
                                          } else {
                                            if (!empty($aSystemReports_prev) && !empty($aSystemReports_prev[0])) {
                                              echo _e($aSystemReports_prev[0]->columnA);
                                            }
                                          }

                                          ?></td>
  <td class="center width15"><?= !empty($aSystemReports_prev) ? $aSystemReports_prev[0]->faseA : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_prev) ? $aSystemReports_prev[0]->faseB : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_prev) ? $aSystemReports_prev[0]->faseC : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseA : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseB : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseC : '' ?></td>
  <td class="center">
    <?php
    $aImages = array();

    if (isset($aSystemReports_this) && !empty($aSystemReports_this)) {
      $aImages = $aSystemReports_this[0]->getImages();
    }
    $sImageNr = '';
    foreach ($aImages as $oImage) {

      if (
        (empty($oImage->getImageFileByReference('cms_thumb')->title) && !empty($oSubSystemReport->columnA)) || (empty($oImage->getImageFileByReference('cms_thumb')->title) && empty($oSubSystemReport->columnA))
      ) {
        // backwards compatibility where there was only one meting with images without title
        $sMyImagesHTML .= '<div  style="width:25%; float: left; padding-bottom: 5mm;" >';
        $sMyImagesHTML .= ' <img src="' . CLIENT_HTTP_URL . '/' . $oImage->getImageFileByReference('detail')->link . '" style="max-width: 65mm; max-height: 75mm;" />';
        $sImageNr .= (empty($sImageNr) ? '' : ', ') . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId);
        $sMyImagesHTML .= ' <div class="imgnr">' . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId) . '</div>';
        $sMyImagesHTML .= '</div>' . PHP_EOL;
      }



      if (empty($oImage->getImageFileByReference('cms_thumb')->title) || _e($oImage->getImageFileByReference('cms_thumb')->title) != _e($aSystemReports_this[0]->columnA)) {
        continue;
      }
      $sMyImagesHTML .= '<div  style="width:25%; float: left; padding-bottom: 5mm;" >';
      $sMyImagesHTML .= ' <img src="' . CLIENT_HTTP_URL . '/' . $oImage->getImageFileByReference('detail')->link . '" style="max-width: 65mm; max-height: 75mm;" />';
      $sImageNr .= (empty($sImageNr) ? '' : ', ') . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId);
      $sMyImagesHTML .= ' <div class="imgnr">' . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId) . '</div>';
      $sMyImagesHTML .= '</div>' . PHP_EOL;
    }
    if (!empty($sImageNr)) {

      echo $sImageNr;
    }
    ?>
  </td>
  <td><?php

      if (!$oSystem->online) {
        echo '<div>Vervallen</div>';
      }

      $aList = explode(PHP_EOL, trim($oSystem->notice));
      foreach ($aList as $sListItem) {  
        if (substr_count($sListItem, '(' . $iYear . ')') > 0) {
          echo '<div>' . _e($sListItem) . '</div>';
        }
      } ?></td>
</tr>

<?php

$aSubSystemReports_this = array();
$aSubSystemReports_prev = array();

if (isset($aSystemReports_this) && isset($aSystemReports_this[0]) && !empty($aSystemReports_this[0])) {
  $aSubSystemReports_this = $aSystemReports_this[0]->getSubSystemReports();
}
if (isset($aSystemReports_prev) && isset($aSystemReports_prev[0]) && !empty($aSystemReports_prev[0])) {
  $aSubSystemReports_prev = $aSystemReports_prev[0]->getSubSystemReports();
}

foreach ($aSubSystemReports_this as $oSubSystemReportsThis) : ?>

  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>

    <td style="color: red;" class="center"><?= $oSubSystemReportsThis->columnA ? _e($oSubSystemReportsThis->columnA) : (isset($aSystemReports_prev[0]) ? _e($aSystemReports_prev[0]->columnA) : '') ?></td>
    <?php
    $iCount = 0;
    if (!empty($aSubSystemReports_prev)) {
      foreach ($aSubSystemReports_prev as $oSubSystemReportsPrev) {
        if (strtoupper($oSubSystemReportsThis->columnA) == strtoupper($oSubSystemReportsPrev->columnA)) {
          $iCount++;
    ?>
          <td class="center width15"><?= !empty($oSubSystemReportsPrev) ? $oSubSystemReportsPrev->faseA : '' ?></td>
          <td class="center width15"><?= !empty($oSubSystemReportsPrev) ? $oSubSystemReportsPrev->faseB : '' ?></td>
          <td class="center width15"><?= !empty($oSubSystemReportsPrev) ? $oSubSystemReportsPrev->faseC : '' ?></td>
      <?php
        }
      }
    }
    if ($iCount == 0) {
      ?>
      <td>x</td>
      <td>x</td>
      <td>x</td>
    <?php
    }
    ?>

    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseA : '' ?></td>
    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseB : '' ?></td>
    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseC : '' ?></td>

    <td class="center">
      <?php
      $aImages = $aSystemReports_this[0]->getImages();
      $sImageNr = '';
      foreach ($aImages as $oImage) {

        if (empty($oImage->getImageFileByReference('cms_thumb')->title) || _e($oImage->getImageFileByReference('cms_thumb')->title) != _e($oSubSystemReportsThis->columnA)) {
          continue;
        }
        $sMyImagesHTML .= '<div style="width:25%; float: left; padding-bottom: 5mm;">';
        $sMyImagesHTML .= ' <img src="' . CLIENT_HTTP_URL . '/' . $oImage->getImageFileByReference('detail')->link . '" style="max-width: 65mm; max-height: 75mm;" />';
        $sImageNr .= (empty($sImageNr) ? '' : ', ') . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId);
        $sMyImagesHTML .= ' <div class="imgnr">' . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId) . '</div>';
        $sMyImagesHTML .= '</div>' . PHP_EOL;
      }
      if (!empty($sImageNr)) {

        echo $sImageNr;
      }
      ?>
    </td>
    <td></td>
  </tr>

<?php
endforeach;

if (!empty($sMyImagesHTML)) {
  $sImagesHTML .= '' . $sMyImagesHTML . '' . PHP_EOL;
}

?>