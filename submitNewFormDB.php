<?php
include('config.php');

//update form fields
if (isset($_POST['submitNum'])) {
  $templatenum = $_POST['submitNum'];

  $columns = '';
  $values = '';
  $recordnum = '';

  foreach($_POST as $k => $v) {
    $colName = '';
    if(strpos($k, 'input_') === 0) {
      if("" == trim($v)){

      }else{
        $colName = str_replace("input_", "", $k);
        $columns .= $colName.",";
        $values .= trim($v).",";
      }
    }
  }

  $trimcol = "(".rtrim($columns,", ").")";
  $trimval = "(".rtrim($values,", ").")";

  $stmt = '';
  $stmt .= 'INSERT INTO jdms.template'.$templatenum.' :col VALUES :value;';

try{
  //Insert data to template[num] table
  $sqli = $db->prepare($stmt);
  $sqli->bindParam(':col', $trimcol);
  $sqli->bindParam(':value', $trimval);
  $sqli->setFetchMode(PDO::FETCH_ASSOC);
  $sqli->execute();
  $recordnum = $db->lastInsertID();


  var_dump($_POST);
  echo($recordnum);

}catch(PDOException $e){
  echo 'Connection failed: ' . $e->getMessage();
  echo("error");
  echo("recnum:".$recordnum);
  echo("cols~".$trimcol);
  echo("values~".$trimval);

  echo("INSERT INTO jdms.template".$templatenum." ".$trimcol." VALUES ".$trimval.";");
}

//get recordnum


/*
//Insert note fields - notearea
$notes = $_POST['notearea'];


$sqli = $db->prepare("INSERT INTO jdms.notes ");
//$sqli->bindParam(':column', $trimcol, PDO::PARAM_STR);
$sqli->bindParam(':value', $trimval, PDO::PARAM_STR);
$sqli->setFetchMode(PDO::FETCH_ASSOC);
$sqli->execute();

//Insert file - description and file

*/
}else{
  echo("An Error Occurred");
}



 ?>
