<?php
class Email_model extends CI_Model{

	var $sender_name;
	var $sender_email;


	function __construct(){
		
		parent::__construct();
		$this->sender_email = "noreply@vecchiotrees.com";
		$this->sender_name = "Vecchio Trees";
		

	} 

	function put_html_email_together($content){
	$email_top = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><title>Vecchio Trees</title></head><body><div style=\"width:800px; margin-left:auto; margin-right:auto; border: 1px solid #cccccc;\"><img src=\"http://www.vecchiotrees.com/_images/vc_email_top_s1.jpg\" alt = \"Vecchio Trees\" />";
			$email_bot = "<img src=\"http://www.vecchiotrees.com/_images/vc_email_footer_s1.jpg\" alt = \"Vecchio Trees T: 855/819-7777 F: 855/828-6708  - 4285 Spyres Way,  Modesto, CA 95356\" /></div></body></html>";
		$email_mid = "";
		foreach($content as $p){
			$email_mid .= "<p style=\"font-family:Geneva; padding:0px 35px 10px 35px; margin:0px; line-height:1.7em; font-size:12px\">" . $p . "</p>";
		}
				
		$whole = $email_top . $email_mid . $email_bot;
		return $whole;
	}
	
	function put_text_email_together($content){
		$email_top = "------------------VECCHIO TREES------------\r\n\r\n";
		$email_mid = "";
		foreach($content as $p){
			$email_mid .=  $p . "\r\n\r\n";
		}
		$email_bot = "VECCHIO SMS: http://www.vecchiotrees.com/index.php/vecchio_admin/";
		$whole = $email_top . $email_mid . $email_bot;
		return $whole;		
	}
	
	function send_email_to($to, $subject, $content, $type = 'text'){
		

		$headers = "";
		if($type == "html"){
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$message = $this->put_html_email_together($content);
		} else {
		$message = $this->put_text_email_together($content);	
		}
		
		$headers .= "From: " . $this->sender_name; 
		$headers .= " <".$this->sender_email.">\r\n";
		$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
		$headers .= "Return-Path: ".$this->sender_email;
		
		mail($to, $subject, $message, $headers);
	}
	/*

	*/
	
	function send_admin_new($user){
	
	// if rep is involved - 
	if($user['rep_id'] != NULL || $user['rep_id'] != 0){
		$this->db->select('fname, lname, usern_email');
		$this->db->where('id', $user['rep_id']);
		$query = $this->db->get('users');
		$result = $query->result_array();
		$send_rep_email = true;
		$rep_name = $result[0]['fname'] . " " . $result[0]['lname'];
		$rep_email = $result[0]['usern_email'];
		
	} else {
		$rep_name = "House Account";
		$send_rep_email = false;
	}
		
		
	$m = "";	
	$m .= "NEW USER\r\n\r\n";
	$m .= "First Name: " . $user['fname'] . "\r\n" ; 	
	$m .= "Last Name: " . $user['lname'] . "\r\n" ;
	$m .= "Email: " . $user['usern_email'] . "\r\n" ;
	$m .= "Company Name: " . $user['company_name'] . "\r\n" ;
	$m .= "License Number: " . $user['license_number'] . "\r\n" ;
	$m .= "Address: " . $user['bill_address'] . "\r\n" ;
	$m .= "City: " . $user['bill_city'] . "\r\n" ;
	$m .= "State: " . $user['bill_state'] . "\r\n" ;
	$m .= "Zip: " . $user['bill_zip'] . "\r\n" ;
	$m .= "Phone: " . $user['phone'] . "\r\n" ;
	$m .= "Fax: " . $user['fax'] . "\r\n" ;
	$m .= "Type: " . $user['user_type'] . "\r\n" ;
	$m .= "Multiplier: " . $user['multiplier'] . "\r\n" ;
	$m .= "Account Rep: " . $rep_name . "\r\n" ;
	$m .= "---------------------------------------------\r\n" ;
	
	$top = "------------------VECCHIO TREES------------\r\n\r\n";
	$bot = "Sincerely, Vecchio Website Bot";
		
	$to = "kara@vecchiotrees.com, paul@vecchiotrees.com";
	$body = $top . $m . $bot;
	$subject = "New User - 003";
	
	$headers = "";
	$headers .= "From: " . $this->sender_name; 
	$headers .= " <".$this->sender_email.">\r\n";
	$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
	$headers .= "Return-Path: ".$this->sender_email;
		
	mail($to, $subject, $body, $headers);	
	
	if($send_rep_email){
		
		$to = $rep_email;
		$body = $top . $m . $bot;
		$subject = "New Vecchio Client";

		$headers = "";
		$headers .= "From: " . $this->sender_name; 
		$headers .= " <".$this->sender_email.">\r\n";
		$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
		$headers .= "Return-Path: ".$this->sender_email;
		
		mail($to, $subject, $body, $headers);
		
	}
			
			
	}
	
