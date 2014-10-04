<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_calendar extends CI_Controller {
	
	var $sesh;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model'); 
		$this->load->model('Calendar_model');
	    $this->User_model-> is_logged_in_admin();
	}
	
	function display($year = null, $month = null) {
		
		if (!$year) {
			$year = date('Y');
		}
		if (!$month) {
			$month = date('m');
		}
			
		if ($day = $this->input->post('day')) {
			$this->Calendar_model->add_calendar_data(
				"$year-$month-$day",
				$this->input->post('data')
			);
		}
		
			$data['calendar'] = $this->Calendar_model->generate($year, $month);
			$data['main_section'] = '_content/view_cal';
		    $this->load->view('admin_template', $data);
		
	}
	
}
