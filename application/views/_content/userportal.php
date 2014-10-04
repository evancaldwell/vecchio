<?php
if($this->session->userdata('user_type') == 'rep'){
$url = 'vecchio_rep';
} else {
$url = 'vecchio_admin';
}
?>
<h2>Search</h2>
<?php if($this->uri->segment(3) == 'added'){
	echo "<h3 style='color:green; text-align:center;'>Success! User Added</h3>";
} ?>
<?php
echo form_open($url.'/search_users');
?>
<label>Search Users</label>
<input type="input" name="search_term" id="search_term"/>
<input type="submit" value="Search" />
</form>
<h2>Filter By Type</h2>
<style>
li {padding:4px;}
</style>
<ul>
	<li><?php echo anchor($url.'/listusers/contractor/', 'Contractors'); echo " - " . $stats['contractor'];?> </li>
	<li><?php echo anchor($url.'/listusers/landscaper/', 'Landscapers'); echo " - " . $stats['landscaper'];?> </li>
	<li><?php echo anchor($url.'/listusers/homeowner/', 'Homeowners'); echo " - " . $stats['homeowner'];?> </li>
	<li><?php echo anchor($url.'/listusers/architect/', 'Architects'); echo " - " . $stats['architect'];?> </li>
	<li><?php echo anchor($url.'/listusers/nursery/', 'Nursery'); echo " - " . $stats['nursery'];?> </li>
	<li><?php echo anchor($url.'/listusers/broker/', 'Broker'); echo " - " . $stats['broker'];?> </li>
	<li><?php echo anchor($url.'/listusers/distributor/', 'Distributor'); echo " - " . $stats['distributor'];?> </li>
	<?php if($this->session->userdata('user_type') == 'admin'){ ?>
	<li><?php echo anchor($url.'/listusers/rep/', 'Reps'); echo " - " . $stats['rep'];?> </li>		
	<li><?php echo anchor($url.'/listusers/admin/', 'Admin'); echo " - " . $stats['admin'];?> </li>		
	<?php } ?>
</ul>