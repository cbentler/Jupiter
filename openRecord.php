<HTML>
  <head>
    <script src="resources/jquery-3.2.1.min.js" ></script>
    <link rel="stylesheet" href="ui/jquery-ui.min.css">
    <script src="ui/external/jquery/jquery.js"></script>
    <script src="ui/jquery-ui.min.js"></script>
    <script>

    templatenum = '';
    displayName = '';
    recordnum = '';
    newDocNum = 1;


    //on loading
    function onLoad(){
      $(document).ready(function () {
        //prepare doc
        $(window).keydown(function(event){
          if(event.keyCode == 13 && ($(event.target)[0]!=$("textarea")[0])) {
            event.preventDefault();
            return false;
            }
          });


        $("#tabs").tabs();
        //prepare table
        templatenum = parseURL('tem');
        displayName = parseURL('dis');
        recordnum = parseURL('rec');
        fieldCall();
        getDocs();

      });
    }

    //parse url
    function parseURL(name, url){
      if (!url) url = window.location.href;
      //console.log(url);
      name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
        results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
      }

    //get field data
    function fieldCall(){
      $.ajax({
          url: 'openRecordDB.php',
          data: {'templatenum': templatenum},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            try{
              var tempArray = JSON.parse(data);
            }
            catch(ex){
              $('#workSection').html("There was an issue loading this record.  Please close this window and try to realod from search list.");
            }
              recallRecord(tempArray);
          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
        });
    }



    //set fields up
    function recallRecord(tableData){
      //sets template num for this form to be submitted

      //all form html
      var fullhtml = '<input type="submit" class="saveBtn" value="Save" />';//onclick="saveFunction()"
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
      recordhtml += '<tr><th id="dispNameTH" colspan="3"></th></tr>';
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
      noteshtml += '<div style="text-align: center;">Enter notes below.<br><textarea name="notearea" id="notearea" class="notearea"></textarea></div>';
      //close tab
      noteshtml += '</div>';

      //upload Tab
      uploadhtml += '<div id="uploadWork"></div>';

      //set newUploadTable
      uploadhtml += '<table class="newUploadTable"><tr><th style="width: 40%;">New Uploads</th><th style="width: 40%;"></th><th style="width: 10%;"><input type="button" value="Add New" id="addNewRow" onclick="newDocSetup()"/></th></tr>';
      uploadhtml += '<tr id="row_1"><td>Document Description:<br><input type="text" id="des_1"></td><td><br><input type="file" id="file_1"/></td><td><input type="button" value="X" id="remove_1" class="newRowRemovebtn" onclick="removeNewRow(this.id)"/></td></tr></table>';


      //close uploadTable
      //uploadhtml += '</table>';

      uploadhtml += '<br><br><br>';

      //set uploadTable
      uploadhtml += '<table class="uploadTable"><tr><th>Attached Docs</th><th></th></tr></table>';

      //close uploadTable
      //uploadhtml += '</table>';

      //close tab
      uploadhtml += '</div>';

      //sets form html to work section - final step
      fullhtml += recordhtml + noteshtml + uploadhtml;
      fullhtml += '</div></form>';
      $("#workSection").html(fullhtml);

      //populate table data internals
      populateInternals(tableData);
      //$("#submitNum").val(submitNum);
      //console.log(fullhtml);
      $("#tabs").tabs();
      $( "#infoTab" ).click();
      $('#dispNameTH').html(displayName);
      $('#templatenum').val(templatenum);
      $('#recordnum').val(recordnum);
    }

    //populate field data
    function populateInternals(tableData){
      for(i=5; i<tableData.length; i++){
        html = '';
        cell = tableData[i][3];
        cellNum = tableData[i][1];
        label = tableData[i][0];
        type = tableData[i][2];

        html += '<input type="text" id="hiddenFieldnum_'+cellNum+'" value="'+cellNum+'" class="db" hidden/>';
        html += '<p class="label">'+label+'</p>';
        //html += '<input type="text" id="label_'+cellNum+'" class="label" value="'+label+'"><br>';
        html += '<input class="formInput" type="'+type+'" name="f'+cellNum+'" id="f'+cellNum+'" />';
        html += '<br><input type="text" id="hiddenID" value="'+cell+'" class="db" hidden/>';
        $("#"+cell).html(html);

        getFieldVals();
        getNoteVal();
        //get uploads

      }
    }

    function getFieldVals(){
      $.ajax({
          url: 'getFieldValsDB.php',
          data: {'templatenum': templatenum, 'recordnum': recordnum},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            try{
              var tempArray = JSON.parse(data);
            }
            catch(ex){
              $('#workSection').html("There was an issue loading this record.  Please close this window and try to realod from search list.");
            }
              //populate fields with vals from db
              for(i = 0; i < tempArray.length; i++){
                $('#'+tempArray[i][0]).val(tempArray[i][1]);
              }

          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
        });
    }

    function getNoteVal(){
      $.ajax({
          url: 'getNoteValsDB.php',
          data: {'recordnum': recordnum},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            //populate notes with vals from db
            $('#notearea').html(data);
          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
        });
    }

    function newDocSetup(){
      //adds a row to the New Uploads table with description and file select.
      newDocNum++;
      newRowHtml = '';
      newRowHtml = '<tr id="row_'+newDocNum+'"><td>Document Description:<br><input type="text" id="des_'+newDocNum+'"></td><td><br><input type="file" id="file_'+newDocNum+'"/></td><td><input type="button" value="X" class="newRowRemovebtn" id="remove_'+newDocNum+'" onclick="removeNewRow(this.id)"/></td></tr>';
      $('.newUploadTable').append(newRowHtml);
    }

    function removeNewRow(delId){
      //removes row in case of mistakenly added
      console.log(delId);
      var num = delId.replace('remove_','');
      var rowNum = 'row_'+num;
      console.log(rowNum);

      $('#'+rowNum).remove();
    }

    //gets document references from db and populates Attached docs table
    function getDocs(){
      $.ajax({
          url: 'getDocsDB.php',
          data: {'recordnum': recordnum},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            try{
              var tempArray = JSON.parse(data);
              console.log(tempArray);
            }
            catch(ex){
              $('.uploadTable').html("There was an issue loading this record's documents.  Please close this window and try to realod from search list.");
            }
            //populate docs table
            var docHtml = '';
            for(i = 0; i < tempArray.length; i++){
              docHtml += '<tr><td>'+tempArray[i][0]+'</td><td><input type="button" value="open" id="'+tempArray[i][1]+'"onclick="openDoc(this.id)"></td></tr>';
              console.log(docHtml);
            }
            if(docHtml != ''){
              $('.uploadTable').append(docHtml);
            }else{
              $('.uploadTable').append('<tr><td colspan="3">No Documents Found</td></tr>');
            }

          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
        });
    }

    function openDoc(id){
      window.open("/fileServer/test2.png");
      /*
      $.ajax({
          url: 'openDocDB.php',
          data: {'uploadnum': id},
          type: 'POST',
          dataType: 'text',
          success: function(data){
            try{
              console.log(data);
              window.open(data);
            }
            catch(ex){
              alert("Could not open document selected.");
            }

          },
          error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
        });
        */
    }

    //save function
    //function saveFunction(){
      //window.close();
    //}

    function validateForm(){
      submit = false;
      $('.formInput').each(function(){
        if($(this).val() != ""){
          submit = true;
          console.log($(this).val());
        }
      })
      if(submit == false){
        alert("To save the record, please input at least one value on the Info tab.");
        console.log("No fields filled in.");
      }
      //console.log("the return is: "+submit);
      return submit;

    }

    </script>
    <style>
    body{
      margin: 0;
      padding: 0;
      background-color: grey;
      font-family: arial;
    }
    #form{
      background-color: #ccc;
      margin: 10px;
    }
    #workSection{
      background-color: #ccc;
    }
    .notearea{
      background-color: #e6e6ff;
      /*e6e6ff*/
      width: 100%;
      height: 250px;
      font-size: 4pt;
    }
    .saveBtn{
      font-size: 15pt;
    }
    .label{
      margin: 0;
      padding: 0;
    }
    .formInput{
      width: 100%;
      background-color: white;
    }
    .recordtable{
      width: 100%;
      border-collapse: collapse;
    }
    .recordtable th {
      text-align: left;
      background-color: #002C64;
      color: white;
      font-size: 16pt;
    }
    .recordtable td{
      width: 33%;
      /*border: solid 1px black;*/
      padding-left: 10px;
      padding-right: 10px;
      height: 70px;
      background-color: #ccc;
    }
    .uploadWork{
      text-align: center;
    }
    .uploadTable{
      width: 95%;
      border-collapse: collapse;
    }
    .uploadTable th{
      background-color: #002C64;
      color: white;
      text-align: left;
    }
    .uploadTable tr:nth-child(even) {
      background: #dcdee6;
    }
    .uploadTable tr:nth-child(odd) {
      background: #fff;
    }

    .newUploadTable{
      width: 95%;
      border-collapse: collapse;
    }
    .newUploadTable th{
      background-color: #256838;
      color: white;
      text-align: left;
      width: 50%;
    }
    .newUploadTable tr:nth-child(even) {
      background: #ccc;
    }
    .newUploadTable tr:nth-child(odd) {
      background: #c0ccc1;
    }
    .newRowRemovebtn{
      background-color: red;
      color: white;
      border-radius: 5px;
    }

    </style>
  </head>
  <body onload="onLoad()">
    <form action="saveFormDB.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
      <input type="text" name="recordnum" hidden id="recordnum"/>
      <input type="text" name="templatenum" hidden id="templatenum"/>
      <div id="workSection">

      </div><!--end workSection-->
    </form>
  </body>
</html>
