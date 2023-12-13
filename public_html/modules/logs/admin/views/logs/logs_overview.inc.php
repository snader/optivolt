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

