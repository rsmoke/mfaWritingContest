
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

$sql = "SELECT contestName, document, title, datesubmitted, EntryId, status FROM vw_entrydetail WHERE uniqname = '$login_name' AND status NOT IN (0,1)";

if (!$result = $db->query($sql)) {
        db_fatal_error("data select issue", $db->error, $sql, $login_name);
        exit($user_err_message);
    }
if ($result->num_rows > 0 ){
  echo "<div class='table-responsive'><table class='table table-sm table-hover'>";
  echo "<thead><th>Contest</th><th>Title</th><th>Submitted</th><th class='btnIcon'>Manuscript</th><th class='text-secondary'>Entry Status</th></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
      switch($row['status']) {
        case 2:
            $status_notice = "<span class='text-info'>Archived</span>";
            break;
        case 3:
            $status_notice = "<span class='text-warning'>Disqualified</span>";
            break;
        default:
            $status_notice = $row['status'];
      }
        echo "<tr><td class='text-muted small'>";
        echo $row['contestName'] . "</td><td>";
        echo $row['title'] . "</td><td>";
        echo date("F jS, Y  g:i A", (strtotime($row['datesubmitted']))) . "</td>";
        echo "<td class='btnIcon'><a href='fileholder.php?file=" . $row['document'] . "' target='_blank' class='toolytip'><span class='tooltiptext' style='background-color: #007BFF;'>opens in a new browser window</span><i class='fas fa-file text-primary'></i></a></td>";
        echo "<td>" . $status_notice  . "</td></tr>";
    }
      echo "</tbody></table></div>";
} else {
    echo "There are no entries to view. Perhaps you deleted them all or have not submitted an entry";
}
