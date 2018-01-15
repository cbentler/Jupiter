<?php


getSearchFields(101);

function getSearchFields($templatenum){

  include('config.php');

  $sql = $db->prepare("SELECT * from fieldcfg WHERE templatenum = $templatenum and search = 1");
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $searchfieldtable = '';
  while ($row = $sql->fetch()) {
    $searchfieldtable .= '<tr><td>'.$row["name"].'</td></tr> <tr><td> <input type="text" id="'.$row["id"].'"> </td></tr>';
  }
  echo ($searchfieldtable);

}

?>
