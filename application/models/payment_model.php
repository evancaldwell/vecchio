<?php
class Payment_model extends CI_Model{
	
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


	function enter_payment_qq($qq_id, $new_payment, $freight){
	
		$e_sig = '';
		$status = '';
		$location = '';
		$location_phone = '';
		$ship_zip = '';
		$past_payments = 0;
		$notice = "Default Notification ";
		$fname = $this->session->userdata('fname');
		$lname = $this->session->userdata('lname');
		$who_enter = strtoupper($fname[0]) . strtoupper($lname[0]);
		$this->load->model('email_model');
		$return = array('status' => 'fail', 'response' => 'Unknown Error' );
	
		// get current quote status, e_sig
		$this->db->select('e_sig, status');
		$this->db->where('id', $qq_id);
		$q = $this->db->get('quick_quote');
		$res = $q->result_array();
		if(!empty($res)){
			$e_sig = $res[0]['e_sig'];
			$status = $res[0]['status'];
		}
	
		// get current shipment info 
		$this->db->select('location, location_phone, ship_zip');
		$this->db->where('qq_id', $qq_id);
		$q_s = $this->db->get('qq_shipping');
		$res_s = $q_s->result_array();
		if(!empty($res_s)){
			$location = $res_s[0]['location'];
			$location_phone = $res_s[0]['location_phone'];
			$ship_zip = $res_s[0]['ship_zip'];
		}
	
	
		// get past payments
		$this->db->select('amount');
		$this->db->where('qq_id', $qq_id);
		$q_p = $this->db->get('qq_payments');
		$res_p = $q_p->result_array();
		if(!empty($res_p)){
			foreach($res_p as $pay_item){
				$past_payments += $pay_item['amount'];
			}
		}
	
		// start checks
		
		if(count($res_p) == 2){
		// deposit and balance have been paid. 
		$return = array('status' => 'fail', 'response' => 'Deposit and Balance have been paid' );
		return $return;
		break;			
		}
		

	
		if(strlen($e_sig) < 5 ){
			$return = array('status' => 'fail', 'response' => 'E-Signature Not Signed' );
			return $return;
			break;
		}
		if($location == '' || $location_phone == '' ){
			$return = array('status' => 'fail', 'response' => 'Shipping Information Not Entered' );
			return $return;
			break;		
		}
	
		// get quote total 
		$this->load->model('tracking_model');
		$q = $this->tracking_model->get_quick_quote_items($qq_id);
		
		if(($new_payment + $past_payments) > $q[0]['grand_total']){
		// new payment would be more than the order total.  
		$return = array('status' => 'fail', 'response' => 'New Payment + Previous Payments exceeds Quote Total' );
		return $return;
		break;			
		}
	
		// check payment is at least half of total
		$start = $q[0]['grand_total'];
		$half = $start / 2;
		
		
	
		if($new_payment < ($half - 1)){ // allow one dollar off for rounding issues with second deposit. 
			$return = array('status' => 'fail', 'response' => 'Payment Must equal at least half of total to be accepted' );
			return $return;
			break;
		}
	
		$transaction_id = $this->input->post('transaction_id');
		$enter_payment = $this->input->post('payment_date');

		$method = $this->input->post('method');
		
		if($transaction_id == '' || $enter_payment == ''){
			$return = array('status' => 'fail', 'response' => 'Missing either transaction id, or payment date' );
			return $return;
			break;			
		}
		
		$payment_date = date("Y-m-d H:i:s", strtotime($enter_payment));
		
		$data = array(
			'qq_id' => $qq_id,
			'amount' => $new_payment,
			'freight' => $freight,
			'transaction_id' => $transaction_id,
			'method' => $method,
			'payment_date' => $payment_date,
			'who_enter' => $who_enter
			);
		$this->db->insert('qq_payments',$data);
	
		// check current status - move them up if they have made a deposit
		if($status == 0 || $status == 6){ // pending reg or check by fax
			
			$data = array('status' => $status); // current or default
			
			if($q[0]['grand_total'] == ($new_payment + $past_payments)){
				// full payment - 
				$data = array('status' => 2); // Paid / Shippig Backlog
				$notice = "Full Payment recorded for Quote. Quote moved to shipping backlog, email sent to user to choose ship date  ";
			} else {
				$data = array('status' => 1, 'deposit' => $new_payment); // On Account / Shipping Backlog - DEPOSIT
				$notice = "Deposit recorded for quote. Quote moved to shipping backlog, email sent to user to choose ship date. ";
			}
			
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $data);
			
			$this->email_model->send_email_rec_qq($qq_id, true);
			
				
		} else if($status == 1 ){ // currently on account / shipping backlog
			
			$data = array('status' => $status); // current or default
			
			if($q[0]['grand_total'] == ($new_payment + $past_payments)){
				$data = array('status' => 2); // Paid / Shipping Backlog
				$notice = "Full Payment recorded for Quote. Moved to Paid / Shipping Backlog ";
			} else { // they have not paid, so this is a deposit.
				$data = array('deposit' => $new_payment);
				$notice = "Deposit recorded for Quote. ";
			}
			
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $data);
			
