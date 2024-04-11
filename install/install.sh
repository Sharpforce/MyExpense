#!/bin/bash

echo "Starting installation process..."

# Update package lists.
echo "Updating package lists..."
apt update

# Install Apache2 web server.
echo "Installing Apache2 web server..."
apt install -y apache2

# Install MariaDB server.
echo "Installing MariaDB server..."
apt install -y mariadb-server

# Install PHP and PHP modules.
echo "Installing PHP and PHP modules..."
apt install -y php php-mysql

# Remove default index.html file.
echo "Removing default index.html file..."
rm /var/www/html/index.html

# Create directory for custom scripts.
echo "Creating directory for custom scripts..."
mkdir /opt/myexpense_scripts

# Move scripts to custom scripts directory.
echo "Moving scripts to custom scripts directory..."
INSTALL_DIR="$(dirname "$(realpath "$0")")"
mv "$INSTALL_DIR/../install/login_collab1_script.py" /opt/myexpense_scripts
mv "$INSTALL_DIR/../install/login_collab2_script.py" /opt/myexpense_scripts
mv "$INSTALL_DIR/../install/login_manager_script.py" /opt/myexpense_scripts
mv "$INSTALL_DIR/../install/login_admin_script.py" /opt/myexpense_scripts
mv "$INSTALL_DIR/../install/login_scripts.sh" /opt/myexpense_scripts

# Make login_scripts.sh executable.
echo "Making login_scripts.sh executable..."
chmod +x /opt/myexpense_scripts/login_scripts.sh

# Move all contents of the current directory to /var/www/html/.
INSTALL_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

echo "Moving all contents of the current directory to /var/www/html/..."
mv "$INSTALL_DIR/../admin" /var/www/html/
mv "$INSTALL_DIR/../config" /var/www/html/
mv "$INSTALL_DIR/../css" /var/www/html/
mv "$INSTALL_DIR/../fonts" /var/www/html/
mv "$INSTALL_DIR/../img" /var/www/html/
mv "$INSTALL_DIR/../includes" /var/www/html/
mv "$INSTALL_DIR/../.htaccess" /var/www/html/
mv "$INSTALL_DIR/../expense_reports.php" /var/www/html/
mv "$INSTALL_DIR/../index.php" /var/www/html/
mv "$INSTALL_DIR/../login.php" /var/www/html/
mv "$INSTALL_DIR/../logout.php" /var/www/html/
mv "$INSTALL_DIR/../profile.php" /var/www/html/
mv "$INSTALL_DIR/../robots.txt" /var/www/html/
mv "$INSTALL_DIR/../signup.php" /var/www/html/
mv "$INSTALL_DIR/../site.php" /var/www/html/

# Modify AllowOverride directive in /etc/apache2/apache2.conf.
echo "Updating AllowOverride in Apache configuration..."
sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Restart Apache service.
echo "Restarting Apache service..."
service apache2 restart

echo "Granting privileges to MySQL user..."
mysql -u root <<EOF
GRANT ALL ON *.* TO 'MyExpenseUser'@'localhost' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
EOF

# Install dependencies for headless Chrome.
echo "Installing dependencies for headless Chrome..."
apt install -y xvfb libxi6 libgconf-2-4

# Download Google Chrome .deb file to /tmp.
echo "Downloading Google Chrome .deb file to /tmp..."
wget -P /tmp https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb

# Install Google Chrome.
echo "Installing Google Chrome..."
dpkg -i /tmp/google-chrome-stable_current_amd64.deb

# Fix broken dependencies if any.
echo "Fixing broken dependencies if any..."
apt -f install -y

# Install Python3 and pip.
echo "Installing Python3 and pip..."
apt install -y python3 python3-pip

# Install Selenium and WebDriver Manager.
echo "Installing Selenium and WebDriver Manager..."
pip3 install --break-system-packages selenium webdriver-manager

# Add user myexpense.
echo "Adding user myexpense..."
useradd -m -s /bin/bash myexpense
echo "myexpense:myexpense" | chpasswd

# Create and configure the systemd service file.
echo "Creating and configuring systemd service file..."
cat <<EOF > /etc/systemd/system/login_scripts.service
[Unit]
Description=Runs users scripts for MyExpense Vuln VM
ConditionPathExists=/opt/myexpense_scripts/login_scripts.sh

[Service]
Type=forking
ExecStart=/bin/bash /opt/myexpense_scripts/login_scripts.sh
User=myexpense
Group=myexpense
Restart=on-failure

[Install]
WantedBy=multi-user.target
EOF

# Enable myexpense scripts service.
systemctl enable login_scripts.service

# Inform the user that the installation process is complete.
echo "Installation process completed."

# Reboot
reboot