<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>


<script type="text/javascript"> 
$(document).ready(function() {

	// validate signup form on keyup and submit
		$("#new_product_type").validate({
			rules: {
				product_code: "required",
				grow_yard: "required",
				description: "required",
				userfile: "required",
				zone: "required",
				watering: "required",
				dbh: "required",
				exposure: "required",
				text_description: "required",
				size: "required",
				root_ball: "required",
				weight: "required",
				specs: "required",
				box_size: {
					required: true,
					number:true					
				},
				whole_sale: {
					required: true,
					number:true
				},
				list_price: {
					required: true,
					number:true
				},
				trees_to_truck: {
					required: true,
					number:true
				}
			},
			messages: {
				whole_sale: {
					required: "Enter Whole Sale Price ",
					number: "Omit $ (dollar sign) and decimal value"
				},
				list_price: {
					required: "Enter List Price ",
					number: "Omit $ (dollar sign) and decimal value"
				},
				box_size: {
					required: "Enter Box Size",
					number: "Must Be Number"
				},
				trees_to_truck: {
					required: "Enter How Many Trees with Fit To A Truck",
					number: "Must Be Number"
				},
				userfile: "Upload file photo for new product type"
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
<h2>Create New Product Type</h2>
<h4><?php echo anchor('vecchio_admin/get_all_product_type', '&laquo; Back to List')?><h4>
<?php 
$data = array(
       'id' => 'new_product_type'
);
echo  form_open_multipart('vecchio_admin/capture_create_product_type', $data);

?>
<div>
	<label>Product Category</label>
	<select name="product_category" >
		<?php foreach($product_category as $p){
			echo "<option value='" . $p['short_name'] . "'>".$p['category'] . "</option>";
		} ?>
	</select>
</div>
<div>
	<label>Product Code (IE 101)</label>
	<?php
	$data = array(
		'name' => 'product_code',
		'value' => '',
		'id' => 'product_code',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Whole Sale (Do not enter $, just 2000 for $2,000.00)</label>
	<?php
	$data = array(
		'name' => 'whole_sale',
		'id' => 'whole_sale',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>List Price (Do not enter $, just 1000 for $1,000.00)</label>
	<?php
	$data = array(
		'name' => 'list_price',
		'id' => 'list_price',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Box Size (IE 72)</label>
	<?php
	$data = array(
		'name' => 'box_size',
		'id' => 'box_size',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Grow Yard</label>
	<select name="grow_yard" >
		<?php foreach($grow_yards as $g){
			echo "<option value='" . $g['short_name'] . "'>".$g['grow_yard_name'] . "</option>";
		} ?>
	</select>
</div>
<div>
	<label>Description (IE '100 Year Manzanillo')</label>
	<?php
	$data = array(
		'name' => 'description',
		'id' => 'description',
		'style' => 'width:300px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Zone (IE '8,9,11-24, H-1, H-2')</label>
	<?php
	$data = array(
		'name' => 'zone',
		'id' => 'zone',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Watering (IE "Light To Medium Watering")</label>
	<?php
	$data = array(
		'name' => 'watering',
		'id' => 'watering',
		'style' => 'width:300px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Exposure (IE "Light To Medium Watering")</label>
	<?php
	$data = array(
		'name' => 'exposure',
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
		'id' => 'text_description'
	);
	echo form_textarea($data);
	?>
</div>
<div>
	<label>Size (IE 84" box)</label>
	<?php
	$data = array(
		'name' => 'size',
		'id' => 'size',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Root Ball (IE '6X6 or 72" Box)</label>
	<?php
	$data = array(
		'name' => 'root_ball',
		'id' => 'root_ball',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Weight (IE 5k - 6k)</label>
	<?php
	$data = array(
		'name' => 'weight',
		'id' => 'weight',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Trees To Truck (IE 4)</label>
	<?php
	$data = array(
		'name' => 'trees_to_truck',
		'id' => 'trees_to_truck',
		'style' => 'width:100px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Specs (IE 14'Tx12'W)</label>
	<?php
	$data = array(
		'name' => 'specs',
		'id' => 'specs',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>DBH (IE 10")</label>
	<?php
	$data = array(
		'name' => 'dbh',
		'id' => 'dbh',
		'style' => 'width:150px;'
	);
	echo form_input($data);
	?>
</div>
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
<input type="submit" value="Create New Product Type" />
</form>
														