<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');


$sql = "SELECT * FROM tbl_applicant WHERE uniqname = '$login_name'";
if (!$result = $db->query($sql)) {
    db_fatal_error("Details Error", $db->error, $sql, $login_name);
    exit($user_err_message);
}

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $applicantID = $row["id"];
        $userFname =  $row["userFname"];
        $userLname = $row["userLname"];
        $umid = $row["umid"];
        $uniqname = $row["uniqname"];
        $classLevel =  $row["classLevel"];
        $edited_on = $row["edited_on"];
    }
} else {
    non_db_error("No result for" . $login_name, $login_name);
    exit($user_err_message);
}


//set radio button
$firstyearRadState = "unchecked";
$secondyearRadState = "unchecked";
$zellfellowRadState = "unchecked";

switch ($classLevel) {
    case 21:
        $firstyearRadState = "checked";
        break;
    case 22:
        $secondyearRadState = "checked";
        break;
    case 23:
        $zellfellowRadState = "checked";
        break;
}

?>


<!DOCTYPE html>
<html lang="en-US">

<?php include("_head.php"); ?>

<body>

<?php include("_navbar.php");?>

  <div class="container"><!--Container of all things -->
      <div id="basicInfo">
        <div class="row clearfix">
          <div class="col-md-12 column">
            <h4 class="text-left text-muted">Please keep your profile up to date</h4>
            <form id="formUpdBasicInfo" action="updateApplicant.php" method="post">

                    <input type="hidden" required name="id" value="<?php echo $applicantID;?>" />
                    <h5>NAME:</h5>
                    <label for="userFname" >First name</label>
                    <input id="applicantFname" class="form-control" type="text" tabindex="100" required name="userFname" value="<?php echo $userFname;?>" autofocus />
                    <label for="userLname">Last name</label>
                    <input id="applicantLname" class="form-control" type="text" tabindex="110"required name="userLname" value="<?php echo $userLname;?>" />
                    <label for="umid">UMID:&nbsp;</label><?php echo $umid;?> <br />
                    <label for="emailAddress">Campus eMail:&nbsp;</label><?php echo $login_name . "@umich.edu";?>
                    <hr>
                    <h5>ACADEMICS:</h5>
                    I am a:
                    <div id="classLevelSelect">
                      <label class="radio-inline">
                        <input type="radio" id="firstyearRad" name="classLevel" required value="21" <?php echo $firstyearRadState  ?> > First-Year
                      </label>
                      <label class="radio-inline">
                        <input type="radio" id="secondyearRad" name="classLevel" required value="22" <?php echo $secondyearRadState  ?> > Second-Year
                      </label>
                      <label class="radio-inline">
                        <input type="radio" id="zellfellowRad" name="classLevel" required value="23" <?php echo $zellfellowRadState  ?> > Zell-Fellow
                      </label>
                     </div>
                  </div>
                </div>
              </div>
                    <!-- //////////////////////////////// -->
              <div class="row clearfix justify-content-center">
                <div class="col-4 p-1 mb-2">
                    <div class="btn-group" role="group">
                      <button type="submit" id="applyBasicInfo" class='btn btn-success applyBtn'>Submit</button>
                      <a id="cancel" role="button" class="btn btn-warning" href="index.php">Cancel</a>
                    </div>
                </div>
              </div>
            </form>
        <div class="row clearfix">
          <div class="col p-1 mb-1 bg-info text-white">
              This data was last update on <?php echo $edited_on;?>
          </div>
        </div>
  </div><!--Container of all things -->


<?php include("_footer.php");?>
  </div><!--End Container of all things -->
</body>
</html>
