#!/bin/bash

ROOT=""
ETC_PATH="${ROOT}/etc"
APACHE2_PATH="${ETC_PATH}/apache2"
USR_PATH="${ROOT}/usr"
HTML_PATH="${ROOT}/var/www/html"
KCSSITES_PATH="${HTML_PATH}/kcssites"
INSTALLATION_PATH="${HTML_PATH}/installation"
TEMPLATENAME="TEMPLATE"
DBUSERNAME="root"
DBPASSWORD="0:OuzKVA"
LOGPATH="/var/log/kcs"

mkdir "${LOGPATH}"
cd "${INSTALLATION_PATH}"

#----------------------------------------------------------
# GETTING THE EXISTING SITE NAME
#----------------------------------------------------------

	# Get the sitename passed as a parameter
	while [[ $# -gt 1 ]]
	do
	key="$1"
	
	case $key in
		-s|--sitename)
		SITENAME="$2"
		;;
		*)
		#unknown option
		;;
	esac
	shift 2
	done

	if [ -n "$SITENAME" ]; then

# LOGGING
echo "`date -u` | $SITENAME | Discontinuing site..." | cat >> "${LOGPATH}/$SITENAME.log"	
	
#----------------------------------------------------------
# DELETING THE EXISTING SITE FILES
#----------------------------------------------------------		

			# Determine if the www/html/SITENAME folder exists
			if [ -d "${HTML_PATH}/$SITENAME" ]; then
				# LOGGING
				echo "`date -u` | $SITENAME | Site folder found." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Site folder was not found!" | cat >> "${LOGPATH}/$SITENAME.log"
				exit 1				
			fi
			
			rm -R "${HTML_PATH}/$SITENAME"
			
			# Determine if the www/html/SITENAME folder still exists
			if [ -d "${HTML_PATH}/$SITENAME" ]; then
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Site folder could not be deleted." | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1				
			else
				# LOGGING
				echo "`date -u` | $SITENAME | Site folder was deleted." | cat >> "${LOGPATH}/$SITENAME.log"				
			fi			

#----------------------------------------------------------
# DELETING THE EXISTING VIRTUAL SITE CONFIG
#----------------------------------------------------------
			
			# Create the Apache2 virtual website
			tar xzvf "$TEMPLATENAME-conf.tar.gz" -C $APACHE2_PATH/sites-available
			mv $APACHE2_PATH/sites-available/$TEMPLATENAME.conf $APACHE2_PATH/sites-available/$SITENAME.conf
			sed -i "s/ServerAdmin webmaster@localhost/ServerAdmin kcs-admin@kioskcheckoutsystem.com/" $APACHE2_PATH/sites-available/$SITENAME.conf
			sed -i "s/ServerName $TEMPLATENAME.kcs/ServerName $SITENAME.kioskcheckoutsystem.com/" $APACHE2_PATH/sites-available/$SITENAME.conf
			sed -i "s:DocumentRoot $HTML_PATH/$TEMPLATENAME:DocumentRoot ${HTML_PATH}/$SITENAME:" $APACHE2_PATH/sites-available/$SITENAME.conf
			sed -i "s;RewriteMap siteip txt:$HTML_PATH/$TEMPLATENAME/conf.siteip;RewriteMap siteip txt:${HTML_PATH}/$SITENAME/conf.siteip;" $APACHE2_PATH/sites-available/$SITENAME.conf
			sed -i "s;RewriteRule (.*) http://kcs;RewriteRule (.*) https://login.kioskcheckoutsystem.com;" $APACHE2_PATH/sites-available/$SITENAME.conf
			chown www-data:www-data $APACHE2_PATH/sites-available/$SITENAME.conf
			chmod 640 $APACHE2_PATH/sites-available/$SITENAME.conf
			
			if [ -f "$APACHE2_PATH/sites-available/$SITENAME.conf" ]; then
				# LOGGING
				echo "`date -u` | $SITENAME | Apache virtual site found." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Apache virtual site was not created!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi
			
			a2dissite $SITENAME
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Apache virtual site disabled." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Apache virtual site could not be disabled!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi
			
			rm "$APACHE2_PATH/sites-available/$SITENAME.conf"
			if [ -f "$APACHE2_PATH/sites-available/$SITENAME.conf" ]; then
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Apache virtual site could not be deleted!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			else
				# LOGGING
				echo "`date -u` | $SITENAME | Apache virtual site deleted." | cat >> "${LOGPATH}/$SITENAME.log"	
			fi
			
#----------------------------------------------------------
# DELETING THE EXISTING SITE DATABASE
#----------------------------------------------------------
		
			mysql -s -N -u$DBUSERNAME -p$DBPASSWORD -e "DROP DATABASE $SITENAME;"			
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Database was deleted." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Database could not be deleted!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi
			
			mysql -s -N -u$DBUSERNAME -p$DBPASSWORD -e "USE mysql; DELETE FROM \`user\` WHERE \`User\`='$SITENAME';"
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Sitename user deleted." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Sitename user could not be deleted!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi
			
			#service mysql restart

#----------------------------------------------------------
# INSTRUCTING FASTSPRING TO REMOVE SITE
#----------------------------------------------------------

			
#----------------------------------------------------------
# DELETING KCSSITES ENTRY
#----------------------------------------------------------

			mysql -s -N -u$DBUSERNAME -p$DBPASSWORD -e "USE KCSSites; INSERT INTO \`Sites\` (\`SITENAME\`, \`EMAIL\`, \`PASSWORD\`, \`STATUS_ID\`) VALUES ('$SITENAME', '$EMAIL', '$PASSWORD', 1);"
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Sitename added to KCSSites table." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Sitename was not added to KCSSites table!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi

			mysql -s -N -u$DBUSERNAME -p$DBPASSWORD -e "CREATE USER '$SITENAME'@'localhost' IDENTIFIED BY '$PASSWORD';"
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Sitename user created in database." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Sitename user not created in database!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi

			mysql -s -N -u$DBUSERNAME -p$DBPASSWORD -e "GRANT ALL PRIVILEGES ON $SITENAME.* TO '$SITENAME'@'localhost' IDENTIFIED BY '$PASSWORD';"
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Sitename user granted access to sitename in database." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Sitename user not granted access to sitename in database!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi

			mysql -s -N -u$DBUSERNAME -p$DBPASSWORD -e "FLUSH PRIVILEGES;"
			if [ $? -eq 0 ]; then 
				# LOGGING
				echo "`date -u` | $SITENAME | Database privileges flushed." | cat >> "${LOGPATH}/$SITENAME.log"	
			else
				# LOGGING
				echo "`date -u` | $SITENAME | !! FAILURE !! Database privileges not flushed!" | cat >> "${LOGPATH}/$SITENAME.log"	
				exit 1
			fi

			#service mysql restart
			
#----------------------------------------------------------
# EMAILING CONFIRMATION
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
		
			#sh ./009-reloadAPACHE.sh

	else

		echo
		echo "ERROR: Invalid parameters passed to script."
		echo "Usage: ./000c-createNewSite.sh -s MYSITE -e myemail@mydomain.com -p myPassword123!"
		echo
		echo
		
		exit 1

	fi
	
	exit 0
	
