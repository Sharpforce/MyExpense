#!/usr/bin/python
# Script for Administrator (login, visit admin.php)
import time
from selenium import webdriver

driver = webdriver.PhantomJS()
host = "http://myexpense.fbi.com/"
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