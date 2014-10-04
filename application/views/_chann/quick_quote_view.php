<?php
// echo "<pre>";
// print_r($qq_quotes);
$row = $qq_quotes;
$count_quotes = count($row);
for($q=0;$q<$count_quotes;$q++){ ?>
	<script>
		 $(document).ready(function() {
				$( "#qq_sign_e_sig<?php echo $row[$q]['id']?>" )
					
					.click(function() {
						$('#qq-credit-card<?php echo $row[$q]['id'] ?>').hide();
						$('#enter_qq_freight<?php echo $row[$q]['id'] ?>').hide();
						$('#qq-on-account<?php echo $row[$q]['id'] ?>').hide();
						$('#qq_terms_and_warranty<?php echo $row[$q]['id'] ?>').show();

					});
					// box type checkd
					$("#box_check<?php echo $row[$q]['id']?>").change(function() {
						if ($(this).is(":checked")) {
							$.ajax({
								url: bsu+'index.php/dir/add_remove_box_qq',
								type: 'POST',
								data: {
									qq_id: $(this).attr("data-id"),
									boxed: "1"
								},
								dataType: 'json',
								success: function(response) {
									if (response.status == 1) {
										window.location.href = "<?php echo base_url();?>index.php/dir/myaccount/quote_id/<?php echo $row[$q]['id'] ?>"; 
									} 
								}
							});
						} else {
							$.ajax({
								url: bsu+'index.php/dir/add_remove_box_qq',
								type: 'POST',
								data: {
									qq_id: $(this).attr("data-id"),
									boxed: "0"
								},
								dataType: 'json',
								success: function(response) {
									if (response.status == 1) {
										window.location.href = "<?php echo base_url();?>index.php/dir/myaccount/quote_id/<?php echo $row[$q]['id'] ?>"; 
									} 
								}
							});
						}
					});	
					
					$('#update_po').dialog({
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

					$( ".update_po_a" )
								.click(function() {
								$( "#update_po" ).dialog( "open" );
					});	
					
			});
	
	</script>
	<?php
	
	echo "<p>Quote ID: ". $row[$q]['id'] . "-" . mb_substr($row[$q]['quote_date'], 0,10) . "</p>";
	if($row[$q]['po_number'] != ''){
	echo '<p>Purchase Order #: ' .$row[$q]['po_number'] .' <a href="#" class="update_po_a">Edit &raquo;</a></p>';
	} else {
	echo '<p>Purchase Order #: <a href="#" class="update_po_a">ENTER HERE &raquo;</a></p>';
	}
	echo "<p>Prepared By: " . $row[$q]['rep']['name'] ."</p>";
	echo ($row[$q]['memo'] != '' ? "<p>Note From Rep: ". $row[$q]['memo'] . "</p>" : '' );
	?>


	<?php
	echo '<p style="float:right;">' . anchor('dir/myaccount/', '&laquo; Back To Main List') . '</p>'; ?>

	<?php
	if($row[$q]['status'] == 2){ ?>

		</div>
		
<?php	} else { // the quote has not been paid for
	

	
	?>
		<table border='0' cellpadding='10' cellspacing='0'>
		<tr>
		<?php

		if(!$row[$q]['no_custom']){ ?>
			<td>
			<script>
			$(document).ready(function() {
				$( "#qq_custom_select<?php echo $row[$q]['id']?>" ).button();	
			});
			</script>
			<?php echo form_open('dir/set_qq_guide/');?>
			<input type='hidden' name='qq_id' value='<?php echo $row[$q]['id'];?>' />
			<button id="qq_custom_select<?php echo $row[$q]['id']?>">Custom Select From<br />Online Inventory</button>
			<?php echo form_close(); ?>
			</td>	
		<?php } 
		if($row[$q]['e_sig'] == ''){ // the user has not signed, pull up signature page ?>
		<td>
			<a href="#" id="qq_sign_e_sig<?php echo $row[$q]['id']?>"><?php echo img('_images/accept_quote.png'); ?></a>	
		</td>
		<?php } else { ?>
				<script>
					$(document).ready(function() {
						$( "#qq_check_by_fax<?php echo $row[$q]['id']?>" ).button();
						$( "#qq_enter_cc<?php echo $row[$q]['id']?>" )
									.button()
									.click(function() {
										$('#enter_qq_freight<?php echo $row[$q]['id']?>').hide();
										$('#qq_terms_and_warranty<?php echo $row[$q]['id']?>').hide();
										$('#qq-on-account<?php echo $row[$q]['id']?>').hide();
										$('#qq-credit-card<?php echo $row[$q]['id']?>').show();
						});
						$( "#on_account<?php echo $row[$q]['id']?>" )
									.button()
									.click(function() {
										$('#enter_qq_freight<?php echo $row[$q]['id']?>').hide();
										$('#qq_terms_and_warranty<?php echo $row[$q]['id']?>').hide();
										$('#qq-credit-card<?php echo $row[$q]['id']?>').hide();
										$('#qq-on-account<?php echo $row[$q]['id']?>').show();
						});	
						
						$( ".freighty<?php echo $row[$q]['id']?>" )
									.button()
									.click(function() {
										$('#qq_terms_and_warranty<?php echo $row[$q]['id']?>').hide();
										$('#qq-credit-card<?php echo $row[$q]['id']?>').hide();
										$('#qq-on-account<?php echo $row[$q]['id']?>').hide();
										$('#enter_qq_freight<?php echo $row[$q]['id']?>').show();
						});
						

					});
				</script>
			<?php if(empty($row[$q]['shipping'])){ ?>
			<td>
			<button id="qq_freight" class="freighty<?php echo $row[$q]['id']?>">Enter Freight &amp;<br /> Shipping Information</button>	
			<?php } else { ?>
			<td>
			<button id="update_freight" class="freighty<?php echo $row[$q]['id']?>">Update Freight &amp;<br /> Shipping Information</button>
			</td>
			<td>
			<?php if($row[$q]['can_credit']) { ?>
			<td>
			<button id="on_account<?php echo $row[$q]['id']?>" >Place Order <br /> On Account</button>
			</td>				
			<?php } ?>
			</td>
			<td>			
			<?php echo form_open('vecchio_download/check_by_fax_qq'); ?>
			<input name="qq_id" type="hidden" value="<?php echo  $row[$q]['id'] ?>" />
			<button id="qq_check_by_fax<?php echo $row[$q]['id']?>">Download<br />Check By Fax Form</button>
			</form>	
			</td>
			<td>
			<button id="qq_enter_cc<?php echo $row[$q]['id']?>">Complete Payment<br />With Debit/Credit Card</button>
			</td>		
			<?php } ?>

	<?php	} ?>
		</tr>
		</table>
		<hr />
		<div id="qq_terms_and_warranty<?php echo $row[$q]['id']?>" title="Terms And Conditions" style="display:none;">
				<div style="height:300px;  padding:10px; margin:10px; overflow:auto; width:650px; border:3px solid #CCC; background:#fff;">
					<?php echo $w; // WARRANTY GENERATED FROM DB -- ?>
				</div>
			<div style="background-color:#FFFF99; padding:10px; margin:10px; border:3px dashed #ccc; width:650px;">
				<p><b>Electronic Signature: X </b>
					<?php echo form_open('dir/quick_quote_e_sig');?>
						<input type="hidden" name="qq_id" value="<?php echo $row[$q]['id']; ?>" />
					 <input type="text"  style="width:200px;"name="qq_e_sig" id="qq_e_sig" /> (Enter Full Name) <br />
					<div class="signblock">
						<input type="submit" name="qqregsub" id="qqregsub" class="submit_ship" value="Sign E-Signature">
					</div>
					</form>
					<span id="creditorfax"></span> I agree to the Terms and Conditions, Warranty and Company Policies of Vecchio Trees. </p>
			</div>	
	<div style="clear:both;"></div>
</div><!-- end terms and conditions -->


			<div id="enter_qq_freight<?php echo $row[$q]['id']?>" style="display:none;">
				<!-- 


				SHIPPING INFO


				-->
				<div class="signblock">
					<p>Enter Shipping Destination	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<script>
							$(document).ready(function() {
								
								function is_will_call(){
									return ($(' #enter_freight_form_qq<?php echo $row[$q]['id']?> input:radio[name=will_call]:checked').val() == '0');
								}	
								
								$("#enter_freight_form_qq<?php echo $row[$q]['id']?>").validate({
									rules: {
										qqship_zip: {
											required: {
											                depends: function(element) {
											                   return is_will_call();
															}
											},
											minlength: 5,
											maxlength: 5
										},
										qqship_city : {
											required: {
												depends: function(element) {
								                   return is_will_call();
												}
											}
										}, 
										qqship_address : {
											required: {
												depends: function(element) {
								                   return is_will_call();
												}
											}				
										},
										qqship_state : {
											required: {
												depends: function(element) {
								                   return is_will_call();
												}
											}				
										}
									},
									errorPlacement: function(error, element) {
								        error.appendTo('#error<?php echo $row[$q]['id']?>-' + element.attr('id'));
								    }
								});

								$("#enter_credit_card_qq<?php echo $row[$q]['id']?>").validate({
									rules: {
										first_name : {
											required: true
										},
										last_name : {
											required: true
										},
										card_num: {
											required: true,
											creditcard: true
										},
										exp_date: {
											required: true,
											date: true
										},
										card_code : {
											required: true,
											minlength:3,
											maxlength:4
										},
										emailcc : {
											required: true,
											email:true
										}	
									},
									errorPlacement: function(error, element) {
								        error.appendTo('#error<?php echo $row[$q]['id']?>-' + element.attr('id'));
								    }
								});

								$("#qqlocation_phone").mask("(999) 999-9999");
								$("#qqship_zip").mask("99999");

							});
						</script>			  
				<form method="POST" action="<?php echo base_url() . 'index.php/dir/quote_shipping_capture/'; ?>" id="enter_freight_form_qq<?php echo $row[$q]['id']; ?>">

				<div id="signleft">
					<?php
						$will_yes = '';
						$will_no = '';
					if($row[$q]['will_call'] == 1){
						$will_yes = 'checked';
					} else {
						$will_no = 'checked';
					}
					?>
					<div class="signblock">

					<input type="radio" name="will_call" value="0" <?php echo $will_no; ?> > Ship to Location <br />
					<input type="radio" name="will_call" value="1" <?php echo $will_yes; ?> > Will Call / Customer Pickup 
					</div>
					<div class="signblock">
						<input type="text" name="qqlocation" id="qqlocation" class="required" value="<?php echo (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['location'] : '' ); ?>">
					</div>
					<div class="signblock">
						<h2 id="error<?php echo $row[$q]['id']?>-qqlocation">Job Name / Id or Recipient Name</h2>
					</div>
					<div class="signblock">
						<input type="text" name="qqlocation_phone" id="qqlocation_phone" class="required" value="<?php echo (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['location_phone'] : '' ); ?>">
					</div>
					<div class="signblock">
						<h2 id="error<?php echo $row[$q]['id']?>-qqlocation_phone">Phone # of Recipient</h2>
					</div>
					<div class="signblock">
						<input type="text" name="qqship_address" id="qqship_address" value="<?php echo (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['ship_address'] : '' ); ?>">
					</div>
					<div class="signblock">
						<h2 id="error<?php echo $row[$q]['id']?>-qqship_address">Address or Cross Street</h2>
					</div>


				</div><!-- end signleft -->
				<div id="signmidsmall"></div>
				<div id="signright">
					<div class="signblock">
						<input type="text" name="qqship_city" id="qqship_city" value="<?php echo  (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['ship_city'] : '' ); ?>">
					</div>
					<div class="signblock">
						<h2 id="error<?php echo $row[$q]['id']?>-ship_city">City</h2>				
					</div>
					<div class="signblock">
						<input type="text" name="qqship_state" id="qqship_state" value="<?php echo  (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['ship_state'] : '' ); ?>" >
					</div>
					<div class="signblock">
						<h2 id="error<?php echo $row[$q]['id']?>-ship_state">State</h2>				
					</div>
					<div class="signblock">
						<input type="text" name="qqship_zip" id="qqship_zip" value="<?php echo (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['ship_zip'] : '' ); ?>" >
					</div>
					<div class="signblock">
						<h2 id="error<?php echo $row[$q]['id']?>-ship_zip" >Zip</h2>
					</div>
					<div class="signblock">
						<input type="submit" name="qqregsub" id="qqregsub" class="qq_submit_ship" value="<?php echo (empty($row[$q]['shipping']) ? 'Enter' : 'Edit' ); ?> Shipping Information">
					</div>
				</div><!-- end signright -->
				<input type="hidden" name="qq_id" value="<?php echo $row[$q]['id']; ?>" />

				<input type="hidden" name="qq_shipping_id" value="<?php echo (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['id'] : '' ); ?>" />
				</form>

				<div style="float:right; width:200px;">
					<p style="font-size:11px; font-weight:normal;">&#x2713; Step 1: Sign E-Signature</p>
					<p style="font-size:11px; font-weight:bold;"><?php echo (!empty($row[$q]['shipping']) ? '&#x2713;' : ''); ?> Step 2: Shipping Information</p>
					<p style="font-size:11px; font-weight:normal;">Step 3: Complete Order</p>
						<div style="margin-top:0px; background-color:white; padding:6px; border:1px solid #CCC;">	
					<table border="0" cellpadding="0">

						<tr>
							<td width="120">Sub Total:</td>
							<td>$<?php echo number_format($row[$q]['sub_total_items'], 2, '.', ','); ?></td>
						</tr>
						<?php if($row[$q]['boxed'] == 1){ ?>
						<tr>
							<td>Box Trees:</td>
							<td>$<?php echo number_format($row[$q]['box_price'], 2, '.', ','); ?></td>
						</tr>							
						<?php } ?>
						
						<?php if($row[$q]['disc'] > 0){  ?>
						<tr>
							<td width="120">Special Discount:</td>
							<td>- $<?php echo number_format($row[$q]['disc'], 2, '.', ',') . " - " .$row[$q]['who_disc']; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td>Freight:</td>
							<td>$<?php echo number_format($row[$q]['ship_cost'], 2, '.', ','); ?></td>
						</tr>

						<tr>
							<td colspan="2"><hr /></td>
						</tr>
						<tr>
							<td><b>Grand Total</b></td>
							<td><b><?php echo number_format($row[$q]['grand_total'], 2, '.', ','); ?></b></td>
						</tr>

					</table>
					<br />
					 <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="34da993c-6ff8-488b-9f4e-d0e5a5309e5d";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Merchant Services</a></div>
					</div>
				</div>
<div style="clear:both;"></div>
</div>
</div><!-- end of freight div -->

<?php if($row[$q]['can_credit']) { ?>
<script>
 $(document).ready(function() {
	$('#Check_Date<?php echo $row[$q]['id']; ?>').hide();
	$("#new_shipping<?php echo $row[$q]['id']; ?>").validate({
		rules: {
			ship_date: "required"
		},
		messages: {
			ship_date: "Please choose date for order shipment"
		}
	});
	function check_ship(dateText){
		$('#Check_Date<?php echo $row[$q]['id']; ?>').show();
		$.post("<?php echo base_url()?>index.php/dir/check_shipping_date", {
			ship_date: dateText
		}, function(response){
			$('#Check_Date<?php echo $row[$q]['id']; ?>').hide();
			$('#Check_Date<?php echo $row[$q]['id']; ?>').html(unescape(response));
		    $('#Check_Date<?php echo $row[$q]['id']; ?>').fadeIn();
		    // check if the response said date was available
		    if(response.indexOf("Available") != -1 ){
			 	$( "#schedule<?php echo $row[$q]['id']; ?>" ).button({
					disabled: false
				});
			} else {
				$( "#schedule<?php echo $row[$q]['id']; ?>" ).button({
					disabled: true
				});
			} 
		});
		return false;		
	}
	
	$("#ship_date<?php echo $row[$q]['id']; ?>").datepicker({
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
	
	$( "#schedule<?php echo $row[$q]['id']; ?>" ).button({
		disabled: true
	});
}); // end   	
</script>
<div id="qq-on-account<?php echo $row[$q]['id']?>" style="display:none;">
	<?php 
		$data = array(
		       'id' => 'new_shipping' . $row[$q]['id']
		);
	echo form_open('vecchio_cc/enter_ship_date_qq', $data);?>
	
		
		<h2 style="font-size:14px;  ">Place Order On Account</h2>
		<p>You have been approved for a credit limit of <?php echo "$" . number_format($row[$q]['credit_limit'], 2, '.', ',') ?>.</p>
		<?php if($row[$q]['total_in_credit'] != 0){
		$rem = $row[$q]['credit_limit'] - $row[$q]['total_in_credit'];  
		$rem_after = $rem - $row[$q]['quote_total']; ?>
		<p>You currently have <?php echo "$" . number_format($row[$q]['total_in_credit'], 2, '.', ','); ?> purchased on account with Vecchio Trees, with 
		<?php echo "$" . number_format($rem, 2, '.', ',') ?> remaining credit to place on account. 
		 If you choose place this quote on account, you will have <?php echo "$" . number_format($rem_after, 2, '.', ','); ?> remaining in credit.</p>
	    <?php } ?>
	 	<label>Choose Shipping Date</label>
	 	<input type="text" name="ship_date" value="" id="ship_date<?php echo $row[$q]['id']; ?>" class="required"  />
	 	<span id="Check_Date<?php echo $row[$q]['id']; ?>"><img src="<?php echo base_url(); ?>_images/ajax-loader.gif" alt="Ajax Indicator" /></span>
	 	<input type="hidden" name="ship_good" id="ship_good<?php echo $row[$q]['id']; ?>" value="no" />
	 	<input type="hidden" name="qq_id"  value="<?php echo $row[$q]['id']; ?>" />
		<input type="hidden" name="pay_type" value="cstg" />
		<input type="hidden" name="on_account" value="on_account" />
		<button id="schedule<?php echo $row[$q]['id']; ?>">Place On Account</button>
		<?php echo form_close(); ?>
</div><!-- end qq-on-account -->
<?php } // end if can_credit ?>
<div id="qq-credit-card<?php echo $row[$q]['id']?>" style="display:none;"  >	

		<div class="signblock">

			<p>   <img src="<?php echo base_url() . "_images/lock.png"; ?>" />   Complete Order With Credit Card </p>
		</div>
		<?php
		$data = array(
			'id' => "enter_credit_card_qq". $row[$q]['id'] 
		);
		echo form_open('vecchio_cc/validate_credit_card_qq', $data);
		?>

			<input type="hidden" name="amount" value="<?php echo number_format($row[$q]['grand_total'], 2, '.', ',') ;?>"/>
			<input type="hidden" name="qq_id" value="<?php echo $row[$q]['id']; ?>" />
			<input type="hidden" name="coupon" value="" />
			<input type="hidden" name="freight" value="<?php echo number_format($row[$q]['ship_cost'], 2, '.', ','); ?>" />
			<input type="hidden" name="ship_address" value="<?php echo (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['ship_address'] : '' ); ?>" />
			<input type="hidden" name="ship_location" value="<?php echo  (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['location'] : '' ); ?>" />
			<input type="hidden" name="ship_city" value="<?php echo  (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['ship_city'] : '' ); ?>" />
			<input type="hidden" name="ship_state" value="<?php echo  (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['ship_state'] : '' ); ?>" />
			<input type="hidden" name="ship_zip" value="<?php echo  (!empty($row[$q]['shipping']) ? $row[$q]['shipping'][0]['ship_zip'] : '' ); ?>" />
			<input type="hidden" name="pay_type" value="cstg" />
			<input type="hidden" name="discount" value="<?php echo $row[$q]['disc']; ?>" />						
		<div id="signleft">
			<div class="signblock">
				<input type="text" name="first_name" id="first_name" class="required" >
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-first_name" >First Name</h2>
			</div>
			<div class="signblock">
				<input type="text" name="last_name" id="last_name" class="required" >
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-last_name" >Last Name</h2>
			</div>
			<div class="signblock">
				<input type="text" name="email" id="email" class="required" >
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-email" >Email (For Receipt)</h2>
			</div>
			<div class="signblock">
				<input type="text" name="card_num" id="card_num" autocomplete="off" class="required" >
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-card_num">Credit Card Number</h2>
			</div>
			<div class="signblock">
				<input type="text" name="exp_date" id="exp_date" autocomplete="off"  class="required">
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-exp_date">Expire Date (03/13)</h2>
			</div>

		</div><!-- end signleft -->
		<div id="signmidsmall"></div>
		<div id="signright">

			<div class="signblock">
				<input type="text" name="card_code" id="card_code" class="required" autocomplete="off">
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-card_code">Card Code (On Back)</h2>
			</div>
			<div class="signblock">
				<input type="text" name="address" id="address" class="required">
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-address">Address</h2>
			</div>
			<div class="signblock">
				<input type="text" name="city" id="city" class="required">
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-city">City</h2>				
			</div>
			<div class="signblock">
				<input type="text" name="state" id="state" class="required">
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-state">State</h2>				
			</div>
			<div class="signblock">
				<input type="text" name="zip" id="zip" class="required">
			</div>
			<div class="signblock">
				<h2 id="error<?php echo $row[$q]['id']?>-zip" >Zip</h2>
			</div>
			<div class="signblock">
				<input type="submit" name="regsub" id="regsub" value="Pay With Credit Card" />
			</div>
			</form>
		</div><!-- end signright -->
		<div style="float:right; width:200px;">
			<p style="font-size:11px; font-weight:normal;">&#x2713; Step 1: Sign E-Signature</p>
			<p style="font-size:11px; font-weight:normal;">&#x2713; Step 2: Shipping Information</p>
			<p style="font-size:11px; font-weight:bold;">Step 3: Complete Order</p>
			<div style="margin-top:0px; background-color:white; padding:6px; border:1px solid #CCC;">	
				<table border="0" cellpadding="0">

					<tr>
						<td width="120">Sub Total:</td>
						<td>$<?php echo number_format($row[$q]['sub_total_items'], 2, '.', ','); ?></td>
					</tr>
					<?php if($row[$q]['boxed'] == 1){ ?>
					<tr>
						<td>Box Trees:</td>
						<td>$<?php echo number_format($row[$q]['box_price'], 2, '.', ','); ?></td>
					</tr>							
					<?php } ?>
					
					<?php if($row[$q]['disc'] > 0){  ?>
					<tr>
						<td width="120">Special Discount:</td>
						<td>- $<?php echo number_format($row[$q]['disc'], 2, '.', ',') . " - " .$row[$q]['who_disc']; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<td>Freight:</td>
						<td>$<?php echo number_format($row[$q]['ship_cost'], 2, '.', ','); ?></td>
					</tr>

					<tr>
						<td colspan="2"><hr /></td>
					</tr>
					<tr>
						<td><b>Grand Total</b></td>
						<td><b><?php echo number_format($row[$q]['grand_total'], 2, '.', ','); ?></b></td>
					</tr>

				</table>
				<br />
		<!-- (c) 2005, 2011. Authorize.Net is a registered trademark of CyberSource Corporation --> <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="34da993c-6ff8-488b-9f4e-d0e5a5309e5d";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Merchant Services</a>
			</div>
	</div>
	</div>
<div style="clear:both;"></div>
</div><!-- end credit card -->
<?php // if($row[$q]['can_box'] > 0){ ?>
	<?php	
	//	$box_check = '';
		
	//	if($row[$q]['boxed'] == 1){
	//		$box_check = 'checked';
	//	}	
	// <input type="checkbox" id="box_check<?php echo $row[$q]['id']" data-id="echo $row[$q]['id'];" value="$row[$q]['boxed']; " echo // $box_check;   /> 
	// (Optional) Box Trees for Delivery
	 ?> 


<?php // } ?>
	<?php 
	$count_items = count($row[$q]['items']);
	$h = "";
	$h .=  "<table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" >";
	$base = base_url() . "_images/_products/";
	$h .= "<tr><td colspan=\"2\"><hr /></td></tr>";
   for($i=0;$i<$count_items;$i++){
	$h .= "<tr height=\"200\"><td style=\"text-align:left; width:200px;\"><img src=\"".$base. $row[$q]['items'][$i]['product_code'] . ".jpg\"  /></td> ";
	$h .= "<td >";
	$h .= $row[$q]['items'][$i]['description'] . " <span style='font-size:10px;'> - PC ".$row[$q]['items'][$i]['product_code']."</span><br />";
	$h .= "List Price: " . "$" . number_format($row[$q]['items'][$i]['list_price'], 2, '.', ',') . " ";
	$h .= "<b>x</b> Quantity: " . $row[$q]['items'][$i]['quantity'] . "<br />";
	$h .= "<hr />";
	$h .= "Price: <del>" .  "$" . number_format($row[$q]['items'][$i]['line_price'], 2, '.', ',') . "</del><br >";
	$h .= " <span style=\"font-size:12px;\">-- " . ((1 - $row[$q]['multiplier']) * 100 ). "% Discount --</span><br >";
	$h .= "Your Price: <u>$" . number_format($row[$q]['items'][$i]['cust_line'], 2, '.', ',') . "</u><br >";
	$h .= "<p style=\"font-size:10px; color:C0C0C0;\">".$row[$q]['items'][$i]['text_description']." Exposure: ".$row[$q]['items'][$i]['exposure'].". Watering: ".$row[$q]['items'][$i]['watering']."</p>";
	$h .= "</td></tr>";
	$h .= "<tr><td colspan=\"2\"><hr /></td></tr>";
	}
	$h .= "<tr><td colspan=\"2\">";
	$h .= "Sub Total: " . "$" . number_format($row[$q]['sub_total_items'], 2, '.', ',') . "<br >";
	if($row[$q]['boxed'] == 1){	
	$h .= "+ Boxed Trees Fee: " . "$" . number_format($row[$q]['box_price'], 2, '.', ',') . "<br >";		
	}
	if($row[$q]['disc'] > 0){
	$h .= "<b>- Special Discount: " . "$" . number_format($row[$q]['disc'], 2, '.', ',') . " - " .$row[$q]['who_disc'] . "</b><br >"; 
	}
	$h .= "+ Shipping Estimate: " . "$" . number_format($row[$q]['ship_cost'], 2, '.', ',') . "* <br >";
	$h .= "<hr />";
	$h .= "<b>Grand Total: " . "$" . number_format($row[$q]['grand_total'], 2, '.', ',') . "</b><br >";
	if($row[$q]['will_call'] == 0){
	$h .= "<span style=\"font-size:10px;\">*Shipping to: " . $row[$q]['ship_zip'] . ", approximately " . $row[$q]['distance'] . " miles from  VECCHIO shipping facility.</span><br /><br />";
	} else {
	$h .= "<span style=\"font-size:10px;\">* Customer Pickup / Will Call - No Freight</span><br /><br />";		
	}
	$h .= "</td></tr>";
	$h .="</table><hr /><br /><br /><br /><br />";
	echo $h; ?>

	
<?php } // end if the status != 2 (paid for)  ?>
	<div id="update_po" title="Update Purchase Order Number" style="display:none;" >
			<?php if($row[$q]['po_number'] != ''){ ?>
			<p>
				The following purchase order number has been entered for this order: <b><?php echo $row[$q]['po_number']; ?></b>
			</p>
			<p>
			<?php echo form_open('dir/update_po');?>
			Update Purchase Order Number: <input type="text" value="<?php echo $row[$q]['po_number'];?>" name="po"  /> 
			<input type="hidden" name="qq_id" value="<?php echo $row[$q]['id']; ?>" />
			<input type="hidden" name="qq_order" value="qq" />
			<input type="submit" value="Update">
			</form>	
			</p>
			<?php } else { // end if po number for quote ?>
				<p>
					Enter purchase order number for quote. 
				</p>
				<p>
				<?php echo form_open('dir/update_po');?> 
				Enter Purchase Order Number: <input type="text" value="" name="po"  /> 
				<input type="hidden" name="qq_id" value="<?php echo $row[$q]['id']; ?>" />
				<input type="hidden" name="qq_order" value="qq" />
				<input type="submit" value="Submit" >
				</form>	
				</p>				
			<?php } ?>
	</div>

<?php } // end loop for each quote in system  ?>

