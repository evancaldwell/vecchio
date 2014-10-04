<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V_email extends CI_Controller {
	var $sender_name;
	var $sender_email;
		
	function __construct()
	{
		parent::__construct();
		$this->sender_email = "noreply@vecchiotrees.com";
		$this->sender_name = "Vecchio Trees";
	
	}

function index(){
	$order_id = 32;
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
	$m .= "</td></tr>";
	$m .= "<tr><td>";
	$fname =  ucfirst($o['client_info']['fname']);
	$m .= "Dear " .$fname . ",";
	$m .= "<br /><br />";
	$m .= "Thank you for your order with VECCHIO Trees. Our unyielding commitment to quality, service and authenticity has earned Vecchio Trees the trust and loyalty of customers who turn to us first, time and time again. The cultivation of specimen trees is a way of life at Vecchio Trees. By intimately knowing and understanding our client’s vision and needs, we turn vision into reality. Using our creativity, expertise and industry affiliations, Vecchio Trees provides the highest quality products and associated services available. Welcome to the VECCHIO family. <br /> <br />";
	
	$m .= "Thank You - Vecchio Trees <br /><br />";
	$m .= "In this document you will find the following information: <br /><br />";
	$m .= "- Transaction Confirmation Sheet<br />";
	$m .= "- Order Item Details<br />";
	$m .= "- Warranty Information<br /><br /><br />";
	$m .= "</td></tr></table>";		
    $m .= "<hr />";
	$r = "";
	$r .= $table;
	$r .= "<tr><td><span style='font-size:16px;'>Transaction Confirmation Sheet</span></td></tr>";
	$r .= "<tr><td><span style='font-size:16px;'>".$o['order_name']."</span></td></tr>";
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
	$r .= "Subtotal: "  .  "  $". number_format($sub_total, 2, '.', ','). "<br />";
	$r .= "------------------------------------------------------------------------------<br />";
	$r .= "Transaction Information <br />";
	$r .= "Transaction ID: ".$o['transaction_info']['transaction_id']." <br />";
	$r .= "Freight $".number_format($o['transaction_info']['freight'], 2, '.', ',')." <br />";
	$r .= "Total: $".number_format($o['transaction_info']['amount'], 2, '.', ',')." <br />";
	$r .= "Pay Method: ".$o['transaction_info']['method']." <br />";
	$r .= "Payment Date: ".date("l F j, Y - g:i a", strtotime($o['transaction_info']['payment_date']))." <br />";
	$r .=  "------------------------------------------------------------------------------<br />";
	$r .= "Client Information <br />";
	$r .= $o['client_info']['company_name'] . " <br />";
	$r .= $o['client_info']['fname'] . " " . $o['client_info']['lname'] ." <br />";
	$r .= $o['client_info']['bill_city'].", ".$o['client_info']['bill_state'] ." ". $o['client_info']['bill_zip'] ." <br />";
	$r .= "Phone: ".$o['client_info']['phone']." <br />";
	$r .= (!empty($o['client_info']['fax']) ? "Fax: " . $o['client_info']['fax'] . "<br />" : "");
	$r .=  "------------------------------------------------------------------------------<br />";
	$r .= "Freight Information <br />";
	$r .= $o['shipping_info']['location']. " <br />";
	$r .= $o['shipping_info']['ship_address'] . " <br />";
	$r .= $o['shipping_info']['ship_city'].", ".$o['shipping_info']['ship_state'] ." ". $o['shipping_info']['ship_zip'] ." <br />";
	$r .= "Phone: ".$o['shipping_info']['location_phone']." <br />";
	$r .=  "------------------------------------------------------------------------------<br />";
	$r .= "</span>";
	$r .= "</td></tr>";
	$r .= "</table>";
	
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
	
	}
	
	$m .= "</table>";
	$m .= "<hr />";	
	$m .= $table;
	$m .= "<tr><td><span style='font-size:16px;'>Warranty Information</span></td></tr>";
	$txt = "Vecchio Trees LLC is unable to control tree mortalities after the sale. Vecchio Trees LLC is only able to guarantee trees with the use of proper irrigation, drainage and adequate pruning upon planting during delivery (unless tress were laced/pruned for shipping). The tree warranty is for one (1) year, which includes a one-time product replacement, applicable to Olive trees only. Subject to this warranty, risk of loss shall pass to the Buyer upon delivery, if shipping is contracted by Vecchio Trees LLC, or upon shipment, if shipping is contracted by Buyer.  Freight, labor, equipment and other installations costs associated the warranty replacement product IS NOT INCLUDED in this Warranty.   Such costs are to be paid by the Buyer.";
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
	
	$to = "tylerpenney@gmail.com";
	$body = $top . $r . $f . $bot;
	$subject = "New Order - 001";
	
	mail($to, $subject, $body, $headers);
	
}
}