#!/bin/bash
#Probe configuration manager file
#This file is called by cron and:
#1. loads local config.data variables into local variables
#2. queries configuration server for current configuration ID
#3. if configuration id on server == local configuration id, nothing to do
#4. if configuration id on server != local configuration id, get all info from server
#5. update config.data with new values
clear
echo "Starting configuration script"

WHOAMI=$(whoami)
cd /var/www/probes.localhost/probes_sw

#Load current configuration values from config.data
source data/config.data

#get current probe date
date

#assess which key and adaptor we are going to use
#try one after the other until the first one works
HAS_ERROR=true
for i in 0 1; do
	if [[ $SERVER_1 != "" ]]; then
		IFACE1=$(ip addr | awk '/state UP/ {print $2}')
        	IFACE=$(echo "${IFACE1::-1}")
		#IFACE=$(ip route get 8.8.8.8 | awk '{ print $5; exit }')
		MAC=$(cat /sys/class/net/"$IFACE"/address)
		mac="MAC_"$i
		if [[ $MAC == ${!mac} ]]; then
			write_key="KEY_"$i
			RESULT=$(curl -s --data "mac="${!mac}"&write_key="${!write_key} $SERVER_1"/handler/version")
			if [[ $RESULT == *"CONFIG_ID="* ]]; then
				server="SERVER_1"
				HAS_ERROR=false
				sed -i 's@INDEX_IN_USE=.*@INDEX_IN_USE='$i'@' data/config.data
				break
			else
				printf "ERROR 1: Looks like this probe is not registered MAC: " ${!mac} " Wk: "${!write_key}
			fi
		else
			printf "ERROR 2: Looks like this probe is not registered MAC: " ${!mac} " Wk: "${!write_key}
		fi
	fi
done

if [[ $HAS_ERROR = false && ${!server} != "" ]]; then
	echo "Connecting to configuration server ("${!server}")" 
	RESULT=$(curl -s --data "mac="${!mac}"&write_key="${!write_key} ${!server}"/handler/version")
	#echo $RESULT
	if [[ $RESULT == *"CONFIG_ID="* ]]; then
		#get server version number
		SERVER_CONFIG_VERSION=$(grep 'CONFIG_ID=' <<< "$RESULT" | cut -f2 -d"=")
		echo "Server CONFIG_ID: "$SERVER_CONFIG_VERSION" Probe CONFIG_ID: "$CONFIG_ID
		#server replied OK with version number; compare with the version on the file
		if [[ $SERVER_CONFIG_VERSION != $CONFIG_ID ]]; then
			echo "Mismatch! Configuration needs update..."
			#get configuration variables from server and update config.data file
			CONFIG=$(curl -s --data "mac="${!mac}"&write_key="${!write_key} ${!server}"/handler/update")
			#echo $CONFIG
			NEW_CONFIG_ID=$(echo $CONFIG | jq -r '.CONFIG_ID')
			sed -i 's@CONFIG_ID=.*@CONFIG_ID='$NEW_CONFIG_ID'@' data/config.data
			NEW_SERVER_1=$(echo $CONFIG | jq -r '.SERVER_1')
			sed -i "s@SERVER_1=.*@SERVER_1="$NEW_SERVER_1"@" data/config.data
			NEW_TEST_2=$(echo $CONFIG | jq -r '.TEST_2')
			sed -i "s@TEST_2=.*@TEST_2="$NEW_TEST_2"@" data/config.data
			NEW_TEST_5=$(echo $CONFIG | jq -r '.TEST_5')
			sed -i "s@TEST_5=.*@TEST_5="$NEW_TEST_5"@" data/config.data
			NEW_PING_TEST_FREQ=$(echo $CONFIG | jq -r '.PING_TEST_FREQ')
			sed -i 's@PING_TEST_FREQ=.*@PING_TEST_FREQ='$NEW_PING_TEST_FREQ'@' data/config.data
			NEW_SPEED_TEST_FREQ=$(echo $CONFIG | jq -r '.SPEED_TEST_FREQ')
			sed -i 's@SPEED_TEST_FREQ=.*@SPEED_TEST_FREQ='$NEW_SPEED_TEST_FREQ'@' data/config.data
			NEW_CHANNEL_TEST_FREQ=$(echo $CONFIG | jq -r '.CHANNEL_TEST_FREQ')
			sed -i 's@CHANNEL_TEST_FREQ=.*@CHANNEL_TEST_FREQ='$NEW_CHANNEL_TEST_FREQ'@' data/config.data

			#edit the crontab to change the frequency of the server checks:
			#http://zszsit.blogspot.com/2012/07/edit-crontab-from-shell-script.html
			crontab -l > crontab.lst
			#remove line with ping test schedule and line with speed test schedule
			sed '/ping_test\|speed_test\|channel_test/d' crontab.lst > temp
			#add new schedule to temp file
			touch temp
			if [[ $NEW_PING_TEST_FREQ != "0" ]]; then
				echo "*/$NEW_PING_TEST_FREQ * * * * /var/www/probes.localhost/probes_sw/ping_test.sh > /var/www/probes.localhost/probes_sw/logs/ping.log" >> temp
			fi
			if [[ $NEW_SPEED_TEST_FREQ != "0" ]]; then
				echo "*/$NEW_SPEED_TEST_FREQ * * * * /var/www/probes.localhost/probes_sw/speed_test.sh > /var/www/probes.localhost/probes_sw/logs/speed.log" >> temp
			fi
			if [[ $NEW_CHANNEL_TEST_FREQ != "0" ]]; then
				echo "*/$NEW_CHANNEL_TEST_FREQ * * * * /var/probes.localhost/probes_sw/channel_test.sh > /var/www/probes.localhost/probes_sw/logs/channel.log" >> temp
			fi
			#install the new crontab
			crontab temp
			rm temp

			NEW_PING_SERVER=$(echo $CONFIG | jq -r '.PING_SERVER')
			sed -i 's@PING_SERVER=.*@PING_SERVER='$NEW_PING_SERVER'@' data/config.data
			NEW_NUM_PING_TESTS=$(echo $CONFIG | jq -r '.NUM_PING_TESTS')
			sed -i 's@NUM_PING_TESTS=.*@NUM_PING_TESTS='$NEW_NUM_PING_TESTS'@' data/config.data
			NEW_SPEED_TEST_SERVER=$(echo $CONFIG | jq -r '.SPEED_TEST_SERVER')
                        sed -i 's@SPEED_TEST_SERVER=.*@SPEED_TEST_SERVER='$NEW_SPEED_TEST_SERVER'@' data/config.data
			NEW_WIFIAP=$(echo $CONFIG | jq -r '.WIFIAP')
			sed -i 's@WIFIAP=.*@WIFIAP='$NEW_WIFIAP'@' data/config.data
			NEW_WIFIUN=$(echo $CONFIG | jq -r '.WIFIUN')
			sed -i 's@WIFIUN=.*@WIFIUN='$NEW_WIFIUN'@' data/config.data
			NEW_WIFIPW=$(echo $CONFIG | jq -r '.WIFIPW')
			sed -i 's@WIFIPW=.*@WIFIPW='$NEW_WIFIPW'@' data/config.data
			echo "Configuration data has been updated"
		else
			echo "Probe is in sync. Nothing to do."
		fi
	else
		echo "ERROR - Check server message. Probably probe is not registered"
	fi

else
	#we tried the configuration server but there was no information  on the config.data file for that
	echo "You need to specify a configuration server. I couldn't find one!"
fi
