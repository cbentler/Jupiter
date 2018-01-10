<?php
include('config.php');

//update form fields
if (isset($_POST['submitNum'])) {
  $templatenum = $_POST['submitNum'];

  $columns = [];
  $values = [];
  $toBind = [];
  $recordnum = '';
  $saveBool = "";

  foreach($_POST as $k => $v) {
    if(strpos($k, 'f') === 0) {
      if("" == trim($v)){
      }else{
        $params = [];
        array_push($values, trim($v));
        array_push($columns, $k);
        $param = ":" . $k;
        $params[] = $param;
        $toBind[$param] = $v;
      }
    }
  }

  if(!empty($values)){
    //If the values array is not empty, execute insert statement
    $trimval = "('".implode("', '",$values)."')";
    $trimcol = "(".implode(", ",$columns).")";

    $stmt = '';
    $stmt .= 'INSERT INTO jdms.template'.$templatenum.' '.$trimcol.' VALUES '.$trimval.';';

  try{
    //Insert data to template[num] table
    $sqli = $db->prepare($stmt);
    foreach($toBind as $param => $val){
      $sqli->bindValue($param, $val);
    }
    $sqli->setFetchMode(PDO::FETCH_ASSOC);
    $sqli->execute();

    //get recordnum
    $recordnum = $db->lastInsertID();
    //Insert note fields - notearea
    $notes = $_POST['notearea'];

    $sql2 = $db->prepare("INSERT INTO jdms.notes (recordnum, note) VALUES (:recordnum, :notes)");
    $sql2->bindParam(':recordnum', $recordnum, PDO::PARAM_STR);
    $sql2->bindParam(':notes', $notes, PDO::PARAM_STR);
    $sql2->setFetchMode(PDO::FETCH_ASSOC);
    $sql2->execute();

    $saveBool = "true";

    //var_dump($_POST);

    }catch(PDOException $e){
      echo 'Connection failed: ' . $e->getMessage();
      $saveBool = "false";
    }

  }

  header('Location: newform.php?save='.$saveBool);
  die();

}else{
  echo("An Error Occurred");
  die();
}



 ?>
