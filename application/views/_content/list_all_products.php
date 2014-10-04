	<h3>Please Choose A Product Type</h3>
	<div style="margin:25px">
	<?php foreach($products as $pr){
		//id, product_code, description, specs
	$name = $pr['product_code'] . " - " . $pr['description'] . " - " . $pr['specs'] . " $" . number_format($pr['list_price'], 2, '.', ',');	
	echo anchor('vecchio_admin_products/product_by_type/' . $pr['id'], $name) . "<br /><br />";
	}?>
   	</div>


