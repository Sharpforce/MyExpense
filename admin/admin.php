<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/global.inc.php";
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/admin.inc.php";
  connectToDatabase();

  if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) {
    ipAddrFilter();
  }

  if(isset($_GET['status'])) {
    updateStatus($_GET['id'], $_GET['status']);
  }

  if(isset($_POST['deleteUser']) && $_POST['deleteUser'] === "deleteUser") {
    saveDeleteUser($_POST['id'], $_POST['deleteUser']);
  }

  if(isset($_POST['confirmDeleteUser']) && $_POST['confirmDeleteUser'] === 'yes') {
    deleteUser();
    unset($_SESSION['id_action']);
    unset($_SESSION['action']);
  }

  if(isset($_POST['confirmDeleteUser']) && $_POST['confirmDeleteUser'] !== 'yes') {
    unset($_SESSION['id_action']);
    unset($_SESSION['action']);
  }

  $users = retrieveUsersList();       
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
          <?php
            if(isset($_POST['deleteUser']) && $_POST['deleteUser'] === "deleteUser") {
          ?>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Confirm your action</h3>
                </div>
                <div class="panel-body">
                  <form class="col-md-4 col-md-offset-4" action="/admin/admin.php" method="POST">
                  	<fieldset>              
                      <legend>Are you sure to want to delete this user ?</legend>
                      <div class="form-group">
                        <button type="submit" class="btn btn-success form-control" name="confirmDeleteUser" value="yes">Yes</button>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-danger form-control" name="confirmDeleteUser" value="no">No</button>
                      </div>
                    </fieldset> 
                  </form>
                </div>
              </div>
          <?php
            }
          ?>

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Users</h3>
            </div>
            <div class="panel-body">
              <table class="table table-bordered table-striped table-condensed">
                <thead>
                  <tr>
                    <th class="text-center">Username</th>
                    <th class="text-center">Firstname</th>
                    <th class="text-center">Lastname</th>
                    <th class="text-center">Email address</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Last Connection</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($users as $row) {
                  ?>                
                      <tr>
                      <?php
                        echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                        echo '<td>' . $row['firstname'] . '</td>';
                        echo '<td>' . $row['lastname'] . '</td>';
                        echo '<td>' . htmlspecialchars($row['mail']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['role']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['last_connection']) . '</td>';
                      ?>
                        <td class="text-center">
                          <form action="/admin/admin.php" method="GET">
                          <?php
                            echo '<input type="hidden" name="id" value="' . $row['id'] . '" />';
                            if($row['status'] === ACTIVE) {
                              if(isset($_SESSION['id']) && $row['id'] === $_SESSION['id']) {
                                echo '<button type="submit" class="btn btn-success btn-xs" name="status" value="inactive" disabled>Active</button>';
                              }
                              else {
                                echo '<button type="submit" class="btn btn-success btn-xs" name="status" value="inactive">Active</button>';
                              }                              
                            }
                            else {
                              if(isset($_SESSION['id']) && $row['id'] === $_SESSION['id']) {
                                echo '<button type="submit" class="btn btn-danger btn-xs" name="status" value="active" disabled>Inactive</button>';
                              }
                              else {
                                echo '<button type="submit" class="btn btn-danger btn-xs" name="status" value="active">Inactive</button>';
                              }                              
                            }
                          ?>
                          </form>            
                        </td>
                        <td class="text-center">
                          <form action="/admin/admin.php" method="POST">
                            <?php
                              if(isset($_SESSION['id']) && $row['id'] !== $_SESSION['id']) {
                                echo '<input type="hidden" name="id" value="' . $row['id'] . '" />';
                            ?>
                                <button type="submit" class="btn btn-danger btn-xs" name="deleteUser" value="deleteUser" title="Delete User"><span class="glyphicon glyphicon-trash"></span></button>
                            <?php
                              }
                            ?>
                          </form>
                        </td>
                      </tr>
                  <?php
                    }                 
                  ?>    
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>   
  </body>
</html>