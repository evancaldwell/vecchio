<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/maskedinput.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/jquery.countdown.js" type="text/javascript"></script>
<!-- fancybox for image preview -->
<script type="text/javascript" src="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<!-- Fancybox (pop up window thing) css  -->
<link rel="stylesheet" href="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

	<script>
		 $(document).ready(function() {

				// check logout 
				$.sessionTimeout({
			        logoutUrl : "<?php echo base_url() . 'index.php/login/logoutuser/' ?>",
					redirUrl : "<?php echo base_url() . 'index.php/login/logoutuser/' ?>",
					keepAliveUrl : "<?php echo base_url() . 'index.php/dir/keep_alive/' ?>", 
					message: "Your session with Vecchio Trees is about to expire"
			    });
			
			$( "#dwn_lnk" )
						.button()
						.click(function() {
								$( "#dwn_notice" ).dialog( "open" );
			});

			$('#dwn_notice').dialog({
			    autoOpen: false,
				width:500,
				height:275,
			    modal: true,
			    resizable: false,
				buttons: {
				           'Close': function() {
								$(this).dialog( "close" ); 
				         }

				}

			});	
			
	<?php if($yesno){ ?>
		
		
		
	 	$('#Check_Date').hide();
		$("#new_shipping").validate({
			rules: {
				ship_date: "required"
			},
			messages: {
				ship_date: "Please choose date for order shipment"
			}
		});
		function check_ship(dateText){
			$('#Check_Date').show();
			$.post("<?php echo base_url()?>index.php/dir/check_shipping_date", {
				ship_date: dateText
			}, function(response){
				$('#Check_Date').hide();
				$('#Check_Date').html(unescape(response));
			    $('#Check_Date').fadeIn();
			    // check if the response said date was available
			    if(response.indexOf("Available") != -1 ){
				 	$( "#schedule" ).button({
						disabled: false
					});
				} else {
					$( "#schedule" ).button({
						disabled: true
					});
				} 
			});
			return false;		
		}
		
		$("#ship_date").datepicker({
   		onSelect: function(dateText, inst) {
			check_ship(dateText)
   		},
   		minDate : new Date(<?php echo $start_date ?>),
   		showOn: 'focus',
   		beforeShowDay: function (date) {
                	if (date.getDay() == 0 || date.getDay() == 1 ) {
                    	return [false, ''];
                	} else {
                    	return [true, ''];
                	}
            	}
		});
		
		$( "#schedule" ).button({
			disabled: true
		});
		
	
	<?php } ?>
	
	
		}); // end   	
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
#defaultCountdown { width: 240px; height: 38px; padding:3px;}	

.ui-tab-content, .ui-tabs-panel{overflow: auto;}
</style>
	<link href="<?php echo base_url(); ?>_css/signup.css" rel="stylesheet" type="text/css" />
	<!-- jQuery handles to place the header background images --> 
<!--  <div id="headerimgs"> 
	<div id="headerimg1" class="headerimg"></div> 
	<div id="headerimg2" class="headerimg"></div> 
	<div id="news_window"> -->
	<div id="sign_container">
	<div id="my_account">
		
					<div id="tagged_items">
							<br />
							<br />
							<h2 style="font-size:16px;  margin-left:55px;"><?php echo $message; ?>
							<?php if($yesno) {
								echo " - Two More Steps to Complete Order: ";
							}?></h2>
							<div style="margin:5px 0px 0px 55px; background-color:white; padding:10px; border: 1px solid #CCC" >
						     <p style="color:#333333;  font-size:15px; font-weight:bold;">
						
						<?php

						   if(is_array($info)){
							    foreach($info as $err){
								echo $err . "<br />";
								}
						    } else {
						   		echo $info;
							}

							?>


							</p>
							
						
						   </div>
						<?php 
						if($yesno){ ?>
						<div style="margin:5px 0px 0px 55px; background-color:white; padding:10px; border: 1px solid #CCC">
							<h2 style="font-size:14px;  ">Step 1: Download Transaction Confirmation Report</h2>
							<p >	
							<?php 
							$dt = array('id' => 'dwn_lnk');
							echo anchor('vecchio_download/receipt', "Download Transaction Confirmation Report &#9660;", $dt);?></p>
							<span id="">May take a minute to generate..</span>
						</div>
						<?php 
							$data = array(
							       'id' => 'new_shipping'
							);
						echo form_open('vecchio_cc/enter_ship_date', $data);?>
						<div style="margin:5px 0px 25px 55px; background-color:white; padding:10px; border: 1px solid #CCC">
							<div>
							
							<h2 style="font-size:14px;  ">Step 2: Choose a date to receive your products</h2>
						 	<label>Choose Date</label>
						 	<input type="text" name="ship_date" value="" id="ship_date" class="required"  />
						 	<span id="Check_Date"><img src="<?php echo base_url(); ?>_images/ajax-loader.gif" alt="Ajax Indicator" /></span>
						 	<input type="hidden" name="ship_good" id="ship_good" value="no" />
						 	<input type="hidden" name="order_id"  value="<?php echo $order_id; ?>" />
							<input type="hidden" name="pay_type" value="cstg" />
							</div>
						
							<br /><br />
							<div>
							<button id="schedule">Schedule Order Receive Date</button>
							</div>
						</div>
						<?php echo form_close();
						} else {
						?>
						<p style="margin:25px 0px 2px 55px; "><a href="<?php echo base_url() . "index.php/dir/myaccount/cart";?>">Return To My Account</a></p>
						<?php } ?>
					</div>	
	</div>
	</div>
	<div id="dwn_notice" title="Generating Transaction Confirmation Report">
			<p>
			<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span> We are now generating a transaction report for your order. 
			</p>
			<p>
				<b>This may take a minute to generate. Please close this window when the download is complete. </b>
			</p>

			

	</div>
