<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$userName = ldapGleaner($login_name);
$hasApplicantDetails = false;

    $sqlSelect = <<<SQL
    SELECT uniqname
    FROM tbl_applicant
    WHERE uniqname = '{$login_name}'
SQL;

if (!$result = $db->query($sqlSelect)) {
        db_fatal_error("data read issue", $db->error, $sqlSelect, $login_name);
        exit($user_err_message);
}
  //do stuff with your $result set

if ($result->num_rows > 0) {
    $hasApplicantDetails = true;
}

?>
<!DOCTYPE html>
<html lang="en-US">

<?php include("_head.php"); ?>

<body>
<noscript><h4 class="text-white bg-danger px-2 mx-4">Your browser does not have JavaScript support enabled! <a href="https://www.enable-javascript.com/" target="_blank">Enable JavaScript</a> to use this site.</h4></noscript>
<?php include("_navbar.php");?>

<?php
if ($hasApplicantDetails) {
?>
  <div class="container">
    <div id="flashArea">
      <span id='flashNotify'>
      <?php
      if (isset($_SESSION['flashMessage'])) {
          echo $_SESSION['flashMessage'];
          $_SESSION['flashMessage'] = "";
      }
      ?>
      </span>
    </div>
<!-- CONTESTS AVAILABLE -->
<!-- if there is a record for the logged in user in the database then display contests available -->
    <div class="row clearfix">
        <div class="col">
          <?php if (!empty($info_header)) {
                  $html = '<h1 class="text-center">' . $info_header . '</h1>';
                  $html .= $info_body;
                  echo $html;
                } else { echo ''; }
                if ($isAdmin){
                  $html = '<div class="btn-group" role="group" aria-label="Basic example">';
                  $html .= '<a role="button" class="btn btn-sm toolytip" href="header_edit.php"><span class="tooltiptext" style="background-color: #FFC107; left: 100%;">edit description</span><i class="fas fa-edit fa-sm text-warning"></i></a>';
                  $html .= '<a role="button" class="btn btn-sm toolytip" href="contest_admin.php"><span class="tooltiptext" style="background-color: #1987FF; left: 100%;">manage contests</span><i class="fab fa-stack-overflow fa-sm text-primary"></i></a>';
                  $html .= '<a role="button" class="btn btn-sm toolytip" href="admin_edit.php"><span class="tooltiptext" style="background-color: #17A2B8; left: 100%;">manage admins</span><i class="fas fa-lock fa-sm text-info"></i></a>';
                  $html .= '</div>';
                  echo $html;
                }
          ?>
        </div>
    </div>
    <div id="instructions">
        <div class="row clearfix">
            <div class="col">
                <h5 class='p-1 bg-dark text-white'>How to submit an entry:</h5>
                <ol>
                    <li>Click the ( <i class="fas fa-pencil-alt text-success"></i> ) button adjacent to the name of the contest you’d like to enter.</li>
                    <li>Complete the form and click the ( <button type='button' class="btn btn-success btn-sm" disabled>Upload Application</button> ) button.</li>
                    <li>Click the ( <i class="fas fa-file text-primary"></i> ) button to review your submission.</li>
                </ol>
                <ul>
                    <li><span class="text-dark bg-warning"><em>NOTE: Be sure your profile is up to date before submitting your entry.</em></span> <a role="button" type='button' class="btn btn-outline-dark btn-sm toolytip" href="detailEdit.php"><span class="tooltiptext" style="left: 105%;">open your profile</span><i class="fas fa-user-circle fa-lg text-info"></i></a></li>
                    <li><em>NOTE: The single file you upload needs to be in <strong>PDF format</strong> and it will include all the items required for the contest (e.g., Title page, manuscript, other documents)</em></li>
                    <li><em>NOTE: You will need to upload a separate application for each entry.</em></li>
                </ul>
            </div>
        </div>
    </div>
    <hr>
    <div id="contestList">
        <div class="row clearfix">
            <div class="col">
              <div class="card text-white bg-success mb-3">
                <h5 class="card-header">These are the contests currently available to you:</h5>
                <div class="card-body bg-light text-dark">
                  <div id="availableEntry"></div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <hr>
    <!-- Contest history: display the contests that the logged in user has applied to -->
    <div id="appHistory">
        <div class="row clearfix">
            <div class="col">
                <h4 class="text-left text-muted">Here are your current entries:</h4>
                <div id="currentEntry"></div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col">
              <div class="card bg-light">
                <h5 class="card-header text-muted">These are inactivated contest entries <em>(mostly from past contests)</em>:</h5>
                <div class="card-body">
                  <div id="non_active_Entry"></div>
                </div>
              </div>
            </div>
        </div>
    </div>
  </div>
<?php
} else {
?>
  <div class="container"
        <!-- if there is not a record for $login_name display the basic information form. Upon submitting this data display the contest available section -->
    <form id="formBasicInfo" action="insertApplicant.php" method="post">
        <div id="basicInfo" >
          <div class="row clearfix">
              <div class="col">
                <h4 class="text-left text-muted">Before you can apply for a contest you need to enter some basic information about yourself</h4>
                  <h5>NAME:</h5>
                  <label for="userFname" >First name</label>
                  <input id="applicantFname" class="form-control" type="text" tabindex="100" required name="userFname" value="<?php echo $userName[0];?>" autofocus />
                  <label for="userLname">Last name</label>
                  <input id="applicantLname" class="form-control" type="text" tabindex="110"required name="userLname" value="<?php echo $userName[1];?>" />
                  <label for="umid">UMID</label>
                  <input class="form-control" type="text" placeholder="enter your 8 digit UMID - example: 12345678" tabindex="120" required name="umid" pattern="(^\d{8}$)" title="enter an 8 digit UMID" />
                  <label for="emailAddress">Campus eMail<br />
                  <?php echo $login_name . "@umich.edu";?></label>
                  <input class="form-control" type="hidden" required name="uniqname" value="<?php echo $login_name;?>" />
                <hr>
                <h5>ACADEMICS:</h5>
                I am a:
                <div id="classLevelSelect">
                  <label class="radio-inline">
                    <input type="radio" id="inlineRadio1" name="classLevel" required value="21"> First-Year
                  </label>
                  <label class="radio-inline">
                    <input type="radio" id="inlineRadio1" name="classLevel" required value="22"> Second-Year
                  </label>
                  <label class="radio-inline">
                    <input type="radio" id="inlineRadio1" name="classLevel" required value="23"> Zell-Fellow
                  </label>
                </div>
              </div>
            </div>
          </div>
        <div class="row clearfix justify-content-center">
          <div class="col-4 p-1 mb-2">
            <div class="btn-group" role="group">
              <button type="submit" id="applyBasicInfo" class='btn btn-success applyBtn'>Submit</button>
              <a id="cancel" role="button" class="btn btn-warning" href="index.php">Cancel</a>
            </div>
          </div>
        </div>
      </form>
    </div>
<?php
}
  include("_footer.php");
?>
</body>
</html>
