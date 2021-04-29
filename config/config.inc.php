<?php
  // Database Configuration
  $_bdd = array();
  $_bdd['server'] = "127.0.0.1";
  $_bdd['port'] = "3306";
  $_bdd['user'] = "MyExpenseUser";
  $_bdd['password'] = "password";
  $_bdd['database'] = "myexpense";

  function createOrRestoreDatabase() {
    global $_bdd;

    $GLOBALS['___mysqli_ston'] = new mysqli($_bdd['server'], $_bdd['user'], $_bdd['password'], "", $_bdd['port']);
    if($GLOBALS['___mysqli_ston']->connect_errno) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->connect_error;
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("DROP DATABASE IF EXISTS " . $_bdd['database']))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("CREATE DATABASE " . $_bdd['database']))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->select_db($_bdd['database']))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("CREATE TABLE site (site_id int(4) NOT NULL AUTO_INCREMENT, city varchar(32), address text, PRIMARY KEY(site_id))"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("INSERT INTO site VALUES
      ('1', 'Paris', '9 bis rue Dupond Eloise, 78000 VERSAILLES'),
      ('2', 'Rennes', '8 Rue des lilas, 35000 Rennes'),
      ('3', 'Brest', '32 rue de Siam, 29200 Brest')
      "))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("CREATE TABLE user (user_id int(4) NOT NULL AUTO_INCREMENT, username varchar(32) NOT NULL, password varchar(256), role varchar(32), lastname text, firstname text, site_id int(4), mail varchar(128), manager_id int(4), last_connection DATETIME, active tinyint(1), PRIMARY KEY(user_id), UNIQUE(username), UNIQUE(mail), FOREIGN KEY(site_id) REFERENCES site(site_id))"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("INSERT INTO user VALUES 
      (1, 'afoulon', '" . md5('wq6hblv3') . "', 'Financial approver', 'Foulon', 'Aristide', 1, 'afoulon@futuraBI.fr', 1, NOW(), 1),
      (2, 'pbaudouin', '" . md5('HackMe') . "', 'Financial approver', 'Baudouin', 'Paul', 1, 'pbaudouin@futuraBI.fr', 2, NOW(), 1),
      (3, 'rlefrancois', '" . md5('m4ukhkjq') . "', 'Manager', 'Lefrancois', 'Reynaud', 1, 'rlefrancois@futuraBI.fr', 1, NOW(), 1),
      (4, 'mriviere', '" . md5('6v7j4tj2') . "', 'Manager', 'Riviere', 'Manon', 2, 'mriviere@futuraBI.fr', 2, NOW(), 1),
      (5, 'mnguyen', '" . md5('27sfbdij') . "', 'Manager', 'Nguyen', 'Maximilien', 3, 'mnguyen@futuraBI.fr', 2, NOW(), 1),
      (6, 'pgervais', '" . md5('b98e67ys') . "', 'Collaborator', 'Gervais', 'Placide', 1, 'pgervais@futuraBI.fr', 3, NOW(), 1),
      (7, 'placombe', '" . md5('qayhned2') . "', 'Collaborator', 'Lacombe', 'Philibert', 1, 'placombe@futuraBI.fr', 3, NOW(), 1),
      (8, 'triou', '" . md5('h1rlnifq') . "', 'Collaborator', 'Riou', 'Thierry', 1, 'triou@futuraBI.fr', 3, NOW(), 1),
      (9, 'broy', '" . md5('37f52u21') . "', 'Collaborator', 'Roy', 'Baudouin', 1, 'broy@futuraBI.fr', 3, NOW(), 1),
      (10, 'brenaud', '" . md5('u99hau4d') . "', 'Collaborator', 'Renaud', 'Bernadette', 2, 'brenaud@lrtechnologies.fr', 4, NOW(), 1),
      (11, 'slamotte', '" . md5('fzghn4lw') . "', 'Collaborator', 'Lamotte', 'Samuel', 2, 'slamotte@futuraBI.fr', 4, NOW(), 0),
      (12, 'nthomas', '" . md5('en3dtdjy') . "', 'Collaborator', 'Thomas', 'Ninette', 3, 'nthomas@futuraBI.fr', 5, NOW(), 1),
      (13, 'vhoffmann', '" . md5('qzm8hnmw') . "', 'Collaborateur', 'Hoffmann', 'Victorine', 3, 'vhoffmann@futuraBI.fr', 5, NOW(), 1),
      (14, 'rmasson', '" . md5('tdg33vhe') . "', 'Administrator', 'Masson', 'Rodrigue', 1, 'rmasson@futuraBI.fr', 1, NOW(), 1)
      "))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("CREATE TABLE message (message_id int(4) NOT NULL AUTO_INCREMENT, message text, post_date DATETIME, user_id int(4), PRIMARY KEY(message_id), FOREIGN KEY(user_id) REFERENCES user(user_id))"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("INSERT INTO message VALUES 
      ('1', 'MyExpense application allow collaborators and managers to report their expenses in order to be reimburesed as quick as possible.', '2018-02-11 10:52:08', '2'),
      ('2', 'Less time wasted than send excel file in a mail!', '2018-02-11 11:23:12', '5'),
      ('3', 'How do I know if my expense report is reimbursed?', '2018-02-11 13:44:43', '12'),
      ('4', 'The status of your expense report will be \" Sent for payment\".', '2018-02-11 14:01:45', '1'),
      ('5', 'Great ! Thank you.', '2018-02-11 16:34:48', '4')
      "))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("CREATE TABLE expense (expense_id int(4) NOT NULL AUTO_INCREMENT, expense_date DATE, amount int(5), comment text, status varchar(32), user_id int(4), order_status int(4), PRIMARY KEY(expense_id), FOREIGN KEY(user_id) REFERENCES user(user_id))"))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!($stmt = $GLOBALS['___mysqli_ston']->prepare("INSERT INTO expense VALUES 
      (1, '2018-02-13', 36, 'Lunch with AI Enterprise Human Ressource', 'Opened', '9', 3),
      (2, '2018-02-14', 584, 'New Ipad, I left mine in the taxi going to the airport.', 'Refused', '5', 5),
      (3, '2018-02-14', 321, 'Train tickets, customer in Nantes.', 'Submitted', '8', 1),
      (4, '2018-02-15', 8, 'Lunch when go to the Welcome Day for new collaborators.', 'Submitted', '7', 1),
      (5, '2018-02-15', 750, 'Plane tickets, Cybersecurity project n°5423545, Toulouse.', 'Opened', '11', 3),
      (6, '2018-02-15', 23, 'Bus and subway tickets (Rennes).', 'Validated', '3', 2),
      (7, '2018-02-16', 333, 'Price of the car rental for the training day (How to improve your soft skills).', 'Opened', '14', 3),
      (8, '2018-02-17', 1200, 'No comment.', 'Sent for payment', '8', 1),
      (9, '2018-02-20', 56, 'Two breakfasts. I was hungry', 'Refused', '8', 5),
      (10, '2018-02-21', 553, 'A new computer.', 'Validated', '4', 2)
      "))) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    if(!$stmt->execute()) {
      $_SESSION['technical_error'] = "Sorry, a technical error has occured : " . $GLOBALS['___mysqli_ston']->error;
      $stmt->close();
      return;
    }

    $_SESSION['message'] = "The database has been created/restored, have fun !";
  }
?>
