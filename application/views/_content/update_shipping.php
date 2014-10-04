<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/maskedinput.js" type="text/javascript"></script>
<script type="text/javascript"> 
$(document).ready(function() {
    $("#zipcode").mask("99999");
	// validate signup form on keyup and submit
	    $('#Check_Date').hide();
	
		function is_will_call(){
			return ($('input:radio[name=will_call]:checked').val() == '0');
		}
	
		$("#new_shipping").validate({
			rules: {
				location: "required",
				location_phone: "required",
				ship_address: {
					required: {
						depends: function(element) {
		                   return is_will_call();
						}
					}
				},
				ship_city: {
					required: {
						depends: function(element) {
		                   return is_will_call();
						}
					}
				},
				ship_state: {
					required: {
						depends: function(element) {
		                   return is_will_call();
						}
					}
				},
				ship_zip: {
					required: {
						depends: function(element) {
		                   return is_will_call();
						}
					}
				}
			},
			messages: {
				location: "Please Enter Location Name (Could Be Name of Person Receiving Shipment or Store Name, etc.)",
				ship_address: "Please Enter Shipping Address",
				ship_city: "Please Enter City",
				ship_state: "Please Enter State",
				ship_zip: "Please Enter Zip Code"
			}
		});
		
			
});
</script>
<style>
label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
	color:#990000;
	font-weight:bold;
}
span.req {
	color:#990000;
}
</style>
<h2 style="border-bottom: 0px;">Shipping For Order : <?php echo urldecode($order_name); ?></h2>
<br />
<?php 
$data = array(
       'id' => 'new_shipping'
);
if($admin){ 
echo form_open('vecchio_admin/update_shipping_capture', $data);
} else {
echo form_open('vecchio_rep/update_shipping_capture', $data);	
}
if(isset($shipping[0]['location'])){ // update 
	$location = $shipping[0]['location'];
	$location_phone = $shipping[0]['location_phone'];
	$ship_address = $shipping[0]['ship_address'];
	$ship_zip = $shipping[0]['ship_zip'];
	$ship_city = $shipping[0]['ship_city'];
	$ship_state = $shipping[0]['ship_state'];
} else {
	$location = '';
	$location_phone = '';
	$ship_address = '';
	$ship_zip = '';
	$ship_city = '';
	$ship_state = '';
}


// $this->load->view('_content/new_shipping_extra'); ?>
<h3>Enter Shipping Destination</h3>
	<?php
		$will_yes = '';
		$will_no = '';
	if($shipping[0]['will_call'] == 1){
		$will_yes = 'checked';
	} else {
		$will_no = 'checked';
	}
	?>
<div>
		<input type="radio" name="will_call" value="0" <?php echo $will_no; ?> > Ship to Location <br />
		<input type="radio" name="will_call" value="1" <?php echo $will_yes; ?> > Will Call / Customer Pickup
</div>
<div>
	<label>Shipping Destination Name (Location Name or Resident's Name) </label>
	<?php
	$data = array(
		'name' => 'location',
		'style' => 'width:200px;',
		'value' => $location
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Location Phone</label>
	<?php
	$data = array(
		'name' => 'location_phone',
		'style' => 'width:200px;',
		'value' => $location_phone
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Shipping Address</label>
	<?php
	$data = array(
		'name' => 'ship_address',
		'style' => 'width:200px;',
		'value' => $ship_address
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Shipping City</label>
	<?php
	$data = array(
		'name' => 'ship_city',
		'style' => 'width:150px;',
		'value' => $ship_city
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Shipping State</label>
	<?php
	$data = array(
		'name' => 'ship_state',
		'style' => 'width:20px;',
		'value' => $ship_state
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Shipping Zip</label>
	<?php
	$data = array(
		'name' => 'ship_zip',
		'id' => 'zipcode',
		'style' => 'width:100px;',
		'value' => $ship_zip
	);
	echo form_input($data);
	?>
</div>
<?php if(isset($shipping[0]['location'])){ ?>
<input type="hidden" name="shipping_id" value="<?php echo $shipping[0]['id']; ?>" />
<?php } else { ?>
<input type="hidden" name="shipping_id" value="0" />	
<?php } ?>
<input type="hidden" name="order_name" value="<?php echo $order_name; ?>" />
<input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
<br /><br />
<div>
	<?php echo form_submit('submit', 'Update Shipping Destination');?>
</div>
<?php echo form_close();?>
<br />
<br />