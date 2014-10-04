<?php
class Image_model extends CI_Model{
   
   	var $sesh;

   	function __construct(){
   		parent:: __construct();
        $sesh['user_id'] = $this->session->userdata('user_id');
   	}

    
	function get_product_pics($product_id){
	$q = "SELECT file_name, icon_file_name, serial_no
	      FROM images
	      JOIN products ON (products.id = images.product_id)
	      WHERE product_id = '$product_id'";
	$query_images = $this->db->query($q);
	$res_images = $query_images->result_array();
	
	if(empty($res_images)){
		$images = array( 0 => array('file_name' => 'default.png', 'icon_file_name' => 'default_thumb.png'));
		return $images;
	} else {
		return $res_images;
	}
	}
	
	function get_product_pics_gy($grow_yard_id, $product_id = 0){
		
		if($product_id != 0){
			/*
			$this->db->select('images.file_name, images.icon_file_name, products.serial_no');
			$this->db->from('images');
			$this->db->join('products', 'products.id = images.product_id');
			$this->db->where('products.grow_yard_id', $grow_yard_id);
			$this->db->where('products.product_type_id', $product_id);
			$this->db->where('products.order_status', 0);
			*/
			$q = "SELECT images.file_name, images.icon_file_name, products.serial_no
				  FROM images, products
				  WHERE products.id = images.product_id
				  AND products.grow_yard_id = '$grow_yard_id'
				  AND products.product_type_id = '$product_id'
				  AND products.order_status = 0";
			$query_images = $this->db->query($q);
			$res_images = $query_images->result_array();

				
		}
		$q = "SELECT images.file_name, images.icon_file_name, products.serial_no
			  FROM images, products
			  WHERE products.id = images.product_id
			  AND products.grow_yard_id = '$grow_yard_id'
			  AND products.order_status = 0 ";
			if($product_id != 0){
			$q .= "AND products.product_type_id != '$product_id'";
			}
		$query_images = $this->db->query($q);
		$res = $query_images->result_array();
		/*
		$this->db->select('images.file_name, images.icon_file_name, products.serial_no');
		$this->db->from('images');
		$this->db->join('products', 'products.id = images.product_id');
		$this->db->where('products.grow_yard_id', $grow_yard_id);

		$this->db->where('products.order_status', 0);
		$query = $this->db->get();
		$res = $query_images->result_array();
		*/
		if($product_id != 0){
		$resnew = array_merge($res_images, $res);
		return $resnew;
		} else {
		 return $res;	
		}
		
	}
	

	
		
}


?>