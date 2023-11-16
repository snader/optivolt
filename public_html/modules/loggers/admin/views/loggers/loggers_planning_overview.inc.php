<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
        <span class="float-right">
          <a class="backBtn right pl-1" href="<?= ADMIN_FOLDER ?>">
            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('dashboard_menu') ?>">
              Naar onderhoudsplanning
            </button>
          </a>
        </span>
        <h1 class="m-0" style="display:inline-block;"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;Planning</h1>


        &nbsp;
        <!-- Date range -->
        <div class="form-group my-0" style="display:inline-block;">
          <form id="filterLoggers" method="post" action="">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="far fa-calendar-alt"></i>
                </span>
              </div>
              <input type="text" class="form-control float-right" id="planning" value="<?= _e($aLoggersFilter['dates']) ?>" name="loggersFilter[dates]">
              &nbsp;<button type="submit" value="Filter" style="border-width: 1px;" name="filterLoggers">&nbsp;&raquo;&nbsp;</button>

              <span>
                <a class="right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/exportxls">
                  <button type="button" class="btn btn-outline-success btn-block btn-flat ml-5" style="width:100px;"><i class="fa fa-file-excel"></i> export</button>
                </a>
              </span>
            </div>

            <form>
              <!-- /.input group -->

        </div>
        <!-- /.form group -->
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">


  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">

          <h3 class="card-title d-none d-sm-block">Loggers</h3>

          <div class="card-tools">

            <div class="input-group input-group-sm" style="width: auto;">

              <div class="input-group-append">
                <form action="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>" method="POST" class="form-inline pr-2">
                  <input type="hidden" name="filterForm" value="1" />

                  <select class="select2 form-control form-control-sm" name="loggersFilter[customerId]">
                    <option>Filter klant</option>
                    <?php
                    $bShowAddButton = false;
                    foreach ($aAllCustomers as $iCustomerId => $sCompanyName) {

                      echo '<option ' . ((isset($aLoggersFilter['customerId']) && ($aLoggersFilter['customerId'] == $iCustomerId)) ? 'selected' : '') . ' value="' . $iCustomerId . '">' . $sCompanyName . '</option>';
                    }
                    ?>
                  </select>&nbsp;
                  <input type="submit" name="filterLoggers" value="Filter" class="btn btn-default btn-sm" /> <input class="btn btn-default btn-sm" type="submit" name="resetFilter" value="Reset" />
                </form>
              </div>

            </div>

          </div>
        </div>

        <div class="zui-wrapper ">

          <div class="zui-scroller card-body table-responsive p-0 dragscroll styled-scrollbars tscroll">

            <table class="table  text-nowrap lesspadding zui-table" id="loggers-planning">
              <thead class="stickyhead">
                <tr>
                  <th> </th>
                  <?php
                  $sPreviousMonth = '';
                  foreach ($period as $key => $value) {

                    //$value->format('Y-m-d')
                    if ($value->format('M') != $sPreviousMonth) {

                      $iDaysRemaining = (int)date('t', strtotime($value->format('Y-m-d'))) - (int)date('j', strtotime($value->format('Y-m-d'))) + 1;
                      echo '<th colspan="' . $iDaysRemaining . '">' . ddate($value, 'M') . '</th>';
                    }
                    $sPreviousMonth = $value->format('M');
                  }
                  ?>
                </tr>
                <tr>
                  <th> </th>

                  <?php
                  foreach ($period as $key => $value) {

                    $sClass = 'class="thfw"';
                    foreach ($aLoggersDays as $oLoggersDay) {
                      if (($value->format('w') == $oLoggersDay->dayNumber)) {
                        $sClass = 'class="orange thfw hovertext" data-hover="' . $oLoggersDay->name . '"';
                      }
                    }

                    echo '<th ' . $sClass . '>' . ddate($value, 'D') . '</th>';
                  }
                  ?>
                </tr>
                <tr>
                  <th></th>
                  <?php
                  foreach ($period as $key => $value) {
                    $sClass = 'class="thfw "';
                    foreach ($aLoggersDays as $oLoggersDay) {
                      if (($value->format('Y-m-d') . ' 00:00:00' == $oLoggersDay->date)) {
                        $sClass = 'class="thfw orange hovertext" data-hover="' . $oLoggersDay->name . '"';
                      }
                    }
                    if ($value->format('Ymd') == date('Ymd', time())) {
                      $sClass = str_replace('thfw ', 'thfw today ', $sClass);
                    }
                    echo '<th ' . $sClass . '>' . ddate($value, 'd') . '</th>';
                  }
                  ?>
                </tr>
              </thead>
              <tbody>
                <?php
                $iPreviousLoggerId = 0;

                foreach ($aLoggers as $oLogger) {

                  if ($oLogger->mainLoggerId == $iPreviousLoggerId) {
                    // same line, other planitem

                  } else {
                    // new logger line
                ?>
                    <tr>
                      <td class="sticky-col1 edit-logger" data-loggername="<?= $oLogger->name ?>" data-loggerid="<?= $oLogger->mainLoggerId ?>"><?= $oLogger->name ?></td>

                      <td class="sticky-col2"><?php echo customerSelect($aKlantNamen[$oLogger->mainLoggerId], $aPlanningIds[$oLogger->mainLoggerId], $aColors[$oLogger->mainLoggerId]) ?>&nbsp;</td>
                      <?php
                      foreach ($period as $key => $value) {

                        //$value->format('Y-m-d')
                        if (strtotime($value->format('Y-m-d')) > strtotime(date('Y-m-d'))) {
                          $sColorClass = 'emp';
                        } else {
                          $sColorClass = 'pas';
                        }
                        if (strtotime($aAvailableFrom[$oLogger->mainLoggerId]) > strtotime($value->format('Y-m-d'))) {
                          $sColorClass = 'pas unavailable';
                        }
                        $sTitle = '';
                        $sHash = '';
                        $iDays = 0;
                        $sDays = '';
                        $iCount = 0;
                        $sCurStartDate = '';
                        $iPlanningId = null;
                        $iParentPlanningId = null;
                        foreach ($aStartDate[$oLogger->mainLoggerId] as $iCustomerid => $sStartDate) {
                          $iCount++;
                          $sEndDate = $aEndDate[$oLogger->mainLoggerId][$iCustomerid];
                          if (strtotime($value->format('Y-m-d')) >= strtotime($sStartDate) && strtotime($value->format('Y-m-d')) <= strtotime($sEndDate)) {
                            $sColorClass = "hovertext soc-" . ($aColors[$oLogger->mainLoggerId][$iCustomerid] ? $aColors[$oLogger->mainLoggerId][$iCustomerid] : $iCount);
                            $sTitle = $aKlantNamen[$oLogger->mainLoggerId][$iCustomerid];
                            $iDays = $aDays[$oLogger->mainLoggerId][$iCustomerid];
                            $sCurStartDate = date('d-m-Y', strtotime($sStartDate));
                            if ($iDays > 1) {
                              $sDays = $iDays . ' dagen';
                            } else {
                              $sDays = $iDays . ' dag';
                            }
                            if (!empty($aComments[$oLogger->mainLoggerId][$iCustomerid])) {
                              $sTitle .= ' - ' . $aComments[$oLogger->mainLoggerId][$iCustomerid];
                            }

                            $iPlanningId = $aPlanningIds[$oLogger->mainLoggerId][$iCustomerid];
                            $iParentPlanningId = $aParentPlanningIds[$oLogger->mainLoggerId][$iCustomerid];
                            $sHash = $iPlanningId; //$iCount . '_' . $iCustomerid;
                          }
                        }
                        echo '<td data-pid="' . $iPlanningId . '" data-parentid="' . $iParentPlanningId . '" data-cd="' . $value->format('d-m-Y') . '" data-pp="' . $oLogger->mainLoggerId . '_' . strtotime($value->format('Y-m-d')) . '" ' . ($sTitle ? ' data-hover=" ' . $sCurStartDate . ' - ' . $sTitle . ', ' . $sDays . '"' : '') . ' class="ptd p' . $iPlanningId . ' pr' . $iParentPlanningId . ' ' . ($sTitle ? 'pedit' : '') . ' ' . $sColorClass . '"' . ($sHash ? '  data-cid="' . $sHash . '"' : '') . '>' . ($sHash ? '<a name="' . $sHash . '"></a>' : '') . '&nbsp;</td>';
                      } ?>
                    </tr>
                <?php
                  }
                  $iPreviousLoggerId = $oLogger->mainLoggerId;
                }

                ?>


              </tbody>
              <tfoot class="stickyfoot">
                <tr>
                  <th></th>
                  <?php
                  foreach ($period as $key => $value) {
                    $sClass = 'class="thfw "';
                    foreach ($aLoggersDays as $oLoggersDay) {
                      if (($value->format('Y-m-d') . ' 00:00:00' == $oLoggersDay->date)) {
                        $sClass = 'class="thfw orange hovertext" data-hover="' . $oLoggersDay->name . '"';
                      }
                    }
                    if ($value->format('Ymd') == date('Ymd', time())) {
                      $sClass = str_replace('thfw ', 'thfw today ', $sClass);
                    }
                    echo '<th ' . $sClass . '>' . ddate($value, 'd') . '</th>';
                  }
                  ?>
                </tr>
                <tr>
                  <th> </th>

                  <?php
                  foreach ($period as $key => $value) {

                    $sClass = 'class="thfw"';
                    foreach ($aLoggersDays as $oLoggersDay) {
                      if (($value->format('w') == $oLoggersDay->dayNumber)) {
                        $sClass = 'class="orange thfw hovertext" data-hover="' . $oLoggersDay->name . '"';
                      }
                    }

                    echo '<th ' . $sClass . '>' . ddate($value, 'D') . '</th>';
                  }
                  ?>
                </tr>

                <tr>
                  <th> </th>
                  <?php
                  $sPreviousMonth = '';
                  $iColNrToAddBorder = array();
                  $iColCount = 1;
                  foreach ($period as $key => $value) {
                    $iColCount++;
                    //$value->format('Y-m-d')
                    if ($value->format('M') != $sPreviousMonth) {
                      $iColNrToAddBorder[] = $iColCount;
                      $iDaysRemaining = (int)date('t', strtotime($value->format('Y-m-d'))) - (int)date('j', strtotime($value->format('Y-m-d'))) + 1;
                      echo '<th colspan="' . $iDaysRemaining . '">' . ddate($value, 'M') . '</th>';
                    }
                    $sPreviousMonth = $value->format('M');
                  }
                  ?>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

      </div>

    </div>
  </div>


