<?php
class User_model extends CI_Model{

	function __construct(){
		
		parent::__construct();

	} 
	

	
	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
		$this->session->sess_destroy();  
		redirect('please_login/', 'refresh');    
			
		}		
	}
	
	function has_user_priv(){
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function get_multiplier(){
		$this->db->select('multiplier');
		$this->db->where('id', $this->session->userdata('user_id'));
		$res = $this->db->get('users');
		$result = $res->result_array();
		return $result[0]['multiplier'];
	}
	
	function is_logged_in_admin()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		$super_status = $this->session->userdata('user_type');
		if(!isset($is_logged_in) || $is_logged_in != true || $super_status != 'admin')
		{
		$this->session->sess_destroy();  
		redirect('login/', 'refresh'); 			
			
		}		
	}
	
	function is_logged_in_rep()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		$super_status = $this->session->userdata('user_type');
		if(!isset($is_logged_in) || $is_logged_in != true || $super_status != 'rep' )
		{
		$this->session->sess_destroy();  
		redirect('login/', 'refresh'); 				
		}		
	}
	
	function is_logged_in_client()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		$client_id = $this->uri->segment(3);
		$sesh_client_id = $this->session->userdata('client_id');
		$user_id = $this->session->userdata('user_id');
		
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
		
		redirect('login/', 'refresh');
  	
		} 
		    			
		if($client_id != $sesh_client_id ){
		 redirect('please_login/', 'refresh');	
		}   	
	}
	
	function validate()
	{
		$this->db->where('usern_email', $this->input->post('usern_email_login'));
		$this->db->where('password',sha1($this->input->post('password_login')));
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
		   
			$res = $query->result_array();
			return $res;
		} else {
			return false;
		}
		
	}
	
	function get_new_multiplier($ut){
					switch($ut){
						case 'homeowner':
						$multiplier = 0.80;
						break;
						case 'landscaper':
						$multiplier = 0.50;
						break;
						case 'architect':
						$multiplier = 0.50;
						break;
						case 'contractor':
						$multiplier = 0.50;
						break;
						case 'nursery':
						$multiplier = 0.50;
						break;
						case 'broker':
						$multiplier = 0.50;
						break;
						case 'distributor':
						$multiplier = 0.50;
						break;
						default:
						$multiplier = 1;
					}
				return $multiplier;
	}
	
	function create_account(){
		
		//'landscaper', 'architect', 'homeowner', 'contractor', 
		$mt = $this->input->post('multiplier');
		$ut = $this->input->post('user_type');
		$rep_id = $this->input->post('rep_id');
		$multiplier = 1;
		$message = "";

		
		// check for existing user email 
		$this->db->where('usern_email', $this->input->post('usern_email'));
		$count = $this->db->count_all_results('users');
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		$super_status = $this->session->userdata('user_type');
		if(isset($is_logged_in) && $super_status == 'rep' )
		{
		$rep_id = $this->session->userdata('user_id');		
		} else {
			if($rep_id == '' || $rep_id == 0){
				$rep_id = NULL;
			} 
		}
		
		
		if($count == 0){
			
			if($mt != ""){
			$multiplier = $this->input->post('multiplier');
			} else {
			$multiplier = $this->get_new_multiplier($ut);
			}
			
			$net_terms = ($this->input->post('net_terms') == ''? 0 : $this->input->post('net_terms'));
			$credit_limit = ($this->input->post('credit_limit') == '' ? 0.00 : $this->input->post('credit_limit'));
			
			$data = array(
				'fname' => $this->input->post('fname'),
				'lname' => $this->input->post('lname'),
				'usern_email' => $this->input->post('usern_email'),
				'password'  => sha1($this->input->post('password')),
				'company_name' => $this->input->post('company_name'),
				'license_number' => $this->input->post('license_number'),
				'bill_address' => $this->input->post('billing_address'),
				'bill_city' => $this->input->post('billing_city'),
				'bill_state' => $this->input->post('billing_state'),
				'bill_zip' => $this->input->post('billing_zip'),
				'phone' => $this->input->post('phone'),
				'fax' => $this->input->post('fax'),
				'user_type' => $this->input->post('user_type'),
				'multiplier' => $multiplier,
				'dt_signup' => date('Y-m-d'),
				'rep_id' => $rep_id,
				'credit_limit' => $credit_limit,
				'net_terms' => $net_terms
	 		);
		   $this->db->insert('users', $data);
		   $id = $this->db->insert_id();
	      		
					
					$this->load->model('email_model');
					$em[] = "Hello ".$this->input->post('fname').",";
					$em[] = "Welcome to VECCHIO Trees. Please keep a copy of your log in credentials: ";
					$em[] = "<b>Username: ".$this->input->post('usern_email')."</b>";
					$em[] = "<b>Password: ".$this->input->post('password')."</b>";
					if($ut == 'rep'){
					$em[] = "Please log in to your Account here: http://www.vecchiotrees.com/index.php/dir/log_in/my_account";	
					}
					$em[] = "Sincerely, Vecchio Trees";
					$em[] = "vecchiotrees.com" ;
					
					$this->email_model->send_email_to($this->input->post('usern_email'), 'Welcome to VECCHIO Trees', $em,'html');
					
					$this->email_model->send_admin_new($data);
					
					$message = "Success";
					
				
					
					if(isset($is_logged_in) && ($super_status == 'rep' || $super_status == 'admin' )){
					
					// logged in as rep or admin- don't reset the session log in stuff
					
					} else {
						$data = array(
							'usern_email' => $this->input->post('usern_email'),
							'user_id' => $id,
							'user_type' => $ut,
							'fname' => $this->input->post('fname'),
							'lname' => $this->input->post('lname'),
							'multiplier' => $multiplier,
							'is_logged_in' => true,
							'qq_id' => 0
						);

						$this->session->set_userdata($data);
					}
				
		} else {
				$message = "Fail";
		}	
		
		return $message;
	}
	
    function get_all_users($type){
	  //'admin', 'landscaper', 'architect', 'homeowner', 'contractor', 'rep'
	    $return = array();
		
		if($this->session->userdata('user_type') == 'rep'){
			$repon = true;
			$rep_id = $this->session->userdata('user_id');
			$in_house = $this->is_in_house();
		} else {
			$repon = false;
		}
		
		if($type == 'contractor'){
		
		$this->db->where('user_type', 'contractor');
		if($repon && $in_house == false){
		$this->db->where('rep_id', $rep_id);	
		}
		$this->db->order_by('lname', 'asc');
		$query_cont = $this->db->get('users');
		
		$return['contractor'] = $query_cont->result_array();
		
		} else if( $type == 'architect'){
	
		$this->db->where('user_type', 'architect');
		if($repon && $in_house == false){
		$this->db->where('rep_id', $rep_id);	
		}
		$this->db->order_by('lname', 'asc');
		$query_arch = $this->db->get('users');
		$return['architect'] = $query_arch->result_array();
		
		} else if($type == 'landscaper'){ 
			
		$this->db->where('user_type', 'landscaper');
		if($repon && $in_house == false){
		$this->db->where('rep_id', $rep_id);	
		}
		$this->db->order_by('lname', 'asc');
		$query_land = $this->db->get('users');
		$return['landscaper'] = $query_land->result_array();
		
		} else if($type == 'homeowner'){
					
		$this->db->where('user_type', 'homeowner');
		if($repon && $in_house == false){
		$this->db->where('rep_id', $rep_id);	
		}
		$this->db->order_by('lname', 'asc');
		$query_home = $this->db->get('users');
		$return['homeowner'] = $query_home->result_array();
		
		} else if($type == 'nursery'){
					
		$this->db->where('user_type', 'nursery');
		if($repon && $in_house == false){
		$this->db->where('rep_id', $rep_id);	
		}
		$this->db->order_by('lname', 'asc');
		$query_home = $this->db->get('users');
		$return['nursery'] = $query_home->result_array();
		
		} else if($type == 'distributor'){
					
		$this->db->where('user_type', 'distributor');
		if($repon && $in_house == false){
		$this->db->where('rep_id', $rep_id);	
		}
		$this->db->order_by('lname', 'asc');
		$query_home = $this->db->get('users');
		$return['distributor'] = $query_home->result_array();
		
		} else if($type == 'broker'){
					
		$this->db->where('user_type', 'broker');
		if($repon && $in_house == false){
		$this->db->where('rep_id', $rep_id);	
		}
		$this->db->order_by('lname', 'asc');
		$query_home = $this->db->get('users');
		$return['broker'] = $query_home->result_array();
		
		} else if($type == 'rep'){
			if(!$repon){
		
				$this->db->where('user_type', 'rep');
				$this->db->order_by('lname', 'asc');
				$query_rep = $this->db->get('users');
				$return['rep'] = $query_rep->result_array();
			} else { 
				$return['rep'] = array();
			}
		} else if($type == 'admin') {
			if(!$repon){
			$this->db->where('user_type', 'admin');
			$this->db->order_by('lname', 'asc');
			$query_admin = $this->db->get('users');
			$return['admin'] = $query_admin->result_array();
			} else {
			$return['admin'] = array();	
			}
			
		}

		

		
		return $return;
		
		
	}
	
	function c_rep(){
		if($this->session->userdata('user_type') == 'rep'){
				return TRUE;
			} else {
				return FALSE;
			}
	}
	
	function get_all_reps($type = 'object'){
		
		$this->db->where('user_type', 'rep');
		$this->db->order_by('lname', 'asc');
		$query_rep = $this->db->get('users');
		if($type == 'object'){
		$return = $query_rep->result();
		} else {
		$return = $query_rep->result_array();
		}
		return $return;
		
	}
	
	function get_user_by_id($id){
		
		$this->db->where('id', $id);
		$query = $this->db->get('users');
		$result = $query->result_array();
		if($this->session->userdata('user_type') == 'rep'){
		// check if they have user permission
		$id = $this->session->userdata('user_id');
		if($id != $result[0]['rep_id']){
		return false;	
		} else {
			return $result;
		}

		} else {
			return $result;
		}
		
	}
	
	function is_in_house(){
		 $id = $this->session->userdata('user_id');
		 $this->db->where('id', $id);
		 $this->db->where('in_house', 1);
		 $count = $this->db->count_all_results('users');
		
		 if($count == 1){
			return true;
		 } else {
			return false;
		 }
		
	}
	
	function vecchio_settings(){
		$q = $this->db->get('settings');
		$res = $q->result_array();
		$noz = $res[0];
		return $noz;
	}
	
	function get_stats(){
		$result = array();
		$this->db->where('status', '1');
		$this->db->from('orders');
		$result['new_orders'] = $this->db->count_all_results();
		$this->db->where('status', '2');
		$this->db->from('orders');
		$result['in_processing'] = $this->db->count_all_results();
		$this->db->where('status', '3');
		$this->db->from('orders');
		$result['shipped'] = $this->db->count_all_results();
		
		// for quick quotes --
		$this->db->where('admin_void', '0');
		$this->db->from('quick_quote');
		$result['quick_quotes'] = $this->db->count_all_results();		
		
		
		$qbase = "SELECT COUNT( p.id ) AS count
		FROM products AS p
		JOIN product_type AS pt ON ( pt.id = p.product_type_id ) 
		LEFT JOIN (
			SELECT order_items.product_id, orders.status
			FROM order_items, orders
			WHERE orders.id = order_items.order_id
		) AS o ON ( p.id = o.product_id )";
		
		$q_avail = $qbase . " WHERE o.status IS NULL";
		$res = $this->db->query($q_avail);
		$res_array = $res->result_array();
		$result['available_products'] = $res_array[0]['count'];
		
		$q_tagg = $qbase . " WHERE o.status = 1";
		$res = $this->db->query($q_tagg);
		$res_array = $res->result_array();
		$result['tagged_products'] = $res_array[0]['count'];
		
		$q_tagg = $qbase . " WHERE o.status = 2";
		$res = $this->db->query($q_tagg);
		$res_array = $res->result_array();
		$result['new_order_products'] = $res_array[0]['count'];

		$q_tagg = $qbase . " WHERE o.status = 2";
		$res = $this->db->query($q_tagg);
		$res_array = $res->result_array();
		$result['in_processing_products'] = $res_array[0]['count'];
		
		$q_tagg = $qbase . " WHERE o.status = 3";
		$res = $this->db->query($q_tagg);
		$res_array = $res->result_array();
		$result['shipped_products'] = $res_array[0]['count'];
		
		$this->db->where('user_type !=', 'rep');
		$this->db->where('user_type !=', 'admin');
		$this->db->from('users');
		$result['customers'] = $this->db->count_all_results();
		
		// sample comment
		
		return $result;		
	
	}
	
	function get_cart_count(){
		if ($this->session->userdata('user_id') !== FALSE) {
			$customer_id = $this->session->userdata('user_id');
		
			// get how many items they have in cart
			$q = "(SELECT count(*) as num_in_cart, SUM(cust_cost) as cart_cost
					FROM order_items
					WHERE order_id IN
					(SELECT orders.id
					FROM orders, users
					WHERE orders.customer_id = users.id
					AND orders.customer_id = '$customer_id'
					AND orders.status =  1))";
				$result = $this->db->query($q);

				$row = $result->result_array();
		
				$data = array(
					'num_in_cart' => $row[0]['num_in_cart'],
					'cart_cost' =>  $row[0]['cart_cost']
					);
				$this->session->set_userdata($data);
		}
	}
	
	function check_expired(){

	 	$q = "SELECT id
	       		FROM orders
	       		WHERE status = 1 
	       		AND expire_date < CURRENT_TIMESTAMP() ";
	 	$result = $this->db->query($q);
		$row = $result->result_array();
		$count = count($row);
	
		for($i = 0; $i<$count; $i++){
			
			$this->delete_entire_order('order expired', $row[$i]['id']);
		}
		return $row;
	 
	}
	
	function delete_entire_order($drop_type,  $order_id = ""){
		
		if($order_id == ""){
			$order_id  = $this->input->post('order_id');
		}
		$this->db->where('id', $order_id);

		$data = array(
				     'status' => 7
		);
	  	$deleted = $this->db->update('orders', $data);
		
		// place information in dropped_orders table 
		$dt = date('Y-m-d H:i:s');
		
		$dropped_order = array(
				'order_id' => $order_id,
				'drop_date' => $dt,
				'drop_type' => $drop_type
		);
		
		$this->db->insert('dropped_orders', $dropped_order);
		
		$this->db->select('id, cust_cost');
		$this->db->where('order_id', $order_id);
		$result = $this->db->get('order_items');
		$row = $result->result_array();
		
		$count = count($row);
		for($i = 0; $i<$count; $i++){
		$data = array(
			'order_id' => $order_id,
			'product_id' => $row[$i]['id'],
			'cust_cost' => $row[$i]['cust_cost']
		);	
		$this->db->insert('dropped_order_items', $data);
		}
        if($count > 0){
		$this->db->where('order_id', $order_id);	
		$delete_items = $this->db->delete('order_items');
		}	
		return 'Deleted';
	}
	
	function get_users_by_rep($rep_id, $user_id = '', $in_house = false){
		if($in_house == false){
		$q = "SELECT id, fname, lname, multiplier, user_type FROM users WHERE rep_id = '$rep_id'";
		} else {
		$q = "SELECT id, fname, lname, multiplier, user_type FROM users WHERE user_type != 'rep' AND user_type != 'admin' ORDER BY lname ASC";	
		}
		if($user_id != ''){
		$q .= " AND id = '$user_id'";	
		}
		$result = $this->db->query($q);
		$row = $result->result_array();
		return $row;
	}
	
	// search for a member 
	
	function search_users($search_term){
		if($this->session->userdata('user_type') == 'rep'){
			$repon = true;
			$rep_id = $this->session->userdata('user_id');
			$in_house = $this->is_in_house();
		} else {
			$repon = false;
		}
		$this->db->like('fname', $search_term); 
		$this->db->or_like('lname', $search_term); 
		$this->db->order_by('lname', 'asc');
		if($repon && $in_house == false){
		$this->db->where('rep_id', $rep_id);	
		}

		$result = $this->db->get('users');
		$row = $result->result_array();
		$arr['search'] = $row;
		return $arr;
		
	}
	
	function get_user_stats(){
			if($this->session->userdata('user_type') == 'rep'){
				$repon = true;
				$rep_id = $this->session->userdata('user_id');
				$in_house = $this->is_in_house();
			} else {
				$repon = false;
			}
			
		$result = array();
		$this->db->where('user_type', 'architect');
		if($repon && $in_house == false){
			$this->db->where('rep_id', $rep_id);	
		}
		$this->db->from('users');
		$result['architect'] = $this->db->count_all_results();
		
		$this->db->where('user_type', 'homeowner');
		if($repon && $in_house == false){
			$this->db->where('rep_id', $rep_id);	
		}
		$this->db->from('users');
		$result['homeowner'] = $this->db->count_all_results();
		
		$this->db->where('user_type', 'landscaper');
		if($repon && $in_house == false){
			$this->db->where('rep_id', $rep_id);	
		}
		$this->db->from('users');
		$result['landscaper'] = $this->db->count_all_results();
		
		$this->db->where('user_type', 'contractor');
		if($repon && $in_house == false){
			$this->db->where('rep_id', $rep_id);	
		}
		$this->db->from('users');
		$result['contractor'] = $this->db->count_all_results();
		
		$this->db->where('user_type', 'nursery');
		if($repon && $in_house == false){
			$this->db->where('rep_id', $rep_id);	
		}
		$this->db->from('users');
		$result['nursery'] = $this->db->count_all_results();
		
		$this->db->where('user_type', 'broker');
		if($repon && $in_house == false){
			$this->db->where('rep_id', $rep_id);	
		}
		$this->db->from('users');
		$result['broker'] = $this->db->count_all_results();
		
		$this->db->where('user_type', 'distributor');
		if($repon && $in_house == false){
			$this->db->where('rep_id', $rep_id);	
		}
		$this->db->from('users');
		$result['distributor'] = $this->db->count_all_results();
		
		$this->db->where('user_type', 'rep');
		$this->db->from('users');
		$result['rep'] = $this->db->count_all_results();
		
		$this->db->where('user_type', 'admin');
		$this->db->from('users');
		$result['admin'] = $this->db->count_all_results();
		
		return $result;
		
	}
	
	
	

}

?>