	function send_email_rec($order_id, $choose_ship = false){
		
		$email_top = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><title>Vecchio Trees</title></head><body><div style=\"width:800px; margin-left:auto; margin-right:auto; border: 1px solid #cccccc;\"><img src=\"http://www.vecchiotrees.com/_images/vc_email_top_s1.jpg\" alt = \"Vecchio Trees\" />";
			$email_bot = "<img src=\"http://www.vecchiotrees.com/_images/vc_email_footer_s1.jpg\" alt = \"Vecchio Trees T: 855/819-7777 F: 855/828-6708  - 4285 Spyres Way,  Modesto, CA 95356\" /></div></body></html>";
		// get all the order details. 
		$this->load->model('tracking_model');
		$o = $this->tracking_model->get_order_images_info($order_id);
		$table = "<table border='0' cellpadding='20' cellspacing='0' width='760'> ";
		// start building the message 

		$m = $table;
		$m .= "<tr><td>";
		$m .= "VECCHIO Trees <br />";
		$m .= "4285 Spyres Way <br />";
		$m .= "Modesto, CA 95356 <br />";
		$m .= "T: 855/819-7777 <br />";
		$m .= "F: 855/828-6708 <br />";
		$m .= "</td></tr>";
		$m .= "<tr><td>";
		$m .= $o['client_info']['company_name']." <br />";
		$m .= $o['client_info']['fname'] . " " . $o['client_info']['lname'] ." <br />";
		$m .= $o['client_info']['bill_address'] . " <br />";
		$m .= $o['client_info']['bill_city'].", ".$o['client_info']['bill_state'] ." ". $o['client_info']['bill_zip'] ." <br />";
		$m .= "T: ".$o['client_info']['phone']." <br />";
		$m .= (!empty($o['client_info']['fax']) ? "F: " . $o['client_info']['fax'] : "");
		$m .= "Email: " . $o['client_info']['usern_email'];
		$m .= "</td></tr>";
		$m .= "<tr><td>";
		$fname =  ucfirst($o['client_info']['fname']);
		$m .= "Dear " .$fname . ",";
		$m .= "<br /><br />";
		$m .= "Thank you for your order with VECCHIO Trees. Our unyielding commitment to quality, service and authenticity has earned Vecchio Trees the trust and loyalty of customers who turn to us first, time and time again. The cultivation of specimen trees is a way of life at Vecchio Trees. By intimately knowing and understanding our client’s vision and needs, we turn vision into reality. Using our creativity, expertise and industry affiliations, Vecchio Trees provides the highest quality products and associated services available. Welcome to the VECCHIO family. <br /> <br />";

		if($choose_ship){
		$anchor = '<a href="https://www.vecchiotrees.com/index.php/dir/log_in/my_account">Here</a>';
		$m .= '<span style="color:#990000">Please log into your Vecchio account to choose a ship date for your order : '.$anchor.'</span><br /><br /> ';
			
		}
		$m .= "Thank You - Vecchio Trees <br /><br />";
		$m .= "In this document you will find the following information: <br /><br />";
		$m .= "- Transaction Confirmation Sheet<br />";
		$m .= "- Order Item Details<br />";
		$m .= "- Warranty Information<br /><br /><br />";
		$m .= "</td></tr></table>";		
	    $m .= "<hr />";
		
		$r = $this->gen_receipt($o, $table, $order_id);

		$m .= $r;
		$m .= "<br /><br />";
		$m .= "<hr />";

		foreach($o['order_items'] as $item){
		$table_b = str_replace("border='0'", "border='1'", $table);
		$table_c =  str_replace("cellpadding='20'", "cellpadding='5'", $table_b);
		$m .= $table_c; 
		$m .= '<tr><td>' . 'Sold to: '.$o['client_info']['company_name'].' - '.$o['client_info']['fname'] . " " . $o['client_info']['lname'] . "</td></tr>";
		$m .= '<tr><td>' .'Product Serial Number: ' . $item['serial_no'] . "</td></tr>";
		$m .= '<tr><td>' .'Product Description: '. $item['description'] . "</td></tr>";
		$m .= '<tr><td>' .'Order Batch: ' . $o['order_name'] . "</td></tr>";
		$image = base_url() . "_fdr/" . $item['files'][0]['file_name'];
		$m .= "<tr><td><img src='" . $image . "' width='760' /></td></tr>";
		$m .= "</table>";
		$m .= "<br />";
		}

		
		$m .= "<hr />";	
		$m .= $table;
		$m .= "<tr><td><span style='font-size:16px;'>Warranty Information</span></td></tr>";
		// get warranty 
		$this->load->model('site_model');
		$m .= $this->site_model->display_warranty();
		$m .= "<br /><br /><u><b>Client E-Signature: X " . $o['client_info']['e_sig'] . "</b></u>";
		$m .= "<tr><td>".$txt."</td></tr>";
		$m .= "</table>";

	 	$to = $o['client_info']['usern_email'];
		$subject = "Order Confirmation - VECCHIO Trees  ";
		$body =  $email_top . $m . $email_bot;

		$headers = "";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: " . $this->sender_name; 
		$headers .= " <".$this->sender_email.">\r\n";
		$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
		$headers .= "Return-Path: ".$this->sender_email;


		mail($to, $subject, $body, $headers);


	}	
	
