<?php
  $sqlCurrentContest = <<<SQL
  SELECT ContestsName,ttlCount,date_open,date_closed,contestid,status,judgingOpen
  FROM vw_contestlisting_plusttlcounts
  WHERE status = 2
  ORDER BY date_closed DESC, ContestsName
SQL;

  if (!$result = $db->query($sqlCurrentContest)) {
  db_fatal_error("data read issue", $db->error, $sqlCurrentContest, $login_name);
  exit;
  }
  $html = "";
  if ($result->num_rows > 0 ) {
    $html .= '<div class="card-deck">';
        while ($row = $result->fetch_assoc()) {
          $ischecked = ($row['judgingOpen'] == 1 ) ? "checked" : " ";
    $html .= '<div class="card mb-2" style="max-width: 18rem; min-width: 18rem;">
                <div class="card-header">
                  <div style="min-height: 3rem;">' . $row['ContestsName'] .'</div>
                  <hr>
                  <div class="custom-control form-control-sm custom-switch">
                    <input disabled type="checkbox" class="custom-control-input" onClick="contestToggle(' . $row['contestid'] . ')"  id="customSwitch' . $row['contestid'] . '" ' . $ischecked . '>
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
  } else {
    $html .= '<div><h4>There are no archived contests currently</h4></div>';
  }
  echo $html;
