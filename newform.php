<?php
include('config.php');

//template list query
  $sql = $db->prepare("SELECT * from template WHERE templatenum > 100");
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $templatelisttable = '<tr><td><br><select class="templateselect" size="4" id="templatetypes">';
  while ($row = $sql->fetch()) {
    $templatelisttable .= '<option value="'.$row["templatenum"].'" onclick="newrecordupdate('.$row["templatenum"].');">'.$row["templatename"].'</option>';
  }
  $templatelisttable .= '</td></tr>';
//TODO Add scroll bar to template SELECT

?>

<html>
  <head>
    <script src="resources/jquery-3.2.1.min.js" ></script>
    <script>
      function newrecordupdate(templatenum){
        alert(templatenum);
        //Ajax call to database file
        //db return to create template
      }
    </script>
    <link rel="stylesheet" type="text/css" href="headerCss.css">
    <style>

      body{
        margin: 0;
        padding: 0;
        background-color: #ccc;
      }
      #info{
        display: grid;
        grid-template-columns: 250px auto;
        grid-template-rows: 100vh;
      }
      #nav{
        grid-row: 1;
        grid-column: 1;
        background-color: #ff01cc;
      }
      #form{
        background-color: #ccc;
        margin: 10px;
      }
      .section{
        outline-style: solid;
        outline-width: thin;
        outline-color: blue;
      }
      .sectionHeader{
        background-color: blue;
        color: white;
      }
      .templateselect{
        width: 100%;
      }


      </style>
      <script>
      </script>
    </head>
    <body>
      <?php
         include('header.html');
      ?>
      <div id="info">
        <div id="nav">
          <table style="width:100%;">
            <tr>
              <th>
                Templates
              </th>
            </tr>
                <?php
                  echo($templatelisttable);
                ?>
          </table>
        </div>
        <div id="form">
          <div class="section">
            <div class="sectionHeader">
              Section 1
            </div>
            form stuff



          </div>
        </div>
      </div>


    </body>
  </html>
