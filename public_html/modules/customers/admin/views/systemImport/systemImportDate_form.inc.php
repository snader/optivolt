<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">

        <h1 class="m-0"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;Systemen installatiedatum import</h1>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-user pr-1"></i> Import</h3>
        </div>
        <!-- /.card-header -->

        <div class="card-body">


          <div class="errorBox" <?= isset($aErrors) && !empty($aErrors) ? 'style="display:block;"' : '' ?>>
            <div class="title">De volgende fouten zijn gevonden</div>
            <ul>
              <?php
              if (!empty($aErrors)) {
                foreach ($aErrors as $sField => $sError) {
                  echo '<li><label for="' . $sField . '" style="display: block;">' . $sError . '</label></li>';
                }
              }
              ?>
            </ul>
          </div>
          <?php if (!empty($aLogs)) { ?>
            <div class="import-messages">
              <h2>Resultaat <?= $iSaved . '/' . $iTotal ?> opgeslagen</h2>
              <ul style="margin-left: 15px;">
                <li>Regels: <?= $iTotal ?></li>
                <li>Errors: <?= $iErrors ?></li>
                <li>Waarschuwingen: <?= $iWarnings ?></li>
                <li>Totaal opgeslagen: <?= $iSaved ?></li>
              </ul>
              <?php

              # genereate errors summery
              $sErrorContent = '';
              foreach ($aLogs as $iRow => $aErrors) {
                if (isset($aErrors['errors'])) {
                  $sErrorContent .= '<div class="row ' . ($iRow == 2 ? 'first' : '') . '">' . "\n";
                  foreach ($aErrors as $sType => $aMsgs) {
                    $sErrorContent .= '<div class="type-' . $sType . '">' . "\n";
                    foreach ($aMsgs as $sMsg) {
                      $sErrorContent .= '<div class="msg">' . $sMsg . '</div>' . "\n";
                    }
                    $sErrorContent .= '</div>' . "\n";
                  }
                  $sErrorContent .= '</div>' . "\n";
                }
              }

              # write error summery overview
              if (!empty($sErrorContent)) {
                echo '<br>';
                echo '<h2 class="errorColor">Errors</h2>';
                echo $sErrorContent;
                echo '<br>';
              }

              # total overview
              echo '<h2>Totaal overzicht import</h2>';
              foreach ($aLogs as $iRow => $aErrors) {
                echo '<div class="row ' . ($iRow == 2 ? 'first' : '') . '">';
                foreach ($aErrors as $sType => $aMsgs) {
                  echo '<div class="type-' . $sType . '">';
                  foreach ($aMsgs as $sMsg) {
                    echo '<div class="msg">' . $sMsg . '</div>';
                  }
                  echo '</div>';
                }
                echo '</div>';
              }
              ?>
            </div>
          <?php } ?>
          <form method="POST" enctype="multipart/form-data">
            <?= CSRFSynchronizerToken::field() ?>
            <input name="action" type="hidden" value="import" />
            <fieldset>
              <legend>Systeemdatum import</legend>

              <table class="withForm">
                <tr>
                  <td class="withLabel" style="width: 180px;"><label for="file">Bestand (.xlsx, .xls)</label></td>
                  <td>
                    <input name="file" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                  </td>
                </tr>

                <input type="hidden" name="customerGroupIds[]" value="1">


                <tr>
                  <td>&nbsp;</td>
                  <td colspan="2">
                    <input type="submit" class="btn btn-primary" value="Importeer" name="save" />
                  </td>
                </tr>
              </table>
            </fieldset>
          </form>

          <hr />
          <h3>Ondersteunde kolommen</h3>|
          <?php

          foreach ($aImportFields as $aField) {
            $bRequiredColumn = in_array($aField['column'], $aRequiredColumns);

            if ($bRequiredColumn) {
              echo '<b><u>' . $aField['column'] . '</u></b> | ';
            } else {
              echo $aField['column'] . ' | ';
            }
          }
          ?>
          <br>

        </div>
      </div>
    </div>
  </div>