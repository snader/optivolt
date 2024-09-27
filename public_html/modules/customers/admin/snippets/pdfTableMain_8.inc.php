<?php

/**
 * VLINER SW
 */

$aSystemReports_this = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId, 'year' => $iYear]);
$sMyImagesHTML = '';

if (!empty($aSystemReports_this)) {
  $aSubSystemReports = $aSystemReports_this[0]->getSubSystemReports();
}

?>
<tr>
  <td class="center width5"><?= $oSystem->pos ?></td>
  <td><?= _e($oSystem->locationName); ?></td>
  <td><?= _e($oSystem->floor); ?></td>
  <td><?= _e($oSystem->name) ?></td>
  <td style="color: red;" class="center"><?= _e($oSystem->model) ?></td>

  <td style="color: red;" class="center">

    <?php
    if (!empty($aSystemReports_this) && !empty($aSystemReports_this[0])) {
      echo _e($aSystemReports_this[0]->columnA);
    } else {
      if (!empty($aSystemReports_prev) && !empty($aSystemReports_prev[0])) {
        echo _e($aSystemReports_prev[0]->columnA);
      }
    }
    ?>

  </td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseA : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseB : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseC : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseD : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseE : '' ?></td>
  <td class="center width15"><?= !empty($aSystemReports_this) ? $aSystemReports_this[0]->faseF : '' ?></td>
  <td class="center"><?php

                      $aImages = array();

                      if (isset($aSystemReports_this) && !empty($aSystemReports_this)) {
                        $aImages = $aSystemReports_this[0]->getImages();
                      }

                      $sImageNr = '';
                      foreach ($aImages as $oImage) {

                        if (empty($oImage->getImageFileByReference('cms_thumb')->title) || _e($oImage->getImageFileByReference('cms_thumb')->title) != _e($aSystemReports_this[0]->columnA)) {
                          continue;
                        }
                        $sMyImagesHTML .= '<div  style="width:25%; float: left; padding-bottom: 5mm;">';
                        $sMyImagesHTML .= ' <img src="' . DOCUMENT_ROOT . '/' . $oImage->getImageFileByReference('detail')->link . '" style="max-width: 65mm; max-height: 75mm;" />';
                        $sImageNr .= (empty($sImageNr) ? '' : ', ') . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId);
                        $sMyImagesHTML .= ' <div class="imgnr">' . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId) . '</div>';
                        $sMyImagesHTML .= '</div>' . PHP_EOL;
                      }
                      if (!empty($sImageNr)) {

                        echo $sImageNr;
                      }
                      ?></td>
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
$aSubSystemReports_this = array();
if (isset($aSystemReports_this) && isset($aSystemReports_this[0]) && !empty($aSystemReports_this[0])) {
  $aSubSystemReports_this = $aSystemReports_this[0]->getSubSystemReports();
}

foreach ($aSubSystemReports_this as $oSubSystemReportsThis) : ?>

  <tr>
    <td></td>

    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="color: red;" class="center"><?= $oSubSystemReportsThis->columnA ? _e($oSubSystemReportsThis->columnA) : _e($aSystemReports_prev[0]->columnA) ?></td>
    <?php
    $iCount = 0;
    ?>

    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseA : '' ?></td>
    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseB : '' ?></td>
    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseC : '' ?></td>
    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseD : '' ?></td>
    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseE : '' ?></td>
    <td class="center width15"><?= !empty($oSubSystemReportsThis) ? $oSubSystemReportsThis->faseF : '' ?></td>
    <td>
      <?php
      //$aImages = $oSystemReport->getImages();
      $sImageNr = '';
      foreach ($aImages as $oImage) {

        if (empty($oImage->getImageFileByReference('cms_thumb')->title) || _e($oImage->getImageFileByReference('cms_thumb')->title) != _e($oSubSystemReportsThis->columnA)) {
          continue;
        }
        $sMyImagesHTML .= '<div  style="width:25%; float: left; padding-bottom: 5mm;">';
        $sMyImagesHTML .= ' <img src="' . CLIENT_HTTP_URL . '/' . $oImage->getImageFileByReference('detail')->link . '" style="max-width: 65mm; max-height: 75mm;" />';
        $sImageNr .= (empty($sImageNr) ? '' : ', ') . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId);
        $sMyImagesHTML .= ' <div class="imgnr">' . ($oImage->getImageFileByReference('cms_thumb')->orgTitle ? $oImage->getImageFileByReference('cms_thumb')->orgTitle : $oImage->imageId) . '</div>';
        $sMyImagesHTML .= '</div>' . PHP_EOL;
      }
      if (!empty($sImageNr)) {

        echo $sImageNr;
      }
      ?></td>
    <?php // (isset($oSubSystemReportsThis) ? _e($oSubSystemReportsThis->notice) : '')
    ?>
  </tr>

<?php
endforeach;

if (!empty($sMyImagesHTML)) {
  $sImagesHTML .= '' . $sMyImagesHTML . '' . PHP_EOL;
}

?>