<?php
  header('Content-Type: application/pdf');
  readfile($_SERVER["DOCUMENT_ROOT"] . '/../mfacontestfiles/' . preg_replace('/[^-a-zA-Z0-9_\.]/', '', $_GET['file']));
?>
