	<strong>Complete Order with Credit Card : $<?php echo number_format($new[$i]['freight']['grand_total'], 2, '.', ',');?></strong><br />
	<?php echo form_open('vecchio_cc/validate_credit_card'); ?>
	<input type="hidden" name="amount" value="<?php echo $new[$i]['freight']['grand_total'] ;?>"/>
	<input type="hidden" name="order_id" value="<?php echo $new[$i]['id'];?>" />
	<input type="hidden" name="coupon" value="" />
	<input type="hidden" name="freight" value="<?php echo $new[$i]['freight']['total_cost_cust'] ?>" />
	<input type="hidden" name="ship_address" value="<?php echo $new[$i]['shipping'][0]['ship_address']; ?>" />
	<input type="hidden" name="ship_location" value="<?php echo $new[$i]['shipping'][0]['location']; ?>" />
	<input type="hidden" name="ship_city" value="<?php echo $new[$i]['shipping'][0]['ship_city']; ?>" />
	<input type="hidden" name="ship_state" value="<?php echo $new[$i]['shipping'][0]['ship_state']; ?>" />
	<input type="hidden" name="ship_zip" value="<?php echo $new[$i]['shipping'][0]['ship_zip']; ?>" />
	<input type="hidden" name="pay_type" value="admin" />
	<div>
	<label>First Name (as it appears on Credit Card)</label>
	<input type="input" name="first_name" value=""/>
	</div>

	<div>
	<label>Last Name (as it appears on Credit Card)</label>
	<input type="input" name="last_name" value=""/>
	</div>

	<div>
	<label>Credit Card Number</label>
	<input type="input" style="width:200px" name="card_num" value=""/>
	</div>
	<div>
	<label>Expire Date (03/13)</label>
	<input type="input" style="width:50px" name="exp_date" value=""/>
	</div>
	<div>
	<label>Card Code (3-4 digits on back)</label>
	<input type="input" style="width:25px" name="card_code" value=""/>
	</div>
	<div>
	<label>Billing Address</label>
	<input type="input" style="width:200px;" name="address" value="<?php echo $new[$i]['client'][0]['bill_address']; ?>"/>
	</div>

	<div>
	<label>City</label>
	<input type="input" name="city" value="<?php echo $new[$i]['client'][0]['bill_city']; ?>"/>
	</div>

	<div>
	<label>State</label>
	<input type="input" name="state" value="<?php echo $new[$i]['client'][0]['bill_state']; ?>"/>
	</div>

	<div>
	<label>Zip</label>
	<input type="input" name="zip" value="<?php echo $new[$i]['client'][0]['bill_zip']; ?>"/>
	</div>

	<div>
	<label>Email (to receive customer receipt)</label>
	<input type="input" name="email" value=""/>
	</div>
	<input type="submit" value="Submit Payment" />
</form>