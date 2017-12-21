<?php
include('config.php');

//update form fields
if (isset($_POST['data'])) {
  $data = $_POST['data'];
  $templatenum = $_POST['templatenum'];
  $tempflag = $_POST['tempFlag'];

  $dataArr = json_decode($data);


  if($tempflag == 1){
    //create new table for template
    $sqlstmt = '';
    $sqlstmt .= "CREATE TABLE jdms.template".$templatenum." (valnum".$templatenum." INT NOT NULL AUTO_INCREMENT,";
    for ($i = 0; $i < count($dataArr); ++$i){
      $sqlstmt .= "f".$dataArr[$i][0]." VARCHAR(200) NULL, ";
    }
    $sqlstmt .= "PRIMARY KEY (valnum".$templatenum."));";
    $sql = $db->prepare($sqlstmt);
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();
  }

  //set all existing fields to inactive
  $sqli = $db->prepare("UPDATE jdms.fieldcfg SET active = 0 WHERE templatenum = :templatenum;");
  $sqli->bindParam(':templatenum', $templatenum);
  $sqli->setFetchMode(PDO::FETCH_ASSOC);
  $sqli->execute();


  //delete existing rows for templatenum
  for($i = 0; $i < count($dataArr); ++$i){
    $sqld = $db->prepare("DELETE FROM jdms.fieldcfg WHERE templatenum = :templatenum and id = :id;");
    $sqld->bindParam(':id', $dataArr[$i][0]);
    $sqld->bindParam(':templatenum', $templatenum);
    $sqld->setFetchMode(PDO::FETCH_ASSOC);
    $sqld->execute();
  }




  //add new rows
  //0 - ID, 1 - Name, 2 - Type, 3 - coordinate, 4 - Search
  for($i = 0; $i < count($dataArr); ++$i){
    $sql2 = $db->prepare("INSERT INTO jdms.fieldcfg (templatenum, name, id, type, coordinate, active, search) VALUES (:templatenum, :name, :id, :type, :coordinate, 1, :search)");
    $sql2->bindParam(':templatenum', $templatenum);
    $sql2->bindParam(':name', $dataArr[$i][1]);
    $sql2->bindParam(':id', $dataArr[$i][0]);
    $sql2->bindParam(':type', $dataArr[$i][2]);
    $sql2->bindParam(':coordinate', $dataArr[$i][3]);
    $sql2->bindParam(':search', $dataArr[$i][4]);
    $sql2->setFetchMode(PDO::FETCH_ASSOC);
    $sql2->execute();


  }



}



?>
