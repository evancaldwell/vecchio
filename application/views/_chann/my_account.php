<?php if(!empty($tag) && is_array($tag[0]['shipping'])) {
		
		$ship_id = $tag[0]['shipping'][0]['id'];
		$location = $tag[0]['shipping'][0]['location'];
		$location_phone = $tag[0]['shipping'][0]['location_phone'];
		if($tag[0]['will_call'] == 0){
		$ship_address = $tag[0]['shipping'][0]['ship_address'];
		$ship_city = $tag[0]['shipping'][0]['ship_city'];
		$ship_state = $tag[0]['shipping'][0]['ship_state'];
		$ship_zip = $tag[0]['shipping'][0]['ship_zip'];
		} else {
		$ship_address = '';
		$ship_city = '';
		$ship_state = '';
		$ship_zip = '';			
		}
		$ednew = "Update ";
		$show_pay = true;
	} else {
		$ship_id = "";
		$location = "";
		$location_phone = "";
		$ship_address = "";
		$ship_city = "";
		$ship_state = "";
		$ship_zip = "";
		
		$ednew = "Enter ";
		$show_pay = false;
	}
	if(empty($tag)){
		$orders_up = false;
	} else {
		$orders_up = true;
	}

	
		?>
<script>
	var bsu = "<?php echo base_url(); ?>";
	
	
		 $(document).ready(function() {
			
			
			 $("#tabs").tabs({ 
				<?php
				 if($this->uri->segment(3) == 'cart' && (!empty($qq['new']) || !empty($qq['on_account']) || !empty($qq['paid']) || !empty($qq['past_orders']))){
				 echo "selected : 1";	
				 } else {
				 echo "selected : 0";	
				 } ?>
				});
			
			// check logout 
			$.sessionTimeout({
		        logoutUrl : "<?php echo base_url() . 'index.php/login/logoutuser/' ?>",
				redirUrl : "<?php echo base_url() . 'index.php/login/logoutuser/' ?>",
				keepAliveUrl : "<?php echo base_url() . 'index.php/dir/keep_alive/' ?>", 
				message: "Your session with Vecchio Trees is about to expire",
		    });


		
		$('select#avail_prod').selectmenu({
						style:'popup',
						menuWidth: 410,
						width: 410
		});
			
		function go_to_product(){
			var end = $("#avail_prod").val();
			window.location.href = bsu + "index.php/dir/sales/" + end;
		}
		
		
		$( "#go_product" )
					.button()
					.click(function() {
					go_to_product();
		});
		
		$(".iframes").fancybox({
			'width' : 990,
			'height' : 700,
			'autoScale' : false,
			'transitionIn'	 : 'none',
			'transitionOut'	 : 'none',
			'type'	 : 'iframe'});
			
		}); // end  		
</script>		
<?php if($orders_up){ ?>
		<script>
		var order_expire = "<?php echo $tag[0]['expire_date']; ?>";
		var fax_approved = "<?php echo $fax_approved; ?>";
		</script>
<script src="<?php echo base_url(); ?>_js/orders.js" type="text/javascript"></script>	
<?php } // check if there is an order to diplay this stuff.  ?>

 	

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

		/* demo styles */	
		label,select,.ui-select-menu { float: left; margin-right: 10px; }
		select { width: 200px; }		
		.wrap span.ui-selectmenu-item-header,
		.wrap ul.ui-selectmenu-menu li a { text-decoration: underline !important; }

#defaultCountdown { width: 240px; height: 38px; padding:3px;}	

