<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ER Jones Management</title>
<link rel="shortcut icon" type="image/x-icon" href="_images/favicon.ico">

<!-- Source File -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.2.0/build/cssreset/reset-min.css">
<link rel="stylesheet" type="text/css" href="_css/edstylesIE.css" media="screen" />
<link rel="stylesheet" type="text/css" href="_css/superfish.css" media="screen" />
<link rel="stylesheet" type="text/css" href="_css/nivo-slider.css"  media="screen" />

<script type="text/javascript" src="_js/jquery-1.4.3.min.js"></script>  
<script type="text/javascript" src="_js/hoverIntent.js"></script>
<script type="text/javascript" src="_js/superfish.js"></script>
<script type="text/javascript" src="_js/supersubs.js "></script>  
<script type="text/javascript" src="_js/jquery.nivo.slider.pack.js"></script>

<script type="text/javascript"> 

    $(document).ready(function(){ 
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

</head>
<body> 
	<div id="main_sub">
		<div id="sub_top">P: 1-800-577-4576 &nbsp;&nbsp;&nbsp;F: 1-800-577-2251 &nbsp;&nbsp;&nbsp;<a href="login.php">client log in &raquo;</a>
		</div><!-- #sub_top -->
		<div class="sub_left">
			<div id="sub_top_logo">&nbsp;</div><!-- #sub_top_logo -->		
		</div><!-- .sub_left -->
		<div class="sub_right">
			<div id="sub_top_nav">
			<?php $this->load->view('drop_menu');  ?>
			</div><!-- #sub_top_nav -->
			<div id="sub_top_shout">&nbsp;</div><!-- #sub_top_shout -->		
		</div><!-- .sub_right -->
		<div class="clear_bar"></div>