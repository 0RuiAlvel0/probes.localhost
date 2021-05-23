#!/bin/bash
clear
WHOAMI=$(whoami)
cd /var/www/probes.localhost/probes_sw
echo "Starting speed test"
ping -q -w 1 -c 1 8.8.8.8 > /dev/null && HAS_CONNECTION=true || HAS_CONNECTION=false
if [ "$HAS_CONNECTION" = true ] ; then
	source data/config.data
	SPEED_TEST_ERROR="false"
	SPEED_TEST_ERROR_DESCRIPTION=""
	#edit speed.js file to update serverID
	AUX="speedTest({maxTime: 15000, serverId: '$SPEED_TEST_SERVER'});"
	sed -i "s/var test = .*/var test = $AUX/" speed.js
	SPEED_TEST_RESULTS=$(node speed.js)
	if [[ $SPEED_TEST_RESULTS != *"speeds:"* ]]; then
		 SPEED_TEST_ERROR="true"
		 SPEED_TEST_ERROR_DESCRIPTION="this is the error:"$SPEED_TEST_RESULTS
	fi
	OUTPUT=$OUTPUT"~***~SPEED_TEST_ERROR="$SPEED_TEST_ERROR
	OUTPUT=$OUTPUT"~***~SPEED_TEST_ERROR_DESCRIPTION="$SPEED_TEST_ERROR_DESCRIPTION
	OUTPUT=$OUTPUT"~***~SPEED_TEST_RESULTS="$SPEED_TEST_RESULTS

	echo $OUTPUT
	IFACE=$(ip route get 8.8.8.8 | awk '{ print $5; exit }')
	MAC=$(cat /sys/class/net/"$IFACE"/address)
	KEY="KEY_$INDEX_IN_USE"
	GO=$(curl -s --data "mac=$MAC&write_key="${!KEY}"&o=$OUTPUT" $SERVER_1"/handler/speed_test")
	echo $GO
else
	echo "No internet connection. Abort."
fi

