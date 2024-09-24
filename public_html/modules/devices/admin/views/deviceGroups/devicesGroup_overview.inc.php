<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">

                <h1 class="m-0"><i aria-hidden="true" class="fas fa-users"></i>&nbsp;&nbsp;Apparaatgroepen</h1></i>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Alle apparaatgroepen</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: auto;">

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
                                <th><?= sysTranslations::get('global_name') ?></th>
                                <th>Aantal apparaten</th>
                                
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            foreach ($aDeviceGroups as $oDeviceGroup) {
                                $iDevices        = $oDeviceGroup->getAmountOfClients();
                                
                                echo '<tr>';
                            ?>
                                <td>
                                    <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oDeviceGroup->deviceGroupId ?>" title="<?= sysTranslations::get('device_group_edit') ?>">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                                <?php

                                echo '<td>' . _e($oDeviceGroup->title) . '</td>';
                                echo '<td>' . $iDevices . '</td>';
                                
                                echo '<td style="width:25px;">';

                                if ($oDeviceGroup->isDeletable()) {
                                ?>
                                    <a class="btn btn-danger btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oDeviceGroup->deviceGroupId . '?' . CSRFSynchronizerToken::query() ?>" title="<?= sysTranslations::get('device_group_delete') ?>" onclick="return confirmChoice('<?= _e($oDeviceGroup->title) ?>');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php } else { ?><span class="btn btn-danger btn-sm disabled"><i class="fas fa-trash"></i></span><?php }


                                    echo '</td>';
                                    echo '</tr>';
                                }
                                if (empty($aDeviceGroups)) {
                                    echo '<tr><td colspan="6"><i>' . sysTranslations::get('device_no_group') . '</i></td></tr>';
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