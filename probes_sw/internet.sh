#!/bin/bash
clear
WHOAMI=$(whoami)
cd /var/www/probes.localhost/probes_sw
echo "Starting internet test"
ping -q -w 1 -c 1 www.google.com > /dev/null && HAS_CONNECTION=true || HAS_CONNECTION=false
if [ "$HAS_CONNECTION" = false ] ; then
	#try to perform the test one more time, eliminate false positives
	echo "Performing second check on connectivity..."
	ping -q -w 1 -c 1 www.usj.edu.mo > /dev/null && HAS_CONNECTION=true || HAS_CONNECTION=false
fi
if [ "$HAS_CONNECTION" = true ] ; then
        source data/config.data
        echo $OUTPUT
        IFACE=$(ip route get 8.8.8.8 | awk '{ print $5; exit }')
        MAC=$(cat /sys/class/net/"$IFACE"/address)
        KEY="KEY_$INDEX_IN_USE"
        GO=$(curl -s --data "mac=$MAC&write_key="${!KEY}"&o=$OUTPUT" $SERVER_1"/handler/internet_test")
        echo $GO
else
        echo "No internet connection. Abort."
fi

