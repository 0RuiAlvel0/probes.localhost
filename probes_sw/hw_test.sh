#!/bin/bash
#Runs every 1 minutes
clear
WHOAMI=$(whoami)
cd /var/www/probes.localhost/probes_sw
echo "Starting HW test"
source data/config.data
NO_HTTP_SERVER_1=$(echo $SERVER_1 | sed 's/https\?:\/\///')
ping -q -w 1 -c 1 $NO_HTTP_SERVER_1 > /dev/null && HAS_CONNECTION=true || HAS_CONNECTION=false
if [ "$HAS_CONNECTION" = true ] ; then
	OUTPUT=""
	IFACE1=$(ip addr | awk '/state UP/ {print $2}')
	IFACE=$(echo "${IFACE1::-1}")
	MAC=$(cat /sys/class/net/"$IFACE"/address)
	TS=$(date)
	UPTIME=$(uptime)
	OUTPUT=$OUTPUT"~***~IFACE="$IFACE
	OUTPUT=$OUTPUT"~***~MAC="$MAC
	KEY="KEY_$INDEX_IN_USE"
	OUTPUT=$OUTPUT"~***~KEY="${!KEY}
	OUTPUT=$OUTPUT"~***~TS="$TS
	OUTPUT=$OUTPUT"~***~UPTIME="$UPTIME
	LOCALIP=$(hostname -I)
	EXTERNALIP=$(curl -s ipinfo.io/ip)
	OUTPUT=$OUTPUT"~***~LOCALIP="$LOCALIP
	OUTPUT=$OUTPUT"~***~EXTERNALIP="$EXTERNALIP
	echo $OUTPUT

	GO=$(curl -s --data "mac=$MAC&write_key="${!KEY}"&o=$OUTPUT" $SERVER_1"/handler/hw_test")
	echo $GO
else
	echo "No connection to configuration server. Abort."
fi
