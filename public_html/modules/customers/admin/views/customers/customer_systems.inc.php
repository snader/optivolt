<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">

        <span class="float-sm-right">
          <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oCustomer->customerId ?>">
            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?> <?= _e($oCustomer->companyName) ?>">
              <?= sysTranslations::get('to_customer') ?>
            </button>
          </a>
        </span>

        <h1 class="m-0"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;<?= _e($oCustomer->companyName) ?></h1>
      </div>
    </div>
  </div>
</div>


<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
  <input type="hidden" value="save" name="action" />
  <input type="hidden" value="<?= $oCustomer->customerId ?>" name="customerId" />

  <div class="container-fluid">

    <div class="row">


      <div class="col-md-12">

        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-shapes pr-1"></i> Systemen</h3>
            <div class="card-tools">

              <div class="input-group input-group-sm" style="width: auto;">

                <span class="float-sm-right">
                  <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oCustomer->customerId ?>">
                    <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?>">
                      <?= sysTranslations::get('to_customer') ?>
                    </button>
                  </a>
                </span>

              </div>
            </div>

          </div>
          <div class="card-body">
            <?php
            $bShowCustomer = false;
            $bShowLocation = true;
            require getAdminSnippet('systems_list_alt', 'systems');
            ?>
          </div>
          <div class="card-footer">
            <span class="float-sm-right">
              <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oCustomer->customerId ?>">
                <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?>">
                  <?= sysTranslations::get('to_customer') ?>
                </button>
              </a>
            </span>

          </div>


        </div><!-- /.container-fluid -->

      </div>


    </div>
  </div>
</form>