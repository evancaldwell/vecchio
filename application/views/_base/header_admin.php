<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VECCHIO TREES</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE8"/>

<!--<link rel="shortcut icon" type="image/x-icon" href="_images/favicon.ico"> -->

<!-- Main Sytle CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/vecchio.css" media="screen" />
<!-- Admin Dropdown Menu CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/superfish.css" media="screen" />
<!-- Default Slideshow CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/nivo-slider.css"  media="screen" />
<!-- Fancybox (pop up window thing) css  -->
<link rel="stylesheet" href="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<link rel="stylesheet" href="<?php echo base_url();?>_css/ui/jquery-ui-1.8.11.custom.css"> 
<link rel="stylesheet" href="<?php echo base_url();?>_css/ui/jquery-ui.datepicker.css">
<script type="text/javascript" src="<?php echo base_url();?>_js/jquery-1.6.4.min.js"></script>  
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.core.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.widget.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.datepicker.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>_js/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>_js/ui/jquery.ui.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>_js/ui/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>_js/ui/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>_js/ui/jquery.ui.sortable.js"></script>




<!-- fancybox for image preview -->
<script type="text/javascript" src="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<!-- Dropdown Menu js  for Admin (next 3 files) --> 
<script type="text/javascript" src="<?php echo base_url();?>_js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/superfish.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/supersubs.js "></script> 
<!-- slideshow js-->  
<script type="text/javascript" src="<?php echo base_url();?>_js/jquery.nivo.slider.pack.js"></script>


<script type="text/javascript"> 
// Dropdown Menu js for VECCHIO Admin Section
    $(document).ready(function(){
		$(".iframes").fancybox({
			'width'				: '95%',
			'height'			: '95%',
			'autoScale'     	: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'				: 'iframe'});	
	 
        $("ul.sf-menu").supersubs({ 
            minWidth:    12,   // minimum width of sub-menus in em units 
            maxWidth:    30,   // maximum width of sub-menus in em units 
            extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                               // due to slight rounding differences and font-family 
        }).superfish();  // call supersubs first, then superfish, so that subs are 
                         // not display:none when measuring. Call before initialising 
                         // containing tabs for same reason.


    }); 

</script>

<?php if(isset($googlemap)){
$this->load->view('_base/header_googlemap');	?>
</head>
<body onload="initialize()">
<?php } else { ?>
</head>
<body>
<?php } ?>

<div id="main_outer">
	<div id="loginfo">
	<?php if($this->session->userdata('fname')){
		?><img src="<?php echo base_url(); ?>_images/locks.jpg" /> <?php  echo "Hello " . $this->session->userdata('fname'); echo " " . anchor('login/logout', 'logout');}?> </div>
	<div id="admin_logo">&nbsp;</div>
	<div id="admin_dropmenu"><?php 
	if(($main_section !='_content/login') && ($main_section !='_content/error_m')){
	$this->load->view('_base/drop_menu');			
	} else { ?>
		<ul class="sf-menu">
			<li><?php echo anchor('dir/', 'Vecchio Home Page'); ?></li>
		</ul>
	<?php } ?></div>
	<div class="clearup">
	<div id="main_inner">
	
		 