<strong>Complete Order with eCheck : $<?php echo number_format($new[$i]['freight']['grand_total'], 2, '.', ',');?></strong><br />
<?php echo form_open('vecchio_cc/validate_echeck'); ?>
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
<label>Name On Bank Account</label>
<input type="input" style="width:200px;" name="bank_acct_name" value=""/>
</div>

<div>
<label>Account Type</label>
<input type="radio" name="bank_acct_type" value="BUSINESSCHECKING"> Business Checking <br />
<input type="radio" name="bank_acct_type" value="CHECKING"> Personal Checking <br />
<input type="radio" name="bank_acct_type" value="SAVINGS"> Savings Account <br />
</div>

<div>
<label>Bank Name</label>
<input type="input" style="width:200px" name="bank_name" value=""/>
</div>

<div>
<label>Bank Routing Number (9 Digits)</label>
<input type="input" style="width:200px" name="bank_aba_code" value=""/>
</div>

<div>
<label>Account Number</label>
<input type="input" style="width:200px" name="bank_acct_num" value=""/>
</div>
<div>
<label>Email (to receive customer receipt)</label>
<input type="input" name="email" value=""/>
</div>
<input type="submit" value="Submit Payment" />

</form>