function checkIfPossible(planningId = null) {

  var loggerId = $('#loggerId').val();
  var days = $('#days').val();
  if (days == '-') {
    days = $('#daystoo').val();
  }
  var startDate = $('#startDate').val();

  var postData = {};
  postData.ajaxCall = 1;
  postData.loggerId = loggerId;
  postData.days = days;
  postData.startDate = startDate;
  postData.planningId = planningId

  $.ajax('/dashboard/planning', {
      type: 'POST',  // http method
      data: postData,  // data to submit
      success: function(response){
        if (response=='') {
          disableSubmit('');
          hideWarning();
        } else {

          //console.log(items);

          var items = response.split('|');
          enableSubmit();
          if (items[2]==1) {
            // DANGER
            disableSubmit(items[1]);
          } else if (items[2]==2) {
            // WARNING
            showWarning(items[1]);
          } else {

          }
        }
      },
      error: function (jqXhr, textStatus, errorMessage) {
            alert('Error' + errorMessage);
      }
  });

}



function disableSubmit(warning) {
  $("#submitbutton").prop('disabled', true);
  if (warning) {
    $("#warning-content").html(warning);
    $("#warning").removeClass('callout-warning');
    $("#warning").addClass('callout-danger');
    $("#warning").show();
  }
}


function enableSubmit() {
  hideWarning();
  $("#warning").removeClass('callout-warning');
  $("#warning").removeClass('callout-danger');
  $("#submitbutton").prop('disabled', false);
}


function showWarning(warning) {
  if (warning) {
    $("#warning").removeClass('callout-danger');
    $("#warning").addClass('callout-warning');
    $("#warning-content").html(warning);
    $("#warning").show();
  }
}

function addWarning(warning) {
  if (warning) {
    warning = warning + $("#warning-content").html();
    $("#warning-content").html(warning);
    $("#warning").show();
  }
}


function hideWarning() {
  $("#warning").delay(1500).hide();
}


function countSelected() {
  var iCountSelected = 0;
  $("#loggerId option").each(function() {
    if ($(this).prop('selected')) {
      iCountSelected++;
    }
  });
  $('#xSelected').html(iCountSelected);
}

