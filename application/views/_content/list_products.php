	<h3>Product View : <?php echo $breadcrumb; ?></h3>
		<hr />
	<?php
	if(is_array($products)){
	 foreach($products as $pr){ ?>
	     <div style="width:76px; padding:8px; float:left;">
			<a  href="<?php echo base_url() . "index.php/vecchio_admin_products/show_ind_product/" . $pr['id'] ?> "><?php echo img('_fdr/thumbs/'. $pr['files'][0]['icon_file_name']);?></a>
			<?php if($pr['status'] == 0) { ?>
			<div style="width:76px; background:green; height:12px; font-size:10px; margin-top:-12px; z-index:100; color:white;">
				Available
			</div>
		<?php } else if($pr['status'] == 1){ ?>
			<div style="width:76px; background:yellow; height:12px; font-size:10px; margin-top:-12px; z-index:100; color:white;">
				Tagged
			</div>			
		<?php } else if($pr['status'] == 2 || $pr['status'] == 3){ ?>
				<div style="width:76px; background:red; height:12px; font-size:10px; margin-top:-12px; z-index:100; color:white;">
					Sold
				</div>			
		<?php } ?>
		</div>
	<?php }
	} else {
	  echo 'No Products in Category <br /><br />';
	} ?>
	<div style="clear:both;"></div>
