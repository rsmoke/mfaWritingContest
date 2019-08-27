<?php
  if ($isAdmin){
    $sqlCurrentContest = <<<SQL
    SELECT ContestsName,ttlCount,date_open,date_closed,contestid,status,judgingOpen
    FROM vw_contestlisting_plusttlcounts
    WHERE status = 0
    ORDER BY ContestsName
SQL;

    if (!$result = $db->query($sqlCurrentContest)) {
    db_fatal_error("data read issue", $db->error, $sqlCurrentContest, $login_name);
    exit;
    }
      $html = "";
      if ($result->num_rows > 0 ) {
        $html .= '<div><h6>You are able to add new contests <a href="newContest.php">here</a>.</h6></div>';

        $html .= '<div class="card-deck">';
            while ($row = $result->fetch_assoc()) {
              $ischecked = ($row['judgingOpen'] == 1 ) ? "checked" : " ";
        $html .= '<div class="card mb-2" style="max-width: 18rem; min-width: 18rem;">
                    <div class="card-header">
                      <div style="min-height: 3rem;">' . $row['ContestsName'] .'</div>
                      <hr>
                      <div class="d-flex justify-content-between">
                        <div class="custom-control form-control-sm custom-switch">
                          <input type="checkbox" class="custom-control-input" onClick="contestToggle(' . $row['contestid'] . ')"  id="customSwitch' . $row['contestid'] . '" ' . $ischecked . '>
                          <label class="custom-control-label" for="customSwitch' . $row['contestid'] . '">Open for Judging</label>
                        </div>
                        <div>
                        <button onClick="archiveContest(' . $row['contestid'] . ')" type="button" class="btn btn-danger toolytip" ><span class="tooltiptext" style="left: 105%;background-color: #dc7a7f">Archive Contest and all associated Entries</span><i class="fas fa-archive fa-lg text-info"></i></button>
                        </div>
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
        $html .= '<div><h4>There are no active contests currently.<br><small>You are able to add a new contest instance <a href="newContest.php">here</a>.</small></h4></div>';
      }
      echo $html;
  }