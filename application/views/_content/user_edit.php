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
					minlength: 5
				},
				confirm_password: {
					minlength: 5,
					equalTo: "#password"
				},
				usern_email: {
					required: true,
					email: true
				},
				phone: {
					required: true
				}
			},
			messages: {
				fname: "Please enter your First Name",
				lname: "Please enter your Last Name",
				company_name: "Please enter Company Name",
				password: {
					minlength: "Your password must be at least 5 characters long"
				},
				phone:{
					required: "Please enter phone number"
				},
				confirm_password: {
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"
				},
				usern_email: "Please enter a valid email address"
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
<h2 style="border-bottom: 0px;">Edit User: <?php echo $user[0]['lname'] . ", " . $user[0]['fname'] ?></h2>
<h3 >Contact Info</h3>
<?php 
$data = array(
       'id' => 'new_user'
);
if($admin){ 
echo form_open('vecchio_admin/edituserinfo', $data);
} else {
echo form_open('vecchio_rep/edituserinfo', $data);	
}

?>
<div>
	<label>First Name<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'fname',
		'value' => $user[0]['fname'],
		'class' => 'required'
	);
	echo form_input($data);
	echo form_hidden('id', $user[0]['id']);
	?>
</div>
<div>
	<label>Last Name<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'lname',
		'value' => $user[0]['lname'],
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
		'value' => $user[0]['company_name'],
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
		'value' => $user[0]['usern_email'],
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
		'value' => $user[0]['phone'],
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
		'value' => $user[0]['fax'],
		'id' => 'fax',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>

<h3 >User Type</h3>
<div>
	<label>User Type<span class="req">*</span></label>
	<?php echo form_radio('user_type', 'architect', ($user[0]['user_type'] == 'architect' ? TRUE : FALSE))?> Architect
	<?php echo form_radio('user_type', 'homeowner', ($user[0]['user_type'] == 'homeowner' ? TRUE : FALSE))?> Homeowner
	<?php echo form_radio('user_type', 'landscaper', ($user[0]['user_type'] == 'landscaper' ? TRUE : FALSE))?> Landscaper
	<?php echo form_radio('user_type', 'contractor', ($user[0]['user_type'] == 'contractor' ? TRUE : FALSE))?> Contractor
	<?php echo form_radio('user_type', 'distributor', ($user[0]['user_type'] == 'distributor' ? TRUE : FALSE))?> Distributor
	<?php echo form_radio('user_type', 'nursery', ($user[0]['user_type'] == 'nursery' ? TRUE : FALSE))?> Nursery
	<?php echo form_radio('user_type', 'broker', ($user[0]['user_type'] == 'broker' ? TRUE : FALSE))?> Broker
<?php if($admin){ ?>	
	<?php echo form_radio('user_type', 'rep', ($user[0]['user_type'] == 'rep' ? TRUE : FALSE))?> Rep
	<?php echo form_radio('user_type', 'admin', ($user[0]['user_type'] == 'admin' ? TRUE : FALSE))?> Admin (access to SMS)
<?php } ?>	
	
</div>
<div>
	<label>Password<span class="req">*</span></label>
	<?php
	$data = array(
		'name' => 'password',
		'type' => 'password',
		'id' => 'password',
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
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<h3 >Billing Information (optional)</h3>
<?php if($admin){?>
<div>
	<label>Discount Multiplier</label>

<?php echo form_radio('multiplier', '0.25',($user[0]['multiplier'] == '0.25' ? TRUE : FALSE))?> 25%
<?php echo form_radio('multiplier', '0.50',($user[0]['multiplier'] == '0.50' ? TRUE : FALSE))?> 50%
<?php echo form_radio('multiplier', '0.75',($user[0]['multiplier'] == '0.75' ? TRUE : FALSE))?> 75%
<?php echo form_radio('multiplier', '0.85',($user[0]['multiplier'] == '0.85' ? TRUE : FALSE))?> 85%
<?php echo form_radio('multiplier', '0.90',($user[0]['multiplier'] == '0.90' ? TRUE : FALSE))?> 90%
<?php echo form_radio('multiplier', '1.00',($user[0]['multiplier'] == '1.00' ? TRUE : FALSE))?> 100%

</div>
<?php } ?>
<div>
	<label>Billing Address</label>
	<?php
	$data = array(
		'name' => 'billing_address',
		'value' => $user[0]['bill_address'],
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
		'value' => $user[0]['bill_city'],
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
		'value' => $user[0]['bill_state'],
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
		'value' => $user[0]['bill_zip'],
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
		'value' => $user[0]['license_number'],
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Assign Rep</label>
	<select id="rep_id" name="rep_id">	
		<?php
		$count = count($reps);
		echo "<option value='0' ".($user[0]['rep_id'] == '0' ? 'selected' : '')." >House Account</option>\r\n";
		foreach($reps as $rep){
			echo "<option value='".$rep['id']."' ".($user[0]['rep_id'] == $rep['id'] ? 'selected' : '')." >".$rep['fname']." ".$rep['lname']."</option>\r\n";	
		}
		?>
	</select>
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
		'value' => $user[0]['net_terms']
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Credit Limit (For $3,000 enter 3000)</label>
	<?php
	$val = ($user[0]['credit_limit'] == '0.00' ? 0 : $user[0]['credit_limit']);
	
	$data = array(
		'name' => 'credit_limit',
		'id' => 'credit_limit',
		'style' => 'width:150px;',
		'value' => $val
	);
	echo form_input($data);
	?>
</div>
<?php } else {
	$val = ($user[0]['credit_limit'] == '0.00' ? 0 : $user[0]['credit_limit']);
	
	 ?>
	<input type="hidden" id="credit_limit" name="credit_limit" value="<?php echo $val;?>" />
	<input type="hidden" id="terms "name="terms" value="<?php echo $user[0]['net_terms']; ?>" />
<?php } ?>
<div>
	<?php echo form_submit('submit', 'Edit User');?>
</div>
<?php echo form_close();?>
<br />
<br />
