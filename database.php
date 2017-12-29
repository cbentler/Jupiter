<?php


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

  include('config.php');

  $sql = $db->prepare("SELECT * from fieldcfg WHERE templatenum = $templatenum and active = 1 and search = 1");
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $searchfieldtable = '';
  while ($row = $sql->fetch()) {
    $searchfieldtable .= '<tr><td>'.$row["name"].'</td></tr> <tr><td> <input type="text" id="'.$row["id"].'"> </td></tr>';
  }
  $searchfieldtable .= '<tr><td><br><input type="button" value="Search"></td></tr>';
  echo ($searchfieldtable);

}


}

$start = new query();
$start->onLoad();

?>
