	<h3>Product View : <?php echo $breadcrumb; ?></h3>
		<hr />

	<?php foreach($products as $pr){ ?>
     <div class="prod">
    	<h4 ><a class="iframes" href="<?php echo base_url() . "index.php/image_bank/byproduct/" . $pr['id'] ?> "><?php echo $pr['serial_no']. "- LP $" . number_format($pr['list_price'], 2, '.', ',');?></a></h4>
	   <div class="prod_images"> 
		
		<?php 
	
		  foreach($pr['files'] as $img){
			   if(isset($img['id'])){
		 	    echo  img('_fdr/thumbs/' . $img['icon_file_name']); ?>
					<?php echo form_open('vecchio_admin_products/delete_file');?>
				<input type="hidden" name="delete_id" value="<?php echo $img['id']; ?>" />
				<input type="hidden" name="delete_name_icon" value="<?php echo $img['icon_file_name']; ?>" />
				<input type="hidden" name="delete_name" value="<?php echo $img['file_name']; ?>">
				<input type="hidden" name="current_url" value="<?php echo uri_string(); ?>" />
	        	<input type="submit" value='DEL Photo' />         
				</form>  
		    
		 <?php } else { echo "No Photos of Product";} 
			}
		?>
		</div><!-- end prod_images -->
		<?php if($pr['status'] == 0 || $pr['status'] == 1){?>
		<div class="prod_image_upload">
		<?php echo form_open_multipart('vecchio_admin_products/add_another_img');?>
			<div>
			<label>Add An Image:</label>
			<?php echo form_upload('userfile','')?>

			</div>
			<div>
			<?php echo form_submit('submit', 'Upload Image');?>
			</div>
		<input type="hidden" name="product_id" value="<?php echo $pr['id']; ?>" />
		<?php echo form_close();?>
	    </div>
	    <?php } // End if order is available ?>

		<div class="prod_menu">

		    
			    <h4>
			    	<?php
			    	switch($pr['status']){
				    case 0:
				    echo "<span style='color:green'>AVAILABLE</span>";
				    break;
				    case 1:
				   	echo "<span style='color:orange'>TAGGED </span><br />";
				    echo "Ordered: " . date("F j, Y g:i a", strtotime($pr['order_date'])) . "<br />";  
				    echo "Expires: " . date("F j, Y g:i a", strtotime($pr['expire_date'])) . "<br />"; 
				    echo " By: " . $pr['fname'] . " " . $pr['lname'] . "<br />" . $pr['company_name'];
				   
				    break;
				    case 2:
				    echo "<span style='color:red'>SOLD</span><br />";
				    echo  date("F j, Y g:i a", strtotime($pr['payment_date']));
			    	echo " To: " . $pr['fname'] . " " . $pr['lname'] . "<br />" . $pr['company_name']; 
				    break;
				    case 3:
				    echo "<span style='color:purple'>SHIPPED </span><br />";
				    echo date("F j, Y g:i a", strtotime($pr['shipped_date'])); 
				    echo " To: " . $pr['fname'] . " " . $pr['lname'] . "<br />" . $pr['company_name'];				
				    break;
					}
			    	?>
			    </h4>
			<!--- set status of products -->
			<?php if($pr['status'] == 0 && !empty($aval_tagged)){ ?>
			<?php echo form_open('vecchio_admin/add_to_order');?>
			<input type="hidden" name="product_id" value="<?php echo $pr['id']; ?>" />
				<label>Add to open order (Need customer permission)</label>
				
				<?php
			//	echo "<pre>";
			//	print_r($aval_tagged);
					$prod_options = array();
					$prod_options['no_selected'] = "Select Open Order";
					if(isset($aval_tagged)){ 
						foreach($aval_tagged as $row){
						$price_for_customer = "$" . number_format(($row['multiplier'] * $pr['list_price']), 2, '.', ',');
							
						$prod_options[$row['id']] = $row['id']. '-'. $row['order_name'] . " " . $row['company_name'] . "-" . $row['lname'] . " P: " . $price_for_customer;
							}
					} else { 
						$prod_options[''] = 'No Open Orders - Check Expired Orders';  
					}
					$endid = 'id = "ComboBox_orders"';
					echo form_dropdown('order_id', $prod_options, '', $endid);
				?>
		
			
				<?php echo form_submit('submit', 'Add Product To Order');?>
				<input type="hidden" name="product_id" value="<?php echo $pr['id']; ?>" />
				<input type="hidden" name="prod_name" value="<?php echo $pr['serial_no']. "<br /> List Price: $" . number_format($pr['list_price'], 2, '.', ','); ?>" />
		    	<?php echo form_close();?>
				<br />
				<hr />

             <?php } ?>
			<?php if($pr['status'] == 0){?>
				<?php echo form_open('vecchio_admin/delete_product');?>
				<?php echo form_submit('submit', 'Delete Product');?>
				<input type="hidden" name="product_id" value="<?php echo $pr['id']; ?>" />
				<input type="hidden" name="serial_no" value="<?php echo $pr['serial_no']; ?>" />
		    	<?php echo form_close();?>
			<?php }?>
		</div><!-- end prod_menu -->
	  <div class="clearup"></div>
	 </div> <!-- end prod div -->
	<hr />
	<?php }?>
   	


