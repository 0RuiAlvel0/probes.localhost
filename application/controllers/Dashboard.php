<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Aux_functions_model');
    if(!$this->Aux_functions_model->is_logged_in())
      redirect('login');
	}

	function index(){
    $data['main_content'] = 'Dashboard_view';
	  $this->load->view('includes/Template',$data);
	}
}
