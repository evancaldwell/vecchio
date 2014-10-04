<script>
$(document).ready(function() {

		// check logout 
		$.sessionTimeout({
	        logoutUrl : "<?php echo base_url() . 'index.php/login/logoutuser/' ?>",
			redirUrl : "<?php echo base_url() . 'index.php/login/logoutuser/' ?>",
			keepAliveUrl : "<?php echo base_url() . 'index.php/dir/keep_alive/' ?>", 
			message: "Your session with Vecchio Trees is about to expire"
	    });
	
	$( "#dwn_lnk" ).button();
	
});	
</script>
<div id="sign_container">
<div id="my_account">
	
				<div id="tagged_items">
				<div style="background-color:white; margin:16px; padding:20px;">
						<br />
							<br />
				<p style="font-size:16px;">
				Pay By Fax Approved</p>
				<p style="font-size:14px;"> Please Send Check By Fax Immediately For Processing
				</p>
				<?php
				if($type == 'order'){
				echo form_open('vecchio_download/check_by_fax_real');
				} else {
				echo form_open('vecchio_download/check_by_fax_qq');	
				}
				?>
				<?php if($type == 'order') { ?>
					<input type="hidden" name="order_id" value="<?php echo $id; ?>" />
				<?php } else { ?>
					<input type="hidden" name="qq_id" value="<?php echo $id; ?>" />
				<?php } ?>
				<button id="dwn_lnk">Download Check By Fax Form &#9660;</button>
				</form>
				<br />
					<br />
						<br />
							<br />
				</div>
								<div style="clear:both"></div>
				</div>
	<div style="clear:both"></div>
</div>
</div>