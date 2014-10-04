<?PHP
$year = date("Y");
$b = base_url();
$s = str_replace( "http://", "https://", $b );
$n = str_replace( "https://", "http://", $b );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE8"/>
<title><?PHP echo $title;?></title>
<meta name="description" content="Vecchio Trees provides specimen trees and relocation services to leading landscape companies in the industry as well as individual homeowners." />
<?php

echo link_tag('_css/default_2.css');
echo link_tag('_css/dropmenu.css'); 
echo link_tag('_css/jquery.jscrollpane.css');
echo link_tag('_css/pepper-grinder/pepper.css');
?>
<!-- Fancybox (pop up window thing) css  -->
<link rel="stylesheet" href="<?php echo $b ?>_css/ui/jquery.ui.selectmenu.css" type="text/css" media="screen" />
<link href="<?php echo $b ?>_css/signup.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $b ?>_css/jquery.countdown.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $b?>_css/ui/jquery.ui.selectmenu.css" type="text/css" media="screen" />
<link href="<?php echo $b ?>_css/signup.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $b ?>_css/jquery.countdown.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="<?php echo $b?>_js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $b;?>_js/jquery-1.6.4.min.js"></script>

<script type="text/javascript" src="<?php echo $b;?>_js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo $b;?>_js/superfish.js"></script>
<script type="text/javascript" src="<?php echo $b;?>_js/supersubs.js "></script>


<!-- the jScrollPane script -->
<script type="text/javascript" src="<?php echo $b;?>_js/jquery.jscrollpane.min.js"></script> 

<!-- the ui -->

<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery-ui-1.8.16.custom.js"></script>  
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.ui.core.js"></script> 
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.ui.widget.js"></script> 
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.ui.datepicker.js"></script> 
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.ui.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.ui.sortable.js"></script>
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.effects.core.js"></script>
<script type="text/javascript" src="<?php echo $b; ?>_js/ui/jquery.effects.slide.js"></script>
<script src="<?php echo $b ?>_js/ui/jquery.ui.tabs.js"></script>
<script src="<?php echo $b ?>_js/ui/jquery.ui.dialog.js"></script>
<!-- fancybox for image preview -->
<script type="text/javascript" src="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script src="<?php echo $b ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo $b ?>_js/maskedinput.js" type="text/javascript"></script>
<script src="<?php echo $b ?>_js/jquery.countdown.js" type="text/javascript"></script>
<script src="<?php echo $b ?>_js/ui/jquery.ui.selectmenu.js" type="text/javascript"></script>
<script src="<?php echo $b ?>_js/jquery.sessionTimeout.1.0.min.js" type="text/javascript"></script>

<?php 

$file_array = array(
	$b . '_images/slide1.jpg',
	$b . '_images/slide2.jpg',
	$b . '_images/slide3.jpg',
	$b . '_images/slide8.jpg',
	$b . '_images/slide10.jpg' 
	); 
$total = count($file_array); 
$random = (mt_rand()%$total); 
// $noslideshow = "";
$file = (isset($noslideshow) ? "background-color:#705433;" :  "background-image:url("."$file_array[$random]".");"  ); 
?>
<style>
#headerimgs_ns { 	height:460px; 
					margin-top:-7px; 
					<?php echo $file;?> 
					width:100%; 
					background-position: center top;
					background-repeat: no-repeat;
					display:block;
			    }
</style>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16803157-7']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<!-- did not load google maps header -->
<body>	

<div id="header">
	<div class="main_content">
    	<a href="<?php echo $b . 'index.php/dir/index' ?>" alt"VECCHIO Trees" title="Home"><div id="logo"></div></a>
        <div id="search_drop">
			<?php $this->load->view('_chann/searchbar'); ?>
			<?php $this->load->view('_chann/dropdown'); ?>
        </div>

    </div><!-- End .container -->
  
</div><!-- End #header -->
<div id="big_blue"></div>
