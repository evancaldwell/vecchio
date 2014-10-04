<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/maskedinput.js" type="text/javascript"></script>  

<script type="text/javascript"> 
$(document).ready(function() {
	$("#disc_perc").mask("9.99");
	// validate signup form on keyup and submit
	$("#promo").validate();
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

<?php
$data = array(
	'id' => 'promo'
);

 echo form_open('vecchio_admin/new_promo', $data);?>
<h2>Add New Promo Code</h2>
<?php if($this->uri->segment(3) == 'badpromo'){?>
<div><p style="color:red">Sorry, either promo code is too short or already in system </p></div>
<?php }
if($this->uri->segment(3) == 'badperc'){ ?>
<div><p style="color:red">Please enter a discount in the following format: 0.##</p></div>	
<?php }
 ?>

<div>
	<label>Promo Code - Enter Random Code At Least 5 Digits</label>
	<?php
	$data = array(
		'name' => 'code',
		'value' => '',
		'class' => 'required'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Percent Off Order Total - ie 0.15 for 15%</label>
	<?php
	$data = array(
		'name' => 'disc_perc',
		'id' => 'disc_perc',
		'value' => '',
		'class' => 'required'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Description of Memo</label>
<textarea rows='5' cols='50' class='required' name='memo'></textarea>
</div>
<div>
	<input type="radio" value="0" name="active" checked /> Not Active<br />
	<input type="radio" value="1" name="active" /> Active
</div>
<div>
<input type="submit" value="Set New Promo Code" />
</form>
</div>
<br />
<br />
<h2>Existing Promo Codes</h2>
<div style="margin-left:20px;">

<table width="750">
	<tr>
		<td>Promo Code</td>
		<td>Percent Off</td>
		<td>Description</td>
		<td>Activate / Deactivate</td>
	</tr>
	<?php foreach($promo as $p ){?>
	<tr>
		<td><?php echo $p['code']?><br />
		<?php if($p['active'] == 1) { ?>
		<span style="color:green">Active</span>
		<?php } else { ?>
		<span style="color:red">Deactivated</span>	
		<?php } ?>
		</td>
		<td><?php echo ($p['disc_perc'] * 100) . "%"; ?></td>
		<td>
			
		
		<?php echo form_open('vecchio_admin/promo_memo')?>
		<input type="hidden" name="promo_id" value="<?php echo $p['id'] ?>" />
		<textarea rows="3" cols="30" name="memo"><?php echo $p['memo'];?></textarea>
		<input type="submit" value="Update" />
		</form>
		</td>
		<td>
		<?php echo form_open('vecchio_admin/promo_activate')?>
		<input type="hidden" name="promo_id" value="<?php echo $p['id'] ?>" />
		<input type="radio" value="0" name="active" <?php echo ($p['active'] == 0 ? 'checked' : '');?> /> Not Active<br />
		<input type="radio" value="1" name="active" <?php echo ($p['active'] == 1 ? 'checked' : '');?> /> Active
		<input type="submit" value="Change" />
		</form>
		</td>
	</tr>
	<?php } 
	?>
</table>
</div>