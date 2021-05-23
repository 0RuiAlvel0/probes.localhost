<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Probes extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Aux_functions_model');
    if(!$this->Aux_functions_model->is_logged_in())
      redirect('login');
	}

	function index(){
    $data['main_content'] = 'Probes_view';
	  $this->load->view('includes/Template',$data);
	}

	function ajax_get_last_test(){
		$output['error'] = false;
		$output['error_description'] = '';

		$probe_id = $this->input->post('id');

		//get last speed test results
		$output['ping_time'] = 'Not available';
		$output['packets_sent'] = 'Not available';
		$output['packets_received'] = 'Not available';
		$output['packet_loss'] = 'Not available';
		$output['max_time'] = 'Not available';
		$output['min_time'] = 'Not available';
		$output['average_time'] = 'Not available';
		$output['ping_server'] = 'Not available';
		$output['average_latency'] = 'Not available';
		$this->load->model('Probes_tests_ping_model');
		if($last_ping_test = $this->Probes_tests_ping_model->get_last_ping_test($probe_id)){
			$output['ping_time'] = date('H:i:s D dS M, Y', $last_ping_test->server_time + 13 * 60 * 60);
			$output['packets_sent'] = $last_ping_test->num_packets_tx;
			$output['packets_received'] = $last_ping_test->num_packets_rv;
			$output['packet_loss'] = $last_ping_test->packet_loss;
			$output['max_time'] = $last_ping_test->max_time.' ms';
			$output['min_time'] = $last_ping_test->min_time.' ms';
			$output['average_time'] = $last_ping_test->average_time.' ms';
			$output['average_latency'] = $last_ping_test->latency_average_time.' ms';
			$this->load->model('Probes_tests_ping_details_model');
			$output['ping_server'] = $this->Probes_tests_ping_details_model->get_ping_server_for_test($last_ping_test->id);
		}

		$output['speed_time'] = 'Not available';
		$output['speed_download_span'] = 'Not available';
		$output['speed_upload_span'] = 'Not available';
		$output['speed_server_span'] = 'Not available';
		$this->load->model('Probes_tests_speed_model');
		if($last_speed_test = $this->Probes_tests_speed_model->get_last_speed_test($probe_id)){
			$output['speed_time'] = date('H:i:s D dS M, Y', $last_speed_test->server_time + 13 * 60 * 60);
			$output['speed_download_span'] = $last_speed_test->speeds_download.' Mbps';
			$output['speed_upload_span'] = $last_speed_test->speeds_upload.' Mbps';
			$output['speed_server_span'] = $last_speed_test->server_host.' ('.$last_speed_test->server_country.')';
		}

		$this->load->model('Probes_config_model');
		if($this->Probes_config_model->probe_config_data($probe_id)->ping_test_freq == 0)
			$output['ping_test_status'] = 'Ping test disabled';
		else
			$output['ping_test_status'] = 'Ping test every '.$this->Probes_config_model->probe_config_data($probe_id)->ping_test_freq.' m';
		if($this->Probes_config_model->probe_config_data($probe_id)->speed_test_freq == 0)
			$output['speed_test_status'] = 'Speed test disabled';
		else
			$output['speed_test_status'] = 'Speed test every '.$this->Probes_config_model->probe_config_data($probe_id)->speed_test_freq.' m';

		echo json_encode($output);
	}

  function ajax_load_probe_list(){
    $output['error'] = false;
	$output['error_description'] = '';

    //get all probes on the system for this organization:
    $output['num_results'] = 0;
	$this->load->model('Probes_model');
    if($probes_list = $this->Probes_model->get_list_for_team($this->session->userdata('team_id'))){
		foreach($probes_list as $probes_list_row){
			$output[$output['num_results']]['id'] = $probes_list_row->id;
			$output[$output['num_results']]['name'] = $probes_list_row->name;

			//GENERAL HW INFORMATION TO SEND OUT
			$output[$output['num_results']]['last_hw_test_data'] = 'No HW information yet';
			$output[$output['num_results']]['type'] = 'Syncing';

			$this->load->model('Probes_hw_tests_model');
			if($last_hw_test_data = $this->Probes_hw_tests_model->get_last_test($probes_list_row->id)){

				if($last_hw_test_data->iface == 'eth0' || $last_hw_test_data->iface == 'enp1s0')
					$output[$output['num_results']]['type'] = 'WIRED';
				else
					$output[$output['num_results']]['type'] = 'WIRELESS';

				//build the last test info to add to the probes list:
				$output[$output['num_results']]['last_hw_test_data'] = 'Last check-in <strong>'.date('H:i:s D dS M, Y', $last_hw_test_data->server_time + 13 * 60 * 60).'</strong> ';
				$output[$output['num_results']]['last_hw_test_data'] .= ' Ext IP <strong>'.$last_hw_test_data->external_ip.'</strong> Loc IP <strong>'.$last_hw_test_data->local_ip.'</strong>';
			}

			$this->load->model('Probes_config_model');
			if($this->Probes_config_model->probe_config_data($probes_list_row->id)->ping_test_freq == 0)
				$output[$output['num_results']]['name'] .= '<br /> Ping test disabled';
			else
				$output[$output['num_results']]['name'] .= '<br /> Ping test every '.$this->Probes_config_model->probe_config_data($probes_list_row->id)->ping_test_freq.' m';
			if($this->Probes_config_model->probe_config_data($probes_list_row->id)->speed_test_freq == 0)
				$output[$output['num_results']]['name'] .= '<br /> Speed test disabled';
			else
				$output[$output['num_results']]['name'] .= '<br /> Speed test every '.$this->Probes_config_model->probe_config_data($probes_list_row->id)->speed_test_freq.' m';

			//GENERAL PING TEST INFORMATION TO SEND OUT
			$output[$output['num_results']]['last_ping_test_data'] = ' | No ping test information yet';
			$this->load->model('Probes_tests_ping_model');
			if($last_ping_test_data = $this->Probes_tests_ping_model->get_last_ping_test($probes_list_row->id)){
				$output[$output['num_results']]['last_ping_test_data'] = ' | Last ping <strong>'.date('H:i:s D dS M, Y', $last_ping_test_data->server_time + 13 * 60 * 60).'</strong>';
				$output[$output['num_results']]['last_ping_test_data'] .= ' Packet loss <strong>'.$last_ping_test_data->packet_loss.' in '.$last_ping_test_data->num_packets_tx.' tests</strong>';
			}

			//GENERAL SPEED TEST INFORMATION TO SEND OUT
			$output[$output['num_results']]['last_speed_test_data'] = ' | No speed test information yet';
			$this->load->model('Probes_tests_speed_model');
			if($last_speed_test_data = $this->Probes_tests_speed_model->get_last_speed_test($probes_list_row->id)){
				$output[$output['num_results']]['last_speed_test_data'] = ' | Last speed <strong>'.date('H:i:s D dS M, Y', $last_speed_test_data->server_time + 13 * 60 * 60).'</strong>';
				$output[$output['num_results']]['last_speed_test_data'] .= ' UL <strong>'.$last_speed_test_data->speeds_upload.'Mbps</strong> DL<strong> '.$last_speed_test_data->speeds_download.'Mbps</strong>' ;
			}

			//for probe type, get the last probe test:
			$output[$output['num_results']]['hw_status'] = '<span class="label label-warning">Syncing</span>';
			$this->load->model('Probes_config_model');
			if($config_data=$this->Probes_config_model->probe_config_data($probes_list_row->id)){
				if(!$config_data->last_config_contact)
					$output[$output['num_results']]['hw_status'] = '<span class="label label-warning">Wait</span>';
				elseif(time() >= $config_data->last_config_contact + 61)
					$output[$output['num_results']]['hw_status'] = '<span class="label label-danger">Offline</span>';
				else
					$output[$output['num_results']]['hw_status'] = '<span class="label label-success">Online</span>';
			}
			$output[$output['num_results']]['hw_status'] .= ' <br /><small>'.$config_data->server_1.'</small>';

			//GENERAL INTERNET INFORMATION TO SEND OUT
			$this->load->model('probes_tests_internet_model');
			if($internet_data = $this->probes_tests_internet_model->get_last_checkin($probes_list_row->id)){
				if(time() >= $internet_data->server_time + 65)
					$output[$output['num_results']]['internet_status'] = '<span class="label label-danger">Offline</span>';
				else
					$output[$output['num_results']]['internet_status'] = '<span class="label label-success">Online</span>';
			}
			else
				$output[$output['num_results']]['internet_status'] = '<span class="label label-danger">Offline</span>';
        $output[$output['num_results']]['cat'] = '14th Floor';
        $output['num_results']++;
      }
    }
    echo json_encode($output);
  }

	function ajax_get_probes_stats(){
		$output['error'] = false;
		$output['error_description'] = '';

		$output['total_probes'] = 0;
		$output['num_wait'] = 0;
		$output['percentage_wait'] = 0;
		$output['num_online'] = 0;
		$output['percentage_online'] = 0;
		$output['num_offline'] = 0;
		$output['percentage_offline'] = 0;

		$this->load->model('Probes_model');
    if($probes_list = $this->Probes_model->get_list_for_team($this->session->userdata('team_id'))){
			$this->load->model('Probes_config_model');
			foreach($probes_list as $probes_list_row){
				$output['total_probes']++;
				$config_data = $this->Probes_config_model->probe_config_data($probes_list_row->id);
				if($config_data){
					if(!$config_data->last_config_contact)
						$output['num_wait']++;
					elseif(time() >= $config_data->last_config_contact + 60){
						$output['num_offline']++;
					}
					elseif(time() < $config_data->last_config_contact + 60){
						$output['num_online']++;
					}
				}
				else{
					$output['error'] = true;
					$output['error_description'] = 'Error getting data';
				}
			}
			if($output['total_probes']){
				$output['percentage_online'] = round($output['num_online']/$output['total_probes'],1) * 100;
				$output['percentage_offline'] = round($output['num_offline']/$output['total_probes'],1) * 100;
				$output['percentage_wait'] = round($output['num_wait']/$output['total_probes'],1) * 100;
			}
		}
		else{
			$output['error'] = true;
			$output['error_description'] = 'No probes.';
		}
		//get total number of probes

		//get number and percentage of probes Online

		//get number and percentage of probes offline

		echo json_encode($output);
	}

	function ajax_get_probe_data(){
		$output['error'] = false;
		$output['error_description'] = '';

		$probe_id = $this->input->post('id');

		$this->load->model('Probes_model');
		if($probe_data = $this->Probes_model->probe_data($probe_id)){
			$output['id'] = $probe_data->id;

			//general probe data
			$output['w_mac'] = $probe_data->w_mac;
			$output['wl_mac'] = $probe_data->wl_mac;
			$output['w_write_key'] = $probe_data->w_write_key;
			$output['wl_write_key'] = $probe_data->wl_write_key;
			$output['name'] = $probe_data->name;

			$this->load->model('Probes_config_model');
			$config_data = $this->Probes_config_model->probe_config_data($output['id']);

			//configuration server data
			$output['config_id'] = $config_data->id;
			$output['server_1'] = $config_data->server_1;

			//ping test data
			$output['ping_server'] = $config_data->ping_server;
			$output['num_ping_tests'] = $config_data->num_ping_tests;
			$output['ping_test_freq'] = $config_data->ping_test_freq;

			//Speed test data
			$output['speed_test_server'] = $config_data->speed_test_server;
			$output['speed_test_freq'] = $config_data->speed_test_freq;

			//Channel test data
			$output['channel_test_freq'] = $config_data->channel_test_freq;
			$output['test_2'] = $config_data->test_2;
			$output['test_5'] = $config_data->test_5;

			$output['wifiap'] = $config_data->wifiap;
			$output['wifiun'] = $config_data->wifiun;
			$output['wifipw'] = $config_data->wifipw;
		}
		else{
			$output['error'] = true;
			$output['error_description'] = 'No such probe';
		}
		echo json_encode($output);
	}

	function ajax_edit_probe(){
		$output['error'] = false;
		$output['error_description'] = '';

		$config_id = $this->input->post('config_id');
		$probe_id = $this->input->post('probe_id');
		//validate values
		$name = trim($this->input->post('name'));
		if($name != '' && strlen($name) < 100){
			$server_1 = trim($this->input->post('server_1'));

			if($server_1 != ''){
				$pattern = '/(?:http?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/';
				if(preg_match($pattern, $server_1)){
					$ping_server = $this->input->post('ping_server');
					$speed_test_server = $this->input->post('speed_test_server');
					if(preg_match($pattern, $ping_server) || filter_var($ping_server, FILTER_VALIDATE_IP)){
						//if(preg_match($pattern, $speed_test_server) || filter_var($speed_test_server, FILTER_VALIDATE_IP)){
						if(1){
							$speed_test_freq = trim($this->input->post('speed_test_freq'));
							$ping_test_freq = trim($this->input->post('ping_test_freq'));
							$num_ping_tests = $this->input->post('num_ping_tests');
							$ping_test_enabled = $this->input->post('p_en');
							$speed_test_enabled = $this->input->post('s_en');
							$channel_test_enabled = trim($this->input->post('c_en'));
							$channel_test_freq = trim($this->input->post('c_f'));
							$continue_speed = false;
							if(($speed_test_freq == 0 && $speed_test_enabled == 'false') || ($speed_test_freq != 0 && $speed_test_enabled == 'true' && $speed_test_freq >= 5 && $speed_test_freq < 60))
								$continue_speed = true;
							$continue_ping = false;
							if(($ping_test_freq == 0 && $ping_test_enabled == 'false') || ($ping_test_freq != 0 && $ping_test_enabled == 'true' && $ping_test_freq >= 2 && $speed_test_freq < 60))
								$continue_ping = true;
							$continue_channel = false;
							if(($channel_test_freq == 0 && $channel_test_enabled == 'false') || ($channel_test_freq != 0 && $channel_test_enabled == 'true' && $channel_test_freq >= 15 && $channel_test_freq < 60))
								$continue_channel = true;
							if (!(!is_numeric($speed_test_freq) || $speed_test_freq != round($speed_test_freq) || !$continue_speed)){
								if (!(!is_numeric($ping_test_freq) || $ping_test_freq != round($ping_test_freq) || !$continue_ping)){
									if (!(!is_numeric($channel_test_freq) || $channel_test_freq != round($channel_test_freq) || !$continue_channel)){
										if (!(!is_numeric($num_ping_tests) || $num_ping_tests < 0 ||  $num_ping_tests >= 10 || $num_ping_tests != round($num_ping_tests))){
											$wifiun = $this->input->post('wifiun');
											$wifipw = $this->input->post('wifipw');
											$wifiap = $this->input->post('wifiap');
											//get data on old row
											$this->load->model('Probes_model');
											$current_data = $this->Probes_model->probe_data($probe_id);
											$new_probe_data['cat_id'] = $current_data->cat_id;
											$new_probe_data['team_id'] = $current_data->team_id;
											$new_probe_data['w_mac'] = $current_data->w_mac;
											$new_probe_data['wl_mac'] = $current_data->wl_mac;
											$new_probe_data['w_write_key'] = $current_data->w_write_key;
											$new_probe_data['wl_write_key'] = $current_data->wl_write_key;
											$new_probe_data['name'] = $name;
											$this->Probes_model->edit($new_probe_data, $probe_id);

											//$wifichannel can contain "both", "2only" and "5only"
											$wifichannel = $this->input->post('wifi');
											$new_config['test_2'] = false;
											$new_config['test_5'] = false;
											if($wifichannel == 'both'){
												$new_config['test_2'] = true;
												$new_config['test_5'] = true;
											}
											if($wifichannel == '2only'){
												$new_config['test_2'] = true;
											}
											if($wifichannel == '5only'){
												$new_config['test_5'] = true;
											}

											$this->load->model('Probes_config_model');
											$new_config['probe_id'] = $probe_id;
											$new_config['server_1'] = $server_1;
											$new_config['speed_test_freq'] = $speed_test_freq;
											$new_config['ping_test_freq'] = $ping_test_freq;
											$new_config['channel_test_freq'] = $channel_test_freq;
											$new_config['ping_server'] = $ping_server;
											$new_config['num_ping_tests'] = $num_ping_tests;
											$new_config['speed_test_server'] = $speed_test_server;
											$new_config['wifiap'] = $wifiap;
											$new_config['wifiun'] = $wifiun;
											$new_config['wifipw'] = $wifipw;
											$new_config['last_config_contact'] = 0;
											//create new row
											$output['config_id'] = $this->Probes_config_model->add($new_config);
											//delete old row
											$this->Probes_config_model->delete($config_id);
											$output['id'] = $probe_id;
										}
										else{
											$output['error'] = true;
											$output['error_description'] = 'The number of ping tests must be an integer >= 0  and <= 10';
										}
									}
									else{
										$output['error'] = true;
										$output['error_description'] = 'The channel test frequency must be an integer >= 15 minutes and < 60 minutes';
									}
								}
								else{
									$output['error'] = true;
									$output['error_description'] = 'The ping test frequency must be an integer >= 2 minutes and < 60 minutes';
								}
							}
							else{
								$output['error'] = true;
								$output['error_description'] = 'The speed test frequency must be an integer >= 5 minutes and < 60 minutes';
							}
						}
						else{
							$output['error'] = true;
							$output['error_description'] = 'Speed test server must be valid ID';
						}
					}
					else{
						$output['error'] = true;
						$output['error_description'] = 'Ping test server must be valid url or IP address';
					}
				}
				else{
					$output['error'] = true;
					$output['error_description'] = 'Incorrect server URL (server_1 or db_server_1)';
				}
			}
			else{
				$output['error'] = true;
				$output['error_description'] = 'You must specify at least server and a database server';
			}
		}
		else{
			$output['error'] = true;
			$output['error_description'] = 'Please enter a non empty name with less than 100 characters';
		}
		echo json_encode($output);
	}

	function ajax_add_probe(){
		$output['error'] = false;
		$output['error_description'] = '';

		$w_mac_address = $this->input->post('w_mac');
		$wl_mac_address = $this->input->post('wl_mac');

		//mac address must be of the format: 0A-14-EE-01-23-45
		$aux_1 = explode(':',$w_mac_address);
		$aux_2 = explode(':',$wl_mac_address);
		if(count($aux_1) == 6 && count(	$aux_2) == 6 && $w_mac_address != $wl_mac_address){
			//ensure MAC addres doesn't exist on the system:
			$this->load->model('Probes_config_model');
			$this->load->model('Probes_model');
			if(!($this->Probes_model->mac_exists($w_mac_address) && $this->Probes_model->mac_exists($wl_mac_address))){
				//generate a write key and add combination to allowed systems on database:
				$this->load->model('Aux_functions_model');
				$new_probe_data['team_id'] = $this->session->userdata('team_id');
				$new_probe_data['cat_id'] = 0;
				$new_probe_data['name'] = 'w: '.$w_mac_address.' wl: '.$wl_mac_address;
				$new_probe_data['w_mac'] = $w_mac_address;
				$new_probe_data['wl_mac'] = $wl_mac_address;
				$new_probe_data['w_write_key'] = $this->Aux_functions_model->gen_random_string();
				$new_probe_data['wl_write_key'] = $this->Aux_functions_model->gen_random_string();
				//add infor to probes_model
				$probe_id = $this->Probes_model->add($new_probe_data);
				$new_probe_config_data['probe_id'] = $probe_id;
				$new_probe_config_data['server_1'] = 'https://app.commacmms.com';
				//configuration frequency of one minute by default
				$new_probe_config_data['ping_test_freq'] = 0;
				$new_probe_config_data['num_ping_tests'] = 2;
				//test interval set to 10 minutes by default
				$new_probe_config_data['speed_test_freq'] = 0;
				//The default server to ping
				$new_probe_config_data['ping_server'] = 'www.google.com';
				//0 means we will decide automatically
				$new_probe_config_data['speed_test_server'] = 1849;
				$new_probe_config_data['test_2'] = true;
				$new_probe_config_data['test_5'] = false;
				//place this info on the database:
				if(!$this->Probes_config_model->add($new_probe_config_data)){
					$output['error'] = true;
					$output['error_description'] = 'ERROR: Something went wrong when adding information to database. Try again later';
				}
				else{
					$output['w_write_key'] = $new_probe_data['w_write_key'];
					$output['wl_write_key'] = $new_probe_data['wl_write_key'];
				}
			}
			else{
				$output['error'] = true;
				$output['error_description'] = 'ERROR: That MAC address already exists';
			}
		}
		else{
			$output['error'] = true;
			$output['error_description'] = 'ERROR: One of the MAC addresses you entered is incorrect';
		}
		echo json_encode($output);
	}
}
