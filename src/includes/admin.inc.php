<?php
  // Retrieve the list of users stored in the database
  function retrieveUsersList() {
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT user_id, username, firstname, lastname, mail, role, last_connection, active FROM user ORDER BY role, username"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_id = NULL;
    $out_username = NULL;
    $out_firstname = NULL;
    $out_lastname = NULL;
    $out_email = NULL;
    $out_role = NULL;
    $out_last_connection = NULL;
    $out_active = NULL;
    $users = array();

    $stmt->bind_result($out_id, $out_username, $out_firstname, $out_lastname, $out_email, $out_role, $out_last_connection, $out_active);
    while ($stmt->fetch()) {
      $users[] = array('id'=>$out_id, 'username'=>$out_username, 'firstname'=>$out_firstname, 'lastname'=>$out_lastname, 'mail'=>$out_email, 'role'=>$out_role, 'last_connection'=>$out_last_connection, 'status'=>$out_active);
    }

    $stmt->close();
    return $users;
  }

  // Allow an admin to enable/disable a specific user (except himself)
  function updateStatus($idParam, $statusParam) {
    if(!isset($_SESSION['username'])) {
      unauthorized();
    }
    
    if(!isset($_SESSION['role']) || $_SESSION['role'] !== ADMIN_ROLE) {
      forbidden();
    }

    if(isset($_SESSION['role']) === ADMIN_ROLE) {
      ipAddrFilter();
    }

    if(!isset($idParam) || empty($idParam) || !isset($statusParam) || empty($statusParam) || !is_numeric($idParam) || strcmp($idParam, $_SESSION['id']) === 0) {
      $_SESSION['user_error'] = "Sorry, something goes wrong.";
      return;
    }
    
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }    

    $id = $idParam;
    $id = intval($id);
    $status = $statusParam;

    if(strcmp($status, "inactive") === 0) {
      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE user SET active = " . INACTIVE . " WHERE user_id = ?"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if (!$stmt->bind_param("i", $id)) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if (!$stmt->execute()) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      $_SESSION['message'] = "The user is disabled successfully !";
      $stmt->close();
    }

    if(strcmp($status, "active") === 0) {
      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE user SET active = " . ACTIVE . " WHERE user_id = ?"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if (!$stmt->bind_param("i", $id)) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if (!$stmt->execute()) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }
      $_SESSION['message'] = "The user is enabled successfully !";
      $stmt->close();
    }
  }

  // Save info for two steps action like delete
  function saveDeleteUser($idParam, $actionParam) {
    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== ADMIN_ROLE) {
      forbidden();
    }

    if(isset($_SESSION['role']) === ADMIN_ROLE) {
      ipAddrFilter();
    }

    if(!isset($idParam) || empty($idParam) || !isset($actionParam) || empty($actionParam)) {
      $_SESSION["user_error"] = "Sorry, something goes wrong.";
    }
    else {
      $_SESSION['id_action'] = $idParam;
      $_SESSION['action'] = $actionParam;
    }
  }

  // Allow an admin to delete a specific user (except himself)
  function deleteUser() {
    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== ADMIN_ROLE) {
      forbidden();
    }

    if(isset($_SESSION['role']) === ADMIN_ROLE) {
      ipAddrFilter();
    }

    if(!isset($_SESSION['id_action']) || empty($_SESSION['id_action']) || !isset($_SESSION['action']) || empty($_SESSION['action']) || !is_numeric($_SESSION['id_action']) || strcmp($_SESSION['id_action'], $_SESSION['id']) === 0) {
      $_SESSION['user_error'] = "Sorry, something goes wrong.";
      return;
    }


    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }
    
    $id = $_SESSION['id_action'];
    $id = intval($id);

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE message set user_id = NULL WHERE user_id = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->bind_param("i", $id)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("DELETE FROM user WHERE user_id = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->bind_param("i", $id)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $_SESSION['message'] = "The user is deleted successfully !";
    $stmt->close();
  }
?>