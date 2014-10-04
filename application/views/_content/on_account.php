<h3>Clients With Approved Credit</h3>
<div style="margin-left:20px;">
<?php
$count_c = count($oc);
if($count_c > 0){ ?>
	<table border="0" cellpadding="5" cellspacing="0" width="820">
		<tr>
			<td width="200">Contact Info</td>
			<td width="400">Net Terms</td>
			<td width="200">Credit Limit</td>
		</tr>
<?php } ?>
<?php 
for($i=0;$i<$count_c;$i++){ ?>
	<tr>
		<td style="background-color:#ECECEC">
			<?php echo $oc[$i]['fname'] . " " . $oc[$i]['lname']; ?><br />
			<?php echo $oc[$i]['usern_email']; ?><br />
			<?php echo $oc[$i]['phone']; ?>
		</td>
			<td style="background-color:#ECECEC">
			<?php echo $oc[$i]['net_terms']; ?>
		</td>
			<td style="background-color:#ECECEC">
			<?php echo "$" . $oc[$i]['credit_limit']; ?>
		</td>
	</tr>
	<tr>
		<?php $count_q = count($oc[$i]['quotes']);
			  
			if($count_q > 0){ ?>
				<tr>
					<td width="200">Quote Info</td>
					<td width="400">Shipping</td>
					<td width="200">Total + Freight</td>
				</tr>
			<?php for($q=0;$q<$count_q;$q++){ ?>
				<td><?php echo "Quote ID: ". $oc[$i]['quotes'][$q]['id'] . "-" . mb_substr($oc[$i]['quotes'][$q]['quote_date'], 0,10);?> <br />
					
				<?php echo form_open('vecchio_admin/gen_qq_pdf'); ?>
					<input name="quote_id" type="hidden" value="<?php echo $oc[$i]['quotes'][$q]['id'] ?>" />
					<input type="submit" value="View Quote Details" />
					</form>	
				</td>
				<td><?php echo 'Ship Date: '. date("F j, Y", strtotime($oc[$i]['quotes'][$q]['ship_date'])); ?> <br />
					<?php echo $oc[$i]['quotes'][$q]['location']; ?><br />
					<?php echo $oc[$i]['quotes'][$q]['ship_address']; ?><br />
					<?php echo $oc[$i]['quotes'][$q]['ship_city'] . ", " . $oc[$i]['quotes'][$q]['ship_state'] . " ". $oc[$i]['quotes'][$q]['ship_zip']; ?>
				</td>
				<td>
				<?php echo  "$" . number_format(($oc[$i]['quotes'][$q]['qq_total']), 2, '.', ','); ?>
				</td>
				<?php }
				echo "<tr><td colspan='2' style='text-align:right;'>Total In Credit</td>";
				echo "<td >"."$" . number_format(($oc[$i]['total_in_credit']), 2, '.', ',')."</td></tr>";
			} else {
				echo "<td colspan='3'> No Orders On Account</td>";
			} ?>
	</tr>
	<tr><td colspan='3'><hr /></td></tr>
<?php } ?>
</table>
</div>