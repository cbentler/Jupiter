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


}

$start = new query();
$start->onLoad();

?>
