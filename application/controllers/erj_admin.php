<?php

class Erj_admin extends Controller {
	

	var $menu_items = array();
	
	function __construct()
	{
		parent::Controller();
		$this->load->helper(array('form', 'url', 'html', 'download'));
		$this->load->model('User_model');
		$this->load->model('Client_model');
		$this->load->model('Image_model');
		$this->load->model('Default_model');  
 		$this->load->library('form_validation');
        $this->load->library('ftp');
		$this->User_model->is_logged_in_super();
        $this->menu_items['services'] = $this->Default_model->get_all_services();
        $this->menu_items['brochures'] = $this->Default_model->get_all_brochures(); 
	}
	
	function index()
	{
		$data['main_section'] = '_main_section/erj_admin_m';
		$data['side'] = '_sidebar/erj_admin_s';
		$companies = $this->Client_model->get_all_companies(); 
        $users = $this->Client_model->get_all_companies_users();

		$data['companies'] = $companies; 
		$data['users'] = $users;
		$data['menu_items'] = $this->menu_items;
		$this->load->view('sub_template', $data);	
	}
	
	function display_error($error, $link, $link_name){
		$data['error'] = $error; 
		$data['link'] = $link;
		$data['link_name'] = $link_name;
		$data['main_section'] = '_main_section/error_m';
		$data['side'] = '_sidebar/error_s';
		$data['menu'] = $this->menu_items;
		$this->load->view('sub_template', $data);	
		
	}
	
	function client(){
	$client_id = $this->uri->segment(3);
    $data['main_section'] = '_main_section/client_m';
	$data['side'] = '_sidebar/client_s';
	$data['client_info'] = $this->Client_model->get_client_info($client_id);   
	$data['client_files'] = $this->Client_model->get_client_files($client_id);

	$this->load->view('sub_template', $data); 	
	} 
	
	function user(){
	$user_id = $this->uri->segment(3); 
	$data['companies'] = $this->Client_model->get_all_companies();  
    $data['main_section'] = '_main_section/user_m';
	$data['side'] = '_sidebar/user_s';
	$data['user_info'] = $this->Client_model->get_user_info($user_id);   
	$this->load->view('sub_template', $data); 	
	}
	
	function new_user()
	{
		$data['main_section'] = '_main_section/add_user_m';
		$data['side'] = '_sidebar/erj_admin_s';
		$companies = $this->Client_model->get_all_companies(); 
        $users = $this->Client_model->get_all_companies_users();

		$data['companies'] = $companies; 
		$data['users'] = $users;
		$data['menu_items'] = $this->menu_items;
		$this->load->view('sub_template', $data);	
	}
	
	function new_company()
	{
		$data['main_section'] = '_main_section/add_company_m';
		$data['side'] = '_sidebar/erj_admin_s';
		$companies = $this->Client_model->get_all_companies(); 
        $users = $this->Client_model->get_all_companies_users();

		$data['companies'] = $companies; 
		$data['users'] = $users;
		$data['menu_items'] = $this->menu_items;
		$this->load->view('sub_template', $data);	
	}
	
	function add_client(){
		$client_name = $this->input->post('client_name');
        $default_welcome = $this->input->post('default_welcome');
		$address = $this->input->post('address'); 
		$city = $this->input->post('city');
		$state = $this->input->post('state');
		$zip = $this->input->post('zip');
		

		if($client_name != ""){
		$data = array(
		               'client_name' => $client_name,
		               'default_welcome' => $default_welcome,
					   'address' => $address,
					   'city' => $city,
					   'state' => $state,
					   'zip'  => $zip    
		            );
	    $this->db->insert('erj_client', $data);
		}
		$this->index();
	   
	}
	
