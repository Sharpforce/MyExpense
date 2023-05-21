# MyExpense Vulnerable Web Application

__Read this in other languages:__ [English](README.md), [Français](README.fr.md)

## Détails du challenge

* **Difficulté :** Facile
* **Type :** Réaliste
* **Technologies :** PHP / MySQL
* **Réseau :** DHCP

MyExpense est une application **volontairement vulnérable** permettant de s’entraîner à détecter et à exploiter différentes vulnérabilités web. Contrairement à une application de type "challenge" plus classique (qui permet de s'entraîner sur une seule vulnérabilité précise), MyExpense contient un ensemble de vulnérabilités. 

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
- [MyExpense Virtual Machine (LATEST)](https://www.mediafire.com/file/fex3dyfbpjbbqtc/My_Expense_Vulnerable_Web_Application_-_1.2.ova/file)
- [MyExpense Virtual Machine (v1.0) Vulnhub Version](http://www.mediafire.com/file/mx1b7qe00y9dfzv/MyExpense_Vulnerable_Web_Application.ova/file)

> La machine est configurée en DHCP, retrouver son adresse IP fait partie du challenge (à partir de la version 1.2 l'adresse IP est affichée au démarrage de la box).

## Réinitialiser la base de données de l'application
Il est possible de restaurer la base de données de l'application afin de pouvoir recommancer à partir de l'état initial. Pour cela il faut se rendre sur l'url _http://ip/config/setup.php_ puis de cliquer sur _Create/restore the database_. Un message indiquant que l'opération a été effectuée avec succès doit apparaître :

![](https://github.com/Sharpforce/MyExpense/blob/master/img/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)



## Installation à partir des sources
> Testé sur Debian 11 / Python3 / Google Chrome 111.0.5563.110

### Système d'exploitation
L'installation a été testée sous un système d'exploitation Linux Debian 10.

### Installation des paquets
Dans un premier temps il est nécessaire d'installer les paquets correspondant au serveur web Apache, à PHP ainsi que la base de données MySql :

```
# apt-get update
# apt-get install apache2 mysql-server php php-mysql
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
```

Il faut ensuite déplacer le code source au sein du répertoire */var/www/html/* d'Apache :

```
# mv /tmp/MyExpense/src/* /tmp/MyExpense/src/.htaccess /var/www/html/
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
# mv /tmp/MyExpense-master/src/* /tmp/MyExpense-master/src/.htaccess /var/www/html
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
La configuration de l'application MyExpense doit être maintenant accessible via l'url _http://your-ip/config/setup.php_ (il est possible qu'une erreur s'affiche tant que la base de données n'est pas créée) :

![](https://github.com/Sharpforce/MyExpense/blob/master/img/d2a99cee077535dc955e87a1d8f8727e.png?raw=true)

Vérifier les informations puis cliquer sur **Create/Restore the database** :
![](https://github.com/Sharpforce/MyExpense/blob/master/img/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)

### Installation des scripts employés
L'application doit être maintenant installée et fonctionnelle. Afin de pouvoir compléter le challenge proposé et de rendre l'expérience un peu plus immersif, il est nécessaire d'installer les scripts de simulation d'action des employés.

Déplacer les scripts présents dans le répertoire _/var/www/html/config_ dans un autre répertoire, par exemple _/opt_ :

```
# mv /var/www/html/config/login_collab1_script.py /opt
# mv /var/www/html/config/login_collab2_script.py /opt
# mv /var/www/html/config/login_manager_script.py /opt
# mv /var/www/html/config/login_admin_script.py /opt
# mv /var/www/html/config/login_scripts.sh /opt
# chmod +x /opt/login_scripts.sh
```

Les scripts nécessitent plusieurs paquets/composants afin de fonctionner (Python3, Selenium, Google Chrome et Chrome driver). Tout d'abord installer Chrome driver :

```
# cd /tmp
# apt-get install xvfb libxi6 libgconf-2-4 unzip
# wget https://chromedriver.storage.googleapis.com/LATEST_RELEASE
# wget https://chromedriver.storage.googleapis.com/`cat LATEST_RELEASE`/chromedriver_linux64.zip
# unzip chromedriver_linux64.zip
# mv chromedriver /usr/local/bin/
# chown root:root /usr/local/bin/chromedriver
# chmod +x /usr/local/bin/chromedriver
```

Puis Google Chrome :

```
# wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
# dpkg -i google-chrome-stable_current_amd64.deb
# apt-get install -f
```

Ensuite Python3, Pip et Selenium:

```
# apt-get install python3-pip
# pip3 install selenium
```

Chacun des scripts se basent sur l'adresse IP de l'interface enp0s3. Pour que la machine fonctionne correctement, configurer le fichier _/etc/network/interfaces_ comme ceci :

```
# This file describes the network interfaces available on your system
# and how to activate them. For more information, see interfaces(5).

source /etc/network/interfaces.d/*

# The loopback network interface
auto lo
iface lo inet loopback

allow-hotplug enp0s3
iface enp0s3 inet dhcp
```

Et sous VirtualBox :

![](https://github.com/Sharpforce/MyExpense/blob/master/img/virtualbox-network.png?raw=true)

Il est possible d'exécuter les scripts directement et de commencer à attaquer l'application (en accédant à l'application via le navigateur web) :

```
# python3 -W ignore /opt/login_collab1_script.py &
# python3 -W ignore /opt/login_collab2_script.py &
# python3 -W ignore /opt/login_manager_script.py &
# python3 -W ignore /opt/login_admin_script.py &
```

Il peut être plus judicieux de lancer ses scripts au démarrage de la machine afin de ne pas avoir à les lancer à chaque fois :

Créer l'utilisateur Debian "myexpense" (le mot de passe importe peu) : 

```
# adduser myexpense
passwd: password updated successfully
Changing the user information for myexpense
Enter the new value, or press ENTER for the default
        Full Name []:
        Room Number []:
        Work Phone []:
        Home Phone []:
        Other []:
```

```
# vim /etc/systemd/system/login_scripts.service
```

Renseigner les lignes suivantes dans ce fichier :

```
[Unit]
Description=Runs users scripts for MyExpense Vuln VM
ConditionPathExists=/opt/login_scripts.sh

[Service]
Type=forking
ExecStart=/opt/login_scripts.sh
User=myexpense
Group=myexpense
Restart=on-failure


[Install]
WantedBy=multi-user.target
```

Ajouter le script au démarrage du système :

```
# systemctl enable login_scripts.service
```

Redémarrer :

```
# reboot
```

L'installation est maintenant **complète**, l'application est disponible à l'adresse _http://ip_