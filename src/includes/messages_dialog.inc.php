<?php
  if(isset($_SESSION['technical_error'])) {
    echo '
        <div class="alert alert-block alert-warning">'
          . htmlspecialchars($_SESSION['technical_error']) .
        '</div>';
    unset($_SESSION['technical_error']);
  }
  if(isset($_SESSION['user_error'])) {
    echo '
        <div class="alert alert-block alert-danger">'
          . htmlspecialchars($_SESSION['user_error']) .
        '</div>';
    unset($_SESSION['user_error']);
  }
  if(isset($_SESSION['message'])) {
    echo '
        <div class="alert alert-block alert-success">'
          . htmlspecialchars($_SESSION['message']) .
        '</div>';
    unset($_SESSION['message']);
  }
  echo '
    <div class="row" style="height: 50px;">
    </div>
  ';
?>