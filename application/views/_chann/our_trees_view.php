<!-- jQuery handles to place the header background images --> 
<?php include './application/config/dbCN.php';?>

<?php

$mysqli = new mysqli($hn, $un, $up, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

?>

	<div id='product_catalog_v_t'>
	
		<table>
			<tr class='product_catalog_v_t_header'>
				<th>Botanical Name</th>
				<th>24" Box</th>
				<th>36" Box</th>
				<th>48" Box</th>
				<th>60" Box</th>
				<th>72" Box</th>
				<th>84" Box</th>
				<th>96" Box</th>
				<th>108" Box</th>
				<th>120" Box</th>
				<th>132" Box</th>
				<th>B & B</th>
			</tr>
			
			<?php
			
			$res = $mysqli->query("SELECT * FROM product_catalog");
			$resTwo = $mysqli->query("SELECT * FROM product_catalog_two");
			
			while ($row = $res->fetch_assoc()) {
			
    			echo "<tr>";
				echo "<td class='product_catalog_v_t_dynamic_name'><a href='" . base_url() . "index.php/dir/our_trees_dynamic/?bName=" . $row['botanical_name'] . "'>" . $row['botanical_name'] . "</a></td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['24_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['36_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['48_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['60_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['72_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['84_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['96_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['108_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['120_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['132_in_box'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($row['b_n_b'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "</tr>";
    
			}
			
			?>
			
			
			
		</table>
		<br><br>
		<table>
			<tr class='product_catalog_v_t_header'>
				<th>Botanical Name</th>
				<th>10' BTH</th>
				<th>12' BTH</th>
				<th>14' BTH</th>
				<th>16' BTH</th>
				<th>18' BTH</th>
				<th>20' BTH</th>
				<th>25' BTH</th>
				<th>30' BTH</th>
			</tr>
			
			<?php
			
			while ($rowt = $resTwo->fetch_assoc()) {
			
    			echo "<tr>";
    			echo "<td class='product_catalog_v_t_dynamic_name'><a href='<?php echo base_url();?>index.php/dir/our_trees_dynamic/?bName=" . $rowt['botanical_name'] . "'>" . $rowt['botanical_name'] . "</a></td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($rowt['10_BTH'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($rowt['12_BTH'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($rowt['14_BTH'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($rowt['16_BTH'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($rowt['18_BTH'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($rowt['20_BTH'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($rowt['25_BTH'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "<td class='product_catalog_v_t_checkmark'>" . ($rowt['30_BTH'] == 1 ? '&#x2713;' : '') . "</td>";
				echo "</tr>";
				
				}
			?>
			
		</table>
	
	</div>

