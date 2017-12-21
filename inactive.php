<?php
include('config.php');

//update form fields
if (isset($_POST['data'])) {
  $removeArray = $_POST['data'];
  $templatenum = $_POST['templatenum'];

  //sets removed fields to inactive
  $sql = $db->prepare("UPDATE jdms.fieldcfg SET active = 0 WHERE templatenum = :templatenum AND id IN (:removeArray) ;");
  $sql->bindParam(':templatenum', $templatenum);
  $sql->bindParam(':removeArray', $removeArray);
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();

}
?>
