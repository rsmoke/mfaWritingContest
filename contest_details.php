<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['flashMessage'] = '';
}
if ($isAdmin){
  $contestID = $_GET["contestID"];
  settype($contestID, "int");

  if (isset($_POST["disq-entryid"]) && isset($_POST["disq-entry"])) {
    try {
    $stmt = $db->prepare("UPDATE tbl_entry SET status = 3 WHERE id = ? AND contestID = ?;");
    $stmt->bind_param("ii", $_POST["disq-entryid"], $contestID);
    $stmt->execute();
  } catch(Exception $e) {
    db_fatal_error("data update issue", $e->getMessage(), $sqlDisqEntry ,$login_name);
    exit();
  }
  $stmt->close();

  unset($_POST["disq-entry"]);
  }

  $sqlContestDetail = <<<SQL
  SELECT *
  FROM vw_entrydetail
  WHERE ContestInstance = $contestID AND Status IN (0,2)
  ORDER BY EntryID
SQL;

  if (!$result = $db->query($sqlContestDetail)) {
  db_fatal_error("data read issue", $db->error, $sqlContestDetail, $login_name);
  exit;
  }

  $sqlContestName = <<<SQL
  SELECT ContestsName
  FROM vw_contestlisting_plusttlcounts
  WHERE contestid = $contestID AND Status IN (0,2)
SQL;

  if (!$nameresult = $db->query($sqlContestName)) {
  db_fatal_error("data read issue", $db->error, $sqlContestName, $login_name);
  exit;
  }
  
  $nameresult->data_seek(0);

  $firstRow = $nameresult->fetch_row();
  $contestName = $firstRow[0];
}

?>
<!DOCTYPE html>
<html lang="en-US">

<?php include("_head.php"); ?>

<body>
  <?php include("_navbar.php");?>
  <div class='container'>
<?php if ($isAdmin){ ?>
    <div id="flashArea"><span class='flashNotify'><?php echo $_SESSION['flashMessage']; $_SESSION['flashMessage'] = ""; ?></span></div>

    <div class="row clearfix">
      <div class="col">

        <div id="instructions">
          <p class='bg-primary text-white text-center'>These are the current 'Active' contest entries for the <?php echo "$contestName";?></p>
          <a href="contest_admin.php" role="button" class="btn btn-sm btn-success">
              <i class="fas fa-arrow-alt-circle-left"></i>
              return to contest list
          </a>
        </div><!-- #instructions -->
        <hr>
        <div id="activeContestList">
          <?php
            $row_count = $result->num_rows;
            if ($row_count > 0 ){
            $result->data_seek(0);
              while ($row = $result->fetch_assoc()) {
                $html = '<div class="card">
                          <div class="card-header">
                            <small class="text-muted">'
                            . $row['EntryId'] .
                            '</small>
                            <a
                              href="fileholder.php?file=' . $row['document'] .
                              '" target="_blank"
                              class="btn btn-sm btn-outline-primary toolytip">
                              <span
                                class="tooltiptext"
                                style="background-color: #007BFF;right: 0%; left: 110%; width: 125px;">
                                  open in new window
                              </span>
                              <i class="fas fa-file"></i>
                            </a> <sup class="text-muted">Title: </sup>'
                            . $row['title'] . '
                          </div>
                          <div class="card-body">
                          <div class="container">
                            <div class="row">
                              <div class="col-4"><sup class="text-muted">Name: </sup>' . $row['firstname'] . ' ' . $row['lastname'] . '</div>
                              <div class="col"><sup class="text-muted">Uniq: </sup> ' . $row['uniqname']. '</div>
                              <div class="col"><sup class="text-muted">UMID: </sup>' . $row['UMID'] . '</div>
                              <div class="col">
                              <form enctype="multipart/form-data" action="'. htmlspecialchars($_SERVER["PHP_SELF"]) . '?contestID=' . $row['ContestInstance'] . '" method="post">
                                  <input type="hidden" name="disq-entryid" value="' . $row['EntryId'] .'"/>
                                  <span class="toolytip">
                                    <button type="submit" name="disq-entry" value="" class="btn btn-sm btn-outline-warning" id="hyperlink-style-button">
                                      <span class="tooltiptext"
                                        style="background-color: #F7BB07;">
                                        disqualify entry
                                      </span>
                                      <i class="fas fa-ban"></i>
                                    </button>
                                  </span>
                                </form>
                              </div>

                            </div>
                          </div>

                          </div>
                        </div>';
                echo $html;
              }
            } else{
              echo "<p> There are no entries in this contest";
            }
          ?>
        </div>

      </div>
    </div>
  </div>

<?php } else { redirect_to(); }

include("_footer.php"); ?>

</body>
</html>
