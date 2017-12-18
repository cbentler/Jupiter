<?php
  $servername = "localhost";
  $username = "jupiter";
  $password = "password";
  $dbname = "jdms";
  $dsn = 'mysql:host=localhost;dbname=jdms';

  $db = new PDO($dsn, $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
?>