.ui-tab-content, .ui-tabs-panel{overflow: auto;}
</style>


	<div id="sign_container">
	<div id="my_account">
		
		
			<?php if($active != 'false') {?>	
			<div id="return_message">
				<p id="rmessage">
				
				</p>
			</div>
			<div id="tabs" class="order_tabs">
				<ul>
					<?php if((!empty($qq['new']) || !empty($qq['on_account']) || !empty($qq['paid']) || !empty($qq['past_orders']))){ ?><li><a href="#tabssm-4">My Account</a></li><?php } ?>
					<?php if($orders_up){ ?><li><a href="#tabssm-2">My Cart</a></li><?php } ?>
					<li><a href="#tabssm-1">New Orders</a></li>
					<?php if(!empty($pend)){ ?><li><a href="#tabssm-3">Orders Pending Shipment</a></li><?php } ?>
					
				</ul>
				
				<?php if(!empty($qq['new']) || !empty($qq['on_account']) || !empty($qq['paid']) || !empty($qq['past_orders'])){ ?>
				<div id="tabssm-4"  >
				<?php
					if($this->uri->segment(3) == 'quote_id'){
						$this->load->view('_chann/quick_quote_view');
					} else {
				 		$this->load->view('_chann/list_quick_quotes');
					}
				 ?>	
				</div>
				<?php } // end if any quick quotesship ?>
				<?php
				 if($orders_up){ ?>		
				<div id="tabssm-2" style="z-index:10000" >
						

						<div id="tagged_items">
							<div style="width:340px; float:left;"><p>Items In Quote : <?php echo $tag[0]['id'] ."-". $tag[0]['order_name']?></p>
							PO Number: <a href="#" class="update_po_order_a" ><?php echo ($tag[0]['po_number'] != '' ? $tag[0]['po_number'] : 'Enter &raquo;' );?> </a>	
							</div>
							<div id="email_to_cust" style="padding:5px; width:300px; height:30px; float:left;">
							<?php
								$data = array( 'id' => "email_quote" );
								 echo form_open('dir/email_quote', $data); ?>
								<input type="hidden" name="order_id" value="<?php echo $tag[0]['id']; ?>" />
								Email: 
								<input type="text"  name="email_to" id="email_to" value="" />
								<button id="submit_email">Email Tagged Items</button>
								</form>
							</div>
							<div id="email_to_cust_resp" style="padding:5px; width:300px; height:30px; float:left; display:none;">
								Items emailed to: <span id="quote_email_to"></span>
							</div>
							<div style="clear:both"></div>
							
							<form id="remove_items_form" />
							<div id="tagged_wrap">
							<table cellpadding="0" cellspacing="0" style="margin:0px; padding:0px">
							<?php if(isset($qq_cart)){ ?>
							<tr> 
								<td colspan="4" style="padding:5px;" >
								<span >Cart In Quote Guide View -- <?php echo anchor('dir/remove_qq_guide/', 'Turn Off Quote Guide View'); ?></span>
								</td>
							</tr>
							<?php
								$this->load->view('_chann/qq_cart_list');
							} else {
								$this->load->view('_chann/reg_cart_list');	
							}?>
								
							<br />
							<?php if($show_pay){?>
							<button id="comp-credit">Pay With Credit Card <?php echo img('_images/small_cc.png');?> </button>
							<button id="comp-check-by-fax">Pay With Check By Fax <?php echo img('_images/small_fax.png');?> </button>
							<button id="comp-hold">Hold For Later</button>
							<?php if($credit['can_credit']) { ?>
							<button id="comp-on_credit">Place Order On Account</button>	
							<?php } ?>	
							<br /><br />
							<button id="edit-shipping">Edit Shipping Destination</button>
							<?php } else { ?>
							<p>Next Step: </p>
							<button class="enter-shipping"><?php echo $ednew; ?> Shipping Info</button>	
							<?php } ?>
						</div><!-- end tagged_items -->
						<div id="enter_freight">
							<!-- 


							SHIPPING INFO


							-->
							<div class="signblock">
								<p>Enter Freight Options	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	  <button class="back-cart">Back to Cart</button></p>
							</div>
							<form id="enter_freight_form">
							<?php
								$will_yes = '';
								$will_no = '';
							if($tag[0]['will_call'] == 1){
								$will_yes = 'checked';
							} else {
								$will_no = 'checked';
							}
							?>
							<div class="signblock">
							
							<input type="radio" name="will_call" value="0" <?php echo $will_no; ?> > Ship to Location <br />
							<input type="radio" name="will_call" value="1" <?php echo $will_yes; ?> > Will Call / Customer Pickup 
							</div>
							<div id="signleft">
								<div class="signblock">
									<input type="text" name="location" id="location" class="required" value="<?php echo $location; ?>">
								</div>
								<div class="signblock">
									<h2 id="error-location">Job Name / Id or Name of Recipient</h2>
								</div>
								<div class="signblock">
									<input type="text" name="location_phone" id="location_phone" class="required" value="<?php echo $location_phone; ?>">
								</div>
								<div class="signblock">
									<h2 id="error-location_phone">Phone # of Recipient</h2>
								</div>
								<div class="signblock">
									<input type="text" name="ship_address" id="ship_address"  value="<?php echo $ship_address; ?>">
								</div>
								<div class="signblock">
									<h2 id="error-ship_address">Address or Cross Street</h2>
								</div>


							</div><!-- end signleft -->
							<div id="signmidsmall"></div>
							<div id="signright">
								<div class="signblock">
									<input type="text" name="ship_city" id="ship_city"  value="<?php echo $ship_city; ?>">
								</div>
								<div class="signblock">
									<h2 id="error-ship_city">City</h2>				
								</div>
								<div class="signblock">
									<input type="text" name="ship_state" id="ship_state" value="<?php echo $ship_state; ?>" >
								</div>
								<div class="signblock">
									<h2 id="error-ship_state">State</h2>				
								</div>
								<div class="signblock">
									<input type="text" name="ship_zip" id="ship_zip" value="<?php echo $ship_zip; ?>" >
								</div>
								<div class="signblock">
									<h2 id="error-ship_zip" >Zip</h2>
								</div>
								<div class="signblock">
									<input type="submit" name="regsub" id="regsub" class="submit_ship" value="<?php echo $ednew; ?>Shipping Destination">
								</div>
							</div><!-- end signright -->
							<input type="hidden" name="order_id" value="<?php echo $tag[0]['id'];?>" />
							<input type="hidden" name="shipping_id" value="<?php echo $ship_id ;?>" />
							</form>
						</div><!-- enter_freight -->
						<div id="pay_credit_card">
							<!-- 


							PAY WITH CREDIT CARD


							-->
								<?php if($show_pay) { ?>
							<div class="signblock">

								<p>   <img src="<?php echo base_url() . "_images/lock.png"; ?>" />   Complete Order With Credit Card 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	   <button class="back-cart">Back to Cart</button></p>
							</div>
							<?php
							$data = array(
								'id' => "enter_credit_card"
							);
							echo form_open('vecchio_cc/validate_credit_card', $data);
							?>
							
								<input type="hidden" name="amount" value="<?php echo $tag[0]['freight']['grand_total'] ;?>"/>
								<input type="hidden" name="order_id" value="<?php echo $tag[0]['id'];?>" />
								<input type="hidden" name="order_name" value="<?php echo $tag[0]['order_name'] ;?>" />
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
									<input type="text" name="first_name" id="first_name" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-first_name" >First Name</h2>
								</div>
								<div class="signblock">
									<input type="text" name="last_name" id="last_name" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-last_name" >Last Name</h2>
								</div>
								<div class="signblock">
									<input type="text" name="email" id="email" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-email" >Email (For Receipt)</h2>
								</div>
								<div class="signblock">
									<input type="text" name="card_num" id="card_num" autocomplete="off" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-card_num">Credit Card Number</h2>
								</div>
								<div class="signblock">
									<input type="text" name="exp_date" id="exp_date" autocomplete="off"  class="required">
								</div>
								<div class="signblock">
									<h2 id="error-exp_date">Expire Date (03/13)</h2>
								</div>

							</div><!-- end signleft -->
							<div id="signmidsmall"></div>
							<div id="signright">

								<div class="signblock">
									<input type="text" name="card_code" id="card_code" class="required" autocomplete="off">
								</div>
								<div class="signblock">
									<h2 id="error-card_code">Card Code (On Back)</h2>
								</div>
								<div class="signblock">
									<input type="text" name="address" id="address" class="required">
								</div>
								<div class="signblock">
									<h2 id="error-address">Address</h2>
								</div>
								<div class="signblock">
									<input type="text" name="city" id="city" class="required">
								</div>
								<div class="signblock">
									<h2 id="error-city">City</h2>				
								</div>
								<div class="signblock">
									<input type="text" name="state" id="state" class="required">
								</div>
								<div class="signblock">
									<h2 id="error-state">State</h2>				
								</div>
								<div class="signblock">
									<input type="text" name="zip" id="zip" class="required">
								</div>
								<div class="signblock">
									<h2 id="error-zip" >Zip</h2>
								</div>
								<div class="signblock">
									<input type="submit" name="regsub" id="regsub" value="Sign E-Signature">
								</div>
									<input type="hidden" name="e_sig_main" id="e_sig_main" >
								</form>
							</div><!-- end signright -->
						<?php } // end check if freight ?>
						</div><!-- end pay_credit_card -->
						<div id="loadingbar"  >
							<br /><br /><br /><br />
							<?php echo img('_images/loadingbar.gif'); ?>
						</div>
						


						<div id="pay_check_by_fax">
							<!-- 


							PAY WITH CHECK BY FAX


							-->
							 <?php if($show_pay) { ?>
							<div class="signblock">
								<p> <img src="<?php echo base_url() . "_images/lock.png"; ?>" />  Complete Order With Check By Fax
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<button class="back-cart">Back to Cart</button> </p>
							</div>							
							<div id="signleft">
									<?php
									$data = array(
										'id' => "enter_check_by_fax"
									);
									echo form_open('vecchio_cc/check_by_fax', $data);
									?>
									<input type="hidden" name="amount" value="<?php echo $tag[0]['freight']['grand_total'] ;?>"/>
									<input type="hidden" name="order_id" value="<?php echo $tag[0]['id'];?>" />
									<input type="hidden" name="freight" value="<?php echo $tag[0]['freight']['total_cost_cust'] ?>" />
									<input type="hidden" name="order_name" value="<?php echo $tag[0]['order_name']?>" />
									<input type="hidden" name="expire_date" value="<?php echo$tag[0]['expire_date']?>" />
									
									<input type="hidden" name="coupon" value="" />
									<input type="hidden" name="pay_type" value="cstg" />
								<div class="signblock">
									<input type="text" name="first_namefx" id="first_namefx" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-first_namefx" >First Name</h2>
								</div>
								<div class="signblock">
									<input type="text" name="last_namefx" id="last_namefx" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-last_namefx" >Last Name</h2>
								</div>
								<div class="signblock">
									<input type="text" name="phonefx" id="phonefx" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-phonefx" >Phone Number</h2>
								</div>

							</div><!-- end signleft -->
							<div id="signmidsmall"></div>
							<div id="signright">
								<div class="signblock">
									<input type="text" name="faxfx" id="faxfx" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-faxfx" >Fax Number</h2>
								</div>
								<div class="signblock">
									<input type="text" name="emailfx" id="emailfx" class="required" >
								</div>
								<div class="signblock">
									<h2 id="error-emailfx" >Email (For Receipt)</h2>
								</div>							
								<div class="signblock">
									<h2>&nbsp;</h2>
								</div>
								<div class="signblock">
									<input type="submit" name="regsub" id="regsub" value="Sign E-Signature">
								</div>
								<input type="hidden" name="e_sig_mainfx" id="e_sig_mainfx" >
								</form>
							</div><!-- end signright -->						
							<?php } // end check if freight set.. ?>
						</div><!-- end pay_fax -->
						<!-- 


					   Download Check By Fax


						-->						
						<div id="download_check_by_fax">
							<!-- DEPRECIATED - ONCE FORM IS COMPLETE, THIS LOADS IN NEW WINDOW -->
							<div class="signblock">
									<p> <img src="<?php echo base_url() . "_images/lock.png"; ?>" />  Check By Fax Approved - Download PDF
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<button class="back-cart">Back to Cart</button> </p>
							</div>
							<div id="signleft">
								<br />
								<br />
									 Please Send Check By Fax<br /> Immediately For Processing
								<br />
								
								<?php 
								echo form_open('vecchio_download/check_by_fax_real');
								?>
								<input type="hidden" name="order_id" value="<?php echo $tag[0]['id'];?>" />
								<button id="dwn_lnk">Download Check By Fax Form &#9660;</button>
								</form>
								
							</div>	
						</div>			
						<!-- 


					   Countdown / Order Details


						-->

						<div id="total_items">
							<p>Order Expires In: <button class="whatsthis">Whats This?</button></p>
							<div id="defaultCountdown"></div>
							<p style="margin-top:9px; ">Order Totals:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
							$discount_percent = $tag[0]['freight']['discount_percent'];
							 if($show_pay && $discount_percent != 0) {
								echo "<span style='color:red'> Promo: ".$tag[0]['code']."</span>";
							}?> </p>
							<div style="margin-top:0px; background-color:white; padding:6px; border:1px solid #CCC;">
							<?php if($show_pay){?>
							<table border="0" cellpadding="0">
								<tr>
									<td width="120">Sub Total:</td>
									<td><?php
									   $order_total = "$". number_format($tag[0]['freight']['order_total'], 2, '.', ',');
									   $o = $tag[0]['freight']['order_total'];
									   if($tag[0]['boxed'] == 1){
										$order_total =  "$". number_format(($o - $can_box), 2, '.', ','); // box is shown seperate below;
									   }
									   if($discount_percent != 0){
											echo "<del>".$order_total."</del> ";
											echo "<span style='color:red'>".($discount_percent * 100) . "% Off </span><br />";
											echo "<u>$". number_format(($o - ($o * $discount_percent)), 2, '.', ',') . "</u>";
										} else {
											echo $order_total;
										}	
										?></td>
								</tr>
								<?php if($tag[0]['boxed'] == 1){?>
								<tr>
									<td>Box Trees</td>
									<td><?php echo "$". number_format($can_box, 2, '.', ',');?></td>
								</tr>
								<?php } ?>
								<tr>
									<td><a href="#" class="show_freight_info">Freight:</a></td>
									<td><?php echo "$". number_format($tag[0]['freight']['total_cost_cust'], 2, '.', ',');?></td>
								</tr>
								<tr>
									<td colspan="2"><hr /></td>
								</tr>
								<tr>
									<td><b>Grand Total</b></td>
									<td><b><?php echo "$". number_format($tag[0]['freight']['grand_total'], 2, '.', ',');?></b></td>
								</tr>
								<!-- 
								<tr>
									<td colspan="2">
										<form id="apply_promo" >
										<span id="error-promo_code">Promo Code: </span> <input type="input" name="promo_code" id="promo_code" />
										<button id="promo_btn"><span style="font-size:10px;">Apply</span></button>
										<input type="hidden" name="order_id" value="<?php // echo $tag[0]['id']; ?>" />
										</form>
									</td>
								</tr>
									-->
							</table>
							<?php } else { 
								echo "<p>Please Enter Shipping To Show Totals</p>"; 
								} ?>
							</div>
							<hr />
							<div id="show_ssl" style="display:none;">
								<br />
							
							<!-- (c) 2005, 2011. Authorize.Net is a registered trademark of CyberSource Corporation --> <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="34da993c-6ff8-488b-9f4e-d0e5a5309e5d";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Merchant Services</a> </div>
							</div>
						</div>
					<div style="clear:both;"></div>
				</div>
				<?php } // end if anythign is in the cart. ?>
				
				<div id="tabssm-1" style="height:400px; overflow: auto;">
				<?php $this->load->view('_chann/my_acc_welcome')?>
				</div><!-- end tab1 -->

				
				<?php if(!empty($pend)){ ?>
				<div id="tabssm-3" style="height:400px; overflow: auto;" >
				<?php $this->load->view('_chann/my_orders') ; ?>
				</div>
				<?php } // end if any pending ship ?>
				<!-- end my orders --> 
				
			</div><!-- end tabs -->
		<?php } else { // if ordering is turned off  ?>
			 <p>No items in cart</p>
		<?php } // end if ordering is turned off  ?>
		</div><!-- end box -->
			<?php if($show_pay){
				if($tag[0]['freight']['charge_trucks'] > 1){
					$s = "s";
				} else {
					$s = "";
				}
				?>
		<div id="freight_info" title="Freight Information">
				<p>
					<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
					Your freight is calculated as follows:<br />
					Your destination is set to: <b><?php echo $tag[0]['freight']['to_city'] . ", " . $tag[0]['freight']['to_state'];?> </b> which is 
					<b><?php echo $tag[0]['freight']['miles']; ?> miles</b> from our shipping facility.</p>

		</div>
		<?php } if($orders_up){ ?>
			<div id="update_po_order" title="Update Purchase Order Number" style="display:none;" >
					<?php if($tag[0]['po_number'] != ''){ ?>
					<p>
						The following purchase order number has been entered for this order: <b><?php echo $tag[0]['po_number']; ?></b>
					</p>
					<p>
					<?php echo form_open('dir/update_po');?>
					Update Purchase Order Number: <input type="text" value="<?php echo $tag[0]['po_number'];?>" name="po"  /> 
					<input type="hidden" name="qq_id" value="<?php echo $tag[0]['id']; ?>" />
					<input type="hidden" name="qq_order" value="order" />
					<input type="submit" value="Update">
					</form>	
					</p>
					<?php } else { // end if po number for quote ?>
						<p>
							Enter purchase order number 
						</p>
						<p>
						<?php echo form_open('dir/update_po');?> 
						Enter Purchase Order Number: <input type="text" value="" name="po"  /> 
						<input type="hidden" name="qq_id" value="<?php echo $tag[0]['id']; ?>" />
						<input type="hidden" name="qq_order" value="order" />
						<input type="submit" value="Submit" >
						</form>	
						</p>				
					<?php } ?>
			</div>		
		<div id="order_expire" title="Order Expiration Information">
				<p>
					<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
					We allow our customers to "Tag" individual trees for sale. Once tagged, these trees become unavailable to other customers. We allow you a specified amount of time to complete your order before we place them back on the market. <b>If you need an extension on your order, please contact Sales </b>
				</p>
				<p><b>Your tagged trees will be held even if you leave the site or log off</b> </p>

		</div>
		<div id="hold_later" title="Hold Order">
				<p>
				<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
					Your quote will be held until <b><?php echo date("l F j, Y - g:i a", strtotime($tag[0]['expire_date'])); ?> * </b>
				</p>
				<p>
					Please keep a record of your quote id:  <b><?php echo $tag[0]['id'] ."-". $tag[0]['order_name']?></b>
				</p>
				<p><b>Your quote will be held even if you leave the site or log off</b> </p>
				<p style="font-size:11px;"><b>* If you need an extension on your order, please contact Sales at 855 819-7777. </b></p>

		</div>
		
		<div id="return_message_email" title="Email Sent">
				<p>
				<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
					Email sent to <span id="sent_email"></span> containing product pictures and information. 
				</p>
		</div>
		
		<div id="dialog-check-by-fax" title="Check By Fax Confirmation">
				<p>
				<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span> Thank you, we have received your request to complete your order using check by fax. Your quote will automatically receive an additional 3 business days while VECCHIO Trees processes your order. Please download 
				</p>
				<p>
					<b>You will receive a confirmation email once we have processed your payment. </b>
				</p>
				<p>
					Please keep a record of your quote id:  <b><?php echo $tag[0]['id'] ."-". $tag[0]['order_name']?></b>
				</p>
				

		</div>			
				
			<div id="dialog-confirm"	title="Please Confirm Removal Of Product">
						<p>
						 	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span> Please confirm removal of product from your order.
						</p>
						<p id="extra_warning" style="font-weight:bold;">

						</p>
				
			</div>
		
		<div id="dialog-error" title="Please Enter Shipping Destination">
				<p>
				 	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>A shipping destination has not been set for your order. Please enter shipping destination to calculate freight costs. 
				</p>
				<p>
				 	Freight is calculated by the amount of trucks needed for your order and the distance in miles from our grow yards to your destination. <b>Shipping multiple products in one order can reduce your freight costs considerably.</b> 
				</p>
				<p>
					
				</p>
		</div>
			<div id="dialog-error-fax" title="Check By Fax">
					<p>
					 	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>A request to complete order with check by fax has already been issued. Please contact VECCHIO If you have any questions regarding your order. 
					</p>
			</div>
			
			<div id="bad_promo" title="Unrecognized Promo Code">
					<p>
					<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
						The promo code you entered is not recognized by our system. 
					</p>
			</div>
			
			<div id="good_promo" title="Applying Promo Code">
					<p>
					<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
						We are now applying your discount <br />
						<?php
						  $data = array(
						  	'style' => 'width:200px; text-align:center;'
						  );
						 echo img('_images/loadingbar.gif', $data); ?>
					</p>
			</div>
			
		<?php
		 if($credit['can_credit']) { ?>
		<form id="order_on_credit" method="POST" action="<?php echo base_url() . 'index.php/vecchio_cc/order_on_account/'?>" >
				<input type="hidden" name="e_sig_main_oc" id="e_sig_main_oc" >
				<input type="hidden" name="amount" value="<?php echo $tag[0]['freight']['grand_total'] ;?>"/>
				<input type="hidden" name="order_id" value="<?php echo $tag[0]['id'];?>" />
				<input type="hidden" name="freight" value="<?php echo $tag[0]['freight']['total_cost_cust'] ?>" />
				<input type="hidden" name="pay_type" value="cstg" />
		</form>	
		<?php } ?>		
			
		<div id="terms_and_warranty" style="display:none;" title="Terms And Conditions">
			<div style="height:340px; overflow:auto;">
			<?php echo $w; // WARRANTY GENERATED FROM DB -- ?>
			</div>
		<div style="background-color:#FFFF99; padding:10px; margin:10px; border:3px dashed #ccc;">
			<p><b>Electronic Signature: X </b> <input type="text"  style="width:200px;"name="e_sig" id="e_sig" /> (Enter Full Name) <br />
				<span id="creditorfax"></span> and I agree to the Terms and Conditions, Warranty and Company Policies of Vecchio Trees. </p>
		</div>	
		<input type="hidden" name="cof" id="cof" />
		</div><!-- end terms and conditions -->
		<?php }  // end to check if there are any orders. ?>
	</div> <!-- end sign container -->