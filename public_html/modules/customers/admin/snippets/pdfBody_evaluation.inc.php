<div style="text-align:center;"><img class="logo" src="<?= DOCUMENT_ROOT ?>/themes/default/images/optivolt-logo.png"></div>
<br /><br />
<h1>Evaluatie / Evaluation</h1>



<?= _e($oCustomer->companyName) ?><br />
<?= _e($oCustomer->companyAddress) ?><br />
<?= _e($oCustomer->companyPostalCode) ?> <?= _e($oCustomer->companyCity) ?><br />
        Contactpersoon: <?= _e($oCustomer->contactPersonName) ?><br />
        E-mailadres: <?= _e($oCustomer->contactPersonEmail) ?><br />        
        Telefoon: <?= _e($oCustomer->contactPersonPhone) ?><br /><br />
        <br />
</td>
<br />
  
<table style="width:100%;">
    <tr>
        <td style="width:70%;">Is de installatie naar tevredenheid verlopen?<br><i>Was the installation satisfactory?</i></td>  
        <td><?= $oEvaluation->installSat ? 'Ja / Yes' : 'Nee / No' ?></td>
    </tr>
    <tr>
      <td style="width:70%;">Zijn er nog bijzonderheden?<br><i>Are there any details?</i></td>  
      <td><?= $oEvaluation->anyDetails ? 'Ja / Yes' : 'Nee / No' ?></td>
    </tr>
    <tr>
      <td style="width:70%;">Zijn de MultiLiners/PowerLiners aangesloten en gemeten?<br><i>Are the MultiLiners/PowerLiners connected and measured?</i></td>  
      <td><?= $oEvaluation->conMeasured ? 'Ja / Yes' : 'Nee / No' ?></td>
    </tr>
    <tr>
      <td style="width:70%;">Waren de voorbereidingen goed uitgevoerd?<br><i>Were the preparations well done?</i></td>  
      <td><?= $oEvaluation->prepSat ? 'Ja / Yes' : 'Nee / No' ?></td>
    </tr>
    <tr>
      <td style="width:70%;">Zijn de werkzaamheden naar tevredenheid uitgevoerd?<br><i>Was the work performed satisfactorily?</i></td>  
      <td><?= $oEvaluation->workSat ? 'Ja / Yes' : 'Nee / No' ?></td>
    </tr>
    <tr>
      <td style="width:70%;">Zijn eventuele vragen beantwoord?<br><i>Have your questions been answered?</i></td>  
      <td><?= $oEvaluation->answers ? 'Ja / Yes' : 'Nee / No' ?></td>
    </tr>
    <tr>
      <td style="width:70%;">Waren de monteurs vriendelijk en behulpzaam<br><i>Were the fitters friendly and helpful?</i></td>  
      <td><?= $oEvaluation->friendlyHelpfull ? 'Ja / Yes' : 'Nee / No' ?></td>
    </tr>
    <tr>
      <td style="width:70%;">Welk cijfer (tussen 1 – 10) zou u OptiVolt geven?</i></td>  
      <td><?= $oEvaluation->grade ? $oEvaluation->grade : '' ?></td>
    </tr>
    <tr><td colspan="2">
      Opmerkingen<br><i>Remarks</i><br><br>
      <?= _e($oEvaluation->remarks); ?>

    </td></tr>
</table>
<br />
<table style="width:50%;">
    <tr>
      <td>Naam relatie<br><i>Name relationship</i></td>
      <td><?= _e($oEvaluation->nameSigned); ?></td>
    </tr>
    <tr>
      <td>Datum<br><i>Date</i></td>
      <td><?= _e(date('d-m-Y', strtotime($oEvaluation->dateSigned))); ?></td>
    </tr>
    <tr>
      <td>Digitaal ondertekend<br><i>Digitally signed</i></td>
      <td><h1>✓</h1></td>
    </tr>
</table>    
<bR>



