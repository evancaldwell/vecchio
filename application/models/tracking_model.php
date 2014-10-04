<?php
class Tracking_model extends CI_Model{
	
	
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

   	function __construct(){
   		parent:: __construct();
        $sesh['user_id'] = $this->session->userdata('user_id');
   	}

	

    
	function get_orders($status, $rep_id = '', $customer_id = '', $exp_first = '', $just_reps = '', $order_id = '', $no_items = false){
		
		    // get all orders of a certain status, order by date ordered
		
			if($order_id != ''){
			$this->db->where('id', $order_id);	
			} else {
			
				if($just_reps != ''){
					$this->db->order_by('rep_id', 'asc');
					$this->db->where('rep_id !=', 0);		
				}
				if($exp_first != ''){
					$this->db->order_by('expire_date', 'desc');	
				} else {
					$this->db->order_by('order_date', 'desc');				
				}	
					$this->db->where('status', $status);
				if($rep_id != ''){
					$this->db->where('rep_id', $rep_id);
				}
				if($customer_id != ''){
					$this->db->where('customer_id', $customer_id);
				}
			}

			$query = $this->db->get('orders');
			$res_orders = $query->result_array();
			$count = count($res_orders);
			
			// loop through each order in system 
			for($i = 0; $i<$count; $i++ ){
			
			// Get all the customer information for order
			$this->db->select('id, fname, lname, company_name, usern_email, phone, bill_address, bill_city, bill_zip, bill_state');
			$this->db->where('id', $res_orders[$i]['customer_id']);
			$query_client = $this->db->get('users');
			$res_client = $query_client->result_array();
			$res_orders[$i]['client'] = $res_client;
			
			  
			
			// Get shipping location address
			$this->db->where('order_id', $res_orders[$i]['id']);
			$query_ship = $this->db->get('shipping');
			$res_ship = $query_ship->result_array();
			
			
			
			// some orders do not have shipping set, 
			if(!empty($res_ship)){
			// Get Freight Calculation
			$res_orders[$i]['shipping'] =  $res_ship;
			$this->load->model('shipping_model');
			$freight = $this->shipping_model->get_shipping_cost($res_orders[$i]['id'], 93292, $res_orders[$i]['shipping'][0]['ship_zip'], $heavy = false );
			$res_orders[$i]['freight'] = $freight;
			} else {
				$a = anchor('quotes/enter_shipping/'. $res_orders[$i]['id'], 'Calculate Freight');
				$res_orders[$i]['freight'] = $a;
				$res_orders{$i}['shipping'] = "Enter Shipping";
			}
			
			// Get Rep information for order
			 $this->db->select('fname, lname, usern_email, phone');  
			$this->db->where('id', $res_orders[$i]['rep_id']);
			$query_rep = $this->db->get('users');
			$res_rep = $query_rep->result_array();
			
			// no reps at this time
			$res_rep = array(
				0 => array(
					'fname' => 'No',
					'lname' => 'Rep',
					'usern_email' => '',
					'phone' => ''
				)
			);
			
			$res_orders[$i]['rep'] = $res_rep;
			
			if($no_items == false){
			// Get Order Items, loop through to get product info for each item
			$this->db->where('order_id', $res_orders[$i]['id']);
			$this->db->order_by('product_id');
			$query_items = $this->db->get('order_items');
			$res_items = $query_items->result_array();
			$count_inner = count($res_items);
			
				// for each order item, get product info and image urls
				for($u = 0; $u < $count_inner; $u++){
					
					
					$this->db->where('id', $res_items[$u]['product_id']);
					$query_products = $this->db->get('products');
					$res_products = $query_products->result_array();
					$res_items[$u]['product_info'] = $res_products; 
					
					// get the grow yard 
					$this->db->select('grow_yard_name');
					$this->db->where('id', $res_products[0]['grow_yard_id']);
					$query_grow_yard = $this->db->get('grow_yards');
					$res_grow_yard = $query_grow_yard->result_array();
					$res_items[$u]['product_info'][0]['grow_yard'] = $res_grow_yard;					
					
					
					// get the product type
					$this->db->select('product_code, description, trees_to_truck, list_price');
					$this->db->where('id', $res_products[0]['product_type_id']);
					$query_prod_type = $this->db->get('product_type');
					$res_prod_type = $query_prod_type->result_array();
					$res_items[$u]['product_info'][0]['product_type'] = $res_prod_type; 
	  
					// get product type here
					
					
					/* get all images associated with product
					
					// IMAGE GRAB DEPRECIATED - Not automatically loaded to save space, user clicks on serial # to see pop up images
					$this->db->where('product_id', $res_items[$u]['product_id']);
					$query_images = $this->db->get('product_images');
					$res_images = $query_images->result_array();
					$count_images = count($res_images);
					// get all info on images
					for($a = 0; $a < $count_images; $a++){
						
						$this->db->select('file_name, icon_file_name');
						$this->db->where('id', $res_images[$a]['id']);
						$query_image_info = $this->db->get('images');
						$res_image_info = $query_image_info->result_array();
					    $res_items[$u]['product_info'][0]['images'][$a] = $res_image_info; 
						
					} // end image looop
					*/
				
				} // end order items loop
				
			} else {
				$res_items = array();
			}
	
			
			$res_orders[$i]['order_items'] = $res_items;				
			
		} // end order by type loop
			
			return $res_orders;
				
	}
	

	
	function update_products($order_id, $status){
		/*
		$this->db->select('product_id');
	    $this->db->where('order_id', $order_id);
	    $query = $this->db->get('order_items');
		$result = $query->result_array();
		$count = count($result);
		for($i=0; $i<$count; $i++){
			$data = array(
					'order_status' => $status
			);
			$this->db->where('id', $result[$i]['product_id']);
			$this->db->update('products', $data);
             
		}
		*/
	
	}
	
	function get_aval_tagged(){
	 // 1 means that the order is in  "tagged  mode" (not paid for) and the user has X amount of time to pay or lose the items.
	 // expire_date > CURRENT_TIMESTAMP() will pick up the orders that have not expired.  
	 $q = "SELECT orders.id, orders.order_name, users.company_name, users.fname, users.lname, users.multiplier
	       FROM orders, users
	       WHERE orders.customer_id = users.id
	       AND status = 1 
	       AND expire_date > CURRENT_TIMESTAMP() ";
	 if($this->session->userdata('user_type') == 'rep'){
	 $rep_id = $this->session->userdata('user_id');
	 $q .= " AND users.rep_id = '$rep_id'";	
	 }
	
	
	 	$result = $this->db->query($q);
	 	return  $result->result_array();
	}
	
	function calendar_feed(){
			
			$q = "SELECT id, location, ship_date FROM shipping ORDER BY ship_date ASC";
		 	$result = $this->db->query($q);
		    $res_orders = $result->result_array();
			
			$templevel=0;   
			$newkey=0;

			  $grouparr[$templevel]="";

			  foreach ($res_orders as $key => $val) {
			   if ($templevel==$val['ship_date']){
			     $grouparr[$templevel][$newkey]=$val;
			   } else {
			     $grouparr[$val['ship_date']][$newkey]=$val;
			   }
			     $newkey++;       
			  }
			
			echo "<pre>";
			print_r($grouparr);
	
	}
	
