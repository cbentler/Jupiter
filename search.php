
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
        background-color: purple;
      }
      #middlediv{
        height: 20px;
        background-color: black;
      }



    </style>
    <script>
    </script>
  </head>
  <body>
    <?php
       include('header.html');
    ?>
    <div id="searchInfo">
      <div id="searchNav">
        <table>
          <tr>
            <th>
              Form Templates
            </th>
          </tr>
              <?php
              $servername = "localhost";
              $username = "jupiter";
              $password = "password";
              $dbname = "jupiter";
              $dsn = 'mysql:host=localhost;dbname=jupiter';


              $db = new mysqli($servername, $username, $password, $dbname);

              if ($db->connect_error) {
                   die("Connection failed: " . $db->connect_error);
              }


              $sql = "SELECT * from templatetable WHERE templatenum > 100";
              $result = $db->query($sql);

              if ($result->num_rows > 0) {
                $table = '';
                   while($row = $result->fetch_assoc()) {

                     $table = '<tr><td id="template'. $row["templatenum"].'"><a href="formhandler.php">'. $row["templatename"].'</a></td></tr>';

                     echo($table);

                   }
              } else {
                   echo '<tr><td style="font-size: 30; height: 50px;"colspan="7"><b>There are no current requests!</b></td></tr>';
              }

              $db->close();
              ?>
        </table>
        <br>
        <div id="middlediv">
        </div>
        <br>


        <table id="fieldtable">
          <?php
          $servername = "localhost";
          $username = "jupiter";
          $password = "password";
          $dbname = "jupiter";
          $dsn = 'mysql:host=localhost;dbname=jupiter';


          $db = new mysqli($servername, $username, $password, $dbname);

          if ($db->connect_error) {
               die("Connection failed: " . $db->connect_error);
          }


          $sql = "SELECT * from templatefieldconfig WHERE templatenum = 101";
          $result = $db->query($sql);

          if ($result->num_rows > 0) {
            $table = '';
               while($row = $result->fetch_assoc()) {

                 $table = '<tr><td>'.$row["fieldname"].'</td></tr> <tr><td> <input type="text" id="'.$row["fieldid"].'"> </td></tr>';

                 echo($table);

               }
          } else {
               echo '<tr><td style="font-size: 30; height: 50px;"colspan="7"><b>There are no current requests!</b></td></tr>';
          }

          $db->close();
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
