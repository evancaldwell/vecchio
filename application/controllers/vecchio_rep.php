<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_rep extends CI_Controller {
	
	var $sesh;
	var $avail;
	var $in_house = false;
	
	function __construct()
	{
		parent::__construct();
 		$this->load->library('form_validation');
		$this->load->helper('html');
		$this->load->model('User_model');
		$this->load->model('Tracking_model');  
		$this->load->model('product_model');  
	    $this->User_model-> is_logged_in_rep();
	    $sesh['user_type'] = $this->session->userdata('user_type');
	    $sesh['user_id'] =  $this->session->userdata('user_id');
	    $vec['set'] = $this->User_model->vecchio_settings();
		$this->in_house = $this->User_model->is_in_house();
		$avail = $this->product_model->get_count_products_avail(); 
	    
	}
	

	
	function index()
	{
		$this->User_model->check_expired();
		$data['main_section'] = '_rep/welcome';
		$data['to_expire'] = $this->Tracking_model->get_to_expire($this->session->userdata('user_id'));
		$this->load->view('rep_template', $data);	
	}
	
	
	function return_message($message, $info){
		
		$data['main_section'] = '_content/message';
		$data['message'] = $message;
		$data['info'] = $info;
		$this->load->view('rep_template', $data);
	}
	
	function my_orders(){
	  $status = $this->uri->segment(3);
	  $user_id = $this->session->userdata('user_id');
	  $orders = $this->Tracking_model->get_orders_by_rep($user_id, $status = 1);   
	  $data['tag'] = $orders;
	  $data['main_section'] = '_rep/my_orders';
	  $this->load->view('rep_template', $data);
		
	}

	function delete_order(){
	 $response = $this->User_model->delete_entire_order('rep_delete');
	 echo $response;
	}
	
	
	function add_date($givendate,$day=0,$mth=0,$yr=0) {
	      $cd = strtotime($givendate);
	      $newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
	    	date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
	    	date('d',$cd)+$day, date('Y',$cd)+$yr));
	      return $newdate;
	}
	

	
	function add_user_form(){
	  $data['admin'] = false;	
	  $data['main_section'] = '_content/users_addnew';
	  $this->load->view('rep_template', $data);
	}
	
	function addnewuser(){
		
	 	$this->User_model->create_account();
		redirect('vecchio_rep/userportal/added/');
		
	}
	
	function userportal(){
		$data['stats'] = $this->User_model->get_user_stats();
		$data['main_section'] = '_content/userportal';
		$this->load->view('admin_template', $data);
	}
	
	function listusers(){
		$data['type'] = $this->uri->segment(3);
		$data['users'] = $this->User_model->get_all_users($data['type']);
		$data['main_section'] = '_content/listusersmod';
	    $this->load->view('rep_template', $data);
	}
	
	function search_users(){
		$search_term = $this->input->post('search_term');
		$data['type'] = 'search';
		$data['users'] = $this->User_model->search_users($search_term);
		$data['main_section'] = '_content/listusersmod';
	    $this->load->view('admin_template', $data);
		 
	}
	
	function edit_user(){
		$data['admin'] = false;
		$user = $this->User_model->get_user_by_id($this->uri->segment(3));
		if($user != false){
		$data['user'] = $user;
		$data['main_section'] = '_content/user_edit';
	    $this->load->view('rep_template', $data);
	  	} else {
				$error = 'User Not Found';
				$link = 'vecchio_rep/listusers';
				$linkname = 'Back to Users';
				$this->display_error($error, $link, $linkname);
		}
	}
	
	function edituserinfo(){
		$id = $this->input->post('id');
		
		$multiplier = $this->User_model->get_new_multiplier($this->input->post('user_type'));
		
		$data = array(
			'fname' => $this->input->post('fname'),
			'lname' => $this->input->post('lname'),
			'usern_email' => $this->input->post('usern_email'),
			'company_name' => $this->input->post('company_name'),
			'license_number' => $this->input->post('license_number'),
			'bill_address' => $this->input->post('billing_address'),
			'bill_city' => $this->input->post('billing_city'),
			'bill_state' => $this->input->post('billing_state'),
			'bill_zip' => $this->input->post('billing_zip'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'user_type' => $this->input->post('user_type'),
			'multiplier' => $multiplier
 		);
		$this->db->where('id',$id);
		$this->db->update('users', $data);
			
		if($this->input->post('password') != ""){
			$data_password = array(
			 			'password'  => sha1($this->input->post('password'))	
			);
		$this->db->where('id',$id);
		$this->db->update('users', $data_password);			
		}
		redirect('vecchio_rep/edit_user/' . $id);
		
		
		
	}
	
/* New Orders Section  */	
	
	function start_new_order(){
		$this->load->model('product_model');  
		$data['customer_id'] = $this->uri->segment(3);
		$data['main_section'] = '_content/new_shipping';
		
		$data['reps'] = array();
		//$data['product_type'] = $this->product_model->get_product_type_aval();
		$this->load->view('rep_template', $data);
	}
	
	
	function add_new_order(){
		

		$customer_id = $this->input->post('customer_id');
		$rep = "";
		
		if(empty($customer_id)){
			
		(empty($customer_id) ? $error[] = 'No Customer Selected' : '');
		$link = 'vecchio_rep/new_shipping/';
		$linkname = 'Back to Shipping Form';
		
		$this->display_error($error, $link, $linkname);
						
		} else {
	
			$data_ship = array(	
				'location' => $this->input->post('location'),
				'location_phone' => $this->input->post('location_phone'),
				'ship_address' => $this->input->post('ship_address'),
				'ship_city' => $this->input->post('ship_city'),
				'ship_state' => $this->input->post('ship_state'),
				'ship_zip' => $this->input->post('ship_zip')
			);
			
		 $new =	$this->Tracking_model->start_new_quote($customer_id, $rep, $data_ship);
		  if($new) 
			redirect('vecchio_rep/product_status/avail');
		}
			

		
	
	}
	
	function email_quote(){	
		$to = $this->input->post('email_to');
		$order_id = $this->input->post('order_id');
		$this->load->model('email_model');
		$this->email_model->gen_quote_email($order_id, $to);
		$return['message'] = 'Email sent to ' . $to;
		$return['info'] = '';
		$this->return_message($return['message'], $return['info']);	
		
	}

	
	
	function add_to_order(){
		
		$product_id = $this->input->post('product_id');
		$order_id = $this->input->post('order_id');
		if($order_id != 'no_selected'){
		$return = $this->Tracking_model->add_product_to_order($product_id, $order_id);
		$this->return_message($return['message'], $return['info']);	
		} else {
			$error = 'No Order Selected';
			$link = 'vecchio_rep/my_orders';
			$linkname = 'Back to Orders';
			$this->display_error($error, $link, $linkname);
		}
				
	}
	
	function show_product_info($product_id){
			
			$this->load->model('image_model');  
			$this->load->model('product_model');
			$data['info'] = $this->product_model->get_products_by('product',$product_id);
			$data['images'] = $this->image_model->get_product_pics($product_id);
			$data['aval_tagged'] = $this->Tracking_model->get_aval_tagged();
			$data['main_section'] = '_rep/product_view';
			$this->load->view('rep_template', $data);
	}

	
	function remove_item(){	
		$return = $this->Tracking_model->remove_product_from_order();
		$this->return_message($return['message'], $return['info']);
	}
	
	function product_status(){
		$status = $this->uri->segment(3);	 
	    $this->load->model('tracking_model');
		$this->load->model('product_model');
		$data['avail'] = $this->product_model->get_count_products_avail(); 
	//	echo "<pre>"; print_r($data['products']); echo "</pre>";
	// not in use;
		$data['main_section'] = '_rep/list_products_rep';
		switch($status){
			case 'avail':
			$br = "All Available Products";
			$data['products'] = array();
			break;
			case 'tagged':
			$br = "Tagged Products";
			$data['products'] = $this->product_model->get_products_by($status,0);
			break;
			case 'sold':
			$br = 'Sold Products';
			$data['products'] = $this->product_model->get_products_by($status,0);
			break;
			case 'shipped':
			$br = "Shipped Products";
			$data['products'] = $this->product_model->get_products_by($status,0);
			break;
		}
		$data['breadcrumb'] = $br;
		$data['aval_tagged'] = $this->tracking_model->get_aval_tagged();
			$this->db->select('short_name, grow_yard_name');
			$result = $this->db->get('grow_yards');
			$data['grow_yards'] = $result->result_array();
		$this->load->view('rep_template', $data);

	}
	
	function product_by_type(){
	    $product_id =	$this->uri->segment(3);
	    $this->load->model('tracking_model');
		$data['products'] = $this->product_model->get_products_by('avail_product_type',$product_id);
		//echo "<pre>"; print_r($data['products']); echo "</pre>";
		$data['main_section'] = '_rep/list_products_rep';
		$pn = $this->product_model->get_product_name($product_id);
		$data['breadcrumb'] = "By Product Type: " . $pn['product_code'] . " - " . $pn['description'] . " - " . $pn['specs'] . " $" . number_format($pn['list_price'], 2, '.', ',');
		$data['aval_tagged'] = $this->tracking_model->get_aval_tagged();
		$this->load->view('admin_template', $data);
	}
	
	function display_error($error, $link, $link_name){
		$data['error'] = $error; 
		$data['link'] = $link;
		$data['link_name'] = $link_name;
		$data['main_section'] = '_content/error_m';
		$this->load->view('admin_template', $data);	
		
	}
	
	function update_shipping(){
		
		$data['admin'] = false;
		$this->load->model('shipping_model'); 
		$shipping_id = $this->input->post('shipping_id');
		$data['shipping_id'] = $shipping_id;
		$data['order_name'] = $this->input->post('order_name');
		$data['shipping'] = $this->shipping_model->get_shipping_info($shipping_id);
		$data['main_section'] = '_content/update_shipping';
		$this->load->view('rep_template', $data);
	}
	
	function update_shipping_capture(){
		$will_call = $this->input->post('will_call');	
		if($will_call == 1) {
			$order_id = $this->input->post('order_id');
			$shipping_id = $this->input->post('shipping_id');	
					$data_ship = array(
						'location' => $this->input->post('location'),
						'location_phone' => $this->input->post('location_phone')
					);
					$this->load->model('tracking_model');
					$return = $this->tracking_model->update_shipping_address($shipping_id, $data_ship, $order_id, $will_call);
					echo $return['info'];			
			
		} else {
			$count = $this->check_zip($this->input->post('ship_zip'));
			if($count == 1){	
			
			$order_id = $this->input->post('order_id');
			$shipping_id = $this->input->post('shipping_id');
			$data_ship = array(
				'location' => $this->input->post('location'),
				'location_phone' => $this->input->post('location_phone'),
				'ship_address' => $this->input->post('ship_address'),
				'ship_city' => $this->input->post('ship_city'),
				'ship_state' => $this->input->post('ship_state'),
				'ship_zip' => $this->input->post('ship_zip')
			);
			$this->load->model('tracking_model');
			$return = $this->tracking_model->update_shipping_address($shipping_id, $data_ship, $order_id);
				echo $return['info'];
			} else {
				// fail miserably. 
						$error = 'Error - Zip Code Not recognized';
						$link = 'vecchio_admin/';
						$linkname = 'Back to Main Page';
						$this->display_error($error, $link, $linkname);
			}
		}
			
	}
	
	function check_zip($zip){
		
		$this->db->where('zip', $zip);
		$this->db->from('zcta');
		$count = $this->db->count_all_results();
		return $count;
		
	}
	
	function quick_quote(){
		
			$rep_id = $this->session->userdata('user_id');
			$data['client'] = $this->User_model->get_users_by_rep($rep_id, '', $this->in_house);
			// use this to show only products in inventory
		//	$data['product'] = $this->product_model->get_count_products_avail();
		
			// use to show all products
			$data['product'] = $this->product_model->get_product_type('array');

			$this->db->select('id, fname, lname');
			$this->db->where('user_type', 'rep');
			$this->db->where('id !=', $rep_id); // leave their own out of the bunch.... 
			$q = $this->db->get('users');
			$data['other_reps'] = $q->result_array();
						
			$data['main_section'] = '_rep/quick_quote';
			$this->load->view('rep_template', $data);
		
	}
	
	function add_remove_rep(){
		$add_remove= $this->input->post('add_remove');
		$qq_id = $this->input->post('qq_id');
		$rep_id = $this->session->userdata('user_id');
		$rep_id_2 = $this->input->post('rep_id_2');
		if($add_remove == 'add'){
			$data = array('rep_id_2' => $rep_id);
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $data);
		} else if($add_remove == 'remove'){
		
			if($rep_id_2 == $rep_id){ 	// check to make sure they are removing themselves 
			$data = array('rep_id_2' => 0);
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $data);
			}
		}
		redirect('vecchio_rep/my_quick_quotes');
	}
	
	
	function quick_quote_capt(){
		

	if($this->input->post('ready') == 0){
		
		$ship_zip = $this->input->post('ship_zip');
		
		if($this->input->post('customer_id') == 'no_selected'){
			redirect('vecchio_rep/quick_quote/no_selected');
		}
		if($ship_zip != '' && $this->input->post('will_call') != 1){
			$count = $this->check_zip($this->input->post('ship_zip'));	
		} else {
			$count = 0;
		}
		
		if($count != 1  && $this->input->post('will_call') != 1){
			redirect('vecchio_rep/quick_quote/no_selected');
		}
		$this->db->select('multiplier');
		$this->db->where('id', $this->input->post('customer_id'));
		$result = $this->db->get('users');
		$row = $result->result_array();
		$m = $row[0]['multiplier'];
		
		if($this->input->post('will_call') == 1){
		$ship_zip = 'willcall';
		}
		
		redirect('vecchio_rep/quick_quote/' . $this->input->post('customer_id'). '/'. $ship_zip. "/" . $m);
		
	} else {
		$quote_items = array();
		foreach( $_POST as $key => $value){
			if(is_numeric($key) && $value != 0){
				$quote_items[$key] = $value;
			}
		}
		
		$this->db->select('multiplier');
		$this->db->where('id', $this->input->post('customer_id'));
		$result = $this->db->get('users');
		$row = $result->result_array();
		$m = $row[0]['multiplier'];
		
			
		if(!empty($quote_items)){
			
			// will call 
			
			$will_call = $this->input->post('will_call');
			$box_items = $this->input->post('box_items');

			$this->load->model('shipping_model'); 
			$ship = $this->shipping_model->get_quick_ship($this->input->post('ship_zip'), $zipfrom = 93292, $quote_items, $will_call);
			
			// they put something in the quote
			$now_date = date("Y-m-d H:i:s");
			$new_date = $now_date . " + 1 Month";
			$new_exper_date = date('Y-m-d H:i:s', strtotime($new_date));
			$data = array(	 	 	 	 	 	
				'rep_id' =>	$this->session->userdata('user_id'),
				'rep_id_2' => $this->input->post('rep_id_2'), 	 	 	 	 	
				'customer_id' => $this->input->post('customer_id'),
				'ship_zip' => $this->input->post('ship_zip'),			 	 	 	 	 	 	
				'quote_date' =>	$now_date,	 	 	 	 	 	 	
				'expire_date' => $new_exper_date,
				'distance' => $ship['distance'], 
				'actual_trucks' => $ship['actual_trucks'],
				'trucks' => $ship['trucks'],
				'ship_cost' => $ship['ship_cost'],
				'memo' => $this->input->post('memo'),
				'locked_multiplier' => $m, 
				'will_call' => $will_call, 
				'po_number' => $this->input->post('po_number')
				);
			$this->db->insert('quick_quote', $data);
			$id = $this->db->insert_id();
			

			
			foreach($quote_items as $key => $value){
			
			// get product cost 
			$this->db->select('list_price');
			$this->db->where('id', $key);
			$res = $this->db->get('product_type');	
			$item = $res->result_array();
			$lp = $item[0]['list_price'];

				
				$data_i = array(
					'qq_id' => $id,
					'product_type_id' => $key,
					'quantity' => $value,
					'locked_price' => $lp
					);
				$this->db->insert('quick_quote_items', $data_i);
				
				
			}
			
			/* box trees. DEPRECIATED
			if($box_items == 1){
				
				// check if the current items can be boxed. 
				
				$can_box = $this->Tracking_model->can_box($id, 'quick_quote');
				
				if($can_box > 0){
					
					$data = array(
						'boxed' => 1,
						'box_price' => $can_box
					);	 
					
					$this->db->where('id', $id);
					$this->db->update('quick_quote', $data);
					
				}
			}
			*/
			
			redirect('vecchio_rep/my_quick_quotes/');
			
		} else {

			
			redirect('vecchio_rep/quick_quote/' . $this->input->post('customer_id'). '/'. $this->input->post('ship_zip') . "/" . $m);	
		}
	}	
	
		
		
	}
	
	function my_quick_quotes(){
		
		$data['quote'] = $this->Tracking_model->get_quick_quote_items();
		$data['main_section'] = '_rep/my_quick_quotes';
		$this->load->view('rep_template', $data);

	}
	
	function delete_quick_quote(){
		$delete_id = $this->input->post('delete_id');
		$this->db->delete('quick_quote', array('id' => $delete_id )); 
		$this->db->delete('quick_quote_items', array('qq_id' => $delete_id ));
	
		redirect('vecchio_rep/my_quick_quotes');
		
		
	}
	
	function gen_qq_pdf(){
		$this->load->model('pdf_model');  
		$quote_id = $this->input->post('quote_id');
		$this->pdf_model->quick_quote_pdf($quote_id);
		
		
	}
	
	
	// search dat 
	function search_products(){
		
		$inp = $this->input->post('search');
		$data['newlist'] = $this->product_model->search_products($inp);
		$data['search_term'] = $inp;
		$data['main_section'] = '_rep/search_results';
		$this->load->view('rep_template', $data);
			
	}
	
	
	function search_by_row_tree(){
		
		$row = $this->input->post('row_num');
		$tree = $this->input->post('tree_num');
		$grow_yard = $this->input->post('grow_yard_id');
		
    	$result = $this->product_model->search_by_row_tree($grow_yard, $tree, $row);
        // echo "<pre>";
		// print_r($result);
	    // echo "</pre>";
 	    $data['show_products'] = true;	
    	$data['newlist'] = $result;
    	$data['main_section'] = '_rep/search_results'; 
    	$data['title'] = "VECCHIO Trees";
    	$data['inp'] = 'Row and Tree';
    	$this->load->view('rep_template', $data);		
		
	}
	
	function search_by_h_w(){
		
		$h = $this->input->post('height');
		$w = $this->input->post('width');
    	$result = $this->product_model->search_by_h_w($h, $w);	
		$data['show_products'] = true;	
		$data['newlist'] = $result;
		$data['main_section'] = '_rep/search_results'; 
		$data['title'] = "VECCHIO Trees";
		$data['inp'] = 'H x W';
		$this->load->view('rep_template', $data);		
		
	}
	
	function product_search(){
		$inp = trim($this->input->post('search_txt'));
		$result = $this->product_model->search_products($inp, true);
		$data['show_products'] = true;	
		$data['newlist'] = $result;
		$data['main_section'] = '_rep/search_results'; 
		$data['title'] = "VECCHIO Trees";
		$data['inp'] = $inp;
		$this->load->view('rep_template', $data);	
	}

	
	

}