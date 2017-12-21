<?php
include('config.php');

//update form fields
if (isset($_POST['action'])) {
  if($_POST['action'] == "checkFileLocation"){
    $sqlc = $db->prepare("SELECT val FROM jdms.sysconfig WHERE sysconfignum = 102;");
    $sqlc->setFetchMode(PDO::FETCH_ASSOC);
    $sqlc->execute();
    $row = $sqlc->fetch();
    $location = $row['val'];
    echo($location);

  }else if($_POST['action'] == "updateFileLocation"){
    $loc = $_POST['data'];


    //Get template table info
    $sql = $db->prepare("UPDATE jdms.sysconfig SET val = :location WHERE sysconfignum = 102;");
    $sql->bindParam(':location', $loc);
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();


  }

}
?>