	function update_client(){
		$client_name = $this->input->post('client_name');
        $default_welcome = $this->input->post('default_welcome');
		$address = $this->input->post('address'); 
		$city = $this->input->post('city');
		$state = $this->input->post('state');
		$zip = $this->input->post('zip');
	    $client_id = $this->input->post('client_id'); 

		if($client_name != ""){
		$data = array(
		               'client_name' => $client_name,
		               'default_welcome' => $default_welcome,
					   'address' => $address,
					   'city' => $city,
					   'state' => $state,
					   'zip'  => $zip    
		            );
		$this->db->where('id', $client_id);
	    $this->db->update('erj_client', $data);
	    redirect('erj_admin/client/' . $client_id);
		} 
		
		
		$this->index();
	   
	}
	

	
	function add_user()
	{   
		$client_id = $this->input->post('client_id'); 
		$file_desc = $this->input->post('file_desc');
	   	$fname = $this->input->post('fname');
        $lname = $this->input->post('lname');
		$title = $this->input->post('title');
		$phone_number = $this->input->post('phone'); 
		$email = $this->input->post('email');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$client_id = $this->input->post('client_id');
		$password_hashed = sha1($this->input->post('password'));
		$user_type= $this->input->post('user_type');
		$user_profile = $this->input->post('user_profile'); 
		// Check if file image is uploaded, then do the following: 
		
		if ($_FILES['userfile']['error'] != 4) {
   
		
			$config['upload_path'] = '_fdr/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']	= '2048';
			$config['encrypt_name'] = TRUE;

		
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors()); 
				$this->display_error($error, 'erj_admin/', '&laquo; Back To Admin Page');
			}	
			else
			{   
			
				$data = array('upload_data' => $this->upload->data());
			
				$uploadarray = $this->upload->data();
				unset($uploadarray['file_path']);
				unset($uploadarray['raw_name']); 
				unset($uploadarray['client_name']);
				$uploadarray['client_id'] = $client_id;
				$uploadarray['file_desc'] = $file_desc;
            
				$config['source_image'] = $this->upload->upload_path.$this->upload->file_name;
				$config['maintain_ratio'] = TRUE;
				$config['width'] = 150;
				$config['height'] = 150; 

				$this->load->library('image_lib', $config);

				if ( !$this->image_lib->resize()){
					$error = $this->image_lib->display_errors();
					$this->display_error($error, 'erj_admin/', '&laquo; Back To Admin Page');				
				}
			 	   
				$result = $this->db->insert('erj_files', $uploadarray );
				if($result){ 
				$image_id = $this->db->insert_id();   
					$data = array(  
							   'client_id' => $client_id,
							   'image_id' => $image_id,
				               'fname' => $fname,
							   'lname' => $lname,
							   'title' => $title,
							   'user_profile' => $user_profile,
							   'phone_number' => $phone_number,
							   'email' => $email,
				               'username' => $username,
							   'password' => $password,
		                       'user_type' => $user_type,
							   'password_hashed' => $password_hashed,    
				            );
			    		$this->db->insert('erj_users', $data);
			            $this->index();
				}
			}
		} else {
			// No Image was uploaded, just insert the stuff...
			$image_id = "";
			$data = array(  
					   'client_id' => $client_id,
					   'image_id' => $image_id,
		               'fname' => $fname,
					   'lname' => $lname,
					   'title' => $title,
					   'user_profile' => $user_profile,
					   'phone_number' => $phone_number,
					   'email' => $email,
		               'username' => $username,
					   'password' => $password,
                       'user_type' => $user_type,
					   'password_hashed' => $password_hashed,    
		            );
	    		$this->db->insert('erj_users', $data);
				$this->index();
		}
	} 
	
	
	
	function update_user(){
		$fname = $this->input->post('fname');
        $lname = $this->input->post('lname');
		$title = $this->input->post('title');
		$phone_number = $this->input->post('phone'); 
		$email = $this->input->post('email');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$client_id = $this->input->post('client_id');
		$password_hashed = sha1($this->input->post('password'));
		$user_type= $this->input->post('user_type');
		$user_id = $this->input->post('user_id');
		 

		if($fname != "" && $password != ''){
		$data = array(  
					   'client_id' => $client_id,
		               'fname' => $fname,
					   'lname' => $lname,
					   'title' => $title,
					   'phone_number' => $phone_number,
					   'email' => $email,
		               'username' => $username,
					   'password' => $password,
                       'user_type' => $user_type,
					   'password_hashed' => $password_hashed,    
		            );
		$this->db->where('id', $user_id);
	    $this->db->update('erj_users', $data); 
        redirect('erj_admin/user/' . $user_id); 
		}
		$this->index();
	   
	}
	
	/*
	IMAGE UPLOAD
	
	*/
	function do_upload()
	{
		$config['upload_path'] = '_fdr/';
		$config['allowed_types'] = 'doc|docx|xls|xlsx|mdb|accdb|ppt|pptx|csv|ods|odt|zip|rar|odp|pdf|rtf|sql|gif|jpg|jpeg|png';
		$config['max_size']	= '2048';
		$config['encrypt_name'] = TRUE;
        $client_id = $this->input->post('client_id'); 
		$file_desc = $this->input->post('file_desc');
		
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors()); 
			$this->display_error($error, 'erj_admin/client/' . $client_id, '&laquo; Back To Client Page');
		}	
		else
		{   
			
			$data = array('upload_data' => $this->upload->data());
			
			$uploadarray = $this->upload->data();
			unset($uploadarray['file_path']);
			unset($uploadarray['raw_name']); 
			unset($uploadarray['client_name']);
			$uploadarray['client_id'] = $client_id;
			$uploadarray['file_desc'] = $file_desc;
            
			 	   
			$result = $this->db->insert('erj_files', $uploadarray );
			if($result){
			redirect('erj_admin/client/' . $client_id); 
			}
		}
	}
	
	function update_user_profile(){
	   $user_id = $this->input->post('user_id');

	   $user_profile = $this->input->post('user_profile');
	   
		if ($_FILES['userfile']['error'] != 4) {
            	   
			$delete_name = $this->input->post('delete_name');
			$old_image_id = $this->input->post('image_id');   
		
			$config['upload_path'] = '_fdr/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']	= '2048';
			$config['encrypt_name'] = TRUE;

		
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors()); 
				$this->display_error($error, 'erj_admin/', '&laquo; Back To Admin Page');
			}	
			else
			{   
			
				$data = array('upload_data' => $this->upload->data());
			
				$uploadarray = $this->upload->data();
				unset($uploadarray['file_path']);
				unset($uploadarray['raw_name']); 
				unset($uploadarray['client_name']);
   
           
				$config['source_image'] = $this->upload->upload_path.$this->upload->file_name;
				$config['maintain_ratio'] = TRUE;
				$config['width'] = 150;
				$config['height'] = 150; 

				$this->load->library('image_lib', $config);

				if ( !$this->image_lib->resize()){
					$error = $this->image_lib->display_errors();
					$this->display_error($error, 'erj_admin/', '&laquo; Back To Admin Page');				
				}
			 	   
				$result = $this->db->insert('erj_files', $uploadarray );
				if($result){ 
				
				
				$image_id = $this->db->insert_id();
				   
					$data = array(  
				
							   'image_id' => $image_id,
							   'user_profile' => $user_profile
   
				            );
				        $this->db->where('id', $user_id);
			    		$updated = $this->db->update('erj_users', $data);
			            if($updated){
				        // Delete the old file;
				        $this->db->where('id', $old_image_id);
					     $isdeleted = $this->db->delete('erj_files');
					    $base = base_url();
		   				 @unlink($base .'_fdr/'. $delete_name);
						
						}
			            redirect('erj_admin/user/'. $user_id);
				}
			}
		} else {
			// No Image was uploaded, just insert the profile stuff..

			$data = array(  

					   'user_profile' => $user_profile
  
		            );
	            $this->db->where('id', $user_id); 
	    		$this->db->update('erj_users', $data);
				redirect('erj_admin/user/'. $user_id);
		}
		
	}
	
	function services(){
	  	
		$data['main_section'] = '_main_section/service_m';
		$data['side'] = '_sidebar/erj_admin_s';
        $users = $this->Client_model->get_all_companies_users();
        $service_data = $this->Client_model->get_service_info($this->uri->segment(3));     
		$data['users'] = $users;
		$data['service_data'] = $service_data; 
		$data['menu_items'] = $this->menu_items;
		$this->load->view('sub_template', $data);  
		
	}
	
	function new_service(){
		$data['main_section'] = '_main_section/new_service_m';
		$data['side'] = '_sidebar/erj_admin_s';
        $users = $this->Client_model->get_all_companies_users();
        $providers = $this->Client_model->get_all_users(); 

		$data['users'] = $users;
		$data['providers'] = $providers; 
		$data['menu_items'] = $this->menu_items;
		$this->load->view('sub_template', $data);	
		
	}
	
	function add_service(){
		$service_name = $this->input->post('service_name');
		$service_desc = $this->input->post('service_desc');
		$menu_name = $this->input->post('menu_name'); 
        if ($_FILES['userfile']['error'] != 4) {
   
		
			$config['upload_path'] = '_fdr/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']	= '2048';
			$config['encrypt_name'] = TRUE;

		
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors()); 
				$this->display_error($error, 'erj_admin/', '&laquo; Back To Admin Page');
			}	
			else
			{   
			
				$data = array('upload_data' => $this->upload->data());
			
				$uploadarray = $this->upload->data();
				unset($uploadarray['file_path']);
				unset($uploadarray['raw_name']);
				unset($uploadarray['client_name']); 
           
				$config['source_image'] = $this->upload->upload_path.$this->upload->file_name;
				$config['maintain_ratio'] = TRUE;
				$config['width'] = 545;
				$config['height'] = 300; 

				$this->load->library('image_lib', $config);

				if ( !$this->image_lib->resize()){
					$error = $this->image_lib->display_errors();
					$this->display_error($error, 'erj_admin/', '&laquo; Back To Admin Page');				
				}
			 	   
				$result = $this->db->insert('erj_files', $uploadarray );
				if($result){ 
				$image_id = $this->db->insert_id();   
					$data = array(  
							   'service_name' => $service_name,
							   'menu_name' => $menu_name,
							   'service_desc' => $service_desc,
				               'image_id' => $image_id
				            );
			    		$this->db->insert('erj_services', $data);
			            $service_id = $this->db->insert_id();	
						$providers = $this->Client_model->get_all_users(); 
						$count = count($providers);
						$yes_prov = array();
						for($i=0; $i<$count; $i++){ 
						$data = "";
						$user_id = $providers[$i]['id'];
						$yes_prov = $this->input->post($i);
							if($yes_prov == 1){
								$data = array(
									'service_id' => $service_id, 
									'user_id' => $user_id
								);
								$this->db->insert('erj_specs', $data);

							}
						}
						$this->index();    
				}
			}
		} else {
			// No Image was uploaded, just insert the stuff...
			$image_id = "";
			$data = array(  
				   'service_name' => $service_name,
				   'menu_name' => $menu_name,
				   'service_desc' => $service_desc,
	               'image_id' => $image_id   
		            );
	    		$this->db->insert('erj_services', $data);
	            $service_id = $this->db->insert_id();	
				$providers = $this->Client_model->get_all_users(); 
				$count = count($providers);
				$yes_prov = array();
				for($i=0; $i<$count; $i++){ 
				$data = "";
				$user_id = $providers[$i]['id'];
				$yes_prov = $this->input->post($i);
					if($yes_prov == 1){
						$data = array(
							'service_id' => $service_id, 
							'user_id' => $user_id
						);
						$this->db->insert('erj_specs', $data);

					}
				}
				$this->index();
				
		}


	}
	
	function update_service(){
	   $service_id = $this->input->post('service_id');
       $service_name = $this->input->post('service_name');
		$service_desc = $this->input->post('service_desc');
		$menu_name = $this->input->post('menu_name');
		

	   
		if ($_FILES['userfile']['error'] != 4) {
            	   
			$delete_name = $this->input->post('delete_name');
			$old_image_id = $this->input->post('image_id');   
		
			$config['upload_path'] = '_fdr/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']	= '2048';
			$config['encrypt_name'] = TRUE;

		
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors()); 
				$this->display_error($error, 'erj_admin/', '&laquo; Back To Admin Page');
			}	
			else
			{   
			
				$data = array('upload_data' => $this->upload->data());
			
				$uploadarray = $this->upload->data();
				unset($uploadarray['file_path']);
				unset($uploadarray['raw_name']); 
				unset($uploadarray['client_name']);
   
           
				$config['source_image'] = $this->upload->upload_path.$this->upload->file_name;
				$config['maintain_ratio'] = TRUE;
				$config['width'] = 545;
				$config['height'] = 350; 

				$this->load->library('image_lib', $config);

				if ( !$this->image_lib->resize()){
					$error = $this->image_lib->display_errors();
					$this->display_error($error, 'erj_admin/', '&laquo; Back To Admin Page');				
				}
			 	   
				$result = $this->db->insert('erj_files', $uploadarray );
				if($result){ 
				
				
				$image_id = $this->db->insert_id();
				   
					$data = array(  
				
							   'image_id' => $image_id,
							   'service_name' => $service_name,
							   'menu_name' => $menu_name,
							   'service_desc' => $service_desc
   
				            );
				        $this->db->where('id', $service_id);
			    		$updated = $this->db->update('erj_services', $data);
			            if($updated){
				        // Delete the old file;
				        $this->db->where('id', $old_image_id);
					     $isdeleted = $this->db->delete('erj_files');
					    $base = base_url();
		   				 @unlink($base .'_fdr/'. $delete_name);
						// update the service providers
						// delete all entries for this service provider
					    $this->db->where('service_id', $service_id);
					    $this->db->delete('erj_specs');
					    $providers = $this->Client_model->get_all_users(); 
						$count = count($providers);
						$yes_prov = array();
						for($i=0; $i<$count; $i++){ 
						$data = "";
						$user_id = $providers[$i]['id'];
						$yes_prov = $this->input->post($i);
							if($yes_prov == 1){
								$data = array(
									'service_id' => $service_id, 
									'user_id' => $user_id
								);
								$this->db->insert('erj_specs', $data);

							}
						}
						}
			            redirect('erj_admin/services/'. $service_id);
				}
			}
		} else {
			// No Image was uploaded, just insert the profile stuff..

			$data = array(  

					   'service_name' => $service_name,
					   'menu_name' => $menu_name,
					   'service_desc' => $service_desc
  
		            );
	            $this->db->where('id', $service_id); 
	    		$this->db->update('erj_services', $data);
				// update the service providers
			    
			    // delete all entries for this service provider
			    $this->db->where('service_id', $service_id);
			    $this->db->delete('erj_specs');
			    $providers = $this->Client_model->get_all_users(); 
				$count = count($providers);
				$yes_prov = array();
				for($i=0; $i<$count; $i++){ 
				$data = "";
				$user_id = $providers[$i]['id'];
				$yes_prov = $this->input->post($i);
					if($yes_prov == 1){
						$data = array(
							'service_id' => $service_id, 
							'user_id' => $user_id
						);
						$this->db->insert('erj_specs', $data);

					}
				}
			     
				redirect('erj_admin/services/'. $service_id);
		}
		
	}
	
	function new_brochure(){
		
	 	$data['main_section'] = '_main_section/brochures_m';
		$data['side'] = '_sidebar/erj_admin_s';
        $users = $this->Client_model->get_all_companies_users();
        $services = $this->Default_model->get_all_services();   
		$data['users'] = $users;
		$data['services'] = $services; 
		$data['menu_items'] = $this->menu_items;
		$this->load->view('sub_template', $data);  
	
	}
	
	function add_brochure()
	{
		$config['upload_path'] = '_fdr/';
		$config['allowed_types'] = 'doc|docx|pdf';
		$config['max_size']	= '2048';
		$config['encrypt_name'] = TRUE;
        $service_id = $this->input->post('service_id'); 
		$file_desc = $this->input->post('file_desc');
		
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors()); 
			$this->display_error($error, 'erj_admin/brochures/', '&laquo; Back To Brochure Page');
		}	
		else
		{   
			
			$data = array('upload_data' => $this->upload->data());
			
			$uploadarray = $this->upload->data();
			unset($uploadarray['file_path']);
			unset($uploadarray['raw_name']); 
			unset($uploadarray['client_name']);
			$uploadarray['file_desc'] = $file_desc;
            
			 	   
			$result = $this->db->insert('erj_files', $uploadarray );
			if($result){
			$file_id = $this->db->insert_id();
			$data = array( 
				'service_id' => $service_id,
				'file_id' => $file_id
					
			);
			$this->db->insert('erj_downloads', $data);
			
			redirect('erj_admin/new_brochure'); 
			}
		}
	}
	
	function main_info(){
		$data['main_section'] = '_main_section/site_text_m';
		$data['side'] = '_sidebar/erj_admin_s';
		$users = $this->Client_model->get_all_companies_users();  
        $main_info = $this->Client_model->get_main_info();
		$data['users'] = $users;
		$data['main_info'] = $main_info; 
		$data['menu_items'] = $this->menu_items;
		$this->load->view('sub_template', $data);	
		
	}
	
	function edit_info(){
		$main_phone = $this->input->post('main_phone');
		$main_fax = $this->input->post('main_fax');
		$main_email = $this->input->post('main_email');
		$about_us = $this->input->post('about_us');
		$side_bar = $this->input->post('side_bar');
		$our_experience = $this->input->post('our_experience');
		
		$data = array(
			        'main_phone' => $main_phone,
			        'main_fax'  => $main_fax,
					'main_email' => $main_email,
					'about_us' => $about_us,
					'side_bar' => $side_bar,
					'our_experience' =>$our_experience
					);
					
		$this->db->update('main_info', $data);
		$this->main_info();                         
		
 	
	}
	
	function erj_news(){
		$data['main_section'] = '_main_section/erj_news_m';
		$data['side'] = '_sidebar/erj_admin_s';
		$users = $this->Client_model->get_all_companies_users();  
        $news = $this->Client_model->get_all_news();
		$data['users'] = $users;
		$data['news'] = $news; 
		$data['menu_items'] = $this->menu_items;
		$this->load->view('sub_template', $data);	
	}

	function add_news(){
	   	$news_title = $this->input->post('news_title');
		$news_text = $this->input->post('news_text');
		$user_id = $this->session->userdata('user_id');     
		$dt=date("Y-m-d H:i:s");  
		  
		$data = array(
			        'user_id' =>    $user_id,
			        'news_title' => $news_title,
			        'news_text'  => $news_text,
					'news_date' => $dt
					);
					
		$this->db->insert('erj_news', $data);
		$this->erj_news(); 
	}

	function delete_news(){
		$delete_id = $this->input->post('delete_id');
		$this->db->where('id', $delete_id);
		$this->db->delete('erj_news');
		$this->erj_news();
	}
	
	function delete_file(){
	 $delete_id =  $this->input->post('delete_id');
	 $delete_name = $this->input->post('delete_name');
	 $client_id = $this->input->post('client_id');

	 $this->db->where('id', $delete_id);
     $isdeleted = $this->db->delete('erj_files');

   	 if($isdeleted){
	    	$base = base_url();
			 @unlink($base .'_fdr/'. $delete_name);
	 		 redirect('erj_admin/client/' . $client_id);
	 }
	}
	

		
    function do_download(){
	    $orig_name = $this->input->post('orig_name');
		$full_path = $this->input->post('full_path');
		
		$data = file_get_contents($full_path); // Read the file's contents
 

	   force_download($orig_name, $data);
	
	
	}
	

}