<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">

        <h1 class="m-0"><i aria-hidden="true" class="fas fa-suitcase"></i>&nbsp;&nbsp;Loggers defaults</h1></i>
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
            <h3 class="card-title">Standaard periodes</h3>

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
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th style="width: 10px;">&nbsp;</th>
                  <th>Naam</th>
                  <th>Dagen</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($aLoggersDefaults as $oLoggersDefault) {
                ?>
                  <tr>
                    <td>
                      <?php

                      if ($oLoggersDefault->isEditable()) {
                      ?>
                        <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oLoggersDefault->loggerDefaultId ?>" title="Bewerken">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                      <?php } else {
                        echo '<i class="fas fa-pencil-alt"></i>';
                      } ?>
                    </td>
                    <td><?= $oLoggersDefault->name ?></td>
                    <td><?= $oLoggersDefault->days ?></td>
                    <td>

                    <td>
                      <?php
                      # online/offline

                      echo '<a id="loggerdefault_' . $oLoggersDefault->loggerDefaultId . '_online_0" title="Activeren" class="btn btn-danger btn-xs ' . ($oLoggersDefault->online ? 'hide' : '') . ($oLoggersDefault->isOnlineChangeable() == 0 ? 'disabled ' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                        'controller'
                      ) . '/ajax-setOnline/' . $oLoggersDefault->loggerDefaultId . '/?online=1"><i class="fas fa-eye"></i></a>';
                      echo '<a id="loggerdefault_' . $oLoggersDefault->loggerDefaultId . '_online_1" title="Deactiveren" class="btn btn-success btn-xs ' . ($oLoggersDefault->online ? '' : 'hide') . ($oLoggersDefault->isOnlineChangeable() == 0 ? 'disabled ' : '') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                        'controller'
                      ) . '/ajax-setOnline/' . $oLoggersDefault->loggerDefaultId . '/?online=0"><i class="fas fa-eye"></i></a>';

                      ?>

                      <?php
                      if ($oLoggersDefault->isDeletable()) {
                      ?>
                        <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oLoggersDefault->loggerDefaultId ?>" title="Verwijderen" onclick="return confirmChoice('Verwijder <?= ' ' . $oLoggersDefault->name ?>');">
                          <i class="fas fa-trash"></i>
                        </a>
                      <?php } else { ?><span class="btn btn-danger btn-xs disabled"><i class="fas fa-trash"></i></span><?php } ?>
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

$sIsOnlineMsg   = "Standaard periode geactiveerd";
$sIsOfflineMsg  = "Standaard periode gedeactiveerd";
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
                    $("#loggerdefault_"+dataObj.loggerDefaultId+"_online_0").hide(); // hide offline button
                    $("#loggerdefault_"+dataObj.loggerDefaultId+"_online_1").hide(); // hide online button
                    $("#loggerdefault_"+dataObj.loggerDefaultId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                     $("#loggerdefault_"+dataObj.loggerDefaultId+"_locked").html(dataObj.deonline);
                     $("#loggerdefault_"+dataObj.loggerDefaultId+"_reason").html(dataObj.reason);
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
