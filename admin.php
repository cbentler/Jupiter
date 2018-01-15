<?php
include('config.php');

//template list query
  $sql = $db->prepare("SELECT * from template WHERE templatenum > 100");
  $sql->setFetchMode(PDO::FETCH_ASSOC);
  $sql->execute();
  $templatelisttable = '<select class="templateselect" id="templatetypes">';
  while ($row = $sql->fetch()) {
    $templatelisttable .= '<option value="'.$row["templatenum"].'">'.$row["templatename"].'</option>';
  }
  $templatelisttable .= '</select>'
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
      var general = [];
      var cellArray = [];
      var numSections = '';
      var removeArray = [];
      var addArray = [];


      //Adding the default cells to a template (3x3) and Add row button
      function newTemplate(){
        if(tempFlag != 1){
          numSections = 3;
          getTemplatenum();
          cfghtml = '';
          fieldnum = 101;
          cfghtml += '<tr><th>Template Name:<input type="text" id="templateName"style="width: 90%;"/></th>';
          cfghtml += '<th>Display Name:<input type="text" id="displayName" style="width: 90%;"/></th>';
          cfghtml += '<th>Template Number:<input type="text" id="templateNumField" readonly style="width: 90%;"/></th></tr>';
          $('.configTable').html(cfghtml);
          var table = '';
          for(row=0; row<3; row++){
            table += '<tr>';
            for(j=0; j<3; j++){
              table += '<td id='+row+'_'+j+' class="configCell" ><div class="overlay" onclick="add(this);">Click To Add Field</div></td>';
            }
          }
          table += '</tr>';
          $('.configTable').append(table);

          addSave();
          tempFlag = 1;
        }
        else{
        }
      }

      //Add function executes on click of template cells.  Adds control config
      function add(overlay){
        var cell = $(overlay).closest("td");
        if($(cell).hasClass("active")){
        }else{
          //alert("add function");
          var id = $(cell).attr('id');
          var options = '';
          var html = '';
          $(cell).toggleClass("active");
          $(cell).toggleClass("configCell");
          options = '<option value="text">Text</option><option value="date">Date</option>';
          html += '<input type="text" id="hiddenFieldnum_'+fieldnum+'" value="'+fieldnum+'" class="db" hidden/>Label:<input type="text" id="label_'+fieldnum+'" class="label db"> ('+fieldnum+')<br>Field Type:<select id="fieldType_'+fieldnum+'" class="fieldType db">'+options+'</select><br>Search?'
          html += '<input type="checkbox" class="checkbox" value="Search" id="search_'+fieldnum+'" onclick="updateSearch(this.id);"><br>Remove Field: <input type="button" value="X" id="r'+fieldnum+'" style="background-color: red" onclick="remove(this);"/>';
          html += '<br><input type="text" id="hiddenID" value="'+id+'" class="db" hidden/>';
          html += '<input type="text" id="hidden_search_'+fieldnum+'" value="0" class="db" hidden/>';
          $(cell).html(html);
          addArray.push(fieldnum);
          fieldnum++;

        }
      }

      //remove cell
      function remove(deleteBtn){
        if(window.confirm("Are you sure you want to remove this field?")){
          var deleteId = $(deleteBtn).attr("id");
          var cellNum = deleteId.replace("r","");
          var cell = $(deleteBtn).closest("td");
          $(cell).empty();
          $(cell).toggleClass("active");
          $(cell).toggleClass("configCell");
          $(cell).html('<div class="overlay" onclick="add(this);">Click To Add Field</div>');

          //add to remove array
          removeArray.push(cellNum);
        }
      }


      //Function to add a new row to the config
      function newRow(){
        var table = '<tr>';
        for(j=0; j<3; j++){
          table += '<td id="'+row+'_'+j+'" class="configCell"><div class="overlay" onclick="add(this);">Click To Add Field</div></td>';
        }
        row ++;
        $('.configTable').append(table);
        numSections++;
      }

      //Adds save button
      function addSave(){
        if($("#saveBtn").length){

        }else{
          $('#workSection').append('<br><input type="button" value="Add New Row" onclick="newRow();"/>');
          $('#workSection').prepend('<input type="button" id="saveBtn" value="Save" onclick="saveConfig();"/>');
        }


      }

      //updates the hidden txt field with the search values
      function updateSearch(id){
        var field = "hidden_"+id;
        if($("#"+id).is(':checked')){
          $("#"+field).val("1");
        }else{
          $("#"+field).val("0");
        }
      }

      //save Function
      //action based on tempFlag variable 0 = error, 1 = new, 2 = edit existing
      function saveConfig(){
        if(tempFlag == 0){
          console.log("Error processing.  TempFlag ="+tempFlag)
        }else if (tempFlag == 1){
          //console.log(tempFlag+ "- New");
          //new form
          //prepare and send general information
          tempGeneral();

          $.ajax({url: 'adminDB.php',
            data: {'action': 'createNewTemplate',
            'data': JSON.stringify(general)},
            type: 'POST',
            dataType: 'text',
            success: function(data){

              getFieldVals();
              updateFormFields();
              tempFlag = 2;
              alert("Form Saved.");
              //location.reload(true);
            },
            error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
          })
        }else if (tempFlag == 2){
          tempGeneral();
          console.log(general);
          $.ajax({url: 'adminDB.php',
            data: {'action': 'updateTemplateGeneral',
            'data': JSON.stringify(general)},
            type: 'POST',
            dataType: 'text',
            success: function(data){
              //location.reload(true);
              console.log(data);
              getFieldVals();
              updateFormFields();
              addRemoveField();
              alert("Form Saved.");

            },
            error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
          })

          console.log(tempFlag + "- Edit");
        }
      }

      //opens a template to edit
      function loadEditTemplate(){
        tempFlag = 2;
        addArray = [];
        removeArray = [];
        var templatenum = $("#templatetypes").val();

        //get template info
        $.ajax({url: 'getTemplateInfoDB.php',
          data: {'templatenum': templatenum},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            var tempArray = JSON.parse(data);
            //console.log(array);
            recallTable(tempArray);
          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
        })

      }

      //Gets template num for the form from database
      function getTemplatenum(){
        $.ajax({url: 'adminDB.php',
          data: {'action': 'getTemplatenum'},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            $("#templateNumField").val(data);
            //templatenum = data;
          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }

        })
      }

      //updates the file share location in the database for the upload function
      function updateFileLocation(){
        var loc = $("#fileUpload").val();
        var target = $("#fileUploadTarget").val();
        if(window.confirm("Are you sure you want to update the fileserver location to: "+loc+" and target location to: "+target+"")){
        $.ajax({url: 'sysConfig.php',
          data: {'action': 'updateFileLocation',
          'data': loc,
          'target': target},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            //console.log(data);
          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }

          })
        }
      }

      //check file Location
      function checkFileLocation(){
        $.ajax({url: 'sysConfig.php',
          data: {'action': 'checkFileLocation'},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            var array = JSON.parse(data)
            $("#fileUpload").val(array[0]);
            $("#fileUploadTarget").val(array[1]);
            console.log("Check File Location: "+data);
            console.log("Actual Val: "+$("#fileUpload").val());
          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }

        })
      }

      //Prepares general template information (name, display name, num)
      function tempGeneral(){
        general = [];
        var name = $("#templateName").val();
        var display = $("#displayName").val();
        var num = $("#templateNumField").val();
        general.push(name, display, num, fieldnum, numSections);
      }

      //sets removed cells to Inactive
      function addRemoveField(){
        var addRemoveArr = [];
        removeArray.toString();
        addRemoveArr.push(removeArray, addArray);
        var ajaxAR = JSON.stringify(addRemoveArr);
        var templatenum = $("#templateNumField").val();
        $.ajax({url: 'addRemove.php',
          data: {'data': ajaxAR,
          'templatenum': templatenum},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            console.log(data);
          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }

        })
      }

      //gets items configured for each cell
      function getFieldVals(){
        cellArray = [];
        //0 - ID, 1 - Name, 2 - Type, 3 - coordinate, 4 - Search
        $(".active").each(function(index){
          fieldArray = [];
          $(this).children(".db").each(function(index2){
            fieldArray.push($(this).val());
            //console.log(index2 + "-" + $(this).val());
          });
          cellArray.push(fieldArray);
        })
        //console.log(cellArray);
      }

      //update database with form field config
      function updateFormFields(){
        var templatenum = $("#templateNumField").val();
        //Ajax call to db file
        console.log("CellArray:");
        console.log(cellArray);
        $.ajax({url: 'updateFormFieldsDB.php',
          data: {'templatenum': templatenum,
            'data': JSON.stringify(cellArray),
            'tempFlag': tempFlag},
          type: 'POST',
          dataType: 'text',
          success: function(data){
              console.log("success php:"+data);
            },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
            })
      }

      function recallTable(tableData){
        console.log(tableData);
        //create table
        cfghtml = '';
        cfghtml += '<tr><th>Template Name:<input type="text" id="templateName"style="width: 90%;"/></th>';
        cfghtml += '<th>Display Name:<input type="text" id="displayName" style="width: 90%;"/></th>';
        cfghtml += '<th>Template Number:<input type="text" id="templateNumField" readonly style="width: 90%;"/></th></tr>';
        $('.configTable').html(cfghtml);

        //add template VALUES
        $('#templateName').val(tableData[3]);
        $('#displayName').val(tableData[2]);
        $('#templateNumField').val(tableData[0]);

        //create edit fields
        var table = '';
        var tableRowCount = '';

        if(tableData[4] < 3){
          tableRowCount = 3;
          numSections = tableRowCount;
        }else{
          tableRowCount = tableData[4];
          numSections = tableRowCount;
        }

        for(row=0; row < tableRowCount; row++){
          table += '<tr>';
          for(j=0; j<3; j++){
            table += '<td id='+row+'_'+j+' class="configCell" ><div class="overlay" onclick="add(this);">Click To Add Field</div></td>';
          }
        }
        table += '</tr>';
        $('.configTable').append(table);

        //set existing config cells
        var options = '';
        var html = '';
        var cell = '';
        var cellNum = '';
        for(i=5; i<tableData.length; i++){
          html = '';
          cell = tableData[i][3];
          cellNum = tableData[i][1];
          $("#"+cell).toggleClass("active");
          $("#"+cell).toggleClass("configCell");
          options = '<option value="text">Text</option><option value="date">Date</option>';
          html += '<input type="text" id="hiddenFieldnum_'+cellNum+'" value="'+cellNum+'" class="db" hidden/>Label:<input type="text" id="label_'+cellNum+'" class="label db"> ('+cellNum+')<br>Field Type:<select id="fieldType_'+cellNum+'" class="fieldType db" disabled>'+options+'</select><br>Search?'
          html += '<input type="checkbox" class="checkbox" value="Search" id="search_'+cellNum+'" onclick="updateSearch(this.id);"><br>Remove Field: <input type="button" value="X" id="r'+cellNum+'" style="background-color: red" onclick="remove(this);"/>';
          html += '<br><input type="text" id="hiddenID" value="'+cell+'" class="db" hidden/>';
          html += '<input type="text" id="hidden_search_'+cellNum+'" value="0" class="db" hidden/>';
          $("#"+cell).html(html);

          $("#hiddenFieldnum_"+cellNum).val(tableData[i][1]);
          $("#label_"+cellNum).val(tableData[i][0]);
          $("#fieldType_"+cellNum).val(tableData[i][2]);
          $("#hidden_search_"+cellNum).val(tableData[i][4]);
          if($("#hidden_search_"+cellNum).val() == 1){
            $("#search_"+cellNum).prop('checked', true);
          }

        }

        addSave();
        tempFlag = 2;
        fieldnum = tableData[1];
      }

      $(document).ready(function() {
        //run on load
        checkFileLocation();
      });




    </script>
    <style>

      body{
        margin: 0;
        padding: 0;
        background-color: grey;
        font-family: arial;
      }
      #pageLabel{
        background-color: red;
        height: 50px;
        text-align: right;
        font-size: 30pt;
        font-weight: bold;
        color: white;
      }
      #searchInfoTable{
        width: 100%;
        height: 100%;
        border-collapse: collapse;
      }
      #searchInfoTable td{
        border: hidden ;
        vertical-align: top;
      }
      #searchInfo{
        /*
        display: grid;
        grid-template-columns: 250px auto;
        grid-template-rows: 100vh;*/
      }
      #searchNav{
        /*grid-row: 1;
        grid-column: 1;*/
      }
      #workSection{
        /*grid-row: 1;
        grid-column: 2;*/
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
        background-color: light grey;
      }
      .configCell:hover .overlay {
        opacity: 1;
      }
      .active:hover{
      }
      .configTable table, th, td{
        border: 1px solid black;
      }
      .templateselect{
        width: 100%;
      }

      .overlay {
        position: relative;
        width: 100%;
        height: 100%;
        opacity: 0;
        text-align: center;
        background-color: #cdcdcd;
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
      <table id="searchInfoTable">
        <tr>
          <td style="width: 250px;">
            <div id="searchNav">
              Add New Template:<br>
              <input type="button" value="New" name="newTemplate" onclick="newTemplate();"/>
              <br>
              -or-
              <br>
              Select an existing template to modify
              <br>
              <?php
              echo($templatelisttable);
              ?>
              <br>
              <input type="button" value="Edit" name="edit" onclick="loadEditTemplate();"/>
              <br>
              <br>
              -----------------------------------------
              <br>
              File upload location (Alias):
              <br>
              <input type="text" style="width: 100%;" id="fileUpload"/>
              <br>
              File Target Directory:
              <br>
              <input type="text" style="width: 100%;" id="fileUploadTarget"/>
              <br>
              <input type="button" value="Update Location" id="updateLocBtn" onclick="updateFileLocation();"/>

            </div>
          </td>
          <td style="background-color: #cdcdcd;">
            <div id="workSection">
              <table class="configTable">
              </table>
            </div>
          </td>
        </tr>
      </table>
    </div>


  </body>
</html>
