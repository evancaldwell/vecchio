<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_cc extends CI_Controller {
	
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
	
	var $sesh;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('ssl_helper');
		$this->load->model('User_model'); 
		$this->load->model('Tracking_model');
		$this->load->model('email_model');  
	    // $this->User_model-> is_logged_in();
	    $sesh['user_type'] = $this->session->userdata('user_type');
	    $sesh['user_id'] =  $this->session->userdata('user_id');
		$this->load->helper('form');
		define("AUTHORIZENET_API_LOGIN_ID",$this->config->item('at_login'));
		define("AUTHORIZENET_TRANSACTION_KEY",$this->config->item('at_password'));
		define("AUTHORIZENET_SANDBOX",false);
		$METHOD_TO_USE = "AIM";
		
		$this->load->library('authorizenet');
	    
	}
	
	function index(){
		echo "&nbsp;";
	}
	
	
	/* moved to TRACKING_MODEL 
	function get_order_items($order_id){
		$q = "SELECT products.serial_no,
					 product_type.description,
					 product_type.specs,
					 order_items.cust_cost,
					 order_items.product_id
			    FROM order_items, products, product_type
			    WHERE products.id = order_items.product_id
				AND  product_type.id = products.product_type_id
				AND order_items.order_id = '$order_id'";
		$result = $this->db->query($q);
		return $result->result_array();	
	}
	 */
	
	function display_response($status, $pay_type){
	//	force_ssl();
		
		$on_account = $this->uri->segment(5);
		
		// check if they are logged in and the user matches the order... do this for pete sakes... 
		$vec = $this->User_model->vecchio_settings();
		$days = $vec['order_to_ship'];
		$s = ($days > 1 ? 's' : '');
		$sd = date('m/d/Y') . " +" .$days . " Weekday" . $s ;
		$data['start_date'] = date('Y,m -1,d', strtotime($sd));

		if($status == 'tekG7734sfa'){
			
			if($on_account != 'oa'){	
			
				$id = $this->session->userdata('trans_id');	
				$this->db->select('order_id, transaction_id, amount');
				// id is not order id! its from the insert ID..
				$this->db->where('id', $id);
				$result = $this->db->get('payments');
				$pay_info = $result->result_array();
		
				$info[] = "Payment Approved";
				$info[] = "Transaction Number: " . $pay_info[0]['transaction_id'];
				$info[] = "Amount: $" . $pay_info[0]['amount'];
		
				$data['message'] = "Transaction Complete";
				$data['info'] = $info;
				$data['order_id'] = $pay_info[0]['order_id'];
		
			} else {
			
				$info[] = "Order Placed On Account ";
				$info[] = 'Open account (credit) terms are net 30 days, and all past due accounts are subject to an interest charge of 18% per year.';

				$data['message'] = "Transaction Complete ";
				$data['info'] = $info;
				$data['order_id'] = $this->session->userdata('c_order_id');
			
			
			}
				if($pay_type == 'cstg'){
					// load consumer 
					// set the order session variable to download later
					
					$sesh_d['c_order_id'] = (isset($pay_info[0]['order_id']) ? $pay_info[0]['order_id'] : $data['order_id'] );
				
					$this->session->set_userdata($sesh_d);
					$data['title'] = "VECCHIO Trees - Transaction Approved";
					$data['yesno'] = true;
					$data['main_section'] = '_chann/cc_reply'; 
					$this->load->view('site_template_2', $data);
					} else {
						$data['main_section'] = '_content/transaction_complete';
						$this->load->view('admin_template', $data);
					}
		} else { // failed transaction. 
			
			$this->db->select('order_id, response_reason_text, response_reason_code, response_code');
			$this->db->where('id', $id);
			$result = $this->db->get('payment_fail');
			$pay_info = $result->result_array();
			
			$info[] = "We're sorry, but we can't process your order at this time due to the following error:";
	   		$info[] = "Reason: " . $pay_info[0]['response_reason_text'];
	   		$info[] = "Response Code: " . $pay_info[0]['response_reason_code'] . " - " . $pay_info[0]['response_code'];
	   	
			
			$data['message'] = "Error: Transaction Incomplete";
			$data['info'] = $info;
			
			if($pay_type == 'cstg'){
				 // load consumer 
				$data['title'] = "VECCHIO Trees - Transaction Failed";
				$data['yesno'] = false;
				$data['main_section'] = '_chann/cc_reply'; 
				$this->load->view('site_template_2', $data);
				
				} else {
					$data['main_section'] = '_content/message';
					$this->load->view('admin_template', $data);	
				}
		}
	}
	
	function display_response_qq($status, $pay_type){
	//	force_ssl();
		$id = $this->session->userdata('trans_id');
		// check if they are logged in and the user matches the order... do this for pete sakes... 
		
		if($status == 'tekG7734sfa'){
		$this->db->select('qq_id, transaction_id, amount');
		// id is not order id! its from the insert ID..
		$this->db->where('id', $id);
		$result = $this->db->get('qq_payments');
		$pay_info = $result->result_array();
		
		$info[] = "Payment Approved";
		$info[] = "Transaction Number: " . $pay_info[0]['transaction_id'];
		$info[] = "Amount: $" . $pay_info[0]['amount'];
		
		$vec = $this->User_model->vecchio_settings();
		$days = $vec['order_to_ship'];
		$s = ($days > 1 ? 's' : '');
		$sd = date('m/d/Y') . " +" .$days . " Weekday" . $s ;
		$data['start_date'] = date('Y,m -1,d', strtotime($sd));

		$data['message'] = "Transaction Complete";
		$data['info'] = $info;
		$data['qq_id'] = $pay_info[0]['qq_id'];
		
				if($pay_type == 'cstg'){
				// load consumer 
				// set the order session variable to download later
				$sesh_d['c_qq_id'] = $pay_info[0]['qq_id'];
				
				$this->session->set_userdata($sesh_d);
				$data['title'] = "VECCHIO Trees - Transaction Approved";
				$data['yesno'] = true;
				$data['main_section'] = '_chann/cc_reply_qq'; 
				$this->load->view('site_template_2', $data);
				} else {
					$data['main_section'] = '_content/transaction_complete';
					$this->load->view('admin_template', $data);
				}
		} else {
			
			$this->db->select('qq_id, response_reason_text, response_reason_code, response_code');
			$this->db->where('id', $id);
			$result = $this->db->get('qq_payment_fail');
			$pay_info = $result->result_array();
			
			$info[] = "We're sorry, but we can't process your order at this time due to the following error:";
	   		$info[] = "Reason: " . $pay_info[0]['response_reason_text'];
	   		$info[] = "Response Code: " . $pay_info[0]['response_reason_code'] . " - " . $pay_info[0]['response_code'];
	   	
			
			$data['message'] = "Error: Transaction Incomplete";
			$data['info'] = $info;
			
			if($pay_type == 'cstg'){
				 // load consumer 
				$data['title'] = "VECCHIO Trees - Transaction Failed";
				$data['yesno'] = false;
				$data['main_section'] = '_chann/cc_reply_qq'; 
				$this->load->view('site_template_2', $data);
				
				} else {
					$data['main_section'] = '_content/message';
					$this->load->view('admin_template', $data);	
				}
		}
	}
