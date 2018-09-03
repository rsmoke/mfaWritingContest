<?php
//inserted these values
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

    $applicantid = htmlspecialchars($_POST["id"]);
    $userFname = $db->real_escape_string(htmlspecialchars($_POST["userFname"]));
    $userLname = $db->real_escape_string(htmlspecialchars($_POST["userLname"]));
    $classLevel =  htmlspecialchars($_POST["classLevel"]);

    $sql = <<<SQL
      UPDATE tbl_applicant
      SET userFname='$userFname',
        userLname='$userLname',
        classLevel='$classLevel',
        edited_by='$login_name'
      WHERE id='$applicantid' AND uniqname = '$login_name'
SQL;

    if(!$result = $db->query($sql)){
        db_fatal_error("ERROR: Update failed", $db_error, $sql, $login_name);
        exit($user_err_message);
    }
    //echo "New record created successfully";
    safeRedirect('index.php');
    exit();
