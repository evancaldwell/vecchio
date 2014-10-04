<p style="font-size:14px;"><?php echo anchor('vecchio_rep/quick_quote', "Start New"); ?></p>

<table width="850">
<tr>
	<td>Customer Name</td>
	<td>Rep</td>
	<td>Total</td>
	<td>Download</td>
</tr>
<?php
foreach($quote as $q){ ?>
	<tr>
		<td><?php echo $q['cust_name']?></td>
		<td><?php echo $q['rep']['name'];?>
			<?php if(isset($q['rep2'])){ 
			 echo "<br />" . $q['rep2']['name'];
			} ?>
		</td>
		<td><?php
		$h = "";
		$h .= "Sub Total: " . "$" . number_format($q['sub_total_items'], 2, '.', ',') . "<br >";
		if($q['boxed'] == 1){	
		$h .= "+ Boxed Trees Fee: " . "$" . number_format($q['box_price'], 2, '.', ',') . "<br >";		
		}
		if($q['disc'] > 0){
		$h .= "<b>- Special Discount: " . "$" . number_format($q['disc'], 2, '.', ',') . " - " .$q['who_disc'] . "</b><br >"; 
		}
		$h .= "+ Shipping Estimate: " . "$" . number_format($q['ship_cost'], 2, '.', ',') . "* <br >";
		$h .= "<hr />";
		$h .= "<b>Grand Total: " . "$" . number_format($q['grand_total'], 2, '.', ',') . "</b><br >";
		if($q['will_call'] == 0){
		$h .= "<span style=\"font-size:10px;\">*Shipping to: " . $q['ship_zip'] . ", approximately " . $q['distance'] . " miles from  VECCHIO shipping facility.</span><br /><br />";
		} else {
		$h .= "<span style=\"font-size:10px;\">* Customer Pickup / Will Call - No Freight</span><br /><br />";		
		}
		echo $h;
		
		?></td>
		<td>
			<?php if($q['admin_void'] != 1){  ?>
			<?php echo form_open('vecchio_rep/gen_qq_pdf'); ?>
			<input name="quote_id" type="hidden" value="<?php echo $q['id'] ?>" />
			<input type="submit" value="Download PDF" />
			</form> <br />
			<?php echo form_open('vecchio_download/check_by_fax_qq'); ?>
			<input name="qq_id" type="hidden" value="<?php echo $q['id']  ?>" />
			<input type="submit" value="Check By Fax" />
			</form>
			<?php if($this->in_house){ ?>
				
				<?php 
				$user_id = $this->session->userdata('user_id');
			if($q['rep_id'] != $user_id){ // not their own quotes..
				if($q['rep_id_2'] == $user_id){ // give them the ability to remove themselves from quote.. 
					echo form_open('vecchio_rep/add_remove_rep'); ?>
					<input name="qq_id" type="hidden" value="<?php echo $q['id']  ?>" />
					<input name="rep_id_2" type="hidden" value="<?php echo $q['rep_id_2']; ?>" />
					<input name="add_remove" type="hidden" value="remove" />
					<input type="submit" value="Remove Shared Quote" />
					</form>
			<?php } else { // add themselves from this quote. 
					echo form_open('vecchio_rep/add_remove_rep'); ?>
					<input name="qq_id" type="hidden" value="<?php echo $q['id']  ?>" />
					<input name="add_remove" type="hidden" value="add" />
					<input type="submit" value="Mark Shared Quote" />
					</form>		
			<?php } ?>
					
			<?php
				}  
			} ?>
			<?php } else {
				echo "<span style=\"color:red;\">Quote Voided By Admin</span>";
			} ?>
		</td>
	</tr>
<?php } ?>
</table>
