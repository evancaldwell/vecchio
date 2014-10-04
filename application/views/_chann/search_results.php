<link rel="stylesheet" href="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<!-- fancybox for image preview -->
<script type="text/javascript" src="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" id="sourcecode"> 
	$(function()
	{
		$('#search_results').jScrollPane();
	});
</script>
<!-- jQuery handles to place the header background images --> 
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
    }); 



</script>

<div id="headerimgs_ns"> 
	<div id="news_window">
		<div id="news_box">
			<br />
			<h2>Search Results for: <?php echo $inp; ?></h2>
			<br />
			<div id="search_results"><?php
			if($show_products){
					echo "<table width='580' border='0' cellpadding='5' cellspacing='0' >";
					$count = count($newlist);
					$links = '';
					if($count != 0){
						for($i=0;$i<$count;$i++){
							$link_base = "<a class=\"iframes\" href=\"". base_url() . "index.php/image_bank/byproduct/".$newlist[$i]['id']." \">";
							$link_end = "</a>";
							$links .= "<tr><td width='80' >" . $link_base . img('_fdr/thumbs/' .$newlist[$i]['icon_file_name']) . $link_end . "</td>";
							$links .= "<td><b> " . $newlist[$i]['description'] . "</b> <br /> ";
							$links .= $newlist[$i]['specs'] . " <br /> " ; 
							$links .= $link_base . $newlist[$i]['serial_no'] . $link_end . "<br /><br />";
							$links .= "<span style='font-size:12px;'>".anchor('dir/sales/' . $newlist[$i]['pr_id'], 'View Complete Inventory &raquo;')  . "</span><br />";
							$links .= "</td></tr>";
						}
					} else {
						echo "<tr><td>No Results Found<br/><br />";
						echo "<a href=\"http://www.vecchiotrees.com/index.php/dir/vecchio_sales\">Contact Vecchio Sales</a><br /></td></tr>";
					}	
					echo $links;
					echo "</table>";
			} else {
				echo $newlist;	
			}
			 
			
			
			?></div>
		</div>
	</div>
</div>
