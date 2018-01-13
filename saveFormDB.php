<?php
include('config.php');

$return = false;
$test = false;

if (isset($_POST['recordnum'], $_POST['templatenum'])) {
  $recordnum = $_POST['recordnum'];
  $templatenum = $_POST['templatenum'];
  $notes = $_POST['notearea'];
  $newDocNum = $_POST['newDocNum'];
  $data = $_POST;
  $toBindArr = [];
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

      try{
        //get upload location
        $sqlcfg = $db->prepare("SELECT val FROM jdms.sysconfig WHERE sysconfignum = 103;");
        $sqlcfg->setFetchMode(PDO::FETCH_ASSOC);
        $sqlcfg->execute();
        $row = $sqlcfg->fetch();
        //fs location
        $target_dir = $row["val"];

        $testArr = [];

        $path = '';
        $ext = '';

        //check for return from DB on fs location
        if(isset($target_dir)){
          //loop through files based on newDocNum passed from client
          for($i = 1; $i <= $newDocNum; ++$i){
            //check for file
            if(isset($_FILES['file_'.$newDocNum]['name'])){
              //validate MIME type to verify upload is approved.
              $tempName = $_FILES['file_'.$newDocNum]['tmp_name'];
              if(check_doc_mime($tempName)){
                //set new filename
                $date = gmdate(U);
                $path = $_FILES['file_'.$newDocNum]['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $name = $recordnum.$date.".".$ext;

                //write file to location (check if successful)
                if(move_uploaded_file($_FILES['file_'.$newDocNum]['tmp_name'], $target_dir.$name)){
                  $sqlup = $db->prepare("INSERT INTO jdms.upload (recordnum, description, path) VALUES (:rec, :desc, :path);");
                  //INSERT INTO `jdms`.`upload` (`recordnum`, `description`, `path`) VALUES ('101', 'This is a test for the upload', '000.txt');
                  $sqlup->bindParam(':rec', $recordnum);
                  if(!isset($_POST['des_'.$i])){
                    $description = $_FILES['file_'.$newDocNum]['name'];
                    $sqlup->bindParam(':desc', $description);
                  }else{
                    $sqlup->bindParam(':desc', $_POST['des_'.$i]);
                  }
                  $sqlup->bindParam(':path', $name);
                  $sqlup->setFetchMode(PDO::FETCH_ASSOC);
                  $sqlup->execute();
                }else{ $reason = "File Upload Failed"; }
              }else{ $reason = "mime type match failed"; }
            }else{ $reason = "File not set";}
          }
        }else{ $reason = "target_dir not set";}
        }
        catch(PDOException $e){
          echo 'Connection failed: ' . $e->getMessage();
          $reason = "failed doc upload";
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

//$return = false;
if($return){
  //echo '<script type="text/javascript">window.close();</script>';
  echo("reason:".$reason);
  //echo("Type: ".$tempName);
  //echo $target_dir;
  echo ("NewDocNum: ".$newDocNum);
  var_dump($_FILES);
  //echo $target_dir.$name;
  //$user = posix_getpwuid(posix_geteuid());
  //var_dump($user);
  //echo $_SERVER['DOCUMENT_ROOT'];
  //$path = $target_dir;

echo "Path : $path";

require "$path";
}else{
  if($test){
      var_dump($testArr);
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

function check_doc_mime( $tmpname ) {
    // MIME types: http://filext.com/faq/office_mime_types.php
    $finfo = finfo_open( FILEINFO_MIME_TYPE );
    $mtype = finfo_file( $finfo, $tmpname );
    finfo_close( $finfo );
    if(
        $mtype == ( "application/vnd.openxmlformats-officedocument.wordprocessingml.document" ) ||
        $mtype == ( "application/vnd.ms-excel" ) ||
        $mtype == ( "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ) ||
        $mtype == ( "application/msword" ) ||
        $mtype == ( "image/png" ) ||
        $mtype == ( "image/tiff" ) ||
        $mtype == ( "image/gif" ) ||
        $mtype == ( "image/jpeg" ) ||
        $mtype == ( "image/bmp" ) ||
        $mtype == ( "text/plain" ) ||
        $mtype == ( "application/rtf" ) ||
        $mtype == ( "text/csv" ) ||
        $mtype == ( "application/pdf" ) ) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

/*
Image
Png image/png
Tiff(tif) image/tiff
Gif image/gif
Jpg image/jpeg
Bmp image/bmp

MS Docs
Doc application/msword
Docx application/vnd.openxmlformats-officedocument.wordprocessingml.document
Xls application/vnd.ms-excel
Xlsx application/vnd.openxmlformats-officedocument.spreadsheetml.sheet

Pdf
Pdf application/pdf

Text
Txt text/plain
Rtf application/rtf
csv text/csv

*/


?>
