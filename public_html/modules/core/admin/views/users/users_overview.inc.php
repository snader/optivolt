<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h1 class="m-0"><i aria-hidden="true" class="fa fa-users-cog fa-th-large"></i>&nbsp;&nbsp;<?= sysTranslations::get('users_menu') ?></h1>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= sysTranslations::get('user_all') ?></h3>

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
                                <th><?= sysTranslations::get('user_username') ?></th>
                                <th><?= sysTranslations::get('user_userAccessGroup') ?></th>
                                <th><?= sysTranslations::get('user_locked') ?></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($aUsers as $oUser) {
                            ?>
                                <tr>
                                    <td>
                                        <?php
                                        if ($oUser->isEditable()) {
                                        ?>
                                            <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oUser->userId ?>" title="<?= sysTranslations::get('user_edit') ?>">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $oUser->name ?></td>
                                    <td><?= $oUser->username ?></td>
                                    <td><?= $oUser->getUserAccessGroup()->displayName ?></td>
                                    <?php
                                    if (!empty($oUser->locked) && empty($oUser->deactivationDate)) {
                                        echo '<td id="User_' . $oUser->userId . '_locked">' . ($oUser->locked ? Date::strToDate($oUser->locked)
                                            ->format('%d-%m-%Y om %H:%M') : '') . '</td>';
                                    } else {
                                        echo '<td id="User_' . $oUser->userId . '_locked">' . ($oUser->deactivation ? Date::strToDate($oUser->deactivationDate)
                                            ->format('%d-%m-%Y om %H:%M') : '') . '</td>';
                                    }
                                    if ($oUser->locked) {
                                        echo '<td id="User_' . $oUser->userId . '_reason">' . ($oUser->lockedReason ? sysTranslations::get($oUser->lockedReason) . ' <a class="action_icon unlock_icon" href="#" title="' . _e(
                                            sysTranslations::get('user_unlock')
                                        ) . '" onclick="unlock(' . $oUser->userId . ', \'' . _e($oUser->name) . '\'); return false;"></a>' : '') . '</td></a>';
                                    } else {
                                        echo '<td id="User_' . $oUser->userId . '_reason">' . ($oUser->lockedReason ? $oUser->lockedReason : '') . '</td></a>';
                                    }
                                    ?>

                                    <td>
                                        <?php
                                        # online/offline

                                        echo '<a id="user_' . $oUser->userId . '_activation_1" title="' . sysTranslations::get(
                                            'user_set_activation'
                                        ) . '" class="btn btn-danger btn-xs ' . ($oUser->isOnlineChangeable() == 1 ? 'disabled ' : '') . ($oUser->deactivation ? '' : 'hide') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                                            'controller'
                                        ) . '/ajax-setActivation/' . $oUser->userId . '/?activation=0"><i class="fas fa-eye"></i></a>';
                                        echo '<a id="user_' . $oUser->userId . '_activation_0" title="' . sysTranslations::get(
                                            'user_set_deactivation'
                                        ) . '" class="btn btn-success btn-xs ' . ($oUser->isOnlineChangeable() == 1 ? 'disabled ' : '') . ($oUser->deactivation ? 'hide' : '') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                                            'controller'
                                        ) . '/ajax-setActivation/' . $oUser->userId . '/?activation=1"><i class="fas fa-eye"></i></a>';

                                        ?>

                                        <?php
                                        if ($oUser->isDeletable()) {
                                        ?>
                                            <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oUser->userId ?>" title="<?= sysTranslations::get('user_delete') ?>" onclick="return confirmChoice('<?= strtolower(sysTranslations::get('user_user') ?? '') . ' ' . $oUser->name ?>');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php } else { ?><span class="btn btn-danger btn-xs disabled"><i class="fas fa-trash"></i></span><?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

</div>


<div style="display: none;">
    <div id="unlockUserForm" style="width: 500px;">
        <form method="POST">
            <input class="locked_userId" name="userId" type="hidden" value="" />
            <input name="action" type="hidden" value="unlockUser" />
            <table class="withForm">
                <tr>
                    <td colspan="2">
                        <h2 style="margin-bottom: 5px;"><?= sysTranslations::get('user_unlock') ?> `<span class="locked_name"></span>`</h2>
                    </td>
                </tr>
                <tr>
                    <td class="withLabel" style="width: 120px;"><label for="unlock_reason"><?= sysTranslations::get('user_unlock_reason') ?></label></td>
                    <td>
                        <input id="unlock_reason" name="unlockReason" style="width: 300px;" type="text" value="" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="<?= sysTranslations::get('global_save') ?>" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
    function unlock(userId, name) {
        $('.locked_userId').val(userId);
        $('.locked_name').html(name);
        $.fancybox.open({
            href: '#unlockUserForm'
        });
        return false;
    }
</script>
<?php

$sNewIsOnlineMsg   = sysTranslations::get('user_is_activated');
$sNewIsOfflineMsg  = sysTranslations::get('user_is_deactivated');
$sNewNotChangedMsg = sysTranslations::get('user_not_changed');
# add ajax code for online/offline handling
$sOnlineOfflineJavascript = <<<EOT
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
                    $("#user_"+dataObj.userId+"_activation_0").hide(); // hide offline button
                    $("#user_"+dataObj.userId+"_activation_1").hide(); // hide online button
                    $("#user_"+dataObj.userId+"_activation_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                     $("#user_"+dataObj.userId+"_locked").html(dataObj.deactivation);
                     $("#user_"+dataObj.userId+"_reason").html(dataObj.reason);
                    if(dataObj.online == 0)
                        showStatusUpdate("$sNewIsOnlineMsg");
                    if(dataObj.online == 1)
                        showStatusUpdate("$sNewIsOfflineMsg");
                }else{
                        showStatusUpdate("$sNewNotChangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sOnlineOfflineJavascript);
