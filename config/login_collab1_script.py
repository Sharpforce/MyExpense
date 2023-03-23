#!/usr/bin/python3

import time
import socket
import fcntl
import struct
import os
from selenium import webdriver

# Script for Collab1 (login, visit index.php)
time.sleep(10)
def get_ip_address(ifname):
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    return socket.inet_ntoa(fcntl.ioctl(s.fileno(),0x8915,struct.pack('256s', bytes(ifname[:15], 'utf-8')))[20:24])

options = webdriver.ChromeOptions()
options.add_argument('--headless=new')
driver = webdriver.Chrome(options=options)

host = "http://" + get_ip_address('enp0s3') + "/"
login = "login.php"
index = "index.php"

logged_pattern = "Last messages"
time.sleep(30)

while 1:
    driver.get(host + index)
    html_source = driver.page_source

    if html_source.find(logged_pattern) == -1:
        driver.get(host + login)
        driver.find_element('id', 'username').send_keys("pgervais")
        driver.find_element('id', 'password').send_keys("b98e67ys")
        driver.find_element('name', 'login').click()

    driver.get(host + index)
    time.sleep(20)

driver.quit()