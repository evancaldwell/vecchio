<?php
class Shipping_model extends CI_Model{

	var $ship_per_day;
	function __construct(){
		
		parent::__construct();
		// get the reference values (price_per_mile, heavy_item_price);
		

	} 
	

	function get_shipping_cost($order_id, $zipfrom = 93292, $zipto, $heavy = false )
	{
		
		// get status of order 
		
		$this->db->select('status, code, will_call');
		$this->db->where('id', $order_id);
		$resu = $this->db->get('orders');
		$resa = $resu->result_array();
		$status = $resa[0]['status'];
		$discount = $this->check_dc($resa[0]['code']);
		
		// if the status of an order is not pending, grab the info from the database. 
		
		if($status != 1){
		$this->db->select('from_city, from_state, to_city, to_state, miles, buffer_miles, total_gy, cost_per_mile, mileage_cost, heavy, trucks_each_gy, actual_trucks, charge_trucks, total_cost_cust, total_cost_vecchio, order_total, grand_total, total_actual_trucks, discount_percent, box_price');
		$this->db->where('order_id', $order_id);
		$result = $this->db->get('shipping');
		$resultarr = $result->result_array();
		$ret = $resultarr[0];
		return $ret;
		} else {
		
		
		/* get price per mile	
		$this->db->select('ref_value');
		$this->db->where('ref_name','price_per_mile');
		$query = $this->db->get('ref');
		$prelim = $query->result_array();
		$price_per_mile = $prelim[0]['ref_value'];
		*/
		$this->load->model('User_model');
		$vec = $this->User_model->vecchio_settings();
		$price_per_mile = $vec['price_per_mile'];
		$buffer_miles = $vec['buffer_miles'];
		// get heavy item cost;
		$heavy_item_cost = "";
		

		
		// calculate how many trucks - based on order items
		$this->db->select('product_type.trees_to_truck,  order_items.product_id, order_items.cust_cost, order_items.order_id, products.id, products.product_type_id, product_type.id, products.grow_yard_id');
		$this->db->from('order_items');
		$this->db->join('products', ' order_items.product_id = products.id', 'left');
		$this->db->join('product_type', 'products.product_type_id = product_type.id', 'left');
		$this->db->where('order_items.order_id', $order_id);
		$this->db->order_by('products.grow_yard_id');
		$query = $this->db->get();

		$res_orders = $query->result_array();
		
		if(!empty($res_orders)){
			
			$trucks = 0;
			$templevel=0;   

			  $newkey=0;

			  $grouparr[$templevel]="";

			  foreach ($res_orders as $key => $val) {
			   if ($templevel==$val['grow_yard_id']){
			     $grouparr[$templevel][$newkey]=$val;
			   } else {
			     $grouparr[$val['grow_yard_id']][$newkey]=$val;
			   }
			     $newkey++;       
			  }
			array_shift($grouparr);


			// figure out actual trucks needed
			$actual = 0;
			$per_gy = 0;
			$frac_item = 0;
			$gy_frac = 0;
			$total_gy = 0;
			$order_total = 0;
			foreach($grouparr as $gw){


				foreach($gw as $item){
					// figure out the actual
					$frac_item = (1 / $item['trees_to_truck']); 
					$gy_frac += $frac_item; // total for grow yard
					$actual += $frac_item;  // total for entire order
					$order_total += $item['cust_cost'];
				}
				// add one truck per grow yard
				$per_gy++;
				$total_gy++;
				// if the total number of trucks is more than one, take one away 
				// (because one was already added to this grow yard by default)
				// so you account for the truck already there plus whatever else is needed 
				if($gy_frac > 1){
					$was_left = ($gy_frac - 1);
					$per_gy += $was_left;
				}
				$gy_frac = 0;

			}

			// round up the amout of trucks
			$ceil_trucks = ceil($per_gy);
			$ceil_actual = ceil($actual);
			

		
		if($resa[0]['will_call'] == 1){
			
			// customer pickup. No freight cost
			
			$miles = 0;
			$buffer_miles = 0;
			$total_gy = 0;
		    $price_per_mile = 0;
			$mileage_cost = 0;
			$from_city = 'Grow Yard' ;
			$from_state = '';
			$to_city = 'Customer Pickup';
			$to_state = '';
			$total_cost = 0;
			$vecchio_cost = 0;
			
		} else {
			
			// get the lat lon for from zip code
			$this->db->select('city, state, lat, lon');
			$this->db->where('zip', trim($zipfrom));
			$query = $this->db->get('zcta');
			$resultfrom = $query->result_array();
			$lat1 = $resultfrom[0]['lat'];
			$lng1 = $resultfrom[0]['lon'];
			$from_city = $resultfrom[0]['city'] ;
			$from_state = $resultfrom[0]['state'];


			// get the lat lon for 'to' zip code
			$this->db->select('city, state, lat, lon');
			$this->db->where('zip', trim($zipto));
			$query = $this->db->get('zcta');
			$resultto = $query->result_array();
			$lat2 = $resultto[0]['lat'];
			$lng2 = $resultto[0]['lon'];
			$to_city = $resultto[0]['city'];
			$to_state = $resultto[0]['state'];			
			
			
			// calculate distance
			$pi80 = M_PI / 180;
			$lat1 *= $pi80;
			$lng1 *= $pi80;
			$lat2 *= $pi80;
			$lng2 *= $pi80;

			$r = 6372.797; // mean radius of Earth in km
			$dlat = $lat2 - $lat1;
			$dlng = $lng2 - $lng1;
			$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			$miles = round((($r * $c) * 0.621371192),2);
		
			// add on the buffer miles
			$miles += $buffer_miles;
		
			// calculate the milage cost
			$mileage_cost = round(($miles * $price_per_mile) , 2 );

			// if there is a heavy item, include the heavy cost
			if($heavy == true){ $mileage_cost += $heavy_item_cost; }

			$total_cost = $ceil_trucks * $mileage_cost;
			$vecchio_cost = $ceil_actual * $mileage_cost;
		
		} // end if will_call 
		
		
		// box price 
		
		$box = "SELECT SUM(box.price) as box_price
		FROM products, product_type, box, orders, order_items
		WHERE products.product_type_id = product_type.id
		AND box.size = product_type.box_size
		AND order_items.product_id = products.id
		AND orders.id = order_items.order_id
		AND orders.boxed = 1
		AND orders.id = '$order_id'
		GROUP BY orders.id ";
		
		$box_q = $this->db->query($box);
		$box_r = $box_q->result_array();
		
		$box_price = (!empty($box_r) ? number_format($box_r[0]['box_price'], 2, '.', ',') : '0.00');
		
		$order_total = $order_total + $box_price;
		
		// check discount
		
		if($discount != 0){
			$grand_total = $total_cost + ($order_total - ($order_total * $discount));
		} else {
			$grand_total = $total_cost + $order_total;	
		}
		


		
		// return the results as an array of info
		$ret = array(
			'from_city' => $from_city,
			'from_state' => $from_state,
			'to_city' => $to_city,
			'to_state' => $to_state,
			'miles' => $miles,
			'buffer_miles' => $buffer_miles,
			'total_gy' => $total_gy,
		    'cost_per_mile' => $price_per_mile,
			'mileage_cost' => $mileage_cost,
			'heavy' => ($heavy) ? $heavy_item_cost : 0,
		    'trucks_each_gy' => $per_gy,
		    'actual_trucks' => $actual,
		    'total_actual_trucks' => $ceil_actual, 
			'charge_trucks' => $ceil_trucks,
			'total_cost_cust' => $total_cost,
			'total_cost_vecchio' => $vecchio_cost,
			'order_total' => $order_total,
			'discount_percent' => $discount,
			'grand_total' => $grand_total,
			'box_price' => $box_price
		);
		
		// store these values in database
		$this->db->where('order_id', $order_id);
		$this->db->update('shipping', $ret);
		
		return $ret;
	} // end check if any orders items in order. 
	
	} // end check status of order , if not in tagged status get the info from the database. 
	
	}
	
