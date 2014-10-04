
<h2>Update <?php echo $pt[0]['product_code'] . " - " . $pt[0]['description']; ?>  </h2>
<h4><?php echo anchor('vecchio_admin/get_all_product_type', '&laquo; Back to List')?><h4>
<?php 
$data = array(
       'id' => 'new_user'
);
echo  form_open_multipart('vecchio_admin/update_product_type_capture', $data);

?>
<input type="hidden" name="id" value="<?php echo $pt[0]['id']; ?>" />
<div>
	<label>Product Code</label>
	<?php
	$data = array(
		'name' => 'product_code',
		'value' => $pt[0]['product_code'],
		'id' => 'product_code',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Whole Sale (Do not enter $)</label>
	<?php
	$data = array(
		'name' => 'whole_sale',
		'value' => $pt[0]['whole_sale'],
		'id' => 'whole_sale',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>List Price (Do not enter $)</label>
	<?php
	$data = array(
		'name' => 'list_price',
		'value' => $pt[0]['list_price'],
		'id' => 'list_price',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Box Size</label>
	<?php
	$data = array(
		'name' => 'box_size',
		'value' => $pt[0]['box_size'],
		'id' => 'box_size',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Grow Yard</label>
	<?php
	$data = array(
		'name' => 'grow_yard',
		'value' => $pt[0]['grow_yard'],
		'id' => 'grow_yard',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Description</label>
	<?php
	$data = array(
		'name' => 'description',
		'value' => $pt[0]['description'],
		'id' => 'description',
		'style' => 'width:300px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Zone</label>
	<?php
	$data = array(
		'name' => 'zone',
		'value' => $pt[0]['zone'],
		'id' => 'zone',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Watering</label>
	<?php
	$data = array(
		'name' => 'watering',
		'value' => $pt[0]['watering'],
		'id' => 'watering',
		'style' => 'width:300px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Exposure</label>
	<?php
	$data = array(
		'name' => 'exposure',
		'value' => $pt[0]['exposure'],
		'id' => 'exposure',
		'style' => 'width:300px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Product Description</label>
	<?php
	$data = array(
		'name' => 'text_description',
		'value' => $pt[0]['text_description'],
		'id' => 'text_description'
	);
	echo form_textarea($data);
	?>
</div>
<div>
	<label>Size</label>
	<?php
	$data = array(
		'name' => 'size',
		'value' => $pt[0]['size'],
		'id' => 'size',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Root Ball</label>
	<?php
	$data = array(
		'name' => 'root_ball',
		'value' => $pt[0]['root_ball'],
		'id' => 'root_ball',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Weight</label>
	<?php
	$data = array(
		'name' => 'weight',
		'value' => $pt[0]['weight'],
		'id' => 'weight',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Trees To Truck</label>
	<?php
	$data = array(
		'name' => 'trees_to_truck',
		'value' => $pt[0]['trees_to_truck'],
		'id' => 'trees_to_truck',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Specs</label>
	<?php
	$data = array(
		'name' => 'specs',
		'value' => $pt[0]['specs'],
		'id' => 'specs',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>DBH</label>
	<?php
	$data = array(
		'name' => 'dbh',
		'value' => $pt[0]['dbh'],
		'id' => 'dbh',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<br />
<br />
<input type="submit" value="Update" />
<h2>Update Project Image</h2>
<p>
	Current Product Image File: <br />
	<?php $rand = rand(1,100);?>
	<img src="<?php echo base_url() . '_images/_products/' . $pt[0]['product_code'] . '.jpg?rnd='.$rand;;?>" />
	<br />
	Image Dimensions: Width: 185px , Height: 259px <br />
	Image Type: .jpg
	
</p>
<br />
<div>
	<label>Upload New Product Image: Must be 185px X 259px and .jpg</label>
	<?php
	$data = array(
		'name' => 'userfile',
		'id' => 'userfile'
	);
	echo form_upload($data);
	?>
</div>
<br />
<br />
<input type="submit" value="Update" />
</form>
														