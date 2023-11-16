<?php

/**
 * V-LINER
 */
?>

<table class="table table-bordered table-striped data-table">
  <thead>
    <tr>
      <td colspan="2"></td>
      <td colspan="6" class="text-center">Meting</td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <th style="width: 10px;">&nbsp;</th>
      <th><?= sysTranslations::get('global_date') ?></th>
      <th nowrap>L1/L2</th>
      <th nowrap>L1/L3</th>
      <th nowrap>L2/L3</th>
      <th nowrap>L1/PE</th>
      <th nowrap>L2/PE</th>
      <th nowrap>L3/PE</th>
      <th style="width:25px;"><?= sysTranslations::get('global_picture') ?></th>
      <th style="width:10px;"></th>

    </tr>
  </thead>
  <tbody>
    <?php
    if (isset($aSystemReports) && count($aSystemReports) > 0) {

      $iCount = 0;
      foreach ($aSystemReports as $oSystemReport) {
        $iCount++;
        // if (isEven($iCount)) {
        $rowClass = 'altEven';
        //} else {
        //  $rowClass = 'altOdd';
        //}
        $aSubSystemReports = $oSystemReport->getSubSystemReports();
        if (empty($aSubSystemReports)) {
          $iRowspan = 2;
        } else {
          $iRowspan = 2 + count($aSubSystemReports);
        }

        echo '<tr>';
    ?>
        <td rowspan="<?= $iRowspan ?>">
          <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/system-reports/bewerken/' . $oSystemReport->systemReportId ?>" title="Metingen">
            <i class="fas <?= $oSystemReport->isEditable() ? 'fa-pencil-alt' : 'fa-search-location' ?>"></i>
          </a>
        </td>
        <?php
        //if (!empty($oSystemReport->wideInfo)) {
        echo '<td style="border-bottom: 1px solid #000;"><strong>' . _e(date('d-m-Y', strtotime($oSystemReport->created ? $oSystemReport->created : $oSystemReport->modified))) . '</strong></td>';
        echo '<td style="border-bottom: 1px solid #000;" colspan="6" align="center">' . _e($oSystemReport->wideInfo) . '</td>';

        ?>
        <td style="border-bottom: 1px solid #000;"></td>
        <td nowrap rowspan="<?= $iRowspan ?>">
          <?php if ($oSystemReport->isEditable()) { ?>
            <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/system-reports/verwijderen/' . $oSystemReport->systemReportId . '?' . CSRFSynchronizerToken::query() ?>" title="Metingen verwijderen" onclick="return confirmChoice('<?= strtolower(sysTranslations::get('system_system_report')) . ' ' . $oSystemReport->systemReportId ?>');">
              <i class="fas fa-trash"></i>
            </a>
          <?php } ?>
        </td>
        <?php
        echo '</tr>';
        echo '<tr class="odd">';

        echo '<td>' . _e($oSystemReport->columnA) . '</td>';
        echo '<td>' . _e($oSystemReport->faseA) . '</td>';
        echo '<td>' . _e($oSystemReport->faseB) . '</td>';
        echo '<td>' . _e($oSystemReport->faseC) . '</td>';
        echo '<td>' . _e($oSystemReport->faseD) . '</td>';
        echo '<td>' . _e($oSystemReport->faseE) . '</td>';
        echo '<td>' . _e($oSystemReport->faseF) . '</td>';
        echo '<td align="center" nowrap>'; // ' . (!empty($aSubSystemReports) ? 'rowspan="' . ($iRowspan - 1) . '"' : '') . '
        $aImages = $oSystemReport->getImages();
        foreach ($aImages as $oImage) {

          if (empty($oImage->getImageFileByReference('cms_thumb')->title) || _e($oImage->getImageFileByReference('cms_thumb')->title) != _e($oSystemReport->columnA)) {
            continue;
          }
        ?>
          <a class="pr-1" href="<?= $oImage->getImageFileByReference('detail')->link ?>" data-toggle="lightbox" data-title="<?= _e($oSystem->name) ?> - <?= _e($oSystem->model) ?> - <?= _e($oImage->getImageFileByReference('cms_thumb')->title) ?>" data-gallery="gallery">
            <img src="<?= $oImage->getImageFileByReference('cms_thumb')->link ?>" alt="<?= $oImage->getImageFileByReference('cms_thumb')->title ?>" height="25" title="<?= $oImage->getImageFileByReference('cms_thumb')->title ?>" />
          </a>
        <?php }

        // notice / te uploaden
        if (!empty($oSystemReport->notice)) {
          echo '<span class="toUpload" title="TODO: Uploaden!">' . _e($oSystemReport->notice) . '</span>';
        }

        echo '</td>';
        ?>

        <?php

        echo '</tr>';

        foreach ($aSubSystemReports as $oSubSystemReport) {

          echo '<tr class="odd">';

          echo '<td>' . _e($oSubSystemReport->columnA) . '</td>';
          echo '<td>' . _e($oSubSystemReport->faseA) . '</td>';
          echo '<td>' . _e($oSubSystemReport->faseB) . '</td>';
          echo '<td>' . _e($oSubSystemReport->faseC) . '</td>';
          echo '<td>' . _e($oSubSystemReport->faseD) . '</td>';
          echo '<td>' . _e($oSubSystemReport->faseE) . '</td>';
          echo '<td>' . _e($oSubSystemReport->faseF) . '</td>';
          echo '<td align="center" nowrap>';
          $aImages = $oSystemReport->getImages();
          foreach ($aImages as $oImage) {

            if (empty($oImage->getImageFileByReference('cms_thumb')->title) || _e($oImage->getImageFileByReference('cms_thumb')->title) != _e($oSubSystemReport->columnA)) {
              continue;
            }
        ?>
            <a class="pr-1" href="<?= $oImage->getImageFileByReference('detail')->link ?>" data-toggle="lightbox" data-title="<?= _e($oSystem->name) ?> - <?= _e($oSystem->model) ?> - <?= _e($oImage->getImageFileByReference('cms_thumb')->title) ?>" data-gallery="gallery">
              <img src="<?= $oImage->getImageFileByReference('cms_thumb')->link ?>" alt="<?= $oImage->getImageFileByReference('cms_thumb')->title ?>" height="25" title="<?= $oImage->getImageFileByReference('cms_thumb')->title ?>" />
            </a>
      <?php }


          // notice / te uploaden
          if (!empty($oSubSystemReport->notice)) {
            echo '<span class="toUpload" title="TODO: Uploaden!">' . _e($oSystemReport->notice) . '</span>';
          }

          echo '</td>';
          echo '</tr>';
        }
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
      <th nowrap>L1/L2</th>
      <th nowrap>L1/L3</th>
      <th nowrap>L2/L3</th>
      <th nowrap>L1/PE</th>
      <th nowrap>L2/PE</th>
      <th nowrap>L3/PE</th>
      <th><?= sysTranslations::get('global_picture') ?></th>
      <th></th>

    </tr>
  </tfoot>
</table>

<?php

function isEven($iNumber)
{
  if ($iNumber % 2 == 0) {
    return true;
  } else {
    return false;
  }
}

?>