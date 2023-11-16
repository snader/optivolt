<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">

                <span class="float-sm-right">
                    <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/systems/<?= $oSystemReport->getSystem()->getLocation()->getCustomer()->customerId ?>">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                            Naar systemen
                        </button>
                    </a>
                    <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/systems/bewerken/<?= $oSystemReport->getSystem()->systemId ?>">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                            <?= sysTranslations::get('to_system') ?>
                        </button>
                    </a>
                    <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/locaties/bewerken/<?= $oSystemReport->getSystem()->getLocation()->locationId ?>">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                            <?= sysTranslations::get('to_location') ?>
                        </button>
                    </a>


                    <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oSystemReport->getSystem()->getLocation()->getCustomer()->customerId ?>">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                            <?= sysTranslations::get('to_customer') ?>
                        </button>
                    </a>
                </span>

                <h1 class="m-0"><i aria-hidden="true" class="fa fa-file-contract fa-th-large"></i>&nbsp;&nbsp;

                    <?php
                    echo $oSystemReport->getSystem()->getLocation()->getCustomer()->companyName . ' - ';
                    ?>
                    <?= sysTranslations::get('system_measurement') ?>
                    <?php
                    if ($oSystemReport->systemReportId) {
                        echo date('Y', strtotime($oSystemReport->created));
                    } else {
                        echo date('Y', time());
                    } ?>
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">

    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- form start -->
            <form method="POST" action="" class="validateForm" id="quickForm" autocomplete="off">
                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                <input type="hidden" value="save" name="action" />
                <input type="hidden" value="" name="systemNotice" id="systemNotice" />
                <input type="hidden" value="1" name="online" />
                <input type="hidden" value="<?= $oSystemReport->getSystem() ? $oSystemReport->getSystem()->systemId : http_get('systemId') ?>" name="systemId" />


                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">
                                    <i aria-hidden="true" class="fa fa-thermometer-empty pr-1"></i>
                                    <?= $oSystemReport->getSystem()->getLocation()->getCustomer()->companyName ?>

                                </h3>
                            </div>
                            <div class="col-md-6 ">
                                <h3 class="card-title float-right">
                                    <?= $oSystemReport->getSystem()->getLocation()->name ?>
                                </h3>
                            </div>
                        </div>


                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-3 col-sm-6 form-group">
                                <label for="pos">Pos.</label>
                                <div><?= $oSystemReport->getSystem()->pos ?></div>
                            </div>
                            <div class="col-md-3 col-sm-6 form-group">
                                <label for="name"><?= sysTranslations::get('systems_name') ?></label>
                                <div><?= $oSystemReport->getSystem()->name ?><?= ($oSystemReport->getSystem() && $oSystemReport->getSystem()->getSystemType()) ? ' (' . $oSystemReport->getSystem()->getSystemType()->name() . ')' : '' ?></div>
                            </div>
                            <div class="col-md-3 col-sm-6 form-group">
                                <label for="model"><?= sysTranslations::get('systems_model') ?></label>
                                <div><?= $oSystemReport->getSystem()->model ?></div>
                            </div>
                            <?php
                            if ($oSystemReport->getSystem()->systemTypeId == System::SYSTEM_TYPE_MULTILINER) {
                            ?>
                                <div class="col-md-3 col-sm-6 form-group">
                                    <label for="machineNr">Machinenr.</label>
                                    <div><?= $oSystemReport->getSystem()->machineNr ?></div>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        if (is_numeric($oSystemReport->getSystem()->systemTypeId)) {
                            include getAdminSnippet('systemReports/systemReportFields_' . $oSystemReport->getSystem()->systemTypeId, 'systems');
                        } else {
                            print 'Systeem heeft geen type gekoppeld.';
                        }
                        ?>

                    </div>
                    <div class="card-footer">

                        <?php
                        if (!$oSystemReport->isEditable()) {
                        ?>
                            <a href="javascript:history.back();"><input type="button" class="btn btn-secondary" value="Terug" name="Terug" /></a><?php
                                                                                                                                                } else {
                                                                                                                                                    ?>
                            <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />&nbsp;
                            <input type="submit" class="btn btn-primary" value="Opslaan > Systemen" name="save" />
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>


        <div class="col-md-6">

            <?php
            if ($oSystemReport->systemReportId) {
            ?>
                <div class="card">
                    <div class="card-header">
                        <form style="float:right;" method="POST" action="">
                            Later uploaden <i aria-hidden="true" title="Let op! Dit is puur een herinnering. Vergeet niet om dit veld leeg te maken als de foto(s) geupload is." class="fa fa-question pr-1"></i> <input type="text" style="width:200px;" value="<?= _e($oSystemReport->notice) ?>" name="imgNotice" placeholder="Fotonummer(s)"> <button type="submit" name="saveImgNotice" value="saveImgNotice">&raquo;</button>
                        </form>
                        <h3 class="card-title">
                            <i aria-hidden="true" class="fa fa-file-image pr-1"></i> <?= sysTranslations::get('global_images') ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($oSystemReport->systemReportId !== null) {


                            $oImageManagerHTML->includeTemplate();
                        } else {
                            echo '<p><i>' . sysTranslations::get('system_report_images_warning') . '</i></p>';
                        }
                        ?>
                    </div>

                </div>
            <?php } ?>
            <div class="card">
                <div class="card-header">
                    <?php
                    $aList = explode(PHP_EOL, trim($oSystemReport->getSystem()->notice));
                    if ((UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) && count($aList) > 0) {
                    ?><span class="float-sm-right">
                            <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/systems/bewerken/<?= $oSystemReport->getSystem()->systemId ?>#notice">
                                <button type="button" class="btn btn-default btn-sm" title="'Naar systeem opmerkingen <?= sysTranslations::get('global_without_saving') ?>">
                                    Bewerken
                                </button>
                            </a>
                        </span>
                    <?php
                    }
                    ?>
                    <h3 class="card-title"><i aria-hidden="true" class="fa fa-comments pr-1"></i>Opmerkingen</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="" class="validateForm" id="quickFormNotice" autocomplete="off">
                        <input type="hidden" value="saveNotice" name="action" />
                        <input type="hidden" value="<?= $oSystemReport->getSystem() ? $oSystemReport->getSystem()->systemId : http_get('systemId') ?>" name="systemId" />
                        <input class="form-control mb-1" rows="2" name="notice" id="quickFormNoticeInput" placeholder="Nieuwe opmerking">
                        <input type="submit" class="btn btn-primary" value="Opmerking toevoegen" name="save" /> <span style="font-size:12px;">(Let op! Slaat geen metingen op)</span>
                    </form>

                    <div><br />
                        <ul><?php
                            $aList = explode(PHP_EOL, trim($oSystemReport->getSystem()->notice));
                            foreach ($aList as $sNotice) {
                                if (!empty($sNotice)) {
                                    echo '<li>' . _e($sNotice) . '</li>';
                                }
                            }
                            ?>
                        </ul>

                    </div>

                </div>

            </div>


            <?php

            $sBottomJavascript = <<<EOT
<script type="text/javascript">

    $('#quickFormNoticeInput').on( "change", function() {

        $('#systemNotice').val($(this).val());

    });



</script>
EOT;
            $oPageLayout->addJavascript($sBottomJavascript);
            ?>