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

# Inform the user that the installation process is complete.
echo "Installation process completed."