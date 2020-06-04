<?php
  // Login function, random sleep against brute force, update last_connection
  function login($usernameParam, $passwordParam, $csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) {
      ipAddrFilter();
    }

    sleep(rand(1,3));

    if(!isset($usernameParam) || empty($usernameParam) || !isset($passwordParam) || empty($passwordParam)) {
      $_SESSION['user_error'] = "Incorrect username or password.";
      return;
    } 

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $username = $usernameParam;
    $username = strtolower($username);
    $passwordMD5 = md5($passwordParam);
    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT user_id, username, role, lastname, firstname, active FROM user WHERE username = ? AND password = ? LIMIT 1"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->bind_param("ss", $username, $passwordMD5)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_id = NULL;
    $out_username = NULL;
    $out_role = NULL;
    $out_lastname = NULL;
    $out_firstname = NULL;
    $out_active = NULL;
    $stmt->store_result();
    $stmt->bind_result($out_id, $out_username, $out_role, $out_lastname, $out_firstname, $out_active);

    if($stmt->num_rows === 1) {         
      while($stmt->fetch()) {
        if($out_active !== INACTIVE) {             
          $_SESSION['id'] = $out_id;
          $_SESSION['username'] = $out_username;
          $_SESSION['role'] = $out_role;
          $_SESSION['lastname'] = $out_lastname;
          $_SESSION['firstname'] = $out_firstname;
          $_SESSION['csrf_token'] = md5(mt_rand());
          $_SESSION['ip_addr'] = $_SERVER['REMOTE_ADDR'];

          session_regenerate_id(true);      
          if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE user SET last_connection = NOW() WHERE username = ?"))) {
            $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
            $stmt->free_result();
            return;
          }

          if(!$stmt->bind_param("s", $_SESSION['username'])) {
            $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
            $stmt->free_result();
            return;
          }

          if(!$stmt->execute()) {
            $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
            $stmt->free_result();
            return;
          }

          $stmt->free_result();
          $stmt->close();
          header('Location: /index.php');
          exit();
        }
        else {
          $_SESSION['user_error'] = "Your account has been locked or is inactive. Please contact the administrator team.";
          $stmt->free_result();
          $stmt->close();
          return;
        }
      }     
    } 
    else {
      $_SESSION['user_error'] = "Incorrect username or password.";
      $stmt->free_result();
      $stmt->close(); 
    }      
  }
?>