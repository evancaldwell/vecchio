<script>
// don't forget the doc open ..

$("#enter_e_check_e").validate({
	rules: {
		bank_aba_code : {
			required: true,
			minlength: 9
		},
		card_code : {
			required: true
		},
		bank_name : {
			required: true
		},
		bank_acct_name : {
			required : true
		},
		bank_acct_num : {
			required: true
		},
		termsec : {
			required: true
		}
		
	},
	errorPlacement: function(error, element) {
        error.appendTo('#error-' + element.attr('id'));
    }
});

function show_e_check(){
	$('#tagged_items').hide();
	$('#enter_freight').hide();
	$('#pay_credit_card').hide();
	$('#pay_e_check').show();
	$('#pay_check_by_fax').hide();
	$('#show_ssl').show();
}

$( "#comp-e-check" )
			.button()
			.click(function() {
				show_e_check();
});

</script>

<button id="comp-e-check">Complete Order With E-Check</button>


<div id="pay_e_check">
	<!-- 


	PAY WITH E CHECK


	-->
	 <?php if($show_pay && $tag[0]['freight']['grand_total'] < 4000) { ?>
	<div class="signblock">
		<p> <img src="<?php echo base_url() . "_images/lock.png"; ?>" />  Complete Order With E-Check
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<button class="back-cart">Back to Cart</button> </p>
	</div>
		<?php
		$data = array(
			'id' => "enter_e_check_e"
		);
		echo form_open('vecchio_cc/validate_echeck', $data);
		?>
		<input type="hidden" name="amount" value="<?php echo $tag[0]['freight']['grand_total'] ;?>"/>
		<input type="hidden" name="order_id" value="<?php echo $tag[0]['id'];?>" />
		<input type="hidden" name="coupon" value="" />
		<input type="hidden" name="freight" value="<?php echo $tag[0]['freight']['total_cost_cust'] ?>" />
		<input type="hidden" name="ship_address" value="<?php echo $tag[0]['shipping'][0]['ship_address']; ?>" />
		<input type="hidden" name="ship_location" value="<?php echo $tag[0]['shipping'][0]['location']; ?>" />
		<input type="hidden" name="ship_city" value="<?php echo $tag[0]['shipping'][0]['ship_city']; ?>" />
		<input type="hidden" name="ship_state" value="<?php echo $tag[0]['shipping'][0]['ship_state']; ?>" />
		<input type="hidden" name="ship_zip" value="<?php echo $tag[0]['shipping'][0]['ship_zip']; ?>" />
		<input type="hidden" name="pay_type" value="cstg" />
	<div id="signleft">
		
		<div class="signblock">
			<input type="text" name="bank_acct_name" id="bank_acct_name" autocomplete="off" class="required" >
		</div>
		<div class="signblock">
			<h2 id="error-bank_acct_name" >Name on Bank Account</h2>
		</div>

		<div class="signblock">
			<input type="text" name="bank_name" id="bank_name" autocomplete="off" class="required">
		</div>
		<div class="signblock">
			<h2 id="error-bank_name" >Bank Name</h2>
		</div>
		<div class="signblock">
			<div style="margin-left:10px">
			<input type="radio" name="bank_acct_type" value="BUSINESSCHECKING" checked > Business Checking <br />
			<input type="radio" name="bank_acct_type" value="CHECKING"> Personal Checking <br />
			<input type="radio" name="bank_acct_type" value="SAVINGS"> Savings Account <br />
			</div>
		</div>
		<div class="signblock">
			<h2 id="error-lname" >Bank Account Type</h2>
		</div>

	</div><!-- end signleft -->
	<div id="signmidsmall"></div>
	<div id="signright">
		<div class="signblock">
			<input type="text" name="bank_aba_code" id="bank_aba_code" autocomplete="off" class="required">
		</div>
		<div class="signblock">
			<h2 id="error-bank_aba_code">Bank Routing Number (9 Digits)</h2>
		</div>
		<div class="signblock">
			<input type="text" name="bank_acct_num" id="bank_acct_num" autocomplete="off" class="required">
		</div>
		<div class="signblock">
			<h2 id="error-bank_acct_num">Account Number</h2>
		</div>
		<div class="signblock">
			<input type="text" name="email" id="emailec" class="required" >
		</div>
		<div class="signblock">
			<h2 id="error-emailec" >Email (For Receipt)</h2>
		</div>							
		<div class="signblock">
				<h2 id="error-termsec"> <input type="checkbox" name="termsec" id="termsec" class= "required" > 
					I Accept <a href="#" class="show_terms" >Terms and Conditions</a></h2>
		</div>
		<div class="signblock">
			<h2>&nbsp;</h2>
		</div>
		<div class="signblock">
			<input type="submit" name="regsub" id="regsub" value="Pay By E-Check">
		</div>
		</form>
	</div><!-- end signright -->						
	<?php } // end check if freight set.. ?>
</div><!-- end pay_e_check -->