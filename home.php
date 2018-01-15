<?php
   include('config.php');
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


      #landingPage{
        background-color: white;
        margin: 50px;
        min-height: 200px;
        text-align: center;
        padding: 5px;
      }
      #pageLabel{
        background-color: #595959;
        height: 50px;
        text-align: right;
        font-size: 30pt;
        font-weight: bold;
        color: white;
        padding-right: 5px;
      }



    </style>
    <script>
    </script>
  </head>
  <body>
    <?php
       include('header.html');
    ?>
    <div id="pageLabel">
      Home
    </div>
    <div id="info">
      <div id="landingPage">
        <?php include('quickReferenceText.txt'); ?>
      </div>
    </div>


  </body>
</html>
