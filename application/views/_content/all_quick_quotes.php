<table width="850">
<tr>
	<td>Customer Name</td>
	<td>Rep</td>
	<td>Total</td>
	<td>Date</td>
	<td>Status</td>
	<td>Manage</td>
</tr>
<?php foreach($quote as $q){ ?>
	<?php if($q['admin_void'] == 1){ ?>
		<style>.qq<?php echo $q['id']; ?>{text-decoration:line-through; color:#990000 }</style>
	<?php }?>
	<tr>
		<td class="qq<?php echo $q['id']?>" ><?php echo $q['cust_name']?></td>
		<td class="qq<?php echo $q['id']?>"  ><?php echo $q['rep']['name'];?>
			<?php if(isset($q['rep2'])){ 
			 echo "<br />" . $q['rep2']['name'];
			} ?>
		</td>
		<td class="qq<?php echo $q['id']?>" ><?php
		echo "<b>$" . number_format($q['grand_total'], 2, '.', ',') . "</b><br >";
		?></td>
		<td class="qq<?php echo $q['id']?>" ><?php echo date("F j, Y h:i A", strtotime($q['quote_date'])); ?></td>
		<td class="qq<?php echo $q['id']?>" ><?php echo $q['status_text']; ?>
		<?php if($q['pay_status']['payment_ready'] == false){
			echo '<br />'. $q['pay_status']['payment_reason']; } ?>
		</td>
		<td><?php echo anchor('vecchio_admin/edit_quick_quote/'. $q['id'], "Manage"); ?></td>
	</tr>
<?php } ?>
</table>