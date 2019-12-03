<?php
  $ok = @session_start();
  if(!$ok){
    session_regenerate_id(true);
    session_start();
  }
  if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = md5(mt_rand());
  }
  
  require_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
  define("PASSWORD_MIN_LENGTH", 8);
  
  define("ADMIN_ROLE", "Administrator");
  define("FINANCIAL_APPROVER_ROLE", "Financial approver"); 
  define("MANAGER_ROLE", "Manager");
  define("COLLABORATOR_ROLE", "Collaborator");
  
  define("ACTIVE", 1);
  define("INACTIVE", 0);  

  define("OPENED", "Opened");
  define("SUBMITTED", "Submitted");
  define("REFUSED", "Refused");
  define("VALIDATED", "Validated");
  define("SENT_FOR_PAYMENT", "Sent for payment");

  define("SUBMITTED_ORDER", 1);  
  define("VALIDATED_ORDER", 2);
  define("OPENED_ORDER", 3);
  define("SENT_FOR_PAYMENT_ORDER", 4);
  define("REFUSED_ORDER", 5);
  
  // Connect to database
  function connectToDatabase() {
    global $_bdd;

    if(!isset($GLOBALS['___mysqli_ston'])) {
      $GLOBALS['___mysqli_ston'] = new mysqli($_bdd['server'], $_bdd['user'], $_bdd['password'], $_bdd['database'], $_bdd['port']);
      if($GLOBALS['___mysqli_ston']->connect_errno) {
        $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      }
    }
  }

  function ipAddrFilter() {
    if(isset($_SESSION['ip_addr']) && $_SESSION['ip_addr'] !== $_SERVER['REMOTE_ADDR']) {
      $_SESSION['user_error'] = "Sorry, as an administrator, you can be authenticated only once a time.";
      header("HTTP/1.1 403 Forbidden");
      $restrictIP = "ON";
      include($_SERVER['DOCUMENT_ROOT'] . "/includes/error403.php");
      $restrictIP = "OFF";
      exit();
    }
  }

  // Verify anti-csrf token
  function verifyCSRF($csrf_tokenParam) {
    if(!isset($csrf_tokenParam) || !isset($_SESSION['csrf_token'])) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return false;
    }

    if($csrf_tokenParam !== $_SESSION['csrf_token']) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return false;
    }

    return true;
  }

  // Error when user is anonymous
  function unauthorized() {
    $_SESSION['user_error'] = "Sorry, something goes wrong.";
    header("HTTP/1.1 401 Unauthorized");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/error401.php");
    exit();
  }

  // Error when user is not administrator
  function forbidden() {
    $_SESSION['user_error'] = "Sorry, something goes wrong.";
    header("HTTP/1.1 403 Forbidden");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/error403.php");
    exit();
  }  

  function retrieveManagerSite() {
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT site.site_id, site.city, site.address FROM site JOIN user ON user.site_id = site.site_id WHERE user.user_id = ?"))) {
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

    $out_site_id = NULL;
    $out_site_city = NULL;
    $out_site_address = NULL;
    $site_info = array();

    $stmt->bind_result($out_site_id, $out_site_city, $out_site_address);
    while($stmt->fetch()) {
      $site_info[] = array('id'=>$out_site_id, 'city'=>$out_site_city, 'address'=>$out_site_address);
    }

    $stmt->close();
    return $site_info;
  }

  function retrieveSite() {
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT site_id, city, address FROM site"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_site_id = NULL;
    $out_site_city = NULL;
    $out_site_address = NULL;
    $site_info = array();

    $stmt->bind_result($out_site_id, $out_site_city, $out_site_address);
    while($stmt->fetch()) {
      $site_info[] = array('id'=>$out_site_id, 'city'=>$out_site_city, 'address'=>$out_site_address);
    }

    $stmt->close();
    return $site_info;    
  }

  // Close the MySQL connection
  function webVulnerabilitiesDatabaseCloseConnect() {
    $GLOBALS["___mysqli_ston"]->close();
  }
?>