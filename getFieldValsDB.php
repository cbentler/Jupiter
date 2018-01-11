<?php
include('config.php');

$return = false;

if (isset($_POST['templatenum'], $_POST['recordnum'])) {
  $templatenum = $_POST['templatenum'];
  $recordnum = $_POST['recordnum'];

  if(strlen($templatenum) == 3){
    try{


      //Get template table info
      $sql = $db->prepare("SELECT * FROM jdms.template".$templatenum." WHERE recordnum = :recordnum ;");
      $sql->bindParam(':recordnum', $recordnum);
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      $sql->execute();
      $row = $sql->fetch();

      $outputArr = [];
      foreach($row as $key => $value){
        $internalArr = [];
        array_push($internalArr, key($row), $value);
        next($row);
        array_push($outputArr, $internalArr);
      }

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

  }else{
    $return = false;
  }




if($return){

  array_splice($outputArr, 0, 1);

  $JSONOutput = json_encode($outputArr);
  echo($JSONOutput);

}else{
  //echo("DB Fail");
  echo($reason);
}


 ?>
