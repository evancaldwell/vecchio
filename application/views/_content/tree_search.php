<div id="advanced_search" style="margin-left:20px;">
	
	<h2 >Advanced Search Online Products</h2>

	<?php echo form_open('vecchio_admin_products/product_search'); ?>
	Search By Tree Name:</td>
<input type="text" style="width:275px; padding:3px;" name="search_txt">
	(ie: Manzanillo)
	<input type="submit" value="Search">
	</form>
	<?php echo form_open('vecchio_admin_products/search_by_h_w'); ?>
	Search By Size:
    
			  Width # <input type="text" style="width:75px; padding:3px;" name="width">
			 Height # <input type="text"  style="width:75px; padding:3px;" name="height">
	<input type="submit" value="Search"> 
	</form>
	<?php echo form_open('vecchio_admin_products/search_by_row_tree'); ?>
	Search By Tree Location:
		<select name="grow_yard_id" style="display:inline;">
			<?php foreach($grow_yards as $g){
				echo "<option value='" . $g['short_name'] . "'>".$g['grow_yard_name'] . "</option>";
			}?>
		</select>
	 Row # <input type="text" style="width:75px; padding:3px;" name="row_num">
	 Tree # <input type="text"  style="width:75px; padding:3px;" name="tree_num">
	 <input type="submit" value="Search"> 
	</form>
<hr />
</div><!-- -->
<div style="margin-left:20px;">
	<?php
	foreach($avail as $a) {
	?>
<div style="width:185px; float:left; margin-right:5px; margin-bottom:5px; border: 2px solid #907D55; background-color:#907D55; color:white;">		
	<?php
	 echo anchor('vecchio_admin_products/product_by_type/' . $a['product_type_id'], img('_images/_products/' . $a['product_code'] . ".jpg")); ?>
	 <?php echo "<br /> # Available: " . $a['cnt']; ?>
</div>		
	<?php
	 } ?>
<div style="clear:both;"></div>
</div>