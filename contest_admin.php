<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($isAdmin){
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
          <p class='bg-primary text-white text-center'>These are the current 'Active' contest instances in the <?php echo "$contestTitle";?> Application</p>
        </div><!-- #instructions -->
        <div id="activeContestList">
          <?php
          $sqlCurrentContest = <<<SQL
          SELECT ContestsName,ttlCount,date_open,date_closed,contestid,status,judgingOpen
          FROM vw_contestlisting_plusttlcounts1
          WHERE status = 0
          ORDER BY ContestsName
SQL;

          if (!$result = $db->query($sqlCurrentContest)) {
          db_fatal_error("data read issue", $db->error, $sqlCurrentContest, $login_name);
          exit;
          }
            $html = '<div class="card-deck">';
                while ($row = $result->fetch_assoc()) {
                  $ischecked = ($row['judgingOpen'] == 1 ) ? "checked" : " ";
            $html .= '<div class="card">
                        <div class="card-header">
                          <div>' . $row['ContestsName'] .'</div>
                          <hr>
                          <div class="custom-control form-control-sm custom-switch">
                            <input type="checkbox" class="custom-control-input" onClick="contestToggle(' . $row['contestid'] . ')"  id="customSwitch' . $row['contestid'] . '" ' . $ischecked . '>
                            <label class="custom-control-label" for="customSwitch' . $row['contestid'] . '">Open for Judging</label>
                          </div>
                        </div>
                        <div class="card-body">
                          <ul class="list-group list-group-flush small">
                            <li class="list-group-item">Current number of entries: <strong>' . $row['ttlCount'] . '</strong></li>
                            <li class="list-group-item">Open date: <strong>' . date("j F Y", strtotime($row["date_open"])) . '</strong></li>
                            <li class="list-group-item">Close date: <strong>' . date("j F Y", strtotime($row["date_closed"])) . '</strong></li>
                          </ul>
                        </div>
                        <div class="card-footer text-muted">
                          <a href="contest_details.php?contestID=' . $row['contestid'] . '" class="btn btn-primary btn-sm">View Entries</a>
                        </div>
                      </div>';
                }
            $html .= '</div>';
            echo $html;

          ?>
        </div>

      </div>
    </div>

    <div class="row clearfix">
      <div class="col">
        <div class="pastContestList"></div>
      </div>
    </div>
  </div>

<?php } else { redirect_to(); }

include("_footer.php"); ?>

</body>
</html>
