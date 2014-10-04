<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VECCHIO TREES</title>

<link rel="stylesheet" href="<?php echo base_url();?>_css/infopage.css" type="text/css" media="screen" />
</head>
<body>
<div id="main_box">
<h2 id="plant_h2"><?php echo $title; ?></h2>
<div id="view_inventory">
<?php
$uri = $this->uri->segment(3);
$data['target'] = 'parent';
 if($this->session->userdata('user_type') == 'rep'){
	echo anchor('vecchio_rep/product_by_type/' . $uri,  img('_images/viewinventory.png' ), $data); 
} else {
 	echo anchor('dir/sales/' . $uri,  img('_images/viewinventory.png' ), $data); 
}?>
</div>
<p id="description"><?php echo $description; ?></p>
<h3 id="zones_h3">Zones</h3>
<p id="zones"><?php echo $zones; ?></p>
<h3 id="water_h3">Watering &amp; Exposure</h3>
<p id="water"><?php echo $watering; ?></p>
</div><!-- end main_box -->
</body>
</html>