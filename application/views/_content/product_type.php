<div style="margin-left:5px;">
<h3>Product Types</h3>
<h3><?php echo anchor('vecchio_admin/create_product_type/', 'Create New Product Type'); ?></h3>
<table>
<tr>
	<td>Product Code</td>
		<td>Name</td>
	<td>Box Size / Grow Yard</td>
	<td>Zone / Watering / Exposure</td>
	<td>Text Description</td>
	<td>Size / Root Ball / Weight</td>
	<td>Trees To Truck</td>
	<td>Specs / DBH</td>
	<td>Whole Sale</td>
	<td>List Price</td>
</tr>
	<?php foreach($product_type as $pt){
		echo "<tr>";
		echo "<td> " . anchor('vecchio_admin/update_product_type/' . $pt['id'],$pt['product_code'] ) . "</td>";
		echo "<td> " . $pt['description'] . "</td>";
		echo "<td> " . $pt['box_size'] . " / " .$pt['grow_yard']. "</td>";
		echo "<td> " . $pt['zone'] . " / " . $pt['watering'] . ' / ' . $pt['exposure'] . "</td>";
		echo "<td> " . $pt['text_description'] . "</td>";
		echo "<td> " . $pt['size'] . " / ". $pt['root_ball'] . " / ". $pt['weight']. "</td>";
		echo "<td> " .$pt['trees_to_truck'] . "</td>";
		echo "<td> " . $pt['specs'] ." / ". $pt['dbh']. "</td>";
		echo "<td> " . $pt['whole_sale'] . "</td>";
		echo "<td> " . $pt['list_price'] . "</td>";
		echo "</tr>"; 
	}?>
</table>
</div>