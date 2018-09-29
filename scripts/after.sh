#!/bin/sh

green='\033[0;32m'
done='\033[0m' # No Color


printf "\n${green}Download and Install the Latest Updates for the OS.${done}"
sudo apt update -y


printf "\n${green}Set the Server Timezone to Europe/Athens.${done}"
sudo timedatectl set-timezone Europe/Athens
sudo dpkg-reconfigure -f noninteractive tzdata


printf "\n${green}Install essential packages.${done}"
sudo apt -y install zsh htop


printf "\n${green}Install MySQL Server in a Non-Interactive mode.${done}"
echo "mysql-server-5.6 mysql-server/root_password password secret" | sudo debconf-set-selections
echo "mysql-server-5.6 mysql-server/root_password_again password secret" | sudo debconf-set-selections
sudo apt -y install mysql-server
sudo mysql -uroot -psecret -e "create database if not exists sass;";
sudo mysql -uroot -psecret sass < "/var/www/sass/scripts/database.sql"


printf "\n${green}Install PHP5.6.${done}"
sudo apt -y install python-software-properties
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt -y install php5.6 php5.6-mcrypt php5.6-mysql php5.6-zip php5.6-xml php5.6-mbstring php5.6-curl

printf "\n${green}Install mailer.${done}"
sudo apt -y install sendmail/xenial

printf "\n${green}Install composer.${done}"
sudo apt -y install composer


printf "\n${green}Install Apache.${done}"
sudo apt install apache2 -y
sudo apache2ctl configtest
sudo a2enmod rewrite


printf "\n${green}Setup shared directory.${done}"
sudo cp /var/www/sass/scripts/sass.conf /etc/apache2/sites-available/sass.conf
sudo a2ensite sass.conf
sudo a2dissite 000-default.conf
sudo systemctl restart apache2


printf "\n${green}Install composer.${done}"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer


printf "\n${green}Setup terminal prompt.${done}"
wget -O - https://gist.githubusercontent.com/rdok/4e9b7a589f63c3d8219f/raw/617f720a4915ce7267ea4c47acf3eb35d3bcd0fb/prepare_vm.sh | bash


sudo apt upgrade -y
