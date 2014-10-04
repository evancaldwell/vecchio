<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_pdf extends CI_Controller {
		
	function __construct()
	{
		parent::__construct();
 		$this->load->library('form_validation');
		$this->load->model('User_model');
		$this->load->model('Site_model');
		$this->load->model('product_model');
		$this->load->helper('html', 'download');
	}
	
	function tcpdf()
	    {
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

			$txt = "Sample Company \n";
			$txt .= "344 West Hannover \n";
			$txt .= "Van Ness, CA 95356 \n";
			$txt .= "T: (559) 443 4343 \n";
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$this->pdf->Ln(5);
	
			
			// set some text to print
			$txt = "Dear Tyler,";
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
			$this->pdf->Ln(25);
			
			$this->pdf->SetFont('helvetica', 'B', 14);
			$txt = "Transaction Confirmation Sheet";
			//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$this->pdf->Ln(10);
			$this->pdf->SetFont('courier', '', 11);

			$txt = "Transaction Information \n";
			$txt .= "------------------------------------------------------------------------------\n";
			$txt .= "Transaction ID: JKOKJODIFF3233jk34j34 \n";
			$txt .= "Freight ($434.33) \n";
			$txt .= "Total: $3,434.33 \n";
			$txt .= "Pay Method: eCheck \n";
			$txt .= "Payment Date: AUG 12 2011 - 3:33 PM \n";
			$txt .=  "------------------------------------------------------------------------------\n";
			$txt .= "Billing Information \n";
			$txt .= "Jess Swinnerton \n";
			$txt .= "3344 Westover Lane \n";
			$txt .= "Sanger, CA 34434 \n";
			$txt .=  "------------------------------------------------------------------------------\n";
			$txt .= "Freight Information \n";
			$txt .= "Jess Swinnerton \n";
			$txt .= "3344 Westover Lane \n";
			$txt .= "Sanger, CA 34434 \n";
			$txt .= "Freight distance: 234.34 Miles\n";
			$txt .= "Trucks to Ship: 3\n";
			$txt .=  "------------------------------------------------------------------------------\n";
			
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$this->pdf->AddPage();

			$this->pdf->SetFont('helvetica', 'B', 14);
			$txt = "Order Item Details";
					//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			// print a block of text using Write()
			// Image example with resizing
			$this->pdf->Ln(10);
			$this->pdf->SetFont('helvetica', 'B', 11);
			$this->pdf->Cell(0, 0, 'Sold to: TYLER PENNEY - MILLER&PENNEY SCIENTIFIC SOLUTIONS - $450.00', 0, 1, 'L', 0, '', 0);
			$this->pdf->SetFont('helvetica', '', 11);
			$this->pdf->Cell(0, 0, 'Product Serial Number: 120-123-36-T001-R003-EFE1DF', 1, 1, 'L', 0, '', 0);
			$this->pdf->Cell(0, 0, 'Product Description: Manzanillo ', 1, 1, 'L', 0, '', 0);
			$this->pdf->Cell(0, 0, 'Order Batch: 34-DFJDKFDKK1', 1, 1, 'L', 0, '', 0);
			$base_url = base_url() . "_fdr/2d948e49ba23a3d85a37d7362f2b949f.jpg";
			$this->pdf->Image($base_url, 15, 90, 186, "", 'JPG', $base_url, '', true, 200, '', false, false, 1, false, false, false);
			$this->pdf->SetFont('helvetica', '', 9);
			$this->pdf->SetY(-50);
			// ---------------------------------------------------------
$this->pdf->Cell(0, 0, 'click on picture to view larger photo', 0, 1, 'L', 0, '', 0);

			
			$this->pdf->AddPage();


			$this->pdf->SetFont('helvetica', 'B', 14);
			$txt = "Warranty Information";
					//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			// print a block of text using Write()
			// Image example with resizing
			$this->pdf->Ln(10);
			
			$this->pdf->SetFont('helvetica', '', 12);
			$txt = "Vecchio Trees LLC is unable to control tree mortalities after the sale. Vecchio Trees LLC is only able to guarantee trees with the use of proper irrigation, drainage and adequate pruning upon planting during delivery (unless tress were laced/pruned for shipping). The tree warranty is for one (1) year, which includes a one-time product replacement, applicable to Olive trees only. Subject to this warranty, risk of loss shall pass to the Buyer upon delivery, if shipping is contracted by Vecchio Trees LLC, or upon shipment, if shipping is contracted by Buyer.  Freight, labor, equipment and other installations costs associated the warranty replacement product IS NOT INCLUDED in this Warranty.   Such costs are to be paid by the Buyer.";
					//TCPDF Example 003
			$this->pdf->Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			// email that succa
			
			// email stuff (change data below)
			$to = "tylerpenney@hotmail.com"; 
			$from = "kara@vecchiotrees.com"; 
			$subject = "Sample Attachement"; 
			$message = "<p>Please see the attachment.</p>";

			// a random hash will be necessary to send mixed content
			$separator = md5(time());

			// carriage return type (we use a PHP end of line constant)
			$eol = PHP_EOL;

			// attachment name
			$filename = "example.pdf";

			// encode data (puts attachment in proper format)
			//Close and output PDF document
			$pdfdoc = $this->pdf->Output('', 'S');
			$attachment = chunk_split(base64_encode($pdfdoc));

			// main header
			$headers  = "From: ".$from.$eol;
			$headers .= "MIME-Version: 1.0".$eol; 
			$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

			// no more headers after this, we start the body! //

			$body = "--".$separator.$eol;
			$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
		//	$body .= "This is a MIME encoded message.".$eol;

			// message
			$body .= "--".$separator.$eol;
			$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
			$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
			$body .= $message.$eol;

			// attachment
			$body .= "--".$separator.$eol;
			$body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
			$body .= "Content-Transfer-Encoding: base64".$eol;
			$body .= "Content-Disposition: attachment".$eol.$eol;
			$body .= $attachment.$eol;
			$body .= "--".$separator."--";

			// send message
			mail($to, $subject, $body, $headers);
			


			//============================================================+
			// END OF FILE                                                
			//============================================================+  
	    }
}