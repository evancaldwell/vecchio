<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ================================

Tracking Variables orders.status
Inventory Based Orders 						|					Quick Quote Variables
0 = NULL 												0 = Null
1 = Items in Order Tagged for 24 Hours					1 = On Account / Shipping Backlog
2 = Paid / Shipping Backlog								2 = Paid / Shipping Backlog
3 = Paid / Shipped										3 = Paid / Shipped
4 = On Account / Shipping Backlog						4 = On Account / Shipped
5 = On Account / Shipped								6 = Check By Fax - Needs Approval
6 = Check By Fax - Needs Approval
7 = Expired 
==================================
*/

class Vecchio_admin extends CI_Controller {
	
	var $sesh;
	
	function __construct()
	{
		parent::__construct();
 		$this->load->library('form_validation');
		$this->load->model('User_model');
		$this->load->model('Tracking_model');  
	    $this->User_model-> is_logged_in_admin();
	    $sesh['user_type'] = $this->session->userdata('user_type');
	    $sesh['user_id'] =  $this->session->userdata('user_id');
	    $vec['set'] = $this->User_model->vecchio_settings();
	    
	}
	

	
	function index()
	{
		$data['main_section'] = '_content/main_admin';
		$data['stats'] = $this->User_model->get_stats(); 
		$q = "SELECT count('id') as cnt FROM quick_quote WHERE status = 1";
		$query = $this->db->query($q);
		$res = $query->result_array();
		$data['on_account'] = $res[0]['cnt'];
		$data['to_expire'] = $this->Tracking_model->get_to_expire_admin();
		$this->load->view('admin_template', $data);	
	}
	
	
	function return_message($message, $info){
		
		$data['main_section'] = '_content/message';
		$data['message'] = $message;
		$data['info'] = $info;
		$this->load->view('admin_template', $data);
	}
	
	function payment_accepted(){
		$info[0] = "";
		$data['main_section'] = '_content/message';
		$data['message'] = 'Payment Entered Succesfully';
		$data['info'] = $info[0];
		$this->load->view('admin_template', $data);
	}
	
	function new_estimates(){
		
		$order_id = $this->uri->segment(3);
	  if(is_numeric($order_id)){
    	 $merge = $this->Tracking_model->get_orders('','', '','', '', $order_id , false);
	  } else {
	  $orders = $this->Tracking_model->get_orders(1); // new estimates 
	  $fax_pend = $this->Tracking_model->get_orders(6); // pending fax 
	  $merge = array_merge($orders,$fax_pend);
   	  }
	  // get payment options 
		$count = count($merge);
		$this->load->model('payment_model');
		$location = ''; 
		$location_phone = '';
		$ship_zip = '';
		for($i=0;$i<$count;$i++){
		if(!empty($merge[$i]['shipping']) && $merge[$i]['shipping'] != 'Enter Shipping'){
			$location = $merge[$i]['shipping'][0]['location']; 
			$location_phone = $merge[$i]['shipping'][0]['location_phone'];
			$ship_zip = $merge[$i]['shipping'][0]['ship_zip'];
		}
		$e_sig = $merge[$i]['e_sig'];
		$status = $merge[$i]['status'];	
		$merge[$i]['pay_status'] = 	$this->payment_model->check_payment_ready($location, $location_phone, $ship_zip, $e_sig, $status);
		
		if($merge[$i]['pay_status']['payment_ready'] == true){
			// get full and half payment amounts 
			$merge[$i]['pay_amounts'] = $this->payment_model->get_payment_half($merge[$i]['freight']['grand_total'], $merge[$i]['freight']['total_cost_cust']);
						
		}
			$merge[$i]['amount_owed'] = $this->payment_model->get_amount_owed($merge[$i]['freight']['grand_total'], $merge[$i]['id'], 'order');	
		$merge[$i]['status_text'] = $this->payment_model->get_status_text($merge[$i]['status']);
		}
		
		
		// get quick quotes
		$q = $this->Tracking_model->get_quick_quote_items("", "", "", $status = array(0,6));
		$count = count($q);
		$this->load->model('payment_model');
		$location = ''; 
		$location_phone = '';
		$ship_zip = '';
		for($i=0;$i<$count;$i++){
		if(!empty($q[$i]['shipping'])){
			$location = $q[$i]['shipping'][0]['location']; 
			$location_phone = $q[$i]['shipping'][0]['location_phone'];
			$ship_zip = $q[$i]['shipping'][0]['ship_zip'];
		}
		$e_sig = $q[$i]['e_sig'];
		$status = $q[$i]['status'];	
		$q[$i]['pay_status'] = 	$this->payment_model->check_payment_ready_qq($location, $location_phone, $ship_zip, $e_sig, $status);	
		}
	  
	  $data['quote'] = $q;		
	
	  $data['new_estimates'] = $merge;
	  $data['main_section'] = '_content/new_estimates_view';
	  $this->load->view('admin_template', $data);
		
	}
	
