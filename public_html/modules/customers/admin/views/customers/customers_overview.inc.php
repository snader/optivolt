<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;<?= sysTranslations::get('customer_collapse') ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= sysTranslations::get('global_customers') ?></h3>
                    <div class="card-tools">

                        <?php
                        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
                        ?>
                            <div class="input-group input-group-sm" style="width: auto;">

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
                                <?php
                                if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) { ?>
                                    <th>Deb#</th>
                                <?php } ?>
                                <th><?= sysTranslations::get('global_company_name') ?></th>
                                <th><?= sysTranslations::get('global_address') ?></th>
                                <th><?= sysTranslations::get('global_city') ?></th>
                                <th>E-mail (contact)</th>
                                <th>Tel# (contact)</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($aAllCustomers as $oCustomer) {
                                echo '<tr>';
                            ?>
                                <td class="align-middle">
                                    <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomer->customerId ?>" title="<?= sysTranslations::get('customer_edit') ?>">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                                <?php
                                if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
                                    echo '<td >' . _e($oCustomer->debNr) . '</td>';
                                }
                                echo '<td >' . _e($oCustomer->companyName) . '</td>';
                                echo '<td nowrap>' . _e($oCustomer->companyAddress) . '</td>';
                                echo '<td nowrap>' . _e($oCustomer->companyCity) . '</td>';
                                echo '<td nowrap><a target="_blank" href="mailto:' . _e($oCustomer->contactPersonEmail) . '">' . _e($oCustomer->contactPersonEmail) . '</a></td>';
                                echo '<td nowrap>' . _e($oCustomer->contactPersonPhone) . '</td>';

                                ?>
                                <td nowrap class="align-middle">
                                    <?php

                                    if ($oCustomer->isOnlineChangeable()) {

                                        # online/offline

                                        echo '<a id="customer_' . $oCustomer->customerId . '_online_1" title="' . sysTranslations::get(
                                            'user_set_deactivation'
                                        ) . '" class="btn btn-success btn-xs ' . ($oCustomer->online ? '' : 'hide') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                                            'controller'
                                        ) . '/ajax-setOnline/' . $oCustomer->customerId . '/?online=0&' . CSRFSynchronizerToken::query() . '"><i class="fas fa-eye"></i></a>';
                                        echo '<a id="customer_' . $oCustomer->customerId . '_online_0" title="' . sysTranslations::get(
                                            'user_set_activation'
                                        ) . '" class="btn btn-secondary btn-xs ' . ($oCustomer->online ? 'hide' : '') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                                            'controller'
                                        ) . '/ajax-setOnline/' . $oCustomer->customerId . '/?online=1&' . CSRFSynchronizerToken::query() . '"><i class="fas fa-eye"></i></a>';
                                        echo '&nbsp;';
                                    }
                                    if ($oCustomer->isDeletable()) {
                                    ?>
                                        <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oCustomer->customerId . '?' . CSRFSynchronizerToken::query() ?>" title="<?= sysTranslations::get('user_delete') ?>" onclick="return confirmChoice('<?= strtolower(sysTranslations::get('user_user') ?? '') . ' ' . $oCustomer->companyName ?>');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php } ?>

                                </td>
                            <?php

                                echo '</tr>';
                            }
                            if (empty($aAllCustomers)) {
                                echo '<tr><td colspan="7"><i>' . sysTranslations::get('customer_no_customers') . '</i></td></tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 10px;">&nbsp;</th>
                                <?php
                                if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) { ?>
                                    <th>Deb#</th>
                                <?php } ?>
                                <th><?= sysTranslations::get('global_company_name') ?></th>
                                <th><?= sysTranslations::get('global_address') ?></th>
                                <th><?= sysTranslations::get('global_city') ?></th>
                                <th><?= sysTranslations::get('global_email') ?></th>
                                <th><?= sysTranslations::get('global_phone') ?></th>
                                <th></th>

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
$sCustomersOnline           = sysTranslations::get('customer_online');
$sCustomersOffline          = sysTranslations::get('customer_offline');
$sCustomersStatusNotChanged = sysTranslations::get('customer_status_not_changed');

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
                    $("#customer_"+dataObj.customerId+"_online_0").hide(); // hide offline button
                    $("#customer_"+dataObj.customerId+"_online_1").hide(); // hide online button
                    $("#customer_"+dataObj.customerId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)
                        showStatusUpdate("$sCustomersOffline");
                    if(dataObj.online == 1)
                        showStatusUpdate("$sCustomersOnline");
                } else {
                    showStatusUpdate("$sCustomersStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>


<script>
// "copy", "csv", "excel", "pdf", "print", // .buttons().container().appendTo('#customers_wrapper .col-md-6:eq(0)')
  $(function () {
    $(".data-table").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false,  "scrollX": true,
      "stateSave": true,
      "buttons": ["colvis"],
      "columnDefs": [ {
        "searchable": false,
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