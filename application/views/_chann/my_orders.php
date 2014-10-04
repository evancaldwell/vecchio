<?php
$count_o = count($pend);
for($o=0; $o < $count_o; $o++){

?><script type="text/javascript">
 			$(document).ready(function(){
		 	$('#Check_Date-<?php echo $pend[$o]['id']?>').hide();
			$("#new_shipping-<?php echo $pend[$o]['id']?>").validate({
				rules: {
					ship_date: "required"
				},
				messages: {
					ship_date: "Please choose date for order shipment"
				}
			});
			function check_ship(dateText){
				$('#Check_Date-<?php echo $pend[$o]['id']?>').show();
				$.post("<?php echo base_url()?>index.php/dir/check_shipping_date", {
					ship_date: dateText
				}, function(response){
					$('#Check_Date-<?php echo $pend[$o]['id']?>').hide();
					$('#Check_Date-<?php echo $pend[$o]['id']?>').html(unescape(response));
				    $('#Check_Date-<?php echo $pend[$o]['id']?>').fadeIn();
				    // check if the response said date was available
				    if(response.indexOf("Available") != -1 ){
					 	$( "#schedule-<?php echo $pend[$o]['id']?>" ).button({
							disabled: false
						});
					} else {
						$( "#schedule-<?php echo $pend[$o]['id']?>" ).button({
							disabled: true
						});
					} 
				});
				return false;		
			}

			$("#ship_date-<?php echo $pend[$o]['id']?>").datepicker({
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

			$( "#schedule-<?php echo $pend[$o]['id']?>" ).button({
				disabled: true
			});
			$( "#down-<?php echo $pend[$o]['id']?>" ).button();
		});
	</script>					
	<div style="padding:12px; background-color:white; width:940px; float:left; border:1px solid #CCC; margin-bottom:12px;">	
		
		<div style="width:550px; float:left;">
			<h2 style="font-size:14px;">Order : <?php echo $pend[$o]['id'] ."-". $pend[$o]['order_name']?> 
				</h2>
		<table cellpadding="0" cellspacing="0" style="margin:0px; padding:0px">
			<tr>
			<td class="ti_serial_no_top">Product Serial Number</td>
			<td class="ti_description_top">Description</td>
			<td class="ti_price_top">Price</td>
			</tr>

		<?php
		$cust_cost = 0;
		$num_in_order = 0;
		$count = count($pend[$o]['order_items']);

		for($i = 0; $i<$count; $i++){ ?>
			<tr>
			<td class="ti_serial_no"><a class="iframes" href="<?php echo base_url() . "index.php/image_bank/byproduct/" . $pend[$o]['order_items'][$i]['product_id'] ?> ">	<?php echo $pend[$o]['order_items'][$i]['product_info'][0]['serial_no'];?></a></td>
			<td class="ti_description"><?php echo $pend[$o]['order_items'][$i]['product_info'][0]['product_type'][0]['description']; ?></td>
			<td class="ti_price"><?php echo "$". number_format($pend[$o]['order_items'][$i]['cust_cost'], 2, '.', ',');?></td>
			</tr>
		<?php 
		$cust_cost += $pend[$o]['order_items'][$i]['cust_cost'];
		$num_in_order ++;
		} ?>

			<?php 
			 $d = $pend[$o]['freight']['discount_percent'];
			 $c = $pend[$o]['code'];
			 $s = $cust_cost ;
			 $dc = ($cust_cost - ($cust_cost  * $d));
			 if($d != 0){ ?>
				<tr>
					<td class="ti_blank" colspan="2">Sub Total</td>
					<td class="ti_price" ><del><?php echo "$". number_format($cust_cost, 2, '.', ','); ?></del></td>
				</tr>				
			<tr>
				<td class="ti_blank" colspan="2">Discounted Sub Total - <?php echo ($d * 100); ?>% Discount</td>
				<td class="ti_price" ><?php echo "$". number_format($dc, 2, '.', ','); ?></td>
			</tr>
			<?php } else { ?>
				<tr>
					<td class="ti_blank" colspan="2">Sub Total</td>
					<td class="ti_price" ><?php echo "$". number_format($cust_cost, 2, '.', ','); ?></td>
				</tr>				
			<?php } ?>
			<tr>
				<td class="ti_blank" colspan="2">Freight</td>
				<td class="ti_price" ><?php echo "$". number_format($pend[$o]['freight']['total_cost_cust'], 2, '.', ',');?></td>
			</tr>
			<tr>
				<td class="ti_blank" colspan="2">Grand Total</td>
				<td class="ti_price" ><?php echo "$". number_format($pend[$o]['freight']['grand_total'], 2, '.', ',');?></td>
			</tr>
			<tr>
				<?php
				if($pend[$o]['freight']['charge_trucks'] > 1){
					$s = "s";
				} else {
					$s = "";
				}
				?>
				<td colspan="3"><span style="font-size:11px; font-weight:normal; ">Destination: <b><?php echo $pend[$o]['freight']['to_city'] . ", " . $pend[$o]['freight']['to_state'];?>. <br />
				<b> Order requires <?php echo $pend[$o]['freight']['charge_trucks']; ?> truck<?php echo $s;?> to ship. Cost per mile: <b><?php echo "$" . $pend[$o]['freight']['cost_per_mile']; ?></b> at <?php echo $pend[$o]['freight']['miles']; ?> miles. </span>
				</td>
				
			</tr>

		</table>
		</div><!-- end of left file-->
		<div style="width:345px; float:right;">
			<h2 style="font-size:14px; text-align:right;">Placed: <?php echo  date("F j, Y - g:i a", strtotime($pend[$o]['payment_date'])); ?>
				</h2> 
			<?php 
				$data = array(
				       'id' => 'new_shipping-' . $pend[$o]['id']
				);
				$cu = "";
			echo form_open('vecchio_cc/enter_ship_date', $data);?>
			<div style="margin:5px 0px 5px 55px; background-color:white; padding:10px; border: 1px solid #CCC">
			<?php if($pend[$o]['shipping'][0]['ship_date'] != '0000-00-00'){
				$cu = "Update";
			?>
			Current Ship Date:<br />
				<b><?php echo date("l F j, Y", strtotime($pend[$o]['shipping'][0]['ship_date']));?></b><br /><br />
			<?php } else {
				$cu = "Choose"; ?>
			No Ship Date Set<br />
			<?php }?>
				<div>
			
			 	<label><?php echo $cu;?></label>
			 	<input type="text" name="ship_date" value="" id="ship_date-<?php echo $pend[$o]['id']?>" class="required"  /><br />
			 	<span id="Check_Date-<?php echo $pend[$o]['id']?>"><img src="<?php echo base_url(); ?>_images/ajax-loader.gif" alt="Ajax Indicator" /></span>
			 	<input type="hidden" name="ship_good" id="ship_good" value="no" />
			 	<input type="hidden" name="order_id"  value="<?php echo $pend[$o]['id']?>" />
				<input type="hidden" name="pay_type" value="cstg" />
				</div>
			
				<br /><br />
				<div>
				<button id="schedule-<?php echo $pend[$o]['id']?>"><?php echo $cu; ?> Order Ship Date</button>
				</div>
			</div>
			<?php echo form_close(); ?>
				<div style="margin:5px 0px 25px 55px; background-color:white; padding:10px; border: 1px solid #CCC">
					<?php 
						$data = array(
						       'id' => 'receipt-' . $pend[$o]['id']
						);
						$cu = "";
					echo form_open('vecchio_download/receipt_post', $data);?>
					<input type="hidden" name="order_id"  value="<?php echo $pend[$o]['id']?>" />
					<button id="down-<?php echo $pend[$o]['id']?>">Download Transaction Report</button>
					</form>
					<span style="font-size:11px;">large file, may take a few seconds to generate</span>
				</div>
		</div>
		
	</div><!-- end -->

<?php } ?>