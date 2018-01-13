<?php
include('config.php');
$locationArr = [];

//update form fields
if (isset($_POST['action'])) {
  if($_POST['action'] == "checkFileLocation"){
    $sqlc = $db->prepare("SELECT val FROM jdms.sysconfig WHERE sysconfignum = 102;");
    $sqlc->setFetchMode(PDO::FETCH_ASSOC);
    $sqlc->execute();
    $row = $sqlc->fetch();
    $location = $row['val'];
    array_push($locationArr, $location);


    $sqlc2 = $db->prepare("SELECT val FROM jdms.sysconfig WHERE sysconfignum = 103;");
    $sqlc2->setFetchMode(PDO::FETCH_ASSOC);
    $sqlc2->execute();
    $row = $sqlc2->fetch();
    $location2 = $row['val'];
    array_push($locationArr, $location2);

    $JSONOutput = json_encode($locationArr);
    echo($JSONOutput);

  }else if($_POST['action'] == "updateFileLocation"){
    $loc = $_POST['data'];
    $target = $_POST['target'];


    //Get template table info
    $sql = $db->prepare("UPDATE jdms.sysconfig SET val = :location WHERE sysconfignum = 102;");
    $sql->bindParam(':location', $loc);
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();

    //Get template table info
    $sql2 = $db->prepare("UPDATE jdms.sysconfig SET val = :target WHERE sysconfignum = 103;");
    $sql2->bindParam(':target', $target);
    $sql2->setFetchMode(PDO::FETCH_ASSOC);
    $sql2->execute();


  }

}
?>
