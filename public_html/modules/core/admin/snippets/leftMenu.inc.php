<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= CLIENT_HTTP_URL ?>/dashboard" class="brand-link">
    <img src="<?= getSiteImage('logo.png') ?>" alt="<?= CLIENT_NAME ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?= CLIENT_NAME ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php
        if ($oUserProfileImage = $oCurrentUser->getImage(true)) {
          $oReference = $oUserProfileImage->getImageFileByReference('user_image');
        ?>
          <img src="<?= $oReference->link ?>" class="img-circle elevation-2" alt="<?= $oCurrentUser->name ?>">
        <?php
        } else {
        ?>
          <img src="<?= getSiteImage('avatar.png') ?>" class="img-circle elevation-2" alt="<?= $oCurrentUser->name ?>">
        <?php
        }
        ?>
      </div>
      <div class="info">
        <a title="Naar het dashboard" href="<?= CLIENT_HTTP_URL ?>/dashboard" class="d-block"><?= _e($oCurrentUser->name) ?></a>
      </div>
    </div>

    <!-- Sidebar -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <?php

        $iT = 1;
        $oCurrentUser->getUserAccessGroup(true); // reset to rebuild menu
        // overrule sorting of menu by sorting on translation when order is same as others
        $aMainModulesForMenu           = $oCurrentUser->getUserAccessGroup()
          ->getModules();
        $aMainModulesForMenuByOrderKey = [];
        foreach ($aMainModulesForMenu as $oMainModuleForMenu) {
          $aMainModulesForMenuByOrderKey[sprintf('%05d', $oMainModuleForMenu->order) . '-' . sysTranslations::get($oMainModuleForMenu->linkName)] = $oMainModuleForMenu;
        }

        ksort($aMainModulesForMenuByOrderKey);

        foreach ($aMainModulesForMenuByOrderKey as $oMainModuleForMenu) {

          // sort on translation
          $aChildrenForMenu           = $oMainModuleForMenu->getChildren();
          $aChildrenForMenuByOrderKey = [];
          foreach ($aChildrenForMenu as $oChildForMenu) {
            $aChildrenForMenuByOrderKey[sprintf('%05d', $oChildForMenu->order) . '-' . sysTranslations::get($oChildForMenu->linkName)] = $oChildForMenu;
          }

          ksort($aChildrenForMenuByOrderKey);

          $bHasChildren = count($aChildrenForMenuByOrderKey) > 0;
          $bIsActive    = strtolower($oMainModuleForMenu->name  ?? '') == strtolower(http_get("controller") ?? '');

          if (strtolower($oMainModuleForMenu->name ?? '') == strtolower(http_get("controller") ?? '')) {
            $oCurrentModule = $oMainModuleForMenu;
          }

          if (!$bHasChildren) {

        ?>
            <li class="nav-item">
              <a href="<?= ADMIN_FOLDER . '/' . $oMainModuleForMenu->name ?>" class="nav-link<?= $bIsActive ? ' active' : '' ?>">
                <?= (!empty($oMainModuleForMenu->icon) ? '<i class="nav-icon fas ' . $oMainModuleForMenu->icon . '"></i>' : '') ?>
                <p>
                  <?= sysTranslations::get($oMainModuleForMenu->linkName) ?>
                </p>

              </a>
            </li>
          <?php

          } else {

          ?>
            <!-- collapse menu -->
            <?php
            $sClassesSub = 'nav-item';
            $sClassesSub .= $bIsActive || $oMainModuleForMenu->hasChild(strtolower(http_get("controller") ?? '')) ? ' menu-is-opening menu-open ' : ''; // this item is active
            ?>
            <li class="<?= $sClassesSub ?>">
              <a href="<?= ADMIN_FOLDER . '/' . $oMainModuleForMenu->name ?>" class="nav-link <?= ($bIsActive || $oMainModuleForMenu->hasChild(strtolower(http_get("controller") ?? '')) ? ' active' : '') ?>">
                <?= (!empty($oMainModuleForMenu->icon) ? '<i class="nav-icon fas ' . $oMainModuleForMenu->icon . '"></i>' : '') ?>
                <p>
                  <?= sysTranslations::get($oMainModuleForMenu->collapseName) ?>
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">

                <li class="nav-item">
                  <a href="<?= ADMIN_FOLDER . '/' . $oMainModuleForMenu->name ?>" class="nav-link<?= $bIsActive ? ' active' : '' ?>">
                    &nbsp;
                    <?= (!empty($oMainModuleForMenu->icon) ? '<i class="nav-icon fas ' . $oMainModuleForMenu->icon . '"></i>' : '') ?>
                    <p>
                      <?= sysTranslations::get($oMainModuleForMenu->linkName) ?>
                    </p>

                  </a>
                </li>

                <?php

                foreach ($aChildrenForMenuByOrderKey as $oChild) {
                  if (strtolower($oChild->name ?? '') == strtolower(http_get("controller") ?? '')) {
                    $oCurrentModule = $oChild;
                  }
                  $sClassesSub = 'nav-item';
                  $sClassesSub .= strtolower($oChild->name ?? '') == strtolower(http_get("controller") ?? '') ? ' menu-is-opening menu-open ' : ''; // this item is active

                ?>
                  <li class="<?= $sClassesSub ?>
                            <?= ($oChild->linkName == 'locations_menu' ? ' hide' : '') ?>
                            <?= ($oChild->linkName == 'system_reports_menu' ? ' hide' : '') ?>">
                    <a href="<?= ADMIN_FOLDER . '/' . $oChild->name ?>" class="nav-link<?= (strtolower($oChild->name ?? '') == strtolower(http_get("controller") ?? '') ? ' active ' : '') ?>">
                      &nbsp;
                      <?= (!empty($oChild->icon) ? '<i class="nav-icon fas ' . $oChild->icon . '"></i>' : '') ?>
                      <p>
                        <?= sysTranslations::get($oChild->linkName) ?>
                      </p>
                    </a>
                  </li>
                <?php

                }
                ?>
              </ul>


            </li>
        <?php


          }
        }
        ?>




      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>