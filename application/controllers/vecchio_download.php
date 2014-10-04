<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_download extends CI_Controller {
		
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->helper('download');
		$this->load->helper('html');
	}
	
	function specs_info(){
		$orig_name = "Vecchio_Product_List.pdf";
		$full_path = "_dwn/Vecchio_Product_List.pdf";

		$data = file_get_contents($full_path); // Read the file's contents
		
	   	force_download($orig_name, $data);
	}
	
	function warranty(){
		$this->load->model('pdf_model');
		$this->pdf_model->gen_warranty();
	}
	
	function planting_detail(){
		$orig_name = "Vecchio_Planting_Detail.pdf";
		$full_path = "_dwn/Tree_Planting_Detail-Vecchio_Trees.pdf";

		$data = file_get_contents($full_path); // Read the file's contents
		
	   	force_download($orig_name, $data);
	}
	
	function planting_detail_cad(){
		$orig_name = "Vecchio_Planting_Detail.dwg";
		$full_path = "_dwn/Tree_Planting_Detail-Vecchio_Trees.dwg";

		$data = file_get_contents($full_path); // Read the file's contents
		
	   	force_download($orig_name, $data);
	}
	
	
	function check_by_fax(){
		$orig_name = "Vecchio_Check_By_Fax.pdf";
		$full_path = "_dwn/V_By_Fax.pdf";

		$data = file_get_contents($full_path); // Read the file's contents
		
	   	force_download($orig_name, $data);
	}
	
	function catalog(){
		$orig_name = "ProductCatalog.pdf";
		$full_path = "_dwn/ProductCatalog.pdf";

		$data = file_get_contents($full_path); // Read the file's contents
		
	   	force_download($orig_name, $data);
	}
	

	function availability(){
		$orig_name = "ProductAvailabilityList.pdf";
		$full_path = "_dwn/ProductAvailabilityList.pdf";

		$data = file_get_contents($full_path); // Read the file's contents
		
	   	force_download($orig_name, $data);
	}

	
	function check_by_fax_real(){
		$order_id = $this->input->post('order_id');
		if($order_id != ''){
		$this->load->model('pdf_model');
		$this->pdf_model->gen_check_by_fax_pdf($order_id);
		} else {
			redirect('dir/');
		}
	}
	
	function check_by_fax_qq(){
		$qq_id = $this->input->post('qq_id');
		if($qq_id != ''){
		$this->load->model('pdf_model');
		$this->pdf_model->gen_check_by_fax_pdf_qq($qq_id);
		} else {
			redirect('dir/');
		}
	}
	
	function send_email(){
	$this->load->model('email_model');
	$data['fname'] = "Tyler";
	$data['lname'] = "Penney";
	$data['usern_email'] = "tylerpenney@gmail.com";
	$em[] = "Hello ".$this->input->post('fname').",";
	$em[] = "Thank you for creating an account with VECCHIO Trees. Please keep a copy of your log in credentials: ";
	$em[] = "<b>Username: "."</b>";
	$em[] = "<b>Password: "."</b>";
	$em[] = "Sincerely, Vecchio Trees";
	$em[] = "vecchiotrees.com" ;
	$this->email_model->send_email_to($data['fname'], $data['lname'], $data['usern_email'], 'Welcome to VECCHIO Trees', $em,'html');
	}
	
	function receipt(){
		
			$order_id = $this->session->userdata('c_order_id');
			$this->load->model('pdf_model');
			$this->pdf_model->gen_pdf($order_id, 'regular');
	}
	

	
	function receipt_qq(){
		
			$yn = $this->User_model->has_user_priv();
			if(!$yn){
				redirect('dir/log_in/my_account');
			} else {
			$qq_id = $this->uri->segment(3);	
			$this->load->model('pdf_model');
			$this->pdf_model->gen_pdf_qq($qq_id);
			}
	}
	
	function receipt_post(){
		
			$order_id = $this->uri->segment(3);
			if($this->session->userdata('user_type') != 'rep' && $this->session->userdata('user_type') != 'admin'){
				// check
			$this->db->where('customer_id', $this->session->userdata('user_id'));
			$this->db->where('id', $order_id);
			$num = $this->db->count_all_results('orders');
			if($num == 1){
					$this->load->model('pdf_model');
					$this->pdf_model->gen_pdf($order_id, 'regular');
				} else {
					redirect('dir/my_account/');
				}
			} else {
				$this->load->model('pdf_model');
				$this->pdf_model->gen_pdf($order_id, 'regular');
			}
			
	}
	
	function vecchio_quote(){
		$order_id = $this->input->post('order_id');
		$this->load->model('pdf_model');
		$this->pdf_model->generate_order_quote($order_id);
	}
	
	
}