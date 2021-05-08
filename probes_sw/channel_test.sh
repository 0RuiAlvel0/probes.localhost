#!/bin/bash

clear
echo "Channel tests running"
date

HAS_ERROR="false"
TEST_MESSAGE=""
#1. wait in case other test scripts are running. Need only care about the scripts that go outside:
#https://stackoverflow.com/questions/16807876/how-to-check-if-another-instance-of-my-shell-script-is-running
CLEAR_TO_CONTINUE="false"
for i in 1 2 3 4 5
do
	if  pidof -x "ping_test.sh" >/dev/null  ||  pidof -x "config.sh" >/dev/null || pidof -x "hw_test.sh" >/dev/null || pidof -x "internet.sh" >/dev/null || pidof -x "speed_test.sh" >/dev/null ; then
		TEST_MESSAGE=$TEST_MESSAGE"Probe busy, need to wait 5s | "
		sleep 5
	else
		CLEAR_TO_CONTINUE="true"
		break
	fi
done

if [[ $CLEAR_TO_CONTINUE == "true" ]]; then
	echo "Clear to continue with the test. Proceeding..."
	#2. check current channel
	#iwlist wlan0 channel will end with "Current Frequency: 5.805 GHz" or will not have this line if not connected
	#sudo iwlist wlan0 scanning | egrep 'Cell |Encryption|Quality|Last beacon|ESSID|Frequency|Channel'



else
	HAS_ERROR="true"
	ERROR_MESSAGE="Probe busy. Could not perform test."
fi


#2. check current channel
	#is it 2.4G or 5G?
	#has_error?
	#get connection speed to AP (Mb/s)
	#get signal quality (%)
#3. take NIC down
	#restrict to not the current channel frequency
#4. take NIC up
	#has_erro?
	#get connection speeed to AP (Mb/s)
	#get signal quality (%)
#5. take NIC down
	#remove frequency contrains
#6. take NIC up
#7. send info to server or log error