	function inprocessing(){
	  $order_id = $this->uri->segment(3);
	  if(is_numeric($order_id)){
    	 $merge = $this->Tracking_model->get_orders('','', '','', '', $order_id , false);
	  } else {
	  	$orders = $this->Tracking_model->get_orders(2);  // paid / shipping backlog
	  	$account = $this->Tracking_model->get_orders(4); // on account / shipping backlog
	  	$merge = array_merge($orders,$account); 
	  }
	  //$this->Tracking_model->calendar_feed();  
		$count = count($merge);
	  $this->load->model('payment_model');
	  for($i=0;$i<$count;$i++){
			$merge[$i]['status_text'] = $this->payment_model->get_status_text($merge[$i]['status']);
		// orders that have not paid have the ability to enter payment here. 
			$merge[$i]['amount_owed'] = $this->payment_model->get_amount_owed($merge[$i]['freight']['grand_total'], $merge[$i]['id'], 'order');	
			$merge[$i]['pay_amounts'] = $this->payment_model->get_payment_half($merge[$i]['freight']['grand_total'], $merge[$i]['freight']['total_cost_cust']);
			$merge[$i]['transaction_text'] = $this->payment_model->get_transaction_text($merge[$i]['id']);	
	  }
	
		// get quick quotes
		$q = $this->Tracking_model->get_quick_quote_items("", "", "", $status = array(1,2));
		$count = count($q);
		$this->load->model('payment_model');
		$location = ''; 
		$location_phone = '';
		$ship_zip = '';
		for($i=0;$i<$count;$i++){
		if(!empty($q[$i]['shipping'])){
			$location = $q[$i]['shipping'][0]['location']; 
			$location_phone = $q[$i]['shipping'][0]['location_phone'];
			$ship_zip = $q[$i]['shipping'][0]['ship_zip'];
		}
		$e_sig = $q[$i]['e_sig'];
		$status = $q[$i]['status'];	
		$q[$i]['pay_status'] = 	$this->payment_model->check_payment_ready_qq($location, $location_phone, $ship_zip, $e_sig, $status);	
		}
	  
	  $data['quote'] = $q;
	  $data['new_estimates'] = $merge;
	  $data['main_section'] = '_content/in_processing_view';
	  $this->load->view('admin_template', $data);
		
	}
	
	function shipped(){
		$order_id = $this->uri->segment(3);
		  if(is_numeric($order_id)){
	    	 $merge = $this->Tracking_model->get_orders('','', '','', '', $order_id , false);
		  } else {
	  		$orders = $this->Tracking_model->get_orders(3);  // paid / on account
	  		$account = $this->Tracking_model->get_orders(5); // on account / shipped
	  		$merge = array_merge($orders,$account); 
		}
	 $count = count($merge);
	 $this->load->model('payment_model');
	  for($i=0;$i<$count;$i++){	
			$merge[$i]['status_text'] = $this->payment_model->get_status_text($merge[$i]['status']);
			// orders that have not paid have the ability to enter payment here. 
			$merge[$i]['amount_owed'] = $this->payment_model->get_amount_owed($merge[$i]['freight']['grand_total'], $merge[$i]['id'], 'order');	
			$merge[$i]['pay_amounts'] = $this->payment_model->get_payment_half($merge[$i]['freight']['grand_total'], $merge[$i]['freight']['total_cost_cust']);
			$merge[$i]['transaction_text'] = $this->payment_model->get_transaction_text($merge[$i]['id']);
	  }  
	
		// get quick quotes
		$q = $this->Tracking_model->get_quick_quote_items("", "", "", $status = array(3,4));
		$count = count($q);
		$this->load->model('payment_model');
		$location = ''; 
		$location_phone = '';
		$ship_zip = '';
		for($i=0;$i<$count;$i++){
		if(!empty($q[$i]['shipping'])){
			$location = $q[$i]['shipping'][0]['location']; 
			$location_phone = $q[$i]['shipping'][0]['location_phone'];
			$ship_zip = $q[$i]['shipping'][0]['ship_zip'];
		}
		$e_sig = $q[$i]['e_sig'];
		$status = $q[$i]['status'];	
		$q[$i]['pay_status'] = 	$this->payment_model->check_payment_ready_qq($location, $location_phone, $ship_zip, $e_sig, $status);	
		}
	  
	  $data['quote'] = $q;
	
	  $data['new_estimates'] = $merge;
	  $data['main_section'] = '_content/shipped_view';
	  $this->load->view('admin_template', $data);
		
	}
	
