<?PHP
$year = date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?PHP echo $title;?></title>
<meta name="description" content="<?PHP echo $description;?>" />
<?php
echo link_tag('_css/default.css'); 
//echo link_tag('_css/navigation.css'); 
?>

<script type="text/javascript" src="<?php echo base_url();?>_js/jquery-1.4.3.min.js"></script>  
<script type="text/javascript" src="<?php echo base_url();?>_js/kuiper.js"></script>  

<link href="<?php echo base_url();?>_css/<?php 
$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
echo str_replace(".php", "",$parts[count($parts) - 1]);
?>.css" media="screen" rel="stylesheet" type="text/css" />
<?php if(isset($shipping) && $shipping == 'yes'){
	$this->load->view('vecchio_site/header_googlemap');
} else { ?>
</head>
<!-- did not load google maps header -->
<body>	
<?php }?>

<div id="footer_fix">
<div id="header">
<div class="float_fix">
	<div class="container">
		<!-- 
    	<div id="account">
			<div id="account_left"></div>
            <div id="account_middle">
            	<?php // echo anchor( 'vecchio_site/account', 'Customer Log In', 'title="Customer Login"' ); ?>  
            </div>
            <div id="account_right"></div>
   		</div> End #account -->
    	<a href="<?php echo base_url() . 'index.php/vecchio_site/index' ?>" alt"VECCHIO Trees" title="Home"><div id="logo"></div></a>
        <div id="nav">
        	<ul>
            	<li><?php echo anchor( 'vecchio_site/products', 'Products', 'title="Products"' ); ?></li>
                <li>|</li>
                <li><?php echo anchor( 'vecchio_site/contact', 'Contact', 'title="Contact"' ); ?></li>
				<li>|</li>
				<li><?php echo anchor( 'vecchio_site/sales', 'Sales', 'title="Sales"' ); ?></li>
				<li>|</li>
				<li><?php echo anchor( 'vecchio_site/about', 'Philosophy'); ?></li>
				<li>|</li>
				<li><?php echo anchor( 'vecchio_site/faq', 'Our Trees'); ?></li>
				<li>|</li>
				<li><?php echo anchor( 'vecchio_site/news', 'News', 'title="News"' ); ?></li>
				<li>|</li>
				<li><?php echo anchor( 'vecchio_site/account', 'Log In', 'title="Customer Login"' ); ?></li>  
           </ul>
        </div>
        <!--<div id="search">
        	<form action="" method="get" name="search">
            	<input name="searchField" id="searchField" type="text" value="SEARCH" onfocus="if(this.value=='SEARCH')this.value=''"/>
                <input name="searchSubmit" id="searchSubmit" type="submit" value="" />
            </form>
        </div> End #search -->
    </div><!-- End .container -->
        </div><!-- End .float_fix -->
</div><!-- End #header -->
<div id="bar_left"></div><div id="bar_mid"></div>