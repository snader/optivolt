<table style="width:100%;">
  <tr>
    <td colspan="2" style="width:70%">
      <h1>Optivolt - Onderhoudsformulier</h1>
    </td>
    <td colspan="2" style="width:30%; text-align:right;">
      <img class="logo" src="<?= DOCUMENT_ROOT ?>/themes/default/images/optivolt-logo.png">

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
    <td><?= $oCustomer->companyPostalCode ?> <?= $oCustomer->companyCity ?></td>
    <td>Datum onderhoud</td>
    <td><?= date('d-m-Y', strtotime($oAppointment->visitDate)) ?></td>
  </tr>
  <tr>
    <td>Monteur</td>
    <td><?= $oUser->getDisplayName() ?></td>
    <td>Ordernummer</td>
    <td><?= _e($oAppointment->orderNr) ?></td>
  </tr>
</table>
<br />

<?php
$iPrevYear = date('Y', strtotime($oAppointment->visitDate)) - 1;
$iYear = date('Y', strtotime($oAppointment->visitDate));
?>

<table style="width:100%;">

  <?php

  $sImagesHTML = '';
  $iCnt = 0;
  $iImagesCount = 0;

  $iPrevSystemType = 0;
  foreach ($aSystems as $oSystem) {

    // switch header/table info when type changes
    //if (($oSystem->systemTypeId==System::SYSTEM_TYPE_VLINER && $iPrevSystemType!=System::SYSTEM_TYPE_VLINER) || ($oSystem->systemTypeId != System::SYSTEM_TYPE_VLINER && $iPrevSystemType == System::SYSTEM_TYPE_VLINER)) {

    if ($oSystem->systemTypeId != $iPrevSystemType) {
      require getAdminSnippet('pdfTableHeader_' . $oSystem->systemTypeId, 'customers');
      $iPrevSystemType = $oSystem->systemTypeId;
    }

    $iCnt++;

    require getAdminSnippet('pdfTableMain_' . $oSystem->systemTypeId, 'customers');
  }


  ?>

</table>

<table style="width:100%;">
  <tr>
    <td style="width:60%;"> </td>
    <td style="width:30%;padding: 5mm 0 0 5mm;">
      <?php
      //echo 'Voltooid op ' . date('d-m-Y', strtotime($oAppointment->modified)) . ' door ' . $oUser->getDisplayName() . '<br/>';
      if (!empty($oAppointment->signatureName)) {
        echo 'Naam aftekening: ' . $oAppointment->signatureName . '<br/>';
      }
      echo 'Handtekening voor gezien:<br/>';

      ?>

      <img src='<?= DOCUMENT_ROOT ?>/private_files/signatures/<?= $oAppointment->signature ?>.png' style="max-width: 65mm; max-height: 75mm;">

    </td>
  </tr>
</table>

<pagebreak></pagebreak>

<?php

echo $sImagesHTML
?>