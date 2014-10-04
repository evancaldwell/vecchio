<h1>Manage Quick Quote</h1>	
<div style="margin-left:20px;">
	
	<?php
	if($this->uri->segment(4) == 'error-zip'){ ?>
	<p style="color:red">Error - Zip Code Entered Unknown</p>
	<?php }
	if($this->uri->segment(4) == 'ship_updated'){
	 echo "<h3 style='color:green;'>&#x2713; Shipping Updated</h3>";	
	} else if($this->uri->segment(4) == 'status_updated'){
	 echo "<h3 style='color:green;'>&#x2713; Status Updated</h3>";			
	} else if($this->uri->segment(4) == 'deposit_updated'){
	 echo "<h3 style='color:green;'>&#x2713; Deposit Entered</h3>";
	}
	?>
	<table style="width:700px;" border="0" cellpadding="0" cellspacing="0" >
<tr>
<td>	
	<?php $count_quotes = count($row);
	for($q=0;$q<$count_quotes;$q++){ 
		
	$s = $row[$q]['status'];	
	echo "<p>Quote ID: ". $row[$q]['id'] . "-" . mb_substr($row[$q]['quote_date'], 0,10) . "</p>";
	echo "<p>Prepared By: " . $row[$q]['rep']['name'] ."</p>";
	if(isset($row[$q]['rep2']['name'])){
	echo "<p>And By: " . $row[$q]['rep2']['name'] ."</p>";
	}
	echo "<p>Prepared For: " . $row[$q]['cust_name'] ."</p>";
	echo ($row[$q]['memo'] != '' ? "<p>Note From Rep: ". $row[$q]['memo'] . "</p>" : '' );
	if($s != 0 || $s != 6){
		$dt = array('id' => 'dwn_lnk'.$row[$q]['id']);
		echo "<p>". anchor('vecchio_download/receipt_qq/'. $row[$q]['id'], "Download Receipt &#9660;", $dt) ."</p>";
	} ?>
</td>
<td>
	<?php 
	
	echo form_open('vecchio_admin/gen_qq_pdf'); ?>
	<input name="quote_id" type="hidden" value="<?php echo $row[$q]['id'] ?>" />
	<input type="submit" value="Download PDF" />
	</form>
	 <br />

	<?php echo form_open('vecchio_download/check_by_fax_qq'); ?>
	<input name="qq_id" type="hidden" value="<?php echo $row[$q]['id']  ?>" />
	<input type="submit" value="Check By Fax" />
	</form>
</td>
<td>
	<?php 
	if($s == 0 ){
		if($row[$q]['admin_void'] == 1){
			echo form_open('vecchio_admin/unvoid_quick_quote'); ?>
	<input name="void_id" type="hidden" value="<?php echo $row[$q]['id']; ?>" />
	<input type="submit" value="Un-Void" />
	</form>	
	<?php } else { 
	echo form_open('vecchio_admin/void_quick_quote'); ?>
	<input name="void_id" type="hidden" value="<?php echo $row[$q]['id']; ?>" />
	<input type="submit" value="Void" />
	</form>	
	<?php }
	} ?>
</td>
<td>
	<?php
	if($s == 0 ){
	echo form_open('vecchio_admin/delete_quick_quote'); ?>
	<input name="delete_id" type="hidden" value="<?php echo $row[$q]['id']; ?>" />
	<input type="submit" value="Delete" />
	</form>	
	<?php } ?>
</td>
</tr>
</table>
	<hr />
		<script>
			 $(document).ready(function() {
			  	$( "#datepickership-<?php echo $row[$q]['id']  ?>" ).datepicker();
				$( ".datepickerpaid-<?php echo $row[$q]['id']  ?>" ).datepicker();
			 	$( "#datepicker" ).datepicker();
			});    	
	 </script>
	
	<?php 
 	$s = $row[$q]['status'];
	$st = $row[$q]['status_text'];
	echo '<h2>Status: '. $st . "</h2>";
	echo '<hr />';
	if(!empty($row[$q]['shipping']) && ($s == 1 || $s == 2) ){ // shipping backlog (on account and paid) ?>
	<table style="width:700px;" border="0" cellpadding="0" cellspacing="0" >
		<tr>

			<td>
		<?php 
		echo "<h4>Enter or Update Ship Date</h4>"; 

		
		 echo form_open('vecchio_admin/update_ship_date/quote');
		if($row[$q]['shipping'][0]['ship_date'] != '0000-00-00'){
			$new_date = date('m/d/Y', strtotime($row[$q]['shipping'][0]['ship_date']));
		} else {
			$new_date = '';
		}
		echo form_hidden('qq_id', $row[$q]['id']);
		?>
		<input type="text" name="ship_date" value="<?php echo $new_date ?>" id="datepickership-<?php echo $row[$q]['id'] ?>" />
		<?php 	echo form_submit('submit', 'Enter or Update Ship Date'); ?>
		</form>	
			<?php

				echo "<h4>Mark As Shipped</h4>"; 		
				echo form_open('vecchio_admin/mark_qq_shipped/'); ?>
				<input type="text" name="ship_date" value="" id="datepicker" > Enter Shipped Date
				<input type="hidden" name="qq_id" value="<?php echo $row[$q]['id']?>" />
				<input type="hidden" name="status" value="<?php echo $s; ?>" />
				<?php 	echo form_submit('submit', 'Enter Ship Date and Mark As Shipped'); ?>
				</form>
		</td>
				<td>
	
		
				</td>
		</tr>
		<table>
	<?php } 
//	print_r($pay_status);
	?>
	<?php	if($amount_owed > 0 && $pay_status['payment_ready']){
		
			echo form_open('vecchio_admin/enter_payment/quote'); ?>
			<h2>Grand Total: <?php echo '$'. number_format($row[$q]['grand_total'], 2, '.', ',');; ?></h2>
			<?php
				 if($amount_owed == $row[$q]['grand_total']){ // they haven't paid a deposit (on accout)  ?>
				<input type="radio" name="deposit_whole" value="deposit" checked /> 
				Deposit  $<?php echo number_format($pay_amounts['half_deposit'], 2, '.', ','); ?><br />
				<input type="radio" name="deposit_whole" value="full_payment" /> 
				Full Payment  $<?php echo number_format($row[$q]['grand_total'], 2, '.', ','); ?><br />
				<?php } else { // they have paid a deposit ?>
				Balance  $<?php echo number_format($pay_amounts['half_balance'], 2, '.', ','); ?><br />
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
			<input type="text"  name="transaction_id" value="" />
			<label>Payment Date:</label>
			<input name="payment_date" value="" class="datepickerpaid-<?php echo $row[$q]['id']?>" />
			<input name="qq_id" type="hidden" value="<?php echo $row[$q]['id']; ?>" />
			<?php if($amount_owed == $row[$q]['grand_total']){ // they have not paid a deposit (on account)  ?>
			<input name="dep_bal" type="hidden" value="dep" />	
			<?php } else { // they have paid a deposit. enter the balance ?>
			<input name="dep_bal" type="hidden" value="bal" />
			<?php } ?>
			<input name="grand_total" type="hidden" value="<?php echo $row[$q]['grand_total']?>" />	
			<input name="freight" type="hidden" value="<?php echo $row[$q]['ship_cost']?>" />				
			<?php echo form_submit('submit', 'Enter Payment'); ?>
			</form>
			
<?php } ?>
<?php if($amount_owed > 0 && $pay_status['payment_ready'] == false){
	echo "<h4>Cannot Enter Payment: ".$pay_status['payment_reason']."</h4>";
} ?>
	<hr />
	<?php 
	if($s == 0){
	echo form_open('vecchio_admin/edit_quick_quote_capt/');
	}
	$count_items = count($row[$q]['items']);
	$h = "";
	$h .=  "<table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" >";
	$base = base_url() . "_images/_products/";
	$h .= "<tr><td colspan=\"2\"><hr /></td></tr>";
for($i=0;$i<$count_items;$i++){
	$h .= "<tr height=\"200\"><td style=\"text-align:left; width:200px;\"><img src=\"".$base. $row[$q]['items'][$i]['product_code'] . ".jpg\"  />";
	$h .= "<h4 class=\"prod_pf_txt\" style=\"font-size:10px;\">PC-". $row[$q]['items'][$i]['product_code'] . " " . $row[$q]['items'][$i]['description'] ."</h4>";
	$h .= "</td> ";
	$h .= "<td >";
	$h .= $row[$q]['items'][$i]['description'] . " <span style='font-size:10px;'> - PC ".$row[$q]['items'][$i]['product_code']."</span><br />";
	$h .= "List Price: " . "$" . number_format($row[$q]['items'][$i]['list_price'], 2, '.', ',') . " ";
	$h .= "<b>x</b> Quantity: ";
	if($s == 0){
	$h .= "<input name='".$row[$q]['items'][$i]['item_id']."' value='". $row[$q]['items'][$i]['quantity'] ."' />" . "<br />";
	} else {
	$h .= $row[$q]['items'][$i]['quantity'] . "<br />";	
	}
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
	$h .= "<tr><td colspan=\"2\">";
	if($s == 0){
	$h .= "<label for='disc'>Special Discount</label> ";
	$h .= "<input name='disc' value='".$row[$q]['disc']."'  /> ";
	$fname = $this->session->userdata('fname');
	$lname = $this->session->userdata('lname');
	$who_disc = strtoupper($fname[0]) . strtoupper($lname[0]);
	$h .= "<input name='who_disc' value='".$who_disc."' type='hidden' />";
	$h .= "<input name='qq_id' value='".$row[$q]['id']."' type='hidden' />";
	$h .= "	<input type='submit' value='Update Quote' />";	
	$h .= "</td></tr>";	
	$h .="</table><hr /><br />";

	}
	echo $h;
	if($s == 0){
	$o_name_short = $row[$q]['id'] . "-" . mb_substr($row[$q]['quote_date'], 0,10);
	 echo anchor('vecchio_admin/update_shipping_qq/' . $row[$q]['id'] .'/'. urlencode($o_name_short) , "Update Shipping" );
	}
	} ?>

</form>
<br /><br />
<?php echo $transaction_text;?>
</div>