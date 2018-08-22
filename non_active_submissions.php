
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

$sql = "SELECT contestName, manuscriptType, document, title, datesubmitted, EntryId, status FROM vw_entrydetail WHERE uniqname = '$login_name' AND status NOT IN (0,1)";

if (!$result = $db->query($sql)) {
        db_fatal_error("data select issue", $db->error, $sql, $login_name);
        exit(user_err_message);
    }
if ($result->num_rows > 0 ){
  echo "<table class='table table-responsive table-sm table-hover'>";
  echo "<thead><th>Contest</th><th>Title</th><th>Submitted</th><th class='btnIcon'>Manuscript</th><th>Entry Status</th></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
      switch($row['status']) {
        case 1:
            $status_notice = "<span class='label label-danger'>Deleted</span>";
            break;
        case 2:
            $status_notice = "<span class='label label-info'>Archived</span>";
            break;
        case 3:
            $status_notice = "<span class='label label-warning'>Disqualified</span>";
            break;
        default:
            $status_notice = $row['status'];
      }
        echo "<tr><td>";
        echo $row['contestName'] . "</td><td>";
        echo $row['title'] . "</td><td>";
        echo date("F jS, Y  g:i A", (strtotime($row['datesubmitted']))) . "</td>";
        echo "<td class='btnIcon'><a href='fileholder.php?file=" . $row['document'] . "' target='_blank' data-toggle='tooltip' data-placement='left' title='opens in a new browser window'><i class='fas fa-file text-primary'></i></a></td>";
        echo "<td>" . $status_notice  . "</td></tr>";
    }
      echo "</tbody></table>";
} else {
    echo "There are no entries to view. Perhaps you deleted them all or have not submitted an entry";
}
