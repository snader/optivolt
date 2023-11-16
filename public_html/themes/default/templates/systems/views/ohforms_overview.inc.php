
<!-- Content Header (Page header) -->
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?= $oPage->shortTitle ?></h1>
          </div>
          <div class="col-sm-6">
		  	<?php include getSiteSnippet('navigationBreadcrumbs'); ?>            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $oPage->title ?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
		    <?= $oPage->intro ?><?= $oPage->content ?>

            <div class="row">
            <div class="col-12">
                
                                               
                <table class="table table-hover text-nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Uitgevoerd door</th>                        
                        <th>Ondertekend door</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($aAppointments as $oAppointment) { 
                            
                            
                            $oUser = UserManager::getUserById($oAppointment->userId);
                            ?>
                            <tr>
                            <td><?= date('d-m-Y', strtotime($oAppointment->visitDate)) ?></td>
                            <td class="d-none d-lg-table-cell"><?= _e($oUser->getDisplayName()) ?></td>
                            <td class="d-none d-lg-table-cell"><?= _e($oAppointment->signatureName) ?></td>
                            <td >
                                <span class="badge badge-<?= ($oAppointment->finished) ? 'success' : 'warning'?>"><?= ($oAppointment->finished) ? 'Afgerond' : 'Ingepland'?></span>
                            </td>
                            <td class="project-actions text-right">
                            <?php
                                if ($oAppointment->finished) { ?>
                                <a title="Download PDF Onderhoudsformulier" class="btn btn-primary btn-sm" href="/<?= http_get("controller") ?>/<?= $oAppointment->appointmentId ?>/pdf">
                                    <i class="fas fa-file-pdf">
                                    </i>
                                    &nbsp;PDF download
                                </a>
                                
                                
                                <?php
                                }
                                ?>
                            </td>
                            </tr>
                            
                        <?php }
                        ?>
                        
                    
                    </tbody>
                </table>
                        
            </div>
        </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->

