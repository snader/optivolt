<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item" id="sidebar-toggle">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="/dashboard" class="nav-link">Home</a>
    </li>

    <li class="nav-item d-none d-sm-inline-block pt-2">

      <?php
      if (($oCurrentUser && $oCurrentUser->isSuperAdmin()) || !empty($_SESSION['fastLoginEnabled'])) {
      ?>

      <?php

        echo ' <select style="color: #00000080; border:0;" id="fastlogin" name="fastlogin" onchange="window.location=\'/dashboard/login/fastlogin/\'+this.value;">';
        echo '<option value="">' . sysTranslations::get('global_login_as') . '</option>';
        foreach (UserManager::getUsersByFilter() as $oFastLoginUser) {
          echo '<option value="' . $oFastLoginUser->userId . '">' . $oFastLoginUser->name . '</option>';
        }
        echo '</select>';
      }
      ?>
    </li>
    <li class="nav-item d-none d-sm-inline-block pt-2 pl-2">
      <?php

      setlocale(LC_TIME, 'nl_NL');
echo strftime('%e %B %Y', time());

      ?>
    </li>


  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" title="Full screen" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <?php
    if ($oCurrentUser) { ?>
      <li class="nav-item">
        <a class="nav-link" href="?logout=<?= md5(rand()) ?>" title="<?= sysTranslations::get('global_logout') ?>" role="button">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
    <?php } ?>
  </ul>
</nav>
<!-- /.navbar -->

<?php
$sBottomJavascript = <<<EOT
<script>

function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }

    function getCookie(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }

    function eraseCookie(key) {
        var keyValue = getCookie(key);
        setCookie(key, keyValue, '-1');
    }

$( document ).ready(function() {
  if (getCookie('sidebar') == '0') {
    $("body").removeClass('sidebar-collapse')
  } else {
    $("body").addClass('sidebar-collapse')
  }

  $('#sidebar-toggle').click(function() {

    event.preventDefault();
   if (getCookie('sidebar') == 1) {
      setCookie('sidebar', '0');
    } else {
      setCookie('sidebar', '1');
    }
  });
});

</script>


EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>