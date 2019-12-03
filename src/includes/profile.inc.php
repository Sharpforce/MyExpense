<?php
  // Retrieve pesonnal information for the authenticated user and display it in the web form
  function retrieveUserInformation() {
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT user.username, user.role, user.firstname, user.lastname, user.mail, manager_id, site.city FROM user JOIN site ON user.site_id = site.site_id WHERE username = ?"))) {
      $_SESSION["technical_error"] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->bind_param("s", $_SESSION['username'])) {
      $_SESSION["technical_error"] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION["technical_error"] = "Sorry, a technical error has occured.";
      return;
    }

    $out_username = NULL;
    $out_role = NULL;
    $out_firstname = NULL;
    $out_lastname = NULL;
    $out_email = NULL;
    $out_manager_id = NULL;
    $out_city = NULL;
    $userInfo = array();

    $stmt->bind_result($out_username, $out_role, $out_firstname, $out_lastname, $out_email, $out_manager_id, $out_city);
    while ($stmt->fetch()) {
      $userInfo['username'] = $out_username;
      $userInfo['role'] = $out_role;
      $userInfo['firstname'] = $out_firstname;
      $userInfo['lastname'] = $out_lastname;
      $userInfo['email'] = $out_email;
      $userInfo['manager_id'] = $out_manager_id; 
      $userInfo['city'] = $out_city;
    }

    $stmt->close();

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT firstname, lastname FROM user WHERE user_id  = ?"))) {
      $_SESSION["technical_error"] = "Sorry, a technical error has occured.";
      return;
    }  

    if (!$stmt->bind_param("s", $userInfo['manager_id'])) {
      $_SESSION["technical_error"] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION["technical_error"] = "Sorry, a technical error has occured.";
      return;
    }
  
    $out_firstname_manager = NULL;
    $out_lastname_manager = NULL;
    $stmt->bind_result($out_firstname_manager, $out_lastname_manager);
    while ($stmt->fetch()) {
      $userInfo['firstname_manager'] = $out_firstname_manager;
      $userInfo['lastname_manager'] = $out_lastname_manager;
    } 
    return $userInfo;
  }

  // Update pesonnal information for the authenticated user (firstname, lastname, email)
  function updateProfile($fisrtnameParam, $lastnameParam, $emailParam, $csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['username'])) {
      unauthorized();
    }

    if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) {
      ipAddrFilter();
    }

    if(!isset($fisrtnameParam) || empty($fisrtnameParam) || !isset($lastnameParam) || empty($lastnameParam) || !isset($emailParam) || empty($emailParam)) {
      $_SESSION['user_error'] = "Some required fields are missing values.";
      return;
    }

    $firstname = $fisrtnameParam;
    $lastname = $lastnameParam;
    $email = $emailParam;
    if(($email = filter_var($email, FILTER_VALIDATE_EMAIL)) === false) {
      $_SESSION['user_error'] = "Email address format is not valid.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT mail FROM user WHERE mail = ? AND username != ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->bind_param("ss", $email, $_SESSION['username'])) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $stmt->store_result();
    if($stmt->num_rows >= 1) {
      $_SESSION['user_error'] = "Sorry, that email address already exists.";
      $stmt->free_result();
      $stmt->close();
      return; 
    }

    $stmt->free_result();
    $stmt->close();

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE user SET firstname = ?, lastname = ?, mail = ? WHERE username = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->bind_param("ssss", $firstname, $lastname, $email, $_SESSION['username'])) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }
    
    $_SESSION['lastname'] = $lastname;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['message'] = "Your changes have been saved successfully !";
    $stmt->close();
  }

  // Change the current password for the authenticated user
  function changePassword($oldPasswordParam, $newPasswordParam, $confirmPasswordParam, $csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['username'])) {
      unauthorized();
    }

    if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) {
      ipAddrFilter();
    }    

    if(!isset($oldPasswordParam) || empty($oldPasswordParam) || !isset($newPasswordParam) || empty($newPasswordParam) || !isset($confirmPasswordParam) || empty($confirmPasswordParam)) {
      $_SESSION['user_error'] = "Some required fields are missing values.";
      return;
    } 

    $oldPassword = $oldPasswordParam;
    $newPassword = $newPasswordParam;
    $confirmPassword = $confirmPasswordParam;
    if(strlen($newPassword) < PASSWORD_MIN_LENGTH) {
      $_SESSION['user_error'] = "Password should be at least eight characters long.";
      return;
    }  

    if(strcmp($newPassword, $confirmPassword) !== 0) {
      $_SESSION['user_error'] = "New password does not match the confirm password.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT password FROM user WHERE username = ?"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->bind_param("s", $_SESSION['username'])) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $matchPassword = false;
    $out_password = NULL;
    $stmt->store_result();
    $stmt->bind_result($out_password);
    if($stmt->num_rows === 1) {         
      while($stmt->fetch()) {
        if(md5($oldPassword) === $out_password) {
          $matchPassword = true;
        }
      }

      $stmt->free_result();
      $stmt->close();     
    }
    else {
      $_SESSION['user_error'] = "Sorry, something goes wrong.";
      $stmt->free_result();
      $stmt->close();
      return;        
    }

    if($matchPassword) {
      if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("UPDATE user SET password = ? WHERE username = ?"))) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if (!$stmt->bind_param("ss", md5($newPassword), $_SESSION['username'])) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      if (!$stmt->execute()) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
        return;
      }

      $_SESSION['message'] = "Your password has been changed successfully !";
      $stmt->close();
    }
    else {
      $_SESSION['user_error'] = "Your current password is incorrect.";
      $stmt->close();
      return;  
    }
  }
?>