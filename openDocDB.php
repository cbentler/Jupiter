<?php

include('config.php');

$return = false;
$test = false;
$configLocation = '';
$docName = '';
$link = '';


if (isset($_POST['uploadnum'])) {
  $uploadnum = $_POST['uploadnum'];

  try{
    $sql = $db->prepare("SELECT val FROM sysconfig WHERE sysconfignum = 102 ;");
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();
    $row = $sql->fetch();
    $configLocation = $row['val'];

    $sql2 = $db->prepare("SELECT path FROM upload WHERE uploadnum = :uploadnum ;");
    $sql2->bindParam(':uploadnum', $uploadnum);
    $sql2->setFetchMode(PDO::FETCH_ASSOC);
    $sql2->execute();
    $row = $sql2->fetch();
    $docName = $row['path'];

    $link = $configLocation.$docName;

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
  //echo($link);
  echo($link);
}else{
  if($test){
  }else{
  echo("There was an error opening the document.");
  //echo("Error: ".$reason);
  }
}


?>