	function send_email_rec_qq($qq_id, $choose_ship = false){
		
		$email_top = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><title>Vecchio Trees</title></head><body><div style=\"width:800px; margin-left:auto; margin-right:auto; border: 1px solid #cccccc;\"><img src=\"http://www.vecchiotrees.com/_images/vc_email_top_s1.jpg\" alt = \"Vecchio Trees\" />";
			$email_bot = "<img src=\"http://www.vecchiotrees.com/_images/vc_email_footer_s1.jpg\" alt = \"Vecchio Trees T: 855/819-7777 F: 855/828-6708  - 4285 Spyres Way,  Modesto, CA 95356\" /></div></body></html>";
		// get all the order details. 
		$this->load->model('tracking_model');
		$qq = $this->tracking_model->get_quick_quote_items($qq_id);
		$table = "<table border='0' cellpadding='20' cellspacing='0' width='760'> ";
		// start building the message 

		$m = $table;
		$m .= "<tr><td>";
		$m .= "VECCHIO Trees <br />";
		$m .= "4285 Spyres Way <br />";
		$m .= "Modesto, CA 95356 <br />";
		$m .= "T: 855/819-7777 <br />";
		$m .= "F: 855/828-6708 <br />";
		$m .= "</td></tr>";
		$m .= "<tr><td>";
		$m .= $qq[0]['company_name']." <br />";
		$m .= $qq[0]['fname'] . " " . $qq[0]['lname'] ." <br />";
		$m .= $qq[0]['bill_address'] . " <br />";
		$m .= $qq[0]['bill_city'].", ".$qq[0]['bill_state'] ." ". $qq[0]['bill_zip'] ." <br />";
		$m .= "T: ".$qq[0]['phone']." <br />";
		$m .= (!empty($qq[0]['fax']) ? "F: " . $qq[0]['fax'] : "");
		$m .= "Email: " . $qq[0]['usern_email'];
		$m .= "</td></tr>";
		$m .= "<tr><td>";
		$fname =  ucfirst($qq[0]['fname']);
		$m .= "Dear " .$fname . ",";
		$m .= "<br /><br />";
		$m .= "Thank you for your order with VECCHIO Trees. Our unyielding commitment to quality, service and authenticity has earned Vecchio Trees the trust and loyalty of customers who turn to us first, time and time again. The cultivation of specimen trees is a way of life at Vecchio Trees. By intimately knowing and understanding our client’s vision and needs, we turn vision into reality. Using our creativity, expertise and industry affiliations, Vecchio Trees provides the highest quality products and associated services available. Welcome to the VECCHIO family. <br /> <br />";
		
		if($choose_ship){
		$anchor = '<a href="https://www.vecchiotrees.com/index.php/dir/log_in/my_account">Here</a>';
		$m .= '<span style="color:#990000">Please log into your Vecchio account to choose a ship date for your order : '.$anchor.'</span><br /><br /> ';
			
		}

		$m .= "Thank You - Vecchio Trees <br /><br />";
		$m .= "In this document you will find the following information: <br /><br />";
		$m .= "- Transaction Confirmation Sheet<br />";
		$m .= "- Order Item Details<br />";
		$m .= "- Warranty Information<br /><br /><br />";
		$m .= "</td></tr></table>";		
	    $m .= "<hr />";
		
		$r = $this->gen_receipt_qq($qq, $table);

		$m .= $r;
		$m .= "<br /><br />";
		$m .= "<hr />";

		$m .= $table;
		// get warranty 
		$this->load->model('site_model');
		$m .= $this->site_model->display_warranty();
		$m .= "<br /><br />";
		$m .= "<br /><br /><u><b>Client E-Signature: X " . $qq[0]['e_sig'] . "</b></u>";
		$m .= "</table>";

	 	$to = $qq[0]['usern_email'];
		$subject = "Order Confirmation - VECCHIO Trees  ";
		$body =  $email_top . $m . $email_bot;

		$headers = "";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: " . $this->sender_name; 
		$headers .= " <".$this->sender_email.">\r\n";
		$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
		$headers .= "Return-Path: ".$this->sender_email;


		mail($to, $subject, $body, $headers);


	}
	
