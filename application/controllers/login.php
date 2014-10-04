<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
		
	function __construct()
	{
		parent::__construct();
 		$this->load->library('form_validation');

		
	}
	
	function index($error = '')
	{
		$data['main_section'] = '_content/login';
		$data['error'] = $error; 
		$this->load->view('admin_template', $data);	
	}
	
	function display_error($error, $link, $link_name){
		$data['error'] = $error; 
		$data['link'] = $link;
		$data['link_name'] = $link_name;
		$data['main_section'] = '_content/error_m';
		$data['no_menu'] = 'no_menu';
		$this->load->view('admin_template', $data);	
		
	}
	
	function validate_credentials()
	{		
		$this->load->model('user_model');
		$query = $this->user_model->validate();
		
		
		if($query) // if the user's credentials validated...
		{
			
			// check if they have an order with a qq_id, set the session to that var 
			$this->db->select('qq_id');
			$this->db->where('customer_id', $query[0]['id']);
			$this->db->where('status', 1);
			$this->db->order_by('order_date', 'desc');
			$this->db->limit(1);
			$result = $this->db->get('orders');
			$r = $result->result_array();
			if(!empty($r)){
				$qq_id = $r[0]['qq_id'];
			} else {
				$qq_id = 0;
			}
			
			$data = array(
				'usern_email' => $query[0]['usern_email'],
				'user_id' => $query[0]['id'],
				'user_type' => $query[0]['user_type'],
				'fname' => $query[0]['fname'],
				'lname' => $query[0]['lname'],
				'multiplier' => $query[0]['multiplier'],
				'is_logged_in' => true,
				'qq_id' => $qq_id,
				'sa' => $query[0]['sa']
			);

			$this->session->set_userdata($data);
			if($query[0]['user_type'] == "admin"){
				redirect('vecchio_admin');
			} else if($query[0]['user_type'] == "rep"){
				redirect('vecchio_rep');
			} else {
				echo "You have logged in as a " . $this->session->userdata('user_type');
			}
		}
		else // incorrect username or password
		{
		$this->display_error('Unknown Username / Password', 'login/', '&laquo; Back To Log In');	
		}
	}	
	
   
	
	function logout()
	{
		$this->session->sess_destroy();
		$this->index('You have logged out');
	}
	
	function logoutuser()
	{
		$this->session->sess_destroy();
		redirect('dir/log_in/my_account/logout');
	}

}