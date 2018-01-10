<?php
include('config.php');

//perform search function
//prepare search conditions
if (isset($_POST['templatenum'])) {
  $templatenum = $_POST['templatenum'];
  $data = $_POST['querySearchValues'];

  $recordReturn = [];
  $return = false;
  $displayName = '';
  $replaceDisp = false;

  $query = "SELECT recordnum FROM jdms.template".$templatenum." WHERE ";
  for($i = 0; $i < count($data); ++$i){
    if(strpos($data[$i][0], 'f') === 0){
      $query .= $data[$i][0]." = ".":".$i." AND ";
    }else{
      die("An invalid column header was identified.");
    }
  }
  $queryTrim = rtrim($query, "AND ");
  $queryTrim .= ";";

  try{
    $sql = $db->prepare($queryTrim);
    for($i = 0; $i < count($data); ++$i){
      $sql->bindParam(":".$i, $data[$i][1], PDO::PARAM_STR);
    }
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();

    //prepare array of returned recordnums
    while ($row = $sql->fetch()) {
      array_push($recordReturn, $row['recordnum']);
    }

  }catch(PDOException $e){
    echo 'Connection failed: ' . $e->getMessage();
  }

  if(empty($recordReturn)){
    //if array is empty set return to noResults
    $return = false;
  }else{
    //if array is not empty, continue with functions
    //get displayname
      $sql2 = $db->prepare("SELECT displayname from jdms.template where templatenum = ".$templatenum.";");
      $sql2->setFetchMode(PDO::FETCH_ASSOC);
      $sql2->execute();
      $row2 = $sql2->fetch();
      $displayName = $row2['displayname'];

      //parse display name for values to replace
      $re = '/~@(.*)@~/U';
      preg_match_all($re, $displayName, $matches);
      $matchDisplays = $matches[1];
      $displayFieldNums = implode(",f",$matches[1]);

      if(!empty($displayFieldNums)){
        //get field values for the display name
        $recordReturnString = implode(",",$recordReturn);
        $query3 = "SELECT recordnum, f".$displayFieldNums." FROM jdms.template".$templatenum." where recordnum in (".$recordReturnString.");";
        $sql3 = $db->prepare($query3);
        $sql3->setFetchMode(PDO::FETCH_ASSOC);
        $sql3->execute();

        $listDisplayRecords = [];
        $sqlArr = [];

        while ($row3 = $sql3->fetch()) {
          array_push($sqlArr,$row3);
          $dispRecordArr = [];
          array_push($dispRecordArr, $row3['recordnum']);
          foreach($matchDisplays as $field){
            $displayArray = [];
            array_push($displayArray, $field);
            array_push($displayArray, $row3["f".$field]);
            array_push($dispRecordArr, $displayArray);
          }
          array_push($listDisplayRecords,$dispRecordArr);
        }

        //replace displayname with actual values and write to array
        $replacedDisplayNames = [];
        for($i = 0; $i < count($listDisplayRecords); ++$i){
          $displayCopy = $displayName;
          $repDisp = [];
          for($j = 0; $j < count($listDisplayRecords[$i]); ++$j){
              $newDisp = str_replace("~@".$listDisplayRecords[$i][$j][0]."@~",$listDisplayRecords[$i][$j][1],$displayCopy);
              $displayCopy = $newDisp;
          }
          //recordnum
          array_push($repDisp, $listDisplayRecords[$i][0]);
          //displayname - updated
          array_push($repDisp, $newDisp);
          array_push($replacedDisplayNames, $repDisp);
        }

        $replaceDisp = true;
      }

      $return = true;
  }

//prepare and send results back
  if($return){
    //results returned
    if($replaceDisp){
      //display names had values to replace
      $results = json_encode($replacedDisplayNames);
    }else{
      //no display name values were replace (use original)
      $uneditedDisplay = [];
      foreach($recordReturn as $record){
        $subRecord = [];
        array_push($subRecord, $record);
        array_push($subRecord, $displayName);
        array_push($uneditedDisplay, $subRecord);
      }

      $results = json_encode($uneditedDisplay);

    }
  }else{
    //No results found
    $noString = "No Records Found";
    //array_push($noArr, "No Records Found");
    $results = json_encode($noString);
  }

//return results to JS
echo($results);


}



 ?>
