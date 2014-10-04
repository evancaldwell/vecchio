<?php
class Product_model extends CI_Model{

	function __construct(){
		
		parent::__construct();

	}
	 
	function get_grow_yards(){
				$this->db->where('active',1);
		$q = $this->db->get('grow_yards');
		return $q->result();
	}
	
	function get_grow_yard_name($grow_yard_id){
		$this->db->select('grow_yard_name');
		$this->db->where('id', $grow_yard_id);
		$q = $this->db->get('grow_yards');
		$result = $q->result_array();
		return $result[0]['grow_yard_name'];
	}
	
	function get_grow_yards_arr(){
				$this->db->where('active',1);
		$q = $this->db->get('grow_yards');
		return $q->result_array();
	}
	
	function get_product_type($type = 'reg'){
		$this->db->select('id, product_code, description, specs, list_price');
		$this->db->order_by('product_code');
		$this->db->where('active',1);
		$q = $this->db->get('product_type');
		if($type == 'reg') {
		 return $q->result();
		} else {
		 return $q->result_array();	
		}
	}
	
	function get_product_type_aval(){	
		/* Depreciated  */
		$this->db->select('product_type.id, product_type.product_code, product_type.description, product_type.specs');
		$this->db->from('product_type');
		$this->db->join('products', 'products.product_type_id = product_type.id');
		$this->db->where('products.order_status = 0');
		$this->db->order_by('product_type.product_code');
		$q = $this->db->get();
		return $q->result();
	}
	
	function create_product($new_product_insert_data){
		$insert = $this->db->insert('products', $new_product_insert_data);
		return $insert;
	}
	
	function get_products($product_category='', $product_type='', $product_id =''){
		$this->db->select('products.id as products__id,  images.file_name');
		$this->db->from('product_category');
		$this->db->join('product_type', 'product_category.id = product_type.product_category_id', 'left');
		$this->db->join('products', 'product_type.id = products.product_type_id', 'left');
		$this->db->join('images', 'products.id = images.product_id', 'left');
		$this->db->where('products.order_status', '0');
		$this->db->order_by('product_type_id', 'asc');
		$query = $this->db->get();
		
		$res_products = $query->result_array();
		
		$count = count($res_products);
		
		return $res_products;
			
	}
	function update_product($data){
	}
	
	function get_product_name($product_code){
	$this->db->select('description, list_price, product_code, specs');
	$this->db->where('id', $product_code);
	$q = $this->db->get('product_type');
	$result = $q->result_array();
	return $result[0];
	}
	
	function check_if_avail($product_id){
		$this->db->where('product_id', $product_id);
		$this->db->from('order_items');
		return $this->db->count_all_results();
	}
	
	function product_quick_quote($products, $customer_id){
		$this->db->select('multiplier');
		$this->db->where('id', $customer_id);
		$result = $this->db->get('users');
		$mult = $result->result_array();
		$mult = $mult[0]['multiplier'];
	}
	
	function check_inventory($product_id){
		
		$q = "SELECT COUNT(*) as cnt
			  FROM products 
			  LEFT JOIN order_items ON (products.id = order_items.product_id)
			  WHERE products.product_type_id = '$product_id'
			  AND order_id IS NULL";

		$res = $this->db->query($q);
		$result = $res->result_array();
		return  $result[0]['cnt'];
	}
	
	function get_count_products_avail($json = false, $olives = false, $all = false){
		
		// This counts how many products are available in each product type for sale... 
		
		$q = "SELECT COUNT(*) as cnt, product_code, product_type_id, product_type.weight, description, specs, list_price, order_id
		FROM products
		JOIN product_type ON ( products.product_type_id = product_type.id ) 
		LEFT JOIN order_items ON ( products.id = order_items.product_id ) 
		WHERE order_id IS NULL";
		if($olives){
		$q .= " AND product_category_id = '1'";
		}
		
		$q .= " GROUP BY product_type_id
		ORDER BY cnt DESC";
		
		
		if($json){
		$result = $this->db->query($q);
		$data = $result->result();
		
		return json_encode($data);
		
		} else {
			
			$result = $this->db->query($q);
			$rows = $result->result_array();
						
			
			return $rows;	
			
		}
	}
	

	

