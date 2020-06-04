 <?php
  // Retrieve the 25 last messages (by LIMIT 25), retrieve message body, date of the message and user who wrote it
  function retrieveMessages() {
    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("SELECT message.message_id, message.post_date, message.message, user.lastname, user.firstname, user.role, site.city FROM message JOIN user ON message.user_id = user.user_id JOIN site ON user.site_id = site.site_id UNION SELECT message_id, post_date, message, NULL, NULL, NULL, NULL FROM message WHERE user_id IS NULL ORDER BY post_date DESC LIMIT 25"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    $out_id = NULL;
    $out_lastname = NULL;
    $out_firstname = NULL;
    $out_post_date = NULL;
    $out_message = NULL;
    $out_role = NULL;
    $out_site = NULL;
    $messages = array();
    $stmt->bind_result($out_id, $out_post_date, $out_message, $out_lastname, $out_firstname, $out_role, $out_site);
    while($stmt->fetch()) {
      $messages[] = array('id'=>$out_id, 'post_date'=>$out_post_date, 'message'=>$out_message, 'lastname'=>$out_lastname, 'firstname'=>$out_firstname, 'role'=>$out_role, 'site'=>$out_site);
    }

    $stmt->close();
    return $messages;
  }

  // Allow user to post a new message
  function postMessage($messageParam, $csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) {
      ipAddrFilter();
    }

    if(!isset($messageParam) || empty($messageParam)) {
      $_SESSION['user_error'] = "A message is required.";
      return;
    }

    if(!isset($GLOBALS['___mysqli_ston']) || !is_object($GLOBALS['___mysqli_ston']) || $GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }
    
    $message = $messageParam;
    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("INSERT INTO message (message, post_date, user_id) VALUES (?, NOW(), ?)"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->bind_param("ss", $message, $_SESSION['id'])) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured.";
      return;
    }
    else {
      $_SESSION['message'] = "Your message has been posted.";
    }

    $stmt->close();
  }

  // Save info for two steps action like delete
  function saveDeleteMessage($idParam, $actionParam, $csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== FINANCIAL_APPROVER_ROLE && $_SESSION['role'] !== ADMIN_ROLE) {
      forbidden();
    }

    if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) {
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

  function deleteMessage($csrf_tokenParam) {
    if(!verifyCSRF($csrf_tokenParam)) {
      return;
    }

    if(!isset($_SESSION['role'])) {
      unauthorized();
    }

    if($_SESSION['role'] !== FINANCIAL_APPROVER_ROLE && $_SESSION['role'] !== ADMIN_ROLE) {
      forbidden();
    }

    if(isset($_SESSION['role']) === ADMIN_ROLE) {
      ipAddrFilter();
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

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("DELETE FROM message WHERE message_id = ?"))) {
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

    $_SESSION['message'] = "The message is deleted successfully !";
    $stmt->close();
  }
?>