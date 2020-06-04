<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/global.inc.php";
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/site.inc.php";
  connectToDatabase();

  if(!isset($_SESSION['role'])) {
    unauthorized();
  }

  if($_SESSION['role'] !== MANAGER_ROLE && $_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
    forbidden();
  }

  if(isset($_GET['id'])) {
    $site_info = retrieveSiteById($_GET['id']);
    $collabs_info = retrieveUsersBySite($_GET['id']);
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
              <?php
                foreach ($site_info as $row) {
                  echo '<h3 class="panel-title">' . $row['city'] . ' (' . $row['address'] . ')</h3>';
                }
              ?>
            </div>
            <div class="panel-body">
              <table class="table table-bordered table-striped table-condensed">
                <thead>
                  <tr>
                    <th class="text-center">Firstname</th>
                    <th class="text-center">Lastname</th>
                    <th class="text-center">Email address</th>
                    <th class="text-center">Role</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($collabs_info as $row) {
                  ?>
                    <tr>
                      <?php
                        echo '<td class="text-center col-md-2">' . htmlspecialchars($row['firstname']) . '</td>';
                        echo '<td class="text-center col-md-2">' . htmlspecialchars($row['lastname']) . '</td>';
                        echo '<td class="text-center col-md-2">' . htmlspecialchars($row['mail']) . '</td>';
                        echo '<td class="text-center col-md-2">' . htmlspecialchars($row['role']) . '</td>';
                      ?>
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