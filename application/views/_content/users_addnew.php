<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/maskedinput.js" type="text/javascript"></script>  

<script type="text/javascript"> 
$(document).ready(function() {
	$("#phone").mask("(999) 999-9999");
	$("#fax").mask("(999) 999-9999");
    $("#zipcode").mask("99999");
	// validate signup form on keyup and submit
		$("#new_user").validate({
			rules: {
				fname: "required",
				lname: "required",
				company_name: "required",
				password: {
					required: true,
					minlength: 5
				},
				confirm_password: {
					required: true,
					minlength: 5,
					equalTo: "#password"
				},
				usern_email: {
					required: true,
					email: true
				},
				phone: {
					required: true
				},
				credit_limit: {
					required: true,
					number:true
				},
				terms: {
					required: true,
					number:true
				}
			},
			messages: {
				fname: "Please enter your First Name",
				lname: "Please enter your Last Name",
				company_name: "Please enter Company Name",
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				phone:{
					required: "Please enter phone number"
				},
				confirm_password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"
				},
				usern_email: "Please enter a valid email address",
				credit_limit: "Please enter number",
				terms: "Please enter number"
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
<h2 style="border-bottom: 0px;">Add New User</h2>
<h3 >Upon form completion, user will receive welcome email with username / password.</h3>
<?php 
$data = array(
       'id' => 'new_user'
);
if($admin){ 
echo form_open('vecchio_admin/addnewuser', $data);
} else {
echo form_open('vecchio_rep/addnewuser', $data);	
}

if(!$admin){ ?>
<input type="hidden" name="rep_id" value="<?php echo $this->session->userdata('user_id'); ?>" />	
<?php } ?>


<div>
	<label>First Name<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'fname',
		'class' => 'required'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Last Name<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'lname',
		'class' => 'required'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Company Name<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'company_name',
		'class' => 'required'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Email (will also be used as username)<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'usern_email',
		'class' => 'required email',
		'style' => 'width:200px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Phone<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'phone',
		'id' => 'phone',
		'class' => 'required',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Fax</label>
	<?php
	$data = array(
		'name' => 'fax',
		'id' => 'fax',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>

<h3 >User Type</h3>
<div>
	<label>User Type<span class="req">*</span></label>
	<?php echo form_radio('user_type', 'architect', TRUE)?> Architect
	<?php echo form_radio('user_type', 'homeowner', FALSE)?> Homeowner
	<?php echo form_radio('user_type', 'landscaper', FALSE)?> Landscaper
	<?php echo form_radio('user_type', 'contractor', FALSE)?> Contractor
	<?php echo form_radio('user_type', 'nursery', FALSE)?> Nursery
	<?php echo form_radio('user_type', 'broker', FALSE)?> Broker
	<?php echo form_radio('user_type', 'distributor', FALSE)?> Distributor
	<?php if($admin){?>
	<?php echo form_radio('user_type', 'rep', FALSE)?> Rep
	<?php echo form_radio('user_type', 'admin', FALSE)?> Admin (access to SMS)
	<?php } ?>
</div>
<div>
	<label>Password<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'password',
		'type' => 'password',
		'id' => 'password',
		'class' => 'required',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Confirm Password<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'confirm_password',
		'type' => 'password',
		'class' => 'required',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<h3 >Billing Information (optional)</h3>
<?php if($admin){?>
<div>
	<label>Discount Multiplier</label>

<?php echo form_radio('multiplier', '0.25', FALSE)?> 25%
<?php echo form_radio('multiplier', '0.50', FALSE)?> 50%
<?php echo form_radio('multiplier', '0.75', FALSE)?> 75%
<?php echo form_radio('multiplier', '0.85', FALSE)?> 85%
<?php echo form_radio('multiplier', '0.90', FALSE)?> 90%
<?php echo form_radio('multiplier', '1.00', TRUE)?> 100%

</div>
<?php } ?>
<div>
	<label>Billing Address</label>
	<?php
	$data = array(
		'name' => 'billing_address',
		'style' => 'width:200px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Billing City</label>
	<?php
	$data = array(
		'name' => 'billing_city',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Billing State</label>
	<?php
	$data = array(
		'name' => 'billing_state',
		'style' => 'width:20px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Billing Zip</label>
	<?php
	$data = array(
		'name' => 'billing_zip',
		'id' => 'zipcode',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<h3 >License Information (optional)</h3>
<div>
	<label>License #</label>
	<?php
	$data = array(
		'name' => 'license_number',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<?php if($this->session->userdata('usern_email') == 'tylerpenney@gmail.com' || $this->session->userdata('usern_email') == 'kara@vecchiotrees.com'){?>
<h3 >Payment Terms (in Days) and Credit Limit</h3>
<div>
	<label>Terms</label>
	<?php
	$data = array(
		'name' => 'net_terms',
		'id' => 'terms',
		'style' => 'width:150px;',
		'value' => '0'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Credit Limit (For $3,000 enter 3000)</label>
	<?php
	$data = array(
		'name' => 'credit_limit',
		'id' => 'credit_limit',
		'style' => 'width:150px;',
		'value' => '0'
	);
	echo form_input($data);
	?>
</div>
<?php } else { ?>
	<input type="hidden" id="credit_limit" name="credit_limit" value="0" />
	<input type="hidden" id="terms "name="terms" value="0" />
<?php } ?>
<div>
	<?php echo form_submit('submit', 'Add New User');?>
</div>
<?php echo form_close();?>
<br />
<br />