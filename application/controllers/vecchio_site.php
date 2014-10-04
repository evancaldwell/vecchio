<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_site extends CI_Controller {
		
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
			
	function account()
	{
		$data['main_section'] = 'vecchio_site/account_view'; 
		$data['title'] = "Account Login - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
		
	function products()
	{
		$data['main_section'] = 'vecchio_site/products_view'; 
		$data['title'] = "Products - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function products_new()
	{
		$data['main_section'] = 'vecchio_site/products_new_view'; 
		$data['title'] = "Products - VECCHIO Trees";
		$data['products'] = $this->Site_model->get_products();
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function contact()
	{
		$data['main_section'] = 'vecchio_site/contact_view'; 
		$data['title'] = "Contact - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function sales()
	{
		$data['main_section'] = 'vecchio_site/sales_view'; 
		$data['title'] = "Contact - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function news()
	{
		$data['main_section'] = 'vecchio_site/comingsoon_view'; 
		$data['title'] = "Contact - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function about()
	{
		$data['main_section'] = 'vecchio_site/about_view'; 
		$data['title'] = "About Us - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function faq()
	{
		$data['main_section'] = 'vecchio_site/faq_view'; 
		$data['title'] = "Frequently Asked Questions - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function terms()
	{
		$data['main_section'] = 'vecchio_site/terms_view'; 
		$data['title'] = "Terms of Use - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function new_contractor()
	{
		$data['main_section'] = 'vecchio_site/new_contractor_view'; 
		$data['title'] = "Create Consumer Account - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
	
	function new_consumer()
	{
		$data['main_section'] = 'vecchio_site/new_consumer_view'; 
		$data['title'] = "Create Consumer Account - VECCHIO Trees";
		$data['description'] = "Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners.";
		$this->load->view('site_template', $data);	
	}
}