	function gen_quote_email($order_id, $to_email){
		
		$email_top = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><title>Vecchio Trees</title></head><body><div style=\"width:800px; margin-left:auto; margin-right:auto; border: 1px solid #cccccc;\">";
		$email_bot = "</div></body></html>";
		
		// get all the order details
		 
		$this->load->model('tracking_model');
		$o = $this->tracking_model->get_order_images_info($order_id, 'pre');
		$table = "<table border='0' cellpadding='20' cellspacing='0' width='760'> ";
		
		// start building the message 
		
		$m = " ";
		foreach($o['order_items'] as $item){
		$table_b = str_replace("border='0'", "border='1'", $table);
		$table_c =  str_replace("cellpadding='20'", "cellpadding='5'", $table_b);
		$m .= $table_c; 
		$image = base_url() . "_fdr/" . $item['files'][0]['file_name'];
		
		$m .= '<tr><td>' . 'Tagged for: '.$o['client_info']['company_name'].' - '.$o['client_info']['fname'] . " " . $o['client_info']['lname'] . "</td></tr>";
		$m .= '<tr><td>' . 'Product Serial Number: <a href="'.$image.'" >' . $item['serial_no'] . "</a></td></tr>";
		$m .= '<tr><td>' . 'Product Description: '. $item['description'] . "</td></tr>";
		$m .= '<tr><td>' . 'Order Batch: ' . $o['order_name'] . "</td></tr>";	
		
		$m .= "<tr><td><img src='" . $image . "' width='760' style='border:0px;' /></td></tr>";
		$m .= "</table>";
		$m .= "<br />";
		}

		$m .= "<hr />";	

		$subject = "Tagged Trees";
		$body =  $email_top . $m . $email_bot;

		$headers = "";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: " . $this->sender_name; 
		$headers .= " <".$this->sender_email.">\r\n";
		$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
		$headers .= "Return-Path: ".$this->sender_email;


		mail($to_email, $subject, $body, $headers);
		
		return "Sent";
		
	}
	
	function gen_receipt($o , $table, $order_id){
		
		$r = "";
		$r .= $table;
		$r .= "<tr><td><span style='font-size:16px;'>Transaction Confirmation Sheet</span></td></tr>";
		$r .= "<tr><td><span style='font-size:16px;'>".$o['order_name']."</span></td></tr>";
		if(isset($o['po_number']) && $o['po_number'] != ''){
		$r .= "<tr><td><span style='font-size:16px;'>Purchase Order Number: " . $o['po_number']."</span></td></tr>";	
		}
		$r .= "<tr><td>";
		$r .= "<span style='font-size:14px; font-family:courier; font-weight:bold;' >";
		$r .= "Inventory Order <br />";
		
		$r .= "------------------------------------------------------------------------------<br />";
		$sub_total = 0;
		foreach($o['order_items'] as $item){
		$r .= "<pre>";
		$r .=  $item['serial_no'] . "-" . $item['description'] . "\t " .  "  $". number_format($item['cust_cost'], 2, '.', ',') . "<br />";
		$r .= "</pre>";
		$sub_total += $item['cust_cost'];
		}
		if($o['discount_percent'] != 0){
			$r .= "Subtotal: "  .  "  <del>$". number_format($sub_total, 2, '.', ','). "</del><br />";
			$r .= "<span style='color:red'>".($o['discount_percent'] * 100) . "% Off </span><br />";
			$d = ($sub_total - ($sub_total * $o['discount_percent']));
			$r .= "Discounted Subtotal: <u>$". number_format($d, 2, '.', ',') . "</u><br />"; 
		} else {
			$r .= "Subtotal: "  .  "  $". number_format($sub_total, 2, '.', ','). "<br />";
		}

		if($o['boxed'] == 1){
		$r .= "+ Tree Boxes: $". number_format($o['shipping_info']['box_price'], 2, '.', ',') . "<br />";	
		}
		
		$this->load->model('tracking_model');
		$qo = $this->tracking_model->get_transaction_info($order_id);		
		
		$r .= "------------------------------------------------------------------------------<br />";
		if(is_numeric($qo['pay_items'][0]['amount'])){
			$r .= "Transaction Information <br />";

			$count_p = count($qo['pay_items']);
			for($i=0;$i<$count_p;$i++){
				$r .= "Transaction ID: ".$qo['pay_items'][$i]['transaction_id']." <br />";
				$r .= "Freight $".number_format($qo['pay_items'][$i]['freight'], 2, '.', ',')." <br />";
				$r .= "Total: $".number_format($qo['pay_items'][$i]['amount'], 2, '.', ',')." <br />";
				$r .= "Pay Method: ".$qo['pay_items'][$i]['method']." <br />";
				$r .= "Payment Date: ".date("l F j, Y - g:i a", strtotime($qo['pay_items'][$i]['payment_date']))." <br /><br />";	
			}
			if($count_p > 1){
				$r .= " Grand Total: $".number_format($qo['pay_total'], 2, '.', ',')." <br />";;
			}
		}
		$r .=  "------------------------------------------------------------------------------<br />";
		$r .= "Client Information <br />";
		$r .= $o['client_info']['company_name'] . " <br />";
		$r .= $o['client_info']['fname'] . " " . $o['client_info']['lname'] ." <br />";
		$r .= $o['client_info']['bill_city'].", ".$o['client_info']['bill_state'] ." ". $o['client_info']['bill_zip'] ." <br />";
		$r .= "Phone: ".$o['client_info']['phone']." <br />";
		$r .= (!empty($o['client_info']['fax']) ? "Fax: " . $o['client_info']['fax'] . "<br />" : "");
		$r .=  "------------------------------------------------------------------------------<br />";
		$r .= "Recipient Information <br />";
		if($o['will_call'] == 1){
		$r .= "Will Call / Customer Pickup. Please Contact Vecchio Ranch at 559/528-9925 regarding Will Call"."<br />";
		$r .= $o['shipping_info']['location']. "<br />";	
		} else {
		$r .= $o['shipping_info']['location']. " <br />";
		$r .= $o['shipping_info']['ship_address'] . " <br />";
		$r .= $o['shipping_info']['ship_city'].", ".$o['shipping_info']['ship_state'] ." ". $o['shipping_info']['ship_zip'] ." <br />";
		}
		$r .= "Phone: ".$o['shipping_info']['location_phone']." <br />";
		$r .=  "------------------------------------------------------------------------------<br />";
		$r .= "</span>";
		$r .= "</td></tr>";
		$r .= "</table>";
		
		return $r;
	}
	
