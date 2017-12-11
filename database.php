<?php
include('config.php');

class query{

function onLoad(){
  if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if (isset($_POST['templatenum'])){
      $templatenum = $_POST['templatenum'];
    }


    switch($action){
      case 'getSearchFields':
      $this->getSearchFields($templatenum);
      break;

    }

  }
}

function getSearchFields($templatenum){

  $sql = $db->prepare("SELECT * from fieldcfg WHERE templatenum = $templatenum and search = 1");
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $searchfieldtable = '';
  while ($row = $sql->fetch()) {
    $searchfieldtable .= '<tr><td>'.$row["name"].'</td></tr> <tr><td> <input type="text" id="'.$row["id"].'"> </td></tr>';
  }
  echo ($searchfieldtable);

}


}

$start = new query();
$start->onLoad();

?>