	function check_date_aval($date, $grow_yard_id){
	    // AND orders.grow_yard_id = '$grow_yard_id'
     	// orders.grow_yard_id,
		$q = "SELECT ship_date, count(*) AS cn 
		      FROM shipping
			  WHERE ship_date = '$date'";
		//	AND grow_yard_id = '$grow_yard_id' 
		$obj = $this->db->query($q);
		$result = $obj->result_array();
	    return $result;
	}
	
	function get_latlon($grow_yard){
		$q = "SELECT lat, lon FROM grow_yards WHERE id = '$grow_yard'";
		$result = $this->db->query($q);
		$arr = $result->result_array();
		return $arr;
	}
	
	function get_shipping_info($shipping_id){
		$q = "SELECT * FROM shipping WHERE id=  '$shipping_id'";
		$result = $this->db->query($q);
		$arr = $result->result_array();
		$order_id = $arr[0]['order_id'];
		$this->db->select('will_call');
		$this->db->where('id', $order_id);
		$q2 = $this->db->get('orders');
		$res2 = $q2->result_array();
		$arr[0]['will_call'] = $res2[0]['will_call'];
		return $arr;
	}
	
	function get_shipping_info_bo($order_id){
		$q = "SELECT * FROM shipping WHERE order_id = '$order_id'";
		$result = $this->db->query($q);
		$arr = $result->result_array();
		$this->db->select('will_call');
		$this->db->where('id', $order_id);
		$q2 = $this->db->get('orders');
		$res2 = $q2->result_array();
		$arr[0]['will_call'] = $res2[0]['will_call'];
		return $arr;
	}
	
	
	function get_shipping_info_qq($qq_id){
		$q = "SELECT * FROM qq_shipping WHERE qq_id =  '$qq_id'";
		$result = $this->db->query($q);
		$arr = $result->result_array();
		
		$this->db->select('will_call');
		$this->db->where('id', $qq_id);
		$q2 = $this->db->get('quick_quote');
		$res2 = $q2->result_array();
		$arr[0]['will_call'] = $res2[0]['will_call'];
		return $arr;
	}
	
