<aside class="left-sidebar bg-sidebar">
          <div id="sidebar" class="sidebar sidebar-with-footer">
            <!-- Aplication Brand -->
            <div class="app-brand">
              <a href="/" title="Optivolt OMS">
                <img alt="Optivolt" src="<?= getSiteImage('optivolt-icon.png') ?>" >
                <img src="<?= getSiteImage('optivolt-icon-next.png') ?>">
              </a>
            </div>

            <!-- begin sidebar scrollbar -->
            <div class="" data-simplebar style="height: 100%;">
              <!-- sidebar menu -->
              <ul class="nav sidebar-inner" id="sidebar-menu">
                <li class="has-sub active expand">
                  <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#dashboard"
                    aria-expanded="false" aria-controls="dashboard">
                    <i class="mdi mdi-view-dashboard-outline"></i>
                    <span class="nav-text">Dashboard</span> <b class="caret"></b>
                  </a>

                  
                  <?php
                    $aUrl = [];

                    function makeNavTree($aPages)
                    {
                        global $aUrl;
                        if (count($aPages) > 0) {
                            echo '<ul class="collapse show" id="dashboard" data-parent="#sidebar-menu"><div class="sub-menu">';
                            foreach ($aPages AS $oPage) {
                                $iLevel = $oPage->level;

                                if ($iLevel == 1) {
                                    $aUrl[$iLevel] = '/' . http_get('controller');
                                } else {
                                    $aUrl[$iLevel] = $aUrl[$iLevel - 1] . '/' . http_get('param' . ($iLevel - 1));
                                }

                                // check if controller equals page urlPath be aware of special treathment for homepage!!
                                echo '<li class="' . ($aUrl[$oPage->level] == $oPage->getUrlPath() || '/' . http_get('controller') == $oPage->getUrlPath() . PHP_EOL . 'home' ? 'is-active' : '') . ($oPage->getSubPages()  ? ' has-sub' : '') . '"><a class="sidenav-item-link" href="' . $oPage->getBaseUrlPath() . '">' . $oPage->getShortTitle() . '</a>';
                                makeNavTree($oPage->getSubPages()); //call function recursive
                                echo PHP_EOL . '</li>' . PHP_EOL;
                            }
                            echo '</div></ul>';
                        }
                    }

                    makeNavTree(PageManager::getPagesByFilter(['level' => 1, 'inMenu' => 1, 'languageId' => Locales::language()]));
                    ?>
                  
                </li>

                

               

                

              </ul>
            </div>

            <?php
            if (Customer::getCurrent()) { ?>
            <div class="sidebar-footer">
              <hr class="separator mb-0" />
              <div class="sidebar-footer-content">
                <h6 class="text">
                  <?= Customer::getCurrent()->companyName ?>
                </h6>

                <div class="text">                
                  <?= Customer::getCurrent()->companyAddress ?>
                </div>

                <div class="text">                
                  <?= Customer::getCurrent()->companyPostalCode ?>
                </div>

                <div class="text">                
                  <?= Customer::getCurrent()->companyCity ?>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </aside>