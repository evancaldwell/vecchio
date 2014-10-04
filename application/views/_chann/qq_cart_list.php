<?php
$cust_cost = 0;
$num_in_order = 0;
$count = count($o);
// outer loop for quick quote items
foreach ($qq_o as $q){
	$prod_type = $q['product_type_id'];
	?>

	<tr> 
		<td colspan="4" class="ti_top_item">
			
		 <span class="main_item">Your Quote: <?php echo $q['quantity'] . " " . $qq_items[$prod_type]['description']  ?></span>

		</td>
	</tr>
		<tr>
			<td class="ti_check_top">&#x2713;</td>
			<td class="ti_serial_no_top">Product Serial Number</td>
			<td class="ti_description_top">Description</td>
			<td class="ti_price_top">Price</td>
		</tr>

	<?php
	// inner loop for order items
	for($i=0;$i<$count;$i++){
		if($o[$i]['product_type_id'] == $prod_type){
			?>
			<tr>
				<td class="ti_check"><input type="checkbox" id="rem-<?php echo $o[$i]['order_item_id']; ?>" name="<?php echo $o[$i]['order_item_id']; ?>" value="1" /></td>
				<td class="ti_serial_no"><a class="iframes" href="<?php echo base_url() . "index.php/image_bank/byproduct/" . $o[$i]['product_id'] ?>"><?php echo $o[$i]['serial_no'] ?></a></td>
				<td class="ti_description"><?php echo $o[$i]['description']; ?></td>
				<td class="ti_price">$<?php echo number_format($o[$i]['cust_cost'], 2, '.', ',') ?></td>
			</tr>
			<?php 
			$o[$i]['mark'] = 1;
			$cust_cost += $o[$i]['cust_cost'];
			$num_in_order ++;
		}				
	}
	if(array_key_exists($prod_type, $missing)){
		$hay = array_key_exists($prod_type, $order_items) ? true : false;
		$more = $hay ? " More " : " ";
		$s = $hay ? "" : "s";
		?>
		<tr> 
			<td colspan="4" class="ti_select_more">
			 <?php echo anchor('dir/sales/' . $prod_type ,"Select " . $missing[$prod_type] .$more. $qq_items[$prod_type]['description'] . $s); ?>
			</td>
		</tr>
	
		<?php 
	} else {
		?>
		<tr> 
			<td colspan="4" class="ti_select_more">
			 <?php 	echo anchor('dir/sales/' . $prod_type , "Select More " . $qq_items[$prod_type]['description']) ; ?>
			</td>
		</tr>
		<?php
	}
	
}

// remove all the marked
for($i=0;$i<$count;$i++){
	if(array_key_exists('mark', $o[$i])){
	unset($o[$i]);
	}
}
sort($o);
$new_count = count($o);
if(!empty($o)){
	?>
	<tr> 
		<td colspan="4" class="ti_top_item">
			<span class="main_item">Other Items (Outside of Quote)</span>
		</td>
	</tr>
	<tr>
		<td class="ti_check_top">&#x2713;</td>
		<td class="ti_serial_no_top">Product Serial Number</td>
		<td class="ti_description_top">Description</td>
		<td class="ti_price_top">Price</td>
	</tr>	
	<?php
	for($i=0;$i<$new_count;$i++){
		?>
			<tr>
				<td class="ti_check"><input type="checkbox" id="rem-<?php echo $o[$i]['id']; ?>" name="<?php echo $o[$i]['id']; ?>" value="1" /></td>
				<td class="ti_serial_no"><a class="iframes" href="<?php echo base_url() . "index.php/image_bank/byproduct/" . $o[$i]['product_id'] ?>"><?php echo $o[$i]['serial_no'] ?></a></td>
				<td class="ti_description"><?php echo $o[$i]['description']; ?></td>
				<td class="ti_price">$<?php echo number_format($o[$i]['cust_cost'], 2, '.', ',') ?></td>
			</tr>
		<?php
		$cust_cost += $o[$i]['cust_cost'];
		$num_in_order ++;
	}
}
?>
	<tr>
		<td class="ti_blank" colspan="3">Sub Total</td>
		<td class="ti_price" ><?php echo "$". number_format($cust_cost, 2, '.', ','); ?></td>
	</tr>
	<tr>
	<td colspan="2"><button id="remove_item">Remove &#x2713; Item(s) From Order</button></td>

	<td colspan="2" align="right" >
		

	</td>
	</tr>

</table>

<?php $this->load->view('_chann/cart_optional'); ?>

</div>
</form>	
	<input type="hidden" id="num_in_order" value="<?php echo $num_in_order; ?>" />