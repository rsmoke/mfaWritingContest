<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['flashMessage'] = '';
}
if ($isAdmin){

?>
<!DOCTYPE html>
<html lang="en-US">

<?php include("_head.php"); ?>

<body>
  <?php include("_navbar.php");?>
  <div class='container'>
    <div id='flashbox' class='row clearfix '>
      <div id="flashArea"><?php echo $_SESSION['flashMessage']; ?></div>
    </div>
    <div class="row clearfix border border-success pb-2 ">
      <div class="col">

        <div id="instructions">
          <p class='bg-success text-white text-center'>These are the current 'Active' contest instances in the <?php echo "$contestTitle";?> Application</p>
        </div><!-- #instructions -->
        <div id="activeContestList">
          <?php include('activeContestList.php') ?>
        </div>

      </div>
    </div>
    <hr class="w-50">
    <div class="row clearfix border border-warning pb-2 ">
      <div class="col">

        <div id="past_instructions">
          <p class='bg-warning text-black text-center'>These are the past contest instances in the <?php echo "$contestTitle";?> Application</p>
        </div><!-- #instructions -->
        <div class="pastContestList">
          <?php include('pastContestList.php') ?>
        </div>
      </div>
    </div>
  </div>

<?php 
} else { redirect_to(); }

include("_footer.php"); ?>
<script>
  $(document).ready(function(){
    var flashmessage = "<?php echo $_SESSION['flashMessage'];?>";
    console.log(flashmessage);
    if ( flashmessage.length > 0 ){
      $("#flashArea").fadeOut(7000);
    }
  });
</script>
<?php $_SESSION['flashMessage'] = ''; ?>
</body>
</html>