	function get_products_by($type,$id){
			// BEHOLD: THE BEAST SQL STATEMENT .... GRRBRRGRGRR
			$q = "SELECT p.id, 
					  	p.serial_no, 
						p.grow_yard_id, 
						p.product_type_id, 
						pt.description, 
						pt.specs, 
						o.status, 
						pt.list_price, 
						o.cust_cost, 
						o.fname, 
						o.lname, 
						o.company_name,
						o.order_date,
						o.expire_date,
						o.payment_date,
						o.shipped_date
			FROM products AS p
			JOIN product_type AS pt ON (pt.id = p.product_type_id)
			LEFT JOIN ( SELECT  order_items.product_id, 
								orders.status, 
								orders.customer_id,
								orders.order_date,
								orders.expire_date,
								orders.payment_date,
								orders.shipped_date, 
								users.fname, 
								users.lname,
								order_items.cust_cost, 
								users.company_name
			            FROM order_items, orders, users
			            WHERE orders.id = order_items.order_id
						AND orders.customer_id = users.id
					  ) AS o
			ON (p.id = o.product_id)";
			
			if($type == 'products'){
				$q .= " WHERE p.product_type_id = '$id' AND ( o.status IS NULL OR o.status = 1 ) ";
			} else if($type == 'grow_yard'){
				$q .= " WHERE p.grow_yard_id = '$id' ";
			} else if($type == 'avail'){
				$q .= " WHERE o.status IS NULL ";
			} else if($type == 'tagged'){
				$q .= " WHERE o.status = 1 ";
			} else if($type == 'sold'){
			    $q .= " WHERE o.status = 2 ";
			} else if($type == 'shipped'){
				$q .= " WHERE o.status = 3 ";	
			} else if($type == 'product'){
				$q .= " WHERE p.id = '$id' ";
			} else if($type == 'avail_product_type'){
				$q .= " WHERE p.product_type_id = '$id' AND o.status IS NULL ";
			} else if($type == 'avail_grow'){
				$q .= " WHERE p.grow_yard_id = '$id' AND ( o.status IS NULL OR o.status = 1 ) ";
			}
			$q .= " LIMIT 0,250 ";
	        $qnew = $this->db->query($q);
			$res = $qnew->result_array();
			if (empty($res)){	
				return "empty set";
			}
			$count = count($res);
			
			$this->load->model('user_model');
			$pr = $this->user_model->has_user_priv();
			
			for($i=0; $i<$count; $i++){	
				
				if(!$pr){
					$a = anchor('dir/log_in', "Log In To View"); 
					$res[$i]['list_price'] = $a;
					$res[$i]['pr'] = 'red';
				} else {
					$res[$i]['pr'] = 'green';
					$mult = $this->user_model->get_multiplier();
					$res[$i]['customer_price'] = ($res[$i]['list_price'] * $mult); 
				}
				
				$this->db->select('id, file_name, icon_file_name');
				$this->db->where('product_id', $res[$i]['id']);
				$q2 = $this->db->get('images');
			  	$img = $q2->result_array();
			    if(!empty($img)){
			    $res[$i]['files'] = $img;
		        } else {
			    $res[$i]['files'] = array(0 => array('file_name' => 'default.png', 'icon_file_name' => 'default_thumb.png'));
		    	}

			
			}
			
			return $res;
	
	}	

	

	function genRandomString($max = 6){
	        $chars = "ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
			$string = "";
	        for($i = 0; $i < $max; $i++){
	            $rand_key = mt_rand(0, strlen($chars));
	            $string  .= substr($chars, $rand_key, 1);
	        }
	        return str_shuffle($string);
	    }
		
	function check_serial_availablity()
	{
	    $serial_no = trim($this->input->post('serial_no'));
		$serial_no = strtoupper($serial_no);	

		$query = $this->db->query('SELECT id FROM products WHERE serial_no="'.$serial_no.'"');

		if($query->num_rows() > 0)
		return false;
		else
		return true;
	}
	
	function get_short_gy($grow_id)
	{
	  
		$query = $this->db->query('SELECT short_name FROM grow_yards WHERE id="'.$grow_id.'"');
        $result = $query->result_array();
        return $result;
	}
	
	function get_product_id($id)
	{
	  
		$query = $this->db->query('SELECT product_code FROM product_type WHERE id="'.$id.'"');
        $result = $query->result_array();
        return $result;
	}
	