	function on_account(){
	  $data['oc'] = $this->Tracking_model->get_on_account();   
	  $data['main_section'] = '_content/on_account';
	  $this->load->view('admin_template', $data);
	
	}
	
	function delete_order(){
				
	 $response = $this->User_model->delete_entire_order('admin_delete');
	 echo $response;
	}
	
	function delete_product(){
		$this->load->model('product_model'); 
		$product_id = $this->input->post('product_id');
		$serial_no = $this->input->post('serial_no');
		// make sure its not part of an order already !! !!
		$is_avail = $this->product_model->check_if_avail($product_id);
		if($is_avail == 0){
		$this->db->where('id', $product_id);
		$delete_items = $this->db->delete('products');
		
		// remove images from database 
		$this->product_model->remove_product_images($product_id);
		
			$message = "Product Deleted";
			$info = array();
			$base = base_url();
			$info[0] = "Serial # of Deleted Product: " . $serial_no;
		
		   $this->return_message($message, $info);
		}
	
	}

	// Private function
	function add_date($givendate,$day=0,$mth=0,$yr=0) {
	      $cd = strtotime($givendate);
	      $newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
	    	date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
	    	date('d',$cd)+$day, date('Y',$cd)+$yr));
	      return $newdate;
	}
	
	function extend_order(){
		$order_id = $this->input->post('order_id');
		$days = $this->input->post('extend_to');
		$old_date = $this->input->post('expire_date');
		$new_date = $this->add_date($old_date,$days);
		$this->db->where('id', $order_id);
		$data = array(
				     'expire_date' => $new_date
		);
		$this->db->update('orders', $data);
		redirect('vecchio_admin/new_estimates/');
	
	}

	
	function toshipped(){
		$this->load->model('payment_model');
		$order_id = $this->input->post('order_id');
		$shipped_date = date("Y-m-d H:i:s", strtotime($this->input->post('shipped_date')));
		$grand_total = $this->input->post('grand_total');
		$amount_owed = $this->payment_model->get_amount_owed($grand_total, $order_id, 'order');	
		
		if($amount_owed > 0){ // the have not paid last deposit
			$status = 5; // shipped on account
		} else {  // all paid up
			$status = 3; // shipped paid
		}
		
		$this->db->where('id', $order_id);	

		$data = array(
				     'shipped_date' => $shipped_date,
				     'status' => $status
		);
		$this->db->update('orders', $data);
		$this->Tracking_model->update_products($order_id, 4);
		redirect('vecchio_admin/inprocessing/');
	
	}
	
	function register_check_by_fax(){
		
		$order_id = $this->input->post('order_id');
		$payment_date = date("Y-m-d H:i:s", strtotime($this->input->post('payment_date')));
		$now_date = date("Y-m-d H:i:s");
		
			// Generate random name for order 
			$this->load->model('product_model');  
			$rand_str = $this->product_model->genRandomString(10);  
		
		$data = array(
				'order_id' => $order_id,
				'amount' => $this->input->post('amount'),
				'freight' => $this->input->post('freight'),
				'transaction_id' => $rand_str,
				'method' => 'CHECK-BY-FAX',
				'payment_date' => $now_date
		);
		$payment = $this->db->insert('payments',$data);
		
		
		$this->db->where('id', $order_id);

		$data = array(
				     'payment_date' => $payment_date,
				     'status' => 2
		);
		$this->db->update('orders', $data);
		
		//$this->email_model->send_email_rec($order_id);
		//$this->email_model->send_admin_rec($order_id);		
		
		
		redirect('vecchio_admin/inprocessing/');
	
	}
	

	
	function add_user_form(){
	  $data['admin'] = true;
	  $data['main_section'] = '_content/users_addnew';
	  $this->load->view('admin_template', $data);
	}
	
	function addnewuser(){
		
		$this->User_model->create_account();
		redirect('vecchio_admin/userportal/');
		
	}
	
	function userportal(){
		$data['stats'] = $this->User_model->get_user_stats();
		$data['main_section'] = '_content/userportal';
		$this->load->view('admin_template', $data);
	}
	
	function listusers(){
		$data['admin'] = true;
		$data['type'] = $this->uri->segment(3);
		$data['users'] = $this->User_model->get_all_users($data['type']);
		$data['main_section'] = '_content/listusersmod';
		$reps = $this->User_model->get_all_reps('array');
		$lreps = array();
		$lreps[0] = "House";
		foreach($reps as $rep){
			$lreps[$rep['id']] = $rep['fname'] . " " . $rep['lname'];
		}
		$data['lreps'] = $lreps;
	    $this->load->view('admin_template', $data);
	}
	
	function search_users(){
		$search_term = $this->input->post('search_term');
		$data['type'] = 'search';
		$data['users'] = $this->User_model->search_users($search_term);
		$data['main_section'] = '_content/listusersmod';
		$reps = $this->User_model->get_all_reps('array');
		$lreps = array();
		$lreps[0] = "House";
		foreach($reps as $rep){
			$lreps[$rep['id']] = $rep['fname'] . " " . $rep['lname'];
		}
		$data['lreps'] = $lreps;
	    $this->load->view('admin_template', $data);
		 
	}
	
	function edit_user(){
		$data['admin'] = true;
		$data['user'] = $this->User_model->get_user_by_id($this->uri->segment(3));
		$data['reps'] = $this->User_model->get_all_reps('array');
		$data['main_section'] = '_content/user_edit';
	    $this->load->view('admin_template', $data);
	}
	
	function edituserinfo(){
		$id = $this->input->post('id');

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
			'multiplier' => $this->input->post('multiplier'),
			'rep_id' => $this->input->post('rep_id'),
			'net_terms' => $this->input->post('net_terms'),
			'credit_limit' => $this->input->post('credit_limit')
			
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
		redirect('vecchio_admin/edit_user/' . $id);
		
		
		
	}
	
