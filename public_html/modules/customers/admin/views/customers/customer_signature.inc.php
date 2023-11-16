<!-- WORDT NIET GEBRUIKT -->

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">

                <span class="float-right">
                    <a class="backBtn right pl-1" href="<?= ADMIN_FOLDER ?>/klanten">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer_overview') ?>">
                            <?= sysTranslations::get('to_customer_overview') ?>
                        </button>
                    </a>
                </span>
                <?php
                if (!UserManager::getCurrentUser()->isClientAdmin()) {
                ?>
                    <span class="float-right">
                        <a class="backBtn right pl-1" href="<?= ADMIN_FOLDER ?>">
                            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('dashboard_menu') ?>">
                                <?= sysTranslations::get('dashboard_menu') ?>
                            </button>
                        </a>
                    </span>
                <?php } ?>


                <h1 class="m-0"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;<?= sysTranslations::get('systems_customer') ?> & <?= sysTranslations::get('customer_locations') ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">

            <?php
            if ($oCustomer->customerId) {



                if (isset($aAppointment) && !empty($aAppointment)) {
            ?>

                    <div class="card finish-card <?= ($iMissedSystems > 0) ? 'card-danger' : 'card-success' ?>">
                        <div class="card-header">
                            <a name="signature-pad"></a>
                            <h3 class="card-title"><i class="fas fa-flag-checkered pr-1"></i> <?php
                                                                                                if ($aAppointment['finished']) {
                                                                                                    echo sysTranslations::get('report_finished');
                                                                                                } else { ?><?= sysTranslations::get('report_finishing') ?><?php }
                                                                                                                                                            ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="p-1">

                                <?php
                                if ($aAppointment['finished']) {
                                    echo 'Voltooid op ' . date('d-m-Y', strtotime($aAppointment['modified'])) . ' door ' . UserManager::getUserById($aAppointment['userId'])->getDisplayName() . '<br/>';
                                    echo 'Handtekening voor gezien:<br/>';

                                ?>
                                    <img src='<?= ADMIN_FOLDER ?>/klanten/sigs/<?= $aAppointment['signature'] ?>' width='100%'>
                                <?php

                                } else {
                                ?>
                                    <form action="<?= ADMIN_FOLDER ?>/klanten/sig" method="POST">
                                        <?= CSRFSynchronizerToken::field() ?>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="finish" value="1">
                                                <input type="hidden" name="userId" id="userId" value="<?= UserManager::getCurrentUser()->userId ?>">
                                                <input type="hidden" name="customerId" id="customerId" value="<?= $oCustomer->customerId ?>">
                                                <input type="hidden" name="visitDate" id="visitDate" value="<?= $aAppointment['visitDate'] ?>">
                                                <textarea id="signature64" name="signature" style="display: none"></textarea>
                                                <label for="finish" class="custom-control-label">Afronden onderhoudsafspraak</label>
                                            </div>
                                        </div>
                                        <div id="signature-pad">
                                            <div>
                                                <strong>aaIngepland op:</strong> <?= date('d-m-Y', strtotime($aAppointment['visitDate'])) ?><br />
                                                <strong>Datum voltooid:</strong> <?= date('d-m-Y', time()) ?><br />
                                                <strong>Handtekening namens:</strong> <?= $oCustomer->companyName ?>
                                                <strong>Naam aftekening:</strong><input type="text" name="signatureName" placeholder="Anders dan contactpersoon" value="<?= $oCustomer->contactPersonName ?>">
                                            </div>
                                            <div id="sig"></div>
                                            <button type="button" class="button btn btn-default clear" id="clear">Clear</button>
                                            <button class="button btn btn-success save" type="submit" id="save-signature">Voltooien</button>
                                        </div>
                                    </form>
                                <?php
                                }
                                ?>
                            </div>

                        </div>
                    </div>

                    <?php if ($aAppointment['finished'] && (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin())) {
                    ?>
                        <form method="POST" action="">
                            <input type="hidden" name="export" value="PDF">
                            <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                <i class="fa fa-download"></i> Download onderhoudsformulier
                            </button>
                            <!--<select name="year" class="form-control mr-1" style="width:130px; float:left;">
                                <?php
                                for ($x = 2021; $x <= date('Y'); $x++) {
                                    echo '<option value="' . $x . '">' . ($x - 1) . ' - ' . $x . '</option>';
                                }
                                ?>
                            </select>-->
                        </form>
                <?php
                    }
                }
                ?>



            <?php } ?>
        </div>


    </div>
</div>




<style>
    .kbw-signature {
        width: 100%;
        height: 500px;
    }

    #sig canvas {
        width: 100% !important;
        height: auto;
    }

    .actAsModal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        background-color: white;
        z-index: 1502;
        overflow: auto;
    }

    .disableScroll {
        overflow: hidden;
        height: 100%;
    }
</style>

<?php
$sBottomJavascript = <<<EOT
<script type="text/javascript">

        $('#signature-pad').show();
        $('html, body').addClass('disableScroll');
        $('.finish-card').addClass('actAsModal');
        var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });

    });


</script>

EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>