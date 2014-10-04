<?php 
if(1 == 0){ // disabled
 //if($can_box > 0){ ?>
<br />
<table border="0" cellspacing="0" cellpadding="0" >	
	<tr>
	<td class="ti_check_top">&#x2713;</td>
	<td class="ti_optional_top">Optional Order Items (Check if Desired)</td>
	<td class="ti_price_top">Price</td>
	</tr>

	<?php	
		$box_check = '';
		
		if($tag[0]['boxed'] == 1){
			$box_check = 'checked';
		}	
	 ?>
	<tr>
		<td class="ti_check"><input type="checkbox" class="boxcheck" data-id="<?php echo $tag[0]['id']; ?>" value="<?php echo $tag[0]['boxed']; ?>" <?php echo $box_check; ?>  /></td>
		<td class="ti_optional"  >Box trees for delivery or pickup</td>
		<td class="ti_price"><?php echo "$". number_format($can_box, 2, '.', ',');?></td> 
	</tr>

</table>
	<?php } ?>