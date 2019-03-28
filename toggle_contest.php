<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {
  //Receive the RAW post data.
  $content = trim(file_get_contents("php://input"));
  $decoded = json_decode($content, true);

  //If json_decode failed, the JSON is invalid.
  if(is_array($decoded)) {

    $sql = <<<SQL
    UPDATE tbl_contest
    SET judgingOpen = $decoded[contest_action]
    WHERE id=$decoded[contest_id]
    LIMIT 1
SQL;
    echo $sql;

    if(!$result = $db->query($sql)){
      db_fatal_error("ERROR: Update failed", $db->error, $sql, $login_name);
      exit($user_err_message);
    }
    safeRedirect('contest_admin.php');
    exit();

  } else {
    exit($user_err_message);
  }
}


