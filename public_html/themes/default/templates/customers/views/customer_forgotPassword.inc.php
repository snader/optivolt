
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
                <form method="POST" class="validateFormInline">
                    <?= CustomerCSRFSynchronizerToken::field() ?>
                    <input type="hidden" value="requestPassword" name="action"/>
                    <div class="form-group">
                        <label for="debnr"><?= _e(SiteTranslations::get('site_debnr')) ?></label>
                        <input type="text" required value="<?php http_post('debnr') ?>" id="debnr" name="debnr" class="form-control" placeholder="<?= _e(SiteTranslations::get('site_debnr')) ?>">                    
                    </div>
                    <div class="form-group">
                        <label for="email"><?= _e(SiteTranslations::get('site_email_contact')) ?></label>
                        <input type="email" required value="<?php http_post('email') ?>" id="email" name="email" class="form-control" placeholder="<?= _e(SiteTranslations::get('site_email_contact')) ?>">                    
                    </div>
                
                    <button type="submit" name="requestPassword" class="btn btn-primary btn-default"><?= _e(SiteTranslations::get('site_send_me_a_reset_link')) ?></button>
    
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

