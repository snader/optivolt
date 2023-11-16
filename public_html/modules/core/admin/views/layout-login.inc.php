<!DOCTYPE HTML>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex">
  <link rel="shortcut icon" href="/themes/default/images/icons/favicon.ico" />
  <link rel="apple-touch-icon" sizes="57x57" href="/themes/default/images/icons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/themes/default/images/icons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/themes/default/images/icons/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/themes/default/images/icons/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/themes/default/images/icons/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/themes/default/images/icons/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/themes/default/images/icons/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/themes/default/images/icons/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/themes/default/images/icons/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/themes/default/images/icons/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/themes/default/images/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/themes/default/images/icons/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/themes/default/images/icons/favicon-16x16.png">
  <link rel="manifest" href="/themes/default/images/icons/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/themes/default/images/icons/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
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

</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="/"><img style="width:auto; max-height:100px;margin-top:10px;margin-bottom:20px;" src="<?= getSiteImage('optivolt-logo.png') ?>" alt="<?= CLIENT_NAME ?>" /></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">

          <?php if ($bLoginEnabled && $bShowLoginAttemptsWarning) { ?>
        <div class="alert alert-danger errorColor " style="text-align: left; margin-bottom:10px;">Login failed : wrong credentials</b></div>
      <?php } ?>

      Sign in to start your session</p>


      <?php

      if (!$bDeactivation) {
        if ($bLoginEnabled) {
      ?>

          <form action="" method="post">
            <input type="hidden" value="send" name="login_form" />
            <?= CSRFSynchronizerToken::field() ?>
            <div class="input-group mb-3">
              <input type="text" name="username" id="username" class="form-control" placeholder="<?= sysTranslations::get('user_username') ?>">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" id="password" class="form-control" placeholder="<?= sysTranslations::get('user_password') ?>">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <!--<div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember">
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>-->
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" value="Login" name="verzendBtn" class="btn btn-primary btn-block">Sign In</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
        <?php

        } else {
        ?>
          <div class="alert alert-danger errorColor" style="text-align: left; margin-bottom:10px;">U
            heeft <?= AccessLogManager::max_login_attempts_account_lock . sysTranslations::get('login_attempts') . sysTranslations::get('user_account_is_blocked_part1') . AccessLogManager::account_locked_time . sysTranslations::get(
                    'user_account_is_blocked_part2'
                  ) ?>
          </div>
          <a href="<?= ADMIN_FOLDER ?>/login" class="btn btn-default"><?= sysTranslations::get('back_to_login') ?></a>
        <?php

        }
      } else {
        ?>
        <div class="alert alert-danger errorColor" style="text-align: left; margin-bottom:10px;"><?= sysTranslations::get('user_account_blocked') ?><br />
          <?php

          if (!empty($oUser->lockedReason)) {

            echo sysTranslations::get('locked_reason') . ' ' . _e($oUser->lockedReason) ?>
          <?php

          } ?>
        </div>
        <a href="<?= ADMIN_FOLDER ?>/login" class="btn btn-default"><?= sysTranslations::get('back_to_login') ?></a>
      <?php

      } ?>

      <!--
      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>-->
      </div>
      <!-- /.login-card-body -->
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