
<h3>Search results for: "<?php echo $inp; ?>"</h3>
<?php echo anchor('vecchio_rep/product_status/avail', 'Search Again'); ?>
<div style="margin-left:20px">
<?php
if($newlist != ''){
		echo "<table width='700' border='0' cellpadding='5' cellspacing='0' >";
		$count = count($newlist);
		$links = '';
		for($i=0;$i<$count;$i++){
		$link_base = "<a href=\"". base_url() . "index.php/vecchio_rep/show_product_info/".$newlist[$i]['id']." \">";
		$link_end = "</a>";
		$links .= "<tr><td width='80' >" . $link_base . img('_fdr/thumbs/' .$newlist[$i]['icon_file_name']) . $link_end . "</td>";
		$links .= "<td><b> " . $newlist[$i]['description'] . "</b> <br /> ";
		$links .= $newlist[$i]['specs'] . " <br /> " ; 
		$links .= $link_base . $newlist[$i]['serial_no'] . $link_end . "<br /><br />";
		$links .= "<span style='font-size:12px;'>".anchor('vecchio_rep/product_by_type/' . $newlist[$i]['pr_id'], 'View Complete Inventory &raquo;')  . "</span><br />";
		$links .= "</td></tr>";
		}
		echo $links;
		echo "</table>";
} else {
	echo "<b>Sorry, couldn't find any available products based on the following search: " . $search_term ;
}

?>
</div>