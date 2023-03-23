#!/bin/sh -e

/usr/bin/python3 /opt/login_collab1_script.py > /dev/null 2>&1 &
/usr/bin/python3 /opt/login_collab2_script.py > /dev/null 2>&1 &
/usr/bin/python3 /opt/login_manager_script.py > /dev/null 2>&1 &
/usr/bin/python3 /opt/login_admin_script.py > /dev/null 2>&1 &