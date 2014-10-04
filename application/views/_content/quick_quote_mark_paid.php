<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script> 
<script>
 $(document).ready(function() {
	
$(".payment_date").datepicker();
$("#mark_qq_paid").validate({
	rules: {
		location: "required",
		ship_address: "required",
		ship_city: "required",
		ship_zip: "required",
		transaction_id : "required",
		payment_date : "required"
	},
	messages: {
		location: "Please Enter Location Name (Could Be Name of Person Receiving Shipment or Store Name, etc.)",
		ship_address: "Please Enter Shipping Address",
		ship_city: "Please Enter City",
		ship_zip: "Please Enter Zip Code",
		transaction_id : 'Please enter internal transaction ID',
		payment_date : 'Please enter payment date'
	}
});
});
</script>
<style>
label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
	color:red;
}
</style>
<h3>Mark Quote as Paid</h3>
<h4><?php echo $cust_name;?><br />
<?php echo 'Total: ' . $amount; ?><br />
<?php echo 'Freight: '. $freight; ?>
</h4>

<div>
	<?php
	$data = array('id'=> 'mark_qq_paid');
	 echo form_open('vecchio_admin/mark_qq_paid',$data); ?>

	<input name="qq_id" type="hidden" value="<?php echo $qq_id ?>" />
<p>
	<label>Enter Transaction ID</label>
	<input type="text" value="" id="transaction_id" name="transaction_id" />
</p>
<p>
	<label>Enter Payment Date</label>
	<input type="text" value="" id="payment_date" name="payment_date" class='payment_date' />
</p>
	<input type="hidden" name="amount" value="<?php echo $amount ?>" />
	<input type="hidden" name="freight" value="<?php echo $freight; ?>" />
<p>
<label>Job Name or Location</label>
<input name="location" id="location" />
</p>
<p>
<label>Location Phone</label>
<input name="location_phone" id="location_phone" />
</p>
<p>
<label>Ship Address</label>
<input name="ship_address" id="ship_address" />
</p>
<p>
<label>Ship City</label>
<input name="ship_city" id="ship_city" />
</p>
<p>
<label>Ship State</label>
<input name="ship_state" id="ship_state" />
</p>
<p>
<label>Ship Zip</label>
<input name="ship_zip" id="ship_zip" />
</p>
<p>	<input type="submit" value="Mark Paid" />
</p>
</form>
</div>