<?php

/**
 * MULTILINER
 */

?>
<table class="table table-bordered table-striped data-table">
  <thead>
    <tr>
      <td colspan="2"></td>
      <td colspan="3" class="text-center">uF meting</td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <th style="width: 10px;">&nbsp;</th>
      <th><?= sysTranslations::get('global_date') ?></th>
      <th nowrap>Fase 1</th>
      <th nowrap>Fase 2</th>
      <th nowrap>Fase 3</th>
      <th style="width:25px;"><?= sysTranslations::get('global_picture') ?></th>
      <th style="width:10px;"></th>

    </tr>
  </thead>
  <tbody>
    <?php
    if (isset($aSystemReports) && count($aSystemReports) > 0) {

      foreach ($aSystemReports as $oSystemReport) {
        echo '<tr>';
    ?>
        <td>
          <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/system-reports/bewerken/' . $oSystemReport->systemReportId ?>" title="Metingen aanpassen">
            <i class="fas <?= $oSystemReport->isEditable() ? 'fa-pencil-alt' : 'fa-search-location' ?>"></i>
          </a>
        </td>
        <?php
        echo '<td>' . _e(date('d-m-Y', strtotime($oSystemReport->created ? $oSystemReport->created : $oSystemReport->modified))) . '</td>';
        echo '<td>' . _e($oSystemReport->faseA) . '</td>';
        echo '<td>' . _e($oSystemReport->faseB) . '</td>';
        echo '<td>' . _e($oSystemReport->faseC) . '</td>';
        echo '<td align="center" nowrap>';
        $aImages = $oSystemReport->getImages();
        foreach ($aImages as $oImage) {
        ?>
          <a class="pr-1" href="<?= $oImage->getImageFileByReference('detail')->link ?>" data-toggle="lightbox" data-title="<?= _e($oSystem->name) ?> - <?= _e($oSystem->model) ?> - <?= _e($oSystem->machineNr) ?>" data-gallery="gallery">
            <img src="<?= $oImage->getImageFileByReference('cms_thumb')->link ?>" alt="<?= $oImage->getImageFileByReference('cms_thumb')->title ?>" height="25" title="<?= $oImage->getImageFileByReference('cms_thumb')->title ?>" />
          </a>
        <?php }

        // notice / te uploaden
        if (!empty($oSystemReport->notice)) {
          echo '<span class="toUpload" title="TODO: Uploaden!">' . _e($oSystemReport->notice) . '</span>';
        }

        echo '</td>';
        ?>
        <td nowrap>
          <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/system-reports/verwijderen/' . $oSystemReport->systemReportId . '?' . CSRFSynchronizerToken::query() ?>" title="Metingen verwijderen" onclick="return confirmChoice('<?= strtolower(sysTranslations::get('system_system_report')) . ' ' . $oSystemReport->systemReportId ?>');">
            <i class="fas fa-trash"></i>
          </a>

        </td>
      <?php


        echo '</tr>';
      }
    } else {
      ?>
      <tr>
        <td colspan="8">Geen records gevonden</td>
      </tr>
    <?php
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <th style="width: 10px;">&nbsp;</th>
      <th><?= sysTranslations::get('global_date') ?></th>
      <th nowrap>Fase 1</th>
      <th nowrap>Fase 2</th>
      <th nowrap>Fase 3</th>
      <th><?= sysTranslations::get('global_picture') ?></th>
      <th></th>

    </tr>
  </tfoot>
</table>