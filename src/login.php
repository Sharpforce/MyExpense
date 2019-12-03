<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/global.inc.php";
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/login.inc.php";
  connectToDatabase();

  if(isset($_POST['login']) && $_POST['login'] === "login") {
    login($_POST['username'], $_POST['password'], $_POST['csrf_token']);
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
              <h3 class="panel-title">Log in</h3>
            </div>
            <div class="panel-body">
              <form class="col-md-4 col-md-offset-4" action="/login.php" method="POST">
                <div class="form-group">
                  <label for="username">Username : </label>
                  <input id="username" type="text" name="username" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="password">Password : </label>
                  <input id="password" type="password" name="password" class="form-control" autocomplete="off">
                </div>
                <?php
                  echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                ?>
                <button type="submit" class="btn btn-primary btn-block" name="login" value="login">Log in</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>