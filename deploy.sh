#!/bin/bash

sudo mkdir /var/www/html/kcs
sudo cp -R kcs/TESTING /var/www/html/kcs
sudo chmod a-rwx,u+rwX,g+rX -R /var/www/html/kcs/TESTING
sudo chmod g+s /var/www/html/kcs/TESTING		
sudo chown www-data:www-data -R /var/www/html/kcs/TESTING

sudo cp kcs/TESTING.conf /etc/apache2/sites-available
sudo chmod 644 /etc/apache2/sites-available/TESTING.conf
sudo chown root:root /etc/apache2/sites-available/TESTING.conf

sudo a2ensite TESTING.kcs
sudo service apache2 reload

sudo mysql -s -N -uroot -p0:OuzKVA -e "CREATE DATABASE TESTING"
sudo mysql -s -N -uroot -p0:OuzKVA TESTING < TESTING_DBs/TESTING_NODATA.sql
sudo service mysql restart