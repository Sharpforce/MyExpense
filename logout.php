<?php
  //if(isset($_SESSION['username'])) {
    session_start();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
    unset($_SESSION);
    session_destroy();
    session_write_close();

    header('Location: /');
    die;
  //}
?>