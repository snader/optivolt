<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">

        <h1 class="m-0"><i aria-hidden="true" class="fas fa-check-double"></i>&nbsp;&nbsp;Checklist Power quality inventarisations</h1></i>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Formulieren</h3>

            <div class="card-tools">
              <div class="input-group input-group-sm" style="width: auto;">
              <form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST" class="form-inline pr-2">
                <input type="text" name="inventarisationsFilter[q]" value="<?= $aInventarisationFilter['q'] ?>" id="q" class="form-control form-control-sm float-right" placeholder="Zoeken">
                <div class="input-group-append">
                  <input type="submit" name="filterLogs" value="Filter" class="btn btn-default btn-sm">
                  <input class="btn btn-default btn-sm" type="submit" name="resetFilter" value="Reset">
                </button>
                </div>
                <div class="input-group-append">
                    <a class="addBtn" href="<?= ADMIN_FOLDER ?>/inventarisations/toevoegen" title="<?= sysTranslations::get('add_item') ?>">
                        <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                    </a>&nbsp;
                </div>
              </form>
              
              </div>
              
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th style="width:50px;"></th>
                  <th>Klant</th>
                  <th>Info</th>    
                  <th>Remarks</th>              
                  <th>Aangemaakt op</th>
                  <th>User</th>
                  <th style="width:50px;"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($aInventarisations AS $oInventarisation) {
                ?>
                  <tr>
                    <td>
                      <?php

                      if ($oInventarisation->isEditable()) {
                      ?>
                        <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oInventarisation->inventarisationId ?>" title="Bewerken">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                      <?php } else {
                        echo '<i class="fas fa-pencil-alt"></i>';
                      } ?>
                    </td>

                    <td><?= ($oInventarisation->customerName ? _e($oInventarisation->customerName) : CustomerManager::getCustomerById($oInventarisation->customerId)->companyName) ?></td>
                    <td><?= ($oInventarisation->name ? _e($oInventarisation->name) : $oInventarisation->type) ?></td>
                    <td><?= ($oInventarisation->remarks ? _e(firstXWords($oInventarisation->remarks,5)) : '-') ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($oInventarisation->created)) ?></td>
                    <td><?= Usermanager::getUserById($oInventarisation->userId)->name?>
                    <td>
                    <?php
              
                      if ($oInventarisation->isDeletable()) {                                   
                      ?>
                        <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oInventarisation->inventarisationId ?>" title="Verwijderen" onclick="return confirmChoice('Verwijder dit record?');">
                          <i class="fas fa-trash"></i>
                        </a>
                      <?php } else { ?><span class="btn btn-danger btn-xs disabled"><i class="fas fa-trash"></i></span><?php } ?>

                    </td>
                  </tr>
                <?php
                }
                ?>

              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <form method="POST">
                <?= generatePaginationHTMLAdminLTE($iPageCount, $iCurrPage) ?> 
                <input type="hidden" name="setPerPage" value="1"/>
                <select name="perPage" class="form-control form-control-sm float-left" style="width:55px;" onchange="$(this).closest('form').submit();">                    
                    <option <?= $iPerPage == 10 ? 'SELECTED' : '' ?> value="10">10</option>
                    <option <?= $iPerPage == 25 ? 'SELECTED' : '' ?> value="25">25</option>
                    <option <?= $iPerPage == 50 ? 'SELECTED' : '' ?> value="50">50</option>
                    <option <?= $iPerPage == 100 ? 'SELECTED' : '' ?> value="100">100</option>
                </select> <span>&nbsp;<?= sysTranslations::get('global_per_page') ?></span>&nbsp;&nbsp;<span style="color:#888;">(<?=$iFoundRows?> records gevonden)</span>
            </form>
          
          </div>
          
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div>
</section>