			// email client to confirm payment

		 	$this->email_model->send_email_rec_qq($qq_id, false);				
			
		} else if($status == 4){ // On Account / Shipped
			$data = array('status' => $status); // current or default
			
			if($q[0]['grand_total'] == ($new_payment + $past_payments)){
				$data = array('status' => 3); // Paid / Shipped
				$notice = "Full Payment recorded for Quote. Moved to Paid / Shipped";
			} else { // they have not paid, so this is a deposit.
				$data = array('deposit' => $new_payment);
				$notice = "Deposit recorded for Quote. ";
			}
			
			$this->db->where('id', $qq_id);
			$this->db->update('quick_quote', $data);
			
			// email client to confirm payment
	
			$this->email_model->send_email_rec_qq($qq_id, false);	

		}
		
		$amount_owed =  number_format(($q[0]['grand_total'] - ($new_payment + $past_payments)), 2, '.', '');
 		
		$notice .= "<br /> Grand Total: $".$q[0]['grand_total']."<br /> New Payment: $".$new_payment." <br /> Amount Remaining: $" . $amount_owed;
		$notice .= "<br /> Payment Entered By: " . $fname . " " . $lname ;

		$this->email_model->send_admin_rec_qq($qq_id);
		$this->email_model->send_rep_notice($qq_id, $notice, 'quote');
			
			$return = array('status' => 'success', 'response' => 'Payment Accepted.' . ($amount_owed > 0 ? '$ ' . $amount_owed . ' Remaining' : ' Payment Complete ') );
			return $return;
		

	}
	
	function enter_payment($order_id, $new_payment, $freight){
	
		$e_sig = '';
		$status = '';
		$location = '';
		$location_phone = '';
		$ship_zip = '';
		$past_payments = 0;
		$fname = $this->session->userdata('fname');
		$lname = $this->session->userdata('lname');
		$who_enter = strtoupper($fname[0]) . strtoupper($lname[0]);
		
		$notice = "Default Notice";
		$this->load->model('email_model');
		$return = array('status' => 'fail', 'response' => 'Unknown Error' );
	
		// get current quote status, e_sig
		$this->db->select('e_sig, status');
		$this->db->where('id', $order_id);
		$q = $this->db->get('orders');
		$res = $q->result_array();
		if(!empty($res)){
			$e_sig = $res[0]['e_sig'];
			$status = $res[0]['status'];
		}
	
		// get current shipment info 
		$this->db->select('location, location_phone, ship_zip');  // ORDERS
		$this->db->where('order_id', $order_id);
		$q_s = $this->db->get('shipping');
		$res_s = $q_s->result_array();
		if(!empty($res_s)){
			$location = $res_s[0]['location'];
			$location_phone = $res_s[0]['location_phone'];
			$ship_zip = $res_s[0]['ship_zip'];
		}
	
	
		// get past payments
		$this->db->select('amount');
		$this->db->where('order_id', $order_id);
		$q_p = $this->db->get('payments');
		$res_p = $q_p->result_array();
		if(!empty($res_p)){
			foreach($res_p as $pay_item){
				$past_payments += $pay_item['amount'];
			}
		}
	
		// start checks  // ORDERS
		
		if(count($res_p) == 2){ // Max 2 payments on each order (deposit and balance). It has been reached
		// deposit and balance have been paid. 
		$return = array('status' => 'fail', 'response' => 'Deposit and Balance have been paid' );
		return $return;
		break;			
		}
		
		// get quote total 
		$grand_total = $this->get_grand_total($order_id);  // ORDERS
		// check payment is at least half of total
		$half = $grand_total / 2;
		
		if(($new_payment + $past_payments) > $grand_total){
		// new payment would be more than the order total.  
		$return = array('status' => 'fail', 'response' => 'New Payment + Previous Payments exceeds Order Total' );
		return $return;
		break;			
		}
	
		if(strlen($e_sig) < 5 ){
			$return = array('status' => 'fail', 'response' => 'E-Signature Not Signed' );
			return $return;
			break;
		}
		if($location == '' || $location_phone == '' ){
			$return = array('status' => 'fail', 'response' => 'Shipping Information Not Entered' );
			return $return;
			break;		
		}
	
		
	
		if($new_payment < ($half - 1)){ // allow one dollar off for rounding issues with second deposit. 
			$return = array('status' => 'fail', 'response' => 'Payment Must equal at least half of total to be accepted' );
			return $return;
			break;
		}
	
		$transaction_id = $this->input->post('transaction_id');
		$enter_payment = $this->input->post('payment_date');

		$method = $this->input->post('method');
		
		if($transaction_id == '' || $enter_payment == ''){
			$return = array('status' => 'fail', 'response' => 'Missing either transaction id, or payment date' );
			return $return;
			break;			
		}
		
		$payment_date = date("Y-m-d H:i:s", strtotime($enter_payment));
	
		
		$data = array(
			'order_id' => $order_id,
			'amount' => $new_payment,
			'freight' => $freight,
			'transaction_id' => $transaction_id,
			'method' => $method,
			'payment_date' => $payment_date,
			'who_enter' => $who_enter
			);
		$this->db->insert('payments',$data);
	
		// check current status - move them up if they have made a deposit
		if($status == 1 || $status == 6){ // reg tagged or check by fax
			
			$data = array('status' => $status); // current or default
			
			if($grand_total == ($new_payment + $past_payments)){
				// full payment - 
				$data = array('status' => 2); // Paid / Shipping Backlog
				$notice = "Full Payment recorded for Order. Quote moved to shipping backlog, email sent to user to choose ship date. ";
			} else {
				
				$data = array('status' => 4, 'deposit' => $new_payment); // On Account / Shipping Backlog - DEPOSIT
				$notice = "Deposit recorded for Order. Quote moved to shipping backlog, email sent to user to choose ship date.  ";
				
			}
			
			$this->db->where('id', $order_id);
			$this->db->update('orders', $data);
			
			// email client to choose shipping date

			 $this->email_model->send_email_rec($order_id, true);
			
				
		} else if($status == 4){ // currently on account / shipping backlog
			
			$data = array('status' => $status); // current or default
			
			if($grand_total == ($new_payment + $past_payments)){ // paid up / green light for shipping backlog
				$data = array('status' => 2); // Paid / Shipping Backlog
				$notice = "Full Payment recorded for Order. Moved to Paid / Shipping Backlog ";
			} else { // this is a deposit
					$data = array('deposit' => $new_payment); 
				$notice = "Deposit recorded for Order. ";
			}
			
			$this->db->where('id', $order_id);
			$this->db->update('orders', $data);
			
			// email client to confirm payment
		
			$this->email_model->send_email_rec($order_id, false);				
			
		} else if($status == 5){ // currently on account / shipped
			
				$data = array('status' => $status); // current or default

				if($grand_total == ($new_payment + $past_payments)){ // all paid up
					$data = array('status' => 3); // Paid / Shipped
					$notice = "Full Payment recorded for Order. Moved to Paid / Shipped. ";
				} else { // this is a deposit
					$data = array('deposit' => $new_payment); 
					$notice = "Deposit recorded for Order. Moved to Paid / Shipped. ";
				}

				$this->db->where('id', $order_id);
				$this->db->update('orders', $data);

			
			 $this->email_model->send_email_rec($order_id, false);
			
			
		}
	
			$amount_owed =  number_format(($grand_total - ($new_payment + $past_payments)), 2, '.', '');	
			$notice .= "<br /> Grand Total: $".$grand_total."<br /> New Payment: $".$new_payment." <br /> Amount Remaining: $" . $amount_owed;
		    $notice .= "<br /> Payment Entered By: " . $fname . " " . $lname ;

			$this->email_model->send_admin_rec($order_id);
			$this->email_model->send_rep_notice($order_id, $notice, 'order');
			
			$return = array('status' => 'success', 'response' => 'Payment Accepted.' . ($amount_owed > 0 ? '$ ' . $amount_owed . ' Remaining' : ' Payment Complete ') );
			return $return;
		

	}
	
	function get_payment_half($grand_total, $freight){
		
		$data['order_t'] = number_format($grand_total, 2, '.', '');
		$data['half'] = ($data['order_t'] / 2);
		$data['order_total'] = "Total: " .  "$". $data['order_t']. "\n";
		$data['half_deposit'] = number_format($data['half'], 2, '.', '');
		$data['half_balance'] = number_format(($data['order_t'] - $data['half_deposit']), 2, '.', '');
		
		// split up freight to be entered in as half
		$ship_cost = number_format($freight, 2, '.', '');
		$half_s = ($ship_cost / 2);
		$ship_total = "Freight: " .  "$". $ship_cost. "\n";
		$half_ship1 = number_format($half_s, 2, '.', '');
		$half_ship2 = number_format(($ship_cost - $half_ship1) , 2, '.', '');
		$data['ship_total'] = $ship_total;
		$data['half_ship1'] = $half_ship1;
		$data['half_ship2'] = $half_ship2;
		
		return $data;
		
	}
	
	function check_payment_ready_qq($location, $location_phone, $ship_zip, $e_sig, $status){
		$payment_ready = false;
		$payment_reason = '';
		$no_location = true;
		$no_sig = true;
		if(($status == 0 || $status == 6)){
			
			if($location == '' || $location_phone == ''  ){
				$payment_ready = false;
				$payment_reason = 'User has not entered freight freight.';
				$no_location = true;
			} else {
				$no_location = false;
			}
			
			if($e_sig == ''){
				$payment_ready = false;
				$payment_reason = 'User has not signed E-Signature.';
				$no_sig = true;				
			} else {
				$no_sig = false;	
			}
			
			if($no_sig && $no_location ){
				$payment_ready = false;
				$payment_reason = 'User has not signed E-Signature or entered Freight information';
			} else if(!$no_sig && !$no_location) {
				// we are set. 
				$payment_ready = TRUE;
			}
			
		} else if($status == 1 || $status = 4) { // on account - shipping backlog or shipped. They have either placed on account, or 
				$payment_ready = TRUE;
		} else if($status == 2 || $status == 3){
				$payment_ready = FALSE;
				$payment_reason = 'Payment complete';
		}
	
		$return = array(
					'payment_ready' => $payment_ready,
					'payment_reason' => $payment_reason
					);
					
		return $return;			
		
	}
	
	function check_payment_ready($location, $location_phone, $ship_zip, $e_sig, $status){
		$payment_ready = false;
		$payment_reason = '';
		$no_location = true;
		$no_sig = true;
		if(($status == 1 || $status == 6)){ // Tagged for 24 hours or Pending Check by Fax
			
			if($location == '' || $location_phone == ''  ){
				$payment_ready = false;
				$payment_reason = 'User has not entered freight location.';
				$no_location = true;
			} else {
				$no_location = false;
			}
			
			if($e_sig == ''){
				$payment_ready = false;
				$payment_reason = 'User has not signed E-Signature';
				$no_sig = true;				
			} else {
				$no_sig = false;	
			}
			
			if($no_sig && $no_location ){
				$payment_ready = false;
				$payment_reason = 'User has not signed E-Signature or entered Freight location';
			} else if(!$no_sig && !$no_location) {
					// we are set. 
					$payment_ready = TRUE;
			}
			
		} else if($status == 4 || $status = 5) { // on account shipping backlog or on account shipped
				$payment_ready = TRUE;
		} else if($status == 2 || $status == 3){
				$payment_ready = FALSE;
				$payment_reason = 'Payment complete';
		}
	
		$return = array(
					'payment_ready' => $payment_ready,
					'payment_reason' => $payment_reason
					);
					
		return $return;			
		
	}
	
	
	function get_amount_owed($grand_total, $id, $type){
		
		$past_payments = 0;
			// get past payments
		if($type == 'quick_quote'){
			
			$this->db->select('amount');
			$this->db->where('qq_id', $id);
			$q_p = $this->db->get('qq_payments');
			$res_p = $q_p->result_array();
			if(!empty($res_p)){
				foreach($res_p as $pay_item){
					$past_payments += $pay_item['amount'];
				}
			}
			
		} else if($type == 'order'){
		
			$this->db->select('amount');
			$this->db->where('order_id', $id);
			$q_p = $this->db->get('payments');
			$res_p = $q_p->result_array();
			if(!empty($res_p)){
				foreach($res_p as $pay_item){
					$past_payments += $pay_item['amount'];
				}
			}
			
		}
			
		$amount_owed = ($grand_total - $past_payments);
			
		return $amount_owed;		
		
	}
	
	function get_grand_total($order_id){
		// check total of individual order
		$this_order = "SELECT orders.id, (shipping.total_cost_cust + SUM(order_items.cust_cost * order_items.quantity)) as o_total
		FROM orders, order_items, shipping
		WHERE orders.id = order_items.order_id
		AND shipping.order_id = orders.id
		AND orders.id = '$order_id'
		GROUP BY order_items.order_id ";
		
		$order_q = $this->db->query($this_order);
		$o_r = $order_q->result_array();
		
		return $o_r[0]['o_total'];
		
	}
	
	function get_grand_total_qq($qq_id){
		$this_quote = "SELECT ( 
		quick_quote.ship_cost + SUM( quick_quote.locked_multiplier * quick_quote_items.quantity * quick_quote_items.locked_price )
		+ quick_quote.box_price ) - quick_quote.disc AS qq_total
		FROM quick_quote, quick_quote_items
		WHERE quick_quote.id = quick_quote_items.qq_id
		AND quick_quote.id = '$qq_id'
		GROUP BY quick_quote_items.qq_id";
	
		$q_total = $this->db->query($this_quote);
		$q_t = $q_total->result_array();

 		return $q_t[0]['qq_total'];
	}
	
	function get_transaction_text($order_id, $type = 'html'){
		if($type == 'html'){
			$br = '<br />';
		} else {
			$br = "\n";
		}
		
		$this->load->model('tracking_model');
		$qo = $this->tracking_model->get_transaction_info($order_id);
		$txtt = '';
		if($qo['pay_items'][0]['amount'] != ''){
			$txtt .= "Transaction Information" .$br;

			$count_p = count($qo['pay_items']);
			for($i=0;$i<$count_p;$i++){
		
			$txtt .= "Transaction ID: ".$qo['pay_items'][$i]['transaction_id'].$br;
			$txtt .= "Freight $".number_format($qo['pay_items'][$i]['freight'], 2, '.', ','). $br;
			$txtt .= "Total: $".number_format($qo['pay_items'][$i]['amount'], 2, '.', ',').$br;
			$txtt .= "Pay Method: ".$qo['pay_items'][$i]['method']."\n";
			$txtt .= "Payment Date: ".date("l F j, Y - g:i a", strtotime($qo['pay_items'][$i]['payment_date'])).$br . $br;;	
		
			}
			if($count_p > 1){
			$txtt .= " Grand Total: $".number_format($qo['pay_total'], 2, '.', ',').$br.$br.$br;
			}
		}
		
		return $txtt;
		
	}
	
	function get_transaction_text_qq($qq_id, $type = 'html'){
		if($type == 'html'){
			$br = '<br />';
		} else {
			$br = "\n";
		}
		
		$this->load->model('tracking_model');
		$qo = $this->tracking_model->get_qq_order_receipt($qq_id);
		
		$txtt = '';
		if($qo['pay_items'][0]['amount'] != ''){
			$txtt .= "Transaction Information" .$br;

			$count_p = count($qo['pay_items']);
			for($i=0;$i<$count_p;$i++){
		
			$txtt .= "Transaction ID: ".$qo['pay_items'][$i]['transaction_id'].$br;
			$txtt .= "Freight $".number_format($qo['pay_items'][$i]['freight'], 2, '.', ','). $br;
			$txtt .= "Total: $".number_format($qo['pay_items'][$i]['amount'], 2, '.', ',').$br;
			$txtt .= "Pay Method: ".$qo['pay_items'][$i]['method']."\n";
			$txtt .= "Payment Date: ".date("l F j, Y", strtotime($qo['pay_items'][$i]['payment_date'])).$br . $br;;	
		
			}
			if($count_p > 1){
			$txtt .= " Grand Total: $".number_format($qo['pay_total'], 2, '.', ',').$br.$br.$br;
			}
		}
		
		return $txtt;
		
	}
	
	function get_status_text($status){
		$return = '';
		
		switch($status){
			case 1: 
			$return = "Pending -Tagged Order ";
			break;
			case 2: 
			$return = "Paid &amp; Shipping Backlog ";
			break;
			case 3: 
			$return = "Paid &amp; Shipped ";
			break;
			case 4: 
			$return = "On Account &amp; Shipping Backlog ";
			break;
			case 5: 
			$return = "On Account &amp; Shipped ";
			break;
			case 6: 
			$return = "Pending - Check By Fax ";
			break;			
		}
		
		return $return;
		
	} 
	
	
	
	
}