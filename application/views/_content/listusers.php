<style>
.small_text{font-size:9px; letter-spacing:0px;}
</style>
<h2>Contractors</h2>
<?php
//'admin', 'landscaper', 'architect', 'homeowner', 'contractor', 'rep'
$count = count($users['contractor']);
if($count == 0){
	echo "<h5>No Contractors In System</h5>";
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
		echo "<td>" . $users['contractor'][$i]['lname'] . ", " . $users['contractor'][$i]['fname'] . "</td>";
		echo "<td>" . $users['contractor'][$i]['phone']  . "</td>";
		echo "<td>" . $users['contractor'][$i]['usern_email']  . "</td>";
		echo "<td>" . $users['contractor'][$i]['fax']  . "</td>";
	    echo "<td>" . $users['contractor'][$i]['company_name']  . "</td>";
		echo "<td>" . $users['contractor'][$i]['bill_address']  . "<br />" .$users['contractor'][$i]['bill_city'].", " . $users['contractor'][$i]['bill_state']  . " " . $users['contractor'][$i]['bill_zip'] . "</td>";
		echo "<td>" . $users['contractor'][$i]['multiplier']  . "</td>";
		echo "<td>" . date("F j, Y", strtotime($users['contractor'][$i]['dt_signup'] )) . "</td>";
		echo "<td>" . $users['contractor'][$i]['license_number']  . "</td>";
		if($this->session->userdata('user_type') != 'rep'){
			$rep_id = 	$users['contractor'][$i]['rep_id'];
			if($rep_id == null){
				$rep_id = 0;
			}
		echo "<td>" .  $lreps[$rep_id] . "</td>";
		echo "<td>" .  anchor('vecchio_admin/edit_user/'.  $users['contractor'][$i]['id'], "Edit") . "</td>";
		} else {
		echo "<td>" .  anchor('vecchio_rep/edit_user/'.  $users['contractor'][$i]['id'], "Edit") . "</td>";	
		}
		echo "</tr>";
	}
	?>
		
		</table>
	<?php
}
?>
<h2>Landscapers</h2>
<?php
//'admin', 'landscaper', 'architect', 'homeowner', 'contractor', 'rep'
$countl = count($users['landscaper']);
if($countl == 0){
	echo "<h5>No Landscapers In System</h5>";
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
	for($i=0; $i<$countl; $i++){
		echo "<tr>";
		echo "<td>" . $users['landscaper'][$i]['lname'] . ", " . $users['landscaper'][$i]['fname'] . "</td>";
		echo "<td>" . $users['landscaper'][$i]['phone']  . "</td>";
		echo "<td>" . $users['landscaper'][$i]['usern_email']  . "</td>";
		echo "<td>" . $users['landscaper'][$i]['fax']  . "</td>";
	    echo "<td>" . $users['landscaper'][$i]['company_name']  . "</td>";
		echo "<td>" . $users['landscaper'][$i]['bill_address']  . "<br />" .$users['landscaper'][$i]['bill_city'].", " . $users['landscaper'][$i]['bill_state']  . " " . $users['landscaper'][$i]['bill_zip'] . "</td>";
		echo "<td>" . $users['landscaper'][$i]['multiplier']  . "</td>";
		echo "<td>" . date("F j, Y", strtotime($users['landscaper'][$i]['dt_signup'] )) . "</td>";
		echo "<td>" . $users['landscaper'][$i]['license_number']  . "</td>";
		if($this->session->userdata('user_type') != 'rep'){
				$rep_id = 	$users['landscaper'][$i]['rep_id'];
				if($rep_id == null){
					$rep_id = 0;
				}
			echo "<td>" .  $lreps[$rep_id] . "</td>";
		echo "<td>" .  anchor('vecchio_admin/edit_user/'.  $users['landscaper'][$i]['id'], "Edit") . "</td>";
		} else {
		echo "<td>" .  anchor('vecchio_rep/edit_user/'.  $users['landscaper'][$i]['id'], "Edit") . "</td>";	
		}
		echo "</tr>";
	}
	?>
		</table>
	<?php
}
?>
<h2>Architects</h2>
<?php
//'admin', 'landscaper', 'architect', 'homeowner', 'contractor', 'rep'
$counta = count($users['architect']);
if($counta == 0){
	echo "<h5>No Architects In System</h5>";
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
	for($i=0; $i<$counta; $i++){
		echo "<tr>";
		echo "<td>" . $users['architect'][$i]['lname'] . ", " . $users['architect'][$i]['fname'] . "</td>";
		echo "<td>" . $users['architect'][$i]['phone']  . "</td>";
		echo "<td>" . $users['architect'][$i]['usern_email']  . "</td>";
		echo "<td>" . $users['architect'][$i]['fax']  . "</td>";
	    echo "<td>" . $users['architect'][$i]['company_name']  . "</td>";
		echo "<td>" . $users['architect'][$i]['bill_address']  . "<br />" .$users['architect'][$i]['bill_city'].", " . $users['architect'][$i]['bill_state']  . " " . $users['architect'][$i]['bill_zip'] . "</td>";
		echo "<td>" . $users['architect'][$i]['multiplier']  . "</td>";
		echo "<td>" . date("F j, Y", strtotime($users['architect'][$i]['dt_signup'] )) . "</td>";
		echo "<td>" . $users['architect'][$i]['license_number']  . "</td>";
		if($this->session->userdata('user_type') != 'rep'){
				$rep_id = 	$users['architect'][$i]['rep_id'];
				if($rep_id == null){
					$rep_id = 0;
				}
			echo "<td>" .  $lreps[$rep_id] . "</td>";
		echo "<td>" .  anchor('vecchio_admin/edit_user/'.  $users['architect'][$i]['id'], "Edit") . "</td>";
		} else {
		echo "<td>" .  anchor('vecchio_rep/edit_user/'.  $users['architect'][$i]['id'], "Edit") . "</td>";	
		}
		echo "</tr>";
	}
	?>
		</table>
	<?php
}
?>
<h2>Homeowner</h2>
<?php
//'admin', 'landscaper', 'architect', 'homeowner', 'contractor', 'rep'
$counth = count($users['homeowner']);
if($counth == 0){
	echo "<h5>No Homeowners In System</h5>";
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
	for($i=0; $i<$counth; $i++){
		echo "<tr>";
		echo "<td>" . $users['homeowner'][$i]['lname'] . ", " . $users['homeowner'][$i]['fname'] . "</td>";
		echo "<td>" . $users['homeowner'][$i]['phone']  . "</td>";
		echo "<td>" . $users['homeowner'][$i]['usern_email']  . "</td>";
		echo "<td>" . $users['homeowner'][$i]['fax']  . "</td>";
	    echo "<td>" . $users['homeowner'][$i]['company_name']  . "</td>";
		echo "<td>" . $users['homeowner'][$i]['bill_address']  . "<br />" .$users['homeowner'][$i]['bill_city'].", " . $users['homeowner'][$i]['bill_state']  . " " . $users['homeowner'][$i]['bill_zip'] . "</td>";
		echo "<td>" . $users['homeowner'][$i]['multiplier']  . "</td>";
		echo "<td>" . date("F j, Y", strtotime($users['homeowner'][$i]['dt_signup'] )) . "</td>";
		echo "<td>" . $users['homeowner'][$i]['license_number']  . "</td>";
		if($this->session->userdata('user_type') != 'rep'){
				$rep_id = 	$users['homeowner'][$i]['rep_id'];
				if($rep_id == null){
					$rep_id = 0;
				}
			echo "<td>" .  $lreps[$rep_id] . "</td>";
		echo "<td>" .  anchor('vecchio_admin/edit_user/'.  $users['homeowner'][$i]['id'], "Edit") . "</td>";
		} else {
		echo "<td>" .  anchor('vecchio_rep/edit_user/'.  $users['homeowner'][$i]['id'], "Edit") . "</td>";	
		}
		echo "</tr>";
	}
	?>
		</table>
	<?php
}

