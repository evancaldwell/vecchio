
<h1>Vecchio Sales Management System</h1><?php if(!empty($to_expire)){ ?>
	<p style="color:red" >You have <?php echo (count($to_expire) > 1 ? '' : 'a');?> customer<?php echo (count($to_expire) > 1 ? 's' : '')?> with an order in their cart set to expire within 1 day</p>
<?php foreach($to_expire as $t){
	?>
	<p><?php echo $t['fname'] . ' ' . $t['lname'] . " Order Name: " . anchor('vecchio_admin/new_estimates',$t['order_name']) . " Expire Date / Time: " . date("l F j, Y - g:i a", strtotime($t['expire_date'])); ?>
	<br />Phone: <?php echo $t['phone']; ?> Email: <?php echo mailto($t['usern_email']); ?>	
		</p>
<?php } 
	} ?>
<br />
<br />
<h2>Orders</h2>
<div style="margin-left:20px">
<table>
<tr class="top_order">
<td><?php echo anchor("vecchio_admin/new_estimates/", 'New Quotes');?></td>
<td><?php  echo anchor("vecchio_admin/inprocessing/", 'Shipping Backlog');?></td>
<td><?php echo anchor("vecchio_admin/shipped/", 'Shipped');?></td>
<!-- 
<td><?php //echo anchor("vecchio_admin/new_estimates/", 'New Quotes');?></td>
<td><?php // echo anchor("vecchio_admin/inprocessing/", 'Shipping Backlog');?></td>
<td><?php // echo anchor("vecchio_admin/shipped/", 'Shipped');?></td>
-->
</tr>
<tr >
<td><?php  echo anchor("vecchio_admin/new_estimates/", $stats['new_orders']); ?></td>
<td><?php  echo anchor("vecchio_admin/inprocessing/",$stats['in_processing']);?></td>
<td><?php echo anchor("vecchio_admin/shipped/",$stats['shipped']);?></td>
</tr>
</table>
</div>
<h2>Quick Quotes</h2>
<div style="margin-left:20px;">
	<table>
	<tr class="top_order">
	<td><?php echo anchor("vecchio_admin/quick_quotes/","Active Quick Quotes"); ?></td>
	</tr>
	<tr >
	<td><?php echo anchor("vecchio_admin/quick_quotes/",$stats['quick_quotes']); ?></td>
	</tr>
	</table>
</div>
<h2>On Account</h2>
<div style="margin-left:20px;">
	<table>
	<tr class="top_order">
	<td><?php echo anchor("vecchio_admin/on_account/","On Account"); ?></td>
	</tr>
	<tr >
	<td><?php echo anchor("vecchio_admin/on_account/",$on_account); ?></td>
	</tr>
	</table>
</div>
<h2>Products</h2>
<div style="margin-left:20px;">
<table>
<tr class="top_order">
<td>Available Products </td>
<td>Tagged Products </td>
<td>Products In Backlog </td>
<td>Shipped Products </td>
</tr>
<tr >
<td><?php echo $stats['available_products']; ?></td>
<td><?php echo $stats['tagged_products']; ?></td>
<td><?php echo $stats['in_processing_products'];?></td>
<td><?php echo $stats['shipped_products'];?></td>
</tr>
</table>
</div>		 
<h2>Customers</h2>
<div style="margin-left:20px">
<table>
<tr class="top_order">
<td><?php echo anchor("vecchio_admin/userportal/", 'Number of Customers') ?></td>
</tr>
<tr >
<td><?php echo anchor("vecchio_admin/userportal/", $stats['customers']); ?></td>
</tr>
</table>		
</div>	