/* New Orders Section  */	
	
	function start_new_order(){
		$this->load->model('product_model');  
		$data['customer_id'] = $this->uri->segment(3);
		$data['main_section'] = '_content/new_shipping';
		
		$data['reps'] = ($this->User_model->c_rep() ? ' ' : $this->User_model->get_all_reps());
		//$data['product_type'] = $this->product_model->get_product_type_aval();
		$this->load->view('admin_template', $data);
	}
	
	
	function add_new_order(){
		

		$customer_id = $this->input->post('customer_id');
		$rep = "";
		
		if(empty($customer_id)){
			
		(empty($customer_id) ? $error[] = 'No Customer Selected' : '');
		$link = 'vecchio_admin/new_shipping/';
		$linkname = 'Back to Shipping Form';
		
		$this->display_error($error, $link, $linkname);
						
		} else {
	
			$data_ship = array(	
				'location' => $this->input->post('location'),
				'ship_address' => $this->input->post('ship_address'),
				'ship_city' => $this->input->post('ship_city'),
				'ship_state' => $this->input->post('ship_state'),
				'ship_zip' => $this->input->post('ship_zip')
			);
			
		 $new =	$this->Tracking_model->start_new_quote($customer_id, $rep, $data_ship);
		  if($new) 
			redirect('vecchio_admin_products/products');
		}
			

		
	
	}
	
	
	function quick_quotes(){
		
		$q = $this->Tracking_model->get_quick_quote_items();
		$count = count($q);
		$this->load->model('payment_model');
		$location = ''; 
		$location_phone = '';
		$ship_zip = '';
		for($i=0;$i<$count;$i++){
		if(!empty($q[$i]['shipping'])){
			$location = $q[$i]['shipping'][0]['location']; 
			$location_phone = $q[$i]['shipping'][0]['location_phone'];
			$ship_zip = $q[$i]['shipping'][0]['ship_zip'];
		}
		$e_sig = $q[$i]['e_sig'];
		$status = $q[$i]['status'];	
		$q[$i]['pay_status'] = 	$this->payment_model->check_payment_ready_qq($location, $location_phone, $ship_zip, $e_sig, $status);	
		}
		$data['quote'] = $q;
		
		$data['main_section'] = '_content/all_quick_quotes';
		$this->load->view('admin_template', $data);
		
		/* Old By Rep
		$data['rep'] = $this->Tracking_model->get_all_quick_quote();
		$data['main_section'] = '_content/quick_quote_by_rep';
		$this->load->view('admin_template', $data);
		*/
	}
	
	function gen_qq_pdf(){
		
		$this->load->model('pdf_model');  
		$quote_id = $this->input->post('quote_id');
		$this->pdf_model->quick_quote_pdf($quote_id);
			
	}
	


	

	
	function edit_quick_quote($qq_id){
		$quote =  $this->Tracking_model->get_quick_quote_items($qq_id);
		$data['row'] = $quote;
		$location = '';
		$location_phone = '';
		$ship_zip = '';
	
		// check if quote has the nescessary items to move from pending to shipping backlog
		if(!empty($quote[0]['shipping'])){
			$location = $quote[0]['shipping'][0]['location'];
			$location_phone = $quote[0]['shipping'][0]['location_phone'];
			$ship_zip = $quote[0]['shipping'][0]['ship_zip'];
		}
		$e_sig = $quote[0]['e_sig'];
		$status = $quote[0]['status'];
		$this->load->model('payment_model');

		$data['pay_status'] = $this->payment_model->check_payment_ready_qq($location, $location_phone, $ship_zip, $e_sig, $status);

		if($data['pay_status']['payment_ready'] == true){
			// get full and half payment amounts 
			$data['pay_amounts'] = $this->payment_model->get_payment_half($quote[0]['grand_total'],$quote[0]['ship_cost']);
						
		}
		$data['amount_owed'] = $this->payment_model->get_amount_owed($quote[0]['grand_total'], $quote[0]['id'], 'quick_quote');
		$data['transaction_text'] = $this->payment_model->get_transaction_text_qq($quote[0]['id']);
		$data['main_section'] = '_content/edit_quick_quote';
	
		$this->load->view('admin_template', $data);
	}
	
	function enter_payment(){
		$type = $this->uri->segment(3);
		 $grand_total = $this->input->post('grand_total');
		 $freight = $this->input->post('freight');
		 $deposit_whole = $this->input->post('deposit_whole');
		 $this->load->model('payment_model');
		 $payments = $this->payment_model->get_payment_half($grand_total, $freight);
		 $dep_bal = $this->input->post('dep_bal');
		 
		if($dep_bal == 'dep'){ // on account or 
		 	if($deposit_whole == 'deposit'){
				$new_payment = $payments['half_deposit'];
				$new_freight = $payments['half_ship1'];
			} else if($deposit_whole == 'full_payment'){
				$new_payment = $grand_total;
				$new_freight = $freight;				
			}
		 } else if($dep_bal == 'bal'){
			$new_payment = $payments['half_balance'];
			$new_freight = $payments['half_ship2'];
		 }
		
		if($type == 'quote'){
		
			$qq_id = $this->input->post('qq_id');
		
			$result = $this->payment_model->enter_payment_qq($qq_id, $new_payment, $new_freight);
			if($result['status'] != 'fail'){
			
				redirect('vecchio_admin/payment_accepted/');
			
			} else {
					 // fail miserably. 
							$error = $result['response'];
							$link = 'vecchio_admin/edit_quick_quote/' . $qq_id;
							$linkname = 'Back to Main Quote Page';
							$this->display_error($error, $link, $linkname);
			}
		} else { // order
			
			$order_id = $this->input->post('order_id');
		
			$result = $this->payment_model->enter_payment($order_id, $new_payment, $new_freight);
			if($result['status'] != 'fail'){
			
				redirect('vecchio_admin/payment_accepted/');
			
			} else {
					 // fail miserably. 
							$error = $result['response'];
							$link = 'vecchio_admin/new_estimates/';
							$linkname = 'Back to Main New Estimates Page';
							$this->display_error($error, $link, $linkname);
			}
			
		}
		
		
		
		
	}
	
	function edit_quick_quote_capt(){
		
		$disc= $this->input->post('disc');
		$qq_id = $this->input->post('qq_id');
		

		
		if(is_numeric($disc)){
			$disc_up = array('disc' => $disc, 'who_disc' => $this->input->post('who_disc'));
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $disc_up);
		}
				
		
		
		// get the items and change quantity if changed. 
		
		$this->db->where('qq_id', $qq_id);
		$query = $this->db->get('quick_quote_items');
		$result = $query->result_array();
		$count = count($result);
		$quote_items = array();
		for($i=0;$i<$count;$i++){
		$item_id =  $result[$i]['id'];
		$product_type_id = $result[$i]['product_type_id'];
		$item_post = $this->input->post($item_id);
		$quote_items[$product_type_id] = $item_post;
		
			if($item_post != $result[$i]['quantity'] && $item_post > 0){
				$quant_up = array('quantity' => $item_post);
				$this->db->where('id', $item_id);
				$this->db->update('quick_quote_items', $quant_up);
			}
			
		}
		
		// update shipping calc
		$this->load->model('shipping_model'); 
		$will_call = $this->input->post('will_call');
		$ship = $this->shipping_model->get_quick_ship($ship_zip, $zipfrom = 93292, $quote_items, $will_call);
		
		$data = array(	 	 	 	 	 	
			'distance' => $ship['distance'], 
			'actual_trucks' => $ship['actual_trucks'],
			'trucks' => $ship['trucks'],
			'ship_cost' => $ship['ship_cost']
			);
		$this->db->where('id', $qq_id);	
		$this->db->update('quick_quote', $data);		
		
		
		redirect('vecchio_admin/edit_quick_quote/' . $qq_id);
		
	}
	
	function void_quick_quote(){
		$void_id = $this->input->post('void_id');
		$data = array(
			'admin_void' => 1
		);
		$this->db->where('id', $void_id);
		$this->db->update('quick_quote', $data);
		redirect('vecchio_admin/quick_quotes');
	}
	
	function unvoid_quick_quote(){
		$void_id = $this->input->post('void_id');
		$data = array(
			'admin_void' => 0
		);
		$this->db->where('id', $void_id);
		$this->db->update('quick_quote', $data);
		redirect('vecchio_admin/quick_quotes');
	}
	
	function delete_quick_quote(){
		$delete_id = $this->input->post('delete_id');
		$this->db->delete('quick_quote', array('id' => $delete_id )); 
		$this->db->delete('quick_quote_items', array('qq_id' => $delete_id ));
	
		redirect('vecchio_admin/quick_quotes');
		
		
	}
	
	function promo(){
	 $result = $this->db->get('promo');
	 $data['promo'] = $result->result_array();
	 $data['main_section'] = '_content/new_promo';
	 $this->load->view('admin_template', $data);
	
	}
	
	function new_promo(){
		
		$code = $this->input->post('code');
		$perc = $this->input->post('disc_perc');
		$this->db->where('code', $code);
		$this->db->from('promo');
		$count = $this->db->count_all_results();
		
		$code = trim($code);
		$count_code = strlen($code);
		$code = strtoupper($code);
		
		if($count > 0 || $count_code < 5 ){
			redirect('vecchio_admin/promo/badpromo');
		} else if($perc > 0.99 ) {
			redirect('vecchio_admin/promo/badperc');
		} else {
		
		$data = array(
			'code' => $code,
			'disc_perc' => $perc,
			'memo' => $this->input->post('memo'),
			'active' => $this->input->post('active')
		);
		
		$this->db->insert('promo',$data);
		
		redirect('vecchio_admin/promo');
		
		}
		
	}
	
	function promo_activate(){
		
		$data = array('active' => $this->input->post('active'));
		$this->db->where('id', $this->input->post('promo_id'));
		$this->db->update('promo',$data);
		
		redirect('vecchio_admin/promo');
	}
	
	function promo_memo(){
		
		$data = array('memo' => $this->input->post('memo'));
		$this->db->where('id', $this->input->post('promo_id'));
		$this->db->update('promo',$data);
		
		redirect('vecchio_admin/promo');
	}
	
	
	function test_dat(){
		$this->load->model('prox_model');
		$data['products'] = $this->prox_model->get_prox('93657','5');
		$data['product_id'] = 5;  
		$data['main_section'] = '_content/choose_gy_sd';
		$this->load->view('admin_template', $data);	
	}
	
	
	function add_to_order(){
		
		$product_id = $this->input->post('product_id');
		$order_id = $this->input->post('order_id');
		if($order_id != "no_selected"){
		$return = $this->Tracking_model->add_product_to_order($product_id, $order_id);
		$this->return_message($return['message'], $return['info']);	

		} else {
					$error = 'No Order Selected';
					$link = 'vecchio_admin/';
					$linkname = 'Back to Main Page';
					$this->display_error($error, $link, $linkname);
			
		}
				
	}
	
	function update_shipping(){
		$this->load->model('shipping_model'); 
		if(is_numeric($this->uri->segment(3))){
		$order_id = $this->uri->segment(3);
		$order_name = $this->uri->segment(4);	
		} else {
		$order_id = $this->input->post('order_id');
		$order_name = $this->input->post('order_name');
		}
		$data['order_id'] = $order_id;
		$data['order_name'] = $order_name;
		$data['shipping'] = $this->shipping_model->get_shipping_info_bo($order_id);
		$data['admin'] = true;
		$data['main_section'] = '_content/update_shipping';
		$this->load->view('admin_template', $data);
	}
	
	function update_shipping_qq(){
		$this->load->model('shipping_model'); 
		if(is_numeric($this->uri->segment(3))){
		$qq_id = $this->uri->segment(3);
		$order_name = $this->uri->segment(4);	
		} else {
		$qq_id = $this->input->post('qq_id');
		$order_name = $this->input->post('order_name');
		}
		$data['qq_id'] = $qq_id;
		$data['order_name'] = $order_name;
		$data['shipping'] = $this->shipping_model->get_shipping_info_qq($qq_id);
		$data['admin'] = true;
		$data['main_section'] = '_content/update_shipping_qq';
		$this->load->view('admin_template', $data);
	}
	
	function update_ship_date($type){
		$orig_ship = $this->input->post('ship_date');
		$ship_date = date("Y-m-d", strtotime($orig_ship));
		$order_id = $this->input->post('order_id');
		$qq_id = $this->input->post('qq_id');
		if($type == 'order' && $order_id != '' && $orig_ship != ''){
			
			$data = array(
					'ship_date' => $ship_date
					);
			$this->db->where('order_id', $order_id);
			$this->db->update('shipping', $data);
			
			redirect('vecchio_admin/inprocessing/ship_updated');
				
		} else if($type == 'quote' && $qq_id != '' && $orig_ship != '') {

			$data = array(
					'ship_date' => $ship_date
					);
			$this->db->where('qq_id', $qq_id);
			$this->db->update('qq_shipping', $data);
						
			redirect('vecchio_admin/edit_quick_quote/'. $qq_id . '/ship_updated' );
			
		} else {
			 // fail miserably. 
					$error = 'No Date Selected';
					$link = 'vecchio_admin/';
					$linkname = 'Back to Main Page';
					$this->display_error($error, $link, $linkname);
		}
		
	
		

	}
	
	function mark_qq_shipped(){
		
		$qq_id = $this->input->post('qq_id');
		$status = $this->input->post('status');
		$orig_ship = $this->input->post('ship_date');
		$ship_date = date("Y-m-d", strtotime($orig_ship));
		$up = false;
		if($status == 1){ // On Account - Shipping Backlog 
			$new_status = 4; // On Account - Shipped
			$up = true;
		} else if($status == 2){ // Paid - Shipping Backlog
			$new_status = 3; // Paid - Shipped
			$up = true;
		} else {
			$new_status = $status;
		}
		
		if($up){
			$data = array(
					'ship_date' => $ship_date, 
					);
			$this->db->where('qq_id', $qq_id);
			$this->db->update('qq_shipping', $data);
			
			$stat = array(
					'status' => $new_status, 
					);
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $stat);
			
			redirect('vecchio_admin/edit_quick_quote/'. $qq_id . '/status_updated' );		
					
		} else {
			// fail miserably. 
					$error = 'Error - Status Update';
					$link = 'vecchio_admin/';
					$linkname = 'Back to Main Page';
					$this->display_error($error, $link, $linkname);			
		}	
		
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
