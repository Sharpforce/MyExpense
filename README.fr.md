# MyExpense Vulnerable Web Application

__Read this in other languages:__ [English](README.md), [Français](README.fr.md)

## Détails du challenge

* **Difficulté :** Facile
* **Type :** Réaliste / Web (Pas une machine boot 2 root)
* **Technologies :** PHP / MariaDB
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

## Installation

### Machine virtuelle (VirtualBox)

Il est plus simple de récupérer l'application en téléchargeant directement la machine virtuelle au format _.vbox_ :
- [MyExpense Virtual Machine (LATEST)](https://www.mediafire.com/file/v4yugfeikmx1mpn/MyExpense_Vulnerable_Web_Application_-_1.4.ova/file)
- [MyExpense Virtual Machine (v1.0) Vulnhub Version](http://www.mediafire.com/file/mx1b7qe00y9dfzv/MyExpense_Vulnerable_Web_Application.ova/file)

> La machine est configurée en DHCP, retrouver son adresse IP fait partie du challenge (à partir de la version 1.2 l'adresse IP est affichée au démarrage de la box).

### Docker

```
# docker-compose up -d
```

L'accès à l'application doit impérativement s'effectuer par _http://myexpense-web_. Pour cela il est donc nécessaire d'ajouter l'entrée suivante dans le fichier _/etc/hosts_ de la machine hôte : 

```
127.0.0.1 myexpense-web
```

> L'adresse IP peut être adaptée selon la configuration du système.

### Installation à partir des sources

> Lors de l'installation via une machine virtuelle, la première interface réseau doit être en mode __bridge__ ou __host_only__.

#### Compatibilité

> Testée sur Virtualbox 7.0.18 / Debian 12 (bookworm) / Python3 / Google Chrome 129.0.6668.58-1

#### Installation de Git et récupération du code source

Il est possible d'installer l'utilitaire git afin de récupérer les sources de l'application :
```
# apt install git
# cd /tmp
# git clone https://github.com/Sharpforce/MyExpense.git
```

#### Exécution du script d'installation

```
# /bin/bash /tmp/MyExpense/install/install.sh
```

#### Création de la base de données

La configuration de l'application MyExpense doit être maintenant accessible via l'url _http://your-ip/config/setup.php_ :

![](https://github.com/Sharpforce/MyExpense/blob/main/img/d2a99cee077535dc955e87a1d8f8727e.png?raw=true)

Vérifier les informations puis cliquer sur **Create/Restore the database** :

![](https://github.com/Sharpforce/MyExpense/blob/main/img/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)

L'installation est maintenant **complète**, l'application est disponible à l'adresse _http://ip_

## Réinitialiser la base de données de l'application

Il est possible de restaurer la base de données de l'application afin de pouvoir recommancer à partir de l'état initial. Pour cela il faut se rendre sur l'url _http://ip/config/setup.php_ puis de cliquer sur _Create/restore the database_. Un message indiquant que l'opération a été effectuée avec succès doit apparaître :

![](https://github.com/Sharpforce/MyExpense/blob/main/img/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)