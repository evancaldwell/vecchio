<!-- jQuery handles to place the header background images --> 
<?php include './application/config/dbCN.php';?>

<?php

$mysqli = new mysqli($hn, $un, $up, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>
	<div id='quote'>
		<h2>Your Current Quote</h2>
		<table>
			<tr class='quote_header'>
				<th>Botanical Name</th>
				<th>Quantity</th>
				<th>Size</th>
				<th>Remove</th>
			</tr>
			<?php
				if(isset($_SESSION['quote'])){
					$array = $_SESSION['quote'];
					foreach ($array as $product){
						echo "<tr>";
						foreach ($product as $key => $value){
							echo "<td>".$value."</td>";
						}
						echo '<td><button class="deleteProductButton" value="'.$product['product_name'].'*'.$product['product_quantity'].'*'.$product['product_size'].'">Remove</button></td></tr>';
					}
				}
			?>
		</table>
		<div id="ourTreesDynamicProductDisplayInnerDivFormFinalizeQuoteButton">Request Quote</div>
	</div>
<script type='text/JavaScript' src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type='text/CSS' src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/le-frog/jquery-ui.min.css"></script>
<script type='text/JavaScript' src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script type='text/JavaScript'>
	$(document).ready(function() {
		$(document).on("click",".deleteProductButton",function(){
			var deleteValue = this.value;
			var deleteValues = deleteValue.split('*');
			var product_name = deleteValues[0]
			var product_quantity = deleteValues[1]
			var product_size = deleteValues[2]
			$.post("<?php echo base_url()?>index.php/dir/remove_product_from_quote",{
								product_name:product_name,
								product_quantity:product_quantity,
								product_size:product_size
							},
							function(response){
								if(response.indexOf("Success") != -1 ){
									location.reload();
								} else {
									alert('Something went wrong and the product could not be removed.');
								}
							});
		 });
		
		$(document).on("click","#ourTreesDynamicProductDisplayInnerDivFormFinalizeQuoteButton", function(){
			<?php  ?>
			var sender = 
			$.post("<?php echo base_url()?>index.php/dir/email_quote_v2",
							function(response){
								if(response.indexOf("Success") != -1 ){
									alert('Your email was successfuly sent.');
									location.reload();
								} else {
									alert('Something went wrong and the email was not sent, perhaps you have no products listed for a quote?');
								}
							}
						);
		});
	});
</script>

