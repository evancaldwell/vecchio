<script src="<?php echo base_url();?>_js/ui/jquery.ui.tabs.js"></script>
<script src="<?php echo base_url();?>_js/ui/jquery.ui.dialog.js"></script>
<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script> 

<script src="<?php echo base_url();?>_js/ui/jquery.ui.dialog.js"></script>
<style>
.order_tabs {width:384px; margin:0px; padding:0px; font-size:12px; letter-spacing:0px; }
</style>
<?php 
// count the number of new estimates
	$no = count($new_estimates);
	$new = $new_estimates;
 ?>
<h2>Inventory Based Orders</h2>
<table width="857" border="0" cellspacing="0" cellpadding="0">
  <tr class="top_order">
    <td width ="400" >Order ID</td>
    <td width="172">Order Date</td>
    <td width="172">Order Expire Date </td>
    <td width="148">Show / Hide Info</td>
  </tr>
   <?php if($no != 0){
	// "F j, Y g:i a" Y-m-d H:i:s
	for($i=0; $i<$no; $i++){
	 ?>
		<script>
			 $(document).ready(function() {

			   $('#show-<?php echo $new[$i]['id'];?>').click( function() {
				$('.order_details').hide();
				$('.order_details_all td').css('backgroundColor', '#FFFFFF');
			    $('#order_details-<?php echo $new[$i]['id'];?>').toggle();
			    $('#order_details_all-<?php echo $new[$i]['id'];?> td').css('backgroundColor', '#B1FFAE');
			   });
			  $("#tabs-<?php echo $new[$i]['id'];?>").tabs();
				$("#extend-<?php echo $new[$i]['id'];?>").validate();
				$("#toproc-<?php echo $new[$i]['id'];?>").validate();
			
			$(" #payment_date-<?php echo $new[$i]['id'];?> ").datepicker();
		
		
			$('#dialog-confirm-<?php echo $new[$i]['id'];?>').dialog({
			    autoOpen: false,
			    height: 200,
			    width: 400,
			    modal: true,
			    resizable: false,
			    buttons: {
			        'Confirm': function(){
			    		$.post("<?php echo base_url()?>index.php/vecchio_admin/delete_order", {
							order_id: <?php echo $new[$i]['id'];?>
						}, function(response){
							if(response.indexOf("Deleted") != -1 ){
								location.reload();
							} else {
								$(this).dialog('close');
							}
							
						});
							            	
			        },
			        'Cancel': function(){
			            
			            $(this).dialog('close');
			        }
			    }
			});
			

				
			$('#DELETE-<?php echo $new[$i]['id'];?>').submit(function(e){
						e.preventDefault();
						$('#dialog-confirm-<?php echo $new[$i]['id'];?>').dialog('open');
			});
			
			
			
			
			
			});    	
	 </script>
    <tr id="order_details_all-<?php echo $new[$i]['id'];?>" class="order_details_all" />
    <td width ="400" ><?php
	$order_n = $new[$i]['id'] . "-" . $new[$i]['order_name'] . " " . $new[$i]['client'][0]['company_name'] . " - " . $new[$i]['client'][0]['lname'] ;
 	echo $order_n;?></td>
    <td width="172"><?php echo date("F j, Y g:i a", strtotime($new[$i]['order_date'])); ?> </td>
    <td width="172"><?php echo date("F j, Y g:i a", strtotime($new[$i]['expire_date'])); ?></td>
    <td width="158">
	<?php if($no > 1){ ?>
	<?php echo anchor('vecchio_admin/new_estimates/' .$new[$i]['id'], 'View / Edit' ); ?>
	<?php } else {
		echo anchor('vecchio_admin/new_estimates', 'Back to List' ); 
		} ?>
	</td>
  </tr>
   <tr>
    </tr>
  	<?php if($no > 1){ ?>
		<tr class="order_details" style="display:none;" id="order_details-<?php echo $new[$i]['id'];?>" >
		<?php } else { ?>
			<tr class="order_details" >	
		<?php } ?>
      <td  valign="top" >
	   <div id="tabs-<?php echo $new[$i]['id'];?>" class="order_tabs">
		<ul>
			<li><a href="#tabssm-1-<?php echo $new[$i]['id'];?>">Client</a></li>
			<li><a href="#tabssm-2-<?php echo $new[$i]['id'];?>">Freight</a></li>
			<li><a href="#tabssm-3-<?php echo $new[$i]['id'];?>">Payment</a></li>
			<li><a href="#tabssm-4-<?php echo $new[$i]['id'];?>">Ch B Fax</a></li>
			<li><a href="#tabssm-5-<?php echo $new[$i]['id'];?>">Ext/Del</a></li>
		</ul>
		<div id="tabssm-1-<?php echo $new[$i]['id'];?>" >
		<table>
			<tr><td>Rep<br /><?php echo $new[$i]['rep'][0]['fname'] . " " . $new[$i]['rep'][0]['lname'];?></td></tr>
			<tr><td>Customer<br /><?php echo $new[$i]['client'][0]['fname'] . " " .$new[$i]['client'][0]['lname'] . "<br />";
			 											      echo $new[$i]['client'][0]['company_name']?></td></tr>
			<tr><td>Phone<br /><?php echo $new[$i]['client'][0]['phone'] ?></td></tr>
			<tr><td>Billing<br /><?php echo $new[$i]['client'][0]['bill_address']; ?><br />
															  <?php echo $new[$i]['client'][0]['bill_city']; ?>,&nbsp;
															  <?php echo $new[$i]['client'][0]['bill_state']; ?>&nbsp;
															  <?php echo $new[$i]['client'][0]['bill_zip']; ?>
															<hr width="290" />
			<?php echo anchor('vecchio_admin/edit_user/'. $new[$i]['client'][0]['id'], "Edit User Info &raquo;");?>												
			</td></tr>
		</table>
		</div>
		<div id="tabssm-2-<?php echo $new[$i]['id'];?>">
		<table>
			<tr><td>
			<?php if(is_array($new[$i]['shipping'])){?>	
			Ship To: <br />
															  <?php echo $new[$i]['shipping'][0]['location']; ?><br />
															  <?php echo $new[$i]['shipping'][0]['ship_address']; ?><br />
															  <?php echo $new[$i]['shipping'][0]['ship_city']; ?>,&nbsp;
															  <?php echo $new[$i]['shipping'][0]['ship_state']; ?>&nbsp;
															  <span style="color:red"><?php echo $new[$i]['shipping'][0]['ship_zip']; ?><br /></span>
			
			<?php
			$o_name_short = $new[$i]['id'] . "-" . $new[$i]['order_name'];
			 echo anchor('vecchio_admin/update_shipping/' . $new[$i]['id'] .'/'. urlencode($o_name_short) , "Update Shipping" );?>
			<?php } ?>
			</td></tr>
			<tr><td>
				
			<?php
			if(is_array($new[$i]['freight'])){
			$from_city = $new[$i]['freight']['from_city'];
			$from_state = $new[$i]['freight']['from_state'];
			$to_city = $new[$i]['freight']['to_city'];
			$to_state = $new[$i]['freight']['to_state'];
			$miles = $new[$i]['freight']['miles'];
			$buffer_miles = $new[$i]['freight']['buffer_miles'];
			$cost_per_mile = $new[$i]['freight']['cost_per_mile'];
			$mileage_cost = $new[$i]['freight']['mileage_cost'];
			$charge_trucks = $new[$i]['freight']['charge_trucks'];
			$total_cost_cust = $new[$i]['freight']['total_cost_cust'];
			$trucks_each_gy = $new[$i]['freight']['trucks_each_gy'];
			$actual_trucks = $new[$i]['freight']['actual_trucks'];
			$total_actual_trucks = $new[$i]['freight']['total_actual_trucks'];
			$total_cost_vecchio = $new[$i]['freight']['total_cost_vecchio'];
			$total_gy = $new[$i]['freight']['total_gy'];
			
			?>
			Freight Information:<br />
			From: <?php echo $from_city . ", " . $from_state; ?><br />
			To:   <?php echo $to_city . ", " . $to_state; ?>
			<hr width="290" />
			Total Miles: (+ <?php echo $buffer_miles;?> Buffer Miles) = <?php echo  $miles; ?><br />
			Cost Per Mile: $<?php echo  $cost_per_mile;?><br />
			Mileage Cost: (<?php echo  $miles; ?> x $<?php echo $cost_per_mile;?>) = $<?php echo $mileage_cost;?><br />			
			Total Trucks: <?php echo  $charge_trucks; ?><br />
			Total Freight Cost: (<?php echo $charge_trucks; ?> x $<?php echo $mileage_cost;?>) = $<?php echo $total_cost_cust;?>
			<hr />
			Total Grow Yards: <?php echo $total_gy;?><br />
			1 Truck Per Grow Yard: <?php echo $trucks_each_gy;?><br />
			Calculated Truck Space: <?php echo $actual_trucks;?><br />
			Calculated Trucks: <?php echo $total_actual_trucks;?><br />	
		    Calculated Cost: (<?php echo $total_actual_trucks;?> x $<?php echo $mileage_cost;?>) = $<?php echo $total_cost_vecchio;?>	
		
		
			<?php } else { ?>
			Freight Information Not Entered <br />
			<?php
			$o_name_short = $new[$i]['id'] . "-" . $new[$i]['order_name'];
			 echo anchor('vecchio_admin/update_shipping/' . $new[$i]['id'] .'/'. urlencode($o_name_short) , "Enter Freight" );?>
			<?php  } // else freight is not entered ?>
			</td></tr>
		    </table>
		</div><!-- end  tab for freight -->
		<div id="tabssm-3-<?php echo $new[$i]['id'];?>">
		<?php 	if(is_array($new[$i]['freight']) && $new[$i]['pay_status']['payment_ready']){ ?>
				<?php
				if($new[$i]['amount_owed'] > 0){

				echo "<h4>Enter Payment - Send to Shipping Backlog</h4>"; 		
					echo form_open('vecchio_admin/enter_payment/order'); ?>
					<h2>Total: <?php echo '$'. number_format($new[$i]['freight']['grand_total'], 2, '.', ','); ?></h2>
					<input type="radio" name="deposit_whole" value="deposit" checked /> 
					Deposit  $<?php echo number_format($new[$i]['pay_amounts']['half_deposit'], 2, '.', ','); ?><br />
					<input type="radio" name="deposit_whole" value="full_payment" /> 
					Full Payment  $<?php echo number_format($new[$i]['freight']['grand_total'], 2, '.', ','); ?><br />
					<select name="method">
						<option value="CHECK-BY-FAX">Check By Fax</option>
						<option value="CC-MANUAL">Manual Credit Card</option>
						<option value="CHECK">Mailed Check</option>
						<option value="E-CHECK">E-Check</option>
						<option value="CASH">Cash</option>
					</select>
					<br />
					<label>Enter Transaction ID: </label>
					<input name="transaction_id" value="" />
					<label>Payment Date:</label>
					<input name="payment_date" value="" id="payment_date-<?php echo $new[$i]['id']?>" class="payment_date-<?php echo $new[$i]['id']?>" />
					<input name="order_id" type="hidden" value="<?php echo $new[$i]['id']; ?>" />
					<input name="dep_bal" type="hidden" value="dep" />		
					<input name="grand_total" type="hidden" value="<?php echo $new[$i]['freight']['grand_total'];?>" />	
					<input name="freight" type="hidden" value="<?php echo $new[$i]['freight']['total_cost_cust']?>" />	
					<?php echo form_submit('submit', 'Enter Deposit and Place on Shipping Backlog'); ?>
					<p>Once entered- client will receive email with receipt and message to choose ship date.</p>
					</form>		
			<?php } // end amount owed more than 0-- 
			 } else if($new[$i]['pay_status']['payment_ready'] == false){
				echo "<h4>Cannot enter Deposit</h4>";
				echo $new[$i]['pay_status']['payment_reason'];
			} ?>
		</div><!-- End Credit Card -->
		<div id="tabssm-4-<?php echo $new[$i]['id'];?>">
			<?php 	if(is_array($new[$i]['freight'])){ ?>
				<?php 	echo form_open('vecchio_download/check_by_fax_real/'); ?>
					<p>Download Check By Fax sheet for this order</p>
					<input type="hidden" name="order_id" value="<?php echo $new[$i]['id'];?>" />
					<input type="submit" value="Download Check By Fax for Order PDF" />
					</form>
				<br />
				<br />
				<?php } ?>
		</div><!-- End Check By Fax-->
		<div id="tabssm-5-<?php echo $new[$i]['id'];?>">
		Extend Order if Expired
			<?php
			$data = array(
			       'id' => 'extend-'. $new[$i]['id'] 
			);
			echo form_open('vecchio_admin/extend_order', $data);
			$data = array(
			    'name'        => 'extend_to',
			    'value'       => '1',
			    'checked'     => false,
			    'style'       => 'margin:10px; ',
		    	'class' => 'required'
			    );

			echo form_radio($data) . "1 Day  " . "<br />";
			$data = array(
			    'name'        => 'extend_to',
			    'value'       => '7',
			    'checked'     => false,
			    'style'       => 'margin:10px; ',
				'class' => 'required'
			    );

			echo form_radio($data) . "1 Week  ". "<br />";
			$data = array(
			    'name'        => 'extend_to',
			    'value'       => '14',
			    'checked'     => false,
			    'style'       => 'margin:10px; ',
				'class' => 'required'
			    );

			echo form_radio($data) . "2 Weeks  ". "<br />";
			$data = array(
			    'name'        => 'extend_to',
			    'value'       => '30',
			    'checked'     => false,
			    'style'       => 'margin:10px; ',
		    	'class' => 'required'
			    );

			echo form_radio($data) . "1 Month". "<br />";
			echo form_hidden('order_id', $new[$i]['id']);
			echo form_hidden('expire_date', $new[$i]['expire_date']);
			echo form_submit('submit', 'Extend Order');
			echo form_close();
			?>
			<br />
			<hr />
			<br />
			Delete Entire Order / Release Products <strong>(Permanent)</strong><br />
			
			<?php 
			$data = array('id' => 'DELETE-' . $new[$i]['id']);
			echo form_open('vecchio_admin/delete_order/', $data);
			echo form_hidden('order_id', $new[$i]['id']);?>
			<input type="submit" name="submit" id="del_submit" value="DELETE ORDER" />
		<?php	echo form_close();	?>
		<div id="dialog-confirm-<?php echo $new[$i]['id']; ?>" title="Erase Entire Order?">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
		</div>	
			
		</div><!-- end extent order -->
		</div><!-- end big tab -->
		
		</td><!-- end for order info cell -->
      <td colspan="3" valign="top">

		<?php $no_items = count($new[$i]['order_items']);
	     ?>
		<table>

        <tr class="top_order_o">
	   	<td align="middle">&#x2713;</td>
	    <td>Serial #</td>
        <td>T.Truck</td>
        <td>Cost</td>
		<td>List Price</td>
        </tr>

        <tr>
	     <?php 
	           $list_total = 0; 
		    echo form_open('vecchio_admin/remove_item');?>
	    <?php for($u=0; $u<$no_items; $u++){?>
		<?php $list_total += $new[$i]['order_items'][$u]['product_info'][0]['product_type'][0]['list_price'];

		?>
	
		<td align="middle"><input type="checkbox" name="<?php echo $new[$i]['order_items'][$u]['id']; ?>" value="1" /></td>
	    <td><a class="iframes" href="<?php echo base_url() . "index.php/image_bank/byproduct/" . $new[$i]['order_items'][$u]['product_id'] ?> ">
		<?php echo $new[$i]['order_items'][$u]['product_info'][0]['serial_no'];?></a></td>
        <td><?php echo $new[$i]['order_items'][$u]['product_info'][0]['product_type'][0]['trees_to_truck'];?></td>
        <td><?php echo "$". number_format($new[$i]['order_items'][$u]['cust_cost']);?></td>
		<td><?php echo "$". number_format($new[$i]['order_items'][$u]['product_info'][0]['product_type'][0]['list_price']);?></td>	
        </tr>
        <?php }
		if(is_array($new[$i]['freight'])){
		$order_total = $new[$i]['freight']['order_total']; 
		$grand_total = $new[$i]['freight']['grand_total'];
		}
		?>
	    <tr>
		<td colspan="3" align="right"><strong>Total:</strong></td>
		
		<td><strong><?php if(is_array($new[$i]['freight'])){ echo "$" . number_format($order_total, 2, '.', ','); } ?></strong></td>
		<td><strong><?php echo "$" . number_format($list_total, 2, '.', ','); ?></strong></td>
		</tr>
		<?php if(is_array($new[$i]['freight'])){?>
		<tr>
		<td colspan="3" align="right"><strong>Total Freight:</strong></td>
		<td><strong>$<?php echo  number_format($new[$i]['freight']['total_cost_cust'], 2, '.', ',');?></strong></td>
		<td></td>
		</tr>
		<tr>
		<td colspan="3" align="right"><strong>Grand Total:</strong></td>
		<td><strong>$<?php echo number_format($grand_total, 2, '.', ',');?></strong></td>
		<td></td>
		</tr>
		<?php } else {
			echo $new[$i]['freight'];
		}?>	
        </table>
        <input type="submit" value="Remove &#x2713; Item(s) From Order " />
		</form>
            </td><!-- end for product cell -->
      </tr>
	  <?php
	}// end main order loop
	 	} else { // end if there are any orders ?>
		<tr>
			<td colspan="5"><strong>No New Estimates </strong></td>
		</tr>
	   <?php } ?>
</table>
<br />
<?php if(!is_numeric($this->uri->segment(3))){ ?>
<h2>Quick Quotes</h2>
<?php $this->load->view('_content/all_quick_quotes'); ?>
<?php } ?>