	function get_cust_price($order_id, $product_id){

   	$q = "SELECT users.multiplier
	 		FROM users, orders
	 		WHERE orders.customer_id = users.id
	 		AND orders.id = '$order_id'";
	 $q_mult = $this->db->query($q);
	 $mt = $q_mult->result_array();

	 $lpq = "SELECT product_type.list_price 
	 	     FROM product_type, products
	 		 WHERE products.product_type_id = product_type.id
	 		 AND products.id = '$product_id'";
	 $q_lp = $this->db->query($lpq);
	 $lp = $q_lp->result_array();
	 
	 return ($lp[0]['list_price'] * $mt[0]['multiplier']);	
	}
	
	function remove_product_images($product_id){
		$this->db->select('id, file_name, icon_file_name');
		$this->db->where('product_id', $product_id);
		$res = $this->db->get('images');
		$res_array = $res->result_array();
		if(!empty($res_array)){
		
		// delete from server
		foreach($res_array as $res){
			//$base = base_url();
			@unlink($base .'_fdr/'.$res['file_name']);
			@unlink($base . '_fdr/thumbs/' . $res['icon_file_name']);
		}
		
		// delete from database 
		$this->db->where('product_id', $product_id);
		$res = $this->db->delete('images');
		}
		
	}
	
	function get_product_serial_no($product_id){
		$product_id = mysql_real_escape_string($inp);
		$q = "SELECT serial_no FROM products WHERE id = '$product_id'";
		$result = $this->db->query($q);
		$res = $result->result_array();
		return $res[0];
	}
	
