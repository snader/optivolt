<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0"><i aria-hidden="true" class="fa fa-plug "></i>&nbsp;&nbsp;<?= sysTranslations::get('devices_menu') ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= sysTranslations::get('devices_menu') ?> & certificaten</h3>
                    <div class="card-tools">

                        <?php
                        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
                        ?>
                            <div class="input-group input-group-sm" style="width: auto;">

                                <div class="input-group-append">
                                    <form action="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>" method="POST" class="form-inline pr-2">
                                        <input type="hidden" name="filterForm" value="1" />

                                        <select class="select2 form-control form-control-sm" style="width:200px;" name="devicesFilter[deviceGroupId]">
                                            <option>Selecteer groep</option>
                                            <?= $sOptionHTML ?>
                                        </select>&nbsp;
                                        <input type="submit" name="filterDevices" value="Filter" class="btn btn-default btn-sm" /> <input class="btn btn-default btn-sm" type="submit" name="resetFilter" value="Reset" />
                                    </form>
                                </div>
                                <div class="input-group-append">
                                    <a class="addBtn" href="<?= ADMIN_FOLDER . '/' . http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('add_item') ?>">
                                        <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    </a>&nbsp;
                                </div>


                            </div>
                        <?php } ?>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-striped data-table display">
                        <thead>
                            <tr>
                                <th style="width: 10px;">&nbsp;</th>
                                <th>Apparaat</th>
                                <th>Merk</th>
                                <th>Type</th>
                                <th>Serienummer</th>    
                                <th>Testdatum</th>                            
                                <th style="width: 30px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($aAllDevices as $oDevice) {
               
                                echo '<tr>';
                            ?>
                                <td class="align-middle">
                                    <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oDevice->deviceId ?>" title="<?= sysTranslations::get('device_edit') ?>">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                                <?php

                                echo '<td >' . _e($oDevice->name) . '</td>';
                                echo '<td nowrap>' . _e($oDevice->brand) . '</td>';
                                echo '<td nowrap>' . _e($oDevice->type) . '</td>';                                
                                echo '<td nowrap>' . _e($oDevice->serial) . '</td>';
                                echo '<td nowrap>' . ($oDevice->created != '' ? _e(date('d-m-Y',strtotime($oDevice->created))) : '') . '</td>';

                                ?>
                                <td nowrap class="align-middle">
                                    <?php

                                    if ($oDevice->isOnlineChangeable()) {

                                        # online/offline

                                        echo '<a id="device_' . $oDevice->deviceId . '_online_1" title="' . sysTranslations::get(
                                            'user_set_deactivation'
                                        ) . '" class="btn btn-success btn-xs ' . ($oDevice->online ? '' : 'hide') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                                            'controller'
                                        ) . '/ajax-setOnline/' . $oDevice->deviceId . '/?online=0&' . CSRFSynchronizerToken::query() . '"><i class="fas fa-eye"></i></a>';
                                        echo '<a id="device_' . $oDevice->deviceId . '_online_0" title="' . sysTranslations::get(
                                            'user_set_activation'
                                        ) . '" class="btn btn-secondary btn-xs ' . ($oDevice->online ? 'hide' : '') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                                            'controller'
                                        ) . '/ajax-setOnline/' . $oDevice->deviceId . '/?online=1&' . CSRFSynchronizerToken::query() . '"><i class="fas fa-eye"></i></a>';
                                        echo '&nbsp;';
                                    }
                                    if ($oDevice->isDeletable()) {
                                    ?>
                                        <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oDevice->deviceId . '?' . CSRFSynchronizerToken::query() ?>" title="<?= sysTranslations::get('user_delete') ?>" onclick="return confirmChoice('<?= strtolower(sysTranslations::get('user_user') ?? '') . ' ' . $oDevice->companyName ?>');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php } ?>

                                </td>
                            <?php

                                echo '</tr>';
                            }
                            if (empty($aAllDevices)) {
                                echo '<tr><td colspan="7"><i>Geen apparaten gevonden</i></td></tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 10px;">&nbsp;</th>                                                        
                                <th>Apparaat</th>
                                <th>Merk</th>
                                <th>Type</th>
                                <th>Serienummer</th>                                
                                <th>Testdatum</th>   
                                <th style="width: 30px;">&nbsp;</th>

                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>

</div>

<?php


# create necessary javascript
$sController                = http_get('controller');
$sDevicesOnline           = sysTranslations::get('device_online');
$sDevicesOffline          = sysTranslations::get('device_offline');
$sDevicesStatusNotChanged = sysTranslations::get('device_status_not_changed');

$sBottomJavascript = <<<EOT
<script type="text/javascript">
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: $(this).prop('href'),
            data: 'ajax=1',
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $("#device_"+dataObj.deviceId+"_online_0").hide(); // hide offline button
                    $("#device_"+dataObj.deviceId+"_online_1").hide(); // hide online button
                    $("#device_"+dataObj.deviceId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)
                        showStatusUpdate("$sDevicesOffline");
                    if(dataObj.online == 1)
                        showStatusUpdate("$sDevicesOnline");
                } else {
                    showStatusUpdate("$sDevicesStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>


<script>

  $(function () {
    $(".data-table").DataTable({
      "responsive": false, "lengthChange": true, "autoWidth": false,  "scrollX": false,
      "stateSave": true,
      "buttons": ["colvis"],
      "columnDefs": [ {
        "searchable": true,
        "orderable": false,
        "targets": [0, 6]
    } ],
    "order": [[ 1, 'asc' ]]
    });

  });


</script>

EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>