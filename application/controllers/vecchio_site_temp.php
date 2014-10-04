<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_site_temp extends CI_Controller {

	function __construct()
	{
		parent::__construct();
 		$this->load->library('form_validation');
		$this->load->model('Site_model');
		$this->load->helper('html');
	}

	function products_new()
	{
		$data['main_section'] = 'vecchio_site/products_new_view'; 
		$data['title'] = "Products - VECCHIO Trees";
		$data['products'] = $this->Site_model->get_products();
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
}