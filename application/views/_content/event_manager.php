<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/maskedinput.js" type="text/javascript"></script>  

<script type="text/javascript"> 
$(document).ready(function() {
	$("#event").validate();
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
	'id' => 'event'
);

 echo form_open('vecchio_admin/new_event', $data);?>
<h2>Add New Event</h2>
<?php
if($this->uri->segment(3) == 'error'){
	echo "<p style='color:red'>Error - Missing Event Information</p>";
}
?>
<div>
	<label>Enter Event Name</label>
	<?php
	$data = array(
		'name' => 'event_name',
		'value' => '',
		'class' => 'required',
		'style' => 'width:300px'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Enter Event Dates</label>
	<?php
	$data = array(
		'name' => 'event_dates',
		'value' => '',
		'class' => 'required',
		'style' => 'width:200px'
	);
	echo form_input($data);
	?>
</div>
<div>
	<label>Enter Event Website</label>
	<?php
	$data = array(
		'name' => 'event_web',
		'value' => '',
		'class' => 'required',
		'style' => 'width:200px'
	);
	echo form_input($data);
	?>
</div>
<div>
<input type="submit" value="Enter New Event" />
</form>
</div>
<br />
<br />
<h2>Listed Events</h2>
<div style="margin-left:20px;">
<table width="760" cellspacing="0" cellpading="10">

<?php foreach($event as $e){?>
<?php echo form_open('vecchio_admin/edit_event/')?>
<tr>
	<td>
<label>Event Name</label>
<input name="event_name" value="<?php echo $e['event_name'];?>" type="text" style="width:300px" />
<br />
<label>Event Date(s)</label>
<input name="event_dates" value="<?php echo $e['event_dates'];?>" type="text"  style="width:200px" />
<br />
<label>Event Website</label>
<input name="event_web" value="<?php echo $e['event_web'];?>" type="text"  style="width:200px" />
<input name="event_id" value="<?php echo $e['id'];?>" type="hidden" />
<br />
	<input type="submit" value="Update" />
</form>
	</td>
	<td>
<?php echo form_open('vecchio_admin/remove_event');?>
<input name="event_id" value="<?php echo $e['id'];?>" type="hidden" />
<input type="submit" value="Delete Event" />
</form>
	</td>
<?php } ?>
</table>
</div>