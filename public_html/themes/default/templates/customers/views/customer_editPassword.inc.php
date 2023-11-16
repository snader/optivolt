
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

            <?php
        if (http_post('email', http_get('email')) && http_post('code', http_get('code'))) {
        ?>
        <div class="row">
            <div class="col-12">
                <?php

                    include getSiteSnippet('errorBox');

                ?>
                    <form method="POST" class="validateFormInline">
                        <?= CustomerCSRFSynchronizerToken::field() ?>
                        <input type="hidden" value="updatePassword" name="action"/>
                        <input type="hidden" autocomplete="off" required id="code" name="code" value="<?= http_post('code', http_get('code')) ?>"/>
                        
                        <div class="form-group">
                            <label class="label" for="email"><?= _e(SiteTranslations::get('site_email_contact')) ?> *</label>
                      
                                <input class="form-control" readonly placeholder="<?= _e(SiteTranslations::get('site_email_contact')) ?> *" autocomplete="off" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_email')) ?>"
                                       data-rule-email="true" data-msg-email="<?= _e(SiteTranslations::get('site_email_not_valid')) ?>" id="email" name="email"
                                       type="email" value="<?= http_post('email', http_get('email')) ?>"/>
                      
                        </div>

                        <div class="form-group">
                            <label class="label" for="password"><?= _e(SiteTranslations::get('site_new_password')) ?> *</label>
                        
                                <input class="form-control" placeholder="<?= _e(SiteTranslations::get('site_new_password')) ?> *" required autocomplete="off" data-rule-required="true"
                                        data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_password')) ?>" minlength="8"
                                        data-msg-minlength="<?= _e(SiteTranslations::get('site_enter_safe_password_8_digits')) ?>"
                                        id="password" class="required" maxlength="15" title="<?= _e(SiteTranslations::get('site_enter_safe_password_8_digits')) ?>" name="password" type="password" value=""/>
                            
                        </div>
                        
                            
                        <div class="form-group">
                            <label class="label" for="confirmPassword"><?= _e(SiteTranslations::get('site_password_again')) ?> *</label>
                            
                                <input class="form-control" placeholder="<?= _e(SiteTranslations::get('site_password_again')) ?> *" autocomplete="off" required minlength="8" maxlength="15"
                                        data-msg-required="<?= _e(SiteTranslations::get('site_you_did_not_confirm_your_password')) ?>" data-rule-equalTo="#password"
                                        data-msg-equalTo="<?= _e(SiteTranslations::get('site_passwords_do_not_match')) ?>"
                                        id="confirmPassword" name="confirmPassword" type="password" value=""/>
                        
                        </div>
                            
                        
                        <button type="submit" class="btn btn-primary btn-default" name="updatePassword"><?= _e(SiteTranslations::get('site_change_password')) ?></button>
                    </form>
                </div>
        </div>
        <?php } ?>


        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
    

