<div style="margin-left:20px; margin-top:14px;">
<script>
$(document).ready(function(){
	$(".delete_form").submit(function(e){
    	if (!confirm("Are you certain you wish to delete item?"))
    	{
        	e.preventDefault();
        	return;
    	} 
	});
});
</script>
<?php if($this->uri->segment(3) == 'edit'){
?><p style="font-weight:bold;color:green">Edit Accepted</p> <?php
}?>

<?php

echo anchor('vecchio_admin/warranty/preview', "Preview") . "<br /><br />";
$class= array('class' => 'delete_form');
$count = count($w);
for($i=0;$i<$count;$i++){
	$title = $w[$i]['title'];
	$title_id = $w[$i]['id'];
	?>
	<table width="680" border="2" cellspacing="0">
	  <tr>
	    <td width="445" rowspan="2">
	    		<?php echo form_open('vecchio_admin/update_warranty_title');?>
				<label for="update_warranty_title">Update Section Title</label><br />
				<input type="text" name="title_text" value="<?php echo $title; ?>" id="update_warranty_title" style="width:400px;" />
				<input type="hidden" name="title_id" value="<?php echo $title_id; ?>">
	    </td>
	    <td width="219"><br /><br />
		<input type="submit" value="Update Warranty Title" />
		</form><br /><br /></td>
	  </tr>
	  <tr>
	    <td><?php echo form_open('vecchio_admin/delete_warranty_title', $class);?>
		<input type="hidden" name="title_id" value="<?php echo $title_id; ?>">
		<input type="submit" value="Delete Warranty Title" />
		<p style="font-size:10px; color:red;">Warning! all sub sections for this entry will also be removed. </p>
		</form></td>
	  </tr>
	  <tr>
	    <td colspan="2">

<?php $count_items = count($w[$i]['items']);	
	 for($j=0;$j<$count_items;$j++){
		
		$description = $w[$i]['items'][$j]['description'];
		$text_id = $w[$i]['items'][$j]['id'];
?>		 <table width="674" height="54" border="1" cellspacing="0">
	      <tr>
	        <td width="483" rowspan="2">
		<?php echo form_open('vecchio_admin/update_warranty_text');?>        	
		<label for="update_warranty_text">Update Section Text</label><br />
		<textarea rows="10" cols="60" name="warranty_text" ><?php echo $description; ?></textarea>
		<input type="hidden" name="text_id" value="<?php echo $text_id; ?>">	
	        </td>
	        <td width="169">
				<br /><br />
			<input type="submit" value="Update Section" />
			</form><br /><br /></td>
	      </tr>
	      <tr>
	        <td><?php echo form_open('vecchio_admin/delete_warranty_text', $class);?>
			<input type="hidden" name="text_id" value="<?php echo $text_id; ?>">
			<input type="submit" value="Delete Section" />
			</form></td>
	      </tr>
	    </table>	
		
<?php	} // end item loop ?>
<tr>
	<td><?php echo form_open('vecchio_admin/new_warranty_text');?>
	<label for="update_warranty_text">Enter New Section Text</label><br />
	<textarea rows="5" cols="40" name="warranty_text" ></textarea>
	<input type="hidden" name="title_id" value="<?php echo $title_id; ?>"></td>
  <td>	<input type="submit" value="Enter New Section Text" />
	</form></td>
</tr>
</table>
<br />
<br />
<?php 
} // end title loop

?>
<hr />
<?php echo form_open('vecchio_admin/new_warranty_title');?>
<label for="new_warranty_title">New Warranty Title</label><br />
<input type="text" name="title_text" value="" id="new_warranty_title" style="width:400px;" />
<input type="submit" value="Enter New Title" />
</form>
<br />
<br />
</div>