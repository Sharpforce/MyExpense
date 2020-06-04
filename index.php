<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/global.inc.php";
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/index.inc.php";
  connectToDatabase();

  if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) {
    ipAddrFilter();
  }

  if(isset($_POST['post_message']) && $_POST['post_message'] === "post_message") {
    postMessage($_POST['message'], $_POST['csrf_token']);
  }

  if(isset($_POST['deleteMessage']) && $_POST['deleteMessage'] === "deleteMessage") {
    saveDeleteMessage($_POST['id'], $_POST['deleteMessage'], $_POST['csrf_token']);
  }
  
  if(isset($_POST['confirmDeleteMessage']) && $_POST['confirmDeleteMessage'] === 'yes') {
    deleteMessage($_POST['csrf_token']);
  }

  if(isset($_POST['confirmDeleteMessage']) && $_POST['confirmDeleteMessage'] !== 'yes') {
    unset($_SESSION['id_action']);
    unset($_SESSION['action']);
  }

  $messages = retrieveMessages();
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
            if(isset($_POST['deleteMessage']) && $_POST['deleteMessage'] === "deleteMessage") {
          ?>
               <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Confirm your action</h3>
                </div>
                <div class="panel-body">
                  <form class="col-md-4 col-md-offset-4" action="/index.php" method="POST">  
                  	<fieldset>         
                      <legend>Are you sure to want to delete this message ?</legend>
                      <div class="form-group">
                        <button type="submit" class="btn btn-success form-control" name="confirmDeleteMessage" value="yes">Yes</button>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-danger form-control" name="confirmDeleteMessage" value="no">No</button>
                      </div>
                      <?php
                        echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                      ?>
                    </fieldset>    
                  </form>
                </div>
              </div>
          <?php
            }
          ?>

          <div class="panel panel-default">
            <?php
              if(isset($_SESSION['role']) && ($_SESSION['role'] === COLLABORATOR_ROLE || $_SESSION['role'] === MANAGER_ROLE || $_SESSION['role'] === FINANCIAL_APPROVER_ROLE || $_SESSION['role'] === ADMIN_ROLE)) {
            ?>
                <div class="panel-heading">
                  <h3 class="panel-title">Last messages</h3>
                </div>
                <div class="panel-body">
                  <table class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th class="text-center">Initiated By / Date</th>
                        <th class="text-center">Message</th>
                        <?php
                          if(isset($_SESSION['role']) && ($_SESSION['role'] === FINANCIAL_APPROVER_ROLE || $_SESSION['role'] === ADMIN_ROLE)) {
                        ?>
                            <th class="text-center">Action</th>
                        <?php
                          }
                        ?>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      foreach ($messages as $row) {
                    ?>
                      <tr>
                      <?php
                        if(!isset($row['lastname']) && !isset($row['firstname'])) {
                          echo '<td class="text-center col-md-2">' . 'Deleted User' . '<br />' . htmlspecialchars($row['post_date']) . '</td>';
                        }
                        else {
                            echo '<td class="text-center col-md-2">' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) . ' (' . htmlspecialchars($row['site']) . ')' . '<br />' . htmlspecialchars($row['role']) . '<br />' . htmlspecialchars($row['post_date']) . '</td>';
                        }
                        echo '<td>' . $row['message'] . '</td>';
                        if(isset($_SESSION['role']) && ($_SESSION['role'] === FINANCIAL_APPROVER_ROLE || $_SESSION['role'] === ADMIN_ROLE)) {
                      ?>
                          <td class="text-center col-md-1" style="vertical-align: middle;">
                            <form action="/index.php" method="POST">
                              <?php 
                                echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />';
                                echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                                echo '<button type="submit" class="btn btn-danger btn-xs" name="deleteMessage" value="deleteMessage" alt="Delete Message" title="Delete Message"><span class="glyphicon glyphicon-trash"></span></button>';
                              ?>
                          </form>
                          </td>
                      <?php
                        }                        
                      ?>                    
                      </tr>
                    <?php
                      }                    
                    ?>
                    </tbody>
                  </table>
                </div>
            <?php
              }
              else {
            ?>
                <div class="panel-heading">
                  <h3 class="panel-title">Welcome to MyExpense Application</h3>
                </div>
                <div class="panel-body">
                  <p>MyExpense application allow collaborators and managers to report their expenses in order to be reimburesed as quick as possible.</p>
                  <p>Collaborator : your manager, then the financial approver have to validate your request.</p>
                  <p>Manager : the financial approver has to validate your request.</p>
                  <p>To report an expense you have to login first. If you have meet any issue with the application or a request, please use the internal messaging system.</p> 
                </div>
            <?php
              }
            ?>
          </div>  

          <?php
            if(isset($_SESSION['role']) && ($_SESSION['role'] === COLLABORATOR_ROLE || $_SESSION['role'] === MANAGER_ROLE || $_SESSION['role'] === FINANCIAL_APPROVER_ROLE || $_SESSION['role'] === ADMIN_ROLE)) {
          ?>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Post a new message</h3>
                </div>
                <div class="panel-body">
                  <form class="col-md-6 col-md-offset-3" action="/index.php" method="POST">
                    <div class="form-group">
                      <label for="textarea">Your message : </label>
                      <textarea id="textarea" class="form-control" name="message"></textarea>
                    </div>
                    <?php
                      echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                    ?>
                    <button type="submit" class="btn btn-primary btn-block" name="post_message" value="post_message">Post your message</button>
                  </form>
                </div>
              </div>
          <?php
            }
          ?>        
        </div>
      </div>
    </div>
  </body>
</html>