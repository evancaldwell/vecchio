<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VECCHIO TREES</title>


<link rel="stylesheet" href="<?php echo base_url();?>_css/pepper-grinder/pepper.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>_css/supersized.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>_css/full_image.css" type="text/css" media="screen" />
<link href="<?php echo base_url(); ?>_css/jquery.countdown.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url();?>_js/jquery-1.6.4.min.js"></script>
<!-- the ui -->
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery-ui-1.8.16.custom.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.core.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.widget.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.datepicker.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.sortable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.effects.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.effects.slide.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/ui/jquery.ui.dialog.js"></script>
<script src="<?php echo base_url(); ?>_js/jquery.countdown.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>_js/supersized.3.1.3.min.js"></script>

<script type="text/javascript" >



$(function($){
		
	$.supersized({
	
		//Functionality
		slideshow               :   1,		//Slideshow on/off
		autoplay				:	1,		//Slideshow starts playing automatically
		start_slide             :   1,		//Start slide (0 is random)
		random					: 	1,		//Randomize slide order (Ignores start slide)
		slide_interval          :   7000,	//Length between transitions
		transition              :   1, 		//0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
		transition_speed		:	700,	//Speed of transition
		new_window				:	0,		//Image links open in new window/tab
		pause_hover             :   0,		//Pause slideshow on hover
		keyboard_nav            :   0,		//Keyboard navigation on/off
		performance				:	0,		//0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
		image_protect			:	1,		//Disables image  dragging and right click with Javascript
		image_path				:	'_images/', //Default image path

		//Size & Position
		min_width		        :   0,		//Min width allowed (in pixels)
		min_height		        :   0,		//Min height allowed (in pixels)
		vertical_center         :   1,		//Vertically center background
		horizontal_center       :   1,		//Horizontally center background
		fit_portrait         	:   1,		//Portrait images will not exceed browser height
		fit_landscape			:   0,		//Landscape images will not exceed browser width
		
		//Components
		navigation              :   0,		//Slideshow controls on/off
		thumbnail_navigation    :   0,		//Thumbnail navigation
		slide_counter           :   0,		//Display slide numbers
		slide_captions          :   0,		//Slide caption (Pull from "title" in slides array)
		slides 					:  	[		//Slideshow Images
								<?php
								$count = count($images);
								for($i=0; $i<$count; $i++){
									echo "{image : '" . base_url()."_fdr/".$images[$i]['file_name'] . "'}" . ($i == ($count-1) ? '' : ',') . "\r\n";
								}
								?>
									]
									
	});
	
		});
		$(document).ready(function() {
			var targetUrl = '<?php echo base_url() . "index.php/dir/myaccount/cart" ?>';
			$('#dialog-confirm').dialog({
			    autoOpen: false,
				width:500,
				height:225,
			    modal: true,
			    resizable: false,
				buttons: {
				           'Add More Products': function() {
							//	parent.$.fancybox.close();
								window.parent.location.href = window.parent.location.href;	 
				         },
						   'Check Out': function(){
							window.parent.location.href = targetUrl;
						 }
						
				},
				close: function() {
					parent.$.fancybox.close(); 	
				}
				
			});	
			
			$('#dialog-error').dialog({
			    autoOpen: false,
				width:500,
				height:225,
			    modal: true,
			    resizable: false,
				buttons: {
				           'Okay': function() {
								parent.$.fancybox.close(); 	 
				         }
						
				}
				
			});
			
			
			   
			$("#add_to_cart").live('click', function() {
				
		        $.post("<?php echo base_url()?>index.php/dir/add_product", {
					product_id: <?php echo $info[0]['id'];?>
				},
		        function(response){
		            if(response.indexOf("Success") != -1 ){
						$('#dialog-confirm').dialog('open');
					} else {
						$('#dialog-error').dialog('open');
					}
		        });
			});		       
			
				
			<?php if($info[0]['expire_date'] != ''){ ?>
				var t = "<?php echo $info[0]['expire_date']; ?>".split(/[- :]/);

				// Apply each element to the Date function
				var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
				$('#defaultCountdown').countdown({until: d });
			
			<?php } ?>
	});

