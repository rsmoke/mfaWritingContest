<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
session_start();
}

if (isset($_POST['upload'])) {
        $contestID = htmlspecialchars($_POST['contestID']); //Get id passed, this is the contests id

        // this comes from the radio buttons on submission for selecting category name ie. fiction, poetry, screenplay etc
        // $categoryID = htmlspecialchars($_POST['categoryName']);
        $title = $db->real_escape_string(htmlspecialchars($_POST["title"]));

        $sqlApplicant = "SELECT id, classLevel FROM tbl_applicant WHERE uniqname = '$login_name' ";
        if(!$resApplicant = $db->query($sqlApplicant)){
          unset($_POST['upload']);
          db_fatal_error("data lookup issue", $db->error, $sqlApplicant, $login_name);
          exit($user_err_message);
        }
        if ($resApplicant->num_rows > 0) {
          // output data of each row
            while ($row = $resApplicant->fetch_assoc()) {
                $applicantID =  $row["id"];
                $classLevelID =  $row["classLevel"];
            }
        } else {
            //no contest matched ID so go back to index to allow user to reselect a contest
            non_db_error("no applicant matched ID! Exited application - Username=> " . $login_name, $login_name);
            safeRedirect('index.php');
            exit();
        }

        if ((!empty($_FILES['fileToUpload'])) && ($_FILES['fileToUpload']['error'] == 0) && (strlen(basename($_FILES['fileToUpload']['name'])) < 250)) {
            $target_dir = $_SERVER["DOCUMENT_ROOT"] . '/../mfacontestfiles/';
            $filename = basename($_FILES['fileToUpload']['name']);
            $filename = preg_replace("/[^A-Za-z0-9\.]/", '', $filename);
            $target_file = getUTCTime() . "_" . $filename;
            //added 111215 to fix upload error due to special chars in name
            $target_file = $db->real_escape_string(htmlspecialchars($target_file));
            $target_full = $target_dir . $target_file;
            $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
            $fileType = $_FILES['fileToUpload']['type'];
            $max_file_size = 20971520; //20M

            $uploadOk = 0; //if this value is 0 the file will not upload. After all check pass it will set to 1.
            $fileErrMessage = "<strong>Use your browser's back button and correct the following errors: </strong>";

            // check for pdf filetype
            if (($fileType == "application/pdf") || ($fileType == "application/vnd.adobe.pdf")) {
              $uploadOk = 1;
            } else {
              $fileErrMessage = $fileErrMessage . " <br />=>Sorry, only PDF files are allowed. This file has a type of " . $fileType;
            }
            // check for pdf file extension
            if ($ext != 'pdf') {
              $fileErrMessage = $fileErrMessage . " <br />=>Sorry, only PDF files are allowed. This file has an extension of " . $ext;
              $uploadOk = 0;
            }
            // Check if file already exists
            if (file_exists($target_full)) {
                $fileErrMessage = $fileErrMessage . " <br />=>Sorry, that file already exists.";
                $uploadOk = 0;
            }
            // Check file size is not larger than allowable
            if ($_FILES['fileToUpload']['size'] > $max_file_size) {
                $fileErrMessage = $fileErrMessage . " <br />=>Sorry, your file was too large.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $fileErrMessage = $fileErrMessage . " <br />=>Your file was not uploaded. Confirm the file is 20 megabytes or less and in PDF format.";
                $_SESSION['flashMessage'] = "<span class='text-danger'>Your file was not uploaded. Confirm the file is 20 megabytes or less and in PDF format.</span>";
                $target_file = "empty";
                non_db_error($fileErrMessage . "Username=> " . $login_name, $login_name);
                exit($user_err_message . "<br />" . $fileErrMessage);
                // safeRedirect('index.php');
            } else {
                // if everything is ok, try to upload file
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_full)) {
                    $sqlInsert = <<<SQL
                      INSERT INTO `tbl_entry`
                          (`contestID`,
                          `applicantID`,
                          `classLevelID`,
                          `title`,
                          `documentName`,
                          `created_by`)
                          VALUES
                          ($contestID,
                          $applicantID,
                          $classLevelID,
                          '$title',
                          '$target_file',
                          '$login_name')
SQL;
                    if (!$result = $db->query($sqlInsert)) {
                          db_fatal_error("Data insert issue- " . $fileErrMessage, $db->error, $sqlInsert, $login_name);
                          exit($user_err_message);
                    } else {
                        unset($_POST['upload']);
                        $_SESSION['flashMessage'] = "<span class='text-success'>- Your successful submission has been recorded -</span>";
                        safeRedirect('index.php');
                        exit();
                    }
                } else {
                    $target_file = "empty";
                    $fileErrMessage = $fileErrMessage . " - Sorry, there was an error uploading your file.";
                    non_db_err($fileErrMessage . " - Username=> " . $login_name);
                    exit();
                }
            }
        } else {
            $target_file = "empty";
            $fileErrMessage = $fileErrMessage . " - no file information - ";
            non_db_error($fileErrMessage . " Username=> " . $login_name, $login_name);
            exit($user_err_message);
        }
}

