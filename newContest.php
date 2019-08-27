<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
  session_start();
  $_SESSION['flashMessage'] = "";
}
if ($isAdmin) {
  if (isset($_POST['insertMultiContest'])) {
    
    $date_open = date("Y-m-d H:i:s", (strtotime($_POST['openDate'])));
    $date_closed = date("Y-m-d H:i:s", (strtotime($_POST['closeDate'])));
    $notes = $db->real_escape_string(htmlspecialchars($_POST['notesContests']));

    $sqlMultiInsert = <<< _SQL
    INSERT INTO `quilleng_MFAContestManager`.`tbl_contest`
    (
      `contestsID`,
      `date_open`,
      `date_closed`,
      `notes`,
      `created_by`,
      `edited_by`)
    VALUES
    (31,'$date_open','$date_closed','$notes','$login_name','$login_name'),
    (32,'$date_open','$date_closed','$notes','$login_name','$login_name'),
    (33,'$date_open','$date_closed','$notes','$login_name','$login_name');
_SQL;

    if (!$resAdmin = $db->query($sqlMultiInsert)) {
      db_fatal_error("data insert issue", $db->error, $sqlMultiInsert, $login_name);
      $_SESSION['flashMessage'] = "<span class='text-warning'>You already added the contests for this academic year!</span>";
      $_POST['insertMultiContest'] = false;
      safeRedirect('contest_admin.php');
    } else {
      $_SESSION['flashMessage'] = "<span class='text-success'>You successfully added the contests for this academic year!</span>";
      $_POST['insertMultiContest'] = false;
      safeRedirect('contest_admin.php');
    }
    $date_open = $date_closed = $notes = null;
    $_SESSION['flashMessage'] = "";
    $_POST['insertContest'] = false;
  }

?>
<!DOCTYPE html>
<html lang="en">
<?php include("_head.php"); ?>
  <body>
  <?php include("_navbar.php");?>
  
  <div class="container"><!-- container of all things -->
    <div class="row clearfix">
      <div class="col-md-12">
        <a href="contest_admin.php" role="button" class="btn btn-sm btn-success">
            <i class="fas fa-arrow-alt-circle-left"></i>
            return to contest list
        </a>
      </div>
    </div>
    <h4>Create all the contests for the <?php echo date("Y") . "/" . (date("Y")+1); ?> academic year.<br>
    <small>Select the opening and closing date for all the contests.</small></h4>

    <div class="row clearfix">
      <div class='outputContainer col-sm-8 col-md-offset-2'>
        <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post' id='addContestForms' >
          <div class="form-group">
            <label for='openDate'>Open Date for Contests</label>
            <div class="bfh-datepicker" data-format="y-m-d 12:00:00" data-date="today" data-icon='fas fa-calendar' data-name='openDate' required></div>
          </div>
          <div class="form-group">
            <label for='closeDate'>Close Date for Contests</label>
            <div class="bfh-datepicker" data-format="y-m-d 12:00:00" data-date="today" data-icon='fas fa-calendar' data-name='closeDate' required></div>
          </div>
          <div class='form-group'>
            <label for='notes'>Notes for Contest</label>
            <input class="form-control" type='text' name='notesContests' value='<?php echo  date("Y") . "/" . (date("Y") + 1); ?>'>
          </div>
          <input class="btn btn-success mb-2" type="submit" name="insertMultiContest" value="Insert Contests">
        </form>
      </div>
    </div>
  </div>
<?php
} else {
?>
<!-- if there is not a record for $login_name display the basic information form. Upon submitting this data display the contest available section -->
<div id="notAdmin">
<div class="row clearfix">
  <div class="col-md-8 col-md-offset-2">
    <div id="instructions" style="color:sienna;">
      <h1 class="text-center" >You are not authorized to this space!!!</h1>
      <h4>University of Michigan - LSA Computer System Usage Policy</h4>
      <p>This is the University of Michigan information technology environment. You
        MUST be authorized to use these resources. As an authorized user, by your use
        of these resources, you have implicitly agreed to abide by the highest
        standards of responsibility to your colleagues, -- the students, faculty,
        staff, and external users who share this environment. You are required to
        comply with ALL University policies, state, and federal laws concerning
        appropriate use of information technology. Non-compliance is considered a
      serious breach of community standards and may result in disciplinary and/or legal action.</p>
      </div><!-- #instructions -->
      <div class="center-block" style="width:100px;"><a href="http://www.umich.edu"><img alt="University of Michigan" src="img/michigan.png" width=80px /></a></div>
    </div>
  </div>
</div>
<?php
}
include("_footer.php");?>
<!-- //additional script specific to this page -->
<script>
  $(function () {
    $('.glyphicon').addClass('fa');
    $('.glyphicon-chevron-left').addClass('fa-chevron-left');
    $('.glyphicon-chevron-right').addClass('fa-chevron-right');
  });
</script>
</div><!-- End Container of all things -->
</body>
</html>
<?php
$db->close();
