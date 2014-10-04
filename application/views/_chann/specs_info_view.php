<!-- jQuery handles to place the header background images --> 
<div id="headerimgs_ns"> 
	<div id="spec_window">
		<div id="spec_img">
		<?php echo img('_images/' . $imgfile);?>
		</div>
		<div id="spec_txt">
		<h3><?php echo $hdfile; ?></h3>
		<p><?php echo $txtfile; ?></p>
		<p id="dwn_p" >
			<span style="font-size:10px;">
			<script>
			$(function() {
					$( ".dwn_lnk" ).button();	
				});
			</script>	
			<style>
			.dwn_lnk {margin-bottom:4px;}
			</style>
	<?php 
					$dt = array('class' => 'dwn_lnk');
				//	echo anchor('vecchio_download/specs_info', "Download Specs &amp; Info &#9660;", $dt);?>
<br />
			<?php
			//	$dt = array('class' => 'dwn_lnk');
			//	echo anchor('vecchio_download/warranty', "Download Warranty Details &#9660;", $dt);
			?>
<!-- <br /> -->
<?php
				$dt = array('class' => 'dwn_lnk');
				echo anchor('vecchio_download/planting_detail', "Download Planting Detail (PDF) &#9660;", $dt);?>
<br />
				<?php
				$dt = array('class' => 'dwn_lnk');
				echo anchor('vecchio_download/planting_detail_cad', "Download Planting Detail (CAD) &#9660;", $dt);?>
<br />
			<?php
			$dt = array('class' => 'dwn_lnk');
			echo anchor('vecchio_download/warranty', "Download Vecchio Warranty &#9660;", $dt);?>
			<br />
			<?php
			$dt = array('class' => 'dwn_lnk');
			echo anchor('vecchio_download/catalog', "Download Vecchio Catalog &#9660;", $dt);?>
			<?php
			$dt = array('class' => 'dwn_lnk');
			echo anchor('vecchio_download/availability', "Download Vecchio Product Availability &#9660;", $dt);?>



		</span>
		</p>
		</div>
	</div>
</div>