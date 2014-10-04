<?php
class Ajax_model extends CI_Model{
    
    var $sesh;
	private $vipsaves_db;
	   
	function __construct(){
		
		parent::__construct();
		// load database class and connect to MySQL
		$this->load->database(); 
		//$this->sesh['territory'] = $this->session->userdata('territory');

	}
	
	function lookup_ajax($keyword){
    // 	$this->db->like('lname', $keyword, 'both');
    //	$this->db->where('user_type !=', 'rep'); 
    //  $this->db->where('user_type !=', 'admin'); 
	//	$this->db->order_by('lname');      
	//	$query = $this->db->get('users');
		$q = "SELECT id, fname, lname FROM users WHERE CONCAT_WS(' ', fname, lname) LIKE '%$keyword%'";
		if($this->session->userdata('user_type') == 'rep'){
		$rep_id = $this->session->userdata('user_id');
		$q .= " AND rep_id = '$rep_id'";
		}
		$q .= " ORDER BY lname";
		$result = $this->db->query($q);
	    return $result; 
	    echo $q;
		
	}
	
	function lookup_ajax_id($id){
		$this->db->where('id', $id);     
		$query = $this->db->get('users');
	   return $query; 
		
	}
	  
	
}

?>