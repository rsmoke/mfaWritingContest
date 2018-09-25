<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($isAdmin){

  // admin deletion section
  if (isset($_POST["admin_delete"])) {
    $admin_uniq =  htmlspecialchars($_POST['admin_uniq']);
    if ($admin_uniq != 'rsmoke') {
        $sqlDeleteAdmin = <<< _SQL
            DELETE FROM tbl_contestadmin
            WHERE uniqname = '$admin_uniq';
_SQL;

        if (!$result= $db->query($sqlDeleteAdmin)) {
            db_fatal_error("data delete issue", $db->error, $sqlDeleteAdmin ,$login_name);
            exit;
        }

        // echo "Deleted admin ID: " . $idSent;
    }
    unset($_POST["admin_delete"]);
    unset($_POST["admin_uniq"]);
  }

  if (isset($_POST["admin_add"])) {
    $admin_uniq = $db->real_escape_string(htmlspecialchars($_POST['admin_uniq']));
    if ((in_array($admin_uniq, $admins) == false) && (preg_match('/^[a-z]{1,8}$/',$admin_uniq))){
      $sqlAdminAdd = "INSERT INTO tbl_contestadmin (edited_by, uniqname) VALUES('$login_name','$admin_uniq')";
      if (!$result = $db->query($sqlAdminAdd)) {
              db_fatal_error("data insert issue", $db->error, $sqlAdminAdd, $login_name);
              exit($user_err_message);
      }
    }
    unset($_POST["admin_add"]);
    unset($_POST["admin_uniq"]);
  }
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
          <p class='bg-info text-white text-center'>These are the current individuals who are permitted to manage the <?php echo "$contestTitle";?> Application</p>
          <a href="index.php" role="button" class="btn btn-sm btn-success">
              <i class="fas fa-arrow-alt-circle-left"></i>
              return to main page
          </a>
        </div><!-- #instructions -->
        <hr>
        <div id="adminList">
          <?php
          $sqlAdmSel = <<<SQL
          SELECT *
          FROM tbl_contestadmin
          ORDER BY uniqname
SQL;
          if (!$resADM = $db->query($sqlAdmSel)) {
          db_fatal_error("data read issue", $db->error, $sqlAdmSel, $login_name);
          exit;
          }
          while ($row = $resADM->fetch_assoc()) {
            $fullname = ldapGleaner($row['uniqname']);
            $html = '<div class="record">';
            $html .= '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" >';
            $html .= '<input type="hidden" name="admin_uniq" value="' . $row['uniqname'] . '" />';
            $html .= '<strong>' . $fullname[0] . " " . $fullname[1] . '</strong>  -- ' . $row['uniqname'] .
              '<button type="submit" name="admin_delete" class="m-1 btn btn-sm btn-outline-light"><i class="fas fa-sm fa-trash text-danger"></i></button>';
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
        <form id="myAdminForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >

            To add an Administrator please enter their <b>uniqname</b> below:<br>
            <input class="form_control" type="text" id="admin_uniq" name="admin_uniq" />
            <button type="submit"  name="admin_add" class=" m-1 btn btn-info btn-sm" id="adminAdd">Add Administrator</button>
            <br />
            <i>--look up uniqnames using the <a href="https://mcommunity.umich.edu/" target="_blank">Mcommunity directory</a>--</i>

          <!-- //////////////////////////////// -->
        </form><!-- add Admin -->
      </div>
    </div>
  </div>

<?php } else { redirect_to(); }

include("_footer.php"); ?>

</body>
</html>
