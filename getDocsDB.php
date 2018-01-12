<?php

include('config.php');

$return = false;
$test = false;
$docs = [];


if (isset($_POST['recordnum'])) {
  $recordnum = $_POST['recordnum'];

  try{
    $sql = $db->prepare("SELECT description, uploadnum FROM upload WHERE recordnum = :recordnum ;");
    $sql->bindParam(':recordnum', $recordnum);
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();
    while($row = $sql->fetch()){
      $docRow = [];
      array_push($docRow, $row['description'], $row['uploadnum']);
      array_push($docs, $docRow);
    }


    $return = true;
  }
  catch(PDOException $e){
    echo 'Connection failed: ' . $e->getMessage();
    $reason = "failed DB Query";
    $return = false;
  }

}else{
  $reason = "Post failure";
  $return = false;
}


if($return){
  $JSONOutput = json_encode($docs);
  echo($JSONOutput);
}else{
  if($test){
    echo($row);
  }else{
  echo("There was an error populating the records.  Please close this window and re-open from the search page.");
  //echo("Error: ".$reason);
  }
}


?>
