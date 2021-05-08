<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Aux_functions_model');
    if(!$this->Aux_functions_model->is_logged_in())
      redirect('login');
	}

	function index(){
	
		//load current settings, if any:
		$this->load->model('Settings_model');
		if(!$data['settings'] = $this->Settings_model->get())
			$data['settings'] = false;
	
		$data['main_content'] = 'Settings_view';
		$this->load->view('includes/Template',$data);
	}
	
	function ajax_save(){
		$data['error'] = false;
		//m minute interval after which we send a notification email
		$settings['notification_offline_minutes'] = $this->input->post('m');
		//off list of emails to send a notification to when probe is offline
		$settings['offline_addresses'] = trim($this->input->post('off'));
		//on list of email to send a notification to when the probe is back online
		$settings['online_addresses'] = trim($this->input->post('on'));
		
		$this->load->model('Settings_model');
		if(!$this->Settings_model->update($settings))
			$data['error'] = true;
		
		echo json_encode($data);
	}

}
