<?php


class query{

function onLoad(){
  if (isset($_POST['action'])) {
    $action = $_POST['action'];


    switch($action){
      case 'getSearchFields':
      $this->getSearchFields($templatenum);
      break;

      case 'getTemplatenum':
      $this->getTemplatenum();
      break;

      case 'createNewTemplate':
      $data = $_POST['data'];
      $this->createNewTemplate($data);
      break;

    }

  }
}

function getSearchFields($templatenum){

  include('config.php');

  $sql = $db->prepare(" ");
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $searchfieldtable = '';
  while ($row = $sql->fetch()) {
    $searchfieldtable .= '<tr><td>'.$row["name"].'</td></tr> <tr><td> <input type="text" id="'.$row["id"].'"> </td></tr>';
  }
  $searchfieldtable .= '<tr><td><br><input type="button" value="Search"></td></tr>';
  echo ($searchfieldtable);

}
  function getTemplatenum(){
    include('config.php');

    $sql = $db->prepare("Select val from sysconfig where sysconfignum = 101;");
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();
    $row = $sql->fetch();
    $templatenum = $row["val"];
    echo($templatenum);
  }

  function createNewTemplate($data){
    include('config.php');
    $dataArr = json_decode($data, true);

    //tempName, dispName, num
    $sql = $db->prepare("INSERT INTO `jdms`.`template` (`templatenum`, `displayname`, `templatename`) VALUES ( :tempName, :dispName, :num);");
    $sql->bindParam(':tempName', $dataArr[2]);
    $sql->bindParam(':dispName', $dataArr[1]);
    $sql->bindParam(':num', $dataArr[0]);
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();

    $newMaxnum = ++$dataArr[2];

    $sql2 = $db->prepare("UPDATE jdms.sysconfig SET val =".$newMaxnum." WHERE sysconfignum = 101;");
    $sql2->setFetchMode(PDO::FETCH_ASSOC);
    $sql2->execute();
  }


}

$start = new query();
$start->onLoad();

?>