</div>

<?php


$sController = http_get('controller');
$sCurrentUrl = getCurrentUrl();
$sBottomJavascript = <<<EOT
<script type="text/javascript">

$(document).ready(function ($) {
        $('#loggers-planning').tableCellsSelection();
    });


$('#planning').daterangepicker({
    locale: {
      format: 'DD-MM-YYYY'
    }
  }
);

const scrollContainer = document.querySelector("div.dragscroll");



$('.customerSelect').on('change', function() {

  var object = $(this);
  if (object.find(":selected").val()) {
    event.preventDefault();
    horizontalScroll(object.find(":selected").val());

    var color = object.find('option:selected').css( "background-color" );
    object.css( "background-color", color );

    setTimeout(function() { object.css( "background-color", "#ffffff" ); }, 2000);
    setTimeout(function() { object.val($("#target option:first").val()); }, 2000);


  }
});

function horizontalScroll(hash) {

  $('.tscroll').addClass('smoothscroll');

  var hashobject = $("[name='" + hash + "']");

  xOffset = ( $(scrollContainer).offset().left - $(hashobject).offset().left ) + 50;
  scrollContainer.scrollLeft -= xOffset;

  $('.tscroll').removeClass('smoothscroll');

  hashobject.parent().addClass('divtoBlink');

  setTimeout(function(){ hashobject.parent().removeClass('divtoBlink') }, 2000);

  if ((hashobject.parent().data('parentid'))) {
    parentid =  '.pr' + hashobject.parent().data('parentid');
    currentid =  '.p' + hashobject.parent().data('parentid');

    $(parentid).addClass('divtoBlink');
    $(currentid).addClass('divtoBlink');


    setTimeout(function(){ $(parentid).removeClass('divtoBlink') }, 2000);
    setTimeout(function(){ $(currentid).removeClass('divtoBlink') }, 2000);
  }


}


