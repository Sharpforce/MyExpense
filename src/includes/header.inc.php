<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-header">
    <a class="navbar-brand" href="/index.php">
      <img src="/img/logo.png" alt="Logo" height="100">
    </a>
  </div>
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <li> <a href="/index.php">Home</a> </li>
      <?php
      $restrictIP = "OFF";
      if((isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE) && (isset($_SESSION['ip_addr']) && $_SESSION['ip_addr'] !== $_SERVER['REMOTE_ADDR'])) {
        $restrictIP = "ON";
      }
      if(isset($_SESSION['role']) && ($_SESSION['role'] === COLLABORATOR_ROLE || $_SESSION['role'] === MANAGER_ROLE || $_SESSION['role'] === FINANCIAL_APPROVER_ROLE)) {
        echo '<li> <a href="/expense_reports.php">Expense reports</a> </li>';
      }
      if(isset($_SESSION['role']) && $_SESSION['role'] === MANAGER_ROLE) {
        $site_list = retrieveManagerSite();
        foreach ($site_list as $row) {
          echo '<li> <a href="/site.php?id=' . $row['id'] . '">' . $row['city'] . '</a> </li>';
        }          
      }
      if(isset($_SESSION['role']) && $_SESSION['role'] === FINANCIAL_APPROVER_ROLE) {
        $site_list = retrieveSite();
        foreach ($site_list as $row) {
          echo '<li> <a href="/site.php?id=' . $row['id'] . '">' . $row['city'] . '</a> </li>';
        }          
      }
      if(isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE && $restrictIP == "OFF") {
        echo '<li> <a href="/admin/admin.php">Administration</a> </li>';
      }
      ?>
    </ul>
    <?php
      if(isset($_SESSION['username']) && $restrictIP == "OFF") {
        $sessionUsername = htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname'] . ' (' . $_SESSION['username'] . ')');
        echo '
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/profile.php"><span class="glyphicon glyphicon-user"></span>' . ' ' . $sessionUsername . '</a></li>
            <li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span>' . ' Logout</a></li>
          </ul>
        ';
      }
      else {
        echo '
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/signup.php">Don\'t have an Account ?</a></li>
            <li><a href="/login.php"><span class="glyphicon glyphicon-log-in"></span>' . ' Login</a></li>
          </ul>
        ';
      }
    ?>
  </div>
</nav>