	function check_zip($zip){
		$this->db->where('zip', $zip);
		$this->db->from('zcta');
		$count = $this->db->count_all_results();
		return $count;
	}
	
	function order_breakdown($order_id){
		$q = "SELECT count(product_type.id) as num_products, product_type.product_code
		FROM product_type, order_items, products
		WHERE order_items.product_id = products.id
		AND product_type.id = products.product_type_id
		AND order_items.order_id =  '$order_id'
		GROUP BY product_type.id";
		
		$result = $this->db->query($q);
		return $result->result_array();

	}
	
	function qq_order_breakdown($qq_id){
		$q = "SELECT quick_quote_items.quantity as num_products, product_type.product_code
			  FROM product_type, quick_quote_items
			  WHERE product_type.id = quick_quote_items.product_type_id
			  AND quick_quote_items.qq_id = '$qq_id'";
			$result = $this->db->query($q);
			return $result->result_array();	
	}
	
	function get_calendar_ship($checkdate = true){
		$q = "SELECT orders.order_name, orders.id AS order_id, orders.status, shipping.location, shipping.location_phone, shipping.ship_address, shipping.ship_state, shipping.ship_city, shipping.ship_zip, shipping.ship_date, users.fname, users.lname, users.company_name, rep.fname AS rep_fname, rep.lname AS rep_lname
		FROM orders, shipping, users
		LEFT JOIN users AS rep ON (users.rep_id = users.id)
		WHERE orders.customer_id = users.id
		AND shipping.order_id = orders.id 
		AND (orders.status = 2 OR orders.status = 3 OR orders.status = 4)";
		if($checkdate){
		$q .= " AND shipping.ship_date != '0000-00-00'";	
		}
		$result = $this->db->query($q);
		$res = $result->result_array();
		$count = count($res);
		for($i=0; $i<$count; $i++){
		$res[$i]['freight'] = $this->get_shipping_cost($res[$i]['order_id'], 93292, $res[$i]['ship_zip'], false );
		$res[$i]['breakdown'] = $this->order_breakdown($res[$i]['order_id']);
		$res[$i]['type'] = 'order';
		}
		
	
		$qq_res = $this->get_qq_calendar_ship();
		$merge = array_merge($res,$qq_res);
		return $merge;
	}