/*	
	function get_order_images_info($order_id){
	$orders = $this->get_order_items($order_id);
	$count = count($orders);
	$data = array();
		for($i = 0; $i < $count; $i++){
			$this->db->select('file_name');
			$this->db->where('product_id');
		}
	
	}
	
	function render_email_response($pay_info){
		
		$this->load->model('email_model');
		$data[] = "Hello ".$this->input->post('fname').",";
		$data[] = "Thank you for creating an account with VECCHIO Trees. Please keep a copy of your log in credentials: ";
		$data[] = "<b>Username: ".$this->input->post('usern_email')."</b>";
		$data[] = "<b>Password: ".$this->input->post('password')."</b>";
		$data[] = "Sincerely, Vecchio Trees";
		$data[] = "vecchiotrees.com" ;
		$this->email_model->send_email_to($this->input->post('usern_email'), 'Welcome to VECCHIO Trees', $data,'html');
	}

*/
	
	function check_by_fax(){
		
		$this->load->model('user_model');
		$pr = $this->user_model->has_user_priv();
		if(!$pr){
		//	"Error: Your session has expired. Please log in again";
		redirect('dir/myaccount');	
		} else {
		$order_id = $this->input->post('order_id');
		
		$this->db->where('order_id', $order_id);
		$count = $this->db->count_all_results('check_by_fax');
		
		if($count == 0){
		
		$amount = $this->input->post('amount');
		$freight = $this->input->post('freight');

		$first_name = $this->input->post('first_namefx');
		$last_name = $this->input->post('last_namefx');
		$phone = $this->input->post('phonefx');
		$fax = $this->input->post('faxfx');
		$bank_acct_name = $this->input->post('bank_acct_namefx');
		$bank_name = $this->input->post('bank_namefx');
		$email = $this->input->post('emailfx');
		$expire_date = $this->input->post('expire_date');
		$now_date = date("Y-m-d H:i:s");
		$order_name = $this->input->post('order_name');
		
		$new_date = $now_date . " + 3 Weekdays" ;
		$new_exper_date = date('Y-m-d H:i:s', strtotime($new_date));
		
		$e_sig = $this->input->post('e_sig_mainfx');
		
		// STATUS 6 = FAX APPROVED - AWAITING APPROVAL
		$update = array(
			'expire_date' => $new_exper_date,
			'e_sig' => $e_sig,
			'status' => 6
		);
		
		$this->db->where('id', $order_id);
		$isup = $this->db->update('orders', $update);
		
		$datar = array(
				'order_id' => $order_id,
				'amount' => $amount,
				'freight' => $freight,
				'request_date' => $now_date
		);
		$payment = $this->db->insert('check_by_fax',$datar);
		
		
		$this->load->model('email_model');
		$data[] = "Hello Kara,";
		$data[] = "The following customer has requested to use Check By Fax:  ";
		$data[] = "Name: ".$first_name." ".$last_name;
		$data[] = "Phone: ".$phone;
		$data[] = "Fax: ".$fax;
		$data[] = "Email: ".$email;
		$data[] = "----------------------------------------";
		$data[] = "Order Name: ".$order_id . "-" . $order_name;	
		$data[] = "Amount: ".$amount;
		$data[] = "Freight: ".$freight;
		$data[] = "----------------------------------------";
		$data[] = "Sincerely, Vecchio Website Bot";
		$to = "kara@vecchiotrees.com, paul@vecchiotrees.com";
	//	$to = "tylerpenney@gmail.com";
		
		$this->email_model->send_email_to($to, 'Check By Fax - 002', $data,'text');
		
	//	echo "Success";
	
		$notice = " Customer has chosen to pay via Check By Fax and signed E-Signature: <u>" . $e_sig . "</u>" ; 
		$this->email_model->send_rep_notice($order_id, $notice, 'order');
		 		
		redirect('vecchio_cc/fax_approved/order/' . $order_id );
		
		} else { 
			echo "Fail";
		}
	} // logged in ? 

	}
	
	function fax_approved($type,$id){
		
		$this->load->model('user_model');
		$pr = $this->user_model->has_user_priv();
		if(!$pr){
		//	"Error: Your session has expired. Please log in again";
		redirect('dir/myaccount');	
		} else {
			$data['title'] = 'Check By Fax Approved';
			$data['id'] = $id;
			if($type == 'order'){
				$data['type'] = 'order';
			} else {
				$data['type'] = 'quote';
			}
			
			$data['main_section'] = '_chann/fax_reply'; 
			$this->load->view('site_template_2', $data);
			
		}
		
		
	}
	
	function process_response($response, $order_id, $pay_type = 'admin', $e_sig){
		$now_date = date("Y-m-d H:i:s");
	
		if ($response->approved) {
	        // Transaction approved! Do your logic here.	   
	    	$data = array(
				     'payment_date' => $now_date,
				     'status' => 2, // shipping backlog, baby
				 	 'e_sig' => $e_sig
					);
			$this->db->where('id', $order_id);
			$update = $this->db->update('orders', $data);
			
			$data = array(
					'order_id' => $order_id,
					'amount' => $response->amount,
					'freight' => $response->freight,
					'transaction_id' => $response->transaction_id,
					'method' => $response->method,
					'payment_date' => $now_date
			);
			$payment = $this->db->insert('payments',$data);
			$id = $this->db->insert_id();
			$sesh_d['trans_id'] = $id;
			
			$this->session->set_userdata($sesh_d);
			
			// send the email receipt, notify admin users
			
			$this->email_model->send_email_rec($order_id);
			$this->email_model->send_admin_rec($order_id);
			$notice = "Payment Submitted for Order via Credit Card ";
			$this->email_model->send_rep_notice($order_id, $notice, 'order');
			
			redirect('vecchio_cc/display_response/tekG7734sfa/' . $pay_type );
	    } else {
			$data = array(
					'order_id' => $order_id,
					'amount' => $response->amount,
					'freight' => $response->freight,
					'response_reason_text' => $response->response_reason_text,
					'response_reason_code' => $response->response_reason_code,
					'response_code' => $response->response_code,
					'method' => $response->method,
					'fail_date' => $now_date
			);
			$payment = $this->db->insert('payment_fail',$data);
			
			$id = $this->db->insert_id();
			$sesh_d['trans_id'] = $id;
			
			$this->session->set_userdata($sesh_d);

			redirect('vecchio_cc/display_response/jos2300dZ1/'. $pay_type );
	    }	
	}
	
	function process_response_qq($response, $qq_id, $pay_type = 'admin'){
		$now_date = date("Y-m-d H:i:s");
	
		if ($response->approved) {
	        // Transaction approved! Do your logic here.	   
	    	$data = array(
				     'payment_date' => $now_date,
				     'status' => 2
					);
			$this->db->where('id', $qq_id);
			$update = $this->db->update('quick_quote', $data);
			
			$data = array(
					'qq_id' => $qq_id,
					'amount' => $response->amount,
					'freight' => $response->freight,
					'transaction_id' => $response->transaction_id,
					'method' => $response->method,
					'payment_date' => $now_date
			);
			$payment = $this->db->insert('qq_payments',$data);
			$id = $this->db->insert_id();
			$sesh_d['trans_id'] = $id;
			
			$this->session->set_userdata($sesh_d);
			
			// send the email receipt, notify admin users
			
			$this->email_model->send_email_rec_qq($qq_id);
			$this->email_model->send_admin_rec_qq($qq_id);
			$notice = "Payment Submitted for Quote via Credit Card ";
			$this->email_model->send_rep_notice($qq_id, $notice, 'quote');
			
			redirect('vecchio_cc/display_response_qq/tekG7734sfa/' . $pay_type );
	    } else {
			$data = array(
					'qq_id' => $qq_id,
					'amount' => $response->amount,
					'freight' => $response->freight,
					'response_reason_text' => $response->response_reason_text,
					'response_reason_code' => $response->response_reason_code,
					'response_code' => $response->response_code,
					'method' => $response->method,
					'fail_date' => $now_date
			);
			$payment = $this->db->insert('qq_payment_fail',$data);
			
			$id = $this->db->insert_id();
			$sesh_d['trans_id'] = $id;
			
			$this->session->set_userdata($sesh_d);

			redirect('vecchio_cc/display_response_qq/jos2300dZ1/'. $pay_type );
	    }	
	}
	
	function enter_ship_date(){
	$order_id = $this->input->post('order_id');
	$ship_date = $this->input->post('ship_date');
	$pay_type = $this->input->post('pay_type');
	
	$date_new = date('Y-m-d',strtotime($ship_date));
	$this->db->where('order_id', $order_id);
			$update = array(
				'ship_date' => $date_new
			);
	$update = $this->db->update('shipping', $update);
	
	$this->load->model('tracking_model');
	$client = $this->tracking_model->get_client_info($order_id);
	$order_name = $this->tracking_model->get_order_name($order_id);
	
	$this->load->model('email_model');
	$data[] = "The following customer has updated the ship date for their completed order:  ";
	$data[] = "Name: ".$client['fname']." ".$client['lname'];
	$data[] = "Company: ".$client['company_name'];
	$data[] = "Phone: ".$client['phone'];
	$data[] = "Fax: ".$client['fax'];
	$data[] = "Email: ".$client['usern_email'];
	$data[] = "----------------------------------------";
	$data[] = "Order : ". $order_name;	
	$data[] = "----------------------------------------";
	$data[] = "Ship Date: ". date("l F j, Y", strtotime($ship_date));	
	$data[] = "----------------------------------------";
	$data[] = "Sincerely, Vecchio Website Bot";
	
	$to = "kara@vecchiotrees.com, paul@vecchiotrees.com";
	
	$this->email_model->send_email_to($to, 'Shipping Update - 004', $data,'text');
	$notice = "Customer has chosen a delivery date for their order: " . date("l F j, Y", strtotime($ship_date)); 
	$this->email_model->send_rep_notice($order_id, $notice, 'order');
	
	if($update){
		if($pay_type == 'cstg'){
			redirect('dir/myaccount/');
		} else {
			redirect('vecchio_admin/inprocessing');
		}
	}
	
	}
	
	function enter_ship_date_qq(){
	$qq_id = $this->input->post('qq_id');
	$ship_date = $this->input->post('ship_date');
	$pay_type = $this->input->post('pay_type');
	$on_account = $this->input->post('on_account');
	
	
	$date_new = date('Y-m-d',strtotime($ship_date));
	$this->db->where('qq_id', $qq_id);
			$update = array(
				'ship_date' => $date_new
			);
	$update = $this->db->update('qq_shipping', $update);
	
	// put order on account and send out shipping order 
	if($on_account == 'on_account'){
		
		$now_date = date("Y-m-d H:i:s");
		$this->db->where('id', $qq_id);
		$acc = array('status' => 1, 'on_account_date' => $now_date);
		$this->db->update('quick_quote', $acc);
		$pay_type = 'cstg';
	
	}																		
	
	$this->load->model('email_model');
	$this->email_model->qq_shipping_email($qq_id, $on_account);
	$notice = "Customer has chosen a delivery date for their order: " . date("l F j, Y", strtotime($ship_date)); 
	$this->email_model->send_rep_notice($qq_id, $notice, 'quote');
	
	// START HERE -- SHOW WHAT IS PAID FOR AND WHAT IS'NT

	if($pay_type == 'cstg'){
			redirect('dir/myaccount/');
	} else {
			redirect('vecchio_admin/inprocessing');
	}

	
	}
	
	
	public function validate_credit_card()
	{
	        $order_id = $this->input->post('order_id');
			$amount = $this->input->post('amount');
			$coupon = $this->input->post('coupon');
			$card_num = $this->input->post('card_num');
			$exp_date = $this->input->post('exp_date');
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$address = $this->input->post('address');
			$city = $this->input->post('city');
			$state = $this->input->post('state');
			$zip = $this->input->post('zip');
			$email = $this->input->post('email');
			$card_code = $this->input->post('card_code');
			
			$freight = $this->input->post('freight');
			
			$ship_to_address = $this->input->post('ship_address');
			$ship_to_city = $this->input->post('ship_city');
			$ship_to_state = $this->input->post('ship_state');
			$ship_to_zip = $this->input->post('ship_zip');
			$ship_to_company = $this->input->post('ship_location');
			
			$e_sig = $this->input->post('e_sig_main');
			
			$pay_type = $this->input->post('pay_type');
			
			
	        $this->authorizenet->setFields(
				array(
					'amount' => $amount,
					'freight' => $freight,
					'card_num' => $card_num,
					'exp_date' => $exp_date,
					'first_name' => $first_name,
					'last_name' => $last_name,
					'address' => $address,
					'city' => $city,
					'state' => $state,
					'country' => 'USA',
					'zip' => $zip,
					'email' => $email,
					'card_code' => $card_code,
					'ship_to_address' => $ship_to_address,
					'ship_to_city' => $ship_to_city,
					'ship_to_state' => $ship_to_state,
					'ship_to_zip' => $ship_to_zip,
					'ship_to_company' => $ship_to_company
					)
			);
			
	
		   $order_items = $this->Tracking_model->get_order_items($order_id);
		   foreach($order_items as $item){
			$this->authorizenet->addLineItem(
			  $item['serial_no'], // Item Id
			  $item['description'], // Item Name
			  $item['specs'], // Item Description
			  '1', // Item Quantity
			  $item['cust_cost'], // Item Unit Price
			  0 // Item taxable
			  );
			}
			
			if($coupon != ''){
				$sale->setCustomField("coupon_code", $coupon);
			}
			
			$response = $this->authorizenet->authorizeAndCapture();
		    $this->process_response($response, $order_id, $pay_type, $e_sig);

	}
	
	// FOR QUICK QUOTE PROCESSING
	public function validate_credit_card_qq()
	{
	        $qq_id = $this->input->post('qq_id');
			
			
			$pay_type = $this->input->post('pay_type');
			
			$disc_notice = '';
			$disc = $this->input->post('discount');
			if($disc > 0){
			$disc_notice = '$' . number_format($disc, 2, '.', ',') . ' Discount Placed On Your Order';
			} 
			
	        $this->authorizenet->setFields(
				array(
					'amount' => $this->input->post('amount'),
					'freight' => $this->input->post('freight'),
					'card_num' => $this->input->post('card_num'),
					'exp_date' => $this->input->post('exp_date'),
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'address' => $this->input->post('address'),
					'city' => $this->input->post('city'),
					'state' => $this->input->post('state'),
					'country' => 'USA',
					'zip' => $this->input->post('zip'),
					'email' => $this->input->post('email'),
					'card_code' => $this->input->post('card_code'),
					'ship_to_address' => $this->input->post('ship_address'),
					'ship_to_city' => $this->input->post('ship_city'),
					'ship_to_state' => $this->input->post('ship_state'),
					'ship_to_zip' => $this->input->post('ship_zip'),
					'ship_to_company' => $this->input->post('ship_location'),
					'header_email_receipt'=> $disc_notice
					)
			);
			

			
	
		   $qq_items = $this->Tracking_model->get_qq_items($qq_id);
			
		
		   foreach($qq_items as $item){
			$this->authorizenet->addLineItem(
			  $item['product_id'], // Item Id
			  $item['description'], // Item Name
			  $item['specs'], // Item Description
			  $item['quantity'], // Item Quantity
			  $item['ind_cost'], // Item Unit Price
			  0 // Item taxable
			  );
			}
			
			
			$response = $this->authorizenet->authorizeAndCapture();
		    $this->process_response_qq($response, $qq_id, $pay_type);

	}
	
	function order_on_account(){
		
		$e_sig = $this->input->post('e_sig_main_oc');
		$order_id = $this->input->post('order_id');
		$pay_type = $this->input->post('pay_type');
		$amount = $this->input->post('amount');
		$now_date = date("Y-m-d H:i:s");
		
		$cc_id = $this->session->userdata('user_id');
		
		$avail = $this->Tracking_model->can_credit($cc_id, '', '', $order_id);
		
		if($avail['can_credit']){ // check if they have avail credit for this order
			
		$update = array(
			'e_sig' => $e_sig,
			'status' => 4,
			'on_account_date' => $now_date
		);
		
		$this->db->where('id', $order_id);
		$this->db->update('orders', $update);

	
		if($pay_type == 'cstg'){
			// load consumer 
			// set the order session variable to download later
			$sesh_d['c_order_id'] = $order_id;

			$this->session->set_userdata($sesh_d);
			
			$this->email_model->send_email_rec($order_id);
			$this->email_model->send_admin_rec($order_id);
			$notice = " Customer has signed placed Order On Account. "; 
			$this->email_model->send_rep_notice($order_id, $notice, 'order');
			
			redirect('vecchio_cc/display_response/tekG7734sfa/cstg/oa');
		
		} else {
				$data['main_section'] = '_content/transaction_complete';
				$this->load->view('admin_template', $data);
		}
		
		
	} else {
			
			$info[] = "We're sorry, but we can't process your order at this time due to the following error:";
	   		$info[] = "Reason: Order Exceeds Vecchio Credit Limit";
	   		$info[] = "Response Code: CREDEXC-322" ;
	   	
			
			$data['message'] = "Error: Transaction Incomplete";
			$data['info'] = $info;
			$data['title'] = "VECCHIO Trees - Transaction Failed";
			$data['yesno'] = false;
			
			$data['main_section'] = '_chann/cc_reply'; 
			$this->load->view('site_template_2', $data);
			
	} // can credit
		
		
	}
	

	
	// FOR QUICK QUOTE PROCESSING
	/*
	public function validate_cr()
	{

		if($this->uri->segment(3) == '834jk2'){	
	        $this->authorizenet->setFields(
				array(
					'amount' => '',
					'freight' => '',
					'card_num' => '',
					'exp_date' => '',
					'first_name' => '',
					'last_name' => '',
					'address' => '',
					'city' => '',
					'state' => '',
					'country' => '',
					'zip' => '',
					'email' => '',
					'card_code' => '',
					'ship_to_address' => '',
					'ship_to_city' => '',
					'ship_to_state' => '',
					'ship_to_zip' => '',
					'ship_to_company' => ''
					)
			);
			
	
		   
			$this->authorizenet->addLineItem(
			  'TEST001', // Item Id
			  'Test Description', // Item Name
			  'Test Specs', // Item Description
			  '1', // Item Quantity
			  '1.00', // Item Unit Price
			  0 // Item taxable
			  );
		
			
			$response = $this->authorizenet->authorizeAndCapture();
		    echo '<pre>';
		    print_r($response);
		
		}

	}
	
	*/
	
	public function validate_echeck(){
		
		$order_id = $this->input->post('order_id');
		$coupon = $this->input->post('coupon');	
		$amount = $this->input->post('amount');

		$bank_aba_code = $this->input->post('bank_aba_code');
		$bank_acct_num = $this->input->post('bank_acct_num');
		$bank_acct_type = $this->input->post('bank_acct_type');
		$bank_name = $this->input->post('bank_name');
		$bank_acct_name = $this->input->post('bank_acct_name');
		$echeck_type = "WEB";
		
		$pay_type = $this->input->post('pay_type');
		
		$this->authorizenet->amount = $amount;
		$this->authorizenet->freight = $this->input->post('freight');
		$this->authorizenet->ship_to_address = $this->input->post('ship_address');
		$this->authorizenet->ship_to_city = $this->input->post('ship_city');
		$this->authorizenet->ship_to_state = $this->input->post('ship_state');
		$this->authorizenet->ship_to_zip = $this->input->post('ship_zip');
		$this->authorizenet->ship_to_company = $this->input->post('ship_location');
		$this->authorizenet->email = $this->input->post('email');
	
		
		$this->authorizenet->setECheck(
		  	$bank_aba_code, // bank_aba_code (routing number)
		    $bank_acct_num, // bank_acct_num
		    $bank_acct_type, // bank_acct_type
		    $bank_name, // bank_name
		    $bank_acct_name, // bank_acct_name
		    $echeck_type // echeck_type
		);
		
		   $order_items = $this->Tracking_model->get_order_items($order_id);
		   foreach($order_items as $item){
			$this->authorizenet->addLineItem(
			  $item['serial_no'], // Item Id
			  $item['description'], // Item Name
			  $item['specs'], // Item Description
			  '1', // Item Quantity
			  $item['cust_cost'], // Item Unit Price
			  'N' // Item taxable
			  );
			}
			
			if($coupon){
				$sale->setCustomField("coupon_code", $coupon);
			}
		
		$response  = $this->authorizenet->authorizeAndCapture();
	    
		$this->process_response($response, $order_id, $pay_type);
	}
	
	
	

	

}