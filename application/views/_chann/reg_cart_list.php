
	<tr>
	<td class="ti_check_top">&#x2713;</td>
	<td class="ti_serial_no_top">Product Serial Number</td>
	<td class="ti_description_top">Description</td>
	<td class="ti_price_top">Price</td>
	</tr>

<?php
$cust_cost = 0;
$num_in_order = 0;
$count = count($tag[0]['order_items']);

for($i = 0; $i<$count; $i++){ ?>
	<tr>
	<td class="ti_check"><input type="checkbox" id="rem-<?php echo $tag[0]['order_items'][$i]['id']; ?>" name="<?php echo $tag[0]['order_items'][$i]['id']; ?>" value="1" /></td>
	<td class="ti_serial_no"><a class="iframes" href="<?php echo base_url() . "index.php/image_bank/byproduct/" . $tag[0]['order_items'][$i]['product_id'] ?> ">	<?php echo $tag[0]['order_items'][$i]['product_info'][0]['serial_no'];?></a></td>
	<td class="ti_description"><?php echo $tag[0]['order_items'][$i]['product_info'][0]['product_type'][0]['description']; ?></td>
	<td class="ti_price"><?php echo "$". number_format($tag[0]['order_items'][$i]['cust_cost'], 2, '.', ',');?></td>
	</tr>
<?php 
$cust_cost += $tag[0]['order_items'][$i]['cust_cost'];
$num_in_order ++;
} ?>

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