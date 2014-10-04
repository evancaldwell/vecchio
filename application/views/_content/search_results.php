
			<h2>Search Results for: <?php echo $inp; ?></h2>
			<br />
			<div id="search_res" style="margin-left:40px;"><?php
			if($show_products){
					echo "<table width='580' border='0' cellpadding='5' cellspacing='0' >";
					$count = count($newlist);
					$links = '';
					if($count != 0){
						for($i=0;$i<$count;$i++){
							$link_base = "<a  href=\"". base_url() . "index.php/vecchio_admin_products/show_ind_product/".$newlist[$i]['id']." \">";
							$link_end = "</a>";
							$links .= "<tr><td width='80' >" . $link_base . img('_fdr/thumbs/' .$newlist[$i]['icon_file_name']) . $link_end . "</td>";
							$links .= "<td><b> " . $newlist[$i]['description'] . "</b> <br /> ";
							$links .= $newlist[$i]['specs'] . " <br /> " ;
							$links .= 'PC - '. $newlist[$i]['product_code'] . "<br />";
							$links .= 'Grow Yard - ' . substr($newlist[$i]['serial_no'],0,3) . "<br />"; 
							$links .= $link_base . $newlist[$i]['serial_no'] . $link_end . "<br /><br />";
							$links .= "</td></tr>";
						}
					} else {
						echo "<tr><td>No Results Found<br/><br />";
					}	
					echo $links;
					echo "</table>";
			} 
			 
			
			
			?></div>

