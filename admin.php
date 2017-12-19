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
    <script src="resources/jquery-3.2.1.min.js"></script>
    <script>
      var row ='';
      var tempFlag = 0;
      var fieldnum = '';

      //Adding the default cells to a template (3x3) and Add row button
      function newTemplate(){
        if(tempFlag == 0){
          fieldnum = 101;
          $('.configTable').html('<tr><th colspan="3">Template Name:<input type="text" value="Name"/>        Header:<input type="text" value="test"/></tr>');
          var table = '';
          for(row=0; row<3; row++){
            table += '<tr>';
            for(j=0; j<3; j++){
              table += '<td id='+row+'_'+j+' class="configCell" onclick="add(this);"></td>';
            }
          }
          table += '</tr>';
          $('.configTable').append(table);
          $('#workSection').append('<br><input type="button" value="Add New Row" onclick="newRow();"/>');
          addSave();
        }
        else{
        }
      }

      //Add function executes on click of template cells
      function add(cell){
        if($(cell).hasClass("active")){
        }else{
          var id=cell.id;
          var options = '';
          $(cell).toggleClass("active");
          $(cell).toggleClass("configCell");
          options = '<option value="text">Text</option><option value="date">Date</option><option value="email">Email</option>';
          $(cell).html('Label:<input type="text" class="label">('+fieldnum+')<br>Field Type:<select class="dropdown">'+options+'</select><br>Search?<input type="checkbox" value="Search"><br><input type="button" value="X" id="r'+id+'" style="background-color: red" onclick="remove(this);"/>');
          fieldnum++;

        }
      }

      //remove cell
      function remove(cell){
        //TODO figure out delete


        alert(cell.id);
        var cellid = cell.id;
        var fullCell = cellid.replace('r','');
        alert(fullCell);
        //document.getElementByID(fullCell);
        $('#'+fullCell)[0].html("");
        $('#'+fullCell).toggleClass("active");
        $('#'+fullCell).toggleClass("configCell");
      }


      //Function to add a new row to the config
      function newRow(){
        var table = '<tr>';
        for(j=0; j<3; j++){
          table += '<td id="'+row+'_'+j+'" class="configCell" onclick="add(this);"></td>';
        }
        row ++;
        $('.configTable').append(table);
      }
      //Adds save button
      function addSave(){
        $('#workSection').prepend('<input type="button" value="Save"/>');
        tempFlag = 1;
      }

      //save Function
      //action based on tempFlag variable 0 = error, 1 = new, 2 = edit existing
      function saveConfig(){
        if(tempFlag == 0){
          console.log("Error processing.  TempFlag ="+tempFlag)
        }else if (tempFlag == 1){
          //new form
          //TODO: Ajax call to database file with new Configuration

          //get element values

          //prepare data for db file - stringify array for json

          //Ajax call to db file

        }else if (tempFlag == 2){
          alert("Test");
        }
      }

    </script>
    <style>

      body{
        margin: 0;
        padding: 0;
        background-color: grey;
      }
      #pageLabel{
        background-color: red;
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
      .configTable{
        width: 100%;
      }
      .configTable td{
        background-color: grey;
        height: 100px;
        width: 33%;
      }
      .configCell:hover{
        opacity: 0.2;
      }
      .active:hover{

      }
      table, th, td{
        border: 1px solid black;
      }

    </style>
    <script src="resources/jquery-3.2.1.min.js"></script>
    <script>

    </script>
  </head>
  <body>
    <?php
       include('header.html');
    ?>
    <div id="pageLabel">
      Configuration Administration
    </div>
    <div id="searchInfo">
      <div id="searchNav">
        <table style="width: 100%;">
          <tr>
            <td>
              HEADER!
            </td>
          </tr>
          <TR>
            <td>
              <input type="button" value="Add a new template" name="newTemplate" onclick="newTemplate();"/>
            </td>
          </tr>
        </table>
        <br>
        <div id="middlediv">
        </div>
        <br>

        <table id="fieldtable">
        </table>
      </div>
      <div id="workSection">
        <table class="configTable">
        </table>
      </div>
    </div>


  </body>
</html>