	function gen_receipt_qq($qq , $table){
		
		$r = "";
		$r .= $table;
		$r .= "<tr><td><span style='font-size:16px;'>Transaction Confirmation Sheet</span></td></tr>";
		$r .= "<tr><td><span style='font-size:16px;'>".$qq[0]['id'] . "-" . mb_substr($qq[0]['quote_date'], 0,10) ."</span></td></tr>";
		if(isset($qq[0]['po_number']) && $qq[0]['po_number'] != ''){
		$r .= "<tr><td><span style='font-size:16px;'>Purchase Order Number: ".$qq[0]['po_number']."</span></td></tr>";		
		}
		$r .= "<tr><td>";
		$r .= "<span style='font-size:14px; font-family:courier; font-weight:bold;' >";
		$r .= "Vecchio Trees Order <br />";
		$r .= "------------------------------------------------------------------------------<br />";
		$r .= "</td></tr></table>";
		
		$count_items = count($qq[0]['items']);
		$r = "";
		$r .=  "<table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" >";
		$base = base_url() . "_images/_products/";
		$r .= "<tr><td colspan=\"2\"><hr /></td></tr>";
	   for($i=0;$i<$count_items;$i++){
	    
		$r .= "<tr height=\"200\"><td style=\"text-align:left; width:200px;\"><img src=\"".$base. $qq[0]['items'][$i]['product_code'] . ".jpg\" width=\"200\" /></td> ";
		$r .= "<td >";
		$r .= $qq[0]['items'][$i]['description'] . " <span style='font-size:10px;'> - PC ".$qq[0]['items'][$i]['grow_yard']."-".$qq[0]['items'][$i]['product_code']."-".$qq[0]['items'][$i]['box_size']."</span><br />";
		$r .= "List Price: " . "$" . number_format($qq[0]['items'][$i]['list_price'], 2, '.', ',') . " ";
		$r .= "<b>x</b> Quantity: " . $qq[0]['items'][$i]['quantity'] . "<br />";
		$r .= "<hr />";
		$r .= "Price: <del>" .  "$" . number_format($qq[0]['items'][$i]['line_price'], 2, '.', ',') . "</del><br >";
		$r .= " <span style=\"font-size:12px;\">-- " . ($qq[0]['multiplier'] * 100 ). "% Discount --</span><br >";
		$r .= "Your Price: <u>$" . number_format($qq[0]['items'][$i]['cust_line'], 2, '.', ',') . "</u><br >";
		$r .= "<p style=\"font-size:10px; color:C0C0C0;\">".$qq[0]['items'][$i]['text_description']." Exposure: ".$qq[0]['items'][$i]['exposure'].". Watering: ".$qq[0]['items'][$i]['watering']."</p>";
		$r .= "</td></tr>";
		$r .= "<tr><td colspan=\"2\"><hr /></td></tr>";
		}
		$r .= "<tr><td colspan=\"2\">";
		$r .= "Sub Total: " . "$" . number_format($qq[0]['sub_total_items'], 2, '.', ',') . "<br >";
		if($qq[0]['boxed'] == 1){	
		$r .= "+ Boxed Trees Fee: " . "$" . number_format($qq[0]['box_price'], 2, '.', ',') . "<br >";		
		}
		if($qq[0]['disc'] > 0){
		$r .= "<b>- Special Discount: " . "$" . number_format($qq[0]['disc'], 2, '.', ',') . " - " .$qq[0]['who_disc'] . "</b><br >"; 
		}
		$r .= "+ Shipping Estimate: " . "$" . number_format($qq[0]['ship_cost'], 2, '.', ',') . "* <br >";
		$r .= "<hr />";
		$r .= "<b>Grand Total: " . "$" . number_format($qq[0]['grand_total'], 2, '.', ',') . "</b><br >";
		if($qq[0]['will_call'] == 0){
		$r .= "<span style=\"font-size:10px;\">*Shipping to: " . $qq[0]['ship_zip'] . ", approximately " . $qq[0]['distance'] . " miles from  VECCHIO shipping facility.</span><br /><br />";
		} else {
		$r .= "<span style=\"font-size:10px;\">* Customer Pickup / Will Call - No Freight. Please Contact Vecchio Ranch at 559/528-9925 regarding Will Call</span><br /><br />";		
		}
	    $r .= "<br /><br />";
		$r .= "</td></tr>";
		$r .="</table>";
		
		$this->load->model('tracking_model');
		$qo = $this->tracking_model->get_qq_order_receipt($qq[0]['id']);
		
		$r .= $table;
		$r .= "<tr><td>";
		$r .= "------------------------------------------------------------------------------<br />";
		if(is_numeric($qo['pay_items'][0]['amount'])){
			$count_p = count($qo['pay_items']);
			for($i=0;$i<$count_p;$i++){
				$r .= "Transaction ID: ".$qo['pay_items'][$i]['transaction_id']." <br />";
				$r .= "Freight $".number_format($qo['pay_items'][$i]['freight'], 2, '.', ',')." <br />";
				$r .= "Total: $".number_format($qo['pay_items'][$i]['amount'], 2, '.', ',')." <br />";
				$r .= "Pay Method: ".$qo['pay_items'][$i]['method']." <br />";
				$r .= "Payment Date: ".date("l F j, Y - g:i a", strtotime($qo['pay_items'][$i]['payment_date']))." <br /><br />";	
			}
			if($count_p > 1){
				$r .= " Grand Total: $".number_format($qo['pay_total'], 2, '.', ',')." <br />";;
			}
		}
		$r .=  "------------------------------------------------------------------------------<br />";
		$r .= "Client Information <br />";
		$r .= $qq[0]['company_name'] . " <br />";
		$r .= $qq[0]['fname'] . " " . $qq[0]['lname'] ." <br />";
		$r .= $qq[0]['bill_city'].", ".$qq[0]['bill_state'] ." ". $qq[0]['bill_zip'] ." <br />";
		$r .= "Phone: ".$qq[0]['phone']." <br />";
		$r .= (!empty($qq[0]['fax']) ? "Fax: " . $qq[0]['fax'] . "<br />" : "");
		$r .=  "------------------------------------------------------------------------------<br />";
		$r .= "Freight Information <br />";
		$r .= $qo['location']. " <br />";
		$r .= $qo['ship_address'] . " <br />";
		$r .= $qo['ship_city'].", ".$qo['ship_state'] ." ". $qo['ship_zip'] ." <br />";
		$r .= "Phone: ".$qo['location_phone']." <br />";
		$r .=  "------------------------------------------------------------------------------<br />";
		$r .= "</span>";
		$r .= "</td></tr>";
		$r .= "</table>";
		
		return $r;
	}
	
