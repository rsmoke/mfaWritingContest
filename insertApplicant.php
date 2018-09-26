<?php
//inserted these values
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

  $userFname = $db->real_escape_string(htmlspecialchars($_POST["userFname"]));
  $userLname = $db->real_escape_string(htmlspecialchars($_POST["userLname"]));
  $umid = htmlspecialchars($_POST["umid"]);
  $uniqname = htmlspecialchars($_POST["uniqname"]);
  $classLevel =  htmlspecialchars($_POST["classLevel"]);

  settype($userFname, "string");
  settype($userLname, "string");
  settype($umid, "string");
  settype($uniqname, "string");
  settype($classLevel, "int");


  $sqlInsert = <<<SQL
  INSERT INTO `tbl_applicant` (`userFname`, `userLname`, `umid`, `uniqname`
    ,`classLevel`, `created_by`, `created_on`)
  VALUES ('$userFname', '$userLname', '$umid', '$uniqname'
    , '$classLevel', '$login_name', now())
SQL;
if (!$db->query($sqlInsert)) {
   db_fatal_error("Insert failed", $db->error, $sqlInsert, $login_name);
        exit($user_err_message);
}
  //echo "New record created successfully";
  safeRedirect('index.php');
  exit();
