<h1><?php echo $info[0]['serial_no'];?></h1>

<?php if($info[0]['status'] == ""){?>
<h3 class="avail">Available for Purchase</h3>
<?php } else if($info[0]['status'] == 1) {?>
<h3 class="tagged">Item Tagged for Purchase</h3>
<?php } else { ?>
<h3 class="tagged">Item Purchased</h3>
<?php } ?>
<div style="margin-left:20px; width:300px; float:left;">
<table>
	<tr><td>Description: </td>
		<td><?php echo $info[0]['description'];?></td>
	</tr>
	<tr><td>Specs: </td>
		<td><?php echo $info[0]['specs'];?></td>
	</tr>
	<tr>
		<td>List Price: </td>
		<td>$<?php echo number_format($info[0]['list_price'], 2, '.', ',');?></td>
	</tr>
</table>
</div>
<div style="width:400px; float:right; margin-right:35px;">
	<!--- set status of products -->
	<?php if($info[0]['status'] == 0 && !empty($aval_tagged)){ ?>
	<?php echo form_open('vecchio_rep/add_to_order');?>
	<input type="hidden" name="product_id" value="<?php echo $info[0]['id']; ?>" />
		<label>Add to open order (Need customer permission)</label>
		
		<?php
	//	echo "<pre>";
	//	print_r($aval_tagged);
			$prod_options = array();
			$prod_options['no_selected'] = "Select Open Order";
			if(isset($aval_tagged)){ 
				foreach($aval_tagged as $row){
				$price_for_customer = "$" . number_format(($row['multiplier'] * $info[0]['list_price']), 2, '.', ',');
					
				$prod_options[$row['id']] = $row['id']. '-'. $row['order_name'] . " " . $row['company_name'] . "-" . $row['lname'] . " P: " . $price_for_customer;
					}
			} else { 
				$prod_options[''] = 'No Open Orders - Check Expired Orders';  
			}
			$endid = 'id = "ComboBox_orders"';
			echo form_dropdown('order_id', $prod_options, '', $endid);
		?>

	
		<?php echo form_submit('submit', 'Add Product To Order');?>
		<input type="hidden" name="product_id" value="<?php echo $info[0]['id']; ?>" />
		<input type="hidden" name="prod_name" value="<?php echo $info[0]['serial_no']. "<br /> List Price: $" . number_format($info[0]['list_price'], 2, '.', ','); ?>" />
    	<?php echo form_close();?>
		<br />
		<hr />

     <?php } else {
	    echo "To tag product for customer, start by clicking \"New Quote\" on menu above." ; 
} ?>
</div>
<p>
<?php
$count = count($images);
for($i=0; $i<$count; $i++){ ?>

<img src="<?php echo base_url().'_fdr/'.$images[$i]['file_name']?> " width="800" />	<br />
<?php } ?>
</p>