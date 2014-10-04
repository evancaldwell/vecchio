<br />
<script src="<?php echo base_url();?>_js/ui/jquery.ui.tabs.js"></script>
<script src="<?php echo base_url();?>_js/ui/jquery.ui.dialog.js"></script>
<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script> 

<script src="<?php echo base_url();?>_js/ui/jquery.ui.dialog.js"></script>
<style>
.order_tabs {width:384px; margin:0px; padding:0px; font-size:12px; letter-spacing:0px; }
</style>
<?php
// echo "<pre>";
// print_r($tag);
// echo "</pre>";
$count = count($tag);

for($i=0; $i<$count; $i++){
	
	$order_n = $tag[$i]['info']['order_name'];
?>
<script>
	 $(document).ready(function() {
	  $("#tabs-<?php echo $tag[$i]['id'];?>").tabs();
	});
</script>
<div width="900">
<div style="width:430px; float:left; margin-left:20px;">
<?php  echo form_open('vecchio_rep/remove_item');?>
<div id="tagged_wrap">
<table cellpadding="0" cellspacing="0" style="margin:0px; padding:0px">
	
	<tr>
		<td colspan="5" bgcolor="#6EA7D1"><span style="font-size:15px; color:white;"><?php echo $order_n;?></span>
			<br />
			<span style="font-size:9px; color:white;">Expires: <?php echo date("l F j, Y g:i a", strtotime($tag[$i]['expire_date'])); ?></span>
			</td>
	</tr>
	<tr>
	<td >&#x2713;</td>
	<td >Product Serial Number</td>
	<td >Description&nbsp;&nbsp;</td>
	<td >Trees To Truck&nbsp;&nbsp;</td>
	<td class="ti_price_top">Price&nbsp;&nbsp;</td>
	</tr>

<?php
$cust_cost = 0;
$num_in_order = 0;
$count_inner = count($tag[$i]['info']['order_items']);

for($c = 0; $c<$count_inner; $c++){ ?>
	<tr>
	<td ><input type="checkbox" id="rem-<?php echo $tag[$i]['info']['order_items'][$c]['order_item_id']; ?>" name="<?php echo $tag[$i]['info']['order_items'][$c]['order_item_id']; ?>" value="1" /></td>
	<td><a  href="<?php echo base_url() . "index.php/vecchio_rep/show_product_info/" . $tag[$i]['info']['order_items'][$c]['id'] ?> "><?php echo $tag[$i]['info']['order_items'][$c]['serial_no'];?></a></td>
	<td ><?php echo $tag[$i]['info']['order_items'][$c]['description']; ?></td>
	<td ><?php echo $tag[$i]['info']['order_items'][$c]['trees_to_truck']; ?></td>
	<td ><?php echo "$". number_format($tag[$i]['info']['order_items'][$c]['cust_cost'], 2, '.', ',');?></td>
	</tr>
<?php 
$cust_cost += $tag[$i]['info']['order_items'][$c]['cust_cost'];
$num_in_order ++;
} ?>
	<tr>
		<td  colspan="4" align="right">Sub Total&nbsp;&nbsp;</td>
		<td ><?php echo "$". number_format($cust_cost, 2, '.', ','); ?></td>
	</tr>
<?php if(!empty($tag[$i]['freight'])){?>
	<tr>
		<td  colspan="4" align="right">Freight&nbsp;&nbsp;  </td>
		<td ><?php echo "$". number_format($tag[$i]['freight']['total_cost_cust'], 2, '.', ','); ?></td>
	</tr>
	<tr>
		<td  colspan="4"  align="right">Grand Total&nbsp;&nbsp;  </td>
		<td ><?php echo "$". number_format($tag[$i]['freight']['grand_total'], 2, '.', ','); ?></td>
	</tr>
<?php } ?>
</table>
</div>
<p><button id="remove_item">Remove &#x2713; Item(s) From Order</button></p>
</form>
</div><!-- end left itemized tab -->

<div style="width:320px; float:left; margin-left:10px;">
<div id="tabs-<?php echo $tag[$i]['id'];?>" class="order_tabs">
	<ul>
		<li><a href="#tabssm-1-<?php echo $tag[$i]['id'];?>s">Client</a></li>
		<li><a href="#tabssm-2-<?php echo $tag[$i]['id'];?>s">Freight</a></li>
		<li><a href="#tabssm-3-<?php echo $tag[$i]['id'];?>s">Shipping</a></li>
		<li><a href="#tabssm-4-<?php echo $tag[$i]['id'];?>s">Downloads</a></li>
	</ul>
	<div id="tabssm-1-<?php echo $tag[$i]['id'];?>s" >
			<table>
				<tr><td>Customer<br /><?php echo $tag[$i]['info']['client_info']['fname'] . " " .$tag[$i]['info']['client_info']['lname'] . "<br />";
				 											      echo $tag[$i]['info']['client_info']['company_name']?></td></tr>
				<tr><td>Phone<br /><?php echo $tag[$i]['info']['client_info']['phone'] ?></td></tr>
				<tr><td>Billing<br /><?php echo $tag[$i]['info']['client_info']['bill_address']; ?><br />
																  <?php echo $tag[$i]['info']['client_info']['bill_city']; ?>,&nbsp;
																  <?php echo $tag[$i]['info']['client_info']['bill_state']; ?>&nbsp;
																  <?php echo $tag[$i]['info']['client_info']['bill_zip']; ?>
																<hr width="290" />
				<?php echo anchor('vecchio_rep/edit_user/'. $tag[$i]['info']['client_info']['id'], "Edit User Info &raquo;");?>												
				</td></tr>
			</table>
	</div><!-- end client -->
	<div id="tabssm-2-<?php echo $tag[$i]['id'];?>s" >
		<?php
		if(!empty($tag[$i]['freight'])){
		$from_city = $tag[$i]['freight']['from_city'];
		$from_state = $tag[$i]['freight']['from_state'];
		$to_city = $tag[$i]['freight']['to_city'];
		$to_state = $tag[$i]['freight']['to_state'];
		$miles = $tag[$i]['freight']['miles'];
		$buffer_miles = $tag[$i]['freight']['buffer_miles'];
		$cost_per_mile = $tag[$i]['freight']['cost_per_mile'];
		$mileage_cost = $tag[$i]['freight']['mileage_cost'];
		$charge_trucks = $tag[$i]['freight']['charge_trucks'];
		$total_cost_cust = $tag[$i]['freight']['total_cost_cust'];
		$trucks_each_gy = $tag[$i]['freight']['trucks_each_gy'];
		$actual_trucks = $tag[$i]['freight']['actual_trucks'];
		$total_actual_trucks = $tag[$i]['freight']['total_actual_trucks'];
		$total_cost_vecchio = $tag[$i]['freight']['total_cost_vecchio'];
		$total_gy = $tag[$i]['freight']['total_gy'];
		
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
		$data = array(
		       'id' => 'new_shipping'
		);
		echo form_open('vecchio_rep/update_shipping_capture', $data);?>

		<?php // $this->load->view('_content/new_shipping_extra'); ?>

		<?php // print_r($shipping)?>
		<div>
			<label>Shipping Destination Name (Location Name or Resident's Name) </label>
			<?php
			$data = array(
				'name' => 'location',
				'style' => 'width:200px;'
			);
			echo form_input($data);
			?>
		</div>
		<div>
			<label> Location Phone </label>
			<?php
			$data = array(
				'name' => 'location_phone',
				'style' => 'width:200px;'
			);
			echo form_input($data);
			?>
		</div>
		<div>
			<label>Shipping Address</label>
			<?php
			$data = array(
				'name' => 'ship_address',
				'style' => 'width:200px;'
			);
			echo form_input($data);
			?>
		</div>
		<div>
			<label>Shipping City</label>
			<?php
			$data = array(
				'name' => 'ship_city',
				'style' => 'width:150px;'
			);
			echo form_input($data);
			?>
		</div>
		<div>
			<label>Shipping State</label>
			<?php
			$data = array(
				'name' => 'ship_state',
				'style' => 'width:20px;'
			);
			echo form_input($data);
			?>
		</div>
		<div>
			<label>Shipping Zip</label>
			<?php
			$data = array(
				'name' => 'ship_zip',
				'id' => 'zipcode',
				'style' => 'width:100px;'
			);
			echo form_input($data);
			?>
		</div>
		
		<input type="hidden" name="order_id" value="<?php echo $tag[$i]['id'];?>" />
		<input type="hidden" name="shipping_id" value="0" />
		<br /><br />
		<div>
			<?php echo form_submit('submit', 'Enter Freight Destination');?>
		</div>
		<?php echo form_close();?>
		<br />
		<br />
		<?php  } // else freight is not entered ?>
	</div><!-- end freight -->
	<div id="tabssm-3-<?php echo $tag[$i]['id'];?>s" >
		<?php if(!empty($tag[$i]['info']['shipping_info'])){?>	
		Ship To: <br />
														  <?php echo $tag[$i]['info']['shipping_info']['location']; ?><br />
														  <?php echo  $tag[$i]['info']['shipping_info']['ship_address']; ?><br />
														  <?php echo  $tag[$i]['info']['shipping_info']['ship_city']; ?>,&nbsp;
														  <?php echo  $tag[$i]['info']['shipping_info']['ship_state']; ?>&nbsp;
														  <span style="color:red"><?php echo $tag[$i]['info']['shipping_info']['ship_zip']; ?><br /></span>
		<?php echo form_open('vecchio_rep/update_shipping') ;?>
		<input type="hidden" name="order_name" value="<?php echo $order_n ?>" />
		<input type="hidden" name="shipping_id" value="<?php echo $tag[$i]['info']['shipping_info']['id']; ?>" />
		<input type="submit" value="Change Shipping Info" />
		</form>
		<?php } ?>
	</div>
	<div id="tabssm-4-<?php echo $tag[$i]['id'];?>s" >

		<?php 
		if($count_inner != 0){
?>
		
		<p>Download Quote Details in PDF Form (save and send to client)</p>
		<?php echo form_open('vecchio_download/vecchio_quote/'); ?>
		<input type="hidden" name="order_id" value="<?php echo $tag[$i]['id'];?>" />
		<input type="submit" value="Download Quote PDF" />
			<p style="font-size:9px;">May take a minute to download. Click "Save" with finished</p>
		</form>
		<br />
		<br />
		<hr />
	<?php 	echo form_open('vecchio_download/check_by_fax_real/'); ?>
		<p>Download Check By Fax sheet for this order</p>
		<input type="hidden" name="order_id" value="<?php echo $tag[$i]['id'];?>" />
		<input type="submit" value="Download Check By Fax for Order PDF" />
		</form>
		<br />
		<br />
		<hr />	
		<?php
			$data = array( 'id' => "email_quote" );
			 echo form_open('vecchio_rep/email_quote', $data); ?>
			<input type="hidden" name="order_id" value="<?php echo $tag[$i]['id']; ?>" />
			Email: 
			<input type="text"  name="email_to" id="email_to" value="<?php echo $this->session->userdata('usern_email'); ?>" />
			<button id="submit_email">Email Tagged Items</button>
			</form>
	

		<?php } else {?>
		No products in order
		<?php } ?>
	</div>
</div><!-- end tabs -->
</div><!-- end wrapper for tabs -->

<div style="clear:both;" ></div>
<br /><br />
<hr />
<br />
</div>
<?php } 
if($count == 0){
	
	echo "<p>No Quotes in system under ". $this->session->userdata('fname') . " ".$this->session->userdata('lname')."</p><br />";
}
 ?>