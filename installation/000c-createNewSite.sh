#!/bin/bash

ROOT=""
ETC_PATH="${ROOT}/etc"
APACHE2_PATH="${ETC_PATH}/apache2"
USR_PATH="${ROOT}/usr"
HTML_PATH="${ROOT}/var/www/html"
KCSSITES_PATH="${HTML_PATH}/kcssites"
INSTALLATION_PATH="${HTML_PATH}/installation"
TEMPLATENAME="TEMPLATE"
##commented out after adding parameters below##DBUSERNAME="root"
##commented out after adding parameters below##DBPASSWORD="0:OuzKVA"
LOGPATH="/var/log/kcs"

mkdir "${LOGPATH}"
cd "${INSTALLATION_PATH}"

#----------------------------------------------------------
#GETTING THE NEW SITE NAME
#----------------------------------------------------------

	# Get the sitename passed as a parameter
	while [[ $# -gt 1 ]]
	do
	key="$1"
	
	case $key in
		-u|--dbusername)
		DBUSERNAME="$2"
		;;
		-p|--dbpassword)
		DBPASSWORD="$2"
		;;		
		-s|--sitename)
		SITENAME="$2"
		;;
		*)
		#unknown option
		;;
	esac
	shift 2
	done

	if [ -n "$USERNAME" ] && [ -n "$PASSWORD" ] && [ -n "$SITENAME" ]; then

# LOGGING
echo "`date -u` | $SITENAME | Creating site..." | cat >> "${LOGPATH}/$SITENAME.log"	
	
#----------------------------------------------------------
# EXTRACTING THE TEMPLATE AS NEW SITE
#----------------------------------------------------------		

			# Create the www/html/SITENAME folder
			mkdir "${HTML_PATH}/$SITENAME"
			if [ -d "${HTML_PATH}/$SITENAME" ]; then
				# LOGGING
				echo "`date -u` | $SITENAME | Site folder created." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Site folder was not created!" | cat >> "${LOGPATH}/$SITENAME.log"
				exit 1				
			fi
			
			tar xzvf "$TEMPLATENAME-www.tar.gz" -C ${HTML_PATH}/$SITENAME
			SIZE=$(du -B1 -s "${HTML_PATH}/$SITENAME" | cut -f1)    
			# 4096 bytes = empty folder
			if [ $SIZE -gt 4096 ]; then
				# LOGGING
				echo "`date -u` | $SITENAME | Site folder contains data." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Site folder is empty!" | cat >> "${LOGPATH}/$SITENAME.log"
				exit 1
			fi				

			chown www-data:www-data -R ${HTML_PATH}/$SITENAME
			chmod a-rwx,u+rwX,g+rX -R ${HTML_PATH}/$SITENAME
			chmod g+s ${HTML_PATH}/$SITENAME
			#umask 027 ???

#----------------------------------------------------------
# EXTRACTING THE TEMPLATE VIRTUAL SITE CONFIG
#----------------------------------------------------------
			
			# Create the Apache2 virtual website
			tar xzvf "$TEMPLATENAME-conf.tar.gz" -C $APACHE2_PATH/sites-available
			mv $APACHE2_PATH/sites-available/$TEMPLATENAME.conf $APACHE2_PATH/sites-available/$SITENAME.conf
			## Uncomment and chnage if you want to use a valid email
			#sed -i "s/ServerAdmin webmaster@localhost/ServerAdmin kcs-admin@kioskcheckoutsystem.com/" $APACHE2_PATH/sites-available/$SITENAME.conf
			## Modify to match your domain or workgroup
			sed -i "s/ServerName $TEMPLATENAME.kcs/ServerName $SITENAME.kioskcheckoutsystem.com/" $APACHE2_PATH/sites-available/$SITENAME.conf
			sed -i "s:DocumentRoot $HTML_PATH/$TEMPLATENAME:DocumentRoot ${HTML_PATH}/$SITENAME:" $APACHE2_PATH/sites-available/$SITENAME.conf
			chown www-data:www-data $APACHE2_PATH/sites-available/$SITENAME.conf
			chmod 640 $APACHE2_PATH/sites-available/$SITENAME.conf
			
			if [ -f "$APACHE2_PATH/sites-available/$SITENAME.conf" ]; then
				# LOGGING
				echo "`date -u` | $SITENAME | Apache virtual site created." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Apache virtual site was not created!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi
			
			chown www-data:www-data ${HTML_PATH}/$SITENAME/conf.siteip
			chmod 644 ${HTML_PATH}/$SITENAME/conf.siteip
			
#----------------------------------------------------------
# IMPORTING TEMPLATE DATABASE
#----------------------------------------------------------
		
			mysql -s -N -u$DBUSERNAME -p$DBPASSWORD -e "CREATE DATABASE $SITENAME"			
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Database was created." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Database was not created!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi

			mysql -s -N -u$DBUSERNAME -p$DBPASSWORD $SITENAME < "$TEMPLATENAME.sql"			
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Database was loaded from template." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Database was not loaded from template!" | cat >> "${LOGPATH}/$SITENAME.log"
				if [ -f "$TEMPLATENAME.sql" ]; then
					# LOGGING
					echo "`date -u` | $SITENAME | Template database file found." | cat >> "${LOGPATH}/$SITENAME.log"	
				else
					# LOGGING
					echo "`date -u` | $SITENAME | !! FAILURE !! Template database file not found!" | cat >> "${LOGPATH}/$SITENAME.log"	
				fi
				exit 1
			fi

			service mysql restart
			
#----------------------------------------------------------
# ENABLING NEW VIRTUAL SITE
#----------------------------------------------------------
		
			a2ensite $SITENAME
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Apache virtual site enabled." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Apache virtual site not enabled!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi
		
			sh ./009-reloadAPACHE.sh

	else

		echo
		echo "ERROR: Invalid parameters passed to script."
		echo "Usage: ./000c-createNewSite.sh -u mysqlrootuser -p mysqlrootpassword -s MYSITE"
		echo
		echo
		
		exit 1

	fi
	
	exit 0
	
