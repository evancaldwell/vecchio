<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VECCHIO TREES</title>

<!-- Default Slideshow CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/nivo-slider.css"  media="screen" />
<!--<link rel="shortcut icon" type="image/x-icon" href="_images/favicon.ico"> -->

<!-- Main Sytle CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/vecchio.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_css/nivo.css" media="screen" />

<script type="text/javascript" src="<?php echo base_url();?>_js/jquery-1.4.3.min.js"></script> 

<!-- slideshow js-->  
<script type="text/javascript" src="<?php echo base_url();?>_js/jquery.nivo.slider.pack.js"></script>


<script type="text/javascript"> 
 
    $(document).ready(function(){ 
 		$('#slider').nivoSlider({
		 effect: 'fade',
		 animSpeed: 200
		}); 
    }); 
 
</script>
</head>
<body>
	<div id="content">
		<p>Now you can enjoy all the sites and scenery of the Sierra Nevadas without having to carry anything! Our goats love the mountains, and they will do anything to get up there, including carrying your gear! Give us a call and let us know when you would like to schedule your next adventure with a friendly companion (a goat). </p>
	</div>
<?php
//echo "<pre>";
//print_r($images);
$count = count($images);
?>
<?php if(!empty($title)) echo "<h3>" . $title . "</h3>"; ?>
<div id="slider" class="nivoSlider" >
<?php
for($i=0; $i<$count; $i++){
	echo  "<img src=\"".base_url()."_fdr/".$images[$i]['file_name']."\"   title=".$images[$i]['serial_no']." />";
}

?>
</div>
</body>