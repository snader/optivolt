
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
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->




<section class="section">
    <!-- Navigation Breadcrumbs -->
    <?php include getSiteSnippet('navigationBreadcrumbs'); ?>
    <!-- /Navigation Breadcrumbs -->
    <div class="container">
        <div class="columns">
            <div class="column is-half">
                <h1 class="title is-size-1"><?= _e($oPage->title) ?></h1>
                <?= $oPage->intro ?>
                <?= $oPage->content ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                    <form method="POST" class="validateFormInline">
                        <input type="hidden" value="confirm" name="action"/>
                        <?php
                        $aErrors = [];
                        if (!empty($aErrorsActivate)) {
                            $aErrors = $aErrorsActivate;
                        }
                        include getSiteSnippet('errorBox');
                        ?>

                        <div class="field">
                            <label class="label" for="email"><?= _e(SiteTranslations::get('site_email')) ?> *</label>
                            <div class="control has-icons-right">
                                <input placeholder="<?= _e(SiteTranslations::get('site_email')) ?> *" class="input" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_email')) ?>" data-rule-email="true" data-msg-email="<?= _e(SiteTranslations::get('site_email_not_valid')) ?>" id="email" name="email" type="email" value="<?= _e(http_post('email', http_get('email', http_session('accountConfirmEmail')))) ?>"/>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label" for="code"><?= _e(SiteTranslations::get('site_confirmation_code')) ?> *</label>
                            <div class="control has-icons-right">
                                <input placeholder="<?= _e(SiteTranslations::get('site_confirmation_code')) ?> *" class="input" data-rule-required="true"
                                       data-msg-required="<?= _e(SiteTranslations::get('site_confirmation_code_empty_check_spam')) ?>" id="code" name="code" type="text" value="<?= _e(http_post('code', http_get('code'))) ?>"/>
                            </div>
                        </div>
                        <button class="button is-primary" name="confirm"><?= _e(SiteTranslations::get('customer_account_confirm')) ?></button>
                    </form>
                
            </div>
        </div>
    </div>
</section>