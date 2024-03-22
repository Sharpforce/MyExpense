#!/usr/bin/python3

import time
import socket
import fcntl
import struct
import os
from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException

# Script for Administrator (login, visit admin.php)
time.sleep(10)
def get_ip_address(ifname):
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    return socket.inet_ntoa(fcntl.ioctl(s.fileno(),0x8915,struct.pack('256s', bytes(ifname[:15], 'utf-8')))[20:24])

options = webdriver.ChromeOptions()
options.add_argument('--headless=new')
options.add_argument('--disable-extensions')
options.add_argument('--disable-popup-blocking')
options.set_capability('unhandledPromptBehavior', 'dismiss')
driver = webdriver.Chrome(options=options)

host = "http://" + get_ip_address('enp0s3') + "/"
login = "login.php"
index = "index.php"
admin = "admin/admin.php"

logged_pattern = "Last messages"
time.sleep(60)

while 1:
    try:
      driver.get(host + index)
      html_source = driver.page_source

      if html_source.find(logged_pattern) == -1:
          driver.get(host + login)
          driver.find_element('id', 'username').send_keys("rmasson")
          driver.find_element('id', 'password').send_keys("tdg33vhe")
          driver.find_element('name', 'login').click()

      driver.get(host + admin)
      time.sleep(30)
    except Exception:
      pass

driver.quit()
