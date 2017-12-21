<?php
include('config.php');

//update form fields
if (isset($_POST['templatenum'])) {
  $templatenum = $_POST['templatenum'];

  $templateInfo = array();
  $fieldInfo = array();

  //Get template table info
  $sql = $db->prepare("SELECT fieldindexnum, displayname, templatename, numSections FROM jdms.template WHERE templatenum = :templatenum ;");
  $sql->bindParam(':templatenum', $templatenum);
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $row = $sql->fetch();
  array_push($templateInfo, $templatenum, $row["fieldindexnum"], $row["displayname"], $row["templatename"], $row["numSections"]);


  //Get Field cfg info
  $sql2 = $db->prepare("SELECT name, id, type, coordinate, search FROM jdms.fieldcfg WHERE templatenum = :templatenum and active = 1 ORDER BY coordinate;");
  $sql2->bindParam(':templatenum', $templatenum);
  $sql2->setFetchMode(PDO::FETCH_ASSOC);
  $sql2->execute();

  while($row2 = $sql2->fetch()){
    $fieldInfo= array();
    array_push($fieldInfo, $row2["name"], $row2["id"], $row2["type"], $row2["coordinate"], $row2["search"]);
    array_push($templateInfo, $fieldInfo);
  }


  $JSONOutput = json_encode($templateInfo);
  echo($JSONOutput);

}



?>