</script>

<style>
#defaultCountdown { width: 240px; height: 37px; padding:3px;}	
</style>

</head>
<body>
<?php if($active != 'false'){	?>
	<div id="dialog-confirm" title="Tree Added To Cart">
		    <p>
			<br />
			</p>
			<p>
				<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
				You have successfully added a <?php echo $info[0]['specs'];?> <?php echo $info[0]['description'];?><br />
				Serial no:   <b><?php echo $info[0]['serial_no'];?></b> to your cart.
			</p>
			<p>
				Click on <b>My Account</b> to complete your order, or close window to browse more products. 
			</p>
	</div>
	<div id="dialog-error" title="Error - Product Not Available">
		    <p>
			<br />
			</p>
			<p>
			 	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>There was an error adding this product, most likely it was tagged by another user a few seconds before you. Please choose another from our vast inventory. 
			</p>
	</div>
	
	<?php } // print_r($info); ?>
	<div id="prod_info_box" >
		<div class="top"></div>
		<div class="mid">
			<div class="mid_info">
				<?php if($info[0]['status'] == ""){?>
						<?php if($info[0]['pr'] == 'green') { ?>
							<h1 class="avail">Available for Purchase</h1>
						<?php } else { ?>
							
							<?php
							$img = '<img src="'. base_url() . '_images/avail_purchase.png" border="0" />' ;
							$data = array('target' => 'parent');
							 echo anchor('dir/log_in/new', $img, $data ); ?><br />
						<?php } ?>
				<?php } else if($info[0]['status'] == 1) {?>
				<h2 class="tagged">Item Tagged for Purchase</h1>
				<?php } else { ?>
				<h2 class="tagged">Item Purchased</h1>
				<?php } ?>
				<table>
					<tr><td>Serial no: </td>
						<td><?php echo $info[0]['serial_no'];?></td>
					</tr>
					<tr><td>Description: </td>
						<td><?php echo $info[0]['description'];?></td>
					</tr>
					<tr><td>Specs: </td>
						<td><?php echo $info[0]['specs'];?></td>
					</tr>
					<?php 
				if($active != 'false'){
					if($info[0]['pr'] == 'green') {?>
					<tr>
						<td>List Price: </td>
						<td>$<?php echo number_format($info[0]['list_price'], 2, '.', ',');?></td>
					</tr>
						<?php if($info[0]['status'] == "") { ?>
							<tr>
								<td>Your Price </td>
								<td ><span class="your_price">$<?php echo number_format($info[0]['customer_price'], 2, '.', ',') ;?></span></td>
							</tr>
							<tr><td colspan= "2" align="right" >
								<br />
						
								<?php 
								$pt = $info[0]['product_type_id'];
								if($pt == 1 || $pt == 7 || $pt == 5){ ?>
								To order, please contact <?php
								$data = array(
									'target' => 'parent'
								);
								 echo anchor('dir/vecchio_sales/', 'VECCHIO Sales', $data); ?>
								<?php } else { ?>
								<input type="image" id="add_to_cart"  src="<?php echo base_url(). "_images/addtocart.png"?>" />
					    		<?php }?>
							</td>
							</tr>
						<?php } else if ($info[0]['status'] == "1"){ ?>
						<tr><td colspan= "2"  >
							<b>Product may become available in: </b>
							<div id="defaultCountdown"></div>
							</td>
						</tr>			
					 <?php } 					
					 } else {?>
						<tr><td colspan= "2"  >
							<b>Please log in for pricing info</b>
							</td>
						</tr>	
					<?php }?>
				<?php } // end if active site ?>
				</table>
			</div>
		</div>
		<div class="bot"></div>
	</div>


</body>
