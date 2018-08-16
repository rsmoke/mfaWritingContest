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
        $streetL = $row["streetL"];
        $cityL = $row["cityL"];
        $stateL = $row["stateL"];
        $zipL = $row["zipL"];
        $usrtelL = $row["usrtelL"]; //allow NULL
        $streetH =  $row["streetH"];
        $cityH =  $row["cityH"];
        $stateH =  $row["stateH"];
        $countryH =  $row["countryH"];
        $zipH =  $row["zipH"];
        $usrtelH =  $row["usrtelH"]; //allow NULL
        $classLevel =  $row["classLevel"];
        $school =  $row["school"];
        $major =  $row["major"]; //allow NULL
        $department =  $row["department"]; //allow NULL
        $gradYearMonth =  $row["gradYearMonth"];
        $degree =  $row["degree"];
        $finAid =  $row["finAid"];
        $finAidDesc =  $row["finAidDesc"]; //allow NULL
        $namePub =  $row["namePub"]; //allow NULL
        $homeNewspaper =  $row["homeNewspaper"]; //allow NULL
        $penName =  $row["penName"]; //allow NULL
        $edited_on = $row["edited_on"];
    }
} else {
    non_db_error("No result for" . $login_name, $login_name);
    exit($user_err_message);
}


//set radio button
$froshRadState = "unchecked";
$sophRadState = "unchecked";
$junRadState = "unchecked";
$senRadState = "unchecked";
$gradRadState = "unchecked";

switch ($classLevel) {
    case 9:
        $froshRadState = "checked";
        break;
    case 10:
        $sophRadState = "checked";
        break;
    case 11:
        $junRadState = "checked";
        break;
    case 12:
        $senRadState = "checked";
        break;
    case 20:
        $gradRadState = "checked";
        break;
}

?>


