<link rel="stylesheet" href="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<!-- fancybox for image preview -->
<script type="text/javascript" src="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" id="sourcecode"> 


    $(document).ready(function(){
		$(".iframes").fancybox({
			'width' : 800,
			'height' : 500,
			'autoScale' : false,
			'transitionIn'	 : 'none',
			'transitionOut'	 : 'none',
			'type'	 : 'iframe'});	
			
	$('#tree_sp_wrap').jScrollPane({showArrows: true});		

    }); 



</script>
<style>
#info_page a{
	color:white;
}
</style>
	<h3>Product View : <?php echo $breadcrumb; ?></h3>
	<div id="advanced_search" style="margin-left:20px;">

		<h2 >Advanced Search Online Products</h2>

		<?php echo form_open('vecchio_rep/product_search'); ?>
		Search By Tree Name:</td>
	<input type="text" style="width:275px; padding:3px;" name="search_txt">
		(ie: Manzanillo)
		<input type="submit" value="Search">
		</form>
		<?php echo form_open('vecchio_rep/search_by_h_w'); ?>
		Search By Size:

				  Width # <input type="text" style="width:75px; padding:3px;" name="width">
				 Height # <input type="text"  style="width:75px; padding:3px;" name="height">
		<input type="submit" value="Search"> 
		</form>
		<?php echo form_open('vecchio_rep/search_by_row_tree'); ?>
		Search By Location:
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
		<hr />
	<?php
	if($breadcrumb == 'All Available Products'){ ?>
	    <div id="avail_productss" style="width:800px; overflow:auto; background-color:white; border:1px solid #CCCCCC; padding:5px; margin-left:20px;">
			<?php
			foreach($avail as $a) {
			?>
		<div style="width:185px; float:left; margin-right:5px; margin-bottom:5px; border: 2px solid #907D55; background-color:#907D55;">		
			<?php echo anchor('vecchio_rep/product_by_type/' . $a['product_type_id'], img('_images/_products/' . $a['product_code'] . ".jpg")); ?>
			<div id="info_page" style="width:auto; font-family: 'Trajan Pro'; font-size:11px; margin-bottom:5px;  color:white; clear:both; text-align:center;">
			<a class="iframes" href="<?php echo base_url()."index.php/dir/info_page/".$a['product_type_id'] ?>">Zones, Watering &amp; Info &raquo; </a><br />
			<?php echo $a['cnt']?> Available
		</div>
		</div>		
			<?php } ?>
		<div style="clear:both;"></div>
		</div>
		
  <?php } else {
	
	foreach($products as $pr){ ?>
     <div style="width:76px; padding:8px; float:left;">
		<a  href="<?php echo base_url() . "index.php/vecchio_rep/show_product_info/" . $pr['id'] ?> "><?php echo img('_fdr/thumbs/'. $pr['files'][0]['icon_file_name']);?></a>
	</div>
	<?php }?>
   	<div style="clear:both;"></div>
	<?php } ?>

