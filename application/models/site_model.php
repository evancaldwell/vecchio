<?php
class Site_model extends CI_Model{
   
   	var $sesh;
   	function __construct(){
   		parent:: __construct();
        $sesh['user_id'] = $this->session->userdata('user_id');
   	}

	function get_products_nav(){
		$query = $this->db->get('products');
		$this->db->select('*');
		$this->db->from('');
		$res_orders = $query->result_array();
		$count = count($res_orders);
		
		return $res_orders;
	}
    
	function get_products($product_category='', $product_type='', $product_id =''){

		//$query = $this->db->get('products');
		$this->db->select('products.id as products__id,  images.file_name');
		$this->db->from('product_category');
		$this->db->join('product_type', 'product_category.id = product_type.product_category_id', 'left');
		$this->db->join('products', 'product_type.id = products.product_type_id', 'left');
		$this->db->join('images', 'products.id = images.product_id', 'left');
		$query = $this->db->get();
		
		$res_orders = $query->result_array();
		
		$count = count($res_orders);
		
		return $res_orders;
			
	}
	
	function get_warranty(){
		$warranty = array();
		// $this->db->order_by('title_order');
		$query = $this->db->get('warranty_title');
		
		$q_result = $query->result_array();
		$count = count($q_result);
		for($i=0;$i<$count;$i++){
			$this->db->where('title_type', $q_result[$i]['id']);
			$q_text = $this->db->get('warranty');
			$q_text_res = $q_text->result_array();
			$q_result[$i]['items'] = $q_text_res;
		}
		return $q_result;
	}
	
	function display_warranty($type = 'html'){
		if($type == 'html'){
			$br = "<br />";
		} else {
			$br = "\n";
		}
		$w = $this->get_warranty();
		$wt = "";
		$count = count($w);
		for($i=0;$i<$count;$i++){
			$wt .= "<h2>".$w[$i]['title'] . "</h2>";
			$count_items = count($w[$i]['items']);
			for($j=0;$j<$count_items;$j++){
				$wt .= "<p>" . $w[$i]['items'][$j]['description'] ."</p>";
			}
			
		}
		return $wt;
			
	}
	

	
			
}


?>