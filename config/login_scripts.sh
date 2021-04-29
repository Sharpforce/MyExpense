#!/bin/sh -e

export OPENSSL_CONF=/etc/ssl/
/usr/bin/python /opt/login_collab1_script.py > /dev/null 2>&1 &
/usr/bin/python /opt/login_collab2_script.py > /dev/null 2>&1 &
/usr/bin/python /opt/login_manager_script.py > /dev/null 2>&1 &
/usr/bin/python /opt/login_admin_script.py > /dev/null 2>&1 &
