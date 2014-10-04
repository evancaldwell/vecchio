<script>
	 $(document).ready(function() {
		
			$( ".qq_details" ).button();
			
			$('#contact_vecchio').click(function() {
				   var text = $("#contact_txt").val();
				   var to_rep_v = '<?php echo $rep['email']; ?>';
				   if(text != ''){
					
					$.post("<?php echo base_url()?>index.php/dir/ajax_contact", {
						contact_text: text,
						to_rep : to_rep_v    
					}, function(response){
						$('#contact_response').hide();
						$('#contact_response').html(unescape(response));
					    $('#contact_response').fadeIn();
					    // check if the response said date was available
					    
					});
				}
			})		
			
	});
</script>
<style>
	#list_quotes{
		width:670px;
		float:left;
	}
	#list_receipts{
		width:300px;
		float:right;
	}
	.current_quote{
		background-color:white; padding:15px;
	}
	.past_quotes{
		background-color:white; padding:15px; margin-bottom:5px;
	}
	
</style>
<div  id="list_quotes" >
			<h2 style="font-size:16px;">Pending Quote</h2>

		<?php foreach($qq['new'] as $new){?>
			<div class="current_quote">	
		<p><?php	echo "<p>Quote ID: ". $new['id'] . "-" . mb_substr($new['quote_date'], 0,10) . "</p>"; ?></p>
		<?php if($new['memo'] != ''){ ?>
		<p><?php echo $new['memo']; ?></p>
		<?php } ?>
		<?php echo ($new['po_number'] != ''? '<p>Purchase Order #: ' .$new['po_number'] .'</p>' : ''); ?>
		<p><?php
			$count = count($new['items']);
			for($i=0;$i<$count;$i++){
				echo  $new['items'][$i]['description'] . " (". $new['items'][$i]['quantity'] . ")". ($i == ($count -1) ? ' ' : ', ') ; 
			}
		?></p>
		<?php
		$at = array('class' => 'qq_details');
		echo anchor('dir/myaccount/quote_id/' . $new['id'],'Make Payment', $at); ?>
		</div>
		<?php } 
		if(empty($qq['new'])){ ?>
			<div class="current_quote"><p>No New Quotes In System</p></div>
		<?php } ?>
	<br /><br />
	<div class="current_quote">
	<div id="contact_response">
	<p>Have a Question or Need a Quote? Contact Your Rep Here:</p>
	<textarea name="contact_rep" rows="5" cols="80" id="contact_txt"></textarea><br />
	<input type="submit" value="Contact" id="contact_vecchio" /><br /><br />
	</div>
	<p>Or contact <?php echo $rep['name']?> via phone or email:<br /> <?php echo $rep['phone']; ?> : <?php echo mailto($rep['email']);?></p>
	</div>
</div><!-- end list_quotes -->
<!-- check by fax -->
<div id="list_receipts">
	<?php if(!empty($qq['check_by_fax'])){ ?>		<h2 style="font-size:16px;">Pending Deposit</h2><?php } ?>
	<?php foreach($qq['check_by_fax'] as $cbf){ ?>
	<div class="past_quotes">
	
			<?php	echo "<p>Order ID: ". $cbf['order_name']  . "</p>"; ?>
		
			<form action="<?php echo base_url();?>index.php/vecchio_download/check_by_fax_real" method="post" accept-charset="utf-8">								<p>Download Check By Fax sheet for this order</p>
								<input type="hidden" name="order_id" value="<?php echo $cbf['id']?>" />
								<input type="submit" value="Download Check By Fax" />
								</form>

		
	</div><!-- end pas_quotes -->
	<?php } ?>
</div>

