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
      #pageLabel{
        background-color: orange;
        height: 50px;
        text-align: center;
        font-size: 30pt;
        font-weight: bold;
        color: white;
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
      #workSection{
        grid-row: 1;
        grid-column: 2;
        background-color: #cdcdcd;
      }
      .templateselect{
        width: 100%;
      }



    </style>
    <script src="resources/jquery-3.2.1.min.js"></script>
    <script>
    //Make an AJAX call to the database processing file to pull back the search keys to run
    function searchfieldupdate(templatenum){
      $.ajax({url: 'database.php',
        data: {'action': 'getSearchFields', 'templatenum': templatenum},
        type: 'POST',
        dataType: 'text',
        success: function(data){
          $('#fieldtable').html(data);
        },
        error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }

      })
    }
    </script>
  </head>
  <body>
    <?php
       include('header.html');
    ?>
    <div id="pageLabel">
      Search
    </div>
    <div id="searchInfo">
      <div id="searchNav">
        <table style="width: 100%;">
          <tr>
            <th>
              Templates
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
        </table>
      </div>
      <div id="workSection">
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
