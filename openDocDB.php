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

    $link = "file://".$configLocation.$docName;

    $file = basename(urldecode($_GET['file']));
    $fileDir = '/path/to/files/';

    if (file_exists($configLocation . $docName))
    {
        // Note: You should probably do some more checks
        // on the filetype, size, etc.
        $contents = file_get_contents($configLocation . $docName);

        // Note: You should probably implement some kind
        // of check on filetype
        header('Content-type: image/jpeg');

        //echo $contents;
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
  //echo($link);
  echo($contents);
}else{
  if($test){
  }else{
  echo("There was an error opening the document.");
  //echo("Error: ".$reason);
  }
}


?>
