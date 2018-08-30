<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($isAdmin){

//   // admin deletion section
//   if (isset($_POST["admin_delete"])) {
//     $admin_uniq =  htmlspecialchars($_POST['admin_uniq']);
//     if ($admin_uniq != 'rsmoke') {
//         $sqlDeleteAdmin = <<< _SQL
//             DELETE FROM tbl_contestadmin
//             WHERE uniqname = '$admin_uniq';
// _SQL;
//
//         if (!$result= $db->query($sqlDeleteAdmin)) {
//             db_fatal_error("data delete issue", $db->error, $sqlDeleteAdmin ,$login_name);
//             exit;
//         }
//
//         // echo "Deleted admin ID: " . $idSent;
//     }
//     unset($_POST["admin_delete"]);
//     unset($_POST["admin_uniq"]);
//   }
//
//   if (isset($_POST["admin_add"])) {
//     $admin_uniq = $db->real_escape_string(htmlspecialchars($_POST['admin_uniq']));
//     if ((in_array($admin_uniq, $admins) == false) && (preg_match('/^[a-z]{1,8}$/',$admin_uniq))){
//       $sqlAdminAdd = "INSERT INTO tbl_contestadmin (edited_by, uniqname) VALUES('$login_name','$admin_uniq')";
//       if (!$result = $db->query($sqlAdminAdd)) {
//               db_fatal_error("data insert issue", $db->error, $sqlAdminAdd, $login_name);
//               exit($user_err_message);
//       }
//     }
//     unset($_POST["admin_add"]);
//     unset($_POST["admin_uniq"]);
//   }
}

?>
<!DOCTYPE html>
<html lang="en-US">

<?php include("_head.php"); ?>

<body>
  <?php if ($isAdmin){ ?>
  <?php include("_navbar.php");?>
  <div class='container'>
    <div class="row clearfix">
      <div class="col">
        <div id="instructions">
          <p class='bg-info text-white text-center'>Setup new contests, manage judges for active contests, manage entries for the <?php echo "$contestTitle";?> Application</p>
        </div><!-- #instructions -->
      </div>
    </div>

    <div class="row clearfix">
      <div class="col">
        <div id="new_contests"> <!-- setup/manage new contests and manage judges for active contests -->
          <?php
          $sqlContestsSel = <<<SQL
          SELECT *
          FROM vw_contestlisting
          ORDER BY ContestsName
SQL;
          if (!$resADM = $db->query($sqlContestsSel)) {
          db_fatal_error("data read issue", $db->error, $sqlContestsSel, $login_name);
          exit;
          }
          while ($row = $resADM->fetch_assoc()) {
            // $fullname = ldapGleaner($row['uniqname']);
            $html = '<div class="record">';
            $html .= '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" >';
            $html .= '<input type="hidden" name="contestid" value="' . $row['contestid'] . '" />';
            $html .= '<strong>' . $row['ContestsName'] . '</strong>' . ' - ' . $row['date_open'] . ' - ' . $row['date_closed'] .
              '<button type="submit" name="contest_delete" class="m-1 btn btn-sm btn-outline-light"><i class="fas fa-sm fa-trash text-danger"></i></button>';
            $html .= '</form>';
            $html .= '</div>';
            echo $html;
          }
          ?>
        </div>
      </div>
    </div>

    <div class="row clearfix">
      <div class="col">
        <div id="manage_entries"> <!-- manage entries for active contests -->
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
            <div id="myAdminForm"><!-- add Admin -->
              To add a new set of contests please enter open/close dates and click below:<br>
              <label for="from">From</label>
              <input type="text" id="from" name="from">
              <label for="to">to</label>
              <input type="text" id="to" name="to">
              <button type="submit"  name="admin_add" class=" m-1 btn btn-info btn-sm" id="contestAdd">Add contests for <?php echo date('Y');?>/<?php echo date('Y')+1;?></button>
            </div>
            <!-- //////////////////////////////// -->
          </form><!-- add Admin -->
        </div>
      </div>
    </div>
  </div>

<?php } else { redirect_to(); }

include("_footer.php"); ?>
<script src="js/jquery-ui.min"></script>
<script>
$( function() {
  var dateFormat = "mm/dd/yy",
    from = $( "#from" )
      .datepicker({
        // defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3
      })
      .on( "change", function() {
        to.datepicker( "option", "minDate", getDate( this ) );
      }),
    to = $( "#to" ).datepicker({
      defaultDate: "+3m",
      changeMonth: true,
      numberOfMonths: 3
    })
    .on( "change", function() {
      from.datepicker( "option", "maxDate", getDate( this ) );
    });

  function getDate( element ) {
    var date;
    try {
      date = $.datepicker.parseDate( dateFormat, element.value );
    } catch( error ) {
      date = null;
    }

    return date;
  }
} );
</script>
</body>
</html>
