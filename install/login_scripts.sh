#!/bin/bash

/usr/bin/python3 /opt/myexpense_scripts/login_collab1_script.py > /dev/null 2>&1 &
/usr/bin/python3 /opt/myexpense_scripts/login_collab2_script.py > /dev/null 2>&1 &
/usr/bin/python3 /opt/myexpense_scripts/login_manager_script.py > /dev/null 2>&1 &
/usr/bin/python3 /opt/myexpense_scripts/login_admin_script.py > /dev/null 2>&1 &