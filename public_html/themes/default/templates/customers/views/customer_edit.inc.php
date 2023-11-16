
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
            <div class="col-12 col-md-6"><br />
                <form method="POST" class="validateFormInline">
                    <?= CustomerCSRFSynchronizerToken::field() ?>
                    <input type="hidden" value="doNothing" name="action"/><!-- blocked for now -->
                    <div class="form-group">
                        <label for="debnr"><?= _e(SiteTranslations::get('site_debnr')) ?></label>
                        <input type="text" readonly value="<?= Customer::getCurrent()->debNr ?>" id="debNr" name="debNr" class="form-control" >                    
                    </div>
                    <div class="form-group">
                        <label for="companyName"><?= _e(SiteTranslations::get('site_companyname')) ?></label>
                        <input type="text" readonly value="<?= Customer::getCurrent()->companyName ?>" id="companyName" name="companyName" class="form-control" >                    
                    </div>
                    <div class="form-group">
                        <label for="companyAddress"><?= _e(SiteTranslations::get('site_companyaddress')) ?></label>
                        <input type="text" readonly value="<?= Customer::getCurrent()->companyAddress ?>" id="companyAddress" name="companyAddress" class="form-control" >                    
                    </div>
                    <div class="form-group">
                        <label for="companyPostalCode"><?= _e(SiteTranslations::get('site_companypostalcode')) ?></label>
                        <input type="text" readonly value="<?= Customer::getCurrent()->companyAddress ?>" id="companyPostalCode" name="companyPostalCode" class="form-control" >                    
                    </div>
                    <div class="form-group">
                        <label for="companyCity"><?= _e(SiteTranslations::get('site_companycity')) ?></label>                                        
                        <input type="text" readonly value="<?= Customer::getCurrent()->companyCity ?>" id="companyCity" name="companyCity" class="form-control">                    
                    </div>
                    <div class="form-group">
                        <label for="contactPersonName"><?= _e(SiteTranslations::get('site_contactpersonname')) ?></label>                                        
                        <input type="text" readonly value="<?= Customer::getCurrent()->contactPersonName ?>" id="contactPersonName" name="contactPersonName" class="form-control" >                    
                    </div>
                    <div class="form-group">
                        <label for="contactPersonEmail"><?= _e(SiteTranslations::get('site_contactpersonemail')) ?></label>                                        
                        <input type="text" readonly value="<?= Customer::getCurrent()->contactPersonEmail ?>" id="contactPersonEmail" name="contactPersonEmail" class="form-control" >                    
                    </div>
                    <div class="form-group">
                        <label for="contactPersonPhone"><?= _e(SiteTranslations::get('site_contactpersonphone')) ?></label>                                        
                        <input type="text" readonly value="<?= Customer::getCurrent()->contactPersonPhone ?>" id="contactPersonPhone" name="contactPersonPhone" class="form-control" >                    
                    </div>
                
                    
                </form>
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
        
