<div id="admin_menu">
	
<?php

$user_type = $this->session->userdata('user_type');
if($user_type == 'admin'){

?>
<ul class="sf-menu">
	<li>
	   <?php echo anchor('vecchio_admin/', 'Main'); ?>
		<ul>
           <?php
	         echo "<li>".anchor("vecchio_admin/site_settings/", 'Settings')."</li> \r\n"; 		
			// echo "<li>".anchor("vecchio_admin/promo/", 'Promo Codes')."</li> \r\n"; 
			 echo "<li>".anchor("vecchio_admin/event_manager/", 'Events')."</li> \r\n"; 
			echo "<li>".anchor("vecchio_admin/warranty/preview", 'Warranty')."</li> \r\n"; 	  
           ?>
		</ul>
	</li>
	<li class="current">
		<?php  echo anchor("vecchio_admin/new_estimates/", 'New Quotes');?>
		<ul>
            <?php
	         echo "<li>".anchor("vecchio_admin/start_new_order/", 'Start New')."</li> \r\n"; 
	         echo "<li>".anchor("vecchio_admin/new_estimates/", 'View All')."</li> \r\n"; 
			 echo "<li>".anchor("vecchio_admin/quick_quotes/", 'Quick Quotes')."</li> \r\n"; 
			 echo "<li>".anchor("vecchio_admin/on_account/", 'On Account')."</li> \r\n";			  
            ?>
		</ul>
	</li>
	<li>
		<?php echo anchor("vecchio_admin/inprocessing/", 'Shipping Backlog');?>
		<ul>
            <?php
			 echo "<li>".anchor("v_calendar/", 'Calendar')."</li> \r\n"; 
	         echo "<li>".anchor("vecchio_admin/inprocessing/", 'View All')."</li> \r\n"; 

                // needs for loop for each growyard, name 
				// echo "<li>".anchor("inproc/growyard/" . $growyardid , $growyardname)."</li> \r\n";  			  
            
				?>
		</ul>
	</li>
	<li>
		<?php echo anchor("vecchio_admin/shipped/", 'Shipped');?>
		<ul>
            <?php
	         echo "<li>".anchor("vecchio_admin/shipped/", 'View All')."</li> \r\n"; 

				?>
		</ul>
	</li>
	<li>
		<?php echo anchor("vecchio_admin_products/", 'Products');?>
		<ul>
            <?php
		 	 echo "<li>".anchor('vecchio_admin_products/tree_search','Search Products')."</li> \r\n"; 
			 echo "<li>".anchor('vecchio_admin/get_all_product_type','View Product Types')."</li> \r\n"; 
		   	 echo "<li>".anchor('vecchio_admin/create_product_type','Add New Product Type')."</li> \r\n"; 
			 echo "<li>".anchor('vecchio_admin/add_grow_yard','View / Add Grow Yard')."</li> \r\n"; 
	         echo "<li>".anchor("vecchio_admin_products/addnew/", 'Add New Product')."</li> \r\n"; 
             echo "<li>".anchor("vecchio_admin_products/grow_yards/", 'By Grow Yard')."</li> \r\n";
             echo "<li>".anchor("vecchio_admin_products/products/", 'Product Type')."</li> \r\n"; 
             echo "<li>".anchor("vecchio_admin_products/product_status/avail", 'Available')."</li> \r\n"; 
             echo "<li>".anchor("vecchio_admin_products/product_status/tagged", 'Tagged')."</li> \r\n";
             echo "<li>".anchor("vecchio_admin_products/product_status/sold", 'Sold')."</li> \r\n";
             echo "<li>".anchor("vecchio_admin_products/product_status/shipped", 'Shipped')."</li> \r\n";
		   ?>
		</ul>
	</li>
	<li>
		<?php echo anchor("vecchio_admin/userportal/", 'Users');?>
		<ul>
            <?php
	         echo "<li>".anchor("vecchio_admin/userportal/", 'List Users')."</li> \r\n";
	         echo "<li>".anchor("vecchio_admin/add_user_form/", 'Add New User')."</li> \r\n";
	  		 echo "<li>".anchor("vecchio_admin/on_account/", 'Users With Credit')."</li> \r\n";

		   ?>
		</ul>
	</li>  
</ul>

<?php } else { // rep dropdown menu ?>
<script>
   /* sweet little jSON auto populate
	$.getJSON('<?php echo base_url(); ?>index.php/dir/avail_menu', function(data){
	    var html = '';
	    var len = data.length;
	//	var baseurl = <?php echo base_url() . "index.php/"; ?>;
	    for (var i = 0; i< len; i++) {
		//	html += "<li><a href='blah'>BLAH</a></li>";
	       html += "<li><a href='<?php echo base_url() ?>index.php/vecchio_rep/product_by_type/" + data[i].product_type_id + "'>" + data[i].description + " Count: " + data[i].cnt + '</a></li>';
	    }
	    $('#avail_prod').append(html);
	});
	*/
</script>
	<ul class="sf-menu">
		<li>
		   <?php echo anchor('vecchio_rep/', 'Main'); ?>
		</li>
		<li>
		   <?php echo anchor('vecchio_rep/my_orders', 'Current Quotes'); ?>
			<ul>
	            <?php
		         echo "<li>".anchor("vecchio_rep/my_quick_quotes/", 'Quick Quotes')."</li> \r\n";  
		
			   ?>
			</ul>
		</li>
		<li >
 			<?php echo anchor("vecchio_rep/start_new_order/", 'New Quote'); ?>
			<ul>
			<?php echo "<li>".anchor("vecchio_rep/quick_quote/", 'New Quick Quote')."</li> \r\n"; ?>
			</ul>
		</li>
		<li>
			<?php echo anchor("vecchio_rep/product_status/avail", 'Available Products');?>
		</li>
		<li>
			<?php echo anchor("vecchio_rep/userportal/", 'My Clients');?>
			<ul>
	            <?php
		         echo "<li>".anchor("vecchio_rep/add_user_form/", 'Add New Client')."</li> \r\n";  
			   ?>
			</ul>
		</li>
	</ul><!-- end main UL rep -->
<?php } ?>	
</div>
<div id="clear" ></div>

