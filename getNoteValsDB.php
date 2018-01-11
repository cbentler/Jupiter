<?php
include('config.php');

$return = false;

if (isset($_POST['recordnum'])) {
  $recordnum = $_POST['recordnum'];

    try{
      //Get notes
      $sql = $db->prepare("SELECT note FROM notes WHERE recordnum = :recordnum ;");
      $sql->bindParam(':recordnum', $recordnum);
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      $sql->execute();
      $row = $sql->fetch();
      $notes = $row['note'];

      $return = true;
    }
    catch(PDOException $e){
      echo 'Connection failed: ' . $e->getMessage();
      $reason = "failed DB Query";
      $return = false;
    }

    $return = true;
  }else{
    $reason = "failed POST";
    $return = false;

  }




if($return){


  $JSONOutput = json_encode($notes);
  echo($notes);

}else{
  //echo("DB Fail");
  echo($reason);
}


 ?>
