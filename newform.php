
<html>
  <head>
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
                $dbname = "jdms";
                $dsn = 'mysql:host=localhost;dbname=jdms';


                $db = new mysqli($servername, $username, $password, $dbname);

                if ($db->connect_error) {
                     die("Connection failed: " . $db->connect_error);
                }


                $sql = "SELECT * from template WHERE templatenum > 100";
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
