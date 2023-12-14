<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-calendar pr-1"></i> <?= sysTranslations::get('customer_appointments') ?></h3>
    <div class="card-tools">

      <?php
      if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
      ?>
        <div class="input-group input-group-sm" style="width: auto;">
          <div class="input-group-append">
            <a class="addBtn" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oCustomer->customerId ?>/afspraak-toevoegen#addAppointment" title="<?= sysTranslations::get('add_item') ?>">
              <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                <i class="fas fa-plus-circle"></i>
              </button>
            </a>&nbsp;
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <div class="card-body table-responsive p-0">
    <table class="table table-hover text-nowrap">
      <thead>
        <tr>
          <th style="width: 10px;">&nbsp;</th>
          <th><?= sysTranslations::get('global_date') ?></th>
          <th>Order#</th>
          <th>Onderhoudsmonteur</th>          
          <th style="text-align:center;width:30px;" nowrap>Afgerond</th>
          <th style="text-align:center;width:30px;" nowrap>Klant <i class="far fa-eye"></i></th>
          <th style="width: 10px;"></th>
        </tr>
      </thead>
      <tbody>

        <?php
        foreach ($aAppointments as $oAppointment) {
        ?>
          <tr>
            <td>
              <?php
              if (!$oAppointment->finished) {
              ?>
                <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oCustomer->customerId ?>/afspraak-bewerken/<?= $oAppointment->appointmentId ?>" title="<?= sysTranslations::get('appointment_edit') ?>">
                  <i class="fas fa-pencil-alt"></i>
                </a>
              <?php } ?>
              <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oCustomer->customerId ?>/afspraak-bekijken/<?= $oAppointment->appointmentId ?>" title="Afspraak bekijken/afronden">
                <i class="fas fa-search"></i>
              </a>
            </td>
            <td><?= date('d-m-Y', strtotime($oAppointment->visitDate)) ?></td>
            <td><?= firstXCharacters(_e($oAppointment->orderNr),20) ?></td>
            <td><?= _e($oAppointment->name) ?></td>            
            <td style="text-align:center;"><?= $oAppointment->finished ? '<i class="far fa-check-circle text-success"></i>' : '' ?></td>
            <td style="text-align:center;"><?= $oAppointment->customer ? '<i class="far fa-check-circle text-success"></i>' : '' ?></td>
            <td>
              <?php
              if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
              ?>
                <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/klanten/bewerken/' . $oCustomer->customerId . '/afspraak-verwijderen/' . $oAppointment->appointmentId ?>" title="Verwijder deze afspraak" onclick="return confirmChoice('<?= _e($oCustomer->companyName . ' - ' . date('d-m-Y', strtotime($oAppointment->visitDate))) ?>');">
                  <i class="fas fa-trash"></i>
                </a>
              <?php } ?>
            </td>



          </tr>
        <?php
        }
        if (empty($aAppointments)) {
          echo '<tr><td colspan="6"><i>' . sysTranslations::get('customer_no_appointments') . '</i></td></tr>';
        }
        ?>

      </tbody>
    </table>
  </div>
</div>