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
	<h4 style="margin:3px 0px 5px 3px; text-align:left; font-size:13px; color:#333;">
	Welcome, <?php echo $this->session->userdata('fname');?> To Vecchio Trees</h4>		
    <div id="avail_productss" style="width:988px; overflow:auto; height:325px; background-color:white; border:1px solid #CCCCCC; padding:5px;">
		<?php
		foreach($avail as $a) {
		?>
	<div style="width:185px; height:312px; float:left; margin-right:5px; margin-bottom:5px; border: 2px solid #907D55; background-color:#907D55;">		
		<?php echo anchor('dir/sales/' . $a['product_type_id'], img('_images/_products/' . $a['product_code'] . ".jpg")); ?>
		<div id="info_page" style="width:auto; font-family: 'Trajan Pro'; font-size:11px; margin-top:5px; margin-bottom:5px;  color:white; clear:both; text-align:center;">
			<h4 class="prod_pf_txt" style="font-size:10px;"><?php echo 'PC-'. $a['product_code'] . " " . $a['description']; ?></h4>
		<a class="iframes" href="<?php echo base_url()."index.php/dir/info_page/".$a['product_type_id'] ?>">Zones, Watering &amp; Info &raquo; </a>
		</div>
	</div>		
		<?php } ?>
	<div style="clear:both;"></div>
	</div>