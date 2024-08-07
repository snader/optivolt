<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">

        <h1 class="m-0"><i aria-hidden="true" class="fas fa-suitcase"></i>&nbsp;&nbsp;Loggers uitzonderingsdagen</h1></i>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Uitzonderingen</h3>
            <?php if (UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) { ?>
            <div class="card-tools">
              <div class="input-group input-group-sm align-right" style="width: 50px;">

                <div class="input-group-append">
                  <a class="addBtn" href="<?= ADMIN_FOLDER . '/' . http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('add_item') ?>">
                    <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                      <i class="fas fa-plus-circle"></i>
                    </button>
                  </a>&nbsp;
                </div>

              </div>
            </div>
            <?php } ?>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th style="width: 10px;">&nbsp;</th>
                  <th>Naam</th>
                  <th>Uitzondering</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($aLoggersDays as $oLoggersDay) {
                ?>
                  <tr>
                    <td>
                      <?php

                      if ($oLoggersDay->isEditable()) {
                      ?>
                        <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oLoggersDay->loggerDayId ?>" title="Bewerken">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                      <?php } else {
                        
                      } ?>
                    </td>
                    <td><?= $oLoggersDay->name ?></td>
                    <td><?php

                        if (is_numeric($oLoggersDay->dayNumber)) {
                          echo dayOfWeek($oLoggersDay->dayNumber);
                        }
                        if (!empty($oLoggersDay->date)) {
                          echo date('d-m-Y', strtotime($oLoggersDay->date));
                        }

                        ?></td>
                    <td>

                    <td>
                      <?php
                      # online/offline
                  
                      if ($oLoggersDay->isOnlineChangeable()) {
                   
                      echo '<a id="loggersday_' . $oLoggersDay->loggerDayId . '_online_0" title="Activeren" class="btn btn-danger btn-xs ' . ($oLoggersDay->online ? 'hide' : '') . ($oLoggersDay->isOnlineChangeable() == 0 ? 'disabled ' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                        'controller'
                      ) . '/ajax-setOnline/' . $oLoggersDay->loggerDayId . '/?online=1"><i class="fas fa-eye"></i></a>';
                      echo '<a id="loggersday_' . $oLoggersDay->loggerDayId . '_online_1" title="Deactiveren" class="btn btn-success btn-xs ' . ($oLoggersDay->online ? '' : 'hide') . ($oLoggersDay->isOnlineChangeable() == 0 ? 'disabled ' : '') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                        'controller'
                      ) . '/ajax-setOnline/' . $oLoggersDay->loggerDayId . '/?online=0"><i class="fas fa-eye"></i></a>';

                      }
                      if ($oLoggersDay->isDeletable()) {
                      ?>
                        <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oLoggersDay->loggerDayId ?>" title="Verwijderen" onclick="return confirmChoice('Verwijder <?= ' ' . $oLoggersDay->name ?>');">
                          <i class="fas fa-trash"></i>
                        </a>
                      <?php } else { }?>
                    </td>
                    </td>
                  </tr>
                <?php
                }
                ?>

              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div>
</section>

<?php

$sIsOnlineMsg   = "Uitzonderingsregel geactiveerd";
$sIsOfflineMsg  = "Uitzonderingsregel gedeactiveerd";
$sIsNotChangedMsg = "Wijziging mislukt";
# add ajax code for online/offline handling
$sOnlineOfflineJavascript = <<<EOT
<script>
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: this.href,
            data: "ajax=1",
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if(dataObj.success == true){
                    $("#loggersday_"+dataObj.loggerDayId+"_online_0").hide(); // hide offline button
                    $("#loggersday_"+dataObj.loggerDayId+"_online_1").hide(); // hide online button
                    $("#loggersday_"+dataObj.loggerDayId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                     $("#loggersday_"+dataObj.loggerDayId+"_locked").html(dataObj.deonline);
                     $("#loggersday_"+dataObj.loggerDayId+"_reason").html(dataObj.reason);
                    if(dataObj.online == 1)
                        showStatusUpdate("$sIsOnlineMsg");
                    if(dataObj.online == 0)
                        showStatusUpdate("$sIsOfflineMsg");
                }else{
                        showStatusUpdate("$sIsNotChangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sOnlineOfflineJavascript);
