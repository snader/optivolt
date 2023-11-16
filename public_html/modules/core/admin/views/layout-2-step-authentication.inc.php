<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?= _e(CLIENT_NAME) ?></title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <link rel="shortcut icon" href="/favicon.ico"/>
</head>

<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="/"><img style="width:auto; max-height:100px;margin-top:10px;margin-bottom:20px;" src="<?= getSiteImage('optivolt-logo.png') ?>" alt="<?= CLIENT_NAME ?>"/></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">
       
      <?php if ($bLoginEnabled && $bShowLoginAttemptsWarning) { ?>
                <div class="alert alert-danger errorColor " style="text-align: left; margin-bottom:10px;">Authenticatie code is onjuist.</div>
            <?php } ?>
      
        <h5 class="text-center pt-0"><?= sysTranslations::get('2_step_authenticate_for') ?> <?= $oUser->username ?></h5>

        <?php if ($bShowQRCode) { ?>
                <p  class="text-center"><?= ucfirst(sysTranslations::get('2_step_use_authenticator_app_to_scan_qr')) ?></p>
                <div>
                    <img src='<?= $sQRCodeUrl; ?>' style="width:100%;" />
                </div>
                <div style="font-size: 1.4em; font-weight: bold; text-align:center;"><?= $oUser->twoStepSecret ?></div>
                
            <?php } ?>

        <?php if ($bLoginEnabled) { ?>

            <form method="post" action="<?= getCurrentUrl() ?>">
                <?= CSRFSynchronizerToken::field() ?>
            
                <div class="form-group text-center">
                    <label>Your authenticator code</label>
                    <input type="text" class="class="form-control"" name="code" autocomplete="off" autofocus/>
                </div>
                <div class="col-12">
                        <button type="submit" value="<?= sysTranslations::get('2_step_check_code') ?>" name="verzendBtn" class="btn btn-primary btn-block">Continue</button>
                </div>   
           
            </form>
            <div style="text-align:center; margin-bottom: 10px;">
                <br/>                
                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank"><img width="120" src="<?= getAdminImage('googleplaystore.png') ?>"/></a>
                <a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><img width="120" src="<?= getAdminImage('appleappstore.png') ?>"/></a>
            </div>

        <?php } else { ?>
            <div class="alert alert-danger errorColor" style="text-align: left; margin-bottom:10px;">Inloggen is <?= AccessLogManager::max_login_attempts_account_lock ?>x mislukt. Het account
                is voor <?= AccessLogManager::account_locked_time ?> minuten geblokkeerd.
            </div>
        <?php } ?>

        </p>
    </div>

  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
</body>
</html>




<?php

if (ENVIRONMENT != 'production') {
    ?>
    <div style="display: block; padding:10px;">&nbsp;</div>
    <div style="color: #FFF; font-size: 15pt; opacity: .5; z-index:9111; position: fixed; bottom: 0; right: 0; width: auto; background-color: #F00; ">
        <div style="padding: 5px 20px 5px 20px;"><?= ENVIRONMENT ?></div>
    </div>
    <?php

}
?>
</body>
</html>
