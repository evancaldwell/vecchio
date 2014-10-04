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
.iframes {
	color:white;
}
</style>
<!-- jQuery handles to place the header background images --> 
<div id="headerimgs_ns"> 

	<div id="tree_window">
		<div id="tree_sp_wrap">
			<table>
			<tr>
			<?php
			foreach($avail as $a) {
			?>
			<td>
		<div style="width:185px; height:322px; margin-right:5px; border: 2px solid #907D55; background-color:#907D55;">		
			<?php echo anchor('dir/sales/' . $a['product_type_id'], img('_images/_products/' . $a['product_code'] . ".jpg")); ?>
			<div style="width:auto; font-family: 'Trajan Pro'; font-size:10px; margin-bottom:5px; margin-top:5px;  color:white; clear:both; text-align:center;">
					<h4 class="prod_pf_txt" style="font-size:10px;"><?php echo 'PC-'. $a['product_code'] . " " . $a['description']; ?> <br /> <?php echo $a['specs'] . ' - ' . $a['weight'];?></h4>
			<a class="iframes" href="<?php echo base_url()."index.php/dir/info_page/".$a['product_type_id'] ?>">Zones, Watering &amp; Info &raquo; </a>
			</div>
		</div>
		</td>	
			<?php } ?>
		</tr>
		</table>
		</div>
	</div>
</div>
