#!/usr/bin/python
# Script for Collab2 (login, visit index.php)
import time
from selenium import webdriver

driver = webdriver.PhantomJS()
host = "http://myexpense.fbi.com/"
login = "login.php"
index = "index.php"

logged_pattern = "Last messages"
time.sleep(30)

while 1:
    driver.get(host + index)
    html_source = driver.page_source

    if html_source.find(logged_pattern) == -1:
        driver.get(host + login)
        driver.find_element_by_id('username').send_keys("nthomas")
        driver.find_element_by_id('password').send_keys("en3dtdjy")
        driver.find_element_by_name("login").click()

    driver.get(host + index)
    time.sleep(20)

driver.quit()