	function get_order_name($order_id){
		$q = "SELECT orders.id, orders.order_name, users.company_name, users.lname
			  FROM orders, users 
			  WHERE orders.customer_id = users.id
			  AND orders.id = '$order_id'";
			
		$res = $this->db->query($q);
		$ra = $res->result_array();
		$re = $ra[0]['id'] . "-" . $ra[0]['order_name'] . " " . $ra[0]['company_name'] . "-" . $ra[0]['lname'];
		return $re;  
	}
	
	/* order functions disambiguted */
	
	function check_existing($customer_id){
		
		// find an existing, un-processed quote if there is one for a particular customer. 
		
		$q = "SELECT id, count(id) as cnt FROM orders WHERE customer_id = '$customer_id' AND status = '1' ";
		$res = $this->db->query($q);
		$result = $res->result_array();
		if($result[0]['cnt'] == 0){
		// no orders on file, start a new one
		return 0;	
		} else {
		// return use the order on file and use it to add products to
			return $result[0]['id'];
		}
	}
	
	
	/* order functions disambiguted */
	
	function start_new_quote($customer_id, $rep, $data_ship, $qq_id = 0){
		
			// Get the setting for the expire date and calculate how long they have to expire
			$vec = $this->User_model->vecchio_settings();
			date_default_timezone_set('America/Los_Angeles');
			$dt = date('Y-m-d H:i:s');
			$str = $dt ." + ". $vec['hours_expire'] . " hours";
			$exp = date('Y-m-d H:i:s', strtotime($str));


			// Generate random name for order 
			$this->load->model('product_model');  
			$rand_str = $this->product_model->genRandomString(10);  

			$data = array(
					'order_name' => $rand_str,
					'customer_id' => $customer_id,
					'rep_id' => $rep,
					'order_date' => $dt,
					'expire_date' => $exp,
					'status' => '1',
					'qq_id' => $qq_id
			);
			$this->db->insert('orders', $data);
			$id = $this->db->insert_id();
				
			if(!empty($data_ship)){
				
				$data_ship['order_id'] = $id;
				$this->db->insert('shipping', $data_ship);
				$ship_id = $this->db->insert_id();
				
			}
				return $id;
				
	}
	
	/* order functions disambiguted */
	
	function add_product_to_order($product_id, $order_id, $customer_id = 'admin'){

	$qq_id = $this->session->userdata('qq_id');	
		
		if($customer_id != 'admin'){
			$exists_order = $this->check_existing($customer_id);
			// if there is no unproccessed order on file, start one and set $order_id to the one created. 
			if($exists_order == 0){
				$data_ship = array(); // they haven't filled this out yet, must do so to complete order.  
			$order_id = $this->start_new_quote($customer_id, 0, $data_ship, $qq_id);
			} else {
			$order_id = $exists_order;
			}
		}
		
		$this->load->model('product_model'); 
		$price = $this->product_model->get_cust_price($order_id, $product_id);
		
		// Check if item is already in another order.. (hey its not a bad idea)
		$is_avail = $this->product_model->check_if_avail($product_id);
		
		// get product_serial_no 
		
		$prod_serial = $this->product_model->get_product_serial_no($product_id);
		$order_name = $this->get_order_name($order_id);
		$return = array();
		if($is_avail == 0){
			$data = array(
				'order_id' => $order_id,
				'product_id' => $product_id,
				'cust_cost' => $price,
				'quantity' => 1
			);
			$this->db->insert('order_items', $data);
			
			$return['message'] = "Product Added To Order: " . $order_name;
			$return['info'] = "Serial No: " . 	$prod_serial['serial_no'] .  "<br />";
			$return['yesno'] = "Success";

		} else {
			$return['message'] = "<span style='color:red;'>Error: Product Unavailable</span>";
			$return['info'] = "Serial No: " . 	$prod_serial['serial_no'] .  "<br />";
			$return['yesno'] = "Fail";
		}

		
		return $return;
	}
	
	/* order functions disambiguted */
	
	function remove_product_from_order(){
			$to_remove = array();
			$return = array();
			foreach ( $_POST as $key => $value )
			{
			    $checked = $this->input->post($key);
				if($checked == 1){
				$to_remove[] = $key; 	
				}
			}

			if(empty($to_remove)){
				$return['message'] = "Error: No products selected for removal";
				$return['info'] = "";
			} else {

				// get the first one to be removed, and find the order number
				$this->db->select('order_id');
				$this->db->where('id', $to_remove[0]);
			    $res = $this->db->get('order_items');
				$result = $res->result_array();
				
				$order_id = $result[0]['order_id'];
				
				$count = count($to_remove);

				for($i=0; $i<$count; $i++){
					$this->db->where('id', $to_remove[$i]);
					$this->db->delete('order_items');	
				}
				
				// count how many are left for this order. If there arent any, remove the order (set to seven)
				
				$this->db->where('order_id', $order_id);
				$this->db->from('order_items');
				$num = $this->db->count_all_results();
				
				if($num == 0){
					$this->load->model('user_model');
					$this->user_model->delete_entire_order('user deleted all cart items', $order_id);
				}
				
				
				
				$s = ($count > 1 ? 's' : '');
				
				$return['message'] = "Item" . $s . " removed from order ";
				$return['info'] = "";

			}
			
			return $return;
		
	}
	
	
	/* order functions disambiguted */
	
	function update_shipping_address($shipping_id, $data_ship, $order_id, $will_call = 0){
			
			if($shipping_id != 0){
			$this->db->where('id', $shipping_id);
			$this->db->update('shipping', $data_ship);
			$upins = "<b>Freight Information Updated</b> <br />";
			} else {
			$data_ship['order_id'] = $order_id; 
			$this->db->insert('shipping', $data_ship);
			$upins = "<b>Freight Information Added</b> <br />";
			}
            $order_name = $this->get_order_name($order_id);

			$data = array(
				'will_call' => $will_call
			);
			$this->db->where('id', $order_id);
			$this->db->update('orders', $data);
			
			if($will_call == 1){ // Customer Pickup
				$upins = "<b>Information Accepted</b> <br />";

				
				$data_ship['ship_address'] = '';
				$data_ship['ship_city'] = '';
				$data_ship['ship_state'] = '';
				$data_ship['ship_zip'] = '';
			}
			
			$coma = ($data_ship['ship_city'] != '' ? ', ' : '');
			
			$return['message'] = "Information updated for order : " . $order_name;
			$return['info'] = $upins . $data_ship['location'] . "<br /> Phone: " .$data_ship['location_phone'].  "<br />"  . $data_ship['ship_address'] . "<br />" . $data_ship['ship_city'] . $coma . $data_ship['ship_state'] . " " . $data_ship['ship_zip'];
			$this->load->model('email_model');
			$this->email_model->send_rep_notice($order_id, $return['info'], 'order');
			return $return; 
	}
	
	
	/* order functions disambiguted */
	
