<?php
include('config.php');

//template list query
  $sql = $db->prepare("SELECT * from template WHERE templatenum > 100");
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $templatelisttable = '<tr><td><br><select class="templateselect" size="4" id="templatetypes">';
  while ($row = $sql->fetch()) {
    $templatelisttable .= '<option value="'.$row["templatenum"].'">'.$row["templatename"].'</option>';
  }
  $templatelisttable .= '</td></tr>';
//TODO Add scroll bar to template SELECT

?>

<html>
  <head>
    <script src="resources/jquery-3.2.1.min.js" ></script>
    <link rel="stylesheet" href="ui/jquery-ui.min.css">
    <script src="ui/external/jquery/jquery.js"></script>
    <script src="ui/jquery-ui.min.js"></script>
    <script>
      function newRecord(){
        var templatenum = $("#templatetypes").val();
        if(templatenum){

        console.log(templatenum);


        //Ajax call to database file to get template info - info tab
        $.ajax({
            url: 'getTemplateInfoDB.php',
            data: {'templatenum': templatenum},
            type: 'POST',
            dataType: 'text',
            success: function(data){
              var tempArray = JSON.parse(data);
              console.log(tempArray);
              recallNewRecord(tempArray);
            },
            error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
          });

        }else{
          alert("Please select a template.");
        }
        }

        function recallNewRecord(tableData){
          //sets template num for this form to be submitted
          var submitNum = tableData[0];
          console.log(submitNum);

          //all form html
          var fullhtml = '<form action="submitNewFormDB.php" method="post" enctype="multipart/form-data"><input type="submit" class="saveBtn" value="Save"/><input type="text" name="submitNum" id="submitNum"/>';
          fullhtml += '<div id="tabs"><ul><li><a href="#infoTab">Info</a></li><li><a href="#notesTab">Notes</a></li><li><a href="#uploadTab">Documents</a></li></ul>'
          //info tab
          var recordhtml = '<div id="infoTab">';
          //notes tab
          var noteshtml = '<div id="notesTab">';
          //upload tab
          var uploadhtml = '<div id="uploadTab">';

          var tableRowCount;

          //create shell
          recordhtml += '<table class="recordtable">';
          recordhtml += '<tr><th colspan="3"><input type="text" id="header" value="'+tableData[2]+'"/></th></tr>';
          //recall Table
          if(tableData[4] < 3){
            tableRowCount = 3;
          }else{
            tableRowCount = tableData[4];
          }

          for(row=0; row < tableRowCount; row++){
            recordhtml += '<tr>';
            for(j=0; j<3; j++){
              recordhtml += '<td id='+row+'_'+j+' class="configCell" ></td>';
            }
          }
          recordhtml += '</tr>';

          //complete html
          recordhtml += '</table></div>';




          //notes Tab
          noteshtml += '<div style="text-align: center;">Enter notes below.<br><textarea name="notearea" class="notearea" rows="36" cols="100"></textarea></div>';
          //close tab
          noteshtml += '</div>';

          //upload Tab
          uploadhtml += 'Enter File Description: <input type="text" name="description" id="description"/> <br><input type="file" name="file" id="file"/>';
          //close tab
          uploadhtml += '</div>';



          //sets form html to work section - final step
          fullhtml += recordhtml + noteshtml + uploadhtml;
          fullhtml += '</div></form>';
          $("#workSection").html(fullhtml);

          //populate table data internals
          populateInternals(tableData);
          $("#submitNum").val(submitNum);
          console.log(fullhtml);
          $("#tabs").tabs();
          $( "#infoTab" ).click();
        }


        function populateInternals(tableData){
          for(i=5; i<tableData.length; i++){
            html = '';
            cell = tableData[i][3];
            cellNum = tableData[i][1];
            label = tableData[i][0];
            type = tableData[i][2];

            html += '<input type="text" id="hiddenFieldnum_'+cellNum+'" value="'+cellNum+'" class="db" hidden/>';
            html += '<input type="text" id="label_'+cellNum+'" class="label" value="'+label+'"><br>';
            html += '<input class="formInput" type="'+type+'" name="input_f'+cellNum+'" id="input_f'+cellNum+'" />';
            html += '<br><input type="text" id="hiddenID" value="'+cell+'" class="db" hidden/>';
            $("#"+cell).html(html);

          }
        }



        function onLoad(){
          $(document).ready(function () {

            $("#tabs").tabs();

          });
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
        background-color: grey;
      }
      #form{
        background-color: #ccc;
        margin: 10px;
      }
      #pageLabel{
        background-color: green;
        height: 50px;
        text-align: center;
        font-size: 30pt;
        font-weight: bold;
        color: white;
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
      .recordtable{
        width: 100%;
      }
      .recordtable th{
        text-align: left;
      }
      .recordtable td{
        width: 33%;
        border: solid 1px black;
      }
      .notearea{
        background-color: #e6e6ff;
      }
      .saveBtn{
        font-size: 15pt;
      }


      </style>
      <script>
      </script>
    </head>
    <body onload="onLoad()">
      <?php
         include('header.html');
      ?>
      <div id="pageLabel">
        Add New
      </div>
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
                <tr>
                  <td>
                <input type="button" value="New Record" onclick="newRecord();"/>
              </td>
            </tr>
          </table>
        </div>
        <div id="workSection">
          <div id="infoTab">
          </div>
          <div id="notesTab">
          </div>
          <div id="uploadTab">
          </div>


        </div>
      </div>


    </body>
  </html>