	function get_qq_calendar_ship(){
		$q = "SELECT CONCAT(quick_quote.id, '-', (SUBSTR(quick_quote.quote_date,1,10))) as order_name , quick_quote.id as qq_id, quick_quote.actual_trucks, qq_shipping.location, qq_shipping.location_phone, qq_shipping.ship_address, qq_shipping.ship_state, qq_shipping.ship_city, qq_shipping.ship_zip, qq_shipping.ship_date, users.fname, users.lname, users.company_name, rep.fname AS rep_fname, rep.lname AS rep_lname
		FROM quick_quote, qq_shipping, users
		LEFT JOIN users AS rep ON (users.rep_id = users.id)
		WHERE quick_quote.customer_id = users.id
		AND qq_shipping.qq_id = quick_quote.id 
		AND (quick_quote.status = 1 OR quick_quote.status = 2)
		AND qq_shipping.ship_date != '0000-00-00'";
		$result = $this->db->query($q);
		$res = $result->result_array();
		$count = count($res);
		for($i=0; $i<$count; $i++){
		$res[$i]['freight']['actual_trucks'] = $res[$i]['actual_trucks'];
		$res[$i]['breakdown'] = $this->qq_order_breakdown($res[$i]['qq_id']);
		$res[$i]['type'] = 'quote';
		}
		
		return $res;
	}
	
	function check_dc($code){
		$code = trim($code);
		if($code != ''){
		$this->db->where('code', $code);
		$this->db->where('active', 1);
		$count = $this->db->count_all_results('promo');
		
		$this->db->where('code', $code);
		$this->db->where('active', 1);
		$result = $this->db->get('promo');
		$result_arr = $result->result_array();
			if($count == 0){
			return 0;
			} else {
			return $result_arr[0]['disc_perc'];
			}
		} else {
			return 0;
		}
	}
	
	
	function get_quick_ship($zipto, $zipfrom = 93292, $pr, $will_call = 0){
		
		// get the fraction of truck space for each product type multiplied by the number of that product type

	$total_trucks = 0;	
		
	foreach($pr as $key => $value){
		// reset to zero 
		$tot = 0;
		$frac = 0;
		$prtotal = 0;
		
		$q = "SELECT trees_to_truck FROM product_type WHERE id = '$key'";
		$r = $this->db->query($q);
		$row = $r->result_array();
		$tot = $row[0]['trees_to_truck'];
		if($tot != 0){
			$frac = (1/$tot);
			$prtotal = ($frac * $value);
			$total_trucks += $prtotal;
		}
		
	}
	
	$ceil_trucks = ceil($total_trucks);
	
	if($will_call == 0){
	
		$this->load->model('User_model');
		$vec = $this->User_model->vecchio_settings();
		$price_per_mile = $vec['price_per_mile'];
		$buffer_miles = $vec['buffer_miles'];
		// get heavy item cost;
		$heavy_item_cost = "";
	
		// get the lat lon for from zip code
		$this->db->select('city, state, lat, lon');
		$this->db->where('zip', trim($zipfrom));
		$query = $this->db->get('zcta');
		$resultfrom = $query->result_array();
		$lat1 = $resultfrom[0]['lat'];
		$lng1 = $resultfrom[0]['lon'];
	
		// get the lat lon for 'to' zip code
		$this->db->select('city, state, lat, lon');
		$this->db->where('zip', trim($zipto));
		$query = $this->db->get('zcta');
		$resultto = $query->result_array();
		$lat2 = $resultto[0]['lat'];
		$lng2 = $resultto[0]['lon'];
		$tocity = $resultto[0]['city'];
		$tostate = $resultto[0]['state'];
	
		// get the distance
		// calculate distance
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$lng1 *= $pi80;
		$lat2 *= $pi80;
		$lng2 *= $pi80;

		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$miles = round((($r * $c) * 0.621371192),2);
	
		// add on the buffer miles
		$miles += $buffer_miles;
	
		// calculate the milage cost
		$mileage_cost = round(($miles * $price_per_mile) , 2 );
	
		$total_cost = $ceil_trucks * $mileage_cost;
	
	} else {
		
		// will call / customer pickup. 
		$miles = 0;
		$total_cost = '0.00';
		$tocity = '';
		$tostate = '';
		
	}
	
	$data = array(
		'distance' => $miles,
		'actual_trucks' => $total_trucks,
		'trucks' => $ceil_trucks,
		'ship_cost' => $total_cost,
		'city' => $tocity,
		'state' => $tostate
	);
	
	return $data;
		
	}
	

}

?>