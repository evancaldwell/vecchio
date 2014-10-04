<h3>Hello <?php echo $this->session->userdata('fname'); ?></h3>
<?php if(!empty($to_expire)){ ?>
	<p style="color:red" >You have <?php echo (count($to_expire) > 1 ? '' : 'a');?> customer<?php echo (count($to_expire) > 1 ? 's' : '')?> with an order in their cart set to expire within 1 day</p>
<?php foreach($to_expire as $t){
	?>
	<p><?php echo $t['fname'] . ' ' . $t['lname'] . " Order Name: " . anchor('vecchio_rep/my_orders',$t['order_name']) . " Expire Date / Time: " . date("l F j, Y - g:i a", strtotime($t['expire_date'])); ?>
	<br />Phone: <?php echo $t['phone']; ?> Email: <?php echo mailto($t['usern_email']); ?>	
		</p>
<?php } 
	} ?>
<br />
<br />
	
<p>	<?php echo anchor("vecchio_rep/start_new_order/", 'Start New Quote'); ?> </p>
<p>	<?php echo anchor("vecchio_rep/my_orders/", 'View Current Quotes'); ?> </p>
<p>	<?php echo anchor("vecchio_rep/quick_quote/", 'Start New Quick Quote'); ?> </p>
<p>	<?php echo anchor("vecchio_rep/my_quick_quotes/", 'View Quick Quotes'); ?> </p>
<p>	<?php echo anchor("vecchio_rep/product_status/avail", 'View Available Products');?> </p>
<p>	<?php echo anchor("vecchio_rep/userportal/", 'View My Clients');?> </p>
<p>	<?php echo anchor("vecchio_rep/add_user_form/", 'Add New Client'); ?> </p>
<br />
<br />






