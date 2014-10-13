<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class Dir extends CI_Controller {

	/* ================================
	
	Tracking Variables orders.status
	Inventory Based Orders 						|					Quick Quote Variables
	0 = NULL 												0 = Null
	1 = Items in Order Tagged for 24 Hours					1 = On Account / Shipping Backlog
	2 = Paid / Shipping Backlog								2 = Paid / Shipping Backlog
	3 = Paid / Shipped										3 = Paid / Shipped
	4 = On Account / Shipping Backlog						4 = On Account / Shipped
	5 = On Account / Shipped
	7 = Expired 
	==================================
	*/	
		
	function __construct()
	{
		parent::__construct();
 		$this->load->library('form_validation');
		$this->load->model('User_model');
		$this->User_model->get_cart_count();
		$this->User_model->check_expired();
		$this->load->model('Site_model');
		$this->load->model('product_model');
		$this->load->helper('html', 'download');
		$this->load->helper('ssl_helper');
	
	}
	
	function index()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['main_section'] = '_chann/index_view'; 
		$data['title'] = "VECCHIO Trees";
		$data['call_to_action'] = 1;
		$this->load->view('site_template_2', $data);	
	}
	
	
	function no_slide()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['main_section'] = '_chann/index_view_no_slide'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);	
	}
	
	function log_in()
	{
	//	if (function_exists('force_ssl')) remove_ssl();
		force_ssl();
		$this->db->select('id, lname, fname');
		$this->db->where('user_type' , 'rep');
		$query = $this->db->get('users');
		$data['reps'] = $query->result_array();
		$data['main_section'] = '_chann/sign_up'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);	
	}
	
	
	function log_in_capture(){
		$query = $this->User_model->validate();
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
				redirect('vecchio_admin/');
			} else if($query[0]['user_type'] == 'rep'){
				redirect('vecchio_rep/');
			} else {
				redirect('dir/myaccount/');;
			}
		}
		else // incorrect username or password
		{
		 	redirect('dir/log_in/my_account/login_error');
		}
	
	}
	
	function myaccount(){
		
		$this->User_model->check_expired();
		$yn = $this->User_model->has_user_priv();
		if(!$yn){
			redirect('dir/log_in/my_account');
		}
		force_ssl();
		$this->load->model('tracking_model');
		$this->load->model('product_model');
		$this->load->model('site_model');
		$data['w'] = $this->site_model->display_warranty();

		$data['qq'] = $this->tracking_model->qq_quick_list();
		$data['qq_quotes'] = array();

		$userid = $this->session->userdata('user_id');
		if($this->uri->segment(3) == 'quote_id'){
		
			$qq_id = $this->uri->segment(4);
			// check 
			$this->db->where('customer_id', $userid);
			$this->db->where('id', $qq_id);
			$n = $this->db->count_all_results('quick_quote');
			if($n == 1){
			$data['qq_quotes'] = $this->tracking_model->get_quick_quote_items($qq_id);
			$data['qq'] = array('new'=>array('show'));
			} else {
				$this->session->sess_destroy();
				redirect('dir/log_in/my_account');
			}
		}
		
		// rep
		$custid = $this->session->userdata('user_id');
		$q_rep = "SELECT rep.fname, rep.lname, rep.phone, rep.usern_email
				  FROM users as rep, users
				  WHERE rep.id = users.rep_id
				  AND users.id = '$custid'";
						
		$res = $this->db->query($q_rep);
		$repstuff = $res->result_array();
		
		if(!empty($repstuff)){
		 // assigned rep 
		$data['rep']['name'] = $repstuff[0]['fname'] . " " . $repstuff[0]['lname'];
		$data['rep']['phone'] = $repstuff[0]['phone'];
		$data['rep']['email'] = $repstuff[0]['usern_email'];
		} else {
			// house account
			$data['rep']['name'] = 'Vecchio Trees';
			$data['rep']['phone'] = '855/819-7777';
			$data['rep']['email'] = 'paul@vecchiotrees.com';		
		}
		

		
		// check to see if they have selected a quick quote to use as a guide for their order
		if($this->session->userdata('qq_id') != 0){
		$qq_id = $this->session->userdata('qq_id');
		$result = $this->tracking_model->qq_order_compare($qq_id);
			if(!empty($result)){
				$tag[0] = $result['order'];
				$data['order_items'] = $result['order_items'];
				$data['qq_items'] = $result['qq_items'];
				$data['missing'] = $result['missing'];
				$data['o'] = $result['o'];
				$data['qq_o'] = $result['qq'];
				$data['qq_cart'] = 1;
			} else {
				$tag = array();
			}
		} else {
		// they have not selected a quick quote as their order. load normal	
			
		$tag = $this->tracking_model->get_orders(1, '', $userid);  
		}
		
	  	$data['tag'] = $tag;
		$data['pend'] = array();
		$data['credit'] = array();
		
		if(!empty($tag)){
			
		$this->db->where('order_id', $tag[0]['id']);
		$count = $this->db->count_all_results('check_by_fax');
		$data['fax_approved'] = ($count > 0 ? 1 : 0 ); 
		
		// check to see if they can use credit for this order 
		$credit = $this->tracking_model->can_credit($userid, '', '', $tag[0]['id']);
		$data['credit'] = $credit;
		
		// check to see if their products can be boxed 
		$data['can_box'] = $this->tracking_model->can_box($tag[0]['id'], 'order');
		
		
		} else {
		$data['fax_approved'] = 0;
		} 
		
		$vec = $this->User_model->vecchio_settings();
		$days = $vec['order_to_ship'];
		$s = ($days > 1 ? 's' : '');
		$sd = date('m/d/Y') . " +" .$days . " Weekday" . $s ;
		$data['start_date'] = date('Y,m -1,d', strtotime($sd));
			
		$data['main_section'] = '_chann/my_account'; 
		$data['title'] = "VECCHIO Trees";
		$data['active'] = 'true';
		$data['avail'] = $this->product_model->get_count_products_avail(); 
		$this->load->view('site_template_2', $data);
	}
	
	function addnewuser(){
		
		$message =	$this->User_model->create_account();
		echo $message;
	}
	
	
	function avail_menu(){
		// true = return json.
		$json = $this->product_model->get_count_products_avail(true);
		echo $json;
	}
	
	function set_qq_guide(){
	// this sets the qq_id 	
		$qq_id = $this->input->post('qq_id');		
		if($qq_id != ''){
		$this->load->model('tracking_model');
		$product_type_id = $this->tracking_model->set_qq_sesh($qq_id);
			if($product_type_id != 'to_cart'){
			redirect('dir/sales/'. $product_type_id);
			} else {
			redirect('dir/myaccount/cart');
			}
		} 
		
	}
	
	function remove_qq_guide(){
		
		// set the qq_id in session var
		$qq_id = $this->session->userdata('qq_id');
		$this->load->model('tracking_model');
		$this->session->set_userdata('qq_id', 0);
		$order_id = $this->tracking_model->qq_to_order($qq_id);
		if($order_id != 0){
			$data = array(
				'qq_id' => 0
			);
			$this->db->where('id', $order_id);
			$this->db->update('orders',$data);
		}
		redirect('dir/myaccount/cart');
		
	}
	
	function quick_quote_e_sig(){
		$e_sig = $this->input->post('qq_e_sig');
		if($e_sig != '' && strlen($e_sig) > 5){
		$data['e_sig'] = $e_sig;
		$this->db->where('id', $this->input->post('qq_id'));
		$this->db->update('quick_quote', $data);	
		$this->load->model('email_model');
		$notice = "Customer has signed E-Signature: <u>".$e_sign."</u>"; 
		$this->email_model->send_rep_notice($this->input->post('qq_id'), $notice, 'quote');
		}
		 redirect('dir/myaccount/quote_id/'. $this->input->post('qq_id'));
	}
	
	
	function add_product(){
		
		$this->load->model('user_model');
		$pr = $this->user_model->has_user_priv();
		if(!$pr){
		redirect('dir/log_in');	
		} else {
		
		$this->load->model('tracking_model');
		$product_id = $this->input->post('product_id');
		
		// the system will find what order they have on file that is unprocessed, otherwise it will start a new one. 
		$order_id = '';
		$customer_id = $this->session->userdata('user_id');
		$result = $this->tracking_model->add_product_to_order($product_id, $order_id, $customer_id);
		echo $result['yesno'];
		}
	}
	
	function remove_item(){	
			$this->load->model('user_model');
			$pr = $this->user_model->has_user_priv();
			if(!$pr){
			redirect('dir/log_in');	
			} else {
			$this->load->model('tracking_model');
			$return = $this->tracking_model->remove_product_from_order();
				echo $return['message'];
			}
	}
	
	function quote(){
		//$this->User_model->check_expired();
		//$yn = $this->User_model->has_user_priv();
		//if(!$yn){
		//	redirect('dir/log_in/my_account');
		//}
		//else{
			$this->load->library('session');
			//$productsQuoteIndex = $this->session->userdata('productsQuoteIndex');
			//$data['productsQuoteIndex'] = $productsQuoteIndex
			$data['main_section'] = '_chann/quote_view'; 
			$data['title'] = "VECCHIO Trees";
			$this->load->view('site_template_2', $data);
		//}
	}	

	function add_product_to_quote(){
		//$this->load->model('user_model');
		//$pr = $this->user_model->has_user_priv();
		//if(!$pr){
		//	redirect('dir/log_in');	
		//}
		//else {
			//$this->load->library('session');
			//$productsQuoteIndex = $this->session->userdata('productsQuoteIndex');
			if (isset($_SESSION['quote'])){
				$quote = $_SESSION['quote'];
				$product_name = $this->input->post('product_name');
				$product_quantity = $this->input->post('product_quantity');
				$product_size = $this->input->post('product_size');
				$array = array('product_name' => $product_name, 'product_quantity' => $product_quantity, 'product_size' => $product_size);
				array_push($quote, $array);
				$_SESSION['quote'] = $quote;
				echo 'Success';
			}
			else{
				$product_name = $this->input->post('product_name');
				$product_quantity = $this->input->post('product_quantity');
				$product_size = $this->input->post('product_size');
				$quote = array('product_name' => $product_name, 'product_quantity' => $product_quantity, 'product_size' => $product_size);
				$_SESSION['quote'][0] = $quote;
				echo 'Success';
			}
		//}
	}
	
	function remove_product_from_quote(){	
		//$this->load->model('user_model');
		//$pr = $this->user_model->has_user_priv();
		//if(!$pr){
		//	redirect('dir/log_in');	
		//}
		//else {
			if (isset($_SESSION['quote'])){
				$quote = $_SESSION['quote'];
				$product_name = $this->input->post('product_name');
				$product_quantity = $this->input->post('product_quantity');
				$product_size = $this->input->post('product_size');
				foreach ($quote as $index => $data) {
					if ($data['product_name'] == $product_name && $data['product_quantity'] == $product_quantity && $data['product_size'] == $product_size) {
						unset($quote[$index]);
						$quote = array_values($quote);
						$_SESSION['quote'] = $quote;
						echo 'Success';
					}
				}
			}
			else{
				echo 'Fail';
			}
		//}
	}
	
	function add_remove_box(){
		
		$response = array('status' => 0);
		$this->load->model('user_model');
		$pr = $this->user_model->has_user_priv();
		if(!$pr){
		redirect('dir/log_in');	
		} else {
			$boxed = $this->input->post('boxed');
			$order_id = $this->input->post('order_id');
			$this->load->model('tracking_model');
			$box_price = $this->tracking_model->can_box($order_id, 'order');
			if($box_price > 0){
				$data = array(
					'boxed' => $boxed
				);
				$this->db->where('id', $order_id);
				$this->db->update('orders', $data);
			
				$response['status'] = 1;
			}
			
		}
		
		echo json_encode($response);
		
	}
	
	function add_remove_box_qq(){
		
		$response = array('status' => 0);
		$this->load->model('user_model');
		$pr = $this->user_model->has_user_priv();
		if(!$pr){
		redirect('dir/log_in');	
		} else {
			$boxed = $this->input->post('boxed');
			$qq_id = $this->input->post('qq_id');
			$this->load->model('tracking_model');
			$box_price = $this->tracking_model->can_box($qq_id, 'quick_quote');
			if($box_price > 0){
				$data = array(
					'boxed' => $boxed
				);
				$this->db->where('id', $qq_id);
				$this->db->update('quick_quote', $data);
			
				$response['status'] = 1;
			}
		}
		
		echo json_encode($response);
		
	}
	
	function update_po(){
		$po = $this->input->post('po');
		$qq_order = $this->input->post('qq_order');
		$qq_id = $this->input->post('qq_id');
		$data = array(
			'po_number' => $po
		);
		if($qq_order == 'qq'){ // if quick quote
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $data);
			redirect('dir/myaccount/quote_id/' . $qq_id);
		} else { // if order
			$order_id = $qq_id;
			$this->db->where('id', $order_id);
			$this->db->update('orders', $data);	
			redirect('dir/myaccount/cart/');		
		}
	}

	
	
	
	
	function email_quote(){	
		$to = $this->input->post('email_to');
		$order_id = $this->input->post('order_id');
		$this->load->model('email_model');
		$sent = $this->email_model->gen_quote_email($order_id, $to);
		$notice = "Customer has email order to: ". $to; 
		$this->email_model->send_rep_notice($order_id, $notice, 'order');
		echo $sent;
	}
	
	function apply_promo(){

		$promo_code = $this->input->post('promo_code');
		$order_id = $this->input->post('order_id');

		// check to make sure the order is in "tagged" status

		$this->db->select('status');
		$this->db->where('id', $order_id);
		$result = $this->db->get('orders');
		$res = $result->result_array();
		$status = $res[0]['status'];

		$this->load->model('shipping_model');
		$discount = $this->shipping_model->check_dc($promo_code);

		if($discount != 0 && $status == 1){
		 // apply discount, send message back to refresh the page..
		 $data = array(
				'code' => $promo_code
				);
		$this->db->where('id',$order_id);
		$this->db->update('orders', $data);

		echo "discount applied";	

		} else {

		echo "fail";

		}
	}
	
	function update_shipping_capture(){
		$this->load->model('user_model');
		$pr = $this->user_model->has_user_priv();
		if(!$pr){
		 echo "Error: Your session has expired. Please log in again";	
		} else {
		
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
				echo " Error: The zip code entered is not valid. ";
			}
		}
	} // end if need to redirect
			
	}
	
	function quote_shipping_capture(){
		$this->load->model('user_model');
		$pr = $this->user_model->has_user_priv();
		if(!$pr){
		//	"Error: Your session has expired. Please log in again";
		redirect('dir/myaccount');	
		} else {
				$will_call = $this->input->post('will_call');	
				if($will_call == 1) { // customer pickup - no freight
					
					$qq_id = $this->input->post('qq_id');
					$qq_shipping_id = $this->input->post('qq_shipping_id');
					$data_ship = array(
						'location' => $this->input->post('qqlocation'),
						'location_phone' => $this->input->post('qqlocation_phone'),
						'ship_address' => '',
						'ship_city' => '',
						'ship_state' => '',
						'ship_zip' => ''
					);
					$this->load->model('tracking_model');
					$return = $this->tracking_model->update_qq_shipping_address($qq_shipping_id, $data_ship, $qq_id, $will_call);
					redirect('dir/myaccount/quote_id/' . $qq_id );	
					
				} else { 
					$count = $this->check_zip($this->input->post('qqship_zip'));
					if($count == 1){	
						$qq_id = $this->input->post('qq_id');
						$qq_shipping_id = $this->input->post('qq_shipping_id');
						$data_ship = array(
							'location' => $this->input->post('qqlocation'),
							'location_phone' => $this->input->post('qqlocation_phone'),
							'ship_address' => $this->input->post('qqship_address'),
							'ship_city' => $this->input->post('qqship_city'),
							'ship_state' => $this->input->post('qqship_state'),
							'ship_zip' => $this->input->post('qqship_zip')
							);
							$this->load->model('tracking_model');
							$return = $this->tracking_model->update_qq_shipping_address($qq_shipping_id, $data_ship, $qq_id);
							redirect('dir/myaccount/quote_id/' . $qq_id );
						} else {
							$qq_id = $this->input->post('qq_id');
							echo " Error: The zip code entered is not valid. ";                  
							sleep(10);
							redirect('dir/myaccount/quote_id/' . $qq_id);
						}
						
				} // will_call
	} // end if need to redirect
			
	}
	
	function check_shipping_date(){
		$this->load->model('shipping_model');
		$ship_date = $this->input->post('ship_date');
		
		$date = date('Y-m-d',strtotime($ship_date));
	//	$grow_yard = $this->input->post('grow_yard');
		$get_result = $this->shipping_model->check_date_aval($date, $grow_yard = '');
	    // $grow_yard_name = $this->product_model->get_grow_yard_name($grow_yard);
	    $date_new = date('l F j, Y',strtotime($ship_date));
		
		// get the setting for how many shipments per day
		$vec = $this->User_model->vecchio_settings();
		$ship_per_day = $vec['ship_per_day'];
			if($get_result[0]['cn'] >= $ship_per_day){
			echo '<span style="color:#f00">Shipping Booked On '. $date_new .   ', Choose Another</span>';
			} else {
			echo '<span style="color:#0c0">Shipping Available On '. $date_new . ' </span>';
			}
	
	}	
	
	
	function our_trees()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['main_section'] = '_chann/our_trees_view'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);	
	}
	
	function our_trees_dynamic()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['main_section'] = '_chann/our_trees_dynamic_view'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);	
	}
	
	function our_trees_olives(){
		if (function_exists('force_ssl')) remove_ssl();
		$data['avail'] = $this->product_model->get_count_products_avail(false,true); 
		$data['main_section'] = '_chann/ot_olives_view'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);
	}
	
	function all_trees(){
		if (function_exists('force_ssl')) remove_ssl();
		$this->db->select('short_name, grow_yard_name');
		$this->db->where('active',1);
		$result = $this->db->get('grow_yards');
		$data['grow_yards'] = $result->result_array();
		$data['avail'] = $this->product_model->get_count_products_avail(false,false); 
		$this->load->view('_chann/all_trees', $data);
	}
	
	function info_page($product_type_id){
		if (function_exists('force_ssl')) remove_ssl();
		$this->db->where('id', $product_type_id);
		$result = $this->db->get('product_type');
		$res = $result->result_array();
		$data['description'] = $res[0]['text_description'];
		$data['title'] = $res[0]['description'] . " - " . "PC: " . $res[0]['product_code'];
		$data['zones'] = $res[0]['zone'];
		$data['watering'] = $res[0]['watering'] . " <br /> " . $res[0]['exposure'];
		$this->load->view('_chann/infopage', $data);
		
	}
	
	function specs_info()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['main_section'] = '_chann/specs_info_view'; 
		$data['title'] = "VECCHIO Trees";
		$sg = $this->uri->segment(3);
		switch ($sg){
			case 'olives':
			$img = "V_SpecsInfo_Olives.png";
			$hd = "OLIVE TREES";
			$txt = "A native of the Mediterranean region, the olive tree has been in cultivation since
			2500 BC. As it dates back to the beginning of mankind, hundreds of varieties have
			been developed. The tree is easy to grow and will tolerate many soil types, but
			prefers the soil to be well drained. Vecchio Trees offers five of the top olive tree
			varieties from around the world; Arbequino, Manzanillo, Ascalano, Sevillano and the
			Fruitless Olive will all add beauty to your home. Zone 8-10.";
			break;
			case 'citrus':
			$img = "V_SpecsInfo_Citrus.png";
			$hd = "CITRUS TREES";
			$txt = "Dating back to Alexander the Great, the citrus tree has found its way into many
			cultures for landscapers and home gardeners in Florida, Arizona, California and
			along the Gulf Coast. A citrus tree is often the ideal choice as a landscape fruit tree.
			With its appealing shape, fragrant blossoms and edible fruit, a citrus can enhance
			any garden or estate. Zone 8-9.";
			break;
			case 'figs':
			$img = "V_SpecsInfo_Fig.png";
			$hd = "FIG TREES";			
			$txt = "Fig trees yield one of the world’s most beloved fruits. As evidenced by ancient texts,
			figs have been a staple of the human diet since the beginning of recorded history.
			You will be delighted by the beauty our orchard’s fig trees will add to any style of
			landscape from ancient to contemporary. Zone 7-10";
			break;
			case 'almonds':
			$img = "V_SpecsInfo_Almond.png";
			$hd = "ALMOND TREES";
			$txt = "The almond is botanically a stone fruit related to the cherry, the plum, and the
			peach. Almonds are mentioned as far back in history as the Bible. Their exact
			ancestry in unknown, but almonds are thought to have originated in China and
			Central Asia. <br /><br />
			The almond tree was brought to California from Spain in the mid-1700's by the
			Franciscan Padres. By the turn of the 20th century, the almond industry was firmly
			established in the Sacramento and San Joaquin areas of California's great Central
			Valley. Today we are equipped with the knowledge that time brings, transplanting
			this tree with ease and success.";
			break;
			case 'pomegranates':
			$img = "V_SpecsInfo_Pomegrant.png";
			$hd = "POMEGRANATE TREES";
			$txt = "The Pomegranate tree is native to ancient Persia and has been cultivated over the
			entire Mediterranean region since the beginning of written history. The tree was
			brought to California in 1769 by Spanish settlers and has become a wildly popular
			fruit tree today. The tree's appeal has grown over the years not only for the juice of
			its fruit, but also its widely increasing use as an ornamental. Zone 4-9.";
			break;
			case 'italian_cypress':
			$img = "V_SpecsInfo_Cypress.png";
			$hd = "ITALIAN CYPRESS TREES";
			$txt = "One cannot think of Tuscany without thinking of the magnificent cypress tree,
			so quintessential and symbolic of the Tuscan landscape that it has adopted the
			name \"The Tuscan Cypress Tree.\” Although this is a fitting name, it does not
			correctly identify the tree's true place of origin, which was almost certainly Persia.
            <br /><br />
			It was brought to the Tuscan area by the mysterious Etruscan tribes-people many
			thousands of years later. Zone 5 -10.";
			break;
			case 'vecchio_gold':
			$img = "V_SpecsInfo_VecchioGold.png";
			$hd = "VECCHIO GOLD";
			$txt = "Along our travels, we often find specimen trees that are truly like no other. Vecchio
			Gold are one-of-a-kind works of art from mother nature. We delicately side box
			these trees and take them to our nursery, where they are cared for until they are
			ready for transplant. At that time our trained professionals facilitate the relocation
			of each specimen tree, meeting our client's highest expectations through the process";
			break;
			default:
			$img = "V_SpecsInfo_Intro.png";
			$hd = "SPECS &amp; INFORMATION";
			$txt = "Vecchio Trees are field grown and meticulously cared for by trained nursery and
			horticulture experts in each of our six actively working locations. We are affiliated
			with a certified arborist who inspects our farms on a regular basis. Our quality and
			procurement of specimen trees, are of the utmost importance.";
		}
		$data['imgfile'] = $img;
		$data['txtfile'] = $txt;
		$data['hdfile'] = $hd;
		$this->load->view('site_template_2', $data);	
	}
	
	function press()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$query = $this->db->get('events');
		$data['event'] = $query->result_array();
		$data['main_section'] = '_chann/news_view'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);	
	}
	function contact()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['main_section'] = '_chann/contact_view'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);	
	}
	
		
	
	//this one by jason cooksey
	function sales($prod_id = '', $grow_yard = '')
	{
		force_ssl();
		$avail = $this->product_model->get_count_products_avail(); 
		$data['avail'] = $avail;
		if($prod_id == ''){
			// get the top available product with the most pics.. 
			$prod_id = $avail[0]['product_type_id'];
		}
		if($grow_yard != 'gy'){
		$data['type'] = 'reg';
		$data['products']=$this->product_model->get_products_by("products",$prod_id);
		} else {
		$gy_id = $prod_id;
		$data['type'] = 'gy';
		$data['gy_name'] = $this->product_model->get_grow_yard_name($gy_id);
		$data['products']=$this->product_model->get_products_by("avail_grow",$gy_id);	
		}
		
		$data['main_section'] = '_chann/products_view_jason'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);
		//echo "<pre>";
		//print_r( $data['products'] );
		//echo "</pre>";	
	}
	
	function product_view($prod_id=1)
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['products']=$this->product_model->get_products_by("id",$prod_id);
		$data['main_section'] = '_chann/product_view_jason'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('_chann/product_view_jason', $data);	
	}
	
	function philosophy()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['main_section'] = '_chann/phil_view'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);
	}
	
	function vecchio_sales()
	{
		if (function_exists('force_ssl')) remove_ssl();
		$data['main_section'] = '_chann/sales_view'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);
	}
	
	function new_account()
	{
		force_ssl();
		$data['main_section'] = '_chann/sign_up_view'; 
		$data['title'] = "VECCHIO Trees";
		$this->load->view('site_template_2', $data);
	}
	
	function check_zip($zip){
		
		$this->db->where('zip', $zip);
		$this->db->from('zcta');
		$count = $this->db->count_all_results();
		return $count;
		
	}
	
	function search_by_serial_no(){
		$serial_no = $this->input->post('serial_no');
		if($serial_no == ''){
			redirect('dir/our_trees');
		}
		
		
	}
	
	function search_by_row_tree(){
		
		$row = $this->input->post('row_num');
		$tree = $this->input->post('tree_num');
		$grow_yard = $this->input->post('grow_yard_id');
		
		if($row == '' && $tree == ''){
			$this->db->select('id');
			$this->db->where('short_name', $grow_yard );
			$res = $this->db->get('grow_yards');
			$res_a = $res->result_array();
			redirect('dir/sales/'.$res_a[0]['id'].'/gy');
		}
		
    	$result = $this->product_model->search_by_row_tree($grow_yard, $tree, $row);

		
		
 	    $data['show_products'] = true;	
    	$data['newlist'] = $result;
    	$data['main_section'] = '_chann/search_results'; 
    	$data['title'] = "VECCHIO Trees";
    	$data['inp'] = 'Row and Tree';
    	$this->load->view('site_template_2', $data);		
		
	}
	
	function search_by_h_w(){
		
		$h = $this->input->post('height');
		$w = $this->input->post('width');
    	$result = $this->product_model->search_by_h_w($h, $w);	
		$data['show_products'] = true;	
		$data['newlist'] = $result;
		$data['main_section'] = '_chann/search_results'; 
		$data['title'] = "VECCHIO Trees";
		$data['inp'] = 'H x W';
		$this->load->view('site_template_2', $data);		
		
	}
	
	
	function search_result(){
		if (function_exists('force_ssl')) remove_ssl();
		$current = $this->input->post('current');
		$inp = trim($this->input->post('search_txt'));
		
		if($inp != ""){
			
			$organic = $this->product_model->search_products($inp);	
			if($organic != ''){
				$list = $organic;
			$data['show_products'] = true;	
			} else { 	
			$data['show_products'] = false;
		
 		$nolist = "<strong>No specific results found for \"" . $inp . "\"</strong><br /><br />";
		$nolist .= "<a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales\">Contact Vecchio Sales</a><br />";
		/*
		$nolist .=  "Olive Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/our_trees_olives/\">View Inventory</a> - ";
		$nolist .= "<a href=\"http://www.vecchiotrees.com/index.php/dir/specs_info/olives\">View Specs</a><br /> ";
		$nolist .=  "Citrus Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/specs_info/citrus\">View Specs</a><br /> ";
		$nolist .=  "Almond Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/specs_info/almonds\">View Specs</a><br /> ";
		$nolist .=  "Pomegranate Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/specs_info/pomegranates\">View Specs</a> <br />";
		$nolist .=  "Fig Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/specs_info/figs\">View Specs</a> <br />";
		$nolist .=  "Italian Cypress Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/specs_info/italian_cypress\">View Specs</a><br /> ";
		$nolist .=  "Vecchio Gold- <a href=\"http://www.vecchiotrees.com/index.php/dir/specs_info/vecchio_gold\">View Specs</a><br /> ";
		$nolist .= "<a href=\"http://www.vecchiotrees.com/index.php/dir/contact\">Contact Vecchio</a><br />";
		$nolist .=  "<a href=\"http://www.vecchiotrees.com/index.php/dir/sales\">Contact Vecchio Sales</a><br />";
		*/
		$list = "";
		
			switch(strtolower($inp)){
			case 'olives':
			case 'olive' :
			case "ol":
			case "o":
			case 'olive trees':
			case 'olive tree':
			$list =  "Olive Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/our_trees_olives/\">View Inventory</a>" ;
			break;
			case 'citrus':
			case 'oranges':
			case 'lemon':
			case 'cit':
			case 'c':
			case 'citrus tree':
			case 'citrus trees':
			$list =  "Citrus Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales/citrus\">Contact Sales</a> ";
			break;
			case 'almonds':
			case 'almond':
			case 'almund':
			case 'alm':
			case 'a':
			case 'almond trees':
			case 'almond tree':
			$list =  "Almond Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales/almond\">Contact Sales</a> ";
			break;
			case 'pomegranates':
			case 'pomegranetes':
			case 'pomegranites':
			case 'pomigranite':
			case 'pomegranate':
			case 'pomegranite':
			case 'pom':
			case 'p':
			case 'pomegranate trees':
			case 'pomegranate tree':
			$list = "Pomegranate Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales/pomegranate\">Contact Sales</a> ";
			break;
			case 'figs':
			case 'fig':
			case 'fig trees':
			case 'fig tree':
			$list = "Fig Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales/fig\">Contact Sales</a> ";
			break;
			case 'italian cypress':
			case 'italian':
			case 'cypress':
			case 'italian cypress trees':
			case 'italian cypress tree':
			case 'cypress trees':
			case 'cypress tree':
			$list = "Italian Cypress Trees - <a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales/italian_cypress\">Contact Sales</a> ";
			break;
			case 'vecchio gold':
			case 'gold':
			case 'gold trees':
			case 'vecchio':
		    case 'specimen':
		    case 'fine specimen trees':
		    case 'fine trees':
		    $list = "Vecchio Gold - <a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales/vecchio~gold\">Contact Sales</a> ";
			break;
			case 'sales':
			case 'contact':
			case 'marketing':
			case 'reps':
			case 'Paul McCauley':
			case 'Paul':
			case 'purchase':
			$list = "<a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales\">Contact Vecchio Sales</a><br />";
			break;
			default:			
			$list = $nolist;
			} // end switch
		} // end if organic results fail 
	
		$data['newlist'] = $list;
		$data['main_section'] = '_chann/search_results'; 
		$data['title'] = "VECCHIO Trees";
		$data['inp'] = $inp;
		$this->load->view('site_template_2', $data);
		} else {
			redirect($current);
		}
		
		
	}
	/* dummy page to keep session alive */
	function keep_alive(){
		$array = array('keep_alive' => 1);
		echo json_encode($array);
	}
	

	
	function ajax_contact(){

		$flname = $this->session->userdata('fname') . " " . $this->session->userdata('lname');
		$email = $this->session->userdata('usern_email');
		$txt = $this->input->post('contact_text');
		
		if($txt != ''){
		
		$message = '';
		$message .= 'Name: ' . $flname . "\r\n";
		$message .= 'Email: ' . $email. "\r\n";
		$message .= $txt;
		
			
		$to = $this->input->post('to_rep');
	//	$to = 'tylerpenney@gmail.com';
		$subject = 'User Request From VECCHIO SITE';
		$headers = 'From: ' . $email . "\r\n" .
		    'Reply-To: ' . $email . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
	
	 	echo 'Thank you ' . $flname . ". We will be contacting you shortly.";
		
		}
			
	}
	
	

}