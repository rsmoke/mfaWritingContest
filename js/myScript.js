$( document ).ready(function(){

  var host = window.location.hostname;
  if (host === "csgrsmoke.lsait.lsa.umich.edu"){
    $('body').prepend("<div class='bg-danger text-white text-center'>THIS IS A DEVELOPMENT ENVIRONMENT -- THIS IS A DEVELOPMENT ENVIRONMENT -- THIS IS A DEVELOPMENT ENVIRONMENT -- THIS IS A DEVELOPMENT ENVIRONMENT </div>");
  }

  $('[data-info-toggle="tooltip"]').tooltip({
    container:'body',
    placement: 'left'
  });

  $('.flashNotify').fadeOut(6000);

	$.get( "individualSubmission.php", function( data ) {
	  $( "#currentEntry" ).html( data );
	});

  $.get( "non_active_submissions.php", function( data ) {
    $( "#non_active_Entry" ).html( data );
  });

	$.get( "contestList.php", function( data ) {
	  $( "#availableEntry" ).html( data );
	});

  $("#currentEntry").on('click','.applicantdeletebtn', function(e){
    $.post( 'applicantRemEntry.php',{sbmid:$(this).data('entryid')} );
    $.get( "individualSubmission.php", function( data ) {
      $( "#currentEntry" ).html( data );
    });
  });

	$("#currentEntry").on('click','.covshtbtn', function(e){
		var entryid = $(this).data('entryid');
		window.location = 'coversheet.php?sbmid=' + entryid;
	});

	$('#availableEntry').on('click', '.applyBtn', function ( event ){
		var useAppTemplate = 'applicationForm';
		var useContest = $(this).data('contest-num');
		window.location = useAppTemplate + '.php?id=' + useContest;
	});

  $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
});
