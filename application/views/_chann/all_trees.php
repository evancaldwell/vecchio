<html>
<head>
	<style>
	body {
		background-image:url('<?php echo base_url();?>_images/all_trees.jpg');
		font-family: "Gill Sans", Georgia, "Times New Roman", serif;
		font-size: 13px;
		background-color: #fff9f3;
		color: #6c6458;
	}
	h1, h2, h3, h4, ul {
		margin:0px; padding:0px;
		font-family: "Trajan Pro", Georgia, "Times New Roman", serif;
	}
	
	#advanced_search {
		background-image:url('<?php echo base_url();?>_images/V_Logo_web.png');
		background-repeat:no-repeat;
		background-position:right top;
		width:770px;
	}
	.prof_pf {
		width:185px; float:left; margin-right:5px; margin-bottom:5px; border: 2px solid #907D55; background-color:#907D55;
	}

	.prod_pf_bot {
		width:165px; padding:10px; text-align:center; color:white;
	}
	.prof_pf_txt {
		font-size:10px; margin:0px; padding:0px;
	}
	</style>
<script type="text/javascript" src="<?php echo base_url();?>_js/jquery-1.6.4.min.js"></script>
<script>
 $(document).ready(function(){
$( "#adv" )
			.click(function() {
			$( "#adv_search" ).show();
});

$("#sel_prod").submit(function(){
    	product_id = $("#sel_prod_id").val();
	   	window.top.location.href = "<?php echo base_url()?>index.php/dir/sales/" + product_id; 
});
});
</script>
</head>
<body>
<div id="avail_productss" style="width:800px; overflow:auto; height:520px; padding:5px;">
	<div id="advanced_search">
		
		<h2 >Search VECCHIO Products</h2>
		<div id="reg_search">
		<br />

				<form id="sel_prod">
				Select Variety:
				<select name="porduct_type_id" id="sel_prod_id">
					<?php
				foreach($avail as $a) {
				echo '<option value="'.$a['product_type_id'].'"> PC-'.$a['product_code'] . " " . $a['description'].'</option>' . "\n";	
				}
				?>
				</select>
				<input type="submit" value="Go">
				</form>
		</div>
				<br />		
		<button id="adv"><span style="font-size:16px">Advanced Search</span></button>
				<br /><br />
		<div id="adv_search" style="display:none;">
		<br />
				<?php
				$d = array('target'=> '_parent');
				 echo form_open('dir/search_result', $d); ?>
				Search By Tree Name:
			<input type="text" style="width:275px; padding:3px;" name="search_txt">
				(ie: Manzanillo)
				<input type="submit" value="Search">
				</form>	
		<?php
		$d = array('target'=> '_parent');
		 echo form_open('dir/search_by_h_w', $d); ?>
		Search By Size:
	    
				  Width # <input type="text" style="width:75px; padding:3px;" name="width">
				 Height # <input type="text"  style="width:75px; padding:3px;" name="height">
		<input type="submit" value="Search"> 
		</form>
		<?php echo form_open('dir/search_by_row_tree', $d); ?>
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
		</div>
	<hr />
	</div><!-- -->
	<?php
	foreach($avail as $a) {
	?>
<div class="prod_pf" style="width:185px; height:312px; float:left; margin-right:5px; margin-bottom:5px; border: 2px solid #907D55; background-color:#907D55;">		
	<?php
	$data['target'] = 'parent';
	 echo anchor('dir/sales/' . $a['product_type_id'], img('_images/_products/' . $a['product_code'] . ".jpg"), $data); ?>
	<div class="prod_pf_bot" style="width:165px; padding:10px; text-align:center; color:white;">
	<h4 class="prod_pf_txt" style="font-size:10px;"><?php echo 'PC-'. $a['product_code'] . " " . $a['description']; ?></h4>
	</div>
</div>		
	<?php } ?>
<div style="clear:both;"></div>
</div>
</body>
</html>