<!DOCTYPE html>
<html lang="en">

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


              <h5>ADDRESS:</h5>
              <h6>Local(campus)</h6>
              <label for="streetL">Street</label>
              <input class="form-control" type="text" tabindex="130" required name="streetL" value="<?php echo $streetL;?>"  />
              <label for="cityL">City</label>
              <input class="form-control" type="text" tabindex="140" required name="cityL" value="<?php echo $cityL;?>" />
              <label for="stateL">State</label>
                <select class="form-control bfh-states" tabindex="150" name="stateL" data-country="US" data-state="<?php echo $stateL;?>"></select>
              <label for="zipL">Zip</label>
              <input class="form-control" type="text" tabindex="160" required name="zipL" value="<?php echo $zipL;?>" pattern="(^[0-9]{5}$)" placeholder="example: 48109" title="enter a 5 digit zipcode" />
              <label for="usrtelL">Phone</label>
              <input class="form-control bfh-phone" type="text" tabindex="170" name="usrtelL" data-format="ddd-ddd-dddd" value="<?php echo $usrtelL;?>" />
              <br />
              <h6>Hometown: <button id="sameAddress" class="btn btn-xs">or use campus address</button></h6>
              <label for="streetH">Street</label>
              <input class="form-control" type="text" tabindex="180" required name="streetH" value="<?php echo $streetH;?>"  />
              <label for="cityH">City</label>
              <input class="form-control" type="text" tabindex="190" required name="cityH" value="<?php echo $cityH;?>"  />
              <label for="stateH">State</label>
                <select class="form-control bfh-states" tabindex="200" required name="stateH" data-country="countries" data-state="<?php echo $stateH;?>"></select>
                <span id="helpBlock" class="help-block">If your hometown is not in the US, please select the country below first</span>
              <label for="zipH">Zip</label>
              <input class="form-control" type="text" tabindex="210" required name="zipH" value="<?php echo $zipH;?>" pattern="(^[0-9]{5}$)" placeholder="example: 01101" title="enter a 5 digit zipcode"/>
              <label for="countryH">Country</label>
                <select id="countries" class="form-control bfh-countries" tabindex="200" required name="countryH" data-country="<?php echo $countryH;?>"></select>
              <label for="usrtelH">Phone</label>
              <input class="form-control bfh-phone" type="text" tabindex="220" name="usrtelH" data-format="ddd-ddd-dddd" value="<?php echo $usrtelH;?>" />

              <!-- //////////////////////////////// -->
              <hr>
              <h5>ACADEMICS:</h5>
              I am a:
              <div id="classLevelSelect">
                <label class="radio-inline">
                  <input type="radio" id="froshRad" name="classLevel" required value="9" <?php echo $froshRadState  ?> > Freshman
                </label>
                <label class="radio-inline">
                  <input type="radio" id="sophRad" name="classLevel" required value="10" <?php echo $sophRadState  ?> > Sophmore
                </label>
                <label class="radio-inline">
                  <input type="radio" id="junRad" name="classLevel" required value="11" <?php echo $junRadState  ?> > Junior
                </label>
                <label class="radio-inline">
                  <input type="radio" id="senRad" name="classLevel" required value="12" <?php echo $senRadState  ?> > Senior
                </label>
                <label class="radio-inline">
                  <input type="radio" id="gradRad" name="classLevel" required value="20" <?php echo $gradRadState  ?> > Graduate
                </label>
               </div>

              <label for="school">SCHOOL OR COLLEGE</label>
              <input class="form-control" type="text" tabindex="230" required name="school" value="<?php echo $school;?>" placeholder="example: LSA" />
              <label for="major">Major (if undergraduate)</label>
              <input class="form-control" type="text" tabindex="240" name="major" value="<?php echo (isset($major))? $major : "";?>" placeholder="example: English" />
              <label for="dept">Department (if graduate)</label>
              <input class="form-control" type="text" tabindex="250" name ="dept" value="<?php echo (isset($dept))? $dept : "";?>" placeholder="example: Dept. of English Language and Literature" />
              <label for="gradYear">Expected graduation date</label>
              <input class="date-picker form-control" id="gradYearMonth" tabindex="260" required name="gradYearMonth" value="<?php echo $gradYearMonth;?>"  />
              <label for="degree">Degree</label>
              <input class="form-control" type="text" tabindex="270" required name="degree" value="<?php echo $degree;?>" placeholder="example: Bachelors" />
              <label for="finAid">Do you currently receive NEED-BASED financial aid?&nbsp;&nbsp;</label>

              <label class="radio-inline">
                <input type="radio" id="inlineRadio1" name="finAid" required <?php echo ($finAid == 1)? 'checked' : ''; ?> value="1"> YES
              </label>
              <label class="radio-inline">
                <input type="radio" id="inlineRadio2" name="finAid" required <?php echo ($finAid == 0)? 'checked' : ''; ?> value="0"> NO
              </label>

              <br />
              <label for="finAidDesc">Details:</label>
              <input class="form-control" type="textarea" name="finAidDesc" value="<?php echo $finAidDesc;?>" placeholder="In what years and terms did you recieve aid"/>

              <!-- //////////////////////////////// -->

              <hr>
              <h5>PUBLICITY:</h5>
              <p>If your manuscript earns a Hopwood or other award, the Hopwood committee will forward a press release to your local newspaper or media outlet.</p>
              <label for="namePub">Entrant's name as it should appear in publicity</label>
              <input class="form-control" type="text" tabindex="280" name="namePub" value="<?php echo $namePub;?>" />
              <label for="homeNewspaper">Name of your hometown newspaper or preferred media outlet</label>
              <input class="form-control" type="text" tabindex="290" name="homeNewspaper"  value="<?php echo $homeNewspaper;?>" placeholder="example: The Times-Argus" />
              <label for="penName">Enter a Pen name? (Do not use your real name and this pen name must match the one on your pdf entry(s))</label>
              <input id="applicantPenName" class="form-control" type="text" tabindex="300" name="penName" required pattern="^\S[a-zA-Z \/,.'-íéö]+$" value="<?php echo $penName;?>" placeholder="example: Sarah Bellum" />
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
