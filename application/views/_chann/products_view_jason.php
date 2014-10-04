<script src="<?php echo base_url(); ?>_js/ui/jquery.ui.selectmenu.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url();?>_css/ui/jquery.ui.selectmenu.css" type="text/css" media="screen" />
<script type="text/javascript" id="sourcecode"> 
	$(function()
	{
		
		
		$('#product_window').jScrollPane({showArrows: true});
		
	
	});
</script>

<style type="text/css">
/*---product list page---added by jason---*/
#product_window {
	width:100%;
	height:455px;
	margin: 6px auto 0px auto;
	background-color:#5f472e;
	/*background-image:url(<?php echo base_url();?>_images/V_TreeTab_web.png);*/
	z-index:151;
	/*position:relative;*/
	overflow: auto;
}
.nav_bridge {}
.pp_product {width:94px; height:152px; float:left; margin-left:5px; margin-top:5px; position:relative;font-size:8px;}
.pp_bg{ width:94px; height:152px; background-color:#C4a006; filter: alpha(opacity=80); opacity: 0.8; position:absolute; top:0px;}
.pp_picbg{width:92px; height:122px; position:relative; background-color:#C2B59B; margin-left:1px;}
.pp_pic{width:84px; height:113px; background-color:#06C; position:absolute; left:4px; top:5px; overflow:hidden;}
#product_logo{
	width:297px; /*height:162px;*/ height:157px; 
	overflow:hidden; 
	background-image: url(<?php echo base_url();?>_images/V_Olives100_BrownBGLeaf.png);
	background-repeat:no-repeat;
	background-position:top left; 
	float:left;
	position:relative; 
	 }
#bubble{
	position:absolute;
	left:20px;
	top:20px;
	width:257px;
	height:117px;
	display:table;
	}
#bubble h2{
	display:table-cell;
	vertical-align:middle;
	text-align:center;
	color:#fff;
	font-size:24px;}
.center {text-align:center; color:#FFF;position:relative; line-height:14px;}
.tag_status {
	width:74px;
	height:103px;
	background-image:url(<?php echo base_url();?>_images/tagged.png);
	position:absolute;
	top:9px;
	left:9px;
	}
</style>




<!-- jquery to load fancybox with an "iframe" -->
<script type="text/javascript"> 

    $(document).ready(function(){
		$(".iframes").fancybox({
			'width' : 1000,
			'height' : 600,
			'autoScale' : false,
			'transitionIn'	 : 'none',
			'transitionOut'	 : 'none',
			'type'	 : 'iframe'});	
			
			$(".all_trees").fancybox({
				'width' : 830,
				'height' : 550,
				'autoScale' : false,
				'transitionIn'	 : 'none',
				'transitionOut'	 : 'none',
				'type'	 : 'iframe'});
				
			$( ".all_trees" ).button();

    }); 



</script>

<!-- jQuery handles to place the header background images --> 
<div id="headerimgs_ns"> 

	<div id="product_window">
		<!--<div id="spec_txt" >-->
        <?php if (is_array($products)){ ?>
			<div id="product_logo" ><div id="bubble"><h2>
			<?php
			if($type == 'gy'){
				echo $gy_name . "<br />";
			} else {
			 echo $products[0]['description']; 
			} ?>
					<span style="font-size:10px;"><a class="all_trees" href="<?php echo base_url() . "index.php/dir/all_trees/"?>" id="click_other">View Other Products</a></span></h2>
		
			</div></div>
           
		   
		   <?php 
			/*
			echo "<pre>";
			print_r($products);
			echo "</pre>";
			*/
			$count = count($products);
			for($i=0; $i<$count; $i++){
				$product_id = $products[$i]['id'];
				$image_icon = base_url() . "_fdr/thumbs/". $products[$i]['files'][0]['icon_file_name'];
				$serial_no = $products[$i]['serial_no'];
				$status = $products[$i]['status'];
				if($products[$i]['status'] == "1")
					{
						$status = "<a class='iframes' href='".base_url()."index.php/image_bank/byproduct/".$product_id."'><div class='tag_status'></div></a>";
					}else{
						$status ="";
					}
				$description = $products[$i]['description'];
 			?>
            
            <div class="pp_product">
            	<div class="pp_bg"></div>
            	<div class="center"><?php echo $description; ?>
				</div>
            	<div class="pp_picbg">
                	<div class="pp_pic"><a class="iframes" href="<?php echo base_url()."index.php/image_bank/byproduct/".$product_id ?>"><img src="<?php echo $image_icon; ?>" /></a></div>
                    <?php echo $status; ?>
                </div>  
           		<div class="center">
                <!-- put this guy wherever you want to have the clicky to modal-->
				<!--<a class="iframes" href="<?php //echo base_url()."index.php/dir/product_view/".$product_id ?> ">VIEW ITEM</a>-->
                
                </div>     
			</div>
           
		   <?php } 
		   } else {?>
           	<div id="product_logo" ><div id="bubble"><h2>Sold Out <span style="font-size:10px;"><a class="all_trees" href="<?php echo base_url() . "index.php/dir/all_trees/"?>" id="click_other">View Other Products</a></span></h2></div></div>
           <?php } ?>
	</div>
</div>
