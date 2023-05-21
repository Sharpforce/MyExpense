# MyExpense Vulnerable Web Application

__Read this in other languages:__ [English](README.md), [Français](README.fr.md)

## Challenge details

* **Difficulty :** Easy
* **Type :** Realist / Web (not a boot 2 root machine)
* **Technologies :** PHP / MySQL
* **Network :** DHCP

## Description

MyExpense is a deliberately vulnerable web application that allows you to train in detecting and exploiting different web vulnerabilities. Unlike a more traditional "challenge" application (which allows you to train on a single specific vulnerability), MyExpense contains a set of vulnerabilities you need to exploit to achieve the whole scenario.

## Point of attention

As the application is deliberately vulnerable, it is not desirable to expose it on the Internet because other people than you will be able to access it. It is advisable to use a virtual machine (using for example the [VirtualBox](https://www.virtualbox.org/) software) and to restrict the **host/vulnerable machine** connectivity (Host Private Network mode).

For training purposes, it is advised not to use tools for detecting or exploiting vulnerabilities (vulnerability scanner, etc) and not to look at the application source code (*blackbox* mode).  

## Scenario

You are "Samuel Lamotte" and you have just been fired by your company "Furtura Business Informatique". 
Unfortunately because of your hasty departure, you did not have time to validate your expense report for your last business trip, which still amounts to 750 € corresponding to a return flight to your last customer. 

Fearing that your former employer may not want to reimburse you for this expense report, you decide to hack into the internal application called **"MyExpense "** to manage employee expense reports.

So you are in your car, in the company carpark and connected to the internal Wi-Fi (the key has still not been changed after your departure). The application is protected by username/password authentication and you hope that the administrator has not yet modified or deleted your access.

Your credentials were: slamotte/fzghn4lw

Once the challenge is done, the flag will be displayed on the application while being connected with your (samuel) account.

## Downloading the virtual machine

It is easier to get the application by directly downloading the virtual machine in _.vbox_ format:
- [MyExpense Virtual Machine (LATEST)](https://www.mediafire.com/file/smscycfha2qb3u1/My_Expense_Vulnerable_Web_Application_%2528Debian11%2529.ova/file)
- [MyExpense Virtual Machine (v1.0) Vulnhub Version](http://www.mediafire.com/file/mx1b7qe00y9dfzv/MyExpense_Vulnerable_Web_Application.ova/file)

> The machine is in DHCP configuration, finding its IP address is part of the challenge (from version 1.2 the IP address is displayed when the box is started).

## Reinit the application database

It is possible to restore the application database so that you can restart from the initial state. To do this, go to the url _http://ip/config/setup.php_ then click on _Create/restore the database_. A message indicating that the operation has been carried out successfully should appear:

![](https://github.com/Sharpforce/MyExpense/blob/master/img/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)

## Install from sources
> Tested on Debian 11 / Python3 / Google Chrome 111.0.5563.110

### Operating system

The installation has been tested on a Linux Debian 10 operating system.

### Packages installation

First of all it is necessary to install the Apache web server packages, PHP and MySql database:

```
# apt-get update
# apt-get install apache2 mariadb-server php php-mysql
```

Then:

```
# rm /var/www/html/index.html
```

### Installation from Git

You can install git tool to download the source files of the application or you can download the ZIP archive directly to GitHub.

#### With Git tool

```
# apt-get install git
# cd /tmp
# git clone https://github.com/Sharpforce/MyExpense.git
```

Then move the source code into the Apache directory */var/www/html/*:

```
# mv /tmp/MyExpense/* /tmp/MyExpense/.htaccess /var/www/html/
```

#### From Zip file

It may be necessary to install **unzip** in order to extract the zip file:

```
# apt-get install unzip
```

The, extract the zip file:

```
# cd /tmp
# wget https://github.com/Sharpforce/MyExpense/archive/master.zip
# unzip master.zip
# mv /tmp/MyExpense-master/* /tmp/MyExpense-master/.htaccess /var/www/html
```

### Apache2 configuration

A modification must be made in the Apache2 configuration file to enable the _.htacess_ file:

```
# vim /etc/apache2/apache2.conf
```

Change the _AllowOverride None_ line to _AllowOverride All_ in the _<Directory /var/www/>_ section:

```
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>
```

Restart Apache2 server:

```
# service apache2 restart
```

### Database configuration

```
# mysql -u root
```

Creating a new user with specific rights :

```
MariaDB [(none)]> grant all on *.* to MyExpenseUser@localhost identified by 'password';
Query OK, 0 rows affected (0.00 sec)

MariaDB [(none)]> flush privileges;
Query OK, 0 rows affected (0.00 sec)

MariaDB [(none)]> quit
Bye
```

It is now necessary to fill in this information in the configuration file of MyExpense application:

```
# vim /var/www/html/config/config.inc.php
```

Then change the connection information (if necessary):

```
  // Database Configuration
  $_bdd = array();
  $_bdd['server'] = "127.0.0.1";
  $_bdd['port'] = "3306";
  $_bdd['user'] = "MyExpenseUser";
  $_bdd['password'] = "password";
  $_bdd['database'] = "myexpense";
```

### Creation of the database

The configuration of MyExpense application should now be accessible via the url _http://your-ip/config/setup.php_ (it is possible that an error is displayed as long as the database is not created yet):

![](https://github.com/Sharpforce/MyExpense/blob/master/img/d2a99cee077535dc955e87a1d8f8727e.png?raw=true)

Verify database information the click on **Create/Restore the database**:![](https://github.com/Sharpforce/MyExpense/blob/master/img/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)

### Installation of users scripts (bots):

The application is now installed and functional. In order to complete the proposed challenge and make the experience little more immersive, it is necessary to install the employees scripts (that's simulate users).

Move the scripts present in the _/var/www/html/config_ directory to another directory, for example _/opt_:

```
# mv /var/www/html/config/login_collab1_script.py /opt
# mv /var/www/html/config/login_collab2_script.py /opt
# mv /var/www/html/config/login_manager_script.py /opt
# mv /var/www/html/config/login_admin_script.py /opt
# mv /var/www/html/config/login_scripts.sh /opt
# chmod +x /opt/login_scripts.sh
```

Scripts require several packages/components to work (Python3, Selenium, Google Chrome & Chrome Driver). First download and install the Chrome driver:

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

Then Chrome browser :

```
# wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
# dpkg -i google-chrome-stable_current_amd64.deb
# apt-get install -f
```

Then, Install Python3, Pip and Selenium:

```
# apt-get install python3-pip
# pip3 install selenium
```

Each scripts is based on the IP address of the enp0s3 interface. For this to work properly, configure the _/etc/network/interfaces_ file:

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

And if you used Virtualbox:

![](https://github.com/Sharpforce/MyExpense/blob/master/img/virtualbox-network.png?raw=true)

It is possible to execute the scripts directly and start attacking the application (by accessing the application via the web browser):

```
# python3 -W ignore /opt/login_collab1_script.py &
# python3 -W ignore /opt/login_collab2_script.py &
# python3 -W ignore /opt/login_manager_script.py &
# python3 -W ignore /opt/login_admin_script.py &
```

But it's simpler to run your scripts at boot time so that you don't have to run them every time.

Create the myexpense Debian user (put what you want as a password): 

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

Add the following lines in this file:

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

Add the scripts at system startup:

```
# systemctl enable login_scripts.service
```

Then reboot the machine:

```
# reboot
```

The installation is now **complete**, the application is available at http://your_ip_.