<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

$sqlApplicant = "SELECT classLevel FROM tbl_applicant WHERE uniqname = '$login_name'";

if (!$result = $db->query($sqlApplicant)) {
    db_fatal_error("data read issue", $db->error, $sqlApplicant, $login_name);
    exit($user_err_message);
}

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      //Assign variable to applicant classlevel from profile entry
          $classLevel = $row["classLevel"];
      //set database field name associated to applicant's classlevel variable
      switch ($classLevel){
          case '9':
              $eligibility = "freshmanEligible";
              break;
          case '10':
              $eligibility = "sophmoreEligible";
              break;
          case '11':
              $eligibility = "juniorEligible";
              break;
          case '12':
              $eligibility = "seniorEligible";
              break;
          case '20':
              $eligibility = "graduateEligible";
              break;
      }
  }
}

//limit contestlisting to currently open constest 120215
$current_timestamp = date('Y-m-d G:i:s');
$sqlCurrentContest = "SELECT * FROM vw_contestlisting WHERE date_closed > '$current_timestamp' AND status = 0 ORDER BY ContestsName";

if (!$result = $db->query($sqlCurrentContest)) {
    db_fatal_error("data read issue", $db->error, $sqlCurrentContest, $login_name);
    exit($user_err_message);
}

if ($result->num_rows > 0) {
        echo "<table class='table table-responsive table-condensed table-striped'>";
        echo "<thead><th>Apply</th><th>Contest</th></thead><tbody>";
    while ($row = $res->fetch_assoc()) {
           // if the contest is available to applicants classlevel the database fieldname will be set to 1 (true) and
           //  this test will be true and the contest will be displayed.
        if ($row["$eligibility"]) {
                echo'<tr><td><strong>' .
                  $row["ContestsName"] . '</strong><br /><span class="notes">' .
                  $row["contests_notes"] . '</span></td>
                  <td><button type="button" data-contest-num="' . $row["contestid"] .
                  '" class="btn btn-xs btn-success applyBtn"><span class="glyphicon glyphicon-pencil"></span></button></td>
                  </tr>';
        }
    }
        echo "</tbody></table>";
} else {
        echo "No contests are currently open";
}
