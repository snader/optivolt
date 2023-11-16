<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">

                <h1 class="m-0"><i aria-hidden="true" class="fas fa-cubes"></i>&nbsp;&nbsp;Modules</h1></i>
            </div>
        </div>
    </div>
</div>

<?php
# list modules in unordered list

function makeListTree($aModules, $iLevel, $iMaxLevels)
{
    if (count($aModules) > 0 && $iLevel == 1) {
        // echo '<ol class="nestedSortable level' . $iLevel . '">';
    } elseif (count($aModules) > 0) {
        // echo '<ol class="level' . $iLevel . '">';
    }

    $iT = 0;
    foreach ($aModules as $oModule) {
        echo '<tr id="module_' . $oModule->moduleId . '">';

        $sClasses = '';
        if ($iT > 0 && $iLevel == 1) {
            $sClasses .= ' mainModule';
        }
        if ($iT == 0 && $iLevel == 1) {
            $sClasses .= ' first-mainModule';
        }
        if ($iT > 0 && $iLevel > 1) {
            $sClasses .= ' sub';
        }
        if ($iT == 0 && $iLevel > 1) {
            $sClasses .= ' first-sub';
        }

        # add sub module
        echo '<td class="' . $sClasses . '" style="width:100%;">';

        if ($iLevel < $iMaxLevels) { ?>

            <a href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/toevoegen?parentModuleId=' . $oModule->moduleId ?>" title="<?= sysTranslations::get('modules_add_sub') ?>">
                <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                    <i class="fas fa-plus-circle"></i>
                </button>
            </a>&nbsp;


        <?php
        }
        echo sysTranslations::get($oModule->linkName) . ($oModule->showInMenu ? '' : '<span class="brackedComment"> (' . sysTranslations::get('global_not_shown_in_menu') . ')</span>');

        echo '</td>';
        echo '<td class="actionIconsHolder text-right">';




        ?>
        <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oModule->moduleId ?>" title="<?= sysTranslations::get('modules_edit') ?>">
            <i class="fas fa-pencil-alt"></i>
        </a>
        <a class="btn btn-danger btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oModule->moduleId ?>" title="<?= sysTranslations::get('modules_delete') ?>" onclick="return confirmChoice(\'' . sysTranslations::get($oModule->linkName) . '\');">
            <i class="fas fa-trash"></i>
        </a>
<?php
        echo '</td>';



        echo '</tr>';
        makeListTree($oModule->getChildren('all'), $iLevel + 1, $iMaxLevels); //call function recursive
        $iT++;
    }
}

?>
<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Alle modules</h3>

                        <div class="card-tools">
                            <div class="input-group input-group-sm">

                                <a class="addBtn" href="<?= ADMIN_FOLDER ?>/modules/toevoegen" title="Nieuwe module toevoegen">
                                    <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                </a>&nbsp;
                                <a class="changeOrderBtn" href="<?= ADMIN_FOLDER ?>/modules/structuur-wijzigen" title="Modules volgorde wijzigen">
                                    <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                                        <i class="fas fa-sort"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <?php
                                if (count($aAllLevel1Modules) == 0) {
                                    echo '<tr><td>' . sysTranslations::get('modules_no_modules') . '</td></tr>';
                                }
                                ?>
                            </thead>
                            <tbody>

                                <?php
                                # start recursive displaying modules
                                makeListTree($aAllLevel1Modules, 1, $iMaxLevels);
                                ?>


                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>


        </div>
    </div><!-- /.container-fluid -->
</section>
</section>
<!-- /.content -->