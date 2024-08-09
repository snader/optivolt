<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12">
        <h1 class="m-0"><i aria-hidden="true" class="fa fa-users-cog fa-th-large"></i>&nbsp;&nbsp;<?= sysTranslations::get('user_user') ?></h1>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-user pr-1"></i> <?= sysTranslations::get('user_user') ?></h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="" class="validateForm" id="quickForm">
          <input type="hidden" value="save" name="action" />
          <div class="card-body">
            <?php
            if ($oCurrentUser->isSuperAdmin() || $oCurrentUser->isClientAdmin()) {
              if ($oUser->isOnlineChangeable() > 1) { //  && $oUser->userId != $oCurrentUser->userId
            ?>
                <div class="form-group">
                  <div class="row border-bottom mb-2 pb-1">
                    <div class="col-md-4">
                      <label for="name"><?= sysTranslations::get('user_deactivation_status') ?></label>
                    </div>
                    <div class="col-md-8">
                      <input type="hidden" name="deactivation" value="0">
                      <input type="checkbox" id="deactivation" name="deactivation" data-size="mini" data-bootstrap-switch data-off-color="success" value="1" data-on-color="danger" data-on-text="deactivated" data-off-text="activated" <?= $oUser->deactivation ? 'CHECKED' : '' ?>>
                    </div>
                  </div>
                </div>
                <div class="form-group hide" id="lock_status_reason">
                  <label for="locked_reason"><?= sysTranslations::get('user_deactivation_reason') ?></label>
                  <input type="text" name="lockedReason" class="form-control" id="lockedReason" autocomplete="off" value="<?= $oUser->lockedReason ?>" title="<?= sysTranslations::get('user_enter_user_deactivation_reason_tooltip') ?>">
                  <span class="error invalid-feedback show"><?= $oUser->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                </div>
            <?php
              }
            }
            ?>

            <div class="form-group">
              <label for="name"><?= sysTranslations::get('global_name') ?> *</label>
              <input type="text" name="name" class="form-control" id="name" value="<?= $oUser->name ?>" title="<?= sysTranslations::get('user_enter_name_tooltip') ?>" required data-msg="<?= sysTranslations::get('user_enter_name_tooltip') ?>">
              <span class="error invalid-feedback show"><?= $oUser->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>

            <div class="form-group">
              <label for="username"><?= sysTranslations::get('user_username') ?> *</label>
              <input type="text" name="username" autocomplete="off" class="form-control" id="username" value="<?= $oUser->username ?>" title="<?= sysTranslations::get('user_enter_username_tooltip') ?>" required data-msg="<?= sysTranslations::get('user_enter_username_tooltip') ?>" data-rule-remote="<?= ADMIN_FOLDER ?>/gebruikers/ajax-checkUsername?userId=<?= $oUser->userId ?>">
              <span class="error invalid-feedback show"><?= $oUser->isPropValid("username") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>


            <div class="form-group">
              <i onclick="genPassword()" title="Genereer een veilig wachtwoord" class="fas fa-cog float-right pt-1"></i><label for="password"><?= sysTranslations::get('user_new_password') ?> <i class="far fa-question-circle" title="<?= sysTranslations::get('user_secure_password_tooltip') ?>"></i></label>
              <input type="text" name="password" data-rule-password="true" class="form-control" id="password" autocomplete="new-password" title="<?= sysTranslations::get('user_secure_password_tooltip') ?>" <?= empty($oUser->userId) ? 'required data-msg="' . sysTranslations::get('user_secure_password_tooltip') . '"' : '' ?>>
              <em style="font-size:10pt;">&nbsp;<?= sysTranslations::get('user_password_empty') ?></em>
              <span class="error invalid-feedback show"><?= $oUser->isPropValid("password") ? '' : sysTranslations::get('user_secure_password_tooltip') ?></span>
            </div>
            <div class="form-group">
              <label for="userAccessGroupId"><?= sysTranslations::get('user_userAccessGoup') ?> *</label>
              <select class="form-control " id="userAccessGroupId" name="userAccessGroupId" required title="<?= sysTranslations::get('user_userAccessGoup_tooltip') ?>">
                <option value=""><?= sysTranslations::get('user_userAccessGoup_tooltip') ?></option>
                <?php
                $aFilter = [];
                foreach (UserAccessGroupManager::getUserAccessGroupsByFilter($aFilter) as $oUserAccessGroup) {
                  echo '<option ' . ($oUserAccessGroup->userAccessGroupId == $oUser->userAccessGroupId ? 'selected' : '') . ' value="' . $oUserAccessGroup->userAccessGroupId . '">' . $oUserAccessGroup->displayName . '</option>';
                }
                ?>
              </select>
              <span class="error invalid-feedback show"><?= $oUser->isPropValid("userAccessGroupId") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>


            <?php if ($oCurrentUser->isAdmin()) { ?>
              <div class="form-group">
                <div class="row border-bottom mb-2 pb-1">
                  <div class="col-md-4">
                    <label for="accountmanager">Accountmanager</label>
                  </div>
                  <div class="col-md-8">
                    <input type="checkbox" id="accountmanager" name="accountmanager" data-size="mini" data-bootstrap-switch data-off-color="warning" value="1" data-on-color="success" <?= $oUser->getAccountmanager() ? 'CHECKED' : '' ?>>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row border-bottom mb-2 pb-1">
                  <div class="col-md-4">
                    <label for="administrator"><?= sysTranslations::get('user_admin_rights') ?></label>
                  </div>
                  <div class="col-md-8">
                    <input type="checkbox" id="administrator" name="administrator" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" data-on-color="success" <?= $oUser->getAdministrator() ? 'CHECKED' : '' ?>>
                  </div>
                </div>
              </div>
            <?php } ?>
            <div class="form-group">
              <div class="row border-bottom mb-2 pb-1">
                <div class="col-md-4">
                  <label for="administrator"><?= sysTranslations::get('2_step_user_force_two_step') ?></label>
                </div>
                <div class="col-md-8">
                  <input type="checkbox" id="twoStepEnabled" name="twoStepEnabled" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" data-on-color="success" <?= Settings::get('2StepForced') ? ' ng-readonly="true" disabled="disabled"' : '' ?> <?= Settings::get('2StepForced') || $oUser->twoStepEnabled  ? ' checked="checked" ' : '' ?>>
                </div>
              </div>
            </div>

            <input name="systemLanguageId" type="hidden" value="1">

            <!-- /.card-body -->
            <div class="card-footer">
              <span class="float-sm-right">
                <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                  <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                    <?= sysTranslations::get('global_back') ?>
                  </button>
                </a>
              </span>
              <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
              <?php if ($oCurrentUser->isAdmin() && Settings::exists('2StepForced')) { ?>
                <?php if ($oUser->twoStepEnabled && !empty($oUser->userId)) { ?>
                  &nbsp;<input type="submit" id="2StepAuthenticationSecretRequestBtn" class="btn btn-outline-secondary btn-xs" value="<?= sysTranslations::get('2_step_request_new_secret') ?>">
                <?php } ?>
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
      <!-- /.card -->
    </div>
    <!--/.col (left) -->
    <!-- right column -->
    <div class="col-md-6">
      <?php
      if (moduleExists('imageManager')) {
      ?>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-camera pr-1"></i> <?= sysTranslations::get('user_image') ?></h3>
          </div>
          <div class="card-body">
            <?php
            if ($oUser->userId !== null) {
              $oImageManagerHTML->includeTemplate();
            } else {
              echo '<p><i>' . sysTranslations::get('user_images_warning') . '</i></p>';
            }
            ?>
          </div>
        </div>
      <?php
      } ?>
    </div>
    <!--/.col (right) -->
  </div>
  <!-- /.row -->
</div><!-- /.container-fluid -->



<?php

$s2StepSecretResetPopupTitle   = sysTranslations::get('2_step_secret_reset_popup_title');
$s2StepSecretResetPopupContent = sysTranslations::get('2_step_secret_reset_popup_content');
$s2StepSecretResetPopupSuccess = sysTranslations::get('2_step_secret_reset_popup_success');
$s2StepSecretResetPopupCancel  = sysTranslations::get('2_step_secret_reset_popup_cancel');

$bBdeactivation = $oUser->deactivation;

$sJavascript = <<<EOT
<script>

    function genPassword() {
      $("#password").randomPass();
    }

</script>
EOT;
$oPageLayout->addJavascript($sJavascript);

if (!empty($oUser->userId)) {

  $sJavascript2Fa = <<<EOT
<script>
    $('#2StepAuthenticationSecretRequestBtn').click(function(e) {
        e.preventDefault();
      alertify.confirm('$s2StepSecretResetPopupTitle', '$s2StepSecretResetPopupContent', function(){ reset2Fa();  }, function(){ alertify.error('$s2StepSecretResetPopupCancel')});
    }) ;

    function reset2Fa() {
      $.ajax({
            type: "POST",
            url: '/dashboard/gebruikers/ajax/reset2step',
            data: "userId=" + $oUser->userId,
            success: function(data){
                alertify.success('$s2StepSecretResetPopupSuccess');
            }
      });
    }

    $(document).on('click', '.delete_icon', function() {
      setTimeout(function() {
        window.location.reload();
      }
      ,500)
    })

    <!-- deactivate and why -->
    $('input[name="deactivation"]').on('switchChange.bootstrapSwitch', function (event, state) {
        if ($(this).is(':checked')) {
            $('#lock_status_reason').removeClass('hide');
        } else {
            $('#lock_status_reason').addClass('hide');
        }
    });
    $(document).ready(function() {
        if ($bBdeactivation == 1) {
            $('#lock_status_reason').removeClass('hide');
        } else {
            $('#lock_status_reason').addClass('hide');
        }
    });
</script>
EOT;
  $oPageLayout->addJavascript($sJavascript2Fa);
}