	function update_qq_shipping_address($qq_shipping_id, $data_ship, $qq_id, $will_call = 0){
			
			if($qq_shipping_id != ''){
			$this->db->where('id', $qq_shipping_id);
			$this->db->update('qq_shipping', $data_ship);
			$upins = "<b>Shipping Information Updated</b> <br />";
			} else {
			$data_ship['qq_id'] = $qq_id; 
			$this->db->insert('qq_shipping', $data_ship);
			$upins = "<b>Shipping Information Added</b> <br />";
			}
			$data = array(
				'will_call' => $will_call
			);
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $data);
			
			if($will_call == 1){ // Customer Pickup
				$upins = "<b>Information Accepted</b> <br />";

				$this->db->select('product_type_id, quantity');
				$this->db->where('qq_id',$qq_id);
				$prod = $this->db->get('quick_quote_items');
				$list_prod = $prod->result_array();
				$quote_items = array();
				$count = count($list_prod);
				for($i=0;$i<$count;$i++){
					$quote_items[$list_prod[$i]['product_type_id']] = $list_prod[$i]['quantity'];
				}
				
				// update quote info 
				$this->load->model('shipping_model');

				$freight = $this->shipping_model->get_quick_ship('willcall', 'willcall', $quote_items, $will_call);
		
				$update_array = array(
					'distance' => $freight['distance'],
					'actual_trucks' => $freight['actual_trucks'],
					'trucks' => $freight['trucks'],
					'ship_cost' => $freight['ship_cost'],
				);
			
				$this->db->where('id', $qq_id);
				$this->db->update('quick_quote',$update_array);

				$data_ship['ship_address'] = '';
				$data_ship['ship_city'] = '';
				$data_ship['ship_state'] = '';
				$data_ship['ship_zip'] = '';
				
			} else { // Ship
			
		
				$this->db->select('ship_zip');
	    		$this->db->where('id', $qq_id);
				$res = $this->db->get('quick_quote');
				$result = $res->result_array();
				$zip_on_file = $result[0]['ship_zip'];
			
				// check zip is different than zip in original quote. If so, update quote cost
				if($zip_on_file != $data_ship['ship_zip'] || $data_ship['ship_zip'] == '' ){
				
					$this->db->select('product_type_id, quantity');
					$this->db->where('qq_id',$qq_id);
					$prod = $this->db->get('quick_quote_items');
					$list_prod = $prod->result_array();
					$quote_items = array();
					$count = count($list_prod);
					for($i=0;$i<$count;$i++){
						$quote_items[$list_prod[$i]['product_type_id']] = $list_prod[$i]['quantity'];
					}
			
					$this->load->model('shipping_model');
					$freight = $this->shipping_model->get_quick_ship($data_ship['ship_zip'], 93292, $quote_items, 0);
			
					$update_array = array(
						'distance' => $freight['distance'],
						'actual_trucks' => $freight['actual_trucks'],
						'trucks' => $freight['trucks'],
						'ship_cost' => $freight['ship_cost'],
						'ship_zip' => $data_ship['ship_zip'], 
						'will_call' => $will_call
					);
				
					$this->db->where('id', $qq_id);
					$this->db->update('quick_quote',$update_array);
			
				}
			
			}
			
			$coma = ($data_ship['ship_city'] != '' ? ', ' : '');
			
