# MyExpense Vulnerable Web Application

__Read this in other languages:__ [English](README.md), [Français](README.fr.md)

## Challenge details

* **Difficulty :** Easy
* **Type :** Realist / Web (not a boot 2 root machine)
* **Technologies :** PHP / MariaDB
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

## Installation

### Virtual machine (VirtualBox)

It is easier to get the application by directly downloading the virtual machine in _.vbox_ format:
- [MyExpense Virtual Machine (LATEST)](https://www.mediafire.com/file/v4yugfeikmx1mpn/MyExpense_Vulnerable_Web_Application_-_1.4.ova/file)
- [MyExpense Virtual Machine (v1.0) Vulnhub Version](http://www.mediafire.com/file/mx1b7qe00y9dfzv/MyExpense_Vulnerable_Web_Application.ova/file)

> The machine is in DHCP configuration, finding its IP address is part of the challenge (from version 1.2 the IP address is displayed when the box is started).

### Docker

```
# docker-compose up -d
```

Access to the application must be done via _http://myexpense-web_. Therefore, it is necessary to add the following entry to the _/etc/hosts_ file of the host machine:

```
127.0.0.1 myexpense-web
```

> The IP address may be adjusted according to the system configuration.

### Installation from source

> During installation via a virtual machine, the first network interface must be set to either bridge or host_only mode.

#### Compatibility

> Tested on Virtualbox 7.0.18 / Debian 12 (bookworm) / Python3 / Google Chrome 129.0.6668.58-1

#### Installing Git and fetching the source code

You can install git tool to download the source files of the application:
```
# apt-get install git
# cd /tmp
# git clone https://github.com/Sharpforce/MyExpense.git
```

#### Running the installation script

```
# /bin/bash /tmp/MyExpense/install/install.sh
```

#### Database creation

The configuration of MyExpense application should now be accessible via the url _http://your-ip/config/setup.php_ (it is possible that an error is displayed as long as the database is not created yet):

![](https://github.com/Sharpforce/MyExpense/blob/main/img/d2a99cee077535dc955e87a1d8f8727e.png?raw=true)

Verify database information the click on **Create/Restore the database**:

![](https://github.com/Sharpforce/MyExpense/blob/main/img/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)

The installation is now **complete**, the application is available at http://your_ip_.

## Reinit the application database

It is possible to restore the application database so that you can restart from the initial state. To do this, go to the url _http://ip/config/setup.php_ then click on _Create/restore the database_. A message indicating that the operation has been carried out successfully should appear:

![](https://github.com/Sharpforce/MyExpense/blob/main/img/4ae8ad29aadb188f855b952e1e21f588.png?raw=true)