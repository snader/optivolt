<div class="sidebar ">
      
    <!-- Sidebar Menu -->
    <?php
    if (Customer::getCurrent()) { ?>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
        
        <li class="nav-item menu-is-opening menu-open">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

        <?php
        $aUrl = [];

        function makeNavTree($aPages)
        {
            global $aUrl;
            if (count($aPages) > 0) {
                echo '<ul class="nav nav-treeview">';
                foreach ($aPages AS $oPage) {
                    $iLevel = $oPage->level;

                    if ($iLevel == 1) {
                        $aUrl[$iLevel] = '/' . http_get('controller');
                    } else {
                        $aUrl[$iLevel] = $aUrl[$iLevel - 1] . '/' . http_get('param' . ($iLevel - 1));
                    }

                    $sIcon = 'circle';
                    if ($oPage->name == 'home') {
                      $sIcon = 'building';
                    }
                    if ($oPage->name == 'system_reports') {
                      $sIcon = 'file-pdf';
                    }

                    // check if controller equals page urlPath be aware of special treathment for homepage!!
                    echo '<li class="nav-item">' . PHP_EOL;
                    echo '  <a class="nav-link ' . ($aUrl[$oPage->level] == $oPage->getUrlPath() || '/' . http_get('controller') == $oPage->getUrlPath() . PHP_EOL . 'home' ? 'active' : '');
                    echo '" href="' . $oPage->getBaseUrlPath() . '">' . PHP_EOL;
                    echo '  <i class="far fa-' . $sIcon . ' nav-icon"></i>' . PHP_EOL;
                    echo '  <p>' . $oPage->getShortTitle() . '</p>' . PHP_EOL;
                    echo '</a>' . PHP_EOL;
                    makeNavTree($oPage->getSubPages()); //call function recursive
                    echo PHP_EOL . '</li>' . PHP_EOL;
                }
                echo '</li>';
            }
        }

        makeNavTree(PageManager::getPagesByFilter(['level' => 1, 'inMenu' => 1, 'languageId' => Locales::language()]));
        ?>


      </li>
        
        
        
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
      <?php } ?>
    

</div>
