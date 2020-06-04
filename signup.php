<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/global.inc.php";
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/signup.inc.php";
  connectToDatabase();
  
  if(isset($_SESSION['role'])) {
    header('Location: /index.php');
    exit();
  }

  if(isset($_POST['signup']) && $_POST['signup'] === "signup") {
    signUp($_POST['username'], $_POST['password'], $_POST['confirmPassword'], $_POST['site'], $_POST['email'], $_POST['firstname'], $_POST['lastname']);
  }

  if(!isset($_SESSION['user_error'])) {
    $_SESSION['user_error'] = "Sorry, the application is for internal use only. If you are a new collaborator but your account is inactive, please contact your manager or the Futura Business Informatique Manager Team.";
  }
  
  $sites = retrieveSites();
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
              <h3 class="panel-title">Create an account</h3>
            </div>
            <div class="panel-body">
              <form class="col-md-4 col-md-offset-4" action="/signup.php" id="signup" method="POST">
                <div class="form-group">
                  <label for="username">Username : </label>
                  <?php
                    if(isset($_POST['username']) && !empty($_POST['username'])) {
                      $username = htmlspecialchars($_POST['username']);
                      echo '<input id="username" type="text" name="username" class="form-control" value="' . $username . '">';
                    }
                    else {
                      echo '<input id="username" type="text" name="username" class="form-control">';
                    }
                  ?>
                </div>
                <div class="form-group">
                  <label for="password">Password : </label>
                  <input id="password" type="password" name="password" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="confirmPassword">Confirm Password : </label>
                  <input id="confirmPassword" type="password" name="confirmPassword" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="site">Site :</label>
                  <select class="form-control" id="site" name="site" form="signup">
                    <?php
                      foreach ($sites as $row) {
                        if(isset($row['id']) && isset($row['city'])) {
                          echo '<option>' . htmlspecialchars($row['city']) . '</option>';
                        }
                      }
                    ?>
                  </select>
                </div> 
                <div class="form-group">
                  <label for="mail">Email address : </label>
                  <?php
                    if(isset($_POST['email']) && !empty($_POST['email'])) {
                      $email = htmlspecialchars($_POST['email']);
                      echo '<input id="mail" type="text" name="email" class="form-control" value="' . $email . '">';
                    }
                    else {
                      echo '<input id="mail" type="text" name="email" class="form-control">';
                    }
                  ?>                  
                </div>
                <div class="form-group">
                  <label for="firstname">Firstname : </label>
                  <?php
                    if(isset($_POST['firstname']) && !empty($_POST['firstname'])) {
                      $firstname = htmlspecialchars($_POST['firstname']);
                      echo '<input id="firstname" type="text" name="firstname" class="form-control" value="' . $firstname . '">';
                    }
                    else {
                      echo '<input id="firstname" type="text" name="firstname" class="form-control">';
                    }
                  ?>                  
                </div>
                <div class="form-group">
                  <label for="lastname">Lastname : </label>
                  <?php
                    if(isset($_POST['lastname']) && !empty($_POST['lastname'])) {
                      $lastname = htmlspecialchars($_POST['lastname']);
                      echo '<input id="lastname" type="text" name="lastname" class="form-control" value="' . $lastname . '">';
                    }
                    else {
                      echo '<input id="lastname" type="text" name="lastname" class="form-control">';
                    }
                  ?>                  
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="signup" value="signup" disabled>Sign up !</button> 
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>