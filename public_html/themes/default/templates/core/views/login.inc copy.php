<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= _e($oPageLayout->sWindowTitle) ?> | <?= _e(CLIENT_NAME) ?>">
    

    <title><?= _e($oPageLayout->sWindowTitle) ?> | <?= _e(CLIENT_NAME) ?></title>

    <!-- GOOGLE FONTS -->
    <!--<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />-->

    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="/assets/css/sleek.css" />

    <!-- FAVICON -->
    <link href="/themes/default/images/icons/favicon.ico" rel="shortcut icon" />

    <!--
      HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
    -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


  </head>

  <body class="" id="body">
    <div class="container d-flex align-items-center justify-content-center vh-100">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-10">
          <div class="card">
            <div class="card-header login-logo">
              
                <a target=_"blank" href="https://optivolt.nl">
                  <img alt="Optivolt" width="162" height="60" src="<?= getSiteImage('optivolt-logo-groot.png') ?>">
                </a>
              
            </div>

            <div class="card-body p-5">
              <h4 class="text-dark mb-5"><?= _e(SiteTranslations::get('customer_login')) ?></h4>
              
              <form action="" method="POST">
              <?= CustomerCSRFSynchronizerToken::field() ?>
              <input type="hidden" name="action" value="login">
                            <?php
                            // hack for multiple errorBoxes on one page
                            $aErrors = [];
                            if (!empty($aErrorsLogin)) {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                  <i class="mdi mdi-alert mr-1"></i> <?php
                                  foreach ($aErrorsLogin as $sError) {
                                    echo $sError . '</br>';
                                  }
                                  ?>
                                </div>
                                <?php
                            }
                            
                            ?>
                <div class="row">
                  <div class="form-group col-md-12 mb-4">
                  <input placeholder="<?= _e(SiteTranslations::get('site_debnr')) ?> *" class="form-control input-lg" autocomplete="off"
                  required data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_debnr')) ?>"
                                           id="signup-login-debnr" name="debnr" type="text" value="<?= _e(http_post('debnr')) ?>">
              
                  </div>

                  <div class="form-group col-md-12 ">
                  <input placeholder="<?= _e(SiteTranslations::get('site_password')) ?> *" class="form-control input-lg" autocomplete="off"
                  required data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_password')) ?>"
                                           id="signup-login-password" name="password" type="password" value="<?= _e(http_post('password')) ?>"/>
               
                  </div>

                  <div class="col-md-12">
                    <div class="d-flex my-2 justify-content-between">
                      <div class="d-inline-block mr-3">
                        <label class="control control-checkbox">Remember me
                          <input type="checkbox" />
                          <div class="control-indicator"></div>
                        </label>
                      </div>

                      <p><a href="<?= PageManager::getPageByName('account_forgot_password')->getBaseUrlPath() ?>" class="text-blue"><?= _e(SiteTranslations::get('site_forgot_your_password_short')) ?></a></p>
                      
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary btn-block mb-4"><?= _e(SiteTranslations::get('customer_login')) ?></button>

                    
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Javascript -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/sleek.js"></script>

</body>
</html>