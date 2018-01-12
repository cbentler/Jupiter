<?php
include('config.php');

$return = false;
$test = false;

if (isset($_POST['recordnum'], $_POST['templatenum'])) {
  $recordnum = $_POST['recordnum'];
  $templatenum = $_POST['templatenum'];
  $notes = $_POST['notearea'];
  $data = $_POST;
  $toBindArr = [];
  $tst = [];
  $metatst = [];
  $numOfloop = 0;
  $key = '';

  if(strlen($templatenum) == 3){

      try{
        //update tempate[num] table
        $query = "UPDATE jdms.template".$templatenum." SET ";
        for($i = 0; $i < count($data); ++$i){
          $key = key($data);
          $subArr = [];
          if(strpos($key, 'f') === 0){
            $query .= $key." = ".":".$i.", ";
            array_push($subArr, $i, $key);
            array_push($toBindArr, $subArr);
          }else{
            //die("An invalid column header was identified.");
          }
          next($data);

        }
        $queryTrim = rtrim($query, ", ");
        $queryTrim .= " WHERE recordnum = :recordnum;";
        //UPDATE jdms.template101 SET f101 = :2, f105 = :3, f102 = :4, f106 = :5, f103 = :6 WHERE recordnum = :recordnum;

        $sql = $db->prepare($queryTrim);
        $sql->bindParam(':recordnum', $recordnum);
          for($j = 0; $j < count($toBindArr); ++$j){
            $col = $toBindArr[$j];
            $sql->bindParam(":".$col[0], $data[$col[1]], PDO::PARAM_STR);
          }
          $sql->setFetchMode(PDO::FETCH_ASSOC);
          $sql->execute();


        //update note table
        $sql2 = $db->prepare("UPDATE jdms.notes SET note = :notes WHERE recordnum = :recordnum;");
        $sql2->bindParam(':recordnum', $recordnum, PDO::PARAM_STR);
        $sql2->bindParam(':notes', $notes, PDO::PARAM_STR);
        $sql2->setFetchMode(PDO::FETCH_ASSOC);
        $sql2->execute();

        $return = true;
      }
      catch(PDOException $e){
        echo 'Connection failed: ' . $e->getMessage();
        $reason = "failed DB Query";
        $return = false;
      }

    }else{
      $reason = "Invalid templatenum";
      $return = false;
    }
  }else{
    $reason = "Failed POST";
    $return = false;

  }


if($return){
  echo '<script type="text/javascript">window.close();</script>';
}else{
  if($test){
    //var_dump($_POST);
    //var_dump($data);
    //echo($queryTrim);
    //echo("num of loop: ".$numOfloop."  count of bind array:".count($toBindArr)."   ");
    //echo($toBindArr)
    //echo("    tobind arr:");
    //var_dump($toBindArr);
    //var_dump($metatst);
  }else{
  echo("There was an error saving the record.  Please close this window and re-open from the search page.");
  //echo("Error: ".$reason);
}
}

?>
