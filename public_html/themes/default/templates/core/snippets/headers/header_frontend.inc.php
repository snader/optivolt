<?php
// Set extra header classes to do some magic
if (!isset($sHeaderClasses)) {
    $sHeaderClasses = '';
}
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a target="_blank" href="https://optivolt.nl/contact" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
  
    <?php 
    if (Customer::getCurrent()) { ?>
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="/themes/default/images/avatar.png" class="user-image img-circle elevation-2" alt="<?= Customer::getCurrent()->companyName ?>">
          <span class="d-none d-md-inline">Account</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="/themes/default/images/avatar.png" class="img-circle elevation-2" alt="<?= Customer::getCurrent()->companyName ?>">

            <p>
            <?= Customer::getCurrent()->companyName ?>
              <small><?= Customer::getCurrent()->companyCity ?></small>
            </p>
          </li>
          <!-- Menu Body -->
          <li class="user-body">
            <div class="row">
              <div class="col-4 text-center">
                Deb# <?= Customer::getCurrent()->debNr ?>
              </div>
              
              <div class="col-8 text-right">
                <?php
                $sDate = date('d-m-Y', strtotime(Customer::getCurrent()->lastLogin));
                if ($sDate != '01-01-1970') { echo $sDate; }
                ?>
              </div>
            </div>
            <!-- /.row -->
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="<?= PageManager::getPageByName("account_edit")->getBaseUrlPath() ?>" class="btn btn-default btn-flat"><?= PageManager::getPageByName("account_edit")->title ?></a>
            <a href="<?= PageManager::getPageByName("account_logout")->getBaseUrlPath() ?>" class="btn btn-default btn-flat float-right"><?= PageManager::getPageByName("account_logout")->title ?></a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" title="<?= PageManager::getPageByName("account_logout")->title ?>" data-widget="fullscreen" href="<?= PageManager::getPageByName("account_logout")->getBaseUrlPath() ?>" role="button">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
      <?php } ?>
        
     
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      
    </ul>
  </nav>