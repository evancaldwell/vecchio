<div id="big_blue_btm"></div>
<div id="footer">
<div class="float_fix">
	<div class="main_content">
		<?php if(isset($call_to_action)){ ?>
		<a href="<?php echo base_url() . 'index.php/dir/log_in/new/'?>"><div id="call_action">&nbsp;</div></a>
		<?php } ?>
		<h4>VECCHIO TREES </h4>
		<div style="text-align:right; margin-top:-30px;">
		<?php echo anchor('https://www.apldca.org/', img('_images/apdl.png')); ?>

		<?php echo anchor('http://www.clca.org/', img('_images/clcav.png')); ?>

		<?php echo anchor('http://www.wcisa.net/', img('_images/intsocarb.png')); ?>

		<?php echo anchor('http://www.napwl.com/', img('_images/napwl.png')); ?> 
		
		<?php echo anchor('http://www.facebook.com/pages/Vecchio-Trees/260868197326161', img('_images/faceb.png')); ?> 
		<div>
        <div id="copy"> Copyright &copy; <?php $year = date("Y"); echo $year;?> VECCHIO TREES 

	 	</div>
		
		
		
    </div><!-- End .container -->
</div><!-- End .float_fix -->
</div><!-- End #footer -->
</body>

</html>
