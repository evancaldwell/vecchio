<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_bank extends CI_Controller {
		
	function __construct()
	{
		parent::__construct();
 		$this->load->library('form_validation');
		$this->load->model('User_model');
		$this->load->model('Image_model');
		$this->load->model('Tracking_model');  
		$this->load->helper('ssl_helper');
	   //   $this->User_model-> is_logged_in_admin();
	   
	}
	
	function index($error = '')
	{
		$data['main_section'] = '_content/main_admin';
		$data['error'] = $error; 
		$this->load->view('admin_template', $data);	
	}
	
	function byproduct($product_id){
		force_ssl();
		$this->load->model('product_model');
		$data['info'] = $this->product_model->get_products_by('product',$product_id);
		$data['images'] = $this->Image_model->get_product_pics($product_id);
		$data['active'] = 'true';
 		$this->load->view('_content/full_image', $data);
		
	}
	
	
	function bygrowyard(){
		$this->load->model('product_model');
		$product_id = $this->uri->segment(4, 0);
		$data['title'] = $this->product_model->get_grow_yard_name($this->uri->segment(3));
		$data['images'] = $this->Image_model->get_product_pics_gy($this->uri->segment(3), $product_id);
		$this->load->view('_content/image_view', $data);
	}
	
	
	function display_error($error, $link, $link_name){
		$data['error'] = $error; 
		$data['link'] = $link;
		$data['link_name'] = $link_name;
		$data['main_section'] = '_main_section/error_m';
		$this->load->view('admin_template', $data);	
		
	}
	
	

}