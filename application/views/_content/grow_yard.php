<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>


<script type="text/javascript"> 
$(document).ready(function() {

	// validate signup form on keyup and submit
		$("#new_grow_yard").validate({
			rules: {
				grow_yard_name: "required",
				short_name: "required",
				address: "required",
				city: "required",
				state: "required",
				zip: "required",
				lat: "required",
				lon: "required"
			}
		});
});
</script>
<style>
label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
	color:#990000;
	font-weight:bold;
}
span.req {
	color:#990000;
}
</style>
<h2> Grow Yards</h2>
<div style="margin-left:20px;">
	<table>
		<tr>
			<td>Grow Yard Name</td>
			<td>Short Name</td>
			<td>Address</td>
			<td>Lat / Lon</td>
		</tr>
	<?php foreach($grow_yards as $g){ ?>
		<tr>
			<td><?php echo $g['grow_yard_name'];?></td>
			<td><?php echo $g['short_name']?></td>
			<td><?php echo $g['address'] . "<br />" . $g['city'] . ", " . $g['state'] . " " . $g['zip']; ?></td>
			<td><?php echo $g['lat'] . " / " . $g['lon']; ?></td>
		</tr>
	<?php } ?>
	</table>
</div>
<?php 
$data = array(
       'id' => 'new_grow_yard'
);
echo  form_open('vecchio_admin/capture_add_grow_yard', $data);

?>
<h2>Add New Grow Yard</h2>
<div>
	<label>Grow Yard Name (IE Fontana Ranch)</label>
	<?php
	$data = array(
		'name' => 'grow_yard_name',
		'value' => '',
		'id' => 'grow_yard_name',
		'style' => 'width:200px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Short Name (IE 122)</label>
	<?php
	$data = array(
		'name' => 'short_name',
		'value' => '',
		'id' => 'short_name',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Address</label>
	<?php
	$data = array(
		'name' => 'address',
		'value' => '',
		'id' => 'address',
		'style' => 'width:300px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>City</label>
	<?php
	$data = array(
		'name' => 'city',
		'value' => '',
		'id' => 'city',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>State</label>
	<?php
	$data = array(
		'name' => 'state',
		'value' => '',
		'id' => 'state',
		'style' => 'width:40px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Zip</label>
	<?php
	$data = array(
		'name' => 'zip',
		'value' => '',
		'id' => 'zip',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<p>You can find the lat / lon  <a href="http://itouchmap.com/latlong.html">here</a>
<div>
	<label>Latitude</label>
	<?php
	$data = array(
		'name' => 'lat',
		'value' => '',
		'id' => 'lat',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Longitude</label>
	<?php
	$data = array(
		'name' => 'lon',
		'value' => '',
		'id' => 'lon',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<input type="submit" value='Add Grow Yard' />
</form>