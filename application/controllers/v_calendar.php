<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V_calendar extends CI_Controller {
		
	function __construct()
	{
		parent::__construct();
		$this->load->helper('download');
		$this->load->helper('html');
		$this->load->model('shipping_model');
		$this->load->model('User_model');
	    $this->User_model-> is_logged_in_admin();
	}
	
	function index(){
		
		
		$this->load->library('calendar', '', 'cal');
		$this->cal->weeknumbers = 'right';
		$this->cal->basecolor = '3399CC';
		$this->cal->base_url = base_url();
		
		$colors['shipped'] = "#FF9876";
		$colors['pend'] = "#9BFFAE";
		
		$googlemap = "";
		$ev = $this->shipping_model->get_calendar_ship();
		foreach($ev as $e){
		$goog = "<a href='http://maps.google.com/?q=".$e['ship_address'] . ", " . $e['ship_city'] . ", " . $e['ship_state'] . ", " . $e['ship_zip']."' target='_blank'>Google Map &raquo;</a>";
		$breakdown = $e['breakdown'];
		$bd = "";
		if($e['rep_fname'] != NULL){
			$rep = $e['rep_fname'] . " " . $e['rep_lname'];
		} else {
			$rep = "No Assigned Rep";
		}
		foreach($breakdown as $b){
		$bd .= "Product Code: " . $b['product_code'] . " Count: " . $b['num_products'] . "<br />";	
		}
		$link = '';
		$color = '';
		$type_title = '';
		if($e['type'] == 'order'){
		 $link = base_url() . "index.php/vecchio_admin/order_details/" . $e['order_id'];
		 $color = ($e['status'] == 2 ? $colors['pend'] : $colors['shipped']);
		 $type_title = 'S-O ';
		} else {
		 $link = base_url() . "index.php/vecchio_admin/quick_quotes/" . $e['qq_id'];
		 $color = '#9BFFAE';
		 $type_title = 'R-Q ';			
		}
			$this->cal->addEvent(
				array(
					"title"=>$e['lname'] . " - " . $e['company_name'], 
					"from"=>$e['ship_date'],
					"to"=>$e['ship_date'],
					"color"=>$color,
					"location"=>$e['ship_address'] . " " . $e['ship_city'] . ", " . $e['ship_state'] . " " . $e['ship_zip'] . " " . $goog,
					"details"=>$type_title ."<b>Order Id:</b> " .$e['order_name']." <b>Rep:</b> ".$rep. "<br /> <b>Project:</b> ". $e['location'] . " <b>Job Phone: </b>" . $e['location_phone'] . "<br /> <b>Space Needed:</b> " . $e['freight']['actual_trucks'] . " Trucks" . "<br /><b>Order Breakdown:</b> <br />" . $bd ,
					"link"=>$link,
					"linktext" => "View Order Details"
				)
			);
			
			
				
		}
		
		//print_r($ev);
		/* Whole shebang - for reference I'm keeping this here. "
		$this->cal->addEvent(
			array(
				"title"=>"Event w/all values",
				"from"=>date('Y')."-".date('n')."-24",
				"to"=>date('Y')."-".date('n')."-28",
				"starttime"=>"5:30am",
				"endtime"=>"7:30pm",
				"color"=>"#D8E5F9",
				"location"=>"Wisconsin Rapids, WI",
				"details"=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sagittis viverra imperdiet. Sed euismod molestie. ",
				"link"=>"http://www.klovera.com"
			)
		);
		*/
		echo $this->cal->showcal();
		
	}
	
	function test_calendar(){
		$ev = $this->shipping_model->get_calendar_ship();
		echo '<pre>';
		print_r($ev);
		echo '</pre>';
		$ev = $this->shipping_model->get_calendar_ship_qq();
		echo '<pre>';
		print_r($ev);
		echo '</pre>';		
	}
	
	
}