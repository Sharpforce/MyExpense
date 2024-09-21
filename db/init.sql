CREATE DATABASE IF NOT EXISTS `myexpense`;

USE `myexpense`;

GRANT SELECT, INSERT, UPDATE, DELETE ON myexpense.* TO 'MyExpenseUser'@'localhost' IDENTIFIED BY 'password';

CREATE TABLE site (
    site_id INT(4) NOT NULL AUTO_INCREMENT,
    city VARCHAR(32),
    address TEXT,
    PRIMARY KEY (site_id)
);

INSERT INTO site (site_id, city, address) VALUES
(1, 'Paris', '9 bis rue Dupond Eloise, 78000 VERSAILLES'),
(2, 'Rennes', '8 Rue des lilas, 35000 Rennes'),
(3, 'Brest', '32 rue de Siam, 29200 Brest');

CREATE TABLE user (
    user_id INT(4) NOT NULL AUTO_INCREMENT,
    username VARCHAR(32) NOT NULL,
    password VARCHAR(256),
    role VARCHAR(32),
    lastname TEXT,
    firstname TEXT,
    site_id INT(4),
    mail VARCHAR(128),
    manager_id INT(4),
    last_connection DATETIME,
    active TINYINT(1),
    PRIMARY KEY (user_id),
    UNIQUE (username),
    UNIQUE (mail),
    FOREIGN KEY (site_id) REFERENCES site(site_id)
);

INSERT INTO user (user_id, username, password, role, lastname, firstname, site_id, mail, manager_id, last_connection, active) VALUES 
(1, 'afoulon', MD5('wq6hblv3'), 'Financial approver', 'Foulon', 'Aristide', 1, 'afoulon@futuraBI.fr', 1, NOW(), 1),
(2, 'pbaudouin', MD5('HackMe'), 'Financial approver', 'Baudouin', 'Paul', 1, 'pbaudouin@futuraBI.fr', 2, NOW(), 1),
(3, 'rlefrancois', MD5('m4ukhkjq'), 'Manager', 'Lefrancois', 'Reynaud', 1, 'rlefrancois@futuraBI.fr', 1, NOW(), 1),
(4, 'mriviere', MD5('6v7j4tj2'), 'Manager', 'Riviere', 'Manon', 2, 'mriviere@futuraBI.fr', 2, NOW(), 1),
(5, 'mnguyen', MD5('27sfbdij'), 'Manager', 'Nguyen', 'Maximilien', 3, 'mnguyen@futuraBI.fr', 2, NOW(), 1),
(6, 'pgervais', MD5('b98e67ys'), 'Collaborator', 'Gervais', 'Placide', 1, 'pgervais@futuraBI.fr', 3, NOW(), 1),
(7, 'placombe', MD5('qayhned2'), 'Collaborator', 'Lacombe', 'Philibert', 1, 'placombe@futuraBI.fr', 3, NOW(), 1),
(8, 'triou', MD5('h1rlnifq'), 'Collaborator', 'Riou', 'Thierry', 1, 'triou@futuraBI.fr', 3, NOW(), 1),
(9, 'broy', MD5('37f52u21'), 'Collaborator', 'Roy', 'Baudouin', 1, 'broy@futuraBI.fr', 3, NOW(), 1),
(10, 'brenaud', MD5('u99hau4d'), 'Collaborator', 'Renaud', 'Bernadette', 2, 'brenaud@lrtechnologies.fr', 4, NOW(), 1),
(11, 'slamotte', MD5('fzghn4lw'), 'Collaborator', 'Lamotte', 'Samuel', 2, 'slamotte@futuraBI.fr', 4, NOW(), 0),
(12, 'nthomas', MD5('en3dtdjy'), 'Collaborator', 'Thomas', 'Ninette', 3, 'nthomas@futuraBI.fr', 5, NOW(), 1),
(13, 'vhoffmann', MD5('qzm8hnmw'), 'Collaborator', 'Hoffmann', 'Victorine', 3, 'vhoffmann@futuraBI.fr', 5, NOW(), 1),
(14, 'rmasson', MD5('tdg33vhe'), 'Administrator', 'Masson', 'Rodrigue', 1, 'rmasson@futuraBI.fr', 1, NOW(), 1);

CREATE TABLE message (
    message_id INT(4) NOT NULL AUTO_INCREMENT,
    message TEXT,
    post_date DATETIME,
    user_id INT(4),
    PRIMARY KEY (message_id),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

INSERT INTO message (message_id, message, post_date, user_id) VALUES 
(1, 'MyExpense application allow collaborators and managers to report their expenses in order to be reimbursed as quick as possible.', '2018-02-11 10:52:08', 2),
(2, 'Less time wasted than send excel file in a mail!', '2018-02-11 11:23:12', 5),
(3, 'How do I know if my expense report is reimbursed?', '2018-02-11 13:44:43', 12),
(4, 'The status of your expense report will be "Sent for payment".', '2018-02-11 14:01:45', 1),
(5, 'Great! Thank you.', '2018-02-11 16:34:48', 4);

CREATE TABLE expense (
    expense_id INT(4) NOT NULL AUTO_INCREMENT,
    expense_date DATE,
    amount INT(5),
    comment TEXT,
    status VARCHAR(32),
    user_id INT(4),
    order_status INT(4),
    PRIMARY KEY (expense_id),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

INSERT INTO expense (expense_id, expense_date, amount, comment, status, user_id, order_status) VALUES 
(1, '2018-02-13', 36, 'Lunch with AI Enterprise Human Ressource', 'Opened', 9, 3),
(2, '2018-02-14', 584, 'New Ipad, I left mine in the taxi going to the airport.', 'Refused', 5, 5),
(3, '2018-02-14', 321, 'Train tickets, customer in Nantes.', 'Submitted', 8, 1),
(4, '2018-02-15', 8, 'Lunch when go to the Welcome Day for new collaborators.', 'Submitted', 7, 1),
(5, '2018-02-15', 750, 'Plane tickets, Cybersecurity project n°5423545, Toulouse.', 'Opened', 11, 3),
(6, '2018-02-15', 23, 'Bus and subway tickets (Rennes).', 'Validated', 3, 2),
(7, '2018-02-16', 333, 'Price of the car rental for the training day (How to improve your soft skills).', 'Opened', 14, 3),
(8, '2018-02-17', 1200, 'No comment.', 'Sent for payment', 8, 1),
(9, '2018-02-20', 56, 'Two breakfasts. I was hungry', 'Refused', 8, 5),
(10, '2018-02-21', 553, 'A new computer.', 'Validated', 4, 2);