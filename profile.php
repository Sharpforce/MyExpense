<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/global.inc.php";
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/profile.inc.php";
  connectToDatabase();

  if(!isset($_SESSION['role'])) {
    unauthorized();
  }

  if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) {
    ipAddrFilter();
  }

  if(isset($_POST['updateProfile']) && $_POST['updateProfile'] === "updateProfile") {
    updateProfile($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['csrf_token']);
  }

  if(isset($_POST['changePassword']) && $_POST['changePassword'] === "changePassword") {
    changePassword($_POST['oldPassword'], $_POST['newPassword'], $_POST['confirmPassword'], $_POST['csrf_token']);
  }

  $userInfo = retrieveUserInformation();
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
              <h3 class="panel-title">Edit your profile</h3>
            </div>
            <div class="panel-body">
              <form class="col-md-4 col-md-offset-4">
                <fieldset>
                <legend>Your professional information</legend>
                  <div class="form-group">
                    <label for="username">Username : </label>
                    <?php
                      if(isset($userInfo['username']) && !empty($userInfo['username'])) {
                        echo '<input id="role" type="text" name="role" class="form-control" value="' . htmlspecialchars($userInfo['username']) . '" disabled>';
                      }
                      else {
                        echo '<input id="role" type="text" name="role" class="form-control" disabled>';
                      }
                    ?>
                  </div>
                  <div class="form-group">
                    <label for="role">Role : </label>
                    <?php
                      if(isset($userInfo['role']) && !empty($userInfo['role'])) {
                        echo '<input id="role" type="text" name="role" class="form-control" value="' . htmlspecialchars($userInfo['role']) . '" disabled>';
                      }
                      else {
                        echo '<input id="role" type="text" name="role" class="form-control" disabled>';
                      }
                    ?>
                  </div>
                  <div class="form-group">
                    <label for="Site">Site : </label>
                    <?php
                      if(isset($userInfo['city']) && !empty($userInfo['city'])) {
                        echo '<input id="site" type="text" name="site" class="form-control" value="' . htmlspecialchars($userInfo['city']) . '" disabled>';
                      }
                      else {
                        echo '<input id="site" type="text" name="site" class="form-control" disabled>';
                      }
                    ?>
                  </div>
                  <div class="form-group">
                    <label for="manager">Manager : </label>
                    <?php
                      if(isset($userInfo['firstname_manager']) && !empty($userInfo['firstname_manager']) && isset($userInfo['lastname_manager']) && !empty($userInfo['lastname_manager'])) {
                        echo '<input id="manager" type="text" name="manager" class="form-control" value="' . htmlspecialchars($userInfo['firstname_manager'] . ' ' . $userInfo['lastname_manager']) . '" disabled>';
                      }
                      else {
                        echo '<input id="role" type="text" name="role" class="form-control" disabled>';
                      }
                    ?>
                  </div>
                </fieldset>
              </form> 
            </div>
            <div class="panel-body">
              <form class="col-md-4 col-md-offset-4" action="/profile.php" method="POST">
                <fieldset>
                  <legend>Your personnal information</legend>
                  <div class="form-group">
                    <label for="firstname">Firstname : </label>
                    <?php
                      if(isset($userInfo['firstname']) && !empty($userInfo['firstname'])) {
                        echo '<input id="firstname" type="text" name="firstname" class="form-control" value="' . htmlspecialchars($userInfo['firstname']) . '">';
                      } 
                      else {
                        echo '<input id="firstname" type="text" name="firstname" class="form-control">';
                      }
                    ?>                        
                  </div>
                  <div class="form-group">
                    <label for="lastname">Lastname : </label>
                    <?php
                      if(isset($userInfo['lastname']) && !empty($userInfo['lastname'])) {
                        echo '<input id="lastname" type="text" name="lastname" class="form-control" value="' . htmlspecialchars($userInfo['lastname']) . '">';
                      } 
                      else {
                        echo '<input id="lastname" type="text" name="lastname" class="form-control">';
                      }
                    ?>                  
                  </div>
                  <div class="form-group">
                    <label for="email">Email address : </label>
                    <?php
                      if(isset($userInfo['email']) && !empty($userInfo['email'])) {
                        echo '<input id="email" type="text" name="email" class="form-control" value="' . htmlspecialchars($userInfo['email']) . '">';
                      } 
                      else {
                        echo '<input id="email" type="text" name="email" class="form-control">';
                      }
                      echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                    ?>          
                  </div>
                  <button type="submit" class="btn btn-primary btn-block" name="updateProfile" value="updateProfile">Update profile</button>
                </fieldset>
              </form>
            </div>
            <div class="panel-body">
              <form class="col-md-4 col-md-offset-4" action="/profile.php" method="POST">
                <fieldset>
                  <legend>Change your password</legend>
                  <div class="form-group">
                    <label for="oldPassword">Old password : </label>
                    <input id="oldPassword" type="password" name="oldPassword" class="form-control" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="newPassword">New password : </label>
                    <input id="newPassword" type="password" name="newPassword" class="form-control" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="confirmPassword">Confirm password : </label>
                    <input id="confirmPassword" type="password" name="confirmPassword" class="form-control" autocomplete="off">
                  </div>
                  <?php
                    echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                  ?>
                  <button type="submit" class="btn btn-primary btn-block" name="changePassword" value="changePassword">Change password</button>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>   
  </body>
</html>