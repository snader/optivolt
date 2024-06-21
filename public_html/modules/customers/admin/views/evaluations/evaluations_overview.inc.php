<?php
$bShowAddButton = true;
$bShowToCustomerButton = false;
$sOptionHTML = '';
foreach ($aAllCustomers as $oCustomer) {
    if (isset($aEvaluationFilter['customerId']) && is_numeric($aEvaluationFilter['customerId']) && $aEvaluationFilter['customerId'] == $oCustomer->customerId) {
        
        $bShowCustomer = true;
        $bShowToCustomerButton = true;
    }
    $sOptionHTML .= '<option ' . ((isset($aEvaluationFilter['customerId']) && ($aEvaluationFilter['customerId'] == $oCustomer->customerId)) ? 'selected' : '') . ' value="' . $oCustomer->customerId . '">' . $oCustomer->companyName . '</option>';
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
                        <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $aEvaluationFilter['customerId'] ?>">
                            <button type="button" class="btn btn-default btn-sm" title="Naar klant">
                                Naar klant
                            </button>
                        </a>
                    </span>
                <?php
                }
                ?>
                
                <h1 class="m-0"><i aria-hidden="true" class="fa fa-star fa-th-star"></i>&nbsp;&nbsp;Evaluaties</h1>

            </div>

        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title d-none d-sm-block">Overzicht</h3>

                    <div class="card-tools">

                        <div class="input-group input-group-sm" style="width: auto;">

                            <div class="input-group-append">
                                <form action="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>" method="POST" class="form-inline pr-2">
                                    <input type="hidden" name="filterForm" value="1" />

                                    <select class="select2 form-control form-control-sm" name="evaluationFilter[customerId]">
                                        <option>Selecteer klant</option>
                                        <?= $sOptionHTML ?>
                                    </select>&nbsp;
                                    <input type="submit" name="filterEvaluations" value="Filter" class="btn btn-default btn-sm" /> <input class="btn btn-default btn-sm" type="submit" name="resetFilter" value="Reset" />
                                </form>
                            </div>
                            <?php
                            if ($bShowAddButton) { ?>
                                <div class="input-group-append">
                                    <a class="addBtn" href="<?= ADMIN_FOLDER . '/' . http_get('controller') ?>/toevoegen?customerId=<?= $aEvaluationFilter['customerId'] ?>" title="<?= sysTranslations::get('add_item') ?>">
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
                    
                <table class="table table-bordered table-striped data-table">
                    <thead>
                        <tr>
                            <th style="width: 10px;">&nbsp;</th>                       
                            <th>Klant</th>
                            <th>Relatie</th>
                            <th style="text-align:center; width: 30px;">Afgerond?</th>                        
                            <th style="width: 70px;">Gewijzigd</th>
                            <th style="width: 70px;">Aangemaakt</th>
                            <th style="width: 10px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($aEvaluations as $oEvaluation) {

                        echo '<tr>';
                        ?>
                        <td>
                            <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/evaluaties/bewerken/' . $oEvaluation->evaluationId ?>" title="Bewerken/bekijken">
                            <i class="fas fa-pencil-alt"></i>
                            </a>
                        </td>
                        <?php
                                          
                        
                        echo '<td>' .  _e($oEvaluation->companyName) . '</td>';
                        echo '<td>' .  _e($oEvaluation->nameSigned) . '</td>';
                        echo '<td style="text-align:center;">' . ($oEvaluation->digitalSigned ? '<i class="fas fa-check"></i>' : '') . '</td>';
                        echo '<td>'
                            . ($oEvaluation->dateSigned ? date('d-m-Y', strtotime($oEvaluation->dateSigned)) : ($oEvaluation->modified ? date('d-m-Y', strtotime($oEvaluation->modified)) : ''));
                            '</td>';
                        echo '<td>'
                        . ($oEvaluation->created ? date('d-m-Y', strtotime($oEvaluation->created)) : '');
                        '</td>';
                        ?>

                        <td nowrap align="center">
                            <?php
                            if ($oEvaluation->isDeletable()) { ?>
                            <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/evaluation/verwijderen/' . $oEvaluation->evaluationId . '?' . (!$bShowLocation ? 'fromLocation=' . http_get('param2') . '&' : '') . CSRFSynchronizerToken::query() ?>" title="<?= sysTranslations::get('system_delete') ?>" onclick="return confirmChoice('<?= strtolower(sysTranslations::get('system_system')) . ' ' . $oEvaluation->name ?>');">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php } else { ?>
                            <!--<span class="btn btn-danger btn-xs disabled"><i class="fas fa-trash"></i></span>-->
                            <?php } ?>
                        </td>
                        <?php


                        echo '</tr>';
                        }
                        if (empty($aEvaluations)) {
                        echo '<tr><td colspan="8"><i>Geen evaluaties gevonden</i></td></tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                        <th style="width: 10px;">&nbsp;</th>                        
                        <th>Klant</th>
                        <th>Relatie</th>
                        <th style="text-align:center;">Afgerond?</th>
                        <th>Gewijzigd</th>
                        <th>Aangemaakt</th>
                        
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
    "order": [[ 5, 'asc' ]]
    }).buttons().container().appendTo('#customers_wrapper .col-md-6:eq(0)');

  });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
