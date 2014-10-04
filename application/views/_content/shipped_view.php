<?php 
// count the number of new estimates
	$no = count($new_estimates);
	$new = $new_estimates;
 ?>
<h2>Inventory Orders</h2>
<table width="857" border="0" cellspacing="0" cellpadding="0">
  <tr class="top_order">
    <td width ="109" >Order ID</td>
    <td width="210">Customer Name</td>
    <td width="176">Status</td>
    <td width="177">Shipped Date</td>
    <td width="185">Show / Hide Info</td>
  </tr>
   <?php if($no != 0){
	// "F j, Y g:i a" Y-m-d H:i:s
	for($i=0; $i<$no; $i++){
	 ?>
		<script>
			
			 $(document).ready(function() {
				
			$(" #payment_date-<?php echo $new[$i]['id'];?> ").datepicker();

			});    	
	 </script>
    <tr class="order_details">
    <td width ="109" ><?php echo $new[$i]['id'];?></td>
    <td width="210"><?php echo $new[$i]['client'][0]['fname'] . " " . $new[$i]['client'][0]['lname'];?></td>
    <td width="176"><?php echo $new[$i]['status_text']; ?> </td>
    <td width="177"><?php echo date("F j, Y g:i a", strtotime($new[$i]['shipped_date'])); ?></td>
    <td width="185">
		<?php if($no > 1){ ?>
		<?php echo anchor('vecchio_admin/shipped/' .$new[$i]['id'], 'View / Edit' ); ?>
		<?php } else {
		echo anchor('vecchio_admin/shipped', 'Back to List' ); 
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
      <td colspan="2" valign="top" >
		<table>
			<tr class="top_order"><td  width ="109"><strong>Rep: </strong></td><td><?php echo $new[$i]['rep'][0]['fname'] . " " . $new[$i]['rep'][0]['lname'];?></td></tr>
			<tr class="top_order"><td><strong>Customer Phone:</strong></td><td><?php echo $new[$i]['client'][0]['phone'] ?></td></tr>
			<tr class="top_order"><td><strong>Billing:</strong></td><td><?php echo $new[$i]['client'][0]['bill_address']; ?><br />
															  <?php echo $new[$i]['client'][0]['bill_city']; ?>,&nbsp;
															  <?php echo $new[$i]['client'][0]['bill_state']; ?>&nbsp;
															  <?php echo $new[$i]['client'][0]['bill_zip']; ?>
			</td></tr>
			<tr class="top_order"><td><strong>Ship To:</strong></td><td>
															  <?php echo $new[$i]['shipping'][0]['location']; ?><br />
															  <?php echo $new[$i]['shipping'][0]['ship_address']; ?><br />
															  <?php echo $new[$i]['shipping'][0]['ship_city']; ?>,&nbsp;
															  <?php echo $new[$i]['shipping'][0]['ship_state']; ?>&nbsp;
															  <?php echo $new[$i]['shipping'][0]['ship_zip']; ?>
			</td></tr>
		    </table>
		<br />
		<pre>
		<?php print_r( $new[$i]['transaction_text']);?>
		</pre>
		<?php if($new[$i]['amount_owed'] > 0){

				echo "<h3 style='margin-left:0px;'>Enter Payment</h3>"; 		
					echo form_open('vecchio_admin/enter_payment/order'); ?>
					<h4>Total: <?php echo '$'. number_format($new[$i]['freight']['grand_total'], 2, '.', ','); ?></h4>
					<?php
					$amount_owed = $new[$i]['amount_owed'];
					 if($amount_owed == $new[$i]['freight']['grand_total']){ // they haven't paid a deposit (on accout)  ?>
					<input type="radio" name="deposit_whole" value="deposit" checked /> 
					Deposit  $<?php echo number_format($new[$i]['pay_amounts']['half_deposit'], 2, '.', ','); ?><br />
					<input type="radio" name="deposit_whole" value="full_payment" /> 
					Full Payment  $<?php echo number_format($new[$i]['freight']['grand_total'], 2, '.', ','); ?><br />
					<?php } else { // they have paid a deposit ?>
					Balance  $<?php echo number_format($new[$i]['pay_amounts']['half_balance'], 2, '.', ','); ?><br />
					<?php } ?>
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
					<?php if($amount_owed == $new[$i]['freight']['grand_total']){ // they have not paid a deposit (on account)  ?>
					<input name="dep_bal" type="hidden" value="dep" />	
					<?php } else { // they have paid a deposit. enter the balance ?>
					<input name="dep_bal" type="hidden" value="bal" />
					<?php } ?>	
					<input name="grand_total" type="hidden" value="<?php echo $new[$i]['freight']['grand_total'];?>" />	
					<input name="freight" type="hidden" value="<?php echo $new[$i]['freight']['total_cost_cust']?>" />	
					<?php echo form_submit('submit', 'Record Payment'); ?>
					<p>Once entered- client will receive email with receipt.</p>
					</form>		
			<?php } // end amount owed more than 0-- ?>
		</td><!-- end for order info cell -->
      <td colspan="3"  valign="top" >


		<?php $no_items = count($new[$i]['order_items']);
	     ?>
		<table>

        <tr class="top_order_o">
	    <td>Serial #</td>
        <td>Product Code</td>
        <td>Type</td>
        <td>Grow Yard</td>
        <td>Trees To Truck</td>
        <td>Cost</td>
        </tr>

        <tr>
	     <?php $order_total = 0; ?>
	    <?php for($u=0; $u<$no_items; $u++){?>

		<?php $order_total += $new[$i]['order_items'][$u]['cust_cost'];?>
	    <td><a class="iframes" href="<?php echo base_url() . "index.php/image_bank/byproduct/" . $new[$i]['order_items'][$u]['product_id'] ?> ">
		<?php echo $new[$i]['order_items'][$u]['product_info'][0]['serial_no'];?></a></td>
		<td><?php echo $new[$i]['order_items'][$u]['product_info'][0]['product_type'][0]['product_code'];?></td>
        <td><?php echo $new[$i]['order_items'][$u]['product_info'][0]['product_type'][0]['description'];?></td>
        <td><?php echo $new[$i]['order_items'][$u]['product_info'][0]['grow_yard'][0]['grow_yard_name'];?></td>
        <td><?php echo $new[$i]['order_items'][$u]['product_info'][0]['product_type'][0]['trees_to_truck'];?></td>
        <td><?php echo "$". number_format($new[$i]['order_items'][$u]['cust_cost']);?></td>

        </tr>
        <?php }?>
	    <tr>
		<td colspan="5" align="right"><strong>Total:</strong></td>
		<td><strong><?php echo "$" . number_format($order_total); ?></strong></td>
		</tr>
		
		<?php 
		if(is_array($new[$i]['freight'])){
		$grand_total = $new[$i]['freight']['grand_total'];
		}
		?>
		<?php if(is_array($new[$i]['freight'])){?>
		<tr>
		<td colspan="5" align="right"><strong>Total Freight:</strong></td>
		<td><strong>$<?php echo  number_format($new[$i]['freight']['total_cost_cust'], 2, '.', ',');?></strong></td>

		</tr>
		<tr>
		<td colspan="5" align="right"><strong>Grand Total:</strong></td>
		<td><strong>$<?php echo number_format($grand_total, 2, '.', ',');?></strong></td>

		</tr>
		<?php } else {
			echo $new[$i]['freight'];
		}?>		
     
        </table>

            </td><!-- end for product cell -->
      </tr>
	  <?php
	}// end main order loop
	 	} else { // end if there are any orders ?>
		<tr>
			<td colspan="5"><strong>No Shipped Orders In System </strong></td>
		</tr>
	   <?php } ?>
</table>
<br />
<?php if(!is_numeric($this->uri->segment(3))){ ?>
<h2>Quick Quotes</h2>
<?php $this->load->view('_content/all_quick_quotes'); ?>
<?php } ?>