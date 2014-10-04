<?php
class Prox_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get_prox($zip, $product_code){
    
    	// Get the zip code lat and lon -- 
		$query_zip = $this->db->query("SELECT lat, lon FROM zcta WHERE zip = '$zip'");
    	if ($query_zip->num_rows() > 0)
		{
	   		$row = $query_zip->row(); 
	   		$lat = $row->lat;
	   		$lon = $row->lon;
	    // get all the grow yards and order them by distance to zip --
	
		$query = $this->db->query("	SELECT *, 
		   	( 3959 * acos( cos( radians($lat) ) 
		   	* cos( radians( lat ) ) 
		   	* cos( radians( lon ) - radians($lon) ) 
		   	+ sin( radians($lat) ) 
		   	* sin( radians( lat ) ) ) ) AS distance 
			FROM grow_yards 
			ORDER BY distance");

		$res = $query->result_array();
		
		$plants = array();
		// Select products from each grow yard of the particular $product_code.
		$count = count($res);
		for($i=0; $i<$count; $i++){
					$this->db->select('products.id,
						   products.serial_no,
						   products.order_status,
						   images.file_name,
						   images.icon_file_name');
					$this->db->from('products');
					$this->db->join('images', 'images.product_id = products.id', 'left');
					$this->db->where('product_type_id', $product_code);
					$this->db->where('grow_yard_id', $res[$i]['id']);
        			$q = $this->db->get();
					$res_products = $q->result_array();
					if(!empty($res_products)){
						$plants[$i]['grow_yard_id'] = $res[$i]['id'];
						$plants[$i]['grow_yard_name'] = $res[$i]['grow_yard_name'];
						$plants[$i]['distance'] = $res[$i]['distance'];
						$plants[$i]['products_in_grow_yard'] = $res_products;
					}
				}
		$plants = array_values($plants);
		return $plants;
		}
	}
	
	
}

?>

