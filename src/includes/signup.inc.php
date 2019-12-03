<?php
  // Allow anonymous to create an account
  function signUp($usernameParam, $passwordParam, $confirmPasswordParam, $siteParam, $emailParam, $fisrtnameParam, $lastnameParam) {
    if(!isset($usernameParam) || empty($usernameParam) || !isset($passwordParam) || empty($passwordParam) || !isset($confirmPasswordParam) || empty($confirmPasswordParam) || !isset($siteParam) || empty($siteParam) || !isset($emailParam) || empty($emailParam) || !isset($fisrtnameParam) || empty($fisrtnameParam) || !isset($lastnameParam) || empty($lastnameParam)) {
      $_SESSION['user_error'] = "Some required fields are missing values.";
      return;
    }

    $username = $usernameParam;
    $password = $passwordParam;
    $confirmPassword = $confirmPasswordParam;
    $site = $siteParam;
    $email = $emailParam;
    $firstname = $fisrtnameParam;
    $lastname = $lastnameParam;

    if(ctype_alnum($username) === false) {
      $_SESSION['user_error'] = "Username must contain only letters and numbers.";
      return;
    }

    if(($email = filter_var($email, FILTER_VALIDATE_EMAIL)) === false) {
      $_SESSION['user_error'] = "Email address format is not valid.";
      return;
    }

    if(strcmp($password, $confirmPassword) !== 0) {
      $_SESSION['user_error'] = "Password does not match the confirm password.";
      return;
    }

    if(strlen($password) < PASSWORD_MIN_LENGTH) {
      $_SESSION['user_error'] = "Password should be at least eight characters long.";
      return;
    }   

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }    

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT site_id FROM site WHERE city = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    } 

    if(!$stmt->bind_param("s", $site)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_site_id = NULL;
    $site_id = NULL;
    $stmt->store_result();
    $stmt->bind_result($out_site_id);
    if($stmt->num_rows == 0) {
      $_SESSION['user_error'] = "Sorry, that site does not exists.";
      $stmt->free_result();
      $stmt->close();
      return; 
    }
    else {
      while($stmt->fetch()) {
        $site_id = intval($out_site_id);   
      }
    }
    $stmt->free_result();
    $stmt->close();

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT username, mail FROM user WHERE username = ? OR mail = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->bind_param("ss", $username, $email)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_username = NULL;
    $out_email = NULL;
    $stmt->store_result();
    $stmt->bind_result($out_username, $out_email);
    if($stmt->num_rows >= 1) {
      while($stmt->fetch()) {
        if(strcmp($username, $out_username) === 0) {
          $_SESSION['user_error'] = "Sorry, that username already exists.";
          $stmt->free_result();
          $stmt->close();
          return; 
        }
        if(strcmp($email, $out_email) === 0) {
          $_SESSION['user_error'] = "Sorry, that email address already exists.";
          $stmt->free_result();
          $stmt->close();
          return; 
        }
      }
    }

    $stmt->free_result();
    $stmt->close();    

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT user_id FROM user WHERE site_id = ? AND role = 'Manager'"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->bind_param("i", $site_id)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_user_id = NULL;
    $manager_id = NULL;
    $stmt->store_result();
    $stmt->bind_result($out_user_id);
    if($stmt->num_rows >= 1) {
      while($stmt->fetch()) {
        $manager_id = intval($out_user_id);
      }
    }
    $stmt->free_result();
    $stmt->close();

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("INSERT INTO user (username, password, role, lastname, firstname, site_id, mail, manager_id, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
      $_SESSION["technical_error"] = "Sorry, a technical error has occured.";
      return;
    }

    $role = COLLABORATOR_ROLE;
    $state = INACTIVE;
    $md5Password = md5($password);
    if(!$stmt->bind_param("sssssisii", $username, $md5Password, $role, $lastname, $firstname, $site_id, $email, $manager_id, $state)) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $_SESSION['message'] = "Your account was successfully created !";
    $stmt->close();
    header('Location: /login.php');
    exit();
  }

  // Retrieve the differents site
  function retrieveSites() {
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT site_id, city FROM site"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_id = NULL;
    $out_city = NULL;
    $sites = array();

    $stmt->bind_result($out_id, $out_city);
    while($stmt->fetch()) {
      $sites[] = array('id'=>$out_id, 'city'=>$out_city);
    }

    $stmt->close();
    return $sites;
  }
?>