	function send_admin_rec($order_id){
		// get all the order details. 
		$this->load->model('tracking_model');
		$o = $this->tracking_model->get_order_images_info($order_id);
		$table = "<table border='0' cellpadding='20' cellspacing='0' width='760'> ";
		
		// send notification to admin users at same time:
		// get more info on freight first ---
		$this->load->model('shipping_model');
		$ship_zip = $o['shipping_info']['ship_zip'];


		$freight = $this->shipping_model->get_shipping_cost($order_id, 93292, $ship_zip, $heavy = false );

		$from_city = $freight['from_city'];
		$from_state = $freight['from_state'];
		$to_city = $freight['to_city'];
		$to_state = $freight['to_state'];
		$miles = $freight['miles'];
		$buffer_miles = $freight['buffer_miles'];
		$cost_per_mile = $freight['cost_per_mile'];
		$mileage_cost = $freight['mileage_cost'];
		$charge_trucks = $freight['charge_trucks'];
		$total_cost_cust = $freight['total_cost_cust'];
		$trucks_each_gy = $freight['trucks_each_gy'];
		$actual_trucks = $freight['actual_trucks'];
		$total_actual_trucks = $freight['total_actual_trucks'];
		$total_cost_vecchio = $freight['total_cost_vecchio'];
		$total_gy = $freight['total_gy'];

		$f = "";
		
		$r = $this->gen_receipt($o, $table, $order_id);
		$f .= $r;
		$f .= $table;
		$f .= "<tr><td>";
		$f .= "<span style='font-size:14px; font-family:courier; font-weight:bold;' >";
		$f .=  "------------------------------------------------------------------------------<br />";
		$f .= "Freight Information:<br />";
		$f .= 'From:' . $from_city . ", " . $from_state .'<br />';
		$f .= 'To: '  .$to_city . ", " . $to_state. '<br />';
		$f .= '<br />';
		$f .= 'Total Miles: ( + ' . $buffer_miles . ' Buffer Miles) = '.  $miles . '<br />';
		$f .= 'Cost Per Mile: $' . $cost_per_mile.'<br />';
		$f .= 'Mileage Cost: ('. $miles .' x $'. $cost_per_mile.') = $'. $mileage_cost.'<br />';			
		$f .= 'Total Trucks: '.  $charge_trucks. '<br />';
		$f .= 'Total Freight Cost: ( ' . $charge_trucks. ' x $'.$mileage_cost.') = $'. $total_cost_cust.'<br />'; 
		$f .= '<br />';
		$f .= 'Total Grow Yards: '. $total_gy.'<br />';
		$f .= '1 Truck Per Grow Yard: '. $trucks_each_gy . '<br />';
		$f .= 'Calculated Truck Space: ' . $actual_trucks . '<br />';
		$f .= 'Calculated Trucks: '. $total_actual_trucks . '<br />';	
	    $f .= 'Calculated Cost: ( '. $total_actual_trucks .' x $'. $mileage_cost.') = $'. $total_cost_vecchio . '<br />';
		$f .=  "------------------------------------------------------------------------------<br />";
		$f .= "</td></tr></table><br />";

		$top = "------------------VECCHIO TREES------------<br /><br />";
		$bot = "Sincerely, Vecchio Website Bot";


		$headers = "";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: " . $this->sender_name; 
		$headers .= " <".$this->sender_email.">\r\n";
		$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
		$headers .= "Return-Path: ".$this->sender_email;

		$to = "kara@vecchiotrees.com, paul@vecchiotrees.com, tylerpenney@gmail.com";
		$body = $top . $f . $bot;
		$subject = "New Order - 001";

		mail($to, $subject, $body, $headers);
	}
	
