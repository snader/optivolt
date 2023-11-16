<?php
$bShowCustomer = true;
$bShowLocation = true;
?>
<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title d-none d-sm-block">Planning onderhoud van <?= date('d-m-Y', time()) ?></h3>

                    <div class="card-tools">

                        <div class="input-group input-group-sm" style="width: auto;">


                        </div>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <table class="table table-bordered table-striped data-table display" id="dashboardTable">
                        <thead>
                            <tr>
                                <th style="width: 10px;"></th>
                                <th>Datum</th>
                                <th style="width: 10px;"><i class="far fa-check-circle"></i></th>
                                <th><?= sysTranslations::get('global_company_name') ?></th>
                                <th><?= sysTranslations::get('global_address') ?></th>
                                <th><?= sysTranslations::get('global_city') ?></th>
                                <th><?= sysTranslations::get('global_phone') ?></th>
                                <th style="cursor:help;" title="Aantal locaties (aantal voltooid / aantal systemen)">Loc/Sys</th>


                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($aAllCustomers as $oCustomer) {

                                $iSystems = 0;
                                $iSystemsFinished = 0;
                                $aLocations = $oCustomer->getLocations();
                                foreach ($aLocations as $oLocation) {
                                    $iSystems = $iSystems + $oLocation->countSystems();
                                    $iSystemsFinished = $iSystemsFinished + $oLocation->countSystems(date('Y', time()));
                                }

                                echo '<tr>';
                            ?>
                                <td class="align-middle">
                                    <a class="btn btn-info btn-md" href="<?= ADMIN_FOLDER . '/klanten/bewerken/' . $oCustomer->customerId ?>" title="<?= sysTranslations::get('customer_edit') ?>">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            <?php
                                echo '<td width="10" nowrap class="' . ((days_from_now($oCustomer->visitDate) < 0 && !$oCustomer->finished) ? 'text-danger' : '') . ($oCustomer->finished ? 'text-success' : '') . ($oCustomer->visitDate == date('Y-m-d') && !$oCustomer->finished ? 'text-warning' : '') . '" >' . date('d-m-Y', strtotime($oCustomer->visitDate)) . '</td>';
                                echo '<td style="width: 10px;text-align:center;">' . ($oCustomer->finished ? ' <i class="far fa-check-circle text-success"></i>' : '') . '</td>';
                                echo '<td >' . _e($oCustomer->companyName) . '</td>';
                                echo '<td nowrap>' . _e($oCustomer->companyAddress) . '</td>';
                                echo '<td nowrap>' . _e($oCustomer->companyCity) . '</td>';
                                echo '<td nowrap>' . _e($oCustomer->companyPhone) . '</td>';
                                echo '<td style="text-align:center;" nowrap ' . ($iSystemsFinished != $iSystems ? 'class="bg-danger"' : '') . '>' . _e($oCustomer->locations) . ' (' . $iSystemsFinished . '/' . $iSystems . ')' . '</td>';

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
                                <th>Datum</th>
                                <th style="width: 10px;text-align:center;"><i class="far fa-check-circle"></i></th>
                                <th><?= sysTranslations::get('global_company_name') ?></th>
                                <th><?= sysTranslations::get('global_address') ?></th>
                                <th><?= sysTranslations::get('global_city') ?></th>
                                <th><?= sysTranslations::get('global_phone') ?></th>
                                <th><?= sysTranslations::get('customer_locations') ?></th>
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

$sIsOnlineMsg   = sysTranslations::get('system_is_online');
$sIsOfflineMsg  = sysTranslations::get('system_is_offline');
$sNotChangedMsg = sysTranslations::get('system_not_changed');
# add ajax code for online/offline handling
$sBottomJavascript = <<<EOT
<script>
$("#dashboardTable").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["colvis"],
      "stateSave": true,
      "columnDefs": [ {
        "searchable": false,
        "orderable": false,
        "targets": [0,6]
    } ],
    "order": [[ 1, 'asc' ]]
    }).buttons().container().appendTo('#dashboardTable_wrapper .col-md-6:eq(0)');
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);

/*
 $(".data-table").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,

      "columnDefs": [ {
        "searchable": false,
        "orderable": false,
        "targets": [0,6]
    } ],
    "order": [[ 1, 'asc' ]]
    });

*/
