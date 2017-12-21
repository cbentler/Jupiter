<?php
include('config.php');

//update form fields
if (isset($_POST['templatenum'])) {
  $data = $_POST['data'];
  $dataArr = json_decode($data, true);
  $removeArray = $dataArr[0];
  $addArr = $dataArr[1];
  $templatenum = $_POST['templatenum'];

  //sets removed fields to inactive
  $sql = $db->prepare("UPDATE jdms.fieldcfg SET active = 0 WHERE templatenum = :templatenum AND id IN (:removeArray) ;");
  $sql->bindParam(':templatenum', $templatenum);
  $sql->bindParam(':removeArray', $removeArray);
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();

  //Add column to template table
  $stmt = "ALTER TABLE template".$templatenum." ";
  for ($i = 0; $i < count($addArr) ; ++$i){
    if($i == 0){
      $stmt .= "ADD COLUMN f".$addArr[$i]." VARCHAR(200) NULL ";
    }else{
      $stmt .= ", ADD COLUMN f".$addArr[$i]." VARCHAR(200) NULL ";
    }
  }
  $stmt .= ";";
  $sql2 = $db->prepare($stmt);
  $sql2->bindParam(':templatenum', $templatenum);
  $sql2->bindParam(':addArray', $removeArray);
  $sql2->setFetchMode(PDO::FETCH_ASSOC);
  $sql2->execute();

}else{
  echo("error, no template num set");
}
?>
