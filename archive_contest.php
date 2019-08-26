<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

if ($isAdmin) {
  $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

  if ($contentType === "application/json") {
    //Receive the RAW post data.
    $content = trim(file_get_contents("php://input"));
    $decoded = json_decode($content, true);

    //If json_decode failed, the JSON is invalid.
    if(is_array($decoded)) {

      $sql = <<<SQL
      UPDATE tbl_contest
      SET 
        status = 2,
        judgingOpen = 0
      WHERE id=$decoded[contest_id]
      LIMIT 1
SQL;

      $entrysql = <<<ENTRYSQL
      UPDATE tbl_entry
      SET status = 2
      WHERE (contestID=$decoded[contest_id] AND status=0)
      LIMIT 100
ENTRYSQL;

      if(!$result = $db->query($sql)){
        db_fatal_error("ERROR: Update failed", $db->error, $sql, $login_name);
        exit($user_err_message);
      }

      if(!$result = $db->query($entrysql)){
        db_fatal_error("ERROR: Update failed", $db->error, $entrysql, $login_name);
        exit($user_err_message);
      }
      exit();

    } else {
      exit($user_err_message);
    }
  }

}
