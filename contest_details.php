<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($isAdmin){
  $contestID = $_GET["contestID"];
//
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
  <?php include("_navbar.php");?>
  <div class='container'>
<?php if ($isAdmin){ ?>
    <div class="row clearfix">
      <div class="col">

        <div id="instructions">
          <p class='bg-info text-white text-center'>These are the current 'Active' contest instances in the <?php echo "$contestTitle";?> Application</p>
        </div><!-- #instructions -->
        <div id="activeContestList">
          <?php
          $sqlContestDetail = <<<SQL
          SELECT *
          FROM vw_entrydetail
          WHERE ContestInstance = $contestID AND Status = 0
          ORDER BY EntryID
SQL;

          if (!$result = $db->query($sqlContestDetail)) {
          db_fatal_error("data read issue", $db->error, $sqlContestDetail, $login_name);
          exit;
          }
            $html = '<p>' . $row['ContestsName'] . '</p>';
                while ($row = $result->fetch_assoc()) {
            $html .= '<div class="card">
                        <div class="card-body">
                        <div class="card-text">' . $row['EntryId'] . '</div>
                        </div>
                      </div>';
                }
            echo $html;

          ?>
        </div>

      </div>
    </div>

    <div class="row clearfix">
      <div class="col">
        <div class="pastContestList">Past contest instances</div>
        <!-- <form id="myAdminForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >

            To add an Administrator please enter their <b>uniqname</b> below:<br>
            <input class="form_control" type="text" id="admin_uniq" name="admin_uniq" />
            <button type="submit"  name="admin_add" class=" m-1 btn btn-info btn-sm" id="adminAdd">Add Administrator</button>
            <br />
            <i>--look up uniqnames using the <a href="https://mcommunity.umich.edu/" target="_blank">Mcommunity directory</a>--</i>

        </form><!-- add Admin -->
      </div>
    </div>
  </div>

<?php } else { redirect_to(); }

include("_footer.php"); ?>

</body>
</html>