<div id="list_receipts">
<?php if(!empty($qq['backlog_noship'])){ ?>		<h2 style="font-size:16px;">Shipping Backlog</h2><?php } ?>
<?php foreach($qq['backlog_noship'] as $bns){ ?>
<div class="past_quotes">
	<script>
	$(document).ready(function() {
		
		<?php if(isset($bns['needs_ship']) && $bns['needs_ship'] == true){ ?>
			
		 	$('#Check_Date<?php echo $bns['id']; ?>').hide();
			$("#new_shipping<?php echo $bns['id']; ?>").validate({
				rules: {
					ship_date: "required"
				},
				messages: {
					ship_date: "Please choose date for order shipment"
				}
			});
			function check_ship(dateText){
				$('#Check_Date<?php echo $bns['id']; ?>').show();
				$.post("<?php echo base_url()?>index.php/dir/check_shipping_date", {
					ship_date: dateText
				}, function(response){
					$('#Check_Date<?php echo $bns['id']; ?>').hide();
					$('#Check_Date<?php echo $bns['id']; ?>').html(unescape(response));
				    $('#Check_Date<?php echo $bns['id']; ?>').fadeIn();
				    // check if the response said date was available
				    if(response.indexOf("Available") != -1 ){
					 	$( "#schedule<?php echo $bns['id']; ?>" ).button({
							disabled: false
						});
					} else {
						$( "#schedule<?php echo $bns['id']; ?>" ).button({
							disabled: true
						});
					} 
				});
				return false;		
			}

			$("#ship_date<?php echo $bns['id']; ?>").datepicker({
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

			$( "#schedule<?php echo $bns['id']; ?>" ).button({
				disabled: true
			});
			
			<?php } // end needs shipping backlog ?>
			
				$( "#dwn_lnk<?php echo $bns['id']?>" )
							.button()
							.click(function() {
									$( "#dwn_notice<?php echo $bns['id']?>" ).dialog( "open" );
				});

				$('#dwn_notice<?php echo $bns['id']?>').dialog({
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

		}); // end	
	</script>
		<?php	echo "<p>Quote ID: ". $bns['id'] . "-" . mb_substr($bns['quote_date'], 0,10) . "</p>"; ?>
		<?php 
		$dt = array('id' => 'dwn_lnk'.$bns['id']);
		echo anchor('vecchio_download/receipt_qq/'. $bns['id'], "Download Details &#9660;", $dt);?></p>
		<?php
		
		if(isset($bns['needs_ship']) && $bns['needs_ship'] == true){
		 
			$data = array(
			       'id' => 'new_shipping' . $bns['id']
			);
		
		echo form_open('vecchio_cc/enter_ship_date_qq', $data);?>
	
		<div style="border:1px; solid #99000;" >	
			<p>Choose a date to deliver your products</p>
		 	<label>Choose Date</label>
		 	<input type="text" name="ship_date" value="" id="ship_date<?php echo $bns['id']; ?>" class="required"  />
		 	<span id="Check_Date<?php echo $bns['id']; ?>"><img src="<?php echo base_url(); ?>_images/ajax-loader.gif" alt="Ajax Indicator" /></span>
		 	<input type="hidden" name="ship_good" id="ship_good<?php echo $bns['id']; ?>" value="no" />
		 	<input type="hidden" name="qq_id"  value="<?php echo $bns['id']; ?>" />
			<input type="hidden" name="pay_type" value="cstg" />
			<br /><br />

			<button id="schedule">Schedule Order Delivery Date</button>

		<?php echo form_close();	?>
		</div>
		<?php } else { // ship date accepted ?>
		<p>Delivery date: <?php echo $bns['ship_date'];?></p>
		<p><?php echo anchor('dir/vecchio_sales','Contact Vecchio');?> to Update Delivery Date</p>	
		<?php } ?>

</div>		
<?php } // end foreach backlog_noship ?>
			

<?php if(!empty($qq['paid']) || !empty($qq['past_orders'])){?>		<h2 style="font-size:16px;">Past Orders</h2><?php } ?>
	<?php foreach($qq['paid'] as $paid){?>
		<div class="past_quotes">
	<script>
		 $(document).ready(function() {

			$( "#dwn_lnk<?php echo $paid['id']?>" )
						.button()
						.click(function() {
								$( "#dwn_notice<?php echo $paid['id']?>" ).dialog( "open" );
			});

			$('#dwn_notice<?php echo $paid['id']?>').dialog({
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
		});
	</script>
		<?php	echo "<p>Quote ID: ". $paid['id'] . "-" . mb_substr($paid['quote_date'], 0,10) . "</p>"; ?>
		<?php 
		$dt = array('id' => 'dwn_lnk'.$paid['id']);
		echo anchor('vecchio_download/receipt_qq/'. $paid['id'], "Download Receipt &#9660;", $dt);?></p>
		<p>Ship Date: <span style="color:#654B24"><?php echo $paid['ship_date']; ?></span></p>
		<div id="dwn_notice<?php echo $paid['id']?>" title="Generating Transaction Confirmation Report">
			<p>
			<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span> We are now generating a transaction report for your order. 
			</p>
			<p>
				<b>This may take a minute to generate. Please close this window when the download is complete. </b>
			</p>

	</div>
	</div><!-- end past_quotes -->
	<?php } ?>
		<?php foreach($qq['past_orders'] as $past){?>
			<div class="past_quotes">
		<script>
			 $(document).ready(function() {

				$( "#dwn_lnkO<?php echo $past['id']?>" )
							.button()
							.click(function() {
									$( "#dwn_notice<?php echo $past['id']?>" ).dialog( "open" );
				});

				$('#dwn_noticeO<?php echo $past['id']?>').dialog({
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
			});
		</script>
			<?php	echo "<p>Order ID: ". $past['order_name']  . "</p>"; ?>
			<?php 
			$dt = array('id' => 'dwn_lnkO'.$past['id']);
			echo anchor('vecchio_download/receipt_post/'. $past['id'], "Download Receipt &#9660;", $dt);?></p>
			<p>Ship Date: <span style="color:#654B24"><?php echo  date("l F j, Y", strtotime($past['ship_date'])); ?></span></p>
			<div id="dwn_noticeO<?php echo $past['id']?>" title="Generating Transaction Confirmation Report">
				<p>
				<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span> We are now generating a transaction report for your order. 
				</p>
				<p>
					<b>This may take a minute to generate. Please close this window when the download is complete. </b>
				</p>

		</div>
		</div><!-- end past_quotes -->
		<?php } ?>
		
		<?php foreach($qq['on_account'] as $on_account){?>
		<div class="past_quotes">
			<script>
				 $(document).ready(function() {

					$( "#dwn_lnk<?php echo $on_account['id']?>" )
								.button()
								.click(function() {
										$( "#dwn_notice<?php echo $on_account['id']?>" ).dialog( "open" );
					});

					$('#dwn_notice<?php echo $on_account['id']?>').dialog({
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
				});
			</script>
				<?php	echo "<p>Quote ID: ". $on_account['id'] . "-" . mb_substr($on_account['quote_date'], 0,10) . "</p>"; ?>
				<?php 
				$dt = array('id' => 'dwn_lnk'.$on_account['id']);
				echo anchor('vecchio_download/receipt_qq/'. $on_account['id'], "Download Details &#9660;", $dt);?></p>
				<p>Delivery Date: <span style="color:#654B24"><?php echo $on_account['ship_date']; ?></span></p>
				<p>Payment Due By: <span style="<?php echo ($on_account['overdue'] == false ? 'color:#654B24;' : 'color:red;' ); ?>"><?php echo $on_account['pay_date'];?> <?php echo ($on_account['overdue'] == false ? '' : '*' ); ?></span></p>
				<div id="dwn_notice<?php echo $on_account['id']?>" title="Generating Transaction Confirmation Report">
					<p>
					<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span> We are now generating a transaction report for your order. 
					</p>
					<p>
						<b>This may take a minute to generate. Please close this window when the download is complete. </b>
					</p>

			</div>
			</div><!-- end past_quotes -->
			<?php } ?>


</div><!-- end list_receipts -->	
