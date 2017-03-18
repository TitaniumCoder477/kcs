#!/bin/bash

echo "----------------------------------------------------------"
echo " UPDATE AND UPGRADE PACKAGES							    "
echo "----------------------------------------------------------"
echo
	apt-get update
	apt-get upgrade
echo
echo
echo "----------------------------------------------------------"
echo " INSTALL LAMP PACKAGES								    "
echo "----------------------------------------------------------"
echo
	apt-get install lamp-server^