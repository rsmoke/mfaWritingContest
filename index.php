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
<html lang="en">

<?php include("_head.php"); ?>

<body>

<?php include("_navbar.php");?>

    <div class="container"><!--Container of all things -->
    <div id="flashArea"><span class='flashNotify'>
    <?php
    if (isset($_SESSION['flashMessage'])) {
        echo $_SESSION['flashMessage'];
        $_SESSION['flashMessage'] = "";
    }
    ?></span></div>
<?php
if ($hasApplicantDetails) {
?>

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
                  echo '<a role="button" class="btn btn-sm" href="header_edit.php" data-toggle="tooltip" data-placement="left" title="edit the contest page header"><i class="fas fa-edit fa-sm text-warning"></i></a>';
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
                    <li>Complete the form and click the ( <span class="btn btn-success btn-sm disabled">Upload Application</span> ) button.</li>
                    <li>Click the ( <i class="fas fa-file text-primary"></i> ) button to review your submission.</li>
                </ol>
                <ul>
                    <li><em>NOTE: Be sure your profile is up to date before submitting your entry.</em> <a role="button" class="btn btn-outline-dark btn-sm" href="detailEdit.php" data-toggle="tooltip" data-placement="left" title="open your profile"><i class="fas fa-user-circle fa-lg text-info"></i></a></li>
                    <li><em>NOTE: The pen name in your profile must match the one on your pdf; your real name may <u>not</u> be used.</em></li>
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

<?php
} else {
?>
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

                        <h5>ADDRESS:</h5>
                        <h6>Local(campus)</h6>
                        <label for="streetL">Street</label>
                        <input class="form-control" type="text" tabindex="130" required name="streetL" />
                        <label for="cityL">City</label>
                        <input class="form-control" type="text" tabindex="140" required name="cityL" />
                        <label for="stateL">State</label>
                          <select class="form-control bfh-states" tabindex="150" name="stateL" data-country="US" data-state="MI"></select>
                        <label for="zipL">Zip</label>
                        <input class="form-control" type="text" tabindex="160" required name="zipL" pattern="(^[0-9]{5}$)" title="enter a 5 digit zipcode" />
                        <label for="usrtelL">Phone</label>
                        <input class="form-control" type="text" tabindex="170" name="usrtelL" data-format="ddd-ddd-dddd"/>
                    <br />
                        <h6>Hometown: <button id="sameAddress" class="btn btn-xs">or use campus address</button></h6>
                        <label for="streetH">Street</label>
                        <input class="form-control" type="text" tabindex="180" required name="streetH" />
                        <label for="cityH">City</label>
                        <input class="form-control" type="text" tabindex="190" required name="cityH" />
                        <label for="stateH">State</label>
                          <select class="form-control bfh-states" tabindex="200" required name="stateH" data-country="countries" data-state="MI"></select>
                          <span id="helpBlock" class="help-block">If your hometown is not in the US, please select the country below first</span>
                        <label for="zipH">Zip</label>
                        <input class="form-control" type="text" tabindex="210" required name="zipH" pattern="(^[0-9]{5}$)" title="enter a 5 digit zipcode"/>
                        <label for="countryH">Country</label>
                          <select id="countries" class="form-control bfh-countries" tabindex="200" required name="countryH" data-country="US" data-flags="true"></select>
                        <label for="usrtelH">Phone</label>
                        <input class="form-control" type="text" tabindex="220" name="usrtelH" data-format="ddd-ddd-dddd" />

                        <!-- //////////////////////////////// -->
                    <hr>
                        <h5>ACADEMICS:</h5>
                        I am a:
                        <div id="classLevelSelect">
                          <label class="radio-inline">
                            <input type="radio" id="inlineRadio1" name="classLevel" required value="9"> Freshman
                          </label>
                          <label class="radio-inline">
                            <input type="radio" id="inlineRadio1" name="classLevel" required value="10"> Sophmore
                          </label>
                          <label class="radio-inline">
                            <input type="radio" id="inlineRadio1" name="classLevel" required value="11"> Junior
                          </label>
                          <label class="radio-inline">
                            <input type="radio" id="inlineRadio1" name="classLevel" required value="12"> Senior
                          </label>
                          <label class="radio-inline">
                            <input type="radio" id="inlineRadio2" name="classLevel" required value="20"> Graduate
                          </label>
                        </div>
                        <label for="school">SCHOOL OR COLLEGE</label>
                        <input class="form-control" type="text" tabindex="230" required name="school" placeholder="example: LSA" />
                        <label for="major">Major (if undergraduate)</label>
                        <input class="form-control" type="text" tabindex="240" name="major" placeholder="example: English" />
                        <label for="dept">Department (if graduate)</label>
                        <input class="form-control" type="text" tabindex="250" name ="dept" placeholder="example: Dept. of English Language and Literature" />
                        <label for="gradYear">Expected graduation date</label>
                        <input class="date-picker form-control" id="gradYearMonth" tabindex="260" required name="gradYearMonth" />
                        <label for="degree">Degree</label>
                        <input class="form-control" type="text" tabindex="270" required name="degree" placeholder="example: Bachelors" />
                        <label for="finAid">Do you receive NEED-BASED financial aid?&nbsp;&nbsp;</label>
                        <label class="radio-inline">
                          <input type="radio" id="inlineRadio1" name="finAid" required value="1"> YES
                        </label>
                        <label class="radio-inline">
                          <input type="radio" id="inlineRadio2" name="finAid" required value="0"> NO
                        </label><br />
                        <label for="finAidDesc">Details:</label>
                        <input class="form-control" type="textarea" name="finAidDesc" placeholder="In what years and terms did you recieve aid"/>

                        <!-- //////////////////////////////// -->

                        <hr>
                        <h5>PUBLICITY:</h5>
                        <p>If your manuscript earns a Hopwood or other award, the Hopwood committee will forward a press release to your local newspaper or media outlet.</p>
                        <label for="namePub">Entrant's name as it should appear in publicity</label>
                        <input class="form-control" type="text" tabindex="280" name="namePub" value="<?php echo $userName[0] . " " . $userName[1];?>"/>
                        <label for="homeNewspaper">Name of your hometown newspaper or preferred media outlet</label>
                        <input class="form-control" type="text" tabindex="290" name="homeNewspaper"  placeholder="example: The Times-Argus" />
                        <label for="penName">Enter a Pen name? (Do not use your real name and this pen name must match the one on your pdf entry(s))</label>
                        <input id="applicantPenName" class="form-control" type="text" tabindex="300" name="penName" required pattern="^\S[a-zA-Z \/,.'-íéö]+$" placeholder="example: Sarah Bellum" />
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

    </div><!-- End Container of all things -->
<?php
}
  include("_footer.php");
?>

</body>
</html>