	function send_admin_rec_qq($qq_id){
		// get all the order details. 
		$this->load->model('tracking_model');
		$qq = $this->tracking_model->get_quick_quote_items($qq_id);
		$table = "<table border='0' cellpadding='20' cellspacing='0' width='760'> ";

		$top = "------------------VECCHIO TREES------------<br /><br />";
		$bot = "Sincerely, Vecchio Website Bot";

		$r = $this->gen_receipt_qq($qq, $table);


		$headers = "";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: " . $this->sender_name; 
		$headers .= " <".$this->sender_email.">\r\n";
		$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
		$headers .= "Return-Path: ".$this->sender_email;

		$to = "kara@vecchiotrees.com, paul@vecchiotrees.com";
		//$to = "tylerpenney@gmail.com";
		$body = $top . $r . $bot;
		$subject = "Quick Quote Payment Notice";

		mail($to, $subject, $body, $headers);
	}
	
	
	function qq_shipping_email($qq_id, $on_account){
		
			$this->load->model('tracking_model');
			$row = $this->tracking_model->get_quick_quote_items($qq_id);
			$qo = $this->tracking_model->get_qq_order_receipt($qq_id);
			
			$r = "<h2>Vecchio Shipping Order</h2>";
			$r .= "<h4>Client Information</h4>";
			$r .= $row[0]['company_name'] . " <br />";
			$r .= $row[0]['fname'] . " " . $row[0]['lname'] ." <br />";
			$r .= $row[0]['bill_city'].", ".$row[0]['bill_state'] ." ". $row[0]['bill_zip'] ." <br />";
			$r .= "Phone: ".$row[0]['phone']." <br />";
			$r .= (!empty($row[0]['fax']) ? "Fax: " . $row[0]['fax'] . "<br />" : "");
			$r .=  "<hr />";
			$r .= "<h4>Freight Information</h4>";
			$r .= "<b><span style='font-size:16px;'>Ship Date: " . $qo['ship_date'] . "</span></b><br />";
			$r .= $qo['location']. " <br />";
			$r .= $qo['ship_address'] . " <br />";
			$r .= $qo['ship_city'].", ".$qo['ship_state'] ." ". $qo['ship_zip'] ." <br />";
			$r .= "Phone: ".$qo['location_phone']." <br />";
			$r .=  "<hr />";
			
			$count_i = count($row[0]['items']);
			$r .= "<h4>Products</h4>";
			$r .= "--------------------------------------------<br />";
			for($i=0;$i<$count_i; $i++){				
			$r .= $row[0]['items'][$i]['description'] . " - PC ".$row[0]['items'][$i]['grow_yard']."-".$row[0]['items'][$i]['product_code']."-".$row[0]['items'][$i]['box_size'] . " X Quantity: " . $row[0]['items'][$i]['quantity'] . "<br />--------------------------------------------<br />";
			}
			if($on_account == 'on_account'){
			$r .= 'On Account' ;
			} else {
			$r .= 'Paid';
			}
			
			$headers = "";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: " . $this->sender_name; 
			$headers .= " <".$this->sender_email.">\r\n";
			$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
			$headers .= "Return-Path: ".$this->sender_email;

			$to = "kara@vecchiotrees.com, paul@vecchiotrees.com";
			$subject = "Quote Ship Info";

			mail($to, $subject, $r, $headers);
			
			
	}
	
