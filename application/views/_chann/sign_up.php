<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">
var targetUrl = '<?php echo base_url() . "index.php/dir/myaccount/" ?>';
</script>
<script src="<?php echo base_url(); ?>_js/sign_up.js" type="text/javascript"></script>
<script type="text/javascript">


function send_post_ajax(){
	$.post("<?php echo base_url()?>index.php/dir/addnewuser", {
		company_name: $('#company_name').val(),
		fname: $('#fname').val(),
		lname: $('#lname').val(),
		usern_email: $('#usern_email').val(),
		password: $('#password').val(),
		user_type: $('#user_type').val(),
		license_number: $('#license_number').val(), 
		phone: $('#phone').val(),
		fax: $('#fax').val(),
		billing_address: $('#billing_address').val(),
		billing_city: $('#billing_city').val(),
		billing_state: $('#billing_state').val(),
		billing_zip: $('#billing_zip').val(),
		rep_id: $('#rep_id').val()
		
	}, function(response){

	    // check if the response said date was available
	    if(response.indexOf("Success") != -1 ){
			$('#newacc_container').hide();
		    $( "#newacc_success" ).dialog( "open" );
			$("#login_container").show();
		} else if(response.indexOf("Fail") != -1 ){
			
					$( "#newacc_fail" ).dialog( "open" );
		}
	});
	return false;
}
/*
function send_post_login(){
	var urlnew = '<?php echo base_url() . "index.php/dir/myaccount/" ?>';
	var urlt1 = '<?php echo base_url() . "index.php/vecchio_admin/" ?>';
	var urlt2 = '<?php echo base_url() . "index.php/vecchio_rep/" ?>';
	$.post("<?php echo base_url()?>index.php/dir/log_in_capture", {

		usern_email: $('#usern_email_login').val(),
		password: $('#password_login').val()

	}, function(response){

	    // check if the response said date was available
	    if(response.indexOf("Success") != -1 ){
			
			window.location.href= urlnew;
		} else if(response.indexOf("Fail") != -1)  {
		//	alert(response);
			$('#loginerror').fadeIn();
			
		} else if(response.indexOf("T1") != -1)  {
			window.location.href= urlt1;
		} else if(response.indexOf("T2") != -1)  {
			window.location.href= urlt2;
		}
	});
	return false;
}

*/




</script>
<style>
label.error {
	margin-left:10px;
	letter-spacing:1px;
	width: auto;
	display: inline;
	color:#990000;
	font:10px gill sans regular, sans-serif;
	font-weight:bold;

}
span.req {
	color:#990000;
}
</style>
	<link href="<?php echo base_url(); ?>_css/signup.css" rel="stylesheet" type="text/css" />
	<!-- jQuery handles to place the header background images --> 
