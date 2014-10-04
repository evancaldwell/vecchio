<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_order extends CI_Controller {
		
	function __construct()
	{
		parent::__construct();
 		$this->load->library('form_validation');
		$this->load->model('Site_model');
		$this->load->helper('html');
	}
	
	function index()
	{
		$data['main_section'] = 'vecchio_site/index_view'; 
		$data['title'] = "VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function confirm_shipping(){
		$data['main_section'] = 'vecchio_site/confirmship_view'; 
		$data['title'] = "VECCHIO Trees";
		$data['description'] = "Vecchio Trees ";
		$data['shipping'] = "yes";
		$this->load->view('site_template', $data);
	}		
	
	
}