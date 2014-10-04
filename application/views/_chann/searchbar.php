<div id="search_account">
	
	<div id="cart_links">
	<?php echo anchor('dir/myaccount', "Your Account" )?>&nbsp;&nbsp;|&nbsp;&nbsp;
	<?php 
	$my_session = $this->session->userdata('user_id');
	if(!empty($my_session))
		{
			echo anchor('login/logoutuser', "Log Out");
        }else{
			echo anchor('dir/log_in/new/', "Sign Up");
		}
		?>
		
	<?php 
	$num_in_cart = $this->session->userdata('num_in_cart');
	if($num_in_cart != 0){
		$s = ($num_in_cart > 1 ? "s" : "");
		echo "&nbsp;&nbsp;|&nbsp;&nbsp;";
		echo anchor('dir/myaccount/cart', "Check Out ".$num_in_cart . " Item".$s." &raquo;");
	}
	?>
	
	</div>
</div>


