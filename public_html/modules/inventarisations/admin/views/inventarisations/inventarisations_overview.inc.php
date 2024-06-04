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
                  
                  <th>Klant</th>
                  <th>Naam</th>
                  <th>Logger</th>
                  <th>Datum</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($aInventarisations AS $oInventarisation) {
                ?>
                  <tr>
                    <td><?= _e($oInventarisation->customerName) ?></td>
                    <td><?= _e($oInventarisation->name) ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($oLog->created)) ?></td>
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
