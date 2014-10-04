<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VECCHIO TREES</title>

<!--<link rel="shortcut icon" type="image/x-icon" href="_images/favicon.ico"> -->

<!-- Main Admin CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/main_admin.css" media="screen" />
<!-- Admin Dropdown Menu CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/superfish.css" media="screen" />
<!-- Default Slideshow CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/nivo-slider.css"  media="screen" />
<!-- Fancybox (pop up window thing) css  -->
<link rel="stylesheet" href="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>_css/ui/jquery.ui.selectmenu.css" type="text/css" media="screen" />
<link href="<?php echo base_url(); ?>_css/signup.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>_css/jquery.countdown.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url();?>_js/jquery-1.4.3.min.js"></script> 
<!-- Dropdown Menu js  for Admin (next 3 files) --> 
<script type="text/javascript" src="<?php echo base_url();?>_js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/superfish.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/supersubs.js "></script> 
<!-- slideshow js-->  
<script type="text/javascript" src="<?php echo base_url();?>_js/jquery.nivo.slider.pack.js"></script>

<script type="text/javascript"> 
// Dropdown Menu js for VECCHIO Admin Section
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
<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/maskedinput.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/jquery.countdown.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/ui/jquery.ui.selectmenu.js" type="text/javascript"></script>
<!-- fancybox for image preview -->
<script type="text/javascript" src="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

</head>
<body> 