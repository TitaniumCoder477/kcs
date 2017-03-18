# kcs
Kiosk Checkout System (KCS) is a simple web application for checking items in and out. We were having to juggle about four clipboards at the office in order to check out vehicles and laptops and equipment when going onsite. This method was frustrating to us engineers and also to our coordinator who had to track these things. We never knew if a vehicle had gas in it or not until we were sitting in it, ready to go. It was also difficult to know who had a particular item that was in demand and needed for a project.

Hence, I developed KCS. Originally it was just to meet the need at work. But then I saw how useful it was to us and aleviated so much frustration, so I decided to license it for propietary use and convert it to a cloud application. I got pretty far with that project but found connecting KCSSites to a payment gateway was more challenging than I thought. Eventually, after weeks and weeks of deliberating and trying to make it work, I pulled the plug on that idea. Hence KCS is now licensed forever under the GPLv3 license.

I still have a few use cases that I need to implement. And I still have to update our virtual, on-prem LAMP server hosting KCS. So there's much to do. Getting this project on GitHub was the first step!

The domain kioskcheckoutsystem.com is still leased by me (hopefully for a long time!), so I plan to open the firewall to the website (already developed) as soon as I make the changes to inform visitors about the new licensing, how to get it, etc. and double-check the web server security.

All this said, feel free to download KCS and deploy it on your own LAMP or WAMP server! I will add the actual blank MySQL DB files in the repository soon.

Thanks!

TitaniumCoder477



QUICKSTART

PREP: Determine variables

	Please think about and decide on the following:
	
		Name of KCS server? 			Default: TESTING.kcs
		Name of KCS web app?			Default: TESTING.kcs
		*MySQL root username?			Default: root
		*MySQL root password?			Default: 0:OuzKVA		(3rd character is a letter)
		
		*If you are going to change these, you must also update the ../TESTING/snippets/database.php file!

SETUP SERVER: LAMP server

	Do you have a LAMP server all ready to go? If not, one way you can spin up a LAMP server is by 
	installing directly from Ubuntu Server OS ISO. Just choose "LAMP server" from Software selection 
	during installation. (You might also want to select "OpenSSH server" if this server will be headless 
	or you want to get to it from your dev laptop or workstation via your local network)
	
	During the Ubuntu Server installation and if you have selected "LAMP server," you will be prompted 
	for a MySQL root password. Make sure to use the MySQL root username/password chosen above.

	If you already have a server but not necessarily LAMP, you can use the 001-setupLAMP.sh script
	located in the installation/ folder of this repository.

	** Should also work on a WAMP server, but you're on your own for now; I have no experience with
	a WAMP server yet. **

DOWNLOAD: https://github.com/TitaniumCoder477/kcs

	You can clone the repository or download a ZIP and extract.
	
	To clone from Bash: git clone https://github.com/TitaniumCoder477/kcs.git
	
CONFIGURATION

	This is a breakdown of the folders and files and what to do with them.
	
	If you want to skip the custom configuration, just do the following:
	
		chmod u+x kcs/deploy.sh
		./kcs/deploy.sh
		
	Or, follow the steps below (which are all included in the script above).

	installation/
	1. What: A handful of scripts and such
	2. (optional) Where: You can put this folder anywhere

	TESTING/
	1. What: The main web app folder
	2. Where: Must go into /var/www/html/kcs (a kcs sub-folder of your web files location)
	3. Set ownership and permissions (www-data:www-data by default or [your username]:www-data if you plan to edit files)
		
		From the local git folder:
		
		sudo mkdir /var/www/html/kcs
		sudo cp -R kcs/TESTING /var/www/html/kcs
		sudo chmod a-rwx,u+rwX,g+rX -R /var/www/html/kcs/TESTING
		sudo chmod g+s /var/www/html/kcs/TESTING		
		sudo chown www-data:www-data -R /var/www/html/kcs/TESTING
	
	TESTING.conf
	1. What: The apache2 virtual site config file
	2. Where: Put this in the /etc/apache2/sites-available/ folder and set ownership and permissions
			
		From the local folder where git downloaded kcs:
		
		sudo cp kcs/TESTING.conf /etc/apache2/sites-available
		sudo chmod 644 /etc/apache2/sites-available/TESTING.conf
		sudo chown root:root /etc/apache2/sites-available/TESTING.conf
	   
	3. (optional) Edit this file and change the ServerName to whatever you want
	
		Default as per above: TESTING.kcs
		
		If you have chosen to name your actual LAMP server something else, you might want to do the same here to
		keep things consistent. I.e. your LAMP server and Apache2 virtual site CAN be the same but do not have to be.
	  	
		WARNING: Please do not make your KCS server Internet facing. Keep it firewalled within your local network.		
	  
	4. Enable the virtual site and reload Apache2
	
		sudo a2ensite TESTING.kcs
		sudo service apache2 reload
	
	TESTING_DBs/
	1. What: A handful of MySQL DB templates to import
	2. Where: Import one of them into MySQL  (if you changed the root password under SETUP SERVER above, then use that)
	
		From the local folder where git downloaded kcs:
		
		sudo mysql -s -N -uroot -p0:OuzKVA -e "CREATE DATABASE TESTING"
		sudo mysql -s -N -uroot -p0:OuzKVA TESTING < kcs/TESTING_DBs/TESTING_NODATA.sql
		sudo service mysql restart
		
TESTING

	Now KCS should be setup. However, you may need to configure DNS to get to it. If you are running it on a Windows 
	domain, determine the IP address assigned to KCS (or better yet, give it a static or at least a reservation). Then 
	create a DNS entry for TESTING.kcs (or whatever you chose in CONFIGURATION > TESTING.conf > 3 above) to that IP address.
	
	Then refresh your local DNS:
	
		Ex. On a Windows OS open an Administrative command prompt and type: 
		
			ipconfig /flushdns && ipconfig /registerdns
	
	See if you can resolve and ping TESTING.kcs:
	
		ping TESTING.kcs
		
	If successful, you can open a browser and go to http://TESTING.kcs
		