	function search_products($inp, $admin = false){

	$inp = mysql_real_escape_string($inp);
	// search by serial number, description, specs NOT IN current orders. 
	$q = "SELECT products.id, product_type.description, products.serial_no, product_type.product_code, product_type.specs, product_type.id as pr_id, images.icon_file_name
		  FROM product_type, products, images
		  WHERE products.product_type_id = product_type.id
		  AND images.product_id = products.id
	  	  AND (products.serial_no LIKE '%$inp%' OR product_type.description LIKE '%$inp%' OR product_type.specs LIKE '%$inp%' OR concat_ws(' ',product_type.specs,product_type.description) LIKE '%$inp%'  ) ";
	if(!$admin){
	$q .= "AND products.id NOT IN (SELECT product_id 
								  FROM orders, order_items 
								  WHERE orders.id = order_items.order_id
							  	  AND (orders.status = 1 OR orders.status = 2 OR orders.status = 3 )
								  )
		  LIMIT 0,5 ";
	}	
	$result = $this->db->query($q);
	$rows= $result->result_array();
		if(!empty($rows)){
			return $rows;
		} else {
			return '';
		}
	}
	

	
	function serch_by_serial_no($inp, $admin = false){
		
	 $inp = mysql_real_escape_string($inp);
		$q = "SELECT products.id, product_type.description, products.serial_no, product_type.product_code, product_type.specs, product_type.id as pr_id, images.icon_file_name
			  FROM product_type, products, images
			  WHERE products.product_type_id = product_type.id
			  AND images.product_id = products.id
			  AND products.serial_no LIKE '%$inp%' ";
		if(!$admin){
		$q .= "AND products.id NOT IN (SELECT product_id 
									  FROM orders, order_items 
									  WHERE orders.id = order_items.order_id
								  	  AND (orders.status = 1 OR orders.status = 2 OR orders.status = 3 )
									  )
			  LIMIT 0,5 ";
		}
		$result = $this->db->query($q);
		$rows= $result->result_array();
			if(!empty($rows)){
				return $rows;
			} else {
				return '';
			}	
			
	}
	
	
	function search_by_row_tree($grow_yard, $tree, $row, $admin = false){
		
		$tree_search = '';
		if($tree < 10){
			$tree_search = 'T' . '00' . $tree;
		} else if($tree < 100 && $tree >= 10){
			$tree_search = 'T' . '0' . $tree;
		} else if($tree >= 100){
			$tree_search = 'T' . $tree;
		}
		
		$row_search = '';
		if($row < 10){
			$row_search = 'R' . '00' . $row;
		} else if($row < 100 && $row >= 10){
			$row_search = 'R' . '0' . $row;
		} else if($tree >= 100){
			$row_search = 'R' . $row;
		}
		
			
		$rt_search =  mysql_real_escape_string($row_search . "-" . $tree_search);	
		$grow_yard = mysql_real_escape_string($grow_yard);
		
		$q = "SELECT products.id, product_type.description, products.serial_no, product_type.product_code, product_type.specs, product_type.id as pr_id, images.icon_file_name
			  FROM product_type, products, images
			  WHERE products.product_type_id = product_type.id
			  AND images.product_id = products.id
			  AND products.serial_no LIKE '%$rt_search%'
			  AND products.serial_no LIKE '%$grow_yard%' ";
		if(!$admin){
		$q .= "AND products.id NOT IN (SELECT product_id 
									  FROM orders, order_items 
									  WHERE orders.id = order_items.order_id
								  	  AND (orders.status = 1 OR orders.status = 2 OR orders.status = 3 )
									  )
			  LIMIT 0,5 ";
		}
		$result = $this->db->query($q);
		$rows= $result->result_array();
	//	print_r($rows);
		return $rows;
			
	}
	
	function search_by_h_w($h, $w, $admin = false){
		$h = mysql_real_escape_string($h);
		$w = mysql_real_escape_string($w);
		$tree_search = '';
	//	23'Tx16'W
		$base = $h . "\'" . "Tx" . $w . "\'W";
		$up_1_h = ($h + 1) . "\'" . "Tx" . ($w + 0) . "\'W";
		$up_1_w = ($h + 0) . "\'" . "Tx" . ($w + 1) . "\'W";
		$up_1_h_w = ($h + 1) . "\'" . "Tx" . ($w + 1) . "\'W";
		$up_2_h = ($h + 2) . "\'" . "Tx" . ($w + 0) . "\'W";
		$up_2_w = ($h + 0) . "\'" . "Tx" . ($w + 2) . "\'W";
		$up_2_h_w = ($h + 2) . "\'" . "Tx" . ($w + 2) . "\'W";
		$up_n1_h = ($h - 1) . "\'" . "Tx" . ($w + 0) . "\'W";
		$up_n1_w = ($h + 0) . "\'" . "Tx" . ($w - 1) . "\'W";
		$up_n1_h_w = ($h - 1) . "\'" . "Tx" . ($w - 1) . "\'W";
		$up_n2_h = ($h - 2) . "\'" . "Tx" . ($w + 0) . "\'W";
		$up_n2_w = ($h + 0) . "\'" . "Tx" . ($w - 2) . "\'W";
		$up_n2_h_w = ($h - 2) . "\'" . "Tx" . ($w - 2) . "\'W";		
			
		$q = "SELECT products.id, product_type.description, products.serial_no, product_type.specs, product_type.id as pr_id, images.icon_file_name
			  FROM product_type, products, images
			  WHERE products.product_type_id = product_type.id
			  AND images.product_id = products.id
			  AND ( 
				   product_type.specs LIKE '%$base%' OR
				   product_type.specs LIKE '%$up_1_h%' OR
				   product_type.specs LIKE '%$up_1_w%' OR
				   product_type.specs LIKE '%$up_1_h_w%' OR
				   product_type.specs LIKE '%$up_2_h%' OR
				   product_type.specs LIKE '%$up_2_w%' OR
				   product_type.specs LIKE '%$up_2_h_w%' OR
			       product_type.specs LIKE '%$up_n1_h%' OR
				   product_type.specs LIKE '%$up_n1_w%' OR
			       product_type.specs LIKE '%$up_n1_h_w%' OR
			       product_type.specs LIKE '%$up_n2_h%' OR
			       product_type.specs LIKE '%$up_n2_w%' OR
		       	   product_type.specs LIKE '%$up_n2_h_w%'
				   ) ";
			if(!$admin){
			$q .= "AND products.id NOT IN (SELECT product_id 
										  FROM orders, order_items 
										  WHERE orders.id = order_items.order_id
									  	  AND (orders.status = 1 OR orders.status = 2 OR orders.status = 3 )
										  )
				  LIMIT 0,5 ";
			}
			
	//	echo $q;	
		$result = $this->db->query($q);
		$rows= $result->result_array();
		return $rows;
	}
	
	
	/* FUNCTION DEPRECIATED WITH DATABASE NORMALIZATION - STILL AWESOME... 
	function get_products_byDEPR($type,$id){

    	

		$this->db->select('products.id,
						   products.serial_no,
						   products.order_status,
						   product_type.description,
						   product_type.list_price,
					       product_type.product_code,
					       product_type.specs'
						   );
		$this->db->from('products');
		$this->db->join('product_type', 'product_type.id = products.product_type_id');
		if($type == 'products'){
			$this->db->where('product_type_id', $id);
		} else if($type == 'grow_yard'){
			$this->db->where('grow_yard_id', $id);
		} else if($type == 'avail'){
			$this->db->where('order_status', 0);
		} else if($type == 'tagged'){
			$this->db->where('products.order_status', 1);
		} else if($type == 'sold'){
		  $this->db->where('products.order_status', 2);
		} else if($type == 'shipped'){
			$this->db->where('products.order_status', 3);	
		}
        $q = $this->db->get();
		$res = $q->result_array();	
	
		$count = count($res);
		for($i=0; $i<$count; $i++){	
		$this->db->select('id, file_name, icon_file_name');
		$this->db->where('product_id', $res[$i]['id']);
		$q2 = $this->db->get('images');
	  	$img = $q2->result_array();
	    if(!empty($img)){
	    $res[$i]['files'] = $img;
        } else {
	    $res[$i]['files'] = array(0 => array('icon_file_name' => 'default.jpg'));
		}
	     // Get the order info if order_status != 0
	     // If the order_status != 0 that means its part of a select of tagged / ordered / shipped / products 
	    	if($res[$i]['order_status'] != 0 ){
		    	$s_prod_id = $res[$i]['id'];
		        // Get all the info on that product
		        $q = "SELECT order_items.cust_cost, 
							 orders.order_date, 
							 orders.expire_date, 
							 orders.payment_date, 
							 orders.shipped_date, 
							 users.company_name, 
							 users.fname, 
							 users.lname
		              FROM order_items, orders, users
					  WHERE order_items.order_id = orders.id
					  AND orders.customer_id = users.id
					  AND order_items.product_id = '$s_prod_id'";
				$order_res = $this->db->query($q);
				$info = $order_res->result_array();
				$res[$i]['order_info'] = $info[0];
			} else {
				$res[$i]['order_info'] = 'not_in_order';
			}
				
				
					
		}
			
		I Tried, worked for two image files, three files go crashy, no joiny joiny.
		$this->db->select('products.id,
						   products.serial_no,
						   products.order_status,
						   images.file_name,
						   images.icon_file_name');
		$this->db->from('products');
		$this->db->join('images', 'images.product_id = products.id', 'left');
		//$this->db->where('product_type_id', $product_code);
		$this->db->where('grow_yard_id', $grow_yard_id);
        $q = $this->db->get();
		$res = $q->result_array();	
	
		$count = count($res);
		
		
		
		
		// First loop - put the images in part of the array called "files"
		for($i=0; $i<$count; $i++){
			$temp_file = $res[$i]['file_name'];
			$temp_icon = $res[$i]['icon_file_name'];
			$res[$i]['files'][] = array('file_name' => $temp_file, 'icon_file_name' => $temp_icon);
			unset($res[$i]['file_name']);
			unset($res[$i]['icon_file_name']);
		}
		// check for duplicates - remove images from duplicate and place on previous, and pop it off. 
		
		$ph = $res[0]['id']; //<- set the first id to check for duplicates, then start on the second. v
		for($i=1; $i<$count; $i++){
			// previous one
			$previ = $i - 1;	
			if($ph == $res[$i]['id']){
				$temp_file = $res[$i]['files'][0]['file_name'];
				$temp_icon = $res[$i]['files'][0]['icon_file_name'];
		    	$res[$previ]['files'][] = array('file_name' => $temp_file, 'icon_file_name' => $temp_icon); 
				// remove duplicate
				$ph = $res[$i]['id'];
				unset($res[$i]);
				// reset counter 
				$res = array_values($res);
				// check if the counter is up
				$new_count = count($res);
				if($new_count == $i){
					break;
				}
				
			} else {
			// Resets the checker for the next iteration
			$ph = $res[$i]['id'];
			}	
		}

			
		return $res;

	}
	 */	

	
}

?>