if(window.location.hash) {
  var hash = window.location.hash;

  hash = hash.substring(1, hash.length);
  horizontalScroll(hash);

  window.setTimeout(offsetAnchor, 500);

}

function offsetAnchor() {
  if (location.hash.length !== 0) {
    window.scrollTo(window.scrollX, window.scrollY - 100);
  }
}


$(".hovertext").hover(
  // mouse-enter event
  function () {
      var hash = $(this).data("cid");
      $("[data-cid='" + hash + "']").addClass('hovertd');
  },
  // mouse-leave event
  function () {
      var hash = $(this).data("cid");
      $("[data-cid='" + hash + "']").removeClass('hovertd');
  }
);

// edit existing planning Item
$(".pedit").click(function() {
     window.location = '/dashboard/$sController/planning-bewerken/' + $(this).data('pid');
});

$(".pedit").hover(
  // mouse-enter event add EDIT button
  function () {
    var pid = $(this).data('pid');
    var hash = $(this).data("cid");
    if (pid) {
      $('.pedit[data-pid="' + pid + '"]').first().html( "<a name='" + hash + "'><i class='fas fa-pencil-alt'></i></a>" );
    }
  },
  // mouse-leave event remove EDIT button
  function () {
    var pid = $(this).data('pid');
    var hash = $(this).data("cid");
    if (pid) {
      $('.pedit[data-pid="' + pid + '"]').first().html( "<a name='" + hash + "'></a>" );
    }
  }
);

