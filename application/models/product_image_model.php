<?php
class Product_image_model extends CI_Model{
   
   	var $sesh;
	var $gallery_path;
	var $gallery_path_url;
	var $file_image_path;
	var $file_image_path_url;
	var $th_w;
	var $th_h;
	var $orig_w;
	
   	function __construct(){
   		parent:: __construct();
        //$sesh['user_id'] = $this->session->userdata('user_id');
		$this->gallery_path = realpath(APPPATH . '../_fdr');
		$this->gallery_path_url = base_url().'_fdr/';
		$this->file_image_path = realpath(APPPATH . '../_images/_products');
		$this->file_image_url = base_url().'_images/_products/';
		$this->th_w = 84;
		$this->th_h = 113;
		$this->orig_w = 600;
   	}

    function do_upload($product_id) {
		
		$config = array(
			'allowed_types' => 'jpg|jpeg|gif|png',
			'upload_path' => $this->gallery_path,
			'max_size' => 2048,
			'encrypt_name' => TRUE
		);
		
		$this->load->library('upload', $config);
		$this->upload->do_upload();
		
		$uploadarray = $this->upload->data();
	//	echo '<pre>';print_r($uploadarray);echo'</pre>';
	

	
		unset($uploadarray['file_path']);
		unset($uploadarray['raw_name']); 
		unset($uploadarray['client_name']);
		$uploadarray['product_id'] = $product_id;
		$width = $uploadarray['image_width'];
		$height = $uploadarray['image_height'];
		
		if($width > $height){
		// Landscape Mode, shrink that puppy up before you crop it
		$ratio = ($width /$height );
		$new_width = ($this->th_h * $ratio);
		
		} else {
		$new_width = $this->th_w;
		}
		
		$config = array(
			'source_image' => $uploadarray['full_path'],
			'new_image' => $this->gallery_path . '/thumbs',
			'maintain_ratio' => true,
			'thumb_marker' => '_thumb',
			'width' => $new_width + 8, // plus 8 px to give some buffer if the proportions aren't exact upon upload
			'height' => $this->th_h + 8
		);
		
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
		
		$uploadarray['icon_file_name'] = $uploadarray['file_name'];
		$uploadarray['icon_full_path'] = $uploadarray['full_path'].'/thumbs';
		// then crop that thing in the center
		if($width > $height){
			$halfway = $new_width / 2;
			// add back half the left - 
			$crop_x = ($halfway - ($this->th_w / 2));
			$thumb_path = $this->gallery_path . '/thumbs/' . $uploadarray['file_name'];
			$configc['image_library'] = 'gd2';
			$configc['source_image'] = $thumb_path;
			$configc['maintain_ratio'] = FALSE;
			$configc['x_axis'] = $crop_x;
			$configc['y_axis'] = 0;
			$configc['width'] = $this->th_w;
			$configc['height'] = $this->th_h;
			
			$this->image_lib->clear();
			$this->image_lib->initialize($configc); 

			if ( ! $this->image_lib->crop())
			{
		    	echo $this->image_lib->display_errors();
			}	
		}
		
		
	    /*
		$this->load->library('md_image'); 
		$thumb_path = $this->gallery_path . '/thumbs';
		$this->md_image->crop_to_ratio($uploadarray['full_path'], $uploadarray['image_width'], $uploadarray['image_height'],84,113,$thumb_path); 
		$this->md_image->resize_image($thumb_path,84,113,$thumb_path); 
		*/

		$result = $this->db->insert('images', $uploadarray );
		
		
	}
	
	function get_images() {
		
		$files = scandir($this->gallery_path);
		$files = array_diff($files, array('.', '..', 'thumbs'));
		
		$images = array();
		
		foreach ($files as $file) {
			$images []= array (
				'url' => $this->gallery_path_url . $file,
				'thumb_url' => $this->gallery_path_url . 'thumbs/' . $file
			);
		}
		
		return $images;
	}
	
	function get_product_pics($product_id){
	$this->db->select('file_name, icon_file_name');
	$this->db->where('product_id', $product_id);
	$query_images = $this->db->get('images');
	$res_images = $query_images->result_array();
	return $res_images;

	}
	
	function update_image_file(){

	 $product_code =	$this->input->post('product_code');	

		if ($_FILES['userfile']['error'] != 4) {

			$config['upload_path'] = $this->file_image_path;
			$config['allowed_types'] = 'jpg';
			$config['max_size']	= '2048';
			$config['encrypt_name'] = FALSE;
			$config['file_name'] = $product_code . ".jpg";
			$config['overwrite'] = true;


			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors()); 
				return $error;
			}	
			else
			{   

				$uploadarray = $this->upload->data();

				$config['source_image'] = $uploadarray['full_path'];
				$config['maintain_ratio'] = TRUE;
				$config['width'] = 185;
				$config['height'] = 259; 

				$this->load->library('image_lib', $config);

				if ( !$this->image_lib->resize()){
					$error = $this->image_lib->display_errors();
					return $error;			
				}
				
			}
		} 
	 
	}
	

	
		
}


?>