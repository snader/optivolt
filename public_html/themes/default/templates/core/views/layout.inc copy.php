<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />    
    <?php
    if (!empty($oPageLayout->sMetaDescription)) {
        echo '<meta name="description" content="' . _e($oPageLayout->sMetaDescription) . '">' . PHP_EOL;
    }
    ?>

    <meta name="theme-name" content="LV" />
    
    <title><?= _e($oPageLayout->sWindowTitle) ?> | <?= _e(CLIENT_NAME) ?></title>
    
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href='/assets/plugins/daterangepicker/daterangepicker.css' rel='stylesheet'>   
    <link href='/assets/plugins/toastr/toastr.min.css' rel='stylesheet'>
    
       

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
    <script src="/assets/plugins/nprogress/nprogress.js"></script>
  </head>

  <body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
    
    <div id="toaster"></div>

    <!-- ====================================
    ——— WRAPPER
    ===================================== -->
    <div class="wrapper">

      
        <!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
        <!-- Navigation -->
        <?php include getSiteSnippet('navigationFixed'); ?>
        <!-- Navigation -->
        


          <!-- ====================================
        ——— PAGE WRAPPER
        ===================================== -->
        <div class="page-wrapper">
          
        <!-- Header -->
        <?php include getSiteSnippet('headers/header_frontend'); ?>
        <!-- /Header -->

                    
        <!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
        <div class="content-wrapper">
        <div class="content">

        <?php
        
            // include the actual page with changable content
            include_once $oPageLayout->sViewPath;
        ?>
        </div> <!-- End Content -->
        </div> <!-- End Content Wrapper -->
    
  
    <!-- Footer -->
    <?php include getSiteSnippet('footers/footer_frontend'); ?>
    

    </div> <!-- End Page Wrapper -->
  </div> <!-- End Wrapper -->

    <!-- Javascript -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script src='/assets/plugins/daterangepicker/moment.min.js'></script>
    <script src='/assets/plugins/daterangepicker/daterangepicker.js'></script>
    <script src='/assets/js/date-range.js'></script>
    <script src='/assets/plugins/toastr/toastr.min.js'></script>
    <script src="/assets/js/sleek.js"></script>

</body>
</html>