if($this->session->userdata('user_type') != 'rep'){
?>
<h2>Reps</h2>
<?php
//'admin', 'landscaper', 'architect', 'homeowner', 'contractor', 'rep'
$countr = count($users['rep']);
if($countr == 0){
	echo "<h5>No Reps In System</h5>";
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
				<td width="26">Edit</td>
		</tr>
	<?php
	for($i=0; $i<$countr; $i++){
		echo "<tr>";
		echo "<td>" . $users['rep'][$i]['lname'] . ", " . $users['rep'][$i]['fname'] . "</td>";
		echo "<td>" . $users['rep'][$i]['phone']  . "</td>";
		echo "<td>" . $users['rep'][$i]['usern_email']  . "</td>";
		echo "<td>" . $users['rep'][$i]['fax']  . "</td>";
	    echo "<td>" . $users['rep'][$i]['company_name']  . "</td>";
		echo "<td>" . $users['rep'][$i]['bill_address']  . "<br />" .$users['rep'][$i]['bill_city'].", " . $users['rep'][$i]['bill_state']  . " " . $users['rep'][$i]['bill_zip'] . "</td>";
		echo "<td>" . $users['rep'][$i]['multiplier']  . "</td>";
		echo "<td>" . date("F j, Y", strtotime($users['rep'][$i]['dt_signup'] )) . "</td>";
		echo "<td>" . $users['rep'][$i]['license_number']  . "</td>";
		echo "<td>" .  anchor('vecchio_admin/edit_user/'.  $users['rep'][$i]['id'], "Edit") . "</td>";
		echo "</tr>";
	}
	?>
		</table>
	<?php
}
?>
<h2>Admin Users</h2>
<?php
//'admin', 'landscaper', 'architect', 'homeowner', 'contractor', 'rep'
$counta = count($users['admin']);
if($counta == 0){
	echo "<h5>No Admin In System</h5>";
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
				<td width="26">Edit</td>
		</tr>
	<?php
	for($i=0; $i<$counta; $i++){
		echo "<tr>";
		echo "<td>" . $users['admin'][$i]['lname'] . ", " . $users['admin'][$i]['fname'] . "</td>";
		echo "<td>" . $users['admin'][$i]['phone']  . "</td>";
		echo "<td>" . $users['admin'][$i]['usern_email']  . "</td>";
		echo "<td>" . $users['admin'][$i]['fax']  . "</td>";
	    echo "<td>" . $users['admin'][$i]['company_name']  . "</td>";
		echo "<td>" . $users['admin'][$i]['bill_address']  . "<br />" .$users['admin'][$i]['bill_city'].", " . $users['admin'][$i]['bill_state']  . " " . $users['admin'][$i]['bill_zip'] . "</td>";
		echo "<td>" . $users['admin'][$i]['multiplier']  . "</td>";
		echo "<td>" . date("F j, Y", strtotime($users['admin'][$i]['dt_signup'] )) . "</td>";
		echo "<td>" . $users['admin'][$i]['license_number']  . "</td>";
		echo "<td>" .  anchor('vecchio_admin/edit_user/'.  $users['admin'][$i]['id'], "Edit") . "</td>";
		echo "</tr>";
	}
	?>
		</table>
	<?php
}
}// end if rep
?>