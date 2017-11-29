<?php
include('config.php');



//template list query
  $sql = $db->prepare("SELECT * from template WHERE templatenum > 100");
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $templatelisttable = '<tr><td><br><select class="templateselect" size="4" id="templatetypes">';
  while ($row = $sql->fetch()) {
    $templatelisttable .= '<option value="'.$row["templatenum"].'" onclick="searchfieldupdate('.$row["templatenum"].');">'.$row["templatename"].'</option>';
  }
  $templatelisttable .= '</td></tr>';
//TODO Add scroll bar to template SELECT


//search field
//TODO replace with AJAX call triggered from record type list
$sql = $db->prepare("SELECT * from fieldcfg WHERE templatenum = 101");
$sql->setFetchMode(PDO::FETCH_ASSOC);
$sql->execute();
$searchfieldtable = '';
while ($row = $sql->fetch()) {
  $searchfieldtable .= '<tr><td>'.$row["name"].'</td></tr> <tr><td> <input type="text" id="'.$row["fieldnum"].'"> </td></tr>';
}
 ?>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="headerCss.css">
    <style>

      body{
        margin: 0;
        padding: 0;
        background-color: grey;
      }


      #searchInfo{
        display: grid;
        grid-template-columns: 250px auto;
        grid-template-rows: 100vh;
      }

      #searchNav{
        grid-row: 1;
        grid-column: 1;
      }
      #searchResults{
        grid-row: 1;
        grid-column: 2;
        background-color: #cdcdcd;
      }
      #middlediv{
        height: 20px;
        background-color: black;
      }
      .templateselect{
        width: 100%;
      }



    </style>
    <script>
    function searchfieldupdate(templatenum){
      alert(templatenum);
      //Make an AJAX call to the database processing file to pull back the search keys to run
    }
    </script>
  </head>
  <body>
    <?php
       include('header.html');
    ?>
    <div id="searchInfo">
      <div id="searchNav">
        <table style="width: 100%;">
          <tr>
            <th>
              Form Templates
            </th>
          </tr>
              <?php
              echo($templatelisttable);
              ?>
        </table>
        <br>
        <div id="middlediv">
        </div>
        <br>

        <table id="fieldtable">
          <?php
          echo($searchfieldtable);
          ?>
          <tr>
            <td>
              <br>
              <input type="button" value="Search">
            </td>
          </tr>
        </table>
      </div>
      <div id="searchResults">
        <table>
          <tr>
            <th>
              results
            </th>
          </tr>
        </table>
      </div>
    </div>


  </body>
</html>
