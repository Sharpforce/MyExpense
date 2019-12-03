<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/global.inc.php";
  connectToDatabase();

  if(isset($_POST['createorrestore']) && $_POST['createorrestore'] === "createorrestore") {
    createOrRestoreDatabase();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/bootstrap.css"/>
    <link rel="stylesheet" href="/css/style.css"/>
    <title>Futura Business Informatique GROUPE - Conseil en ingénierie</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
  </head>

  <body>
    <div class="container-fluid">      
      <?php        
        include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.inc.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/messages_dialog.inc.php";
      ?>
    </div>

    <div class="container">
      <div class="row"> 
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Database</h3>
            </div>
            <div class="panel-body">
              <form class="col-md-4 col-md-offset-4" action="/config/setup.php" method="POST">
                <div class="form-group">
                  <label for="server">Server : </label>
                  <?php
                    echo '<input id="server" type="text" name="server" class="form-control" value="' . htmlspecialchars($_bdd['server']) . '" disabled>';
                  ?>
                </div>
                <div class="form-group">
                  <label for="port">Port : </label>
                  <?php
                    echo '<input id="port" type="text" name="port" class="form-control" value="' . htmlspecialchars($_bdd['port']) . '" disabled>';
                  ?>
                </div>
                <div class="form-group">
                  <label for="user">User : </label>
                  <?php
                    echo '<input id="user" type="text" name="user" class="form-control" value="' . htmlspecialchars($_bdd['user']) . '" disabled>';
                  ?>
                </div>
                <div class="form-group">
                  <label for="password">Password : </label>
                  <?php
                    echo '<input id="password" type="text" name="password" class="form-control" value="' . htmlspecialchars($_bdd['password']) . '" disabled>';
                  ?>
                </div>
                <div class="form-group">
                  <?php
                    global $_bdd;

                    $GLOBALS['___mysqli_ston'] = new mysqli($_bdd['server'], $_bdd['user'], $_bdd['password'], "", $_bdd['port']);
                    if($GLOBALS['___mysqli_ston']->connect_errno) {
                      echo '<button type="button" class="btn btn-danger form-control disabled">Connection to the database : FAILED</button>';
                    }
                    else {
                      echo '<button type="button" class="btn btn-success form-control disabled">Connection to the database : SUCCESS</button>';
                    }
                  ?>                  
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary form-control" name="createorrestore" value="createorrestore">Create/Restore the database</button>               
                </div>
              </form>       
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>