//Since $_POST is not set this page display the contest entry form so Get id passed, this is the contests id
if (!empty($_GET['id'])) {
    $contestID = htmlspecialchars($_GET['id']);
    $sqlSelect = <<<SQL
    SELECT lk_contests.name, lk_contests.id
    FROM lk_contests where lk_contests.id =  (
                  SELECT contestsid
                  FROM tbl_contest
                  WHERE tbl_contest.id = '{$contestID}'
                  )
SQL;

    if(!$res = $db->query($sqlSelect)){
      db_fatal_error("Error: Could not resolve (get) contest name", $db->error, $sqlSelect, $login_name);
      exit($user_err_message);
    }
    if ($res->num_rows > 0) {
    // output data of each row
        while ($row = $res->fetch_assoc()) {
            $contestName = $row["name"];
            $contestsID = $row["id"]; //get the tbl_contests id of the contest
        }
    } else {
      //no contest matched ID so go back to index to allow user to reselect a contest
      non_db_error("no contest matched ID! sent user back to index to allow them to reselect a contest. Username=> " . $login_name, $login_name);
      safeRedirect('index.php');
      exit();
    }

?>

<!DOCTYPE html>
<html lang="en-US">

<?php include("_head.php"); ?>

<body>
<div class="container">

<?php include("_navbar.php");?>

  <div class="row clearfix">
    <div class="col-md-12">
    <section>
      <header>
        <h3><?php echo $contestName; ?></h3>
      </header>
      <article>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

        <input class="form-control" type="hidden" required name="contestID" value="<?php echo $contestID; ?>" >
    <?php
    switch ($contestsID) {
        case 31:
              $html = '<div id="MFATemplate">'; //THE FREDERICK BUSCH PRIZE IN CREATIVE WRITING
              $html .= '<div class="card border-dark mb-3" style="max-width: 50rem;">';
              $html .= '<div class="card-header bg-transparent border-dark">Established to commemorate the work and teaching of Frederick Busch, who passed away while serving
                        as the Zell Distinguished Visiting Professor in 2006, this $2000 prize is intended to support the work of a
                        promising fiction writer in the midst of graduate study in the Helen Zell Writers’ Program.
                        </div>';
              $html .= '<div class="card-body text-dark">';
              $html .= '<h5 class="card-title">The application should include the following:</h5>';
              $html .= '<ul>';
              $html .= '<li>A title page with only the title and name of contest entered</li>';
              $html .= '<li>A manuscript of up to 30 pages of original, unpublished work ( a single complete story, or chapter from a novel)</li>';
              $html .= '<li>A 1-2 page proposal or outline for the novel or collection of stories from which the sample is taken.</li>';
              $html .= '</ul>';
              $html .= '</div>';
              $html .= '<div class="card-footer bg-transparent border-dark">The entries will be judged by a local writer who does not teach in the MFA Program.</div>';
              $html .= '</div>';
              $html .= '</div>';
              echo $html;
            break;
        case 32:
              $html = '<div id="MFATemplate">'; //THE HENFIELD PRIZE IN FICTION
              $html .= '<div class="card border-dark mb-3" style="max-width: 50rem;">';
              $html .= '<div class="card-header bg-transparent border-dark">The $10,000 Henfield Prize in Fiction, supported by an endowment given by the Joseph F. McCrindle
                        Foundation, is to be awarded to a continuing or graduating student in the Helen Zell Writers’ Program.
                        </div>';
              $html .= '<div class="card-body text-dark">';
              $html .= '<h5 class="card-title">The application should include the following:</h5>';
              $html .= '<ul>';
              $html .= '<li>A title page with only the title and name of contest entered</li>';
              $html .= '<li>A manuscript of up to 30 pages of original, unpublished work ( a single complete story, or chapter from a novel)</li>';
              $html .= '</ul>';
              $html .= '</div>';
              $html .= '<div class="card-footer bg-transparent border-dark">The contest will be judged by a writer of national prominence who does not teach in the MFA Program.</div>';
              $html .= '</div>';
              $html .= '</div>';
              echo $html;
            break;
            case 33:
                  $html = '<div id="MFATemplate">'; //THE TYSON AWARD IN FICTION
                  $html .= '<div class="card border-dark mb-3" style="max-width: 50rem;">';
                  $html .= '<div class="card-header bg-transparent border-dark">The contest for the $2000 Tyson Award in Fiction is open to all continuing graduate students in the
                            Department of English Language and Literature at the University of Michigan.
                            </div>';
                  $html .= '<div class="card-body text-dark">';
                  $html .= '<h5 class="card-title">The application should include the following:</h5>';
                  $html .= '<ul>';
                  $html .= '<li>A title page with only the title and name of contest entered</li>';
                  $html .= '<li>A manuscript of up to 30 pages of original, unpublished work ( a single complete story, or chapter from a novel)</li>';
                  $html .= '</ul>';
                  $html .= '</div>';
                  $html .= '<div class="card-footer bg-transparent border-dark">You cannot submit more than one story, even if the total number of pages stays within the limit of thirty.
                            As required by the donors, the contest will be judged by a faculty member in the Department of English
                            who does not teach in the Helen Zell Writers’ Program.
                            </div>';
                  $html .= '</div>';
                  $html .= '</div>';
                  echo $html;
                break;
        // Do I need a default here?
    }
        ?>

        <label for="title">Title of submission</label>
        <input class="form-control" type="text" required name="title" autofocus />

        <div class="form-group fileUpload-group">
          <label class="control-label" for="fileToUpload">Select file to upload (it must be in PDF format and under 20Mbytes in size):</label>
          <input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
          <input type="file" name="fileToUpload" id="fileToUpload" required />
          <br />
          <span  class="help-block p-1 mb-1 bg-warning text-dark">Title (or first) page of the pdf should be <strong><em>title of your manuscript</em></strong> and <strong><em>name of contest entered</em></strong> only.</span>
        </div>
        <div class='text-center'>
          <input class="btn btn-success p-1 mb-1" type="submit" name="upload" value="Upload Application">
        </div>
         </form>
    </article>
    </section>
    </div>
  </div>
</div><!-- class="container" -->

<!-- if there is not a record display this stuff -->
<?php include("_footer.php");?>

<script>
var uploadField = document.getElementById("fileToUpload");

uploadField.onchange = function() {
    if(this.files[0].size > 20971520){
       alert("Your file is too big!");
       this.value = "";
    };
};
</script>

</body>
</html>
<?php
} else {
  //"no ID in url so go back to index to allow user to reselect a contest"
  non_db_error("no ID in url! sent user back to index to allow them to reselect a contest. Username=> " . $login_name, $login_name);
  safeRedirect('index.php');
  exit();
}
?>
