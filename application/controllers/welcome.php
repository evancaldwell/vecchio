<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Prox_model');
		$this->load->helper('html');
	}

	function index()
	{
		$this->load->view('main');
		
		$products = $this->Prox_model->get_prox('93612', '5');
		echo "<pre>";
		print_r($products);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */