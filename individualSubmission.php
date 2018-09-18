
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

    $sql = "SELECT contestName, document, title, datesubmitted, EntryId, date_closed FROM vw_entrydetail WHERE uniqname = '$login_name' AND status = 0";
    if (!$result = $db->query($sql)) {
            db_fatal_error("data select issue", $db->error, $sql, $login_name);
            exit($user_err_message);
        } else {
            if ($result->num_rows > 0 ){
                echo "<div class='table-responsive'><table class='table table-sm'>";
                echo "<thead><th>Contest</th><th>Title</th><th>Submitted</th><th class='btnIcon'>Manuscript</th><th class='btnIcon'>Remove</th></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td class='text-muted small'>";
                    echo $row['contestName'] . "</td><td>";
                    echo $row['title'] . "</td><td>";
                    echo date("F jS, Y  g:i A", (strtotime($row['datesubmitted']))) . "</td>";
                    echo "<td class='btnIcon'><a href='fileholder.php?file=" . $row['document'] . "' target='_blank' class='toolytip'><span class='tooltiptext' style='background-color: #007BFF;'>opens in a new browser window</span><i class='fas fa-file text-primary'></i></a></td>";
                    echo "<td class='btnIcon'><button type='button' class='btn btn-outline-danger btn-sm toolytip";

                    echo date("Y-m-d H:i:s") > $row['date_closed']? ' disabled ' : '';

                    echo " applicantdeletebtn' data-entryid='" . $row['EntryId'] . "' ><span class='tooltiptext' style='background-color: #DC3545;'>remove entry</span><i class='fas fa-trash'></i></button></td></tr>";
                }
                  echo "</tbody></table></div>";
            } else {
                echo "You currently have no entries in any of the contests";
            }
        }
