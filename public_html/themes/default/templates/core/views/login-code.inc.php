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
  <title><?= _e($oPageLayout->sWindowTitle) ?> | <?= _e(CLIENT_NAME) ?></title>
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

         
      <?php      
      $aErrors = [];
      if (!empty($aErrorsLogin)) {
          ?>
          <div class="alert alert-danger errorColor" role="alert">
            > <?php
            foreach ($aErrorsLogin as $sError) {
              echo $sError . '</br>';
            }
            ?>
          </div>
          <?php
      }
      
      ?>

      <strong>Controleer uw e-mail (<span id="timer"></span>)</strong></p>
     

          <form action="" method="post">
          <?= CustomerCSRFSynchronizerToken::field() ?>
              <input type="hidden" name="action" value="login">
              <input type="hidden" name="loginemail" value="<?= _e($oCustomer->contactPersonEmail);?>">
                            
            <div class="input-group mb-3">
            <input placeholder="Logincode *" class="form-control input-lg" autocomplete="off"
                  required autofocus  data-rule-required="true" data-msg-required="Voer de logincode in die u per e-mail heeft ontvangen."
                                           id="signup-login-debnr" name="logincode" type="text" value="">
              
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-hashtag"></span>
                </div>
              </div>
            </div>
            
            <div class="row">
              
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" value="Login" name="verzendBtn" class="btn btn-primary btn-block"><?= _e(SiteTranslations::get('customer_login')) ?></button>
              </div>
              <!-- /.col -->
            </div>
          </form>
        

      
      <p class="mb-1">
      
      </p>
      <!--<p class="mb-0">
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

  <script>
    document.getElementById('timer').innerHTML = 05 + ":" + 00;
startTimer();


function startTimer() {
  var presentTime = document.getElementById('timer').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1
    if(m<0){
      location.href = "/account";
    }
  }
  
  if (m>-1) {
    document.getElementById('timer').innerHTML =
      m + ":" + s;
    console.log(m)
    setTimeout(startTimer, 1000);
  } 
  
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}
  </script>
</body>

</html>





</body>

</html>