<table class="table table-bordered table-striped data-table">
  <thead>
    <tr>
      <th style="width: 10px;">&nbsp;</th>
      <th style="text-align:center;width:20px;">Pos.</th>
      <?php if ($bShowCustomer) { ?><th><?= sysTranslations::get('systems_customer') ?></th><?php } ?>
      <?php if ($bShowLocation) { ?><th><?= sysTranslations::get('systems_location') ?></th>
        <th>Plaatsbepaling</th><?php } ?>

      <th><?= sysTranslations::get('systems_type') ?></th>
      <th>Machine#</th>
      <th><?= sysTranslations::get('systems_model') ?></th>

      <th>Meting</th>
      <th style="width: 10px;"></th>

    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($aSystems as $oSystem) {

      echo '<tr>';
    ?>
      <td>
        <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/systems/bewerken/' . $oSystem->systemId ?>" title="<?= sysTranslations::get('system_edit') ?>">
          <i class="fas fa-pencil-alt"></i>
        </a>
      </td>

      <?php
      echo '<td style="text-align:center;" nowrap>' . _e($oSystem->pos) . (!$oSystem->online ? ' (vervallen)' : '') . '</td>';
      if ($bShowCustomer) {
        echo '<td>' . _e($oSystem->companyName) . '</td>';
      }
      if ($bShowLocation) {
        echo '<td>' . _e($oSystem->locationName) . '</td>';
        echo '<td>' . _e($oSystem->floor) . '</td>';
      }

      echo '<td>' .  _e($oSystem->typeName) . '</td>';
      echo '<td>' . _e($oSystem->machineNr) . '</td>';
      echo '<td>' . _e($oSystem->model) . '</td>';

      
      $sAddButton = '<a class="addBtn mr-2" href="' . ADMIN_FOLDER . '/system-reports/toevoegen?systemId=' . $oSystem->systemId . '" title="' . sysTranslations::get('add_item') . '">
                                            <button type="button" class="btn btn-default btn-sm ">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </a>';

      //$sAddButton = ''; // DEZE WERKT NIET, WEET EVEN NIET WAAROM

      echo '<td>'
        . (!$oSystem->lastReportDate || date('Y', strtotime($oSystem->lastReportDate)) != date('Y') ? $sAddButton : '')
        . ($oSystem->lastReportDate ? _e(date('Y', strtotime($oSystem->lastReportDate))) : '') .
        '</td>';

      ?>

      <td nowrap align="center">
        <?php
        if ($oSystem->isDeletable()) { ?>
          <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/systems/verwijderen/' . $oSystem->systemId . '?' . (isset($oCustomer) ? 'fromSystemsCustomer=' . $oCustomer->customerId . '&' : '') . CSRFSynchronizerToken::query() ?>" title="<?= sysTranslations::get('system_delete') ?>" onclick="return confirmChoice('<?= strtolower(sysTranslations::get('system_system')) . ' ' . $oSystem->name ?>');">
            <i class="fas fa-trash"></i>
          </a>
        <?php } else { ?>
          <!--<span class="btn btn-danger btn-xs disabled"><i class="fas fa-trash"></i></span>-->
        <?php } ?>
      </td>
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
      <th style="text-align:center;">Pos.</th>
      <?php if ($bShowCustomer) { ?><th><?= sysTranslations::get('systems_customer') ?></th><?php } ?>
      <?php if ($bShowLocation) { ?><th><?= sysTranslations::get('systems_location') ?></th>
        <th>Plaatsbepaling</th><?php } ?>

      <th><?= sysTranslations::get('systems_type') ?></th>
      <th><?= sysTranslations::get('systems_model') ?></th>
      <th>Laatste voltooide meting</th>
      <th></th>
    </tr>
  </tfoot>
</table>