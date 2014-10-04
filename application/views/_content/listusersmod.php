<h2><?php
if($this->session->userdata('user_type') != 'rep'){
 echo anchor('vecchio_admin/userportal/', "&laquo; Back");
} else {
  echo anchor('vecchio_rep/userportal/', "&laquo; Back");
} ?> - <?php echo ucfirst($type) ?>s</h2>
<?php
//'admin', 'landscaper', 'architect', 'homeowner', 'contractor', 'rep'
$count = count($users[$type]);
if($count == 0){
	echo "<h5>No ".ucfirst($type)."s In System</h5>";
} else {
	 ?>
	<table width="850" class="small_text">
		<tr class="top_order">
			<td width="80">Last, First</td> 
				<td width="80">Phone</td> 
				<td width="124">Email</td> 
				<td width="76">Fax</td> 
				<td width="99">Company</td> 
	            <td width="87">Address</td> 
				<td width="55">Multiplier</td> 
				<td width="83">Date Since</td> 
				<td width="96">License #</td> 
				<?php if($this->session->userdata('user_type') != 'rep'){ ?>
				<td>Rep</td>
				<?php } ?>
				<td width="26">Edit</td>
		</tr>
	
	<?php
	for($i=0; $i<$count; $i++){
		echo "<tr>";
		echo "<td>" . $users[$type][$i]['lname'] . ", " . $users[$type][$i]['fname'] . "</td>";
		echo "<td>" . $users[$type][$i]['phone']  . "</td>";
		echo "<td>" . $users[$type][$i]['usern_email']  . "</td>";
		echo "<td>" . $users[$type][$i]['fax']  . "</td>";
	    echo "<td>" . $users[$type][$i]['company_name']  . "</td>";
		echo "<td>" . $users[$type][$i]['bill_address']  . "<br />" .$users[$type][$i]['bill_city'].", " . $users[$type][$i]['bill_state']  . " " . $users[$type][$i]['bill_zip'] . "</td>";
		echo "<td>" . $users[$type][$i]['multiplier']  . "</td>";
		echo "<td>" . date("F j, Y", strtotime($users[$type][$i]['dt_signup'] )) . "</td>";
		echo "<td>" . $users[$type][$i]['license_number']  . "</td>";
		if($this->session->userdata('user_type') != 'rep'){
			$rep_id = 	$users[$type][$i]['rep_id'];
			if($rep_id == null){
				$rep_id = 0;
			}
		echo "<td>" .  $lreps[$rep_id] . "</td>";
		echo "<td>" .  anchor('vecchio_admin/edit_user/'.  $users[$type][$i]['id'], "Edit") . "</td>";
		} else {
		echo "<td>" .  anchor('vecchio_rep/edit_user/'.  $users[$type][$i]['id'], "Edit") . "</td>";	
		}
		echo "</tr>";
	}
	?>
		
		</table>
	<?php
}
?>