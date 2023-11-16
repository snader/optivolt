<table class="table table-bordered table-striped data-table">
  <thead>
    <tr>
      <th style="width: 10px;">&nbsp;</th>
      <th style="width: 50px;">Plandatum</th>
      <?php if ($bShowCustomer) { ?><th><?= sysTranslations::get('systems_customer') ?></th><?php } ?>
      <?php if ($bShowLocation) { ?><th><?= sysTranslations::get('systems_location') ?></th><?php } ?>
      <th><?= sysTranslations::get('systems_type') ?></th>
      <th><?= sysTranslations::get('systems_number') ?></th>
      <th><?= sysTranslations::get('last_report_data') ?></th>


    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($aSystems as $oSystem) {
      echo '<tr>';
    ?>
      <td>
        <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/systems/bewerken/' . $oSystem->systemId ?>?planning=1" title="<?= sysTranslations::get('system_edit') ?>">
          <i class="fas fa-pencil-alt"></i>
        </a>
      </td>
      <td nowrap class="<?= (days_from_now($oSystem->visitDate) < 0 && !$oSystem->finished) ? 'text-danger' : '' ?>">
        <?= _e(date('d-m-Y', strtotime($oSystem->visitDate))) ?>
      </td>
      <?php

      if ($bShowCustomer) {
        echo '<td>' . _e($oSystem->companyName) . '</td>';
      }
      if ($bShowLocation) {
        echo '<td>' . _e($oSystem->locationName) . '</td>';
      }
      echo '<td>' .  _e($oSystem->typeName) . '</td>';
      echo '<td>' . _e($oSystem->machineNr) . '</td>';
      echo '<td>' . ($oSystem->lastReportDate ? _e(date('Y', strtotime($oSystem->lastReportDate))) : '') . '</td>';

      ?>


    <?php


      echo '</tr>';
    }
    if (empty($aSystems)) {
      echo '<tr><td colspan="8"><i>' . sysTranslations::get('system_no_systems') . '</i></td></tr>';
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <th style="width: 10px;">&nbsp;</th>
      <th>Plandatum</th>
      <?php if ($bShowCustomer) { ?><th><?= sysTranslations::get('systems_customer') ?></th><?php } ?>
      <?php if ($bShowLocation) { ?><th><?= sysTranslations::get('systems_location') ?></th><?php } ?>
      <th><?= sysTranslations::get('systems_type') ?></th>
      <th><?= sysTranslations::get('systems_number') ?></th>
      <th><?= sysTranslations::get('last_report_data') ?></th>

    </tr>
  </tfoot>
</table>