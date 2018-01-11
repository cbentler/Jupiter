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
        font-family: arial;
      }
      #pageLabel{
        background-color: #FE7F27;
        height: 50px;
        text-align: right;
        font-size: 30pt;
        font-weight: bold;
        color: white;
        font-family: arial;
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
        background-color: #b3b3b3;
      }
      #fieldtable{
        width: 100%;
      }
      .templateselect{
        width: 100%;
      }
      .searchFields{
        width: 95%;
      }
      .noRes{
        width: 100%;
        text-align: center;
        background-color: #ff4d4d;
        color: white;
        font-size: 26pt;
      }
      .resultTable{
        width: 100%;
        padding: 0px;
        border-collapse: collapse;
      }
      .resultTable th{
        background-color: #002c64;
        color: white;
        height: 40px;
        font-size: 12pt;
        font-family: arial;
      }
      .resultTable td{
        height: 40px;
        font-size: 12pt;
        font-family: arial;
      }
      .resultTable tr:nth-child(even) {
        background: #dcdee6;
      }
      .resultTable tr:nth-child(odd) {
        background: #fff;
      }



    </style>
    <script src="resources/jquery-3.2.1.min.js"></script>
    <script>
    //set global template num
    gTempNum = 0;
    //Make an AJAX call to the database processing file to pull back the search keys to run
    function searchfieldupdate(templatenum){
      gTempNum = templatenum;
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

    //run search function
    function executeSearch(){
      //loop search fields
      querySearchValues = [];
      $('.searchFields').each(function(){
        currentVal = $(this).val();
        currentIndex = $(this).attr("id");
        if(currentVal != ""){
          searchValues = [];
          searchValues.push(currentIndex);
          searchValues.push(currentVal);
          querySearchValues.push(searchValues);
        }
      })

      if(typeof querySearchValues[0] == 'undefined'){
        alert("Please enter search values.");
        }else{


        //ajax call, pass templatenum and field data for search query
        $.ajax({
            url: 'searchDB.php',
            data: {'templatenum': gTempNum, 'querySearchValues': querySearchValues},
            type: 'POST',
            dataType: 'text',
            success: function(data){
              if(data == '"No Records Found"'){
                //no record update
                $('#workSection').html("<br><div class='noRes'>No Records Found</div>");
              }else{
                //Table create from result set
                var tempArray = JSON.parse(data);
                manageResults(tempArray);
              }

            },
            error:function (xhr,textStatus,errorThrown) { alert(textStatus+':'+errorThrown); }
          });
        //on success call manageResults();
      }
    }

    function manageResults(results){
      //get results passed from php and create table
      resultHTML = '<table class="resultTable">';
      resultHTML += '<tr><th>Results Returned</th><th style="width: 100px;"></th></tr>';
      for(i = 0; i < results.length; i++){
        resultHTML += '<tr>';
        resultHTML += '<td id="disp_'+results[i][0]+'">'+results[i][1]+'</td><td style="text-align: center;"><input type="button" value="Open" id="'+results[i][0]+'" onclick="openRecord(this.id);"></td>';
        resultHTML += '</tr>';
      }

      resultHTML += '</table>';
      $('#workSection').html(resultHTML);
      console.log("manage Results list");
      console.log(results);

    }

    function openRecord(id){
      subDisplayName = $('#disp_'+id).html();
      window.open('openRecord.php?rec='+id+'&dis='+subDisplayName+'&tem='+gTempNum, "RecordWindow"+gTempNum+id, "menubar=no,location=no,height=800,width=1050");//, "menubar=no,location=no,height=800,width=1050"
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
      </div>
      <div id="inset_form">
      </div>
    </div>


  </body>
</html>
