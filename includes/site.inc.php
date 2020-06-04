<?php
  function retrieveSiteById($siteId) {
    if(!isset($siteId) || empty($siteId)) {
      $_SESSION['user_error'] = "This site does not exists.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }
    
    // SQL Injection, param $siteId
    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT city, address FROM site WHERE site_id = " . $siteId . " LIMIT 1"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_site_city = NULL;
    $out_site_address = NULL;
    $site_info = array();
    
    $stmt->store_result();
    $stmt->bind_result($out_site_city, $out_site_address);
    if($stmt->num_rows >= 1) {  
      while($stmt->fetch()) {
        $site_info[] = array('city'=>$out_site_city, 'address'=>$out_site_address);
      }
    }
    else {
      $_SESSION['user_error'] = "This site does not exists.";
      $stmt->free_result();
    }   
    $stmt->close();
    return $site_info;
  }

  function retrieveUsersBySite($siteId) {
    if(!isset($siteId) || empty($siteId)) {
      $_SESSION['user_error'] = "This site does not exists.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT lastname, firstname, mail, role FROM user WHERE site_id = " . $siteId))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if (!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_lastname = NULL;
    $out_firstname = NULL;
    $out_mail = NULL;
    $out_role = NULL;
    $collaborators_info = array();

    $stmt->bind_result($out_lastname, $out_firstname, $out_mail, $out_role);
    while($stmt->fetch()) {
      $collaborators_info[] = array('lastname'=>$out_lastname, 'firstname'=>$out_firstname, 'mail'=>$out_mail, 'role'=>$out_role);
    }

    $stmt->close();
    return $collaborators_info;
  }
?>