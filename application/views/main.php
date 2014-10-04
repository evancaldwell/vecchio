<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VECCHIO TREES</title>


<!--<link rel="shortcut icon" type="image/x-icon" href="_images/favicon.ico"> -->

<!-- Main Sytle CSS  -->
<link rel="stylesheet" type="text/css" href="_css/vecchio.css" media="screen" />
<!-- Admin Dropdown Menu CSS  -->
<link rel="stylesheet" type="text/css" href="_css/superfish.css" media="screen" />
<!-- Default Slideshow CSS  -->
<link rel="stylesheet" type="text/css" href="_css/nivo-slider.css"  media="screen" />

<script type="text/javascript" src="_js/jquery-1.4.3.min.js"></script> 
<!-- Dropdown Menu js  for Admin (next 3 files) --> 
<script type="text/javascript" src="_js/hoverIntent.js"></script>
<script type="text/javascript" src="_js/superfish.js"></script>
<script type="text/javascript" src="_js/supersubs.js "></script> 
<!-- slideshow js-->  
<script type="text/javascript" src="_js/jquery.nivo.slider.pack.js"></script>

</head>
<body>
<div id="main_outer">
	<div id="main_inner">
		<h1>VECCHIO SUPER AWESOME WEBSITE</h1>
			<?php /*
			if($this->session->userdata('is_logged_in'))
			{   
				echo anchor('login/logout', 'log out &raquo;');   

			} else {
				  echo anchor('login', 'client log in &raquo;');     
			}
			*/
			?>
		<h3>View Files Setup</h3>
		<p>for public site:  place the html only php files in the "view" folder, located in sandbox->vecchio->application->views, then place your css and js files in the  sandbox->vecchio->_css and  sandbox->vecchio->_js folders. <strong>note: version 2.0 does not require base_url(); before your src file thing </strong> Codeigniter uses MVC to load stuff up, you will have a link like this:<br /><br />
			http://mysite.com/controller/function<br /><br />
			
			you first reference a main controller, could be called "public" and then a function within the controller actually loads a "page" and variables located in the view folder. you place your controller, like the one below in the sandbox->vecchio->system->controller folder <br />
			<textarea cols="90" rows="30">
				class Public extends Controller {

					// constructor loads all your default helpers, models, etc
					function __construct()
					{
						parent::Controller();
						$this->load->helper(array('form', 'url', 'html', 'download'));
					}
					
					// load up dat page within the "view" folder
					function main(){
						$this->load->view('mypublicpage');
					}
					
					// load up another page, with variables. within the view file you reference those variables by just using $name or $phone
					
					function contact(){
						$data['name'] = "Bill";
					    $data['phone'] = "559.355.6798";
						$data['email'] = "bill@whatthewhat.com";
						$this->load->view('mypublicpage', $data);
					}
				}
		    </textarea>
			<br > if anybody needs any pointers on setting this up call me up! 355 1040<br />
			you are running in codeigniter! hot diggity. 
			 </p>
	<h3>for admin section:</h3> 	<p>call up the admin_template view file and pass it a $content variable for the inner content. admin_template auto loads the header, menu bar, $content and footer. </p>
	     
	</div><!-- main_inner -->
</div><!-- main_outer -->	
</body>

</html>