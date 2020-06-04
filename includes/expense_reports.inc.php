<?php
  function retrieveExpenseReports() {
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT expense_id, expense_date, amount, comment, status, user_id FROM expense WHERE user_id = ? ORDER BY order_status ASC"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->bind_param("i", $_SESSION['id'])) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_id = NULL;
    $out_expense_date = NULL;
    $out_amount = NULL;
    $out_comment = NULL;
    $out_status = NULL;
    $out_user_id = NULL;
    $expense_reports = array();

    $stmt->bind_result($out_id, $out_expense_date, $out_amount, $out_comment, $out_status, $out_user_id);
    while($stmt->fetch()) {
      $expense_reports[] = array('id'=>$out_id, 'expense_date'=>$out_expense_date, 'amount'=>$out_amount, 'comment'=>$out_comment, 'status'=>$out_status, 'user_id'=>$out_user_id);
    }
    $stmt->close();
    
    // Verify Flag
    if(isset($_SESSION['username']) && $_SESSION['username'] == 'slamotte') {
        if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
            $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
            return;
        }
        
        if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT amount, status FROM expense WHERE user_id = ?"))) {
            $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
            return;
        }
        
        // Hardcoded id
        $user_id = 11;
        if(!$stmt->bind_param("i", $user_id)) {
            $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
            return;
        }
        
        if(!$stmt->execute()) {
            $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
            return;
        }
        
        $out_amount = NULL;
        $out_status = NULL;
        $list = array();
        $stmt->bind_result($out_amount, $out_status);
        while($stmt->fetch()) {
            $list[] = array('amount'=>$out_amount, 'status'=>$out_status);
        }
        
        foreach ($list as $row) {
            if($row['amount'] === 750 && $row['status'] === SENT_FOR_PAYMENT) {
                $flag = "flag{H4CKY0URL1F3}";
                $_SESSION['message'] = "Congratz ! The flag is : " . $flag;
            }
        }
        
        $stmt->close();
    }
    return $expense_reports;
  }

  function retrieveExpenseReportsCollaborators() {
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if($_SESSION['role'] === MANAGER_ROLE) {
      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT expense.expense_id, expense.expense_date, expense.amount, expense.comment, expense.status, user.lastname, user.firstname, user.user_id FROM expense JOIN user ON expense.user_id = user.user_id WHERE user.manager_id = ? AND expense.status = ? ORDER BY expense.order_status ASC"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      $status_submitted = SUBMITTED;
      if(!$stmt->bind_param("is", $_SESSION['id'], $status_submitted)) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      } 
    }

    if($_SESSION['role'] === FINANCIAL_APPROVER_ROLE) {
      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT expense.expense_id, expense.expense_date, expense.amount, expense.comment, expense.status, user.lastname, user.firstname, user.user_id FROM expense JOIN user ON expense.user_id = user.user_id WHERE user.manager_id = ? AND (expense.status = ? OR expense.status = ?) ORDER BY expense.order_status ASC"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      $status_submitted = SUBMITTED;
      $status_validated = VALIDATED;
      if(!$stmt->bind_param("iss", $_SESSION['id'], $status_submitted, $status_validated)) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      } 
    }  

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_id = NULL;
    $out_expense_date = NULL;
    $out_amount = NULL;
    $out_comment = NULL;
    $out_status = NULL;
    $out_lastname = NULL;
    $out_firstname = NULL;
    $out_user_id = NULL;
    $expense_reports_collabs = array();
    $stmt->bind_result($out_id, $out_expense_date, $out_amount, $out_comment, $out_status, $out_lastname, $out_firstname, $out_user_id);
    while($stmt->fetch()) {
      $expense_reports_collabs[] = array('id'=>$out_id, 'expense_date'=>$out_expense_date, 'amount'=>$out_amount, 'comment'=>$out_comment, 'status'=>$out_status, 'lastname'=>$out_lastname, 'firstname'=>$out_firstname, 'user_id'=>$out_user_id);
    }

    $stmt->close();

    if($_SESSION['role'] === FINANCIAL_APPROVER_ROLE) {
      if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT user_id FROM user WHERE manager_id = ? AND role = ?"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      $role_manager = MANAGER_ROLE;
      if(!$stmt->bind_param("is", $_SESSION['id'], $role_manager)) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if(!$stmt->execute()) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      $out_manager_id = NULL;
      $stmt->bind_result($out_manager_id);
      $temp_list_manager = array();
      while($stmt->fetch()) {
        $temp_list_manager[] = array('id'=>$out_manager_id);
      }

      $stmt->close();

      foreach ($temp_list_manager as $row) {
        if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
          $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
          return;
        }

        if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT expense.expense_id, expense.expense_date, expense.amount, expense.comment, expense.status, user.lastname, user.firstname, user.user_id FROM expense JOIN user ON expense.user_id = user.user_id WHERE user.manager_id = ? AND expense.status = ? ORDER BY order_status ASC"))) {
          $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
          return;
        }

        $status_validated = VALIDATED;
        if(!$stmt->bind_param("is", $row['id'], $status_validated)) {
          $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
          return;
        }

        if(!$stmt->execute()) {
          $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
          return;
        }

        $out_id = NULL;
        $out_expense_date = NULL;
        $out_amount = NULL;
        $out_comment = NULL;
        $out_status = NULL;
        $out_lastname = NULL;
        $out_firstname = NULL;
        $out_user_id = NULL;
        $stmt->bind_result($out_id, $out_expense_date, $out_amount, $out_comment, $out_status, $out_lastname, $out_firstname, $out_user_id);
        while($stmt->fetch()) {
          $expense_reports_collabs[] = array('id'=>$out_id, 'expense_date'=>$out_expense_date, 'amount'=>$out_amount, 'comment'=>$out_comment, 'status'=>$out_status, 'lastname'=>$out_lastname, 'firstname'=>$out_firstname, 'user_id'=>$out_user_id);
        }

        $stmt->close();
      }
    }
    
    return $expense_reports_collabs;
  }

  function isSubCollaborator($userIdParam) {
    $expense_reports_collabs = retrieveExpenseReportsCollaborators();

    foreach ($expense_reports_collabs as $row) {
      if($row['user_id'] === $userIdParam) {
        return true;
      }
    }

    return false;
  } 

  function createExpense($amountParam, $commentParam, $csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== COLLABORATOR_ROLE && $_SESSION['role'] !== MANAGER_ROLE && $_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
      forbidden();
    }    

    if(!isset($amountParam) || empty($amountParam)) {
      $_SESSION['user_error'] = "An amount is required (min : 1 €, max : 99 999 €).";
      return;
    }

    if(intval($amountParam) == 0) {
      $_SESSION['user_error'] = "Amount must be between 1 € and 99 999 €.";
      return;
    }

    if(!isset($commentParam) || empty($commentParam)) {
      $_SESSION['user_error'] = "A comment to explain the reason of the expense report is required.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $amount = intval($amountParam);
    $comment = $commentParam;
    $status = OPENED;
    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("INSERT INTO expense (expense_date, amount, comment, status, user_id, order_status) VALUES (NOW(), ?, ?, ?, ?, ?)"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $order_opened = OPENED_ORDER;
    if(!$stmt->bind_param("issii", $amount, $comment, $status, $_SESSION['id'], $order_opened)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }
    else {
      $_SESSION['message'] = "Your expense report has been created.";
    }

    $stmt->close();
  }

  // Save info for two steps action like delete
  function saveActionExpenseReport($idParam, $actionParam, $csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== COLLABORATOR_ROLE && $_SESSION['role'] !== MANAGER_ROLE && $_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
      forbidden();
    }    

    if(!isset($idParam) || empty($idParam) || !isset($actionParam) || empty($actionParam)) {
      $_SESSION["user_error"] = "Sorry, something goes wrong.";
    }
    else {
      $_SESSION['id_action'] = $idParam;
      $_SESSION['action'] = $actionParam;
    }
  }

  function deleteExpense($csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== COLLABORATOR_ROLE && $_SESSION['role'] !== MANAGER_ROLE && $_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
      forbidden();
    }    

    if(!isset($_SESSION['id_action']) || empty($_SESSION['id_action']) || !isset($_SESSION['action']) || empty($_SESSION['action']) || !is_numeric($_SESSION['id_action'])) {
      $_SESSION['user_error'] = "Sorry, something goes wrong.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }    

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("DELETE FROM expense WHERE expense_id = ? AND status = ? AND user_id = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $id = $_SESSION['id_action'];
    $id = intval($id);
    $status = OPENED;
    if (!$stmt->bind_param("isi", $id, $status, $_SESSION['id'])) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if($stmt->affected_rows > 0) {
      $_SESSION['message'] = "The expense report is deleted successfully ! ";
    }
    else {
      forbidden();
    }
    
    $stmt->close();
  }

  function submitExpense($csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== COLLABORATOR_ROLE && $_SESSION['role'] !== MANAGER_ROLE && $_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
      forbidden();
    }    

    if(!isset($_SESSION['id_action']) || empty($_SESSION['id_action']) || !isset($_SESSION['action']) || empty($_SESSION['action']) || !is_numeric($_SESSION['id_action'])) {
      $_SESSION['user_error'] = "Sorry, something goes wrong.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }
    
    $id = $_SESSION['id_action'];
    $id = intval($id);

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE expense SET status = ?, order_status = ? WHERE expense_id = ? AND user_id = ? AND status = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      $stmt->free_result();
      return;
    }

    $actualStatus = OPENED;
    $newStatus = SUBMITTED;
    $order_submitted = SUBMITTED_ORDER;
    if (!$stmt->bind_param("siiis", $newStatus, $order_submitted, $id, $_SESSION['id'], $actualStatus)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if($stmt->affected_rows > 0) {
      $_SESSION['message'] = "The expense report is submitted successfully ! ";
    }
    else {
      forbidden();
    }

    $stmt->close();
  }

  function refuseExpense($csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== MANAGER_ROLE && $_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
      forbidden();
    }    

    if(!isset($_SESSION['id_action']) || empty($_SESSION['id_action']) || !isset($_SESSION['action']) || empty($_SESSION['action']) || !is_numeric($_SESSION['id_action'])) {
      $_SESSION['user_error'] = "Sorry, something goes wrong.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $id = $_SESSION['id_action'];
    $id = intval($id);

    $actualStatusSubmitted = SUBMITTED;
    $newStatus = REFUSED;
    $order_refused = REFUSED_ORDER;
    if(isset($_SESSION['role']) && $_SESSION['role'] === FINANCIAL_APPROVER_ROLE) {
      if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }    

      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT user_id FROM expense WHERE expense_id = ?"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if (!$stmt->bind_param("i", $id)) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if(!$stmt->execute()) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      $out_user_id = NULL;
      $expense_reports = array();

      $stmt->bind_result($out_user_id);
      while($stmt->fetch()) {
        $expense_reports[] = array('id'=>$out_user_id);
      }
      $stmt->close();
      $user_id = $expense_reports[0]['id'];

      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE expense SET status = ?, order_status = ? WHERE expense_id = ? AND user_id = ? AND (status = ? OR status = ?)"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        $stmt->free_result();
        return;
      }

      $actualStatusValidated = VALIDATED;
      if (!$stmt->bind_param("siiiss", $newStatus, $order_refused, $id, $user_id, $actualStatusSubmitted, $actualStatusValidated)) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }
    }

    if(isset($_SESSION['role']) && $_SESSION['role'] === MANAGER_ROLE) {
      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE expense INNER JOIN user ON expense.user_id = user.user_id SET status = ?, order_status = ? WHERE expense.expense_id = ? AND user.manager_id = ? AND expense.status = ?"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        $stmt->free_result();
        return;
      }

      if (!$stmt->bind_param("siiis", $newStatus, $order_refused, $id, $_SESSION['id'], $actualStatusSubmitted)) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if($stmt->affected_rows > 0) {
      $_SESSION['message'] = "The expense report is refused successfully ! ";
    }
    else {
      forbidden();
    }

    $stmt->close();
  }

  function validateExpense($csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== MANAGER_ROLE && $_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
      forbidden();
    }    

    if(!isset($_SESSION['id_action']) || empty($_SESSION['id_action']) || !isset($_SESSION['action']) || empty($_SESSION['action']) || !is_numeric($_SESSION['id_action'])) {
      $_SESSION['user_error'] = "Sorry, something goes wrong.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $id = $_SESSION['id_action'];
    $id = intval($id);

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE expense INNER JOIN user ON expense.user_id = user.user_id SET status = ?, order_status = ? WHERE expense.expense_id = ? AND user.manager_id = ? AND expense.status = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      $stmt->free_result();
      return;
    }

    $actualStatus = SUBMITTED;
    $newStatus = VALIDATED;
    $order_validated = VALIDATED_ORDER;
    if (!$stmt->bind_param("siiis", $newStatus, $order_validated, $id, $_SESSION['id'], $actualStatus)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if($stmt->affected_rows > 0) {
      $_SESSION['message'] = "The expense report is validated successfully ! ";
    }
    else {
      forbidden();
    }

    $stmt->close();
  }

  function sendForPaymentExpense($csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== FINANCIAL_APPROVER_ROLE) {
      forbidden();
    }    

    if(!isset($_SESSION['id_action']) || empty($_SESSION['id_action']) || !isset($_SESSION['action']) || empty($_SESSION['action']) || !is_numeric($_SESSION['id_action'])) {
      $_SESSION['user_error'] = "Sorry, something goes wrong.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }   

    $id = $_SESSION['id_action'];
    $id = intval($id);

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT user_id FROM expense WHERE expense_id = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->bind_param("i", $id)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_user_id = NULL;
    $expense_reports = array();

    $stmt->bind_result($out_user_id);
    while($stmt->fetch()) {
      $expense_reports[] = array('id'=>$out_user_id);
    }
    $stmt->close();
    $user_id = $expense_reports[0]['id'];

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE expense SET status = ?, order_status = ? WHERE expense_id = ? AND user_id = ? AND status = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      $stmt->free_result();
      return;
    }

    $actualStatus = VALIDATED;
    $newStatus = SENT_FOR_PAYMENT;
    $order_sent_for_payment = SENT_FOR_PAYMENT_ORDER;
    if (!$stmt->bind_param("siiis", $newStatus, $order_sent_for_payment, $id, $user_id, $actualStatus)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if($stmt->affected_rows > 0) {
      $_SESSION['message'] = "The expense report is sent for payment successfully ! ";
    }
    else {
      forbidden();
    }

    $stmt->close();    
  }
?>