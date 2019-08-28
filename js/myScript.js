  var host = window.location.hostname;

  function messageElement () {
    // create a new div element
    var newDiv = document.createElement("environment-alert");
    // and give it some content
    var newContent = document.createTextNode("THIS IS A DEVELOPMENT ENVIRONMENT");
    // add the text node to the newly created div
    newDiv.appendChild(newContent);
    // add the newly created element and its content into the DOM
    var currentDiv = document.querySelector("body").firstElementChild;
    document.body.insertBefore(newDiv, currentDiv);
  }

  (function() {
      var FX = {
          easing: {
              linear: function(progress) {
                  return progress;
              },
              quadratic: function(progress) {
                  return Math.pow(progress, 2);
              },
              swing: function(progress) {
                  return 0.5 - Math.cos(progress * Math.PI) / 2;
              },
              circ: function(progress) {
                  return 1 - Math.sin(Math.acos(progress));
              },
              back: function(progress, x) {
                  return Math.pow(progress, 2) * ((x + 1) * progress - x);
              },
              bounce: function(progress) {
                  for (var a = 0, b = 1, result; 1; a += b, b /= 2) {
                      if (progress >= (7 - 4 * a) / 11) {
                          return -Math.pow((11 - 6 * a - 11 * progress) / 4, 2) + Math.pow(b, 2);
                      }
                  }
              },
              elastic: function(progress, x) {
                  return Math.pow(2, 10 * (progress - 1)) * Math.cos(20 * Math.PI * x / 3 * progress);
              }
          },
          animate: function(options) {
              var start = new Date;
              var id = setInterval(function() {
                  var timePassed = new Date - start;
                  var progress = timePassed / options.duration;
                  if (progress > 1) {
                      progress = 1;
                  }
                  options.progress = progress;
                  var delta = options.delta(progress);
                  options.step(delta);
                  if (progress == 1) {
                      clearInterval(id);
                      options.complete();
                  }
              }, options.delay || 10);
          },
          fadeOut: function(element, options) {
              var to = 1;
              this.animate({
                  duration: options.duration,
                  delta: function(progress) {
                      progress = this.progress;
                      return FX.easing.swing(progress);
                  },
                  complete: options.complete,
                  step: function(delta) {
                      element.style.opacity = to - delta;
                  }
              });
          },
          fadeIn: function(element, options) {
              var to = 0;
              this.animate({
                  duration: options.duration,
                  delta: function(progress) {
                      progress = this.progress;
                      return FX.easing.swing(progress);
                  },
                  complete: options.complete,
                  step: function(delta) {
                      element.style.opacity = to + delta;
                  }
              });
          }
      };
      window.FX = FX;
  })()

  if (host === "mfawritingcontest.test" || host === "csgrsmoke.lsait.lsa.umich.edu"){
    document.body.onload = messageElement;
  }

  if (document.getElementById('flashNotify')){
    var el = document.getElementById('flashNotify');
    FX.fadeOut(el, {
        duration: 6000,
        complete: function() {
        }
    });
  }

  if (document.querySelector("#currentEntry")){
    var el1 = document.querySelector("#currentEntry");
    zlFetch('individualSubmission.php')
      .then(data => {
        el1.innerHTML = (data['body'])
      })
      .catch(error => console.log(error));
  }

  if (document.querySelector("#non_active_Entry")){
    var el2 = document.querySelector("#non_active_Entry");
    zlFetch('non_active_submissions.php')
      .then(data => {
        el2.innerHTML = (data['body'])
      })
      .catch(error => console.log(error));
  }

  if (document.querySelector("#availableEntry")){
    var el3 = document.querySelector("#availableEntry");
    zlFetch('contestList.php')
      .then(data => {
        el3.innerHTML = (data['body'])
      })
      .catch(error => console.log(error));
  }

  $("#currentEntry").on('click','.applicantdeletebtn', function(e){
    $.post( 'applicantRemEntry.php',{sbmid:$(this).data('entryid')} );
    $.get( "individualSubmission.php", function( data ) {
      $( "#currentEntry" ).html( data );
    });
  });

	$('#availableEntry').on('click', '.applyBtn', function ( event ){
		var useAppTemplate = 'applicationForm';
		var useContest = $(this).data('contest-num');
		window.location = useAppTemplate + '.php?id=' + useContest;
	});


  if (document.querySelector("#adminAdd")){
    var el4 = document.querySelector("#adminAdd");
    el4.onclick = function(event) {
      //set up a regex string and test the textbox entry against.
      var reg = /^[a-z]{1,8}$/;
      var val = document.querySelector("#admin_uniq").value;
      if (!reg.test(val)) {
             alert ("!! You did not enter a UMICH uniqname here !!");
        }
    };
  }

  function contestToggle(clicked_id)
  {
    var toggle_action = "";
    var customswitch = document.querySelector("#customSwitch" + clicked_id );
    if (customswitch.checked){
      toggle_action = 1;
    } else {
      toggle_action = 0
    }
    zlFetch("toggle_contest.php", {
      method: "post",
      body: {
        contest_id: clicked_id,
        contest_action: toggle_action
      }
    });
  }

  function archiveContest(archive_id)
  {
    zlFetch("archive_contest.php", {
      method: "post",
      body: {
        contest_id: archive_id
      }
    });
    window.location.replace("../contest_admin.php");
  }