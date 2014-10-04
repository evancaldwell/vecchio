<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/maskedinput.js" type="text/javascript"></script>
<script type="text/javascript"> 
$(document).ready(function() {
    $("#zipcode").mask("99999");
	$("#location_phone").mask("(999) 999-9999");
	// validate signup form on keyup and submit
	    $('#Loading').hide();
	    $('#Check_Date').hide();
		$("#new_shipping").validate({
			rules: {
				location: "required",
				ship_address: "required",
				ship_city: "required",
				ship_zip: "required"
			},
			messages: {
				location: "Please Enter Location Name (Could Be Name of Person Receiving Shipment or Store Name, etc.)",
				ship_address: "Please Enter Shipping Address",
				ship_city: "Please Enter City",
				ship_zip: "Please Enter Zip Code"
			}
		});

				
		function log(message) {

			$.ajax({
	        	url: '<?php echo site_url('search/by_id');?>',
	        	data: "term=" + message,
				type: 'POST',
	        	dataType: "json",
	        	success: function(data){
		            // .text(html) 
		            var new_order = '<?php echo site_url('vecchio_admin/new_shipping/');  ?>';
					$("<li><input type='radio' name='customer_id' value='" + data.id + "' checked /> " + data.label + "</li>").prependTo("#log");
					// $("#log").attr("scrollTop", 0);
			        $("#cond_picker").val("");
	        	}

	    	});

		}

		$("#ajax_search").autocomplete({
	            minLength: 1,
	            source: function(req, add){
	                $.ajax({
	                    url: '<?php echo site_url('search');?>',
	                    dataType: 'json',
	                    type: 'POST',
	                    data: req,
	                    success: function(data){
	                        if(data.response =='true'){
	                           add(data.message);
	                        }
	                    }
	                });
	            },
	            select: function(event, ui){
	              log(ui.item ? (ui.item.id) : "No Selection");
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
	font-size:9px;
}
span.req {
	color:#990000;
}
</style>
<h2 style="border-bottom: 0px;">Start New Order</h2>
<br />
<?php 
$data = array(
       'id' => 'new_shipping'
);
$where = ($this->session->userdata('user_type') == 'rep' ? 'rep' : 'admin');
echo form_open('vecchio_'.$where.'/add_new_order', $data);?>
<div>
<div>
<label>Search For Customer By Name</label>
<input id="ajax_search" type="text" />
</div> 
 
<ul id="log" class="ajax_search_ul" >
<?php if($this->session->userdata('user_type') != 'rep'){ ?>
<li><?php echo anchor('vecchio_admin/add_user_form','Create New Customer Account &raquo;');?> </li>
<?php } else { ?>
<li><?php echo anchor('vecchio_rep/add_user_form','Create New Customer Account &raquo;');?> </li>	
<?php } ?>
</ul>

</div>

<?php // $this->load->view('_content/new_shipping_extra'); ?>
<h3>Enter Shipping Destination</h3>
<div>
	<label>Job Name or Id </label>
	<?php
	$data = array(
		'name' => 'location',
		'style' => 'width:200px;'
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
		'id' => 'location_phone'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Shipping Address</label>
	<?php
	$data = array(
		'name' => 'ship_address',
		'style' => 'width:200px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Shipping City</label>
	<?php
	$data = array(
		'name' => 'ship_city',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Shipping State</label>
	<?php
	$data = array(
		'name' => 'ship_state',
		'style' => 'width:20px;'
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
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>

<br /><br />
<div>
	<?php echo form_submit('submit', 'Start Quote & Tag Products');?>
</div>
<?php echo form_close();?>
<br />
<br />