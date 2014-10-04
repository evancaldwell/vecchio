<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search extends CI_Controller{
  
  function __construct(){
    parent::__construct();
	$this->load->model('Ajax_model');
	$this->load->model('User_model');
	$this->load->helper(array('form', 'url'));
	$this->User_model->is_logged_in(); 
  }

  function index(){
     $keyword = $this->input->post('term');
     $data['response'] = 'false'; //Set default response
     if($keyword != '' && $keyword != 'No Selection'){
     $query = $this->Ajax_model->lookup_ajax($keyword); //Search DB

     if($query->num_rows() > 0)
    {
       $data['response'] = 'true'; //Set response
       $data['message'] = array(); //Create array
      foreach($query->result() as $row)
       {
            $data['message'][] = array('label'=> $row->fname . ' ' . $row->lname, 'value'=> $row->fname, 'id'=> $row->id); //Add a row to array
       }
     }
		echo json_encode($data);
	}
  }

	function by_id(){
     	$keyword = $this->input->post('term');
     	$data['response'] = 'false'; //Set default response

     	$query = $this->Ajax_model->lookup_ajax_id($keyword); //Search DB

     if($query->num_rows() > 0)
    	{
       	$data['response'] = 'true'; //Set response
       	$data['message'] = array(); //Create array
      foreach($query->result() as $row)
       	{
            	$data = array('label'=> $row->fname . " " . $row->lname, 'id' => $row->id ); //Add a row to array
       	}
     }

     	echo json_encode($data);
  	}

}   
?>