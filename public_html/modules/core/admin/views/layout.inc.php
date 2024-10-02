<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <title><?= _e($oPageLayout->sWindowTitle) ?> - <?= _e(CLIENT_NAME) ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="/plugins/ekko-lightbox/ekko-lightbox.css">
    <!-- datatables -->
    <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- table cell selection -->
    <link rel="stylesheet" href="/plugins/tablecellsselection/tablecellsselection.css">

    <!-- signature -->
    <link rel="stylesheet" type="text/css" href="/plugins/jquerySignature4/css/signature-pad.css?time=<?= time() ?>">

    <!-- Select2 -->
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- custom  -->
    <link rel="stylesheet" href="<?= getAdminCss('style') ?>?c=<?= time() ?>" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php

    # stylesheet stuff
    foreach ($oPageLayout->getStylesheets() as $oStylesheet) {
        if ($oStylesheet->getLocation()) {
            echo '<link rel="stylesheet" href="' . _e($oStylesheet->getLocation()) . '" />' . PHP_EOL;
        } else {
            echo '<style>' . $oStylesheet->getStyles() . '</style>' . PHP_EOL;
        }
    }
    ?>

    <?php
    # javascript top stuff
    foreach ($oPageLayout->getJavascripts('top') as $oJavascript) {
        if ($oJavascript->getLocation()) {
            echo '<script src="' . _e($oStylesheet->getLocation()) . '"></script>' . PHP_EOL;
        } else {
            echo '<script>' . $oStylesheet->getScript() . '</script>' . PHP_EOL;
        }
    }
    ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- wrapper -->

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?= getSiteImage('logo.png') ?>" alt="<?= CLIENT_NAME ?>" height="60" width="60">
        </div>


        <?php include_once getAdminSnippet('header') ?>

        <?php
        $sLeftMenu = 'leftMenu';
        //if (file_exists(SYSTEM_MODULES_FOLDER . '/core/admin/snippets/leftMenu' . $oCurrentUser->userAccessGroupId . '.inc.php')) {
        //$sLeftMenu = $sLeftMenu . $oCurrentUser->userAccessGroupId;
        //}
        include_once getAdminSnippet($sLeftMenu);


        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <?php

            if (isset($oCurrentModule)) {
            ?>
                <div class="content-header" style="display:none;">
                    <div class="container-fluid">

                        <?php
                        // TO CUSTOMER BUTTON RIGHT TOP
                        if (isset($oLocation) && $oLocation->getCustomer()) {
                        ?>
                            <span class="float-sm-right">
                                <a class="backBtn right" href="/customers/bewerken/?<?= $oLocation->getCustomer()->customerId ?>">
                                    <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?> <?= _e($oLocation->getCustomer()->companyName) ?>">
                                        <?= sysTranslations::get('to_customer') ?>
                                    </button>
                                </a>
                            </span>
                        <?php
                        }
                        ?>
                        <div class="row mb-2">

                            <div class="col-sm-12">
                                <h1 class="m-0"><i aria-hidden="true" class="fa <?= !empty($oCurrentModule) ? $oCurrentModule->icon : 'fa-th-large' ?> "></i>&nbsp;<?= sysTranslations::get($oCurrentModule->linkName) ?>
                                    <?php
                                    if (isset($oLocation) && $oLocation->getCustomer()) {
                                        echo ' - ' . _e($oLocation->getCustomer()->companyName);
                                    }
                                    ?>

                                </h1>
                            </div>

                        </div>

                    </div><!-- /.container-fluid -->
                </div>
            <?php } ?>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <?php
                # include the actual page with changable content
                include_once $oPageLayout->sViewPath;
                ?>
                <br /><br />
            </section>
            <!-- /.content -->





        </div> <!-- // wrapper -->

        <script>
            var DEBUG = <?= DEBUG ? '1' : '0'; ?>;
        </script>

        <!-- jQuery -->
        <script src="/plugins/jquery/jquery.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.js" data-turbolinks-track="true"></script>

        <!-- bs-custom-file-input -->
        <script src="/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <!-- daterangepicker -->
        <script src="/plugins/moment/moment.min.js"></script>
        <script src="/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- Summernote -->
        <script src="/plugins/summernote/summernote-bs4.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- jquery-validation -->
        <script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="/plugins/jquery-validation/additional-methods.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="/plugins/jszip/jszip.min.js"></script>
        <script src="/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <!-- Ekko Lightbox -->
        <script src="/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>

        <!-- tablecellsselection -->
        <script src="/plugins/tablecellsselection/tablecellsselection.js"></script>

        <!-- Select2 -->
        <script src="/plugins/select2/js/select2.full.min.js"></script>
        <!--<script src="/plugins/dragscroll/dragscroll.js"></script>-->

        <!--<script src="/plugins/double-scroll/jquery.doubleScroll.js"></script>-->


        <!-- signature -->
        <script src="/plugins/jquerySignature4/js/signature_pad.umd.js"></script>
        <!--<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>-->

        <!-- AdminLTE App -->
        <script src=" /dist/js/adminlte.js"></script>

        <?php
        $jsTranslations = json_encode(sysTranslations::getTranslations('js'));
        $jsLang         = empty($oCurrentUser) ? 'nl' : $oCurrentUser->getLanguage()->abbr;
        ?>
        <script>
            var jsTranslations = <?= $jsTranslations ?>;
        </script>
        <script src="<?= getAdminJs('base_functions') ?>?t=<?= time() ?>"></script>
        <script>
            // if there is a statusUpdate, show it

            <?php if (is_array($oPageLayout->sStatusUpdate)) {
            ?>
                showStatusUpdate('<?= isset($oPageLayout->sStatusUpdate['text']) ? $oPageLayout->sStatusUpdate['text'] : 'Something went wrong' ?>', '<?= isset($oPageLayout->sStatusUpdate['type']) ? $oPageLayout->sStatusUpdate['type'] : 'error' ?>');
            <?php } else { ?>
                if ('<?= $oPageLayout->sStatusUpdate ?>' != '') {
                    showStatusUpdate('<?= $oPageLayout->sStatusUpdate ?>', 'success');
                }
            <?php } ?>


            // Set template constant
            var siteTemplate = '<?= SITE_TEMPLATE ?>';
            var globalFunctions = {}; // global functions object for functions with variable

            //autofocus element
            $(".autofocus").focus();

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });


            $(document).ready(function() {
                //Initialize Select2 Elements
                $('.select2').select2()

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })


            });
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });
        </script>
        <?php

        # javascript bottom stuff
        foreach ($oPageLayout->getJavascripts() as $oJavascript) {
            if ($oJavascript->getLocation()) {
                echo '<script src="' . _e($oJavascript->getLocation()) . '"></script>' . PHP_EOL;
            } else {
                echo '<script>' . $oJavascript->getScript() . '</script>' . PHP_EOL;
            }
        }
        ?>

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