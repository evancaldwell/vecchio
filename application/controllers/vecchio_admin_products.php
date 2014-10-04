<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vecchio_admin_products extends CI_Controller {

	var $sesh;

			
	function __construct(){
		parent::__construct();
 		$this->load->library('form_validation');
		$this->load->model('User_model');
		$this->load->model('product_model');
		$this->User_model-> is_logged_in_admin();
	    $sesh['user_type'] = $this->session->userdata('user_type');
	    $sesh['user_id'] =  $this->session->userdata('user_id');

		$this->load->helper('form');
		$this->load->helper('file');
        $this->load->helper('url');
		$this->load->helper('html');
		//$this->User_model->is_logged_in_admin();
	}
	
	function index($error = ''){
		$data = array();
		$data['main_section'] = '_content/products_admin';
		$data['error'] = $error; 
		$this->load->view('admin_template', $data);	
	}
	
	function return_message($message, $info){
		
		$data['main_section'] = '_content/message';
		$data['message'] = $message;
		$data['info'] = $info;
		$this->load->view('admin_template', $data);
	}
	
	function addnew($error = ''){
		$data = array();
		$data['main_section'] = '_content/products_admin_addnew';
		$data['error'] = $error;
		$data['rand_seed'] = $this->product_model->genRandomString();

		if($query = $this->product_model->get_grow_yards()){
			$data['grow_yards'] = $query;
		}
		if($query = $this->product_model->get_product_type()){
			$data['product_type'] = $query;
		}
		$this->load->view('admin_template', $data);
	}
	
	

	function create(){
		$serial = $this->input->post('whole_serial');
		$new_product_insert_data = array(
			'product_type_id' => $this->input->post('product_type_id'),
			'grow_yard_id' => $this->input->post('grow_yard_id'),
			'serial_no' => $serial,
			'public_view' => 1						
		);
		if(strlen($serial) < 9){
			
		} else {
		$this->product_model->create_product($new_product_insert_data);
		$product_id = $this->db->insert_id();
		$this->load->model('Product_image_model');
		if($_FILES['userfile']['error'] != 4){
			$this->Product_image_model->do_upload($product_id);
		} else {
			echo 'no photo to load';
		}
		$message = "Successful Product Upload";
		$info = array();
		$base = base_url();
		$info[0] = "Please choose an option:<br />";
		$info[1] = "<a href='".$base."index.php/vecchio_admin_products/addnew/'>Add Another Product</a><br />";
		$info[2] = "<a href='".$base."index.php/vecchio_admin_products/'>View Products</a><br />";
	//$this->addnew();
	   $this->return_message($message, $info);
		}
	}
	
	function check_serial(){
		$get_result = $this->product_model->check_serial_availablity();

			if(!$get_result )
			echo '<span style="color:#f00">Serial number already in use. </span>';
			else
			echo 'Serial number available';
	}
	
	function check_shipping_date(){
		$this->load->model('shipping_model');
		$ship_date = $this->input->post('ship_date');
		
		$date = date('Y-m-d',strtotime($ship_date));
	//	$grow_yard = $this->input->post('grow_yard');
		$get_result = $this->shipping_model->check_date_aval($date, $grow_yard = '');
	    // $grow_yard_name = $this->product_model->get_grow_yard_name($grow_yard);
	    $date_new = date('l F j, Y',strtotime($ship_date));
		
		// get the setting for how many shipments per day
		$vec = $this->User_model->vecchio_settings();
		$ship_per_day = $vec['ship_per_day'];
			if($get_result[0]['cn'] >= $ship_per_day){
			echo '<span style="color:#f00">Shipping Booked On '. $date_new .   ', Choose Another</span>';
			} else {
			echo '<span style="color:#0c0">Shipping Available On '. $date_new . ' </span>';
			}
	
	}
	
	function get_gy_options(){
		$this->load->model('prox_model');
		$zip = $this->input->post('zip_code');
		$product_id = $this->input->post('product_id');
		$product_code = $this->product_model->get_product_id($product_id);
		$products = $this->prox_model->get_prox($zip,$product_id);
		$b = base_url();
		$return = '<label>Choose Shipping Date</label><input type="text" name="ship_date" value="" id="ship_date" class="required"  />';
		$return .= '<span id="Check_Date"><img src="'.$b.'_images/ajax-loader.gif" alt="Ajax Indicator" /></span>';
		$return .= '<h3>Choose A Grow Yard For Product '.$product_code[0]['product_code'] .'</h3>';
	//	echo "<pre>";
	//	print_r($products);
		$count = count($products);
		$product = (isset($product_id) ? '/'.$product_id : '');
		for($i=0; $i<$count; $i++){
			$ch = ($i == 0 ? "checked" : '');
			$cp = count($products[$i]['products_in_grow_yard']);

			$return .= "<p><input type=\"radio\" name=\"grow_yard\" value=\"".$products[$i]['grow_yard_id']."\" ".$ch." />" . $products[$i]['grow_yard_name'] . " - Aprox. <strong>" . round($products[$i]['distance'], 2) . '</strong> Miles To Ship. <strong>' . $cp . ' of Product Code '.$product_code[0]['product_code'].' Available</strong> '.
	"<a class=\"iframes\" href=\"". $b . "index.php/image_bank/bygrowyard/" . $products[$i]['grow_yard_id'] . $product . "\">View Available Products</a></p>";
	}
	echo $return;
	}
	
	
	function check_short(){
		$get_result = $this->product_model->get_short_gy($this->input->post('grow_id'));
		echo $get_result[0]['short_name'];
	}
	
	function check_prod_id(){
		$get_result = $this->product_model->get_product_id($this->input->post('prod_id'));
		echo $get_result[0]['product_code'];
	}
	
	function product_display($category){
		$data = array();
		$data['main_section'] = '_content/products_view';
		$data['error'] = $error; 
		if($query = $this->product_model->get_product_category($category)){
			$data['cat_id'] = $query;
			foreach($cat_id as $row){
				$category_id = $cat_id['id'];
			}
			
			if($query = $this->product_model->get_product($category_id));
		}
		$this->load->view('admin_template', $data);
	}
	
	function product_by_grow_yard(){
	 $grow_yard_id =	$this->uri->segment(3);
	    $this->load->model('tracking_model');
		$data['products'] = $this->product_model->get_products_by('grow_yard',$grow_yard_id);
	//	echo "<pre>"; print_r($data['products']); echo "</pre>";
		$data['main_section'] = '_content/list_products';
		$data['breadcrumb'] = "By Grow Yard: " . $this->product_model->get_grow_yard_name($grow_yard_id);
		$data['aval_tagged'] = $this->tracking_model->get_aval_tagged();
		$this->load->view('admin_template', $data);

	}
	
	function product_status(){
		$status = $this->uri->segment(3);	 
	    $this->load->model('tracking_model');
		$data['products'] = $this->product_model->get_products_by($status,0);
	//	echo "<pre>"; print_r($data['products']); echo "</pre>";
	// not in use;
		$data['main_section'] = '_content/list_ind_product';
		switch($status){
			case 'avail':
			$br = "All Available Products";
			break;
			case 'tagged':
			$br = "Tagged Products";
			break;
			case 'sold':
			$br = 'Sold Products';
			break;
			case 'shipped':
			$br = "Shipped Products";
			break;
		}
		$data['breadcrumb'] = $br;
		$data['aval_tagged'] = $this->tracking_model->get_aval_tagged();
		$this->load->view('admin_template', $data);

	}
	
	function products(){
		
		$data['products'] = $this->product_model->get_product_type('array');
		$data['main_section'] = '_content/list_all_products';
		$this->load->view('admin_template', $data);	
	}
	
	function product_by_type(){
	    $product_id =	$this->uri->segment(3);
	    $this->load->model('tracking_model');
		$data['products'] = $this->product_model->get_products_by('products',$product_id);
		//echo "<pre>"; print_r($data['products']); echo "</pre>";
		$data['main_section'] = '_content/list_products';
		$pn = $this->product_model->get_product_name($product_id);
		$data['breadcrumb'] = "By Product Type: " . $pn['product_code'] . " - " . $pn['description'] . " - " . $pn['specs'] . " $" . number_format($pn['list_price'], 2, '.', ',');
		$data['aval_tagged'] = $this->tracking_model->get_aval_tagged();
		$this->load->view('admin_template', $data);
	}
	
	function show_ind_product(){
		$product_id =	$this->uri->segment(3);
	    $this->load->model('tracking_model');
		$data['products'] = $this->product_model->get_products_by('product',$product_id);
		//echo "<pre>"; print_r($data['products']); echo "</pre>";
		$data['main_section'] = '_content/list_ind_product';
		$data['breadcrumb'] = $data['products'][0]['serial_no'];
		$data['aval_tagged'] = $this->tracking_model->get_aval_tagged();
		$this->load->view('admin_template', $data);
	}
	
	function grow_yards(){
		$data['grow_yards'] = $this->product_model->get_grow_yards_arr();
		$data['main_section'] = '_content/list_grow_yards';
		$this->load->view('admin_template', $data);	
	}
	

	/*
	function olives($error = ''){
		product_display('olive');
	}
	function specimens($error = ''){
		product_display('specimens');
	}
	function palms($error = ''){
		product_display('palms');
	}
	function others($error = ''){
		product_display('other');
	}
	*/
	function display_error($error, $link, $link_name){
		$data['error'] = $error; 
		$data['link'] = $link;
		$data['link_name'] = $link_name;
		$data['main_section'] = '_main_section/error_m';
		$this->load->view('admin_template', $data);	
	}
	
	function delete_file(){
	 $delete_id =  $this->input->post('delete_id');
	 $delete_name = $this->input->post('delete_name');
	 $delete_name_icon = $this->input->post('delete_name_icon');
	 $client_id = $this->input->post('client_id');

	 $this->db->where('id', $delete_id);
     $isdeleted = $this->db->delete('images');

   	 if($isdeleted){
	    	$base = base_url();
			 @unlink($base .'_fdr/'. $delete_name);
			 @unlink($base .'_fdr/thumbs/'. $delete_name_icon);
	 		 	
				$message = "Photo Deleted";
				$info = array();
				$base = base_url();
				$info[0] = "Please choose an option:<br />";
				$info[1] = "<a href='".$base."index.php/vecchio_admin_products/addnew/'>Add Another Product</a><br />";
				$info[2] = "<a href='".$base."index.php/vecchio_admin_products/'>View Products</a><br />";
				//$this->addnew();
			   $this->return_message($message, $info);
	 }
	}
	

	
	function add_another_img(){
		
		$this->load->model('Product_image_model');
		$product_id = $this->input->post('product_id');
		if($_FILES['userfile']['error'] != 4){
			$this->Product_image_model->do_upload($product_id);
		} else {
			echo 'no photo to load';
		}
		$message = "Successful Image Upload ";
		$info = array();
		$base = base_url();
		$info[0] = "Please choose an option:<br />";
		$info[1] = "<a href='".$base."index.php/vecchio_admin_products/addnew/'>Add Another Product</a><br />";
		$info[2] = "<a href='".$base."index.php/vecchio_admin_products/'>View Products</a><br />";
	//$this->addnew();
	   $this->return_message($message, $info);
	}
	
	
	/* Search Fun */

	function tree_search(){
		$this->db->select('short_name, grow_yard_name');
		$this->db->where('active', 1);
		$result = $this->db->get('grow_yards');
		
		$data['grow_yards'] = $result->result_array();
		$data['avail'] = $this->product_model->get_count_products_avail(false,false);
		$data['main_section'] = '_content/tree_search'; 
    	$data['title'] = "VECCHIO Trees";
    	$data['inp'] = 'Row and Tree';
    	$this->load->view('admin_template', $data);
	}
	
	function search_by_row_tree(){
		
		$row = $this->input->post('row_num');
		$tree = $this->input->post('tree_num');
		$grow_yard = $this->input->post('grow_yard_id');
		
    	$result = $this->product_model->search_by_row_tree($grow_yard, $tree, $row, true);
        // echo "<pre>";
		// print_r($result);
	    // echo "</pre>";
 	    $data['show_products'] = true;	
    	$data['newlist'] = $result;
    	$data['main_section'] = '_content/search_results'; 
    	$data['title'] = "VECCHIO Trees";
    	$data['inp'] = 'Row and Tree';
    	$this->load->view('admin_template', $data);		
		
	}
	
	function search_by_h_w(){
		
		$h = $this->input->post('height');
		$w = $this->input->post('width');
    	$result = $this->product_model->search_by_h_w($h, $w, true);	
		$data['show_products'] = true;	
		$data['newlist'] = $result;
		$data['main_section'] = '_content/search_results'; 
		$data['title'] = "VECCHIO Trees";
		$data['inp'] = 'H x W';
		$this->load->view('admin_template', $data);		
		
	}
	
	function product_search(){
		$inp = trim($this->input->post('search_txt'));
		$result = $this->product_model->search_products($inp, true);
		$data['show_products'] = true;	
		$data['newlist'] = $result;
		$data['main_section'] = '_content/search_results'; 
		$data['title'] = "VECCHIO Trees";
		$data['inp'] = $inp;
		$this->load->view('admin_template', $data);	
	}
	
	
}