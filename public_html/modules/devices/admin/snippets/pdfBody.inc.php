<div style="text-align:center;"><img class="logo" src="<?= DOCUMENT_ROOT ?>/themes/default/images/optivolt-logo.png"></div>
<br /><br />
<?= _e(Settings::get('clientName')) ?><br />
        <?= _e(Settings::get('clientStreet')) ?><br />
        <?= _e(Settings::get('clientPostalCode')) ?> <?= _e(Settings::get('clientCity')) ?><br />
        Telefoon: <?= _e(Settings::get('clientPhone')) ?><br />
        www.optivolt.nl<br />
        <?= _e(Settings::get('clientEmail')) ?><br />
        KvK: 52474607<br />
        <br />
<h1>Testcertificaat</h1></td>
<br />
  
<table style="width:100%;">
    <tr>
        <td style="width:50%;">Bedrijfsnaam</td>  
        <td><?= _e(Settings::get('clientName')) ?></td>
    </tr>
    <tr>
        <td>Keurmeester</td>  
        <td><?= _e($oUser->name) ?></td>
    </tr>
</table>
<br />

<table style="width:100%;">
    <tr>
        <td style="width:25%;">Apparaat:</td>  
        <td style="width:25%;"><?= _e($oDevice->name) ?></td>
        <td style="width:25%;">Getest door:</td>  
        <td style="width:25%;"><?= _e($oUser->name) ?></td>  
    </tr>
    <tr>
        <td style="width:25%;">Merk:</td>  
        <td style="width:25%;"><?= _e($oDevice->brand) ?></td>
        <td style="width:25%;">VBB nummer:</td>  
        <td style="width:25%;"><?= _e($oCertificate->vbbNr) ?></td> 
    </tr>
    <tr>
        <td style="width:25%;">Type:</td>  
        <td style="width:25%;"><?= _e($oDevice->type) ?></td>
        <td style="width:25%;">Test instrument:</td>  
        <td style="width:25%;"><?= _e($oCertificate->testInstrument) ?></td> 
    </tr>
    <tr>
        <td style="width:25%;">Serienummer:</td>  
        <td style="width:25%;"><?= _e($oDevice->serial) ?></td>
        <td style="width:25%;">Serienummer:</td>  
        <td style="width:25%;"><?= _e($oCertificate->testSerialNr) ?></td> 
    </tr>
    <tr>
        <td style="width:25%;">Test datum:</td>  
        <td style="width:25%;"><?= _e($oDevice->name) ?></td>
        <td style="width:25%;">Volgende keuring:</td>  
        <td style="width:25%;"><?= !empty($oCertificate->nextcheck) ? date('d-m-Y', strtotime($oCertificate->nextcheck)) : '' ?></td> 
    </tr>
</table>

<br />

<table style="width:100%;">
  <tr>
    <td style="width:50%;">1. Visuele controle:</td>  
    <td><?= _e($oCertificate->visualCheck) ?></td>
  </tr>
  <tr>
    <td>2. Weerstand bescherming leiding RPE:</td>
    <td><?= _e($oCertificate->weerstandBeLeRPE) ?></td>
  </tr>
  <tr>
    <td>3. Isolatie Weerstand RISO:</td>
    <td><?= _e($oCertificate->isolatieWeRISO) ?></td>
  </tr>
  <tr>
    <td>4. Lekstroom via bescherming leiding IEA:</td>
    <td><?= _e($oCertificate->lekstroomIEA) ?></td>
  </tr>
  <tr>
    <td>5. Lekstroom door aanraking:</td>
    <td><?= _e($oCertificate->lekstroomTouch) ?></td>
  </tr>
</table>

