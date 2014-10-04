<?php
class Pdf_model extends CI_Model{



	function __construct(){		
		parent::__construct();
		$this->load->model('tracking_model');
		$this->load->model('email_model');
	} 
	
	function gen_pdf($order_id, $type)
	    {
			
			// Button to validate and print
			//	$pdf->Button('print', 30, 10, 'Print', 'Print()', array('lineWidth'=>2, 'borderStyle'=>'beveled', 'fillColor'=>array(128, 196, 255), 'strokeColor'=>array(64, 64, 64)));
			
			$o = $this->tracking_model->get_order_images_info($order_id);
			
	        $this->load->library('pdf');
	
			// set font
			$this->pdf->SetFont('helvetica', '', 12);

			// add a page
			$this->pdf->AddPage();
			
			
			$txt = "VECCHIO Trees \n";
			$txt .= "4285 Spyres Way \n";
			$txt .= "Modesto, CA 95356 \n";
			$txt .= "T: 855/819-7777 \n";
			$txt .= "F: 855/828-6708 \n";
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(5);

			$txt = $o['client_info']['company_name']." \n";
			$txt .= $o['client_info']['fname'] . " " . $o['client_info']['lname'] ." \n";
			$txt .= $o['client_info']['bill_address'] . " \n";
			$txt .= $o['client_info']['bill_city'].", ".$o['client_info']['bill_state'] ." ". $o['client_info']['bill_zip'] ." \n";
			$txt .= "T: ".$o['client_info']['phone']." \n";
			$txt .= (!empty($o['client_info']['fax']) ? "F: " . $o['client_info']['fax'] : "");
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(5);
	
			
			// set some text to print
			$fname =  ucfirst($o['client_info']['fname']);
			$txt = "Dear ".$fname.", ";
			//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(5);

			$txt = "Thank you for your order with VECCHIO Trees. Our unyielding commitment to quality, service and authenticity has earned Vecchio Trees the trust and loyalty of customers who turn to us first, time and time again. The cultivation of specimen trees is a way of life at Vecchio Trees. By intimately knowing and understanding our client’s vision and needs, we turn vision into reality. Using our creativity, expertise and industry affiliations, Vecchio Trees provides the highest quality products and associated services available. Welcome to the VECCHIO family.";
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(10);
			// set some text to print
			
			// set font
			$this->pdf->SetFont('helvetica', 'B', 12);
			$txt = "In this document you will find the following information: ";
			//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(5);
			
			$txt = " - Transaction Confirmation Sheet";
			//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(1);
			
			$txt = " - Order Item Details";
			//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(1);
			
			$txt = " - Warranty Information";
			//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(20);
			
			$this->pdf->SetFont('helvetica', '', 12);
			$txt = "Thank You  - VECCHIO Trees";
			//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='R', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
			$this->pdf->AddPage();
			$this->pdf->Ln(10);
			
			$this->pdf->SetFont('courier', 'B', 14);
			$txt = "Transaction Confirmation Sheet \n";
			$txt .= "Order: " . $o['order_name'];
			if(isset($o['po_number']) && $o['po_number'] != ''){
			$txt .= "Purchase Order Number: " . $o['po_number'];	
			}
			//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		
			$this->pdf->Ln(10);
			$this->pdf->SetFont('courier', '', 11);
			$txtt = "Inventory Order \n";
			$txtt .= "------------------------------------------------------------------------------\n";
			$sub_total = 0;
			foreach($o['order_items'] as $item){
			
			$txtt .=  $item['serial_no'] . " - " . $item['description'] . " \t \t \t \t" .  "  $". number_format($item['cust_cost'], 2, '.', ',') . "\n";
			$sub_total += $item['cust_cost'];
			}
			$txtt .= "Subtotal:"  .  "  $". number_format($sub_total, 2, '.', ','). "\n";
			if($o['boxed'] == 1){
				$txtt .= "+ Tree Boxes: $". number_format($o['shipping_info']['box_price'], 2, '.', ',') . "\n";	
			}
			$txtt .= "------------------------------------------------------------------------------\n";
			
			$qo = $this->tracking_model->get_transaction_info($order_id);
			if($qo['pay_items'][0]['amount'] != ''){
				$txtt .= "Transaction Information" ."\n";

				$count_p = count($qo['pay_items']);
				for($i=0;$i<$count_p;$i++){
			
				$txtt .= "Transaction ID: ".$qo['pay_items'][$i]['transaction_id']."\n";
				$txtt .= "Freight $".number_format($qo['pay_items'][$i]['freight'], 2, '.', ','). "\n";
				$txtt .= "Total: $".number_format($qo['pay_items'][$i]['amount'], 2, '.', ',')."\n";
				$txtt .= "Pay Method: ".$qo['pay_items'][$i]['method']."\n";
				$txtt .= "Payment Date: ".date("l F j, Y - g:i a", strtotime($qo['pay_items'][$i]['payment_date']))."\n\n";;	
			
				}
				if($count_p > 1){
				$txtt .= " Grand Total: $".number_format($qo['pay_total'], 2, '.', ',')."\n";
				}			
			}
			$txtt .=  "------------------------------------------------------------------------------\n";
			$txtt .= "Client Information \n";
			$txtt .= $o['client_info']['company_name'] . " \n";
			$txtt .= $o['client_info']['fname'] . " " . $o['client_info']['lname'] ." \n";
			$txtt .= $o['client_info']['bill_city'].", ".$o['client_info']['bill_state'] ." ". $o['client_info']['bill_zip'] ." \n";
			$txtt .= "Phone: ".$o['client_info']['phone']." \n";
			$txtt .= (!empty($o['client_info']['fax']) ? "Fax: " . $o['client_info']['fax'] . "\n" : "");
			$txtt .=  "------------------------------------------------------------------------------\n";
			$txtt .= "Recipient Information \n";

			if($o['will_call'] == 1){
			$txtt .= "Will Call / Customer Pickup -  Please Contact Vecchio Ranch at 559/528-9925 regarding Will Call"." \n";
			$txtt .= $o['shipping_info']['location']. " \n";	
			} else {
			$txtt .= $o['shipping_info']['location']. " \n";
			$txtt .= $o['shipping_info']['ship_address'] . " \n";
			$txtt .= $o['shipping_info']['ship_city'].", ".$o['shipping_info']['ship_state'] ." ". $o['shipping_info']['ship_zip'] ." \n";		}
			$txtt .= "Phone: ".$o['shipping_info']['location_phone']." \n";
			$txtt .=  "------------------------------------------------------------------------------\n";
			
			$this->pdf->Write($h=0, $txtt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			foreach($o['order_items'] as $item){
			$this->pdf->AddPage();

			$this->pdf->SetFont('helvetica', 'B', 14);
			$txt = "Order Item Details";
					//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			// print a block of text using Write()
			// Image example with resizing
			$this->pdf->Ln(10);
			$this->pdf->SetFont('helvetica', 'B', 11);
			$this->pdf->Cell(0, 0, 'Sold to: '.$o['client_info']['company_name'].' - '.$o['client_info']['fname'] . " " . $o['client_info']['lname'], 0, 1, 'L', 0, '', 0);
			$this->pdf->SetFont('helvetica', '', 11);
			$this->pdf->Cell(0, 0, 'Product Serial Number: ' . $item['serial_no'], 1, 1, 'L', 0, '', 0);
			$this->pdf->Cell(0, 0, 'Product Description: '. $item['description'] , 1, 1, 'L', 0, '', 0);
			$this->pdf->Cell(0, 0, 'Order Batch: ' . $o['order_name'], 1, 1, 'L', 0, '', 0);
						if(isset($item['files']) && !empty($item['files'])){
						$base_url = base_url() . "_fdr/" . $item['files'][0]['file_name'];
						$this->pdf->Image($base_url, 15, 90, 186, "", 'JPG', $base_url, '', true, 200, '', false, false, 1, false, false, false);
						$this->pdf->SetFont('helvetica', '', 9);
						$this->pdf->SetY(-50);
						// ---------------------------------------------------------
			$this->pdf->Cell(0, 0, 'click on picture to view larger photo', 0, 1, 'L', 0, '', 0);

						} else {
						$this->pdf->Cell(0, 0, 'No Image Available', 1, 1, 'L', 0, '', 0);	
						}
			} // end foreach for each product 
			
			$this->pdf->AddPage();


			$this->pdf->SetFont('helvetica', 'B', 14);
			$txt = "Warranty Information";

			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			// print a block of text using Write()
			// Image example with resizing
			$this->pdf->Ln(10);
			
			$this->pdf->SetFont('helvetica', '', 12);
			// get warranty from database --- 
			$this->load->model('site_model');
			$warranty = $this->site_model->display_warranty();
					//TCPDF Example 003
			$this->pdf->writeHTML($warranty, true, false, true, false, '');
			
			$this->pdf->Ln(10);
			$html = "<u><b>Client E-Signature: X " . $o['client_info']['e_sig'] . "</b></u>";
			$this->pdf->writeHTML($html, true, false, true, false, '');
			
			if($type == 'email'){	
				// send the pdf to an email
			//	$pdfdoc = $this->pdf->Output('', 'S');
			//	$this->email_model->send_email_rec($o['client_info']['fname'], $o['client_info']['lname'], $o['client_info']['usern_email'], $pdfdoc);
				
		//		// notify admin of transaction
			//	$data[] = $txtt;
				// Kara Bienz
			//	$this->email_model->send_email_to('Tyler', 'Penney', 'tylerpenney@gmail.com', 'New Transaction 101', $data, $type = 'text');
			}
		
			$name =  $o['order_name'] . ".pdf";
			$name = str_replace(' ', '_', $name);
			$this->pdf->Output($name, 'D');	
		
				
			//============================================================+
			// END OF FILE                                                
			//============================================================+  
	    }
	
		function gen_pdf_qq($quote_id){
			
			$row = $this->tracking_model->get_quick_quote_items($quote_id);
			
			$this->load->library('pdf');
			
			$this->pdf->AddPage();
			$this->pdf->SetFont('helvetica', 'B', 11);
			$txt = $row[0]['rep']['name']."\n";	
			if(isset($row[0]['rep2']) && $row[0]['rep2']['name'] != ''){
			$txt .= $row[0]['rep2']['name']."\n";	
			}
			$txt .= "VECCHIO Trees \n";
			$txt .= "4285 Spyres Way \n";
			$txt .= "Modesto, CA 95356 \n";
			$txt .= "C: ". $row[0]['rep']['phone']."\n";
			$txt .= "T: (855) 819-7777 \n";
			$txt .= "F: (855) 828-6708 \n";
			$txt .= $row[0]['rep']['email']."\n";	
			
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(5);
			
			$dt = date("F j, Y", strtotime($row[0]['quote_date']));
			$this->pdf->Write($h=0, $dt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(5);

			$txt = $row[0]['cust_name'] ." \n";
			$txt .= $row[0]['company_name']." \n";
			$txt .= $row[0]['bill_address'] . " \n";
			$txt .= $row[0]['bill_city'].", ".$row[0]['bill_state'] ." ". $row[0]['bill_zip'] ." \n";
			$txt .= "T: ".$row[0]['phone']." \n";
			$txt .= (!empty($row[0]['fax']) ? "F: " . $row[0]['fax'] : "");
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(5);
				// set some text to print
				
			
	
			$txt = "Dear ".$row[0]['cust_name'].", ";
				//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(4);
			$txt = "Please maintain a copy of this receipt for your records. You will find transaction, product order details and warranty information on the following pages. Thank you for your business.  " ;
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(10);
				// set some text to print x 100 y 50
				// set color for background
			
			$this->pdf->AddPage();

			
				$this->load->model('payment_model');
				$qo = $this->tracking_model->get_qq_order_receipt($quote_id);
				$r = '';
				$r .=  "<table border='0' cellpadding='8' cellspacing='0' ><tr><td>";
				$r .= "------------------------------------------------------------------------------<br />";
				if($qo['pay_items'][0]['amount'] != ''){
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
				$r .= "Order Contact Information <br />";
				$r .= $row[0]['company_name'] . " <br />";
				$r .= $row[0]['fname'] . " " . $row[0]['lname'] ." <br />";
				$r .= $row[0]['bill_city'].", ".$row[0]['bill_state'] ." ". $row[0]['bill_zip'] ." <br />";
				$r .= "Phone: ".$row[0]['phone']." <br />";
				$r .= (!empty($row[0]['fax']) ? "Fax: " . $row[0]['fax'] . "<br />" : "");
				$r .=  "------------------------------------------------------------------------------<br />";
				$r .= "Freight Information <br />";
				$r .= $qo['location']. " <br />";
				$r .= $qo['ship_address'] . " <br />";
				$r .= $qo['ship_city'].", ".$qo['ship_state'] ." ". $qo['ship_zip'] ." <br />";
				$r .= "Phone: ".$qo['location_phone']." <br />";
				$r .=  "------------------------------------------------------------------------------<br />";
				$r .= "</td></tr>";
				$r .= "</table>";
				
			$count_items = count($row[0]['items']);
			$h = $r;
	
			$h .=  "<table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" >";
			$base = base_url() . "_images/_products/";
			if(isset($row[0]['po_number']) || $row[0]['po_number'] != ''){
			$h .= "<tr><td colspan=\"2\">Purchase Order Number: " . $row[0]['po_number'] . "</td></tr>";	
			}
			$h .= "<tr><td colspan=\"2\"><hr /></td></tr>";
		   for($i=0;$i<$count_items;$i++){
		    
			$h .= "<tr height=\"200\"><td style=\"text-align:left; width:600px;\"><img src=\"".$base. $row[0]['items'][$i]['product_code'] . ".jpg\" width=\"440\" />";
			$h .= "<h4 class=\"prod_pf_txt\" style=\"font-size:10px;\">PC-". $row[0]['items'][$i]['product_code'] . " " . $row[0]['items'][$i]['description'] ."</h4>";
			$h .= "</td> ";
			$h .= "<td style=\" width:1500px; \" >";
			$h .= $row[0]['items'][$i]['description'] . " <span style='font-size:10px;'> - PC ".$row[0]['items'][$i]['grow_yard']."-".$row[0]['items'][$i]['product_code']."-".$row[0]['items'][$i]['box_size']."</span><br />";
			$h .= "List Price: " . "$" . number_format($row[0]['items'][$i]['list_price'], 2, '.', ',') . " ";
			$h .= "<b>x</b> Quantity: " . $row[0]['items'][$i]['quantity'] . "<br />";
			$h .= "<hr noshade size=1 width=\"53%\">";
			$h .= "Price: <del>" .  "$" . number_format($row[0]['items'][$i]['line_price'], 2, '.', ',') . "</del><br >";
			$h .= " <span style=\"font-size:122px;\">-- " . ((1 - $row[0]['multiplier']) * 100 ). "% Discount --</span><br >";
			$h .= "Your Price: <u>$" . number_format($row[0]['items'][$i]['cust_line'], 2, '.', ',') . "</u><br >";
			$h .= "<p style=\"font-size:100px; color:C0C0C0;\">".$row[0]['items'][$i]['text_description']." Exposure: ".$row[0]['items'][$i]['exposure'].". Watering: ".$row[0]['items'][$i]['watering']."</p>";
			$h .= "</td></tr>";
			$h .= "<tr><td colspan=\"2\"><hr /></td></tr>";	
			if($i == 2 || $i == 5 || $i == 11){
		 	$h .= "<tr><td><br /><br /><br /></td></tr>";
			}
			}
			$h .= "<tr><td colspan=\"2\">";
			$h .= "Sub Total: " . "$" . number_format($row[0]['sub_total_items'], 2, '.', ',') . "<br >";
			if($row[0]['boxed'] == 1){	
			$h .= "+ Boxed Trees Fee: " . "$" . number_format($row[0]['box_price'], 2, '.', ',') . "<br >";		
			}
			if($row[0]['disc'] > 0){
			$h .= "<b>- Special Discount: " . "$" . number_format($row[0]['disc'], 2, '.', ',') . " - " .$row[0]['who_disc'] . "</b><br >"; 
			}
			$h .= "+ Shipping Estimate: " . "$" . number_format($row[0]['ship_cost'], 2, '.', ',') . "* <br >";
			$h .= "<hr />";
			$h .= "<b>Grand Total: " . "$" . number_format($row[0]['grand_total'], 2, '.', ',') . "</b><br >";
			if($row[0]['will_call'] == 0){
			$h .= "<span style=\"font-size:100px;\">*Shipping to: " . $row[0]['ship_zip'] . ", approximately " . $row[0]['distance'] . " miles from  VECCHIO shipping facility.</span><br /><br />";
			} else {
			$h .= "<span style=\"font-size:100px;\">* Customer Pickup / Will Call - No Freight.  Please Contact Vecchio Ranch at 559/528-9925 regarding Will Call</span><br /><br />";	
				
			}
		    $h .= "<br /><br />";
			$h .= "</td></tr>";
			$h .="</table>";
			$this->pdf->writeHTML($h, true, false, true, false, '');	
			

			$this->load->model('site_model');
			$w = $this->site_model->display_warranty();
			$w .= "<p>".$row[0]['e_sig']."</p>";
			$w .= "<hr noshade size=1 width=\"45%\">";
			$w .= "E-Signature<br /><br />";

				
			$this->pdf->writeHTML($w, true, false, true, false, '');	
			
			
			$str = strtoupper(str_replace(' ', "-", $row[0]['cust_name']));
			$name = "VECCHIO_QUOTE-". $str .".pdf";
			$name = str_replace(' ', '_', $name);
			$this->pdf->Output($name, 'D');
			
		//	echo $h;
			
		}
		

		
		
		function gen_check_by_fax_pdf($order_id)
		    {

				$order_name = $this->tracking_model->get_order_name($order_id);
				$o = $this->tracking_model->get_order_items($order_id);
				$c = $this->tracking_model->get_client_info($order_id);
				$s = $this->tracking_model->get_shipping_info($order_id);
				$extra = $this->tracking_model->get_order_extra($order_id, 'order');
				
				$this->load->model('shipping_model');
				$ship_zip = $s['ship_zip'];
				
				$freight = $this->shipping_model->get_shipping_cost($order_id, 93292, $ship_zip, $heavy = false );

				$total_cost_cust = $freight['total_cost_cust'];
				
				$txtt = "Inventory Order \n";
				if($extra['po_number'] != ''){
				$txtt .= 'Purchase Order Number: ' . $extra['po_number'] . "\n";	
				}
				
				$txtt .= "-------------------------------------------------\n";
				$sub_total = 0;
				foreach($o as $item){

				$txtt .=  $item['serial_no'] . " - " . $item['description'] . " \t \t \t \t" .  "  $". number_format($item['cust_cost'], 2, '.', ',') . " Quantity: 1 \n";
				$sub_total += $item['cust_cost'];
				}
				
				// if there is a discount - apply it to the order items. 
			   	if($freight['discount_percent'] != 0){
				$sb = "Subtotal: $". number_format($sub_total, 2, '.', ',') . "\n";
				$sb .= "-- ". ($freight['discount_percent'] * 100) . "% Off -- \n";
				$ds = ($sub_total - ($sub_total * $freight['discount_percent']));
			    $sb .= "Discounted Subtotal: $". number_format($ds, 2, '.', ',') . "\n";
				} else {
				$sb = "Subtotal: "  .  "  $". number_format($sub_total, 2, '.', ','). "\n";
				$ds = $sub_total;
				}
			
				if($extra['will_call'] == 0){
				$fr = "+ Freight: " .  "  $". number_format($total_cost_cust, 2, '.', ','). "\n";
				} else {
				$fr = " No Freight - Will Call". "\n";
				$fr .= "Please Contact Vecchio Ranch at 559/528-9925 regarding Will Call" . "\n";
				}
				$t = number_format($freight['grand_total'], 2, '.', '');
				
				$half_deposit = number_format(($t / 2), 2, '.', '');
				$half_balance = $t - $half_deposit;  
				
				$order_total = "Total: " .  "  $". number_format($t, 2, '.', ','). "\n";
				
				$txtt .= "-------------------------------------------------\n";
				$txtt .= $sb;
				$txtt .= ($freight['box_price'] > 0 ? '+ Box Price: ' . $freight['box_price'] : '') . "\n";
				$txtt .= $fr;
				$txtt .= $order_total;
				
		        $this->load->library('pdf');
				$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
		

				// add a page
				$this->pdf->AddPage();
				$this->pdf->SetFont('helvetica', 'B', 12);
				$txt = "PAYMENT-BY-FAX  AUTHORIZATION  FORM";
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(5);
				
				
				$txt = "Order: " . $order_name . "\n";
				if($extra['po_number'] != ''){
				$txt .= 'Purchase Order Number: ' . $extra['po_number'] . "\n";	
				}
				$txt .= $sb;
				$txt .= ($freight['box_price'] > 0 ? '+ Box Price: ' . $freight['box_price'] : ''). "\n";
				$txt .= $fr;
				$txt .= $order_total;
				

				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(5);
				
				$this->pdf->SetFont('helvetica', '', 11);
				$txt = "Here  is  how  to  FAX  your  checks:\n\n";

				$txt .= "1) Submit  two  checks  for both the deposit and the balance due.  \n";
				$txt .= "2) Attach  signed  checks  to  this  form  in  the  space  provided  below. \n";
				$txt .= "3) Sign  the  authorization  below  and  fax  to  (855) 828 6708 \n";
				$txt .= "4) Retain  the  original  checks  for  your  records,  Do  Not  Mail. \n";
				$txt .= "**** If you have any questions contact Kara Bienz at (855) 819-7777 Ext. 2 **** ";
				
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(2);
				
				$this->pdf->Write($h=0, 'Delivery date can be chosen after initial check has been processed. An email notification will be sent to you on completion.: ', $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				$this->pdf->Ln(5);
				// $this->pdf->Line(101, 128, 124, 128, $style);
				
				$txt = "Attach Check Here (Deposit)  - $" . number_format($half_deposit, 2, '.', ',');
				
				$this->pdf->Cell(185, 70, $txt, 1, 1, 'C', 0, '', 0);
				
				$this->pdf->Ln(5);
				
				$txt = "Attach Check Here (Balance)  - $" . number_format($half_balance, 2, '.', ',');;
				
				$this->pdf->Cell(185, 70, $txt, 1, 1, 'C', 0, '', 0);
				
				$this->pdf->Ln(5);
			
				$this->pdf->Line(20, 220, 95, 220, $style);
				$this->pdf->Text(20, 220, 'Sign Here');
				
				
				$this->pdf->Line(20, 240, 95, 240, $style);
				$this->pdf->Text(20, 240, 'Printed Name');


				$this->pdf->Line(120, 220, 195 , 220, $style);
				$this->pdf->Text(120, 220, 'Signature Date');
				
				$txt = "Fax  your  payment  to:  (855) 828 6708";
				
				$this->pdf->Text(120, 240, $txt);
				// add a page
				$this->pdf->AddPage();
				
				$this->pdf->SetFont('courier', '', 11);
				$txt = "Products In Order \n";
				$txt .= "Order: " . $order_name . "\n";
				$txt .= $txtt;
				//TCPDF Example 003
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);


				$name = "Check_By_Fax-" . $order_name . ".pdf";
				$name = str_replace(' ', '_', $name);
				$this->pdf->Output($name, 'I');	


				//============================================================+
				// END OF FILE                                                
				//============================================================+  
		    }
		
			function gen_check_by_fax_pdf_qq($qq_id)
			    {

					$row = $this->tracking_model->get_quick_quote_items($qq_id);
					$order_name = "Vecchio Quote ".$row[0]['id'] . "-" . mb_substr($row[0]['quote_date'], 0,10)."  \n";
					$txtt = $order_name;
					$txtt .= "Quote Recipient: " . $row[0]['cust_name'] . ($row[0]['company_name'] != '' ? ' - '. $row[0]['company_name'] : '')." \n";
					$txtt .= "Submitted By: " . $row[0]['rep']['name'] . " on " . date("l F j, Y - g:i a", strtotime($row[0]['quote_date']))." \n";
					if(isset($row[0]['rep2']) && $row[0]['rep2']['name'] != ''){
					$txtt .= "And :". $row[0]['rep2']['name']."\n";	
					}
					if(isset($row[0]['po_number']) || $row[0]['po_number'] != ''){
					$txtt .= "Purchase Order Number: " . $row[0]['po_number'] . "\n";	
					}
					$txtt .= "-------------------------------------------------\n\n";
					 
					$count_i = count($row[0]['items']);
					for($i=0;$i<$count_i; $i++){				
					$txtt .= $row[0]['items'][$i]['description'] . " - PC ".$row[0]['items'][$i]['grow_yard']."-".$row[0]['items'][$i]['product_code']."-".$row[0]['items'][$i]['box_size']. "\n";
					$txtt .= "List Price: " . "$" . number_format($row[0]['items'][$i]['list_price'], 2, '.', ',') . "\n ";
					$txtt .= "X Quantity: " . $row[0]['items'][$i]['quantity'] . " \n ";
					$txtt .= "------------------- \n";
					$txtt .= "Price: " .  "$" . number_format($row[0]['items'][$i]['line_price'], 2, '.', ',') . "\n";
					$txtt .= "-- " . ((1 - $row[0]['multiplier']) * 100 ). "% Discount -- \n";
					$txtt .= "Your Price: $" . number_format($row[0]['items'][$i]['cust_line'], 2, '.', ',') . "\n\n";
					
					}					
					
					$order_t = number_format($row[0]['grand_total'], 2, '.', '');
					$half = ($order_t / 2);
					$order_total = "Total: " .  "$". $order_t. "\n";
					$half_deposit = number_format($half, 2, '.', '');
					$half_balance = number_format(($order_t - $half_deposit), 2, '.', '');
					
					$txtt .= "-------------------------------------------------\n";
					$txtt .= "Subtotal: "  .  "$". number_format($row[0]['sub_total_items'], 2, '.', ','). "\n";
					
					if($row[0]['boxed'] == 1){
					$txtt .= "+ Boxed Trees Fee: "  .  "$". 	number_format($row[0]['box_price'], 2, '.', ','). "\n"; 
					}					
					if($row[0]['disc'] > 0){
					$txtt .= "- Special Discount: " . "$" . number_format($row[0]['disc'], 2, '.', ',') . " - " .$row[0]['who_disc'] . "\n"; 
					}
					
					if($row[0]['will_call'] == 0){
					$txtt .= "+ Freight: " .  "  $". number_format($row[0]['ship_cost'], 2, '.', ','). "\n";
					} else {
					$txtt .= "No Freight - Will Call / Customer Pickup" . "\n";
					$txtt .= "Please Contact Vecchio Ranch at 559/528-9925 regarding Will Call" . "\n";
					}
					$txtt .= $order_total;

			        $this->load->library('pdf');
					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));


					// add a page
					$this->pdf->AddPage();
					$this->pdf->SetFont('helvetica', 'B', 12);
					$txt = "PAYMENT-BY-FAX  AUTHORIZATION  FORM";
					$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$this->pdf->Ln(5);


					$txt = $order_name;
					if(isset($row[0]['po_number']) || $row[0]['po_number'] != ''){
					$txt .= "Purchase Order Number: " . $row[0]['po_number'] . "\n";	
					}
					$txt .= "Subtotal: "  .  "$". number_format($row[0]['sub_total_items'], 2, '.', ','). "\n";
					
					if($row[0]['boxed'] == 1){
					$txt .= "+ Boxed Trees Fee: "  .  "$". 	number_format($row[0]['box_price'], 2, '.', ','). "\n"; 
					}					
					if($row[0]['disc'] > 0){
					$txt .= "- Special Discount: " . "$" . number_format($row[0]['disc'], 2, '.', ',') . " - " .$row[0]['who_disc'] . "\n"; 
					}
					
					if($row[0]['will_call'] == 0){
					$txt .= "+ Freight: " .  "  $". number_format($row[0]['ship_cost'], 2, '.', ','). "\n";
					} else {
					$txt .= "No Freight - Will Call / Customer Pickup " . "\n";
					}
					
					$txt .=	$order_total;

					$this->pdf->SetFont('helvetica', '', 11);
					$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$this->pdf->Ln(5);

					$this->pdf->SetFont('helvetica', '', 11);
				//	$txt = "Quote Recipient: David Customer - David Lane Architecture \n";
				//	$txt .= "Submitted By: Janice Rep on ". date("l F j, Y - g:i a", strtotime($row[0]['quote_date']))." \n";
				 	$txt = "Quote Recipient: " . $row[0]['cust_name'] . ($row[0]['company_name'] != '' ? ' - '. $row[0]['company_name'] : '')." \n";
					$txt .= "Submitted By: " . $row[0]['rep']['name'] . " on " . date("l F j, Y - g:i a", strtotime($row[0]['quote_date']))." \n";
										
					$txt .= "Here  is  how  to  FAX  your  checks:\n\n";

						$txt .= "1) Submit two checks for both the deposit and the balance due.  \n";
						$txt .= "2) Attach  signed  checks  to  this  form  in  the  space  provided  below. \n";
						$txt .= "3) Sign  the  authorization  below  and  fax  to  (855) 828 6708 \n";
						$txt .= "4) Retain  the  original  checks  for  your  records,  Do  Not  Mail. \n";
						$txt .= "**** If you have any questions contact Kara Bienz at (855) 819-7777 Ext. 2 **** ";

					$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$this->pdf->Ln(2);

					$this->pdf->Write($h=0, 'Delivery date can be chosen after initial check has been processed. An email notification will be sent to you on completion. ', $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					
					$this->pdf->Ln(5);


					$txt = "Attach Check Here (Deposit)  - \n" . "$". $half_deposit;

					$this->pdf->Cell(185, 70, $txt, 1, 1, 'C', 0, '', 0);

					$this->pdf->Ln(5);
					
					$this->pdf->Line(101, 125, 124, 125, $style);

					$txt = "Attach Check Here (Balance)  - \n" . "$". $half_balance;

					$this->pdf->Cell(185, 70, $txt, 1, 1, 'C', 0, '', 0);

					$this->pdf->Ln(5);

					$this->pdf->Line(20, 220, 95, 220, $style);
					$this->pdf->Text(20, 220, 'Sign Here');


					$this->pdf->Line(20, 240, 95, 240, $style);
					$this->pdf->Text(20, 240, 'Printed Name');


					$this->pdf->Line(120, 220, 195 , 220, $style);
					$this->pdf->Text(120, 220, 'Signature Date');

					$txt = "Fax  your  payment  to:  (855) 828 6708";

					$this->pdf->Text(120, 240, $txt);
					// add a page
					$this->pdf->AddPage();

					$this->pdf->SetFont('courier', '', 11);
					$txt = "Order Details \n";
					$txt .= $txtt;
					//TCPDF Example 003
					$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);


					$name = "Check_By_Fax-" . $row[0]['id'] . "-" . mb_substr($row[0]['quote_date'], 0,10) . ".pdf";
					$name = str_replace(' ', '_', $name);
					$this->pdf->Output($name, 'I');	


					//============================================================+
					// END OF FILE                                                
					//============================================================+  
			    }
			
			
			function generate_order_quote($order_id){
				
				// pre means that the quote should not try to grab transaction info. 
				$o = $this->tracking_model->get_order_images_info($order_id, 'pre');
				$s = $this->tracking_model->get_shipping_info($order_id);
				
				$order_name = $o['order_name'];
				$this->load->model('shipping_model');
				$ship_zip = $s['ship_zip'];
				
				$freight = $this->shipping_model->get_shipping_cost($order_id, 93292, $ship_zip, $heavy = false );

				$total_cost_cust = $freight['total_cost_cust'];
				$expires = date("l F j, Y g:i a", strtotime($o['client_info']['expire_date']));
				$txtt = "Vecchio Quote - Expires " . $expires . "\n\n";
				$txtt .= $o['client_info']['company_name'].' - '.$o['client_info']['fname'] . " " . $o['client_info']['lname'];
				$txtt .= "\n\n\n";
				$txtt .= "Items In Quote: \n";
				$txtt .= "---------------------------------------------------------------------------------------------------------------------\n";
				$sub_total = 0;
				foreach($o['order_items'] as $item){

				$txtt .=  $item['description']  . " - " . $item['specs'] . " - " . $item['serial_no'] . " \t " .  "  $". number_format($item['cust_cost'], 2, '.', ',') . "\n";           
				$sub_total += $item['cust_cost'];
				}
				
				if($o['boxed'] == 1){
				$txtt .= "+ Tree Boxes: $". number_format($o['shipping_info']['box_price'], 2, '.', ',') . "\n";	
				}			
				
				// if there is a discount - apply it to the order items. 
			   	if($freight['discount_percent'] != 0){
				$sb = "Subtotal: $". number_format($sub_total, 2, '.', ',') . "\n";
				$sb .= "-- ". ($freight['discount_percent'] * 100) . "% Off -- \n";
				$ds = ($sub_total - ($sub_total * $freight['discount_percent']));
			    $sb .= "Discounted Subtotal: $". number_format($ds, 2, '.', ',') . "\n";
				} else {
				$sb = "Subtotal: "  .  "  $". number_format($sub_total, 2, '.', ','). "\n";
				$ds = $sub_total;
				}
				
				
				
				$freight = "+ Freight: " .  "  $". number_format($total_cost_cust, 2, '.', ','). "\n";
				$t = $ds + $total_cost_cust;
				
				$order_total = "Total: " .  "  $". number_format($t, 2, '.', ','). "\n";
				$txtt .= "---------------------------------------------------------------------------------------------------------------------\n";
				$txtt .= $sb;
				$txtt .= $freight;
				$txtt .= $order_total;
				$this->load->library('pdf');
				$this->pdf->AddPage();

				$this->pdf->SetFont('helvetica', 'B', 12);
				
				$this->pdf->Write($h=0, $txtt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(4);
				
				$txt = "The items in your order will be placed on hold for you until " . $expires . " after which the products will be placed back on the market for the public to purchase. Log in to your account at vecchiotrees.com to view your quote online and complete the order. Your username is ".$o['client_info']['usern_email']." and your password has been sent to your email address previously. If you cannot log in please contact us. To complete your order you can either pay directly on the site with a credit/debit card or submit payment via check by fax.  ";
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);				
				
				foreach($o['order_items'] as $item){
				$this->pdf->AddPage();

				$this->pdf->SetFont('helvetica', 'B', 14);
				$txt = "Quote Item Details";
						//TCPDF Example 003
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				// print a block of text using Write()
				// Image example with resizing
				$this->pdf->Ln(10);
				$this->pdf->SetFont('helvetica', 'B', 11);
				$this->pdf->Cell(0, 0, 'Tagged For: '.$o['client_info']['company_name'].' - '.$o['client_info']['fname'] . " " . $o['client_info']['lname'], 0, 1, 'L', 0, '', 0);
				$this->pdf->SetFont('helvetica', '', 11);
				$this->pdf->Cell(0, 0, 'Product Serial Number: ' . $item['serial_no'], 1, 1, 'L', 0, '', 0);
				$this->pdf->Cell(0, 0, 'Product Description: '. $item['description'] , 1, 1, 'L', 0, '', 0);
				$this->pdf->Cell(0, 0, 'Order Batch: ' . $o['order_name'], 1, 1, 'L', 0, '', 0);
				if(isset($item['files']) && !empty($item['files'])){
				$base_url = base_url() . "_fdr/" . $item['files'][0]['file_name'];
				$this->pdf->Image($base_url, 15, 90, 186, "", 'JPG', $base_url, '', true, 200, '', false, false, 1, false, false, false);
				$this->pdf->SetFont('helvetica', '', 9);
				$this->pdf->SetY(-50);
				// ---------------------------------------------------------
	$this->pdf->Cell(0, 0, 'click on picture to view larger photo', 0, 1, 'L', 0, '', 0);
				
				} else {
				$this->pdf->Cell(0, 0, 'No Image Available', 1, 1, 'L', 0, '', 0);	
				}
				
				} // end foreach for each product
				
				$name = "Vecchio_Quote-" . $order_name . ".pdf";
				$name = str_replace(' ', '_', $name);
				$this->pdf->Output($name, 'I');
				
			}
			
			function quick_quote_pdf($quote_id){
				
				$row = $this->tracking_model->get_quick_quote_items($quote_id);
				
				$this->load->library('pdf');
				
				$this->pdf->AddPage();
				$this->pdf->SetFont('helvetica', 'B', 11);
				$txt = $row[0]['rep']['name']."\n";	
				$txt .= "VECCHIO Trees \n";
				$txt .= "4285 Spyres Way \n";
				$txt .= "Modesto, CA 95356 \n";
				$txt .= "C: ". $row[0]['rep']['phone']."\n";
				$txt .= "T: (855) 819-7777 \n";
				$txt .= "F: (855) 828-6708 \n";
				$txt .= $row[0]['rep']['email']."\n";	
				
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(5);
				
				$dt = date("F j, Y", strtotime($row[0]['quote_date']));
				$this->pdf->Write($h=0, $dt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(5);

				$txt = $row[0]['cust_name'] ." \n";
				$txt .= $row[0]['company_name']." \n";
				$txt .= $row[0]['bill_address'] . " \n";
				$txt .= $row[0]['bill_city'].", ".$row[0]['bill_state'] ." ". $row[0]['bill_zip'] ." \n";
				$txt .= "T: ".$row[0]['phone']." \n";
				$txt .= (!empty($row[0]['fax']) ? "F: " . $row[0]['fax'] : "");
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(5);
					// set some text to print
					
				
		
				$txt = "Dear ".$row[0]['cust_name'].", ";
					//TCPDF Example 003
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(4);
				$txt = "The following quote was written exclusively for ".$row[0]['cust_name']." by a certified Vecchio Trees account representative. Pricing and availability is applicable until ". date("F j, Y", strtotime($row[0]['expire_date'])).". To secure your order, please sign proposal and e-mail directly to your account representative. If you would like to place your order online, go to WWW.VECCHIOTREES.COM to view our inventory and custom select from our product lines.\n\nThank you for your business\n\nSincerely \n\n\n" .$row[0]['rep']['name'] ;
				$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$this->pdf->Ln(10);
					// set some text to print x 100 y 50
					// set color for background
				if($row[0]['memo'] != ''){
				
				// $this->pdf->SetFillColor(220, 255, 220);
				
				$note = "----- Note ----- \n" . $row[0]['memo'] . "\n - " . $row[0]['rep']['name'];	
					$this->pdf->MultiCell(
					$w = 0,
					$h = 0,
					$txt = $note,
					$border = 0,
					$align = 'C',
					$fill = false,
					$ln = 1,
					$x = 120,
					$y = 50,
					$reseth = true,
					$stretch = 0,
					$ishtml = false,
					$autopadding = true,
					$maxh = 0,
					$valign = 'T',
					$fitcell = false 
					);
				}
				
				
				$this->pdf->AddPage();
				
				$count_items = count($row[0]['items']);
				$h = "";
				$h .=  "<table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" >";
				$base = base_url() . "_images/_products/";
				if(isset($row[0]['po_number']) || $row[0]['po_number'] != ''){
				$h .= "<tr><td colspan=\"2\">Purchase Order Number: " . $row[0]['po_number'] . "</td></tr>";	
				}
				$h .= "<tr><td colspan=\"2\"><hr /></td></tr>";
				
			   for($i=0;$i<$count_items;$i++){
			    
				$h .= "<tr height=\"200\"><td style=\"text-align:left; width:600px;\"><img src=\"".$base. $row[0]['items'][$i]['product_code'] . ".jpg\" width=\"440\" /></td> ";
				$h .= "<td style=\" width:1500px; \" >";
				$h .= $row[0]['items'][$i]['description'] . " <span style='font-size:10px;'> - PC ".$row[0]['items'][$i]['grow_yard']."-".$row[0]['items'][$i]['product_code']."-".$row[0]['items'][$i]['box_size']."</span><br />";
				$h .= "List Price: " . "$" . number_format($row[0]['items'][$i]['list_price'], 2, '.', ',') . " ";
				$h .= "<b>x</b> Quantity: " . $row[0]['items'][$i]['quantity'] . "<br />";
				$h .= "<hr noshade size=1 width=\"53%\">";
				$h .= "Price: <del>" .  "$" . number_format($row[0]['items'][$i]['line_price'], 2, '.', ',') . "</del><br >";
				$h .= " <span style=\"font-size:122px;\">-- " . ((1 - $row[0]['multiplier']) * 100 ). "% Discount --</span><br >";
				$h .= "Your Price: <u>$" . number_format($row[0]['items'][$i]['cust_line'], 2, '.', ',') . "</u><br >";
				$h .= "<p style=\"font-size:100px; color:C0C0C0;\">".$row[0]['items'][$i]['text_description']." Exposure: ".$row[0]['items'][$i]['exposure'].". Watering: ".$row[0]['items'][$i]['watering']."</p>";
				$h .= "</td></tr>";
				$h .= "<tr><td colspan=\"2\"><hr /></td></tr>";
				if($i == 2 || $i == 5 || $i == 11){
			 	$h .= "<tr><td><br /><br /><br /></td></tr>";
				}
				}
				$h .= "<tr><td colspan=\"2\">";
				$h .= "Sub Total: " . "$" . number_format($row[0]['sub_total_items'], 2, '.', ',') . "<br >";
				if($row[0]['boxed'] == 1){	
				$h .= "+ Boxed Trees Fee: " . "$" . number_format($row[0]['box_price'], 2, '.', ',') . "<br >";		
				}
				if($row[0]['disc'] > 0){
				$h .= "<b>- Special Discount: " . "$" . number_format($row[0]['disc'], 2, '.', ',') . " - " .$row[0]['who_disc'] . "</b><br >"; 
				}
				$h .= "+ Shipping Estimate: " . "$" . number_format($row[0]['ship_cost'], 2, '.', ',') . "* <br >";
				$h .= "<hr />";
				$h .= "<b>Grand Total: " . "$" . number_format($row[0]['grand_total'], 2, '.', ',') . "</b><br >";
				if($row[0]['will_call'] == 0){
				$h .= "<span style=\"font-size:100px;\">*Shipping to: " . $row[0]['ship_zip'] . ", approximately " . $row[0]['distance'] . " miles from  VECCHIO shipping facility.</span><br /><br />";
				} else {
				$h .= "<span style=\"font-size:100px;\">* Customer Pickup / Will Call - No Freight. Please Contact Vecchio Ranch at 559/528-9925 regarding Will Call </span><br /><br />";
				}
			    $h .= "<br /><br />";
				$h .= "</td></tr>";
				$h .="</table>";
				$this->pdf->writeHTML($h, true, false, true, false, '');	
				
			

				$this->load->model('site_model');
				$w = $this->site_model->display_warranty();
				$w .= "<p>&nbsp;</p>";
				$w .= "<hr noshade size=1 width=\"45%\">";
				$w .= "Signature<br /><br />";
				$w .= "<hr noshade size=1 width=\"45%\">";
				$w .= "Date";
					
				$this->pdf->writeHTML($w, true, false, true, false, '');	
				
				
				$str = strtoupper(str_replace(' ', "-", $row[0]['cust_name']));
				$name = "VECCHIO_QUOTE-". $str .".pdf";
				$name = str_replace(' ', '_', $name);
				$this->pdf->Output($name, 'I');
				
			//	echo $h;
				
			}
			
			function gen_warranty(){
				
				 	$this->load->library('pdf');
				
					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));

					// add a page
					$this->pdf->AddPage();
					$this->pdf->SetFont('helvetica', '', 11);
					$this->pdf->Ln(3);
					
					$this->load->model('site_model');
					$w = $this->site_model->display_warranty();
					$this->pdf->writeHTML($w, true, false, true, false, '');	
					
					$this->pdf->Output('VECCHIO-WARRANTY', 'D');
					
						
			}



}



?>