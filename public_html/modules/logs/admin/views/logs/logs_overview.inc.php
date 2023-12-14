<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">

        <h1 class="m-0"><i aria-hidden="true" class="fas fa-flag"></i>&nbsp;&nbsp;Logboek</h1></i>
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
            <h3 class="card-title">Mutaties</h3>

            <div class="card-tools">
              <div class="input-group input-group-sm" style="width: auto;">
              <form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST" class="form-inline pr-2">
                <input type="text" name="logsFilter[q]" value="<?= $aLogFilter['q'] ?>" id="q" class="form-control form-control-sm float-right" placeholder="Zoeken">
                <div class="input-group-append">
                  <input type="submit" name="filterLogs" value="Filter" class="btn btn-default btn-sm">
                  <input class="btn btn-default btn-sm" type="submit" name="resetFilter" value="Reset">
                </button>
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
                  
                  <th>Gebruiker</th>
                  <th>Datum/tijd</th>
                  <th>Mutatie</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($aLogs as $oLog) {
                ?>
                  <tr>
                    
                    <td><?= _e($oLog->name) ?></td>
                    <td><?php
                            echo date('d-m-Y H:i', strtotime($oLog->created));
                        ?></td>
                    

                    <td <?= !empty($oLog->content) ? 'class="show-detail"': '' ?>><?= _e($oLog->title) ?><?= !empty($oLog->content) ? '&nbsp;&nbsp;&nbsp;<i class="fas fa-caret-down"></i>' : '' ?>
                        <div class="log-detail"><?= nl2br($oLog->content)?></div></td>
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
                <select name="perPage" class="form-control form-control-sm float-left" style="width:75px;" onchange="$(this).closest('form').submit();">                    
                    <option <?= $iPerPage == 10 ? 'SELECTED' : '' ?> value="10">10</option>
                    <option <?= $iPerPage == 25 ? 'SELECTED' : '' ?> value="25">25</option>
                    <option <?= $iPerPage == 50 ? 'SELECTED' : '' ?> value="50">50</option>
                    <option <?= $iPerPage == 100 ? 'SELECTED' : '' ?> value="100">100</option>
                </select> <span>&nbsp;<?= sysTranslations::get('global_per_page') ?></span>
            </form>
          
          </div>
          
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div>
</section>

<?php

# add ajax code for online/offline handling
$sJavascript = <<<EOT
<script>
$(document).ready(function(){
    $('.show-detail').click(function(){


        $('.log-detail').hide();
        $(this).find('.log-detail').show();
    });
}); 


</script>
EOT;
$oPageLayout->addJavascript($sJavascript);

