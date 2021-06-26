#!/bin/bash
clear
WHOAMI=$(whoami)
cd /var/www/probes.localhost/probes_sw
echo "Starting ping test"
ping -q -w 1 -c 1 8.8.8.8 > /dev/null && HAS_CONNECTION=true || HAS_CONNECTION=false
if [ "$HAS_CONNECTION" = true ] ; then
        OUTPUT=""
        source data/config.data
	PING_TEST=$(ping "$PING_SERVER" -c "$NUM_PING_TESTS" 2>&1)
	LATENCY_PING_TEST=$(ping -U "$PING_SERVER" -c "$NUM_PING_TESTS" 2>&1)
	PING_ERROR="false"
	PING_ERROR_DESCRIPTION=""
	PING_PACKETS_TRANSMITTED=""
	PING_PACKETS_RECEIVED=""
	PING_PACKET_LOSS=""
	PING_MIN_TIME=""
	PING_AVERAGE_TIME=""
	PING_MAX_TIME=""
	PING_RESOLVED_IP=""
	PING_RESOLVED_SERVER=""
	PING_RESULTS_JSON="{"

	if [[ $PING_TEST == *"ping:"* ]]; then
		PING_ERROR="true"
		PING_ERROR_DESCRIPTION=$PING_TEST
	else
		AUX=$(grep 'packets transmitted' <<< "$PING_TEST")
		PING_PACKETS_TRANSMITTED=$(echo "$AUX" | cut -d" " -f1)
		PING_PACKETS_RECEIVED=$(echo "$AUX" | cut -d"," -f2 | cut -d" " -f2)
		PING_PACKET_LOSS=$(echo "$AUX" | cut -d"," -f3 | cut -d" " -f2)
		AUX=$(grep 'PING ' <<< "$PING_TEST")
		PING_RESOLVED_IP=$(echo "$AUX" | cut -d"(" -f2 | cut -d ")" -f1)
		AUX=$(grep 'min/avg/max' <<< "$PING_TEST")
		PING_MIN_TIME=$(echo "$AUX" | cut -d" " -f4 | cut -d"/" -f1)
		PING_AVERAGE_TIME=$(echo "$AUX" | cut -d" " -f4 | cut -d"/" -f2)
		PING_MAX_TIME=$(echo "$AUX" | cut -d" " -f4 | cut -d"/" -f3)
echo $LATENCY_PING_TEST
		AUX_2=$(grep 'min/avg/max' <<< "$LATENCY_PING_TEST")
		LATENCY_PING_MIN_TIME=$(echo "$AUX_2" | cut -d" " -f4 | cut -d"/" -f1)
                LATENCY_PING_AVERAGE_TIME=$(echo "$AUX_2" | cut -d" " -f4 | cut -d"/" -f2)
                LATENCY_PING_MAX_TIME=$(echo "$AUX_2" | cut -d" " -f4 | cut -d"/" -f3)

	fi
	OUTPUT=$OUTPUT"~***~PING_ERROR="$PING_ERROR
	OUTPUT=$OUTPUT"~***~PING_ERROR_DESCRIPTION="$PING_ERROR_DESCRIPTION
	OUTPUT=$OUTPUT"~***~PING_PACKETS_TRANSMITTED="$PING_PACKETS_TRANSMITTED
	OUTPUT=$OUTPUT"~***~PING_PACKETS_RECEIVED="$PING_PACKETS_RECEIVED
	OUTPUT=$OUTPUT"~***~PING_PACKET_LOSS="$PING_PACKET_LOSS
	OUTPUT=$OUTPUT"~***~PING_MIN_TIME="$PING_MIN_TIME
	OUTPUT=$OUTPUT"~***~PING_AVERAGE_TIME="$PING_AVERAGE_TIME
	OUTPUT=$OUTPUT"~***~PING_MAX_TIME="$PING_MAX_TIME
	OUTPUT=$OUTPUT"~***~PING_RESOLVED_IP="$PING_RESOLVED_IP
	#if no errors, collect data of each test made
	if [[ $PING_ERROR != "true" ]]; then
		COUNTER=1
		while read -r line ; do
			BYTES=$(echo $line | cut -d" " -f1)
			FROM=$(echo $line | sed 's/.* from \(.*\):.*/\1/')
			PING_RESOLVED_SERVER=$FROM
			TTL=$(echo $line | sed 's/.* ttl=\(.*\) time.*/\1/')
			TIME=$(echo $line | sed 's/.* time=\(.*\) .*/\1/')
			TIME_UNITS=$(echo "\"ms\"")
			PING_RESULTS_JSON=$PING_RESULTS_JSON"\"$COUNTER\":{\"BYTES\":$BYTES,\"FROM\":\"$FROM\",\"TTL\":$TTL,\"TIME\":$TIME,\"TIME_UNITS\":$TIME_UNITS},"
			let COUNTER=COUNTER+1
		done <<< "$(grep 'icmp_seq=' <<< "$PING_TEST")"
		#Remove the last comma on the json string
		PING_RESULTS_JSON=${PING_RESULTS_JSON%?}
	fi
	PING_RESULTS_JSON=$PING_RESULTS_JSON"}"
	OUTPUT=$OUTPUT"~***~PING_RESULTS_JSON="$PING_RESULTS_JSON
	OUTPUT=$OUTPUT"~***~PING_RESOLVED_SERVER="$PING_RESOLVED_SERVER
	OUTPUT=$OUTPUT"~***~PING_LATENCY_MIN_TIME="$LATENCY_PING_MIN_TIME
	OUTPUT=$OUTPUT"~***~PING_LATENCY_AVERAGE_TIME="$LATENCY_PING_AVERAGE_TIME
	OUTPUT=$OUTPUT"~***~PING_LATENCY_MAX_TIME="$LATENCY_PING_MAX_TIME
	echo $OUTPUT
	IFACE=$(ip route get 8.8.8.8 | awk '{ print $5; exit }')
        MAC=$(cat /sys/class/net/"$IFACE"/address)
	KEY="KEY_$INDEX_IN_USE"
	GO=$(curl -s --data "mac=$MAC&write_key="${!KEY}"&o=$OUTPUT" $SERVER_1"/handler/ping_test")
	echo $GO
else
        echo "No internet connection. Abort."
fi