	function send_rep_notice($id, $notice, $type = 'quote'){
			
			
			if($type == 'order'){
			// get customer info. 
			$q = "SELECT orders.id, orders.order_name,
						 c.usern_email as customer_email, c.fname as customer_fname, c.lname as customer_lname,
			 			 r.usern_email as rep_email, r.fname as rep_fname, r.lname as rep_lname, 
						 rt.usern_email as rep2_email, rt.fname as rep2_fname, rt.lname as rep2_lname
			  	  FROM orders
			      JOIN users AS c ON c.id = orders.customer_id
			      LEFT JOIN users AS r ON r.id = orders.rep_id
			      LEFT JOIN users AS rt ON rt.id = orders.rep_id_2
				  AND orders.id = '$id' ";
				
			} else { // its a quote
				// get customer info. 
					$q = "SELECT quick_quote.id, quick_quote.quote_date,
					 			 c.usern_email as customer_email, c.fname as customer_fname, c.lname as customer_lname,
					 			 r.usern_email as rep_email, r.fname as rep_fname, r.lname as rep_lname, 
								 rt.usern_email as rep2_email, rt.fname as rep2_fname, rt.lname as rep2_lname
				  	  FROM quick_quote
				      JOIN users AS c ON c.id = quick_quote.customer_id
				      LEFT JOIN users AS r ON r.id = quick_quote.rep_id
				      LEFT JOIN users AS rt ON rt.id = quick_quote.rep_id_2
					  AND quick_quote.id = '$id' ";			
			
			
			}
			
			$result = $this->db->query($q);
			$info = $result->result_array();
			
			if(!is_null($info[0]['rep_email'])){ 
				$to = $info[0]['rep_email'];
				if(!is_null($info[0]['rep2_email'])){
					$to .= ", ". $info[0]['rep2_email'];
				}
				$to .= ", admin@vecchiotrees.com"; // because who doesn't want a million notification emails? 
			} else {
				$to = "admin@vecchiotrees.com";
			}	
				$r = "";
				$r .= "<h3>Vecchio Notification</h3><br />";
				if(!is_null($info[0]['rep_fname'])){
					$r .= "<p>Dear " . $info[0]['rep_fname'];
					if(!is_null($info[0]['rep2_fname'])){
					$r .= " And " . $info[0]['rep2_fname'] . "-</p><br />";
					} else {
					$r .= "-</p><br /> ";	
					}
				}
				if($type == 'quote'){
				$r .= "<p>Quote ID: ". $info[0]['id'] . "-" . mb_substr($info[0]['quote_date'], 0,10) . "</p><br />";
			    } else {
				$r .= "<p>Order ID: ". $info[0]['id'] . "-" .$info[0]['order_name'] . "</p><br />";
				} 
				$r .= "<p> Client: ". $info[0]['customer_fname'] . " ". $info[0]['customer_lname']." Client Email:". $info[0]['customer_email'] . "</p><br />";
				$r .= "<h5>". $notice . "</h5><br />";
				$r .= "<p>- VECCHIO TREES WEBSITE</p><br />";
				$r .= "<span style='font-size:11px;'>This e-mail and any attachments may contain confidential and privileged information.</span><br />";
				$now_date = date("Y-m-d H:i:s");
				$r .= "<span style='font-size:10px;'>Notice Sent: ".$now_date."</span>";
							
				$headers = "";
				$headers .= 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= "From: " . $this->sender_name; 
				$headers .= " <".$this->sender_email.">\r\n";
				$headers .= "Reply-To: ".$this->sender_email."\r\n"; 
				$headers .= "Return-Path: ".$this->sender_email;

				$subject = "Vecchio Notification" ;

				mail($to, $subject, $r, $headers);
			
		
			
			
		
	}
	
	
	
	
	
}

?>