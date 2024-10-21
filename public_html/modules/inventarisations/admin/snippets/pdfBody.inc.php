<table style="width:100%;">
  <tr>
    <th colspan="3"><?= ($oInventarisation->customerName ? _e($oInventarisation->customerName) : CustomerManager::getCustomerById($oInventarisation->customerId)->companyName) ?></th>
    <th colspan="3">Optivolt - <?= Usermanager::getUserById($oInventarisation->userId)->name ?> - <?= date('d-m-Y', strtotime($oInventarisation->created)) ?></th>
  </tr>
  <tr>
    <th style="width:20%">Transformator naam/nr</th>
    <th style="width:6%">kVA/A</th>
    <th style="width:6%">Logger</th>
    <th style="width:19%">Remark</th>
    <th style="width:20%">Vrij veld?</th>
    <th style="width:10%">Stroomtrafo</th>
  </tr>
  <?php
  
  // Table 1 - SUB systemReports here with parentID = $oInventarisation->inventarisationId
  if (isset($aInventarisations) && !empty($aInventarisations)) {

    foreach ($aInventarisations as $oSubInventarisation) {

        if (empty($oSubInventarisation->name) && 
            empty($oSubInventarisation->kva) && 
            empty($oSubInventarisation->loggerId) && 
            empty($oSubInventarisation->position) && 
            empty($oSubInventarisation->freeFieldAmp) && 
            empty($oSubInventarisation->stroomTrafo) 
            ) {
                continue;
            }
      ?>
      
      <tr>
        
          <td valign="top"><?= _e($oSubInventarisation->name) ?></td>  
          <td valign="top"><?= _e($oSubInventarisation->kva) ?></td>
          <td valign="top"><?= $oSubInventarisation->loggerId ? LoggerManager::getLoggerById($oSubInventarisation->loggerId)->name : '' ?></td>
          <td valign="top"><?= _e($oSubInventarisation->position) ?></td>
          <td valign="top"><?= (str_replace("|", "<br />", $oSubInventarisation->freeFieldAmp)) ?></td>
          <td valign="top"><?= _e($oSubInventarisation->stroomTrafo) ?></td>
      
        </tr>
      <?php
    }
  }
  ?>

  
</table>
<br />
<table style="width:90%;">
  <tr class="row">
      <th style="width:20%">Type engine</th>
      <th style="width:7%">Control</th>
      <th style="width:14%">Relais#</th>
      <th style="width:15%">KW Engine+30kW</th>
      <th style="width:15%">Turning hours</th>
      <th style="width:20%">Remark</th>
      <th style="width:11%">Trafo#</th>
      
  </tr>

  <?php
    // Table 2 - SUB systemReports here with parentID = $oInventarisation->inventarisationId
    if (isset($aInventarisations) && !empty($aInventarisations)) {

        foreach ($aInventarisations as $oSubInventarisation) {

            if (empty($oSubInventarisation->type) && 
                empty($oSubInventarisation->control) && 
                empty($oSubInventarisation->relaisNr) && 
                empty($oSubInventarisation->engineKw) && 
                empty($oSubInventarisation->turningHours) && 
                empty($oSubInventarisation->photoNrs) && 
                empty($oSubInventarisation->trafoNr) 

                ) {
                    continue;
                }
    ?>
        
        <tr>
            
            
            <td><?= _e($oSubInventarisation->type) ?></td>  
            <td><?= $oSubInventarisation->control ?></td>
            <td><?= _e($oSubInventarisation->relaisNr) ?></td>
            <td><?= _e($oSubInventarisation->engineKw) ?></td>
            <td><?= _e($oSubInventarisation->turningHours) ?></td>
            <td><?= _e($oSubInventarisation->photoNrs) ?></td>
            <td><?= _e($oSubInventarisation->trafoNr) ?></td>
            
        
        </tr>
    <?php
        }
    }
    ?> 


</table>
<br />
<table style="width:100%;">
  <tr>
    <th>Extra notes/remarks</th>
  </tr>    
  <tr>
    <td><?= nl2br(strip_tags($oInventarisation->remarks));?></td>
  </tr>    
  </table>
<br />
<?php

?>