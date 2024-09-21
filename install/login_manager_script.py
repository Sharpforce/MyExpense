#!/usr/bin/python3

import time
import logging
import socket
import fcntl
import struct
import os
from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException

# Retrieve ip from network interface name
def get_ip_address(ifname):
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    return socket.inet_ntoa(fcntl.ioctl(s.fileno(),0x8915,struct.pack('256s', bytes(ifname[:15], 'utf-8')))[20:24])

# Logging
logging.basicConfig(filename='/opt/myexpense_scripts/login_manager_script.log',
                    filemode='w',
                    level=logging.INFO,
                    format='%(asctime)s - %(levelname)s - %(message)s')

logging.info("Starting script execution ...")

# Waiting a few seconds to ensure that the server is properly started.
time.sleep(30)

# Selenium / Chrome options
options = webdriver.ChromeOptions()
options.add_argument('--headless=new')
options.add_argument('--disable-extensions')
options.add_argument('--disable-popup-blocking')
options.add_argument('--disable-dev-shm-usage')
options.set_capability('unhandledPromptBehavior', 'dismiss')
driver = webdriver.Chrome(options=options)

# Get domain name / IP to visit from ENV or enp0s3
app_url = os.getenv('APP_URL')
if app_url is None:
    ip_address = get_ip_address('enp0s3')
    app_url = f'http://{ip_address}/'
else:
    app_url = f'http://{app_url}/'

# Paths for manager
host = app_url
login = "login.php"
index = "index.php"

# Pattern searched to verify if authenticated
logged_pattern = "Last messages"

# Main loop
while 1:
    try:
        # Check if manager is logged in
        driver.get(host + index)
        html_source = driver.page_source

        # If not, then attempts to authenticate
        if html_source.find(logged_pattern) == -1:
            driver.get(host + login)
            driver.find_element('id', 'username').send_keys("mriviere")
            driver.find_element('id', 'password').send_keys("6v7j4tj2")
            driver.find_element('name', 'login').click()

        # Visit index page every 30 seconds
        driver.get(host + index)
        time.sleep(30)
    except Exception as e:
        logging.error("Exception : " + str(e))
        pass

driver.quit()
logging.info("Script execution ends.")
