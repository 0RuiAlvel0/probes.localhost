<?php
	class Aux_functions_model extends CI_Model{
		//collection of common functions to be used throughout the program
		function is_logged_in(){
			$is_logged_in = $this->session->userdata('is_logged_in');
			if(!isset($is_logged_in) || $is_logged_in != true)
				return false;
			else
				return true;
		}

		function is_valid_email($email){
			$output = false;
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
				$output = true;
			return $output;
		}

		function is_valid_password($p){
			$output = true;
			$uppercase = preg_match('@[A-Z]@', $p);
			$number    = preg_match('@[0-9]@', $p);
			if(!$uppercase || !$number || strlen($p) < 8)
				$output = false;
			return $output;
		}

		function gen_random_string(){
			$chars = "abcdefghijkmnopqrstuvwxyz023456789ABCDEFGHIJKLMNPQRSTUVXZ";
			srand((double)microtime()*1000000);
			$i = 0;
			$code = '' ;
			while ($i <= 5){
				$num = rand() % 33;
				$tmp = substr($chars, $num, 1);
				$code = $code . $tmp;
				$i++;
			}
			return $code;
		}

	}
?>
