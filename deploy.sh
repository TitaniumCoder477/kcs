#!/bin/bash

echo "`date -u` | WEB FILES | Creating folder... "
sudo mkdir /var/www/html/kcs
echo "`date -u` | WEB FILES | Copying files... "
sudo cp -R kcs/TESTING /var/www/html/kcs
echo "`date -u` | WEB FILES | Setting permissions and ownership... "
sudo chmod a-rwx,u+rwX,g+rX -R /var/www/html/kcs/TESTING
sudo chmod g+s /var/www/html/kcs/TESTING		
sudo chown www-data:www-data -R /var/www/html/kcs/TESTING
echo "`date -u` | WEB FILES | DONE "
echo "`date -u` | APACHE2 VIRTUAL SITE | Copying file... "
sudo cp kcs/TESTING.kcs.conf /etc/apache2/sites-available
echo "`date -u` | APACHE2 VIRTUAL SITE | Setting permissions and ownership... "
sudo chmod 644 /etc/apache2/sites-available/TESTING.kcs.conf
sudo chown root:root /etc/apache2/sites-available/TESTING.kcs.conf
echo "`date -u` | APACHE2 VIRTUAL SITE | Enabling site... "
sudo a2ensite TESTING.kcs
echo "`date -u` | APACHE2 VIRTUAL SITE | Reloading Apache2... "
sudo service apache2 reload
echo "`date -u` | APACHE2 VIRTUAL SITE | DONE "
echo "`date -u` | DATABASE | Creating database... "
sudo mysql -s -N -uroot -p0:OuzKVA -e "CREATE DATABASE TESTING"
echo "`date -u` | DATABASE | Importing structure... "
sudo mysql -s -N -uroot -p0:OuzKVA TESTING < kcs/TESTING_DBs/TESTING_NODATA.sql
echo "`date -u` | DATABASE | Restarting MySQL... "
sudo service mysql restart
echo "`date -u` | DATABASE | DONE "