<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/global.inc.php";
  require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/expense_reports.inc.php";
  connectToDatabase();

  if(!isset($_SESSION['role'])) {
    unauthorized();
  }

  if($_SESSION['role'] !== COLLABORATOR_ROLE && $_SESSION['role'] !== MANAGER_ROLE && $_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
    forbidden();
  }

  if(isset($_POST['create_expense']) && $_POST['create_expense'] === "create_expense") {
    createExpense($_POST['amount'], $_POST['comment'], $_POST['csrf_token']);
  }

  if(isset($_POST['action'])) {
    saveActionExpenseReport($_POST['id'], $_POST['action'], $_POST['csrf_token']);
  }

  if(isset($_POST['confirmActionExpenseReport']) && $_POST['confirmActionExpenseReport'] === 'yes') {
    switch ($_SESSION['action']) {
      case "deleteExpenseReport":
        deleteExpense($_POST['csrf_token']);
        break;
      case "submitExpenseReport":
        submitExpense($_POST['csrf_token']);
        break;
      case "refuseExpenseReport":
        refuseExpense($_POST['csrf_token']);
        break;
      case "validateExpenseReport":
        validateExpense($_POST['csrf_token']);
        break;
      case "sentForPaymentExpenseReport":
        sendForPaymentExpense($_POST['csrf_token']);
        break;
    }

    unset($_SESSION['id_action']);
    unset($_SESSION['action']);
  }

  if(isset($_POST['confirmActionExpenseReport']) && $_POST['confirmActionExpenseReport'] !== 'yes') {
    unset($_SESSION['id_action']);
    unset($_SESSION['action']);
  }

  $expense_reports = retrieveExpenseReports();
  if(isset($_SESSION['role']) && ($_SESSION['role'] === MANAGER_ROLE || $_SESSION['role'] === FINANCIAL_APPROVER_ROLE)) {
    $expense_reports_collabs = retrieveExpenseReportsCollaborators();
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
          <?php
            if(isset($_POST['action'])) {
          ?>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Confirm your action</h3>
                </div>
                <div class="panel-body">
                  <form class="col-md-4 col-md-offset-4" action="/expense_reports.php" method="POST">
                  <?php
                    if(isset($_POST['action'])) {
                      $action = 1;
                      switch ($_POST['action']) {
                        case "deleteExpenseReport":
                          echo '<legend>Are you sure to want to delete this expense report ?</legend>';
                          break;
                        case "submitExpenseReport":
                          echo '<legend>Are you sure to want to submit this expense report ?</legend>';
                          break;
                        case "refuseExpenseReport":
                          echo '<legend>Are you sure to want to refuse this expense report ?</legend>';
                          break;
                        case "validateExpenseReport":
                          echo '<legend>Are you sure to want to validate this expense report ?</legend>';
                          break;
                        case "sentForPaymentExpenseReport":
                          echo '<legend>Are you sure to want to send for payment this expense report ?</legend>';
                          break;
                        default:
                          $action = 0;
                      }                      
                    }
                    if($action === 1) {
                  ?>
                      <div class="form-group">
                        <button type="submit" class="btn btn-success form-control" name="confirmActionExpenseReport" value="yes">Yes</button>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-danger form-control" name="confirmActionExpenseReport" value="no">No</button>
                      </div>
                    <?php
                      echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                    ?>
                    </form>
                  <?php
                    }
                  ?>
                </div>
              </div>
          <?php
            }
          ?>

          <?php
            if($_SESSION['role'] === MANAGER_ROLE || $_SESSION['role'] === FINANCIAL_APPROVER_ROLE) {
          ?>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Collaborators Expense reports</h3>
            </div>
            <div class="panel-body">
             <table class="table table-bordered table-striped table-condensed">
                <thead>
                  <tr>
                    <th class="text-center">Date</th>
                    <th class="text-center">Collaborator's name</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Comment</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($expense_reports_collabs as $row) {
                  ?>
                  <tr>
                    <?php
                      echo '<td class="text-center col-md-2">' . htmlspecialchars($row['expense_date']) . '</td>';
                      echo '<td class="text-center col-md-2">' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) . '</td>';
                      echo '<td class="text-center col-md-2">' . htmlspecialchars($row['amount']) . ' €' . '</td>';
                      echo '<td>' . htmlspecialchars($row['comment']) . '</td>';
                      echo '<td class="text-center col-md-2">' . htmlspecialchars($row['status']) . '</td>';                      

                      if(isset($row['status']) && $row['status'] === SUBMITTED) {
                    ?>
                      <td class="text-center col-md-1" style="vertical-align: middle;">   
                        <form action="/expense_reports.php" method="POST">
                          <?php 
                            echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />';
                          ?>
                          <?php
                            echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                          ?>
                          <button type="submit" class="btn btn-danger btn-xs" name="action" value="refuseExpenseReport" title="Refuse Expense Report"><span class="glyphicon glyphicon-remove"></span></button>     
                          <button type="submit" class="btn btn-success btn-xs" name="action" value="validateExpenseReport" title="Validate Expense Report"><span class="glyphicon glyphicon-ok"></span></button>
                        </form>
                      </td>
                    <?php
                      }
                      if(isset($row['status']) && $row['status'] === VALIDATED && isset($_SESSION['role']) && $_SESSION['role'] === FINANCIAL_APPROVER_ROLE) {
                    ?>
                      <td class="text-center col-md-1" style="vertical-align: middle;">   
                        <form action="/expense_reports.php" method="POST">
                          <?php 
                            echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />';
                          ?>
                          <?php
                            echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                          ?>
                          <button type="submit" class="btn btn-danger btn-xs" name="action" value="refuseExpenseReport" title="Refuse Expense Report"><span class="glyphicon glyphicon-remove"></span></button>     
                          <button type="submit" class="btn btn-success btn-xs" name="action" value="sentForPaymentExpenseReport" title="Sent For Payment Expense Report"><span class="glyphicon glyphicon-euro"></span></button>
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
          </div>
          <?php
            }
          ?>

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">My Expense reports</h3>
            </div>
            <div class="panel-body">
             <table class="table table-bordered table-striped table-condensed">
                <thead>
                  <tr>
                    <th class="text-center">Date</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Comment</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($expense_reports as $row) {
                  ?>
                  <tr>
                    <?php
                      echo '<td class="text-center col-md-2">' . htmlspecialchars($row['expense_date']) . '</td>';
                      echo '<td class="text-center col-md-2">' . htmlspecialchars($row['amount']) . ' €' . '</td>';
                      echo '<td>' . htmlspecialchars($row['comment']) . '</td>';
                      echo '<td class="text-center col-md-2">' . htmlspecialchars($row['status']) . '</td>';

                      if(isset($row['status']) && $row['status'] === OPENED) {
                    ?>
                    
                    <td class="text-center col-md-1" style="vertical-align: middle;">
                      <form action="/expense_reports.php" method="POST">
                        <?php 
                          echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />';
                        ?>
                        <?php
                          echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                        ?>
                        <button type="submit" class="btn btn-danger btn-xs" name="action" value="deleteExpenseReport" title="Delete Exepense Report"><span class="glyphicon glyphicon-trash"></span></button>     
                        <button type="submit" class="btn btn-success btn-xs" name="action" value="submitExpenseReport" title="Submit Exepense Report"><span class="glyphicon glyphicon-ok"></span></button>
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
          </div>

          <?php
            if(isset($_SESSION['role'])) {
          ?>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">New expense report</h3>
                </div>
                <div class="panel-body">
                  <form class="form-inline text-center" action="/expense_reports.php" method="POST">
                    <div class="form-group">
                      <label for="amount">Amount (€) :</label>
                      <input id="amount" type="text" name="amount" class="form-control" size="5" placeholder="300">
                    </div>
                    <div class="form-group">
                      <label for="comment">Comment: </label>
                      <input id="comment" name="comment" class="form-control" size="30" placeholder="Séminaire du 12/06/2018">
                    </div>
                    <?php
                      echo '<input id="csrf_token" type="hidden" name="csrf_token" value=' . $_SESSION['csrf_token'] . ' autocomplete="off">';
                    ?>
                    <button type="submit" class="btn btn-primary" name="create_expense" value="create_expense">Create</button>
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