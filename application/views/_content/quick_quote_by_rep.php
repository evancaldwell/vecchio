<?php if($this->uri->segment(3) == 'error'){?>
	<p style="color:red; font-size:14px;">Error: The zip code entered to mark as paid is different than the one quoted - this will cause the shipping price to be different. The original zip code entered is: <?php echo $this->uri->segment(4); ?>.</p>
<?php } ?>
<?php $count = count($rep);
for($i=0; $i<$count; $i++) { ?>
<h2><?php echo $rep[$i]['fname'] . " " . $rep[$i]['lname']; ?></h2>
<hr />
<table width="850">
<tr>
	<td>Customer Name</td>
	<td>Expire Date</td>
	<td>Total</td>
	<td>Mark As Paid</td>
	<td>Download</td>
	<td>Mark as Void </td>
	<td>Delete</td>
</tr>
<?php
foreach($rep[$i]['quote'] as $q){ ?>
	<tr>
		<td><?php echo $q['cust_name']?>
			<?php if($q['admin_void'] == 1){ echo "<span style=\"color:red;\">VOID</span>"; }?></td>
		<td><?php echo date("F j, Y", strtotime($q['expire_date']))?></td>
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

			<?php 
			if($q['status'] != 2 ){
				  if($q['status'] == 1){
					echo "<p>On Account</p>";
					}
					echo anchor('vecchio_admin/edit_quick_quote/'. $q['id'], "Edit Quote"); ?><br />
				<?php
				// if($q['e_sig'] != ''){ ?>
					<?php echo form_open('vecchio_admin/admin_mark_qq_paid'); ?>
					<input type="hidden" name="cust_name" value="<?php echo $q['cust_name']  ?>" />
					<input type="hidden" name="qq_id" value="<?php echo $q['id']; ?>" />
					<input type="hidden" name="amount" value="<?php echo ($q['ship_cost'] + $q['sum']); ?>" />
					<input type="hidden" name="freight" value="<?php echo $q['ship_cost']; ?>" />
					<input type="submit" value="Enter Payment Details" />
					</form>
			<?php // } else {
				//	echo "<p>Cannot mark paid until client signs E-Signature.</p>";
				 //  }
			 } else { ?>

					<?php 
					$dt = array('id' => 'dwn_lnk'.$q['id']);
					echo anchor('vecchio_download/receipt_qq/'. $q['id'], "Download Receipt &#9660;", $dt);?></p>
								
			<?php } ?>
		</td>
		<td>
			<?php 
			
			echo form_open('vecchio_admin/gen_qq_pdf'); ?>
			<input name="quote_id" type="hidden" value="<?php echo $q['id'] ?>" />
			<input type="submit" value="Download PDF" />
			</form>
			 <br />

			<?php echo form_open('vecchio_download/check_by_fax_qq'); ?>
			<input name="qq_id" type="hidden" value="<?php echo $q['id']  ?>" />
			<input type="submit" value="Check By Fax" />
			</form>
		</td>
		<td>
			<?php 
			if($q['admin_void'] == 1){
			echo form_open('vecchio_admin/unvoid_quick_quote'); ?>
			<input name="void_id" type="hidden" value="<?php echo $q['id']; ?>" />
			<input type="submit" value="Un-Void" />
			</form>	
			<?php } else { 
			echo form_open('vecchio_admin/void_quick_quote'); ?>
			<input name="void_id" type="hidden" value="<?php echo $q['id']; ?>" />
			<input type="submit" value="Void" />
			</form>	
			<?php } ?>
		</td>
		<td>
			<?php
			echo form_open('vecchio_admin/delete_quick_quote'); ?>
			<input name="delete_id" type="hidden" value="<?php echo $q['id']; ?>" />
			<input type="submit" value="Delete" />
			</form>	
		</td>
	</tr>
<?php } ?>
</table>
<?php } ?>
<p>Note: Voided quick quotes cannot be downloaded by rep</p>