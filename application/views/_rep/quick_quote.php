<?php if($this->uri->segment(4) != '') { ?>
<script>
$(document).ready(function() {
	$('#customer_id').change(function() {
	$('#ready').val('0');
	$('#new_qq').submit();
});
});
</script>
<?php } ?>
<h3>Quick Quote</h3>

<?php if($this->uri->segment(3) == 'no_selected'){?>
<p style="color:red; font-weight: bold">No customer selected or missing / unrecognized zip code 
	<?php
	if(empty($client)){
		echo "No clients in database. Start " . anchor('vecchio_rep/add_user_form', "here");
	}
	?></p>
<?php } ?>
<label>For Client:</label>
<?php
$dt = array('id'=> 'new_qq');
 echo form_open('vecchio_rep/quick_quote_capt/', $dt); ?>
<select name="customer_id" id="customer_id">
<option value="no_selected">Please Select Client</option>
<?php 
foreach($client as $cl){

echo "<option value='". $cl['id'] . "' ". ($cl['id'] == $this->uri->segment(3) ? 'selected' : '')." >" . $cl['fname'] . " " . $cl['lname'] . " -  Type: ". ucfirst($cl['user_type']). "</option>\n";

}
?>
</select>

<hr />
<label>Ship to Zip Code (5 digit zip)</label>
<input type="text" name="ship_zip" value="<?php echo ($this->uri->segment(4) != '' ? $this->uri->segment(4) : '') ?>" />
<input type="hidden" name="ready" id="ready" value="<?php echo ($this->uri->segment(4) != '' ? 1 : 0) ?>">
<label>- OR -</label>
<br />
<?php
$check = '';
if($this->uri->segment(4) == 'willcall'){
$check = 'checked';	
}
?>
<input type="checkbox" value="1" name="will_call" <?php echo $check; ?> > Will Call / Customer Pickup



<?php
if($this->uri->segment(4) != ''){
?>
<br />
<input type="text" name="po_number" value="" > Enter Purchase Order #<br />
<hr />
<table style="margin-left:20px;" border='0' cellpading='0' cellspacing='0'> 
<?php 
$m = $this->uri->segment(5);
$button = "Generate Quick Quote";
foreach($product as $pr){ ?>
<!-- Use this to show just the products uploaded. 
<tr>
   	<td><?php 
/*	$data = array(
		'src'   => '_images/_products/' . $pr['product_code'] . ".jpg",
		'style' => 'width:80px;',
		'alt' => '_images/default_thumb.png'
	);
	echo img($data); */ ?>
	</td> 
	<td >
<?php //echo "PC:" . $pr['product_code'] . " - ".  $pr['description'] . "<br /> - " . $pr['cnt'] . " Available Online"; ?> 
   </td>
	<td  >
<span style="font-size:14px;" >List Price: <?php // echo "$" . number_format(($pr['list_price']), 2, '.', ','); ?></span>
	</td>
	<td >
<span style="font-size:16px;" >Cust Price: <?php // echo "$" . number_format(($pr['list_price'] * $m), 2, '.', ','); ?></span>
	</td>
	<td  >
<?php // foreach ($types as $key => $value){
//echo '<label>' . ucfirst($value) . ": $" . number_format(($value * $pr['list_price']), 2, '.', ',') . "   </label>";
//} 	
?>
<p>Quantity: <input type="text" style="width:30px;" name="<?php // echo $pr['product_type_id'];?>" value="0" /></p>
</td></tr>
-->
<!-- Use this to show all the products  -->
<tr>
   	<td><?php 

	$data = array(
		'src'   => '_images/_products/' . $pr['product_code'] . ".jpg",
		'style' => 'width:80px;',
		'alt' => base_url() . '_images/default_thumb.png'
	);
	echo img($data); ?>
	<h4 class="prod_pf_txt" style="font-size:10px;"><?php echo 'PC-'. $pr['product_code'] . " " . $pr['description']; ?></h4>
	</td> 
	<td >
<?php echo "PC:" . $pr['product_code'] . " - ".  $pr['description'] . "<br />"; ?> 
   </td>
	<td  >
<span style="font-size:14px;" >List Price: <?php echo "$" . number_format(($pr['list_price']), 2, '.', ','); ?></span>
	</td>
	<td >
<span style="font-size:16px;" >Cust Price: <?php echo "$" . number_format(($pr['list_price'] * $m), 2, '.', ','); ?></span>
	</td>
	<td  >
<p>Quantity: <input type="text" style="width:30px;" name="<?php echo $pr['id'];?>" value="0" /></p>
</td></tr>

<?php
	} ?>
</table>
<br />
<?php if($this->in_house){ ?>
<!-- <input type="checkbox" value="1" name="box_items"> Box Trees for Delivery (if available) -->
<label>Secondary Rep</label>
<select name="rep_id_2">
	<option value="0" >None</option>
<?php foreach($other_reps as $r){
	echo '<option value="'.$r['id'].'" >'.$r['fname'] . ' ' . $r['lname'] . '</option>' . "\n";
}
?>
</select>
<?php } else { ?>
<input type="hidden" name="rep_id_2" value="0" />
<?php } ?>
<br />
<label>Add Personal Message:</label>
<textarea rows="5" cols="50" name="memo"></textarea>	
<?php } else {
$button = "Next";	
}
?>

<input type="submit" value="<?php echo $button; ?>" />
<br />
<br />