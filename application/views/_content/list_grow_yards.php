	<h3>Please Choose A Grow Yard to View Products</h3>
	<div style="margin:25px">
	<?php foreach($grow_yards as $gw){
	echo anchor('vecchio_admin_products/product_by_grow_yard/' . $gw['id'], $gw['grow_yard_name']) . "<br /><br />";
	}?>
   	</div>


