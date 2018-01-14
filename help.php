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
      }

      #pageLabel{
        background-color: purple;
        height: 50px;
        text-align: right;
        font-size: 30pt;
        font-weight: bold;
        color: white;
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
      Help
    </div>
    <div id="info">
      <div id="landingPage">
        <?php
           include('helpText.html');
        ?>
      </div>
    </div>


  </body>
</html>
