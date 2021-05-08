<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Handler extends CI_Controller {

	function probe_is_registered(){
		$this->load->model('probes_model');
		if(!$probe_id = $this->probes_model->probe_is_registered($this->input->post('mac'), $this->input->post('write_key')))
			return false;
		else
			return $probe_id;
	}

	function notifications(){
		$data['error_description'] = 'OK';
		$data['server_message'] = '';

		$this->load->model('Probes_model');
		if($probes_list = $this->Probes_model->get_list_for_team(1)){
			foreach($probes_list as $probes_list_row){
				//determines if we should send out notifications for probe offline or probe online
				$this->load->model('probes_tests_internet_model');
				if($internet_data = $this->probes_tests_internet_model->get_last_checkin($probes_list_row->id)){
					$this->load->model('Settings_model');
					$settings = $this->Settings_model->get();
					$this->load->model('Probes_config_model');
					$probe_config = $this->Probes_config_model->probe_config_data($probes_list_row->id);
					//echo time().' >= '.($internet_data->server_time + $settings->notification_offline_minutes * 60).' && '.$settings->offline_addresses.' && !'.$probe_config->sent_offline_email;
					if((time() >= $internet_data->server_time + $settings->notification_offline_minutes * 65) && $settings->offline_addresses && !$probe_config->sent_offline_email){
						//SEND OFFLINE NOTIFICATION EMAIL
						$this->load->library('email');
						$email_config['protocol'] = 'smtp';
						$email_config['smtp_host'] = 'your_host_here';
						$email_config['smtp_user'] = 'your_username_here';
						$email_config['smtp_pass'] = 'your_password_here';
						$email_config['smtp_port'] = '465';
						$email_config['smtp_crypto'] = 'ssl';
						$this->email->initialize($email_config);

						$this->email->set_mailtype('html');
						$this->email->from('info@commacmms.com','Network analyser');
						$this->email->to($settings->offline_addresses);
						$this->email->subject($probes_list_row->name.' just went offline');

						$email_data['probe_name'] = $probes_list_row->name;
						$email_data['last_config_contact'] = date('D, jS \o\f F, Y \a\t H:i:s', $internet_data->server_time + 13 * 60 * 60);
						$email_data['allowed_offline_time'] = $settings->notification_offline_minutes;

						$body = $this->load->view('email/offline_notification_email', $email_data, true);
						$this->email->message($body);
						$this->email->send();
						//SET the "sent_offline_email" column on the "probes_config" table
						$edit_probe_config['sent_offline_email'] = true;
						$edit_probe_config['sent_online_email'] = false;
						$this->Probes_config_model->edit($edit_probe_config,$probe_config->id);
						$data['server_message'] = 'Sent offline email';
					}
					elseif(time() <= $internet_data->server_time + 65 && !$probe_config->sent_online_email){
						//SEND ONLINE NOTIFICATION EMAIL
						$this->load->library('email');
						$email_config['protocol'] = 'smtp';
						$email_config['smtp_host'] = 'your_host_here';
						$email_config['smtp_user'] = 'your_username_here';
						$email_config['smtp_pass'] = 'your_password_here';
						$email_config['smtp_port'] = '465';
						$email_config['smtp_crypto'] = 'ssl';

						$email_config['smtp_crypto'] = 'ssl';
						$this->email->initialize($email_config);

						$this->email->set_mailtype('html');
						$this->email->from('info@commacmms.com','Network analyser');
						$this->email->to($settings->offline_addresses);
						$this->email->subject($probes_list_row->name.' just went online');

						$email_data['probe_name'] = $probes_list_row->name;
						$email_data['last_config_contact'] = date('D, jS \o\f F, Y \a\t H:i:s', $internet_data->server_time + 13 * 60 * 60);

						$body = $this->load->view('email/online_notification_email', $email_data, true);
						$this->email->message($body);
						$this->email->send();
						//SET the "sent_offline_email" column on the "probes_config" table
						$edit_probe_config['sent_offline_email'] = false;
						$edit_probe_config['sent_online_email'] = true;
						$this->Probes_config_model->edit($edit_probe_config,$probe_config->id);
						$data['server_message'] = 'Sent online email';
					}
				}
			}
		}
		$this->load->view('Handler_view',$data);
	}

	function internet_test(){
		$data['error_description'] = 'OK';
		$data['server_message'] = '';
		if($probe_id = $this->probe_is_registered()){
			$probe_message = $this->input->post('o');
			$message_array = explode('~***~',$probe_message);

			$new_data['probe_id'] = $probe_id;
			$new_data['server_time'] = time();

			$this->load->model('Probes_tests_internet_model');
			$this->Probes_tests_internet_model->add($new_data);
		}
		else
			$data['error_description'] = 'Client is not registered.';
		$this->load->view('Handler_view',$data);
	}

	function hw_test(){
		$data['error_description'] = 'OK';
		$data['server_message'] = '';
		if($probe_id = $this->probe_is_registered()){
			$probe_message = $this->input->post('o');
			$message_array = explode('~***~',$probe_message);

			$new_data['probe_id'] = $probe_id;
			//essid not retrieved at the moment
			$new_data['essid'] = "";
			$new_data['server_time'] = time();

			for($i = 1; $i <= 7; $i++){
				$aux = explode('=', $message_array[$i]);
				if($i == 1)
					$new_data['iface'] = $aux[1];
				elseif($i == 2)
					$new_data['mac'] = $aux[1];
				elseif($i == 4)
					$new_data['timestamp'] = $aux[1];
				elseif($i == 5)
					$new_data['uptime'] = $aux[1];
				elseif($i == 6)
					$new_data['local_ip'] = $aux[1];
				elseif($i == 7)
					$new_data['external_ip'] = $aux[1];
			}
			//~***~IFACE=wlan0~***~MAC=0~***~KEY=0~***~TS=Mon Jul 15 17:43:07 CST 2019~***~
			//UPTIME= 17:43:07 up 2 days, 7:05, 6 users, load average: 0.01, 0.07, 0.08~***~
			//LOCALIP=192.168.1.112 ~***~EXTERNALIP=60.246.207.223
			$this->load->model('Probes_hw_tests_model');
			$this->Probes_hw_tests_model->add($new_data);
		}
		else
			$data['error_description'] = 'Client is not registered.';
			$this->load->view('Handler_view',$data);
	}

	function ping_test(){
		$data['error_description'] = 'OK';
		$data['server_message'] = '';
		if($probe_id = $this->probe_is_registered()){
			$probe_message = $this->input->post('o');
			//fields are separated by "~***~"
			$message_array = explode('~***~',$probe_message);
			//$data['server_message'] = print_r($message_array);
			$new_test_data['probe_id'] = $probe_id;
			$new_test_data['server_time'] = time();

			$new_test_data['has_ping_error'] = false;
			$aux = explode('=', $message_array[1]);
			if($aux == 'true'){
				//has_ping_error
				$new_test_data['has_ping_error'] = true;
				$aux = explode('=', $message_array[2]);
				$new_test_data['ping_error_description'] = $aux[1];
			}
			else{
				//no ping test error detected
				$aux = explode('=', $message_array[3]);
				$new_test_data['num_packets_tx'] = $aux[1];

				$aux = explode('=', $message_array[4]);
				$new_test_data['num_packets_rv'] = $aux[1];

				$aux = explode('=', $message_array[5]);
				$new_test_data['packet_loss'] = $aux[1];

				$aux = explode('=', $message_array[6]);
				$new_test_data['min_time'] = $aux[1];

				$aux = explode('=', $message_array[7]);
				$new_test_data['average_time'] = $aux[1];

				$aux = explode('=', $message_array[8]);
				$new_test_data['max_time'] = $aux[1];

				$aux = explode('=', $message_array[9]);
				$new_test_data['resolved_ip'] = $aux[1];

				//new packet latency test results:
				$aux = explode('=', $message_array[12]);
				$new_test_data['latency_min_time'] = $aux[1];

				$aux = explode('=', $message_array[13]);
				$new_test_data['latency_average_time'] = $aux[1];

				$aux = explode('=', $message_array[14]);
				$new_test_data['latency_max_time'] = $aux[1];
			}

			//place general information on probes_tests_ping table:
			$this->load->model('Probes_tests_ping_model');
			$test_id = $this->Probes_tests_ping_model->add($new_test_data);

			if(!$new_test_data['has_ping_error']){
				$aux = explode('=', $message_array[10]);
				//echo 'HERE'.$aux[1];
				$ping_data = json_decode($aux[1]);
				//var_dump($ping_data);
				$this->load->model('Probes_tests_ping_details_model');
				foreach($ping_data as $key=>$val){
					$new_ping_test_data['test_id'] = $test_id;
					$new_ping_test_data['bytes'] = $val->BYTES;
					$new_ping_test_data['from_server'] = $val->FROM;
					$new_ping_test_data['icmp_seq'] = $key;
					$new_ping_test_data['ttl'] = $val->TTL;
					$new_ping_test_data['time'] = $val->TIME;
					$new_ping_test_data['time_units'] = 'ms';
					$this->Probes_tests_ping_details_model->add($new_ping_test_data);
				}
			}
			//place information on probe_tests_ping table
			//~***~PING_ERROR=false~***~PING_ERROR_DESCRIPTION=~***~PING_PACKETS_TRANSMITTED=2~***~
			//PING_PACKETS_RECEIVED=2~***~PING_PACKET_LOSS=0%~***~PING_MIN_TIME=31.607~***~PING_AVERAGE_TIME=36.620
			//~***~PING_MAX_TIME=41.634~***~PING_RESOLVED_IP=124.108.103.104~***
			//~PING_RESULTS_JSON={"1":{"BYTES":64,"FROM":"media-router-fp2.prod1.media.vip.tp2.yahoo.com (124.108.103.104)","TTL":55,"TIME":31.6,"TIME_UNITS"},"2":{"BYTES":64,"FROM":"media-router-fp2.prod1.media.vip.tp2.yahoo.com (124.108.103.104)","TTL":55,"TIME":41.6,"TIME_UNITS"}}~***~PING_RESOLVED_SERVER=media-router-fp2.prod1.media.vip.tp2.yahoo.com (124.108.103.104)d;
		}
		else
			$data['error_description'] = 'Client is not registered.';
			$this->load->view('Handler_view',$data);
	}

	function speed_test(){
		$data['error_description'] = 'OK';
		$data['server_message'] = '';
		if($probe_id = $this->probe_is_registered()){

			$probe_message = $this->input->post('o');
			$probe_message = str_replace('\'','', $probe_message);
			//fields are separated by "~***~"
			$message_array = explode('~***~',$probe_message);

			$new_speed_test_data['probe_id'] = $probe_id;
			$new_speed_test_data['server_time'] = time();

			$new_speed_test_data['has_speed_error'] = false;
			$aux = explode('=', $message_array[1]);
			if($aux == 'true'){
				//has_speed_error
				$new_speed_test_data['has_speed_error'] = true;
				$aux = explode('=', $message_array[2]);
				$new_speed_test_data['speed_error_description'] = $aux[1];
			}
			else{
				//no speed test error: proceed
				//no ping test error detected
				$aux = explode('=', $message_array[3]);

				// //START SECTION 1: SPEEDS
				$aux_1 = explode('download: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['speeds_download'] = $aux_1[0];

				$aux_1 = explode('upload: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['speeds_upload'] = $aux_1[0];

				$aux_1 = explode('originalDownload: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['speeds_originaldownload'] = $aux_1[0];

				$aux_1 = explode('originalUpload: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['speeds_originalupload'] = rtrim($aux_1[0], '}');

				// //START SECTION 1: CLIENT

				// //START SECTION 1: SERVER
				$aux_1 = explode('host: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['server_host'] = $aux_1[0];

				$aux_1 = explode('location: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['server_location'] = $aux_1[0];

				$aux_1 = explode('country: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['server_country'] = $aux_1[0];

				$aux_1 = explode('cc: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['server_cc'] = $aux_1[0];

				$aux_1 = explode('sponsor: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['server_sponsor'] = $aux_1[0];

				$aux_1 = explode('sponsor: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['server_sponsor'] = $aux_1[0];

				$aux_1 = explode('ping: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['server_ping'] = $aux_1[0];

				$aux_1 = explode('id: ',$aux[1]);
				$aux_1 = explode(',', $aux_1[1]);
				$new_speed_test_data['server_id'] = rtrim($aux_1[0],' } }');

				$this->load->model('Probes_tests_speed_model');
				$this->Probes_tests_speed_model->add($new_speed_test_data);
				//~***~SPEED_TEST_ERROR=false~***~SPEED_TEST_ERROR_DESCRIPTION=~***~
				//SPEED_TEST_RESULTS={ speeds: { download: 0.405, upload: 0.089, originalDownload: 44642, originalUpload: 9767 },
				//client: { lat: NaN, lon: NaN, isprating: NaN, rating: NaN, ispdlavg: NaN, ispulavg: NaN },
				//server: { host: 'speedo.eltele.no:8080', lat: 69.9403, lon: 23.3106, location: 'Alta', country: 'Norway',
				//cc: 'NO', sponsor: 'Eltele AS', distance: NaN, distanceMi: NaN, ping: 627.6, id: '3433' } }
			}
		}
		else
			$data['error_description'] = 'Client is not registered.';
		$this->load->view('Handler_view',$data);
	}

	function index(){
		$data['error_description'] = '';
		$data['server_message'] = '';
		if($probe_id = $this->probe_is_registered())
			$data['server_message'] = $probe_id;
		else
			$data['error_description'] = 'Client is not registered.';
		$this->load->view('Handler_view',$data);
	}

  function version(){
    $data['error_description'] = '';
    $data['server_message'] = '';

    if($probe_id = $this->probe_is_registered()){
      $this->load->model('Probes_config_model');
      $config_id = $this->Probes_config_model->probe_config_data($probe_id)->id;
      $data['server_message'] = 'CONFIG_ID='.$config_id;

	  //put information on the connectivity table
	  //Used to assess if there was connectivity or not
	  $new_values = array();
	  $this->load->model('Probes_config_tests_model');
	  $new_values['probe_id'] = $probe_id;
	  $new_values['check_in_date'] = time();
	  $this->Probes_config_tests_model->add($new_values);

      //update last contact time:
	  $new_values = array();
      $new_values['last_config_contact'] = time();
      $this->Probes_config_model->edit($new_values, $config_id);
    }
    else
      $data['error_description'] = 'Client is not registered.';

    $this->load->view('Handler_view',$data);
  }

  function update(){
    $data['error_description'] = '';
    $data['server_message'] = '';

    if($probe_id = $this->probe_is_registered()){
      //get configuration variables:
      $this->load->model('Probes_config_model');
      $config_data = $this->Probes_config_model->probe_config_data($probe_id);
      $output['CONFIG_ID'] = $config_data->id;
      //SERVER_1
      $output['SERVER_1'] = $config_data->server_1;
      //CONFIG_FREQ
      $output['PING_TEST_FREQ'] = $config_data->ping_test_freq;
      //FREQ
      $output['SPEED_TEST_FREQ'] = $config_data->speed_test_freq;
	  //CHANNEL TEST
	  $output['CHANNEL_TEST_FREQ'] = $config_data->channel_test_freq;
	  $output['TEST_2'] = 'NO';
	  if($config_data->test_2)
		$output['TEST_2'] = 'YES';
	  $output['TEST_5'] = 'NO';
	  if($config_data->test_5)
		$output['TEST_5'] = 'YES';
      //PING_SERVER
      $output['PING_SERVER'] = $config_data->ping_server;
      //SPEED_TEST_SERVER
      $output['SPEED_TEST_SERVER'] = $config_data->speed_test_server;
      //WIFIAP
      $output['WIFIAP'] = $config_data->wifiap;
      //WIFIUN
      $output['WIFIUN'] = $config_data->wifiun;
      //WIFIPW
      $output['WIFIPW'] = $config_data->wifipw;
      //NUM_PING_TESTS
      $output['NUM_PING_TESTS'] = $config_data->num_ping_tests;

      $data['server_message'] = json_encode($output);
      //update last contact time:
      $this->load->model('Probes_config_model');
      $new_values['last_config_contact'] = time();
      $this->Probes_config_model->edit($new_values,$config_data->id);
    }
    else
      $data['error_description'] = 'Client is not registered.';

    $this->load->view('Handler_view',$data);
  }

}
