#!/usr/bin/python
# Script for Administrator (login, visit admin.php)
import time
import socket
import fcntl
import struct
import os
from selenium import webdriver

time.sleep(10)
def get_ip_address(ifname):
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    return socket.inet_ntoa(fcntl.ioctl(s.fileno(),0x8915,struct.pack('256s', ifname[:15]))[20:24])

driver = webdriver.PhantomJS(service_log_path=os.path.devnull)
host = "http://" + get_ip_address('enp0s3') + "/"
login = "login.php"
index = "index.php"
admin = "admin/admin.php"

logged_pattern = "Last messages"
time.sleep(60)

while 1:
    driver.get(host + index)
    html_source = driver.page_source

    if html_source.find(logged_pattern) == -1:
        driver.get(host + login)
        driver.find_element_by_id('username').send_keys("rmasson")
        driver.find_element_by_id('password').send_keys("tdg33vhe")
        driver.find_element_by_name("login").click()

    driver.get(host + admin)
    time.sleep(30)

driver.quit()
