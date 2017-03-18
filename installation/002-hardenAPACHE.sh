#!/bin/bash

ROOT=""
ETC="$ROOT/etc"
APACHE2="$ETC/apache2"
MODSECURITY="$ETC/modsecurity"
USR="$ROOT/usr"
SHARE="$USR/share"
MODSECURITYCRS="$SHARE/modsecurity-crs"

echo "----------------------------------------------------------"
echo " HARDEN APACHE2 SERVER									"
echo "----------------------------------------------------------"
echo
	# Make test folders
	if [ ! $ROOT = "" ]; then
		mkdir $ROOT
		mkdir $APACHE2
		mkdir "$APACHE2/conf-available"
		mkdir $MODSECURITY
	fi
	# Disable default site
	a2dissite 000-default
	# Hide Apache Version
	tar xzvf security-conf.tar.gz -C $APACHE2/conf-available 	# security.conf /etc/apache2/conf-available/
	# Extract custom apache config
	# *Turn Off Directory Browsing and Disable Symbolic Links
	# *Turn Off Server Side Includes and CGI Execution
	# *Limiting Large Requests
	# *Disallow Browsing Outside the Document Root
	tar xzvf apache2-conf.tar.gz -C $APACHE2					# apache2.conf /etc/apache2/	
	# Disable Unnecessary Modules
	a2dismod autoindex
	a2dismod status
	# Make Use of ModSecurity
	apt-get install libapache2-modsecurity
	apachectl -M | grep --color security
	tar xzvf modsecurity-conf.tar.gz -C $MODSECURITY			# modsecurity.conf /etc/modsecurity/
	# Install modsecurity-crs
	if [ ! -d "$MODSECURITYCRS" ]; then
		tar xzvf SpiderLabs-owasp-modsecurity-crs-2.2.9-37-gf0e12e3.tar.gz -C $MODSECURITYCRS
		chown root:root -R $MODSECURITYCRS
		chmod a-rwx -R $MODSECURITYCRS
		chmod ug+rw -R $MODSECURITYCRS
		chmod a+rX -R $MODSECURITYCRS
	fi
	# Activate SQL Injection CRS
	ln -s $MODSECURITYCRS/base_rules/modsecurity_crs_41_sql_injection_attacks.conf $MODSECURITYCRS/activated_rules/
echo
echo
