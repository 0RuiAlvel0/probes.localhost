<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Aux_functions_model');
		if(!$this->Aux_functions_model->is_logged_in())
		  redirect('login');
	}

	function index(){
	
	//send the list of probes defined on the system:
	$this->load->model('Probes_model');
	
	$probes = false;
	if($probes_list = $this->Probes_model->get_list_for_team($this->session->userdata('team_id'))){
		foreach($probes_list as $probes_list_row)
			$probes[$probes_list_row->id] = $probes_list_row->name; 
	}
	$data['probes'] = $probes;
	
    $data['main_content'] = 'Stats_view';
	  $this->load->view('includes/Template',$data);
	}
	
	function ajax_get_chart_data(){
		$data['error'] = false;
		$data['error_message'] = '';
		
		$probe_id = $this->input->post('p_id');
		$chart_date = $this->input->post('d');
		$chart_date = explode('-', $chart_date);
		
		//just translate the date above into unix start and end time 
		$start = mktime(0, 0, 0, $chart_date[1], $chart_date[0], $chart_date[2]) - 13 * 60 * 60;
		$end = mktime(23, 59, 59, $chart_date[1], $chart_date[0], $chart_date[2]) - 13 * 60 * 60;
		
		$data['start'] = date('l jS \o\f M, Y H:i:s', $start);
		$data['end'] = date('l jS \o\f M, Y H:i:s', $end);
		
		//check if probe exists
		$this->load->model('Probes_model');
		if($probe_data = $this->Probes_model->probe_data($probe_id)){
			$this->load->model('Probes_tests_internet_model');
			$this->load->model('Probes_config_tests_model');
			$this->load->model('Probes_tests_ping_model');
			//analyze the day hour per hour
			$hour_counter = 0;
			for($i = $start; $i <= $end; $i = $i + 60 * 60){
				//START BASIC CONNECTIVITY TESTS
				$internet_contacts_per_hour[$hour_counter] = 0;
				$server_contacts_per_hour[$hour_counter] = 0;
				// $aux = $test_results->num_rows();
				// var_dump($test_results);
				if($internet_contacts = $this->Probes_tests_internet_model->get_data_on_interval($probe_id, $i, $i + 60 * 60)){
					$internet_contacts_per_hour[$hour_counter] = $internet_contacts->num_rows();
				}
				if($server_contacts = $this->Probes_config_tests_model->get_data_on_interval($probe_id, $i, $i + 60 * 60)){
					//divided by two because two .sh scripts will update the value
					$server_contacts_per_hour[$hour_counter] = $server_contacts->num_rows()/2;
				}
				//END BASIC CONNECTIVITY TESTS
				
				//START PING TESTS
				$ping_test[$hour_counter]['min_time'] = 0;
				$ping_test[$hour_counter]['average_time'] = 0;
				$ping_test[$hour_counter]['max_time'] = 0;
				$ping_test[$hour_counter]['packet_loss'] = 0;
				if($ping_tests = $this->Probes_tests_ping_model->get_data_on_interval($probe_id, $i, $i + 60 * 60)){
					$ping_tests_counter = 1;
					foreach($ping_tests->result() as $ping_tests_row){
						$ping_test[$hour_counter]['min_time'] = $ping_test[$hour_counter]['min_time'] + $ping_tests_row->min_time;
						$ping_test[$hour_counter]['average_time'] = $ping_test[$hour_counter]['average_time'] + $ping_tests_row->average_time;
						$ping_test[$hour_counter]['max_time'] = $ping_test[$hour_counter]['max_time'] + $ping_tests_row->max_time;
						$ping_test[$hour_counter]['packet_loss'] = $ping_test[$hour_counter]['packet_loss'] + (int)(rtrim($ping_tests_row->packet_loss,'%'));
						$ping_tests_counter++;
					}
					//calculate the hourly averages
					$ping_test[$hour_counter]['min_time'] = $ping_test[$hour_counter]['min_time'] / $ping_tests_counter;
					$ping_test[$hour_counter]['average_time'] = $ping_test[$hour_counter]['average_time'] / $ping_tests_counter;
					$ping_test[$hour_counter]['max_time'] = $ping_test[$hour_counter]['max_time'] / $ping_tests_counter;
					$ping_test[$hour_counter]['packet_loss'] = $ping_test[$hour_counter]['packet_loss'] / $ping_tests_counter;
				}
				//END PING TESTS
				
				//START SPEED TESTS
				$this->load->model('Probes_tests_speed_model');
				$speed_test[$hour_counter]['average_upload'] = 0;
				$speed_test[$hour_counter]['average_download'] = 0;
				if($speed_tests = $this->Probes_tests_speed_model->get_data_on_interval($probe_id, $i, $i + 60 * 60)){
					$speed_tests_counter = 1;
					foreach($speed_tests->result() as $speed_tests_row){
						$speed_test[$hour_counter]['average_upload'] = $speed_test[$hour_counter]['average_upload'] + (float)$speed_tests_row->speeds_upload;
						$speed_test[$hour_counter]['average_download'] = $speed_test[$hour_counter]['average_download'] + (float)$speed_tests_row->speeds_download;
						$speed_tests_counter++;
					}
					$speed_test[$hour_counter]['average_upload'] = $speed_test[$hour_counter]['average_upload'] / $speed_tests_counter;
					$speed_test[$hour_counter]['average_download'] = $speed_test[$hour_counter]['average_download'] / $speed_tests_counter;
				}
				//END SPEED TESTS
				$hour_counter++;
			}
			$data['internet_contacts_per_hour'] = $internet_contacts_per_hour;
			$data['server_contacts_per_hour'] = $server_contacts_per_hour;
			$data['ping_tests'] = $ping_test;
			$data['speed_tests'] = $speed_test;
		}
		else{
			$data['error'] = true;
			$data['error_message'] = 'Error: please specify a probe';
		}
		
		echo json_encode($data);
	}

}
