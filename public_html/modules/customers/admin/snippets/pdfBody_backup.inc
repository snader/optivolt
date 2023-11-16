<table style="width:100%;">
  <tr>
    <td colspan="2" style="width:70%">
      <h1>Optivolt - Onderhoudsformulier</h1>
    </td>
    <td colspan="2" style="width:30%; text-align:right;">
      <img class="logo" src="<?= $_SERVER["DOCUMENT_ROOT"] ?>/themes/default/images/optivolt-logo.png">

    </td>
  </tr>
  <tr>
    <td style="width:20%">Projectnummer</td>
    <td style="width:30%"><?= $oCustomer->debNr ?></td>
    <td style="width:20%">Contactpersoon</td>
    <td style="width:30%"><?= $oCustomer->contactPersonName ?></td>
  </tr>
  <tr>
    <td>Naam</td>
    <td><?= $oCustomer->companyName ?></td>
    <td>Telefoon</td>
    <td><?= $oCustomer->companyPhone ?></td>
  </tr>
  <tr>
    <td>Adres</td>
    <td><?= $oCustomer->companyAddress ?></td>
    <td>E-mail</td>
    <td><?= $oCustomer->companyEmail ? $oCustomer->companyEmail : $oCustomer->contactPersonEmail ?></td>
  </tr>
  <tr>
    <td>PC/Woonplaats</td>
    <td><?= $oCustomer->companyPostalCode ?></td>
    <td>Datum onderhoud</td>
    <td><?= $oAppointment->visitDate ?></td>
  </tr>
  <tr>
    <td>Monteur</td>
    <td><?= $oUser->getDisplayName() ?></td>
    <td></td>
    <td></td>
  </tr>
</table>
<br />

<?php
$iPrevYear = date('Y', strtotime($oAppointment->visitDate)) - 1;
$iYear = date('Y', strtotime($oAppointment->visitDate));
?>

<table style="width:100%;">
  <tr>
    <th colspan="6"> </th>
    <th colspan="3"><?= $iPrevYear ?></th>
    <th colspan="3"><?= $iYear ?></th>
    <th colspan="2"> </th>
  </tr>
  <tr>
    <th colspan="6"> </th>
    <th>uF<br />meting</th>
    <th>uF<br />meting</th>
    <th>uF<br />meting</th>
    <th>uF<br />meting</th>
    <th>uF<br />meting</th>
    <th>uF<br />meting</th>
    <th><br />Foto</th>
  </tr>
  <tr>
    <th>Pos.</th>
    <th>Locatie</th>
    <th>Naam</th>
    <th>Model</th>
    <th>Machinenr.</th>
    <th>380 / 400</th>
    <th>fase 1</th>
    <th>fase 2</th>
    <th>fase 3</th>
    <th>fase 1</th>
    <th>fase 2</th>
    <th>fase 3</th>
    <th>Nr</th>
    <th>Opmerking</th>
  </tr>
  <?php

  $sImagesHTML = '';
  $iCnt = 0;
  $iImagesCount = 0;

  foreach ($aSystems as $oSystem) {

    $iCnt++;

    $sImageNr = '';
    if (!empty($aSystemReports)) {
      $aImages = $aSystemReports[0]->getImages();

      if (!empty($aImages)) {
        $sImagesHTML .= '<div class="imageDiv">' . PHP_EOL;

        foreach ($aImages as $oImage) {
          $sImagesHTML .= ' <img src="' . $_SERVER["DOCUMENT_ROOT"] . '/' . $oImage->getImageFileByReference('detail')->link . '" class="photo" />' . PHP_EOL;
          $sImageNr .= (empty($sImageNr) ? '' : '_') . $oImage->imageId;
        }
        $sImagesHTML .= ' <div class="imgnr">' . $sImageNr . '</div>' . PHP_EOL;
        $sImagesHTML .= '</div>' . PHP_EOL;
      }
    }


  ?>
    <tr>
      <td class="center width5"><?= $oSystem->pos ?></td>
      <td><?= _e($oSystem->locationName); ?></td>
      <td><?= _e($oSystem->name) ?></td>
      <td><?= _e($oSystem->model) ?></td>
      <td style="color: red;" class="center"><?= _e($oSystem->machineNr) ?></td>
      <td class="center width15"><?= _e($oSystem->columnA) ?>

        <?php
        $aSystemReports = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId, 'year' => $iPrevYear]);
        ?>
      <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseA : '' ?></td>
      <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseB : '' ?></td>
      <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseC : '' ?></td>

      <?php
      $aSystemReports = SystemReportManager::getSystemReportsByFilter(['systemId' => $oSystem->systemId, 'year' => $iYear]);
      ?>
      <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseA : '' ?></td>
      <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseA : '' ?></td>
      <td class="center width15"><?= !empty($aSystemReports) ? $aSystemReports[0]->faseA : '' ?></td>

      <td class="center"><?= $sImageNr ?></td>
      <td><?= _e($oSystem->notice) ?></td>
    </tr>



  <?php
  }


  ?>

</table>
<pagebreak></pagebreak>

<?= $sImagesHTML ?>