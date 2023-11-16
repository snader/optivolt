<?php
$bShowCustomer = true;
$bShowLocation = true;
?>
<?php
$bShowAddButton = false;
$bShowToCustomerButton = false;
$sOptionHTML = '';
foreach ($aAllCustomers as $oCustomer) {
    if (isset($aSystemFilter['customerId']) && is_numeric($aSystemFilter['customerId']) && $aSystemFilter['customerId'] == $oCustomer->customerId) {
        $bShowAddButton = true;
        $bShowCustomer = false;
        $bShowToCustomerButton = true;
    }
    $sOptionHTML .= '<option ' . ((isset($aSystemFilter['customerId']) && ($aSystemFilter['customerId'] == $oCustomer->customerId)) ? 'selected' : '') . ' value="' . $oCustomer->customerId . '">' . $oCustomer->companyName . '</option>';
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <?php
                if ($bShowToCustomerButton) {
                ?>
                    <span class="float-right pl-1">
                        <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $aSystemFilter['customerId'] ?>">
                            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                                Naar klant
                            </button>
                        </a>
                    </span>
                <?php
                }
                ?>
                <h1 class="m-0"><i aria-hidden="true" class="fa fa-shapes fa-th-large"></i>&nbsp;&nbsp;<?= sysTranslations::get('system_all') ?></h1>

            </div>

        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title d-none d-sm-block"><?= sysTranslations::get('select_customer') ?></h3>

                    <div class="card-tools">

                        <div class="input-group input-group-sm" style="width: auto;">

                            <div class="input-group-append">
                                <form action="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>" method="POST" class="form-inline pr-2">
                                    <input type="hidden" name="filterForm" value="1" />

                                    <select class="select2 form-control form-control-sm" name="systemFilter[customerId]">
                                        <option>Selecteer klant</option>
                                        <?= $sOptionHTML ?>
                                    </select>&nbsp;
                                    <input type="submit" name="filterSystems" value="Filter" class="btn btn-default btn-sm" /> <input class="btn btn-default btn-sm" type="submit" name="resetFilter" value="Reset" />
                                </form>
                            </div>
                            <?php
                            if ($bShowAddButton) { ?>
                                <div class="input-group-append">
                                    <a class="addBtn" href="<?= ADMIN_FOLDER . '/' . http_get('controller') ?>/toevoegen?customerId=<?= $aSystemFilter['customerId'] ?>" title="<?= sysTranslations::get('add_item') ?>">
                                        <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    </a>&nbsp;
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <?php
                    
                    require getAdminSnippet('systems_list', 'systems');
                    ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>

</div>


<?php

$sIsOnlineMsg   = sysTranslations::get('system_is_online');
$sIsOfflineMsg  = sysTranslations::get('system_is_offline');
$sNotChangedMsg = sysTranslations::get('system_not_changed');
# add ajax code for online/offline handling
$sBottomJavascript = <<<EOT
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
                    $("#systems_"+dataObj.systemId+"_online_0").hide(); // hide offline button
                    $("#systems_"+dataObj.systemId+"_online_1").hide(); // hide online button
                    $("#systems_"+dataObj.systemId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)
                        showStatusUpdate("$sIsOfflineMsg");
                    if(dataObj.online == 1)
                        showStatusUpdate("$sIsOnlineMsg");
                }else{
                        showStatusUpdate("$sNotChangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>



<script>
// "copy", "csv", "excel", "pdf", "print",
  $(function () {
    $(".data-table").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["colvis"],
      "columnDefs": [ {
        "searchable": false,
        "orderable": false,
        "targets": [0, 6]
    } ],
    "order": [[ 1, 'asc' ]]
    }).buttons().container().appendTo('#customers_wrapper .col-md-6:eq(0)');

  });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