<div id="headerimgs_ns"> 
<?php
$section = $this->uri->segment(3);
$signout = $this->uri->segment(4);
?>
	<div id="news_window">
	<div id="signslide">
		<div id="signflower"></div>
		<div id="signbox">

			<div id="newacc_success" style="display:none;">
				<p><span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
					Success! Your New Account has been created
				</p>
				<p>Welcome to Vecchio Trees. You have been granted access to order from our inventory of fine specimen trees </p>
			</div>
			<div id="newacc_fail" style="display:none;" title="Error - Email / Username">
				<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Error in New account form</p>
				<p>The email you entered is part of an existing account with VECCHIO Trees. Please check your email box for copy of log in credentials</p>	
			</div>
	<div id="newacc_container" <?php echo ($section == 'new' || $section == '' ? "" : "style='display:none;'"); ?> >
		
			<div class="signblock">
				<h1>NEW ACCOUNT <span style="font-size:9px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Already have an account? <button class="login">Log In</button></span></h1>
			</div>
			<form id="signupslide" action="<?php echo base_url() . "dir/signup/" ?>" method="post">
			<div id="signleft">
				<div class="signblock">
					<input type="text" name="company_name" id="company_name">
				</div>
				<div class="signblock">
					<h2 id="error-company_name" >Company Name</h2>
				</div>
				<div class="signblock">
					<input type="text" name="fname" id="fname">
				</div>
				<div class="signblock">
					<h2 id="error-fname" >First Name</h2>
				</div>
				<div class="signblock">
					<input type="text" name="lname" id="lname">
				</div>
				<div class="signblock">
					<h2 id="error-lname" >Last Name</h2>
				</div>
				<div class="signblock">
					<input type="text" name="usern_email" id="usern_email">
				</div>
				<div class="signblock">
					<h2 id="error-usern_email" >Email</h2>
				</div>
			
				<div class="signblock">
					<input type="password" name="password" id="password">
				</div>
				<div class="signblock">
					<h2 id="error-password">Password</h2>				
				</div>
				<div class="signblock">
					<input type="password" name="confirm_password" id="confirm_password">
				</div>
				<div class="signblock">
					<h2 id="error-confirm_password">Confirm Password</h2>				
				</div>
				<div class="signblock">
					<select name="user_type" id="user_type" class="required" >
						<option selected="" value="">--Select One--</option>
						<option value="landscaper">Landscaper</option>
						<option value="architect">Architect</option>
						<option value="homeowner">Home Owner</option>
						<option value="contractor">Contractor</option>
						<option value="nursery">Nursery</option>
						<option value="broker">Broker</option>
						<option value="distributor">Distributor</option>
					</select>
				</div>
				<div class="signblock">
					<h2 id="error-user_type" >Title (Occupation)</h2>
				</div>
				<div class="signblock">
					<input type="text" name="license_number" id="license_number">
				</div>
				<div class="signblock">
					<h2 id="error-licence_number">License Number</h2>
				</div>
			</div><!-- end signleft -->
			<div id="signmid"></div>
			<div id="signright">

				<div class="signblock">
					<input type="text" name="phone" id="phone">
				</div>
				<div class="signblock">
					<h2 id="error-phone">Phone</h2>
				</div>
				<div class="signblock">
					<input type="text" name="fax" id="fax">
				</div>
				<div class="signblock">
					<h2>Fax</h2>
				</div>
				<div class="signblock">
					<input type="text" name="billing_address" id="billing_address" class="required">
				</div>
				<div class="signblock">
					<h2 id="error-billing_address">Address</h2>
				</div>
				<div class="signblock">
					<input type="text" name="billing_city" id="billing_city" class="required">
				</div>
				<div class="signblock">
					<h2 id="error-billing_city">City</h2>				
				</div>
				<div class="signblock">
					<input type="text" name="billing_state" id="billing_state" class="required">
				</div>
				<div class="signblock">
					<h2 id="error-billing_state">State</h2>				
				</div>
				<div class="signblock">
					<input type="text" name="billing_zip" id="billing_zip" class="required">
				</div>
				<div class="signblock">
					<h2 id="error-billing_zip" >Zip</h2>
				</div>
				<div class="signblock">
					<select name="rep_id" id="rep_id" class="required" >
						<option selected value="">--Select One--</option>
						<option value="0">No Referral</option>
						<?php
						foreach($reps as $rep){
							echo "<option value='" . $rep['id'] . "' >" . $rep['fname'] . " " . $rep['lname'] . "</option> \r\n";
						}
						?>
					</select>
				</div>
				<div class="signblock">
					<h2 id="error-rep_id" >Referred By</h2>
				</div>
				<div class="signblock">
					<input type="submit" name="regsub" id="regsub" value="CREATE ACCOUNT">
				</div>
				</form>
			</div><!-- end signright -->
	</div><!-- end sign newacc_container-->
	<div id="login_container" <?php echo ($section == 'my_account' ? "" : "style='display:none;'"); ?> >
		
				
				<div id="loginslide">
					<br />
					<br />
					<div class="signblock" style="<?php echo ($signout == 'logout' ? "" : "display:none;"); ?>">
						<p style="color:#990000; font-size:14px; " >You have logged out</p>
					</div>
					<div class="signblock" style="<?php echo ($signout == 'login_error' ? "" : "display:none;"); ?>">
						<p style="color:#990000; font-size:14px; " >Unknown username / password combination, please re-enter information.</p>
					</div>
				<div class="signblock">
					<h1>Log In &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-size:9px"> 	Or <button class="signup">Create New Account</button></span>  </h1>
				<form id="loginslidees" action="<?php echo base_url()?>index.php/dir/log_in_capture" method="POST" />
				</div>
				<div class="signblock">
					<input type="text" name="usern_email_login" id="usern_email_login">
				</div>
				<div class="signblock">
					<h2 id="error-usern_email_login">Email</h2>				
				</div>
				<div class="signblock">
					<input type="password" name="password_login" id="password_login">
				</div>
				<div class="signblock">
					<h2 id="error-password_login" >Password</h2>
				</div>
				<div class="signblock">
					<p id="loginerror" style="color:#990000; font-size:12px; display:none;" >Unknown username / password combination</p>
				</div>
				
				<div class="signblock">
					<input type="submit" name="regsub" id="regsub" value="Log In">
				</div>
				
				</form>
				<br />
				<br />
				<button id="forgot"><span style="font-size:9px">Forgot Password</span></button>
			</div><!-- ned login slide -->
			
	</div><!-- end login_container -->

		</div><!-- end signbox -->
		</div><!-- end signslide -->
		</div>
	</div>
</div>
<div id="forgot_password" title="Forgot Password" style="display:none;">
		<p>
		<b>Please check your initial welcome email for instructions on resetting your password.</b>
		</p>
		<p><b>For password assistance contact admin@vecchiotrees.com. We will respond as quickly as possible. </b></p>

</div>