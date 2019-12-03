# MyExpense Vulnerable Web Application

## Détails du challenge
* **Difficulté :** Easy
* **Type :** Réaliste
* **Technologies :** PHP / MySQL

MyExpense est une application **volontairement vulnérable** permettant de s’entraîner à détecter et à exploiter différentes vulnérabilités web. Contrairement à une application de type "challenge" plus classique (qui permet de s'entrainer sur une seule vulnérabilité précise), MyExpense contient un ensemble de vulnérabilités. 

Le second objectif de cette application est d'immerger l'attaquant dans un environnement professionnel. Pour cela, un effort a été fourni afin que l'application ressemble le plus possible à un outil destiné à une utilisation interne par les employés de l'entreprise "Futura Business Informatique". De plus, il est possible (et nécessaire afin de récupérer le flag/drapeau validant le challenge) de lancer des scripts permettant de simuler l'utilisation de l'application par des employés afin d'exploiter certaines vulnérabilités ayant pour victime les utilisateurs authentifiés.

## Point d'attention
L'application étant volontairement vulnérable il n'est pas souhaitable de l'exposer sur le réseau Internet car d'autres personnes que vous pourront y y accéder. Il est conseillé d'utiliser une machine virtuelle (en utilisant par exemple le logiciel [VirtualBox](https://www.virtualbox.org/)) et de restreindre la connectivité **hôte/machine vulnérable** (mode Réseau privé hôte).

A des fins d’entraînement, il est conseillé de ne pas utiliser d'outils de détection ou d'exploitation de vulnérabilités (scanner de vulnérabilité, etc) et de ne pas regarder le code source de l'application (mode *blackbox*).  

## Scénario
Afin de simuler une attaque ayant un but et une motivation réelle, voici un scénario menant à la validation du challenge :
```
Vous vous nommez "Samuel Lamotte" et vous venez de vous faire licencier par votre entreprise "Furtura Business Informatique". 
Malheureusement à cause de votre départ précipité, vous n'avez pas eu le temps de valider votre note de frais de votre dernier déplacement professionnel, note s'élevant quand même à 750 € correspondant à un aller retour en avion pour une mission chez votre dernier client. 
Par peur que votre ex-employeur ne veuille vous rembourser cette note de frais sur simple demande, vous avez décidé de pirater l'application interne nommé **"MyExpense"** destinée à gérer les remboursements des notes de frais des employés.

Vous êtes donc dans votre voiture, sur le parking de l'entreprise voisine et êtes connecté au Wi-Fi interne de l'entreprise (la clé n'a toujours pas été changée après votre départ).
L'application est protégée par une authentification de type nom d'utilisateur/mot de passe et vous espérez que l'administrateur n'a pas encore modifié ou supprimé votre accès.

Vos identifiants étaient : slamotte/fzghn4lw

Une fois le challenge réussi, le flag sera affiché sur l'application en étant connecté avec votre compte.

A vos claviers !
```
## Téléchargement de la machine virtuelle
Il est plus simple de récupérer l'application en téléchargeant directement la machine virtuelle au format _.vbox_ :
[MyExpense Virtual Machine](http://www.mediafire.com/file/mjctimo9y0nsuqf/MyExpense_Vunerable_Web_Application.ova/file)



## Réinitialiser la base de données de l'application
Il est possible de restaurer la base de données de l'application afin de pouvoir recommancer à partir de l'état initial. Pour cela il faut se rendre sur l'url _http://ip/config/setup.php_ puis de cliquer sur _Create/restore the database_. Un message indiquant que l'opération a été effectuée avec succès doit apparaitre :

![](https://github.com/Sharpforce/MyExpense/blob/master/images/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)



## Installation à partir des sources

### Système d'exploitation
L'installation a été testée sous un système d'exploitation Linux Debian9.

### Installation des paquets
Dans un premier temps il est nécessaire d'installer les paquets correspondant au serveur web Apache, à PHP ainsi qu'a la base de données MySql (qui sera MariaDB pour une Debian9) :
```
# apt-get install apache2 mysql-server php7.0 php7.0-mysql
```

Puis :
```
# rm /var/www/html/index.html
```

### Installation de git et récupération du code source
Il est possible d'installer l'utilitaire git afin de récupérer les sources de l'application ou alors de télécharger directement sur GitHub l'archive au format ZIP.

#### Par l'utilitaire Git
```
# apt-get install git
# cd /tmp
# git clone https://github.com/Sharpforce/MyExpense.git
# rm -rf /tmp/Web-Security/.git
```

Il faut ensuite déplacer le code source au sein du répertoire */var/www/html/* d'Apache :
```
# mv /tmp/Web-Security/* /var/www/html/
```

#### Par l'archive Zip
Il sera peut être nécessaire d'installer le paquet **unzip** afin d'extraire l'archive :
```
# apt-get install unzip
```

Puis extraire l'archive :
```
# cd /tmp
# wget https://github.com/Sharpforce/MyExpense/archive/master.zip
# unzip master.zip
# mv master/* /var/www/html
# mv master/.htaccess /var/www/html/.htaccess
```

### Configuration Apache2
Une modification doit être effectuée au sein du fichier de configuration d'Apache2 afin de prendre en compte le fichier _.htacess_ :
```
# vim /etc/apache2/apache2.conf
```

Modifier la ligne _AllowOverride None_ par _AllowOverride All_ dans la partie _<Directory /var/www/>_ :
```
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>
```

Puis redémarrer le service Apache2 :
```
# service apache2 restart
```

### Configuration de la base de données
```
# mysql -u root
```

Création d'un nouvel utilisateur et attribution des droits :
```
MariaDB [(none)]> grant all on *.* to MyExpenseUser@localhost identified by 'password';
Query OK, 0 rows affected (0.00 sec)

MariaDB [(none)]> flush privileges;
Query OK, 0 rows affected (0.00 sec)

MariaDB [(none)]> quit
Bye
```

Il faut maintenant renseigner ces informations au niveau du fichier de configuration de l'application :
```
# vim /var/www/html/config/config.inc.php
```

Puis modifier les informations de connexion :
```
  // Database Configuration
  $_bdd = array();
  $_bdd['server'] = "127.0.0.1";
  $_bdd['port'] = "3306";
  $_bdd['user'] = "MyExpenseUser";
  $_bdd['password'] = "password";
  $_bdd['database'] = "myexpense";
```

### Création de la base de données
La configuration de l'application MyExpense doit être maintenant accessible via l'url _http://<ip>/config/setup.php_ :

![](https://github.com/Sharpforce/MyExpense/blob/master/images/d2a99cee077535dc955e87a1d8f8727e.png?raw=true)

Vérifier les informations puis cliquer sur **Create/Restore the database** :
![](https://github.com/Sharpforce/MyExpense/blob/master/images/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)

### Installation des scripts employés
L'application doit être maintenant installée et fonctionnelle. Afin de pouvoir compléter le challenge proposé et de rendre l'expérience un peu plus immersif, il est nécessaire d'installer les scripts de simulation d'action des employés.

Déplacer les scripts présents dans le répertoire _/var/www/html/config_ dans un autre répertoire, par exemple _/opt_ :
```
# mv /var/www/html/config/login_collab1_script.py /opt
# mv /var/www/html/config/login_collab2_script.py /opt
# mv /var/www/html/config/login_manager_script.py /opt
# mv /var/www/html/config/login_admin_script.py /opt
```

Les scripts nécessitent plusieurs paquets/composants afin de fonctionner (Python, Selenium Webdriver ainsi que PhantomJS). Tout d'abord installer Python :
```
# apt-get install python python-pip libfontconfig
```

Puis Selenium :
```
# pip install selenium
```

Enfin PhantomJS :
```
# wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2
# tar xvjf phantomjs-2.1.1-linux-x86_64.tar.bz2
# cp phantomjs-2.1.1-linux-x86_64/bin/phantomjs /usr/bin/
```

Il est possible d'exécuter les scripts directement et de commencer à attaquer l'application (en accédant à l'application via le navigateur web) :
```
# python -W ignore /opt/login_collab1_script.py &
# python -W ignore /opt/login_collab2_script.py &
# python -W ignore /opt/login_manager_script.py &
# python -W ignore /opt/login_admin_script.py &
```

Il peut être plus judicieux de lancer ses scripts au démarrage de la machine afin de ne pas avoir à les lancer à chaque fois :
```
# vim /etc/systemd/system/rc-local.service
```

Renseigner ceci dans le fichier :
```
[Unit]
Description=/etc/rc.local Compatibility
ConditionPathExists=/etc/rc.local

[Service]
Type=forking
ExecStart=/etc/rc.local start
TimeoutSec=0
StandardOutput=tty
RemainAfterExit=yes
SysVStartPriority=99

[Install]
WantedBy=multi-user.target
```

Créer le fichier _rc.local_ :
```
# vim /etc/rc.local
```

Renseigner ceci :
```
#!/bin/sh -e
#
# rc.local
#
# This script is executed at the end of each multiuser runlevel.
# Make sure that the script will "exit 0" on success or any other
# value on error.
#
# In order to enable or disable this script just change the execution
# bits.
#
# By default this script does nothing.

/usr/bin/python /opt/login_collab1_script.py > /dev/null 2>&1 &
/usr/bin/python /opt/login_collab2_script.py > /dev/null 2>&1 &
/usr/bin/python /opt/login_manager_script.py > /dev/null 2>&1 &
/usr/bin/python /opt/login_admin_script.py > /dev/null 2>&1 &

exit 0
```

Ajouter le script au démarrage du système :
```
# chmod +x /etc/rc.local
# systemctl enable rc-local
```

Redémarrer :
```
# reboot
```

L'installation est maintenant **complète**, l'application est disponible à l'adresse _http://<IP>