			$return['message'] = "Information updated for quote ";
			$return['info'] = $upins ."Shipping Address: <br /> " . $data_ship['location'] . "<br /> Phone: " .$data_ship['location_phone'].  "<br />"  . $data_ship['ship_address'] . "<br />" . $data_ship['ship_city'] . $coma . $data_ship['ship_state'] . " " . $data_ship['ship_zip'];
			$this->load->model('email_model');
			$this->email_model->send_rep_notice($qq_id, $return['info'], 'quote');
			return $return; 
	}
	
	
	
	/*  ORDER RECEIPT FUNCTIONS */
	
	
	
	function get_transaction_info($order_id){
		
		
		$return = array();
		
		$return['pay_items'][0]['amount'] = '';
		$return['pay_items'][0]['freight'] = '';
		$return['pay_items'][0]['transaction_id'] = '';
		$return['pay_items'][0]['payment_date'] = '';
		$return['pay_items'][0]['method'] = '';
		
		// payments table
		$this->db->select('status, on_account_date');
		$this->db->where('id', $order_id);
		$o = $this->db->get('orders');
		$o_array = $o->result_array();
		
		$status = $o_array[0]['status'];
		
		$this->db->where('order_id', $order_id);
		$get = $this->db->get('payments');

		$res = $get->result_array();
		if(!empty($res)){
			$count_num = count($res);
			$pay_total = 0;
			$freight_total = 0;
			for($i=0;$i<$count_num;$i++){
				$pay_total += $res[$i]['amount'];
				$freight_total += $res[$i]['freight'];
				$return['pay_items'][$i]['amount'] = $res[$i]['amount'];
				$return['pay_items'][$i]['freight'] = $res[$i]['freight'];
				$return['pay_items'][$i]['transaction_id'] = $res[$i]['transaction_id'];
				$return['pay_items'][$i]['payment_date'] = $res[$i]['payment_date'];
				$return['pay_items'][$i]['method'] = $res[$i]['method'];
			}
			$return['pay_total'] = $pay_total;
			$return['freight_total'] = $freight_total;
		
		} else {
	
		$this->db->select('grand_total, total_cost_cust');
		$this->db->where('order_id', $order_id);
		$res = $this->db->get('shipping');
		$res_arr = $res->result_array();
						
		
		$return['pay_items'][0]['order_id'] = $order_id;
		$return['pay_items'][0]['amount'] = $res_arr[0]['grand_total'];
		$return['pay_items'][0]['freight'] = $res_arr[0]['total_cost_cust'];
		$return['pay_items'][0]['transaction_id'] = 'VECCHIOCREDIT';
		$return['pay_items'][0]['payment_date'] = $o_array[0]['on_account_date'];
		$return['pay_items'][0]['method'] = 'ON ACCOUNT';
		
		}
		
			
	
	   	return $return;
	}
	// used by the credit cart processing controller - do not edit! 
	
	function get_client_info($order_id){
		// get the user information
		$q = "SELECT users.id, fname, lname, company_name, usern_email, phone, fax, bill_address, bill_city, bill_zip, bill_state, orders.e_sig, orders.expire_date
			  FROM users, orders
			  WHERE users.id = orders.customer_id
			  AND orders.id = '$order_id'";
			
		$query_client = $this->db->query($q);
		$res =  $query_client->result_array();	
		return $res[0];
	}
	
	function get_shipping_info($order_id){
		
		$this->db->where('order_id', $order_id);
		$query_ship = $this->db->get('shipping');
		$res = $query_ship->result_array();	
		return $res[0];
	}
	
	
	function get_order_items($order_id){
		$q = "SELECT products.id,
					 products.serial_no,
					 product_type.id as product_type_id,
					 product_type.description,
					 product_type.specs,
					 product_type.trees_to_truck,
					 order_items.cust_cost,
					 order_items.id as order_item_id,
					 order_items.product_id
			    FROM order_items, products, product_type
			    WHERE products.id = order_items.product_id
				AND  product_type.id = products.product_type_id
				AND order_items.order_id = '$order_id'";
		$result = $this->db->query($q);
		return $result->result_array();	
	}
	
	function get_qq_items($qq_id){
		
		$q = "SELECT product_type.id as product_type_id,
					 product_type.product_code,
					 product_type.specs,
					 product_type.description,
					 product_type.grow_yard,
					 product_type.box_size,
					 quick_quote_items.quantity,
					 quick_quote_items.locked_price as list_price,
					 quick_quote.locked_multiplier as multiplier
			    FROM quick_quote_items, product_type, quick_quote
				WHERE product_type.id = quick_quote_items.product_type_id
				AND quick_quote.id = quick_quote_items.qq_id
				AND quick_quote_items.qq_id = '$qq_id'";
		$res = $this->db->query($q);
		$result = $res->result_array();
		
		$count = count($result);
		for($i=0;$i<$count;$i++){
			$result[$i]['product_id'] = "Product " . $result[$i]['grow_yard'] . "-". $result[$i]['product_code'] . '-' . $result[$i]['box_size'];
			$result[$i]['item_cost'] = (($result[$i]['list_price'] * $result[$i]['multiplier']) * $result[$i]['quantity']);
			$result[$i]['ind_cost'] = ($result[$i]['list_price'] * $result[$i]['multiplier']);
		}
		
		return $result;
		
			
	}
	
	
	function get_orders_by_rep($rep_id, $status){
		$this->load->model('shipping_model');
				$q = "SELECT
							orders.id,
							orders.order_name,
							orders.status, 
							orders.customer_id,
							orders.order_date,
							orders.expire_date,
							orders.payment_date,
							orders.shipped_date, 
							users.fname, 
							users.lname,
							users.company_name
		            FROM orders, users
					WHERE orders.customer_id = users.id
					AND users.rep_id = '$rep_id'
					AND orders.status = '$status'";
			$result = $this->db->query($q);
			$orders = $result->result_array();
		
		$count = count($orders);
		for($i=0; $i<$count; $i++){
		$order_info = $this->get_order_images_info($orders[$i]['id'], 'pre');
		$shipping_zip = $order_info['shipping_info']['ship_zip'];
		if($shipping_zip != ""){
		$freight = $this->shipping_model->get_shipping_cost($orders[$i]['id'], 93292, $shipping_zip, $heavy = false );
		$orders[$i]['freight'] = $freight;
		} else {
		$orders[$i]['freight'] = array();
		}
		$orders[$i]['info'] = $order_info;

		}
		
		return $orders;
		
	}
	
	function get_order_images_info($order_id, $prepost = ''){
	
	// get all this awesome stuff ^^^^	
	$orders = $this->get_order_items($order_id);
	$count = count($orders);
	
	$order_details = array();
	$order_details['order_items'] = $orders;
	// get all the images for the order
		for($i = 0; $i < $count; $i++){
			$this->db->select('id, file_name, icon_file_name');
			$this->db->where('product_id', $orders[$i]['product_id']);
			$q2 = $this->db->get('images');
		  	$img = $q2->result_array();
		    if(!empty($img)){
		    $order_details['order_items'][$i]['files'] = $img;
	        } else {
		    $orders_details['order_items'][$i]['files'] = array(0 => array('file_name' => 'default.png', 'icon_file_name' => 'default_thumb.png'));
	    	}

		}
		// get the transaction information
		
	// get discount info 
	$this->db->select('code');
	$this->db->where('id', $order_id);
	$res = $this->db->get('orders');
	$result = $res->result_array();
	if($result[0]['code'] != ''){
		$this->db->select('disc_perc');
		$this->db->where('code', $result[0]['code']);
		$pro = $this->db->get('promo');
		$promo = $pro->result_array();
		$discount_percent = $promo[0]['disc_perc'];
	} else {
		$discount_percent = 0;
	}
	
	// boxed and will-call info
	$this->db->select('boxed, will_call, po_number');
	$this->db->where('id', $order_id);
	$bw = $this->db->get('orders');
	
	$br = $bw->result_array();
	$order_details['boxed'] = $br[0]['boxed'];
	$order_details['will_call'] = $br[0]['will_call'];
	$order_details['po_number'] = $br[0]['po_number'];
	
    $order_details['order_name'] = $this->get_order_name($order_id);
	$order_details['transaction_info'] = ($prepost == '' ? $this->get_transaction_info($order_id) : array() );
	$order_details['shipping_info'] = $this->get_shipping_info($order_id);
	$order_details['client_info'] = $this->get_client_info($order_id);
	$order_details['discount_percent'] = $discount_percent;
	
	return $order_details;
	
	}
	
	function get_order_extra($id, $type){

		$extra['boxed'] = '';
		$extra['will_call'] = '';
		$extra['po_number'] = '';
		$extra['box_price'] = '';
		
		if($type == 'order'){
			
			$this->db->select('boxed, will_call, po_number');
			$this->db->where('id', $id);
			$bw = $this->db->get('orders');
			$br = $bw->result_array();
			$extra['boxed'] = $br[0]['boxed'];
			$extra['will_call'] = $br[0]['will_call'];
			$extra['po_number'] = $br[0]['po_number'];
			
			
		} else { // quick quote
			
			$this->db->select('boxed, box_price, will_call, po_number');
			$this->db->where('id', $id);
			$bw = $this->db->get('quick_quote');
			$br = $bw->result_array();
			$extra['boxed'] = $br[0]['boxed'];
			$extra['will_call'] = $br[0]['will_call'];
			$extra['po_number'] = $br[0]['po_number'];			
			
		}
		
		return $extra;
		
	}

	
	function get_all_quick_quote(){
		
		$this->db->select('fname, lname, id');
		$this->db->where('user_type', 'rep');
		$results = $this->db->get('users');
		$reps = $results->result_array();
		$count = count($reps);
		for($i=0;$i<$count;$i++){
		$reps[$i]['quote'] = $this->get_quick_quote_items("", $reps[$i]['id']);
		}
		return $reps;
		
	}
	
	function get_qq_order_receipt($qq_id){
		
		$return = array();
		$return['pay_items'][0]['amount'] = '';
		$return['pay_items'][0]['freight'] = '';
		$return['pay_items'][0]['transaction_id'] = '';
		$return['pay_items'][0]['payment_date'] = '';
		$return['pay_items'][0]['method'] = '';
		
		$return['location'] = '';
		$return['location_phone'] = '';
		$return['ship_address'] = '';
		$return['ship_city'] = '';
		$return['ship_state'] = '';
		$return['ship_zip'] = '';	
		$return['ship_date'] = '';
		
		// get order info
		$this->db->where('qq_id', $qq_id);
		$get = $this->db->get('qq_payments');
		
		$res = $get->result_array();
		if(!empty($res)){
			$count_num = count($res);
			$pay_total = 0;
			$freight_total = 0;
			for($i=0;$i<$count_num;$i++){
				$pay_total += $res[$i]['amount'];
				$freight_total += $res[$i]['freight'];
				$return['pay_items'][$i]['amount'] = $res[$i]['amount'];
				$return['pay_items'][$i]['freight'] = $res[$i]['freight'];
				$return['pay_items'][$i]['transaction_id'] = $res[$i]['transaction_id'];
				$return['pay_items'][$i]['payment_date'] = $res[$i]['payment_date'];
				$return['pay_items'][$i]['method'] = $res[$i]['method'];
			}
			$return['pay_total'] = $pay_total;
			$return['freight_total'] = $freight_total;
		} else {
			
			// on account - no payments are set up 
				$q = "SELECT ( quick_quote.ship_cost + ROUND((SUM( 
					quick_quote.locked_multiplier * 
					quick_quote_items.quantity * 
					quick_quote_items.locked_price )
					 + quick_quote.box_price ) , 2) - quick_quote.disc ) AS qq_total,
					quick_quote.ship_cost, 
					quick_quote.on_account_date
					FROM quick_quote, quick_quote_items
					WHERE quick_quote.id = quick_quote_items.qq_id
					AND (quick_quote.status = 1 OR quick_quote.status = 4)
					AND quick_quote.id =  '$qq_id' ";
					
				$r = $this->db->query($q);
				$ans = $r->result_array();
				
				if(!empty($ans) && $ans[0]['qq_total'] > 0){
				$return['pay_items'][0]['amount'] = $ans[0]['qq_total'];
				$return['pay_items'][0]['freight'] = $ans[0]['ship_cost'];	
				$return['pay_items'][0]['transaction_id'] = 'ONACCOUNT-' . $qq_id . '-VEC' ;
				$return['pay_items'][0]['payment_date'] = $ans[0]['on_account_date'];	
				$return['pay_items'][0]['method'] = 'ON-ACCOUNT';								
				}

			
		}
		// get shipping info
		$this->db->select('location, location_phone, ship_address, ship_city, ship_state, ship_zip, ship_date');
		$this->db->where('qq_id', $qq_id);
		$gets = $this->db->get('qq_shipping');
		$ress = $gets->result_array();
		if(!empty($ress)){
		$return['location'] = $ress[0]['location'];
		$return['location_phone'] = $ress[0]['location_phone'];
		$return['ship_address'] = $ress[0]['ship_address'];
		$return['ship_city'] = $ress[0]['ship_city'];
		$return['ship_state'] = $ress[0]['ship_state'];
		$return['ship_zip'] = $ress[0]['ship_zip'];	
		$return['ship_date'] = date("l F j, Y", strtotime($ress[0]['ship_date']));
		}
		
		return $return;	
		
		
		
	}
	
	function get_quick_quote_items($quote_id = "", $rep_id = "", $customer_id = "", $status = ''){
		
		
		
		$super_status = $this->session->userdata('user_type');
		
		if($rep_id == ""){
			$rep_id = $this->session->userdata('user_id');
		}
		
		
		if($quote_id != ""){
			// individual 
		$this->db->where('id', $quote_id);	
		} else if($customer_id != '') {
			// get all the quotes by customer
			// leave out the disabled ones..
			$this->db->where('customer_id', $customer_id);
			$this->db->where('admin_void', 0);

		} else if($super_status != 'admin') { // admins don't filter by rep. 
				// get all quotes by rep
			$this->load->model('user_model');
			$in_house = $this->user_model->is_in_house();
			if(!$in_house){	
			// in house rep sees everything
			$this->db->where("rep_id", $rep_id);
			}
		} else if($status != '' && is_array($status)){
			
			$where = '';	
				
			$count_or = count($status);
			for($s=0;$s<$count_or;$s++){
				if($s == 0){
				 // first round
				$where .= " status = '" . $status[0] ."' ";	
				} else {
				$where .= " OR status = '" . $status[$s]."' ";	
				}
			}
			
				$this->db->where($where, NULL, FALSE);		
		}
		$this->db->order_by("status", 'asc');
		$this->db->order_by("quote_date", 'desc');

		
		$result = $this->db->get('quick_quote');
		$row = $result->result_array();

		$count = count($row);
		
		for($i=0;$i<$count; $i++){
			
			// get customer multiplier, other info..
			
			$this->db->where('id', $row[$i]['customer_id']);
			$m_r = $this->db->get('users');
			$mult = $m_r->result_array();
			$row[$i]['multiplier'] = $row[$i]['locked_multiplier'];
			$row[$i]['fname'] = $mult[0]['fname'];
			$row[$i]['lname'] = $mult[0]['lname'];
			$row[$i]['usern_email'] = $mult[0]['usern_email'];
			$row[$i]['cust_name'] = $mult[0]['fname'] . " " . $mult[0]['lname'];
			$row[$i]['company_name'] = $mult[0]['company_name'];
			$row[$i]['bill_address'] = $mult[0]['bill_address'];
			$row[$i]['bill_city'] = $mult[0]['bill_city'];
			$row[$i]['bill_state'] = $mult[0]['bill_state'];
			$row[$i]['bill_zip'] = $mult[0]['bill_zip'];
			$row[$i]['phone'] = $mult[0]['phone'];
			$row[$i]['fax'] = $mult[0]['fax'];
			$row[$i]['net_terms'] = $mult[0]['net_terms'];
			$row[$i]['credit_limit'] = $mult[0]['credit_limit'];
			$row[$i]['no_custom'] = false;
			
			// if account - see if they have enough credit to put on credit --
			$cc_id = $row[$i]['customer_id'];
			
			$row[$i]['total_in_credit'] = '';
			$row[$i]['quote_total'] = '';
			$row[$i]['can_credit'] = false;
			$row[$i]['pay_date'] = '';
			
			
			if($row[$i]['net_terms'] != 0 && $row[$i]['status'] == 0){ // if the user is given net terms/credit and if this a new order seeking credit
				

		 	$q_id = $row[$i]['id'];	
		
			$credit = $this->can_credit($cc_id, $mult[0]['credit_limit'], $q_id);
			
			$row[$i]['total_in_credit'] = $credit['total_in_credit'];
			$row[$i]['quote_total'] = $credit['quote_total'];
			$row[$i]['can_credit'] = $credit['can_credit'];
			
				
			} else if($row[$i]['status'] == 1 || $row[$i]['status'] == 4){
					// get pay date for qq on account -
					// get shipping info 
				$this->db->select('ship_date');
				$this->db->where('qq_id', $row[$i]['id']);
				$gets = $this->db->get('qq_shipping');
				
				$ress = $gets->result_array();
				if(!empty($ress)){
				$ship_date = $ress[0]['ship_date'];
				$days = $row[$i]['net_terms'];
				$row[$i]['pay_date'] = date("l F j, Y", strtotime($ship_date . " + " . $days . " Days"));
				} else {
				$row[$i]['pay_date'] = 'Ship Date Not Set';
				}
			}
			
			// get rep info 
			
			$this->db->select('fname, lname, phone, usern_email');
			$this->db->where('id', $row[$i]['rep_id']);
			$res = $this->db->get('users');
			$repstuff = $res->result_array();
			
			$row[$i]['rep']['name'] = $repstuff[0]['fname'] . " " . $repstuff[0]['lname'];
			$row[$i]['rep']['phone'] = $repstuff[0]['phone'];
			$row[$i]['rep']['email'] = $repstuff[0]['usern_email'];
			
			if($row[$i]['rep_id_2'] != 0){ // get in house or secondary rep
		
			$this->db->select('fname, lname, phone, usern_email');
			$this->db->where('id', $row[$i]['rep_id_2']);
			$res = $this->db->get('users');
			$repstuff = $res->result_array();
			
			$row[$i]['rep2']['name'] = $repstuff[0]['fname'] . " " . $repstuff[0]['lname'];
			$row[$i]['rep2']['phone'] = $repstuff[0]['phone'];
			$row[$i]['rep2']['email'] = $repstuff[0]['usern_email'];
			
			}
			
			$id = $row[$i]['id'];
			$q = "SELECT product_type.product_code, product_type.box_size, product_type.grow_yard, product_type.description, quick_quote.locked_multiplier, quick_quote_items.product_type_id, exposure, watering, product_type.text_description, quantity, list_price, (quick_quote_items.locked_price * quick_quote_items.quantity) as line_price, ROUND(((quick_quote_items.locked_price * quick_quote_items.quantity) * quick_quote.locked_multiplier),2) as cust_line, quick_quote_items.id as item_id
			 	  FROM product_type, quick_quote_items, quick_quote
				  WHERE product_type.id = quick_quote_items.product_type_id
				  AND quick_quote_items.qq_id = quick_quote.id
				  AND quick_quote_items.qq_id = '$id'";
			$res = $this->db->query($q);
			$res_i = $res->result_array();
			$sum = 0;
			
			// check if they can box the items in the quote 
			
			$row[$i]['can_box'] = $this->can_box($row[$i]['id'], 'quick_quote');
			
			// check to see if products in quote are available in the online inventory --> 
			
			$this->load->model('product_model');
			$count_res = count($res_i);
			for($r=0;$r<$count_res;$r++){
				$sum += $res_i[$r]['cust_line'];
				$res_i[$r]['avail_count'] = $this->product_model->check_inventory($res_i[$r]['product_type_id']);
				// if the online inventory is less than the quantity quoted - disable custom select
				if($res_i[$r]['quantity'] > $res_i[$r]['avail_count']){
					$row[$i]['no_custom'] = true;
				}
			}
			
			// check for complete shipping data 
			$this->db->select('id, location, location_phone, ship_address, ship_city, ship_state, ship_zip, ship_date');
			$this->db->where('qq_id',  $row[$i]['id'] );
			$result = $this->db->get('qq_shipping');
			$ship_info = $result->result_array();
			$row[$i]['shipping'] = $ship_info;		
			
			$row[$i]['items'] = $res_i;
			$row[$i]['sum'] = $sum;
			$row[$i]['sub_total'] = $sum;
			$row[$i]['sub_total_items'] = $sum;
			
			if($row[$i]['disc'] > 0){
		 	$row[$i]['sub_total'] = (($row[$i]['sub_total'] + $row[$i]['box_price'] ) - $row[$i]['disc']); 
			} else {
			$row[$i]['sub_total'] = ($row[$i]['sub_total'] + $row[$i]['box_price'] ); 	
			}
			$row[$i]['grand_total'] = $row[$i]['sub_total'] + $row[$i]['ship_cost'];
			
			$s = $row[$i]['status'];
			switch($s){
				case 0:
				$st = 'Pending';
				break;
				case 1:
				$st = 'On Account -  Shipping Backlog';
				break;
				case 2:
				$st = "Paid - Shipping Backlog";
				break;
				case 3:
				$st = "Paid - Shipped";
				break;
				case 4:
				$st = "On Account / Shipped ";
				break;
				case 6:
				$st = "Check By Fax - Needs Approval";
				break;
				default:
				$st = 'Pending';		 
			}
			$row[$i]['status_text'] = $st;
			
		}
		
		return $row;
		
	}
	
	
	
	
	
	function can_box($id, $type = 'order'){
		
		$return = '0.00';
		
		if($type == 'order'){	
			
			$box_q = "SELECT SUM(box.price) as box_price
					  FROM products, product_type, box, orders, order_items
					  WHERE products.product_type_id = product_type.id
					  AND box.size = product_type.box_size
					  AND order_items.product_id = products.id
					  AND orders.id = order_items.order_id
					  AND orders.id = '$id' ";
					
			$box_r = $this->db->query($box_q);
			$box_a = $box_r->result_array();
			if(!empty($box_a) && $box_a[0]['box_price'] > 0){
			
			$return = $box_a[0]['box_price'];
				
			}
			
			
		} else {
			
			// quick quote can box
		 	
				$box_q = "SELECT quick_quote_items.qq_id, SUM( box.price * quick_quote_items.quantity ) AS box_price
						  FROM product_type, box, quick_quote_items
						  WHERE quick_quote_items.product_type_id = product_type.id
						  AND box.size = product_type.box_size
						  AND quick_quote_items.qq_id = '$id' ";
							
				$box_r = $this->db->query($box_q);
				$box_a = $box_r->result_array();
				if(!empty($box_a) && $box_a[0]['box_price'] > 0){

				$return = $box_a[0]['box_price'];

				}
		
		}
			
		return $return;		
		
	}
	
	
	function can_credit($cc_id, $credit_limit = '', $q_id = 0, $order_id = 0){
		
			$return['total_in_credit'] = '';
			$return['quote_total'] = '';
			$return['can_credit'] = false;
			$return['credit_limit'] = $credit_limit;
			
			if($credit_limit == ''){ // didn't pass the credit_limit. Get it yourself! Gosh! 
				$this->db->select('credit_limit');
				$this->db->where('id', $cc_id);
				$res = $this->db->get('users');
				$res_array = $res->result_array();
				$credit_limit = $res_array[0]['credit_limit'];
			}
			
			if($credit_limit > 0){
		
			$total_in_credit = 0; // how much they currently have in credit. 
			
			// get all quick quotes on account
			// check on account Shipping backlog (1) and on account / shipped (4)
			$credit_q = "SELECT (((SUM( quick_quote.locked_multiplier * quick_quote_items.quantity * quick_quote_items.locked_price )
			+ quick_quote.box_price ) 
			- quick_quote.disc ) + quick_quote.ship_cost ) - quick_quote.deposit AS qq_total
			FROM quick_quote, quick_quote_items
			WHERE quick_quote.id = quick_quote_items.qq_id
			AND quick_quote.customer_id = '$cc_id'
			AND (quick_quote.status = 1 OR quick_quote.status = 4)
			GROUP BY quick_quote_items.qq_id ";
			
			$credit_r = $this->db->query($credit_q);
			$cr = $credit_r->result_array();
			
			foreach($cr as $c){
					$total_in_credit += $c['qq_total']; 
			}
			
			// check how much they have in orders on credit. 
			$credit_o = "SELECT orders.id, ((shipping.total_cost_cust + SUM(order_items.cust_cost * order_items.quantity)) - orders.deposit ) as o_total
			FROM orders, order_items, shipping
			WHERE orders.id = order_items.order_id
			AND shipping.order_id = orders.id
			AND orders.customer_id = '$cc_id'
			AND (orders.status = 4 OR orders.status = 5) 
			GROUP BY order_items.order_id ";
			
			$credit_or = $this->db->query($credit_o);
			$co = $credit_or->result_array();
			
			foreach($co as $c){
					$total_in_credit += $c['o_total']; 
			}
	

			$return['total_in_credit'] = $total_in_credit;
			
			
			if($q_id != 0){ // QUOTE
			// get this quote total
			
				$this_quote = "SELECT ( 
				quick_quote.ship_cost + SUM( quick_quote.locked_multiplier * quick_quote_items.quantity * quick_quote_items.locked_price )
				+ quick_quote.box_price ) - quick_quote.disc AS qq_total
				FROM quick_quote, quick_quote_items
				WHERE quick_quote.id = quick_quote_items.qq_id
				AND quick_quote.id = '$q_id'
				GROUP BY quick_quote_items.qq_id";
			
				$q_total = $this->db->query($this_quote);
				$q_t = $q_total->result_array();
	
				// total in credit plus the quote in question
				$credit_plus_quote = $total_in_credit + $q_t[0]['qq_total'];
			
				// total in quote
				$return['quote_total'] = $q_t[0]['qq_total']; 
			
				if($credit_plus_quote <= $credit_limit){
					// they have enough credit to process 
					$return['can_credit'] = true;
				} else {
					$return['can_credit'] = false;
				}
			
			} else if($order_id != 0){ // INV ORDER
				
				// check if shipping has been set
				$this->db->where('order_id', $order_id);
				$c = $this->db->count_all_results('shipping'); 
				if($c > 0){

				// check total of individual order
				$this_order = "SELECT orders.id, (shipping.total_cost_cust + SUM(order_items.cust_cost * order_items.quantity)) as o_total
				FROM orders, order_items, shipping
				WHERE orders.id = order_items.order_id
				AND shipping.order_id = orders.id
				AND orders.id = '$order_id'
				GROUP BY order_items.order_id ";
				
				$order_q = $this->db->query($this_order);
				$o_r = $order_q->result_array();
				
				// total in credit plus the quote in question
				$credit_plus_quote = $total_in_credit + $o_r[0]['o_total'];
				
				// total in quote
				$return['quote_total'] = $o_r[0]['o_total']; 

				if($credit_plus_quote <= $credit_limit){
					// they have enough credit to process 
					$return['can_credit'] = true;
				} else {
					$return['can_credit'] = false;
				}
				
				
				} // if they have entered freight info
				
			}
			
		} // if credit limit is more than 0
			
		return $return;	
			
			
	}
	
	function qq_quick_list(){
		$c_id = $this->session->userdata('user_id');
		$q_top = "SELECT id, status, quote_date, memo, po_number FROM quick_quote WHERE customer_id = '$c_id' AND admin_void = 0";
		$r = $this->db->query($q_top);
		$top_q = $r->result_array();
		$cnt = count($top_q);
		for($i=0;$i<$cnt; $i++){
			$qq_id = $top_q[$i]['id'];
			$q = "SELECT quick_quote.id, product_type.product_code, product_type.box_size, product_type.grow_yard, product_type.description, quick_quote_items.quantity, quick_quote.boxed, quick_quote.box_price, quick_quote.will_call
			 	  FROM product_type, quick_quote_items, quick_quote
				  WHERE product_type.id = quick_quote_items.product_type_id
				  AND quick_quote_items.qq_id = quick_quote.id
				  AND quick_quote.id = '$qq_id'
				  AND quick_quote.admin_void = 0";

			$query = $this->db->query($q);
			$row = $query->result_array();
			$top_q[$i]['items'] = $row;
		}
		
		
		// place them in different arrays
		$count = count($top_q);
		$new = array();
		$on_account = array();
		$paid = array();
		$backlog_noship = array();
		for($i=0;$i<$count;$i++){
			switch($top_q[$i]['status']){
				case 0 : // new	
				$new[] = $top_q[$i];
				break;
				case 1 : // on account shipping backlog
				case 2 : // paid shipping backlog
				
					$this->db->select('ship_date');
					$this->db->where('qq_id', $top_q[$i]['id']);
					$result = $this->db->get('qq_shipping');
					$ship = $result->result_array();
					if($ship[0]['ship_date'] == '0000-00-00'){
						$top_q[$i]['needs_ship'] = true;
					} else {
						$top_q[$i]['needs_ship'] = false;
						$top_q[$i]['ship_date'] = date("l F j, Y", strtotime($ship[0]['ship_date']));
					}
					$backlog_noship[] = $top_q[$i];				
					
				break;	
				case 4 : 
					$this->db->select('ship_date');
					$this->db->where('qq_id', $top_q[$i]['id']);
					$result = $this->db->get('qq_shipping');
					$ship = $result->result_array();
					$this->db->select('net_terms');
					$this->db->where('id', $c_id);
					$res_c = $this->db->get('users');
					$days = $res_c->result_array();
					if(!empty($ship)){
					$top_q[$i]['ship_date'] = date("l F j, Y", strtotime($ship[0]['ship_date']));
					$top_q[$i]['pay_date'] = date("F j, Y", strtotime($ship[0]['ship_date'] . " + " . $days[0]['net_terms'] . " Days"));
					if( strtotime($top_q[$i]['pay_date']) < time()){
					$top_q[$i]['overdue'] = true;
					} else {
					$top_q[$i]['overdue'] = false;
					} 
						
					} else {
						
						$top_q[$i]['ship_date'] = '';
						$top_q[$i]['pay_date'] = '';
						$top_q[$i]['overdue'] = '';
					}
				$on_account[] = $top_q[$i];
				break;
				case 2 :
				case 5 :
					$this->db->select('ship_date');
					$this->db->where('qq_id', $top_q[$i]['id']);
					$result = $this->db->get('qq_shipping');
					$ship = $result->result_array();
					$top_q[$i]['ship_date'] = date("l F j, Y", strtotime($ship[0]['ship_date']));
				$paid[] = $top_q[$i];
				break;
			}
		}
		
		$q_order = "SELECT orders.id, orders.order_name, orders.status, shipping.ship_date
					FROM orders, shipping
					WHERE orders.id = shipping.order_id
					AND (orders.status = 2 OR orders.status = 3 OR orders.status = 4 OR orders.status = 5 )
					AND orders.customer_id = '$c_id'
					ORDER BY orders.status ASC";
		
		// AND orders.customer_id = '$c_id'
		
		
		
		$res_order = $this->db->query($q_order);
		
		$return = array('new' => $new, 'backlog_noship' => $backlog_noship, 'on_account' => $on_account, 'paid' => $paid);
		$past_orders =  $res_order->result_array();
		
		$cnt = count($past_orders);
		for($i=0;$i<$cnt;$i++){
			$s = $past_orders[$i]['status'];
			if($s == 2 || $s == 4){ 
				// check to make sure a ship date is set for these 
				if($past_orders[$i]['ship_date'] == '0000-00-00'){
					$past_orders[$i]['needs_ship'] = true;
				}
				
			}
		}
		
		$return['past_orders'] = $past_orders;
		
		// get pending orders 
		$q_order = "SELECT orders.id, orders.order_name, orders.status
					FROM orders
					WHERE orders.status = 6
					AND orders.customer_id = '$c_id' ";
					
		$res_pend = $this->db->query($q_order);
		
		$return['check_by_fax'] = $res_pend->result_array();
	
		
		return $return;
		
	
	}
	
	function qq_to_order($qq_id, $customer_id = ''){
		
		// check to see if there is an order that is associated with the quote. 
		
		$this->db->select('id');
		$this->db->where('qq_id', $qq_id);
		$this->db->where('status', 1);
		$this->db->order_by('order_date', 'desc');
		$this->db->limit(1);
		$result = $this->db->get('orders');
		$r = $result->result_array();
		if(!empty($r)){
			return $r[0]['id'];
		} else {
			return 0;
		}
					
	}
	
	function set_qq_sesh($qq_id, $order_id = 0){
		
		// set the qq_id in session var
		$this->session->set_userdata('qq_id', $qq_id);
		
		// get the product with the hightest quantity so they can start tagging. Tagging
		$q = "SELECT quantity, product_type_id
					FROM quick_quote_items
					WHERE qq_id =  '$qq_id'
					ORDER BY quantity DESC 
					LIMIT 0 , 1";
					
		$result = $this->db->query($q);
		$row = $result->result_array();
		
		// check to see if there is an existing one for the client
	
		$this->db->select('id');
		$this->db->where('customer_id', $this->session->userdata('user_id'));
		$this->db->where('status', 1);
		$this->db->order_by('order_date', 'desc');
		$this->db->limit(1);
		$result = $this->db->get('orders');
		$rows = $result->result_array();
		
		if(!empty($rows)){
		// they have chosen a quick quote and already have an order going. update the qq_id for the order
			$data = array(
			'qq_id' => $qq_id
			);
			$this->db->where('id', $rows[0]['id']);
			$this->db->update('orders', $data);	
			
			return 'to_cart';
			
		} else {
		// return the product with the highest quote. 
		return $row[0]['product_type_id'];
		}
				
	}
	
	function qq_order_compare($qq_id){
		
			$this->db->where('qq_id', $qq_id);
			$result = $this->db->get('quick_quote_items');
			$qq = $result->result_array();
			
			// check to see if they have an order on file
			$order_id = $this->qq_to_order($qq_id);
			if($order_id != 0){
				
			$o = $this->get_order_items($order_id);

			$order_items = array();
			foreach($o as $a){
				$pr = $a['product_type_id'];
				if(empty($order_items[$pr])){				
				$order_items[$pr] = 0;	
				$order_items[$pr]++;
				} else {
				$order_items[$pr]++;	
				}
			}

			$qq_items = array();
			foreach($qq as $a){
				$pr = $a['product_type_id'];
				$this->db->select('description');
				$this->db->where('id', $pr);
				$result = $this->db->get('product_type');
				$res = $result->result_array();				
				$qq_items[$pr] = array(
									'quantity' => $a['quantity'],
									'description' => $res[0]['description']
									);	
			}

			$missing = array();

			if(!empty($order_items)){
				// go through each quick quote item
				foreach($qq_items as $key=>$value){

					// if they have an item in the order, subtract it off so you can figure out how many they have to select
					if(array_key_exists($key, $order_items)){
					// subtract the amount already in the order
					$omit = $qq_items[$key]['quantity'] - $order_items[$key];
					// echo "DIFF ".  $omit . "<br />";
					$missing[$key] = ($omit >= 0 ? $omit : 0);
						if($missing[$key] == 0){
							// they have filled the quote, or more so remove this from the missing list
							unset($missing[$key]);
						}
					} else {
					// leave them all there, they don't have that product in their order. 	
					$missing[$key] = $qq_items[$key]['quantity'];
					}	
				}

			} else {

				// they don't have any order items, so start fresh! they need everything
				$missing = $qq_items;
			}
			
			$order = $this->get_orders(1,'','','', '', $order_id, true);
			
			$return = array(
				'order_items' => $order_items,
				'qq_items' => $qq_items,
				'missing' => $missing,
				'o' => $o,
				'qq' => $qq,
				'order' => $order[0]
			);
			
			return $return;
			
		 } else {
			// no order yet! send back empty array;
			return array();
		}
			
			
	}
	
	function get_to_expire($rep_id){
	
		$q = "SELECT orders.order_name, orders.expire_date, users.fname, users.lname, users.phone, users.usern_email FROM orders, users
		WHERE STATUS =  '1'
		AND orders.customer_id = users.id
		AND users.rep_id = '$rep_id'
		AND  expire_date 
		BETWEEN NOW( ) 
		AND DATE_ADD( NOW( ) , INTERVAL 1 
		DAY )";
	
		$result = $this->db->query($q);
	
		return $result->result_array();

	
	}
	
	function get_to_expire_admin(){
	
		$q = "SELECT orders.order_name, orders.expire_date, users.fname, users.lname, users.phone, users.usern_email FROM orders, users
			WHERE STATUS =  '1'
			AND orders.customer_id = users.id
			AND  expire_date 
			BETWEEN NOW( ) 
			AND DATE_ADD( NOW( ) , INTERVAL 1 
			DAY )";
	
			$result = $this->db->query($q);	
			return $result->result_array();

	}
	
	function get_on_account(){
		$q_cust = "SELECT users.id, users.fname, users.lname, users.usern_email, users.phone, users.net_terms, users.credit_limit
					FROM users 
					WHERE credit_limit > 0 ";
		$query = $this->db->query($q_cust);
		$r_cust = $query->result_array();
		$count = count($r_cust);
		for($i=0;$i<$count;$i++){		
				$cc_id = $r_cust[$i]['id'];
				
				$credit_q = "SELECT quick_quote.id, quick_quote.quote_date, (( quick_quote.ship_cost + ( SUM( quick_quote.locked_multiplier * quick_quote_items.quantity * quick_quote_items.locked_price ) + quick_quote.box_price ) - quick_quote.disc 
				) - quick_quote.deposit) AS qq_total, qq_shipping.ship_date, qq_shipping.location, qq_shipping.ship_address, qq_shipping.ship_city, qq_shipping.ship_state, qq_shipping.ship_zip
				FROM quick_quote, quick_quote_items, qq_shipping
				WHERE quick_quote.id = quick_quote_items.qq_id
				AND quick_quote.id = qq_shipping.qq_id
				AND quick_quote.customer_id = '$cc_id'
				AND (quick_quote.status = 1 OR quick_quote.status = 4)
				GROUP BY quick_quote_items.qq_id ";
				
				$credit_r = $this->db->query($credit_q);
				$cr = $credit_r->result_array();
			
				$r_cust[$i]['quotes'] = $cr;
			
				$total_in_credit = 0;
		
				foreach($cr as $c){
						$total_in_credit += $c['qq_total']; 
				}
				
				// check how much they have in orders on credit. 
				$credit_o = "SELECT orders.id, ((shipping.total_cost_cust + SUM(order_items.cust_cost * order_items.quantity)) - orders.deposit ) as o_total
				FROM orders, order_items, shipping
				WHERE orders.id = order_items.order_id
				AND shipping.order_id = orders.id
				AND orders.customer_id = '$cc_id'
				AND (orders.status = 4 OR orders.status = 5) 
				GROUP BY order_items.order_id ";

				$credit_or = $this->db->query($credit_o);
				$co = $credit_or->result_array();

				foreach($co as $c){
						$total_in_credit += $c['o_total']; 
				}
				
				
				
			
				$r_cust[$i]['total_in_credit'] = $total_in_credit;
		}
		
		return $r_cust; 
		
	}
	
	
	

		
}


?>