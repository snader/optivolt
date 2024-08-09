<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">

                <h1 class="m-0"><i aria-hidden="true" class="fas fa-shapes fa-users"></i>&nbsp;&nbsp;Toegangsgroepen</h1></i>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= sysTranslations::get('userAccessGroup_all') ?></h3>

                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">

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
                                    <th><?= sysTranslations::get('userAccessGroup_displayName') ?></th>
                                    <?php if ($oCurrentUser->isSuperAdmin()) { ?>
                                        <th><?= sysTranslations::get('userAccessGroup_systemName') ?></th>
                                    <?php } ?>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($aUserAccessGroups as $oUserAccessGroup) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php

                                            if ($oUserAccessGroup->isEditable()) {
                                            ?>
                                                <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oUserAccessGroup->userAccessGroupId ?>" title="<?= sysTranslations::get('userAccessGroup_edit') ?>">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            <?php } else {
                                                echo '<i class="fas fa-pencil-alt"></i>';
                                            } ?>
                                        </td>
                                        <td><?= $oUserAccessGroup->displayName ?></td>
                                        <?php if ($oCurrentUser->isSuperAdmin()) { ?>
                                            <td><?= $oUserAccessGroup->name ?></td>
                                        <?php } ?>

                                        <td>

                                            <?php
                                            if ($oUserAccessGroup->isDeletable()) {
                                            ?>
                                                <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oUserAccessGroup->userAccessGroupId ?>" title="<?= sysTranslations::get('userAccessGroup_delete') ?>" onclick="return confirmChoice('<?= strtolower(sysTranslations::get('userAccessGroup_userAccessGroup') ?? '') . ' ' . $oUserAccessGroup->displayName ?>');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php } else { ?><span class="btn btn-danger btn-sm disabled"><i class="fas fa-trash"></i></span><?php } ?>
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