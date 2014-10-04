
  
	    <h3>Log In</h3>

		<div class="loginbox" >
	        <?php 
			echo form_open('dir/log_in_capture');
			echo "<span style=\"margin-left:25px;\">Email</span> <br />";
			echo form_input('usern_email_login');
			echo "<br />";
			echo "<span style=\"margin-left:25px;\">Password</span><br />"; 
			echo form_password('password_login');
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />"; 
			echo form_submit('submit', 'Login');
			echo form_close();
			?>
		   <p style="color:#990000;"><?php echo $error;?></p>	
		</div>