$(".edit-logger").hover(
  // mouse-enter event add EDIT button
  function () {
    var loggerid = $(this).data('loggerid');
    $(this).html( "<a href='/dashboard/loggers/bewerken/" + loggerid + "'><i class='fas fa-pencil-alt'></i></a>" );

  },
  // mouse-leave event remove EDIT button
  function () {
    var loggername = $(this).data('loggername');

      $(this).html( loggername );

  }
);


// emtpy tds
$(".ptd").hover(

// mouse-enter event add PLUS button
function () {

    $(this).parent().addClass('hoverthing');

    if ($(this).hasClass('emp')) {

      var datum = $(this).data('cd');

      $(this).html('<i title="Inplannen op ' + datum + '" class="fas fa-plus-circle add-planning"></i>');
      timeoutHandle = setTimeout(function () {
          $('.add-planning').fadeIn( "fast", function() {
      });
      }, 500);

    }
},

// mouse-leave event remove PLUS button
function () {

   $(this).parent().removeClass('hoverthing');

    if (typeof timeoutHandle !== 'undefined') {
      window.clearTimeout(timeoutHandle);
    }
    if ($(this).hasClass('emp')) {
      $(this).html('');
    }
});

/*
var leftButtonDown = false;
$('body').on('mousedown', '.ptd', function(e){
    if(e.which === 1) {
      leftButtonDown = true;

    }

});
$(document).mouseup(function(e){
        // Left mouse button was released, clear flag
        if(e.which === 1) {
          leftButtonDown = false;

        }
});

    $( "#loggers-planning" ).mousemove(function() {


      if (leftButtonDown) {
          // Call the tweak function to check for LMB and set correct e.which
          //tweakMouseMoveEvent(e);

          var selectedCells = $('#loggers-planning').tableCellsSelection('selectedCells');
          amountOfCells = selectedCells.length;

          jQuery.each( selectedCells, function( i, val ) {

                iCount++;
                dom_nodes = $($.parseHTML(val.outerHTML));

                if (iCount==1) {
                  myValues = dom_nodes.attr('data-pp').split("_");
                  min_logger = myValues[0];

                }
                if (iCount==amountOfCells) {
                  myValues = dom_nodes.attr('data-pp').split("_");
                  max_logger = myValues[0];
                }

                var loggerscount = max_logger-min_logger;
                console.log(min_logger + "-" + max_logger);


            });


      };
    });

*/


$( "#loggers-planning" ).mouseup(function() {
  var selectedCells = $('#loggers-planning').tableCellsSelection('selectedCells');

  amountOfCells = selectedCells.length;
  iCount = 0;
  min_logger = 0;
  max_logger = 0;
  min_date = 0;
  max_date = 0;

  //console.log(selectedCells[0].outerHTML);
  dom_nodes = $($.parseHTML(selectedCells[0].outerHTML));


  jQuery.each( selectedCells, function( i, val ) {

      iCount++;
      dom_nodes = $($.parseHTML(val.outerHTML));


      if (iCount==1) {
        myValues = dom_nodes.attr('data-pp').split("_");

        min_logger = myValues[0];
        min_date = myValues[1];
//        console.log(min_logger + '_' + min_date);
      }
      if (iCount==amountOfCells) {
        myValues = dom_nodes.attr('data-pp').split("_");
        //console.log(myValues);
        max_logger = myValues[0];
        max_date = myValues[1];
//        console.log(max_logger + '_' + max_date);
      }


      if (min_date == max_date && min_logger == max_logger) {
        // do nothing
      } else {
          window.location.href = "{$sCurrentUrl}/inplannen/" + min_logger + "_" + min_date + "/" + max_logger + "_" + max_date;
      }


  });


});

function getStringBetween(str, start, end) {
    const result = str.match(new RegExp(start + "(.*)" + end));

    return result[1];
}

$( ".ptd" ).on( "click", function() {

  if ($(this).hasClass('emp')) {
    window.location.href = "{$sCurrentUrl}/inplannen/" + $(this).data("pp");
  }
});


</script>

EOT;
$oPageLayout->addJavascript($sBottomJavascript);

?>

<style>
  .card-body.p-0 .table tbody>tr>td:first-of-type,
  .card-body.p-0 .table tbody>tr>th:first-of-type,
  .card-body.p-0 .table tfoot>tr>td:first-of-type,
  .card-body.p-0 .table tfoot>tr>th:first-of-type,
  .card-body.p-0 .table thead>tr>td:first-of-type,
  .card-body.p-0 .table thead>tr>th:first-of-type {
    padding-left: 0rem;
  }


  <?php
  // for the vertical month lines
  array_shift($iColNrToAddBorder);
  foreach ($iColNrToAddBorder as $iColNr) {
  ?>.table td:nth-of-type(<?= $iColNr ?>) {
    border-right: 1px solid #bbb !important;

  }

  <?php
  }
  ?>
</style>