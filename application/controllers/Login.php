<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Aux_functions_model');
	}

	function index(){
		//This is the function that runs everytime users go to base_url directly
		if(!$this->Aux_functions_model->is_logged_in()){
			$data[''] = '';
			$this->load->view('Login_view',$data);
		}
		else
			redirect('dashboard');
	}

	function validate(){
		//receive user credentials and look for the user on the database:
		date_default_timezone_set('UTC');
		$this->load->model('Users_model');
		$output = array();
		$output['error'] = false;
		$output['error_description'] = '';
		$output['validated'] = false;
		$log_message = '';
		if($result = $this->Users_model->validate(trim($this->input->post('u')),trim($this->input->post('p')))){
			$output['validated'] = true;
			$this->session->set_userdata('is_logged_in',true);
			$this->session->set_userdata('user_id',$result);
			$user_data = $this->Users_model->get_user_data($result);
			foreach($user_data as $row){
				$this->session->set_userdata('team_id',$row['team_id']);
				$user_id = $row['id'];
				$last_login = $row['last_login'];
				$user_email = $row['email'];
				$date_register = $row['date_register'];
				if(trim($this->input->post('p')) == $row['recovery_password']){
					$new_user_data['password'] = trim($this->input->post('p'));
					$new_user_data['recovery_password'] = '';
					$new_user_data['recovery_requested_date'] = 0;
				}
			}
			//update the user table:
			$new_user_data['last_login'] = time();
			$this->Users_model->edit($new_user_data,$user_id);
		}
		else{
			$output['error'] = true;
			$output['error_description'] = 'Invalid credentials';
		}
		echo json_encode($output);
	}

	function ajax_recover(){
		date_default_timezone_set('UTC');
		$output['error'] = false;
		$output['error_description'] = '';
		$email = trim($this->input->post('u'));
		$this->load->model('Users_model');
		if($user_id = $this->Users_model->email_exists($email)){
			$this->load->model('Aux_functions_model');
			$password = $this->Aux_functions_model->gen_random_string();
			$edit_user_data['recovery_password'] = md5($password);
			$edit_user_data['recovery_requested_date'] = time();
			$this->Users_model->edit($edit_user_data,$user_id);
			$this->load->library('email');
			$this->email->set_mailtype('html');
			$this->email->from('toolist@commacmms.com','toolist');
			$this->email->to($email);
			$this->email->subject('Password recovery');
			$view_data['password'] = $password;
			$body = $this->load->view('emails/recover_pass',$view_data,true);
			$this->email->message($body);
			$this->email->send();
		}
		else{
			$output['error'] = true;
			$output['error_description'] = 'Are you sure about that address?';
		}
		echo json_encode($output);
	}

	function logout(){
		if($this->Aux_functions_model->is_logged_in())
			$this->session->sess_destroy();
		redirect('login');
	}
}
