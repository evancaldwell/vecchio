
<?php
include './application/config/dbCN.php';


$bot_name = $_GET['bName'];

$mysqli = new mysqli($hn, $un, $up, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}



?>




<input id='productIdOfSelectedProduct' type='hidden' value='159'>

<div id='ourTreesDynamicProductDisplay'>

	<div id='ourTreesDynamicProductDisplayInnerDiv'>

		<h2><?php echo $bot_name; ?></h2>
		<hr>
		<div id='ourTreesDynamicProductDisplayInnerDivPicture'>
		<img src="https://www.vecchiotrees.com/_fdr/373601e7fbc3dcc3bbd3094fd1e05e6a.JPG" width="400">
		</div>
		<div id='ourTreesDynamicProductDisplayInnerDivSizes'>
		<h3>Sizes</h3>
		<hr>
		<ul style='list-style-type: none;'>
		<?php
		$res = $mysqli->query("SELECT * FROM product_catalog WHERE botanical_name = '$bot_name'");
			while ($row = $res->fetch_assoc()) {
			echo "<li style='line-height:150%;'>" . ($row['24_in_box'] == 1 ? '24" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['36_in_box'] == 1 ? '36" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['48_in_box'] == 1 ? '48" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['60_in_box'] == 1 ? '60" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['72_in_box'] == 1 ? '72" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['84_in_box'] == 1 ? '84" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['96_in_box'] == 1 ? '96" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['108_in_box'] == 1 ? '108" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['120_in_box'] == 1 ? '120" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['132_in_box'] == 1 ? '132" Box' : '') . "</li>";
			echo "<li style='line-height:150%;'>" . ($row['b_n_b'] == 1 ? 'b_n_b' : '') . "</li>";
			}
		?>
		</ul>
		
		</div>
		<div id='ourTreesDynamicProductDisplayInnerDivInformation'>
		<hr>
		<p>
		Information about that tree, info about that tree, info about that tree
		</p>
		<hr>
		</div>
		<div id='ourTreesDynamicProductDisplayInnerDivRequestQuoteButton'>Request Quote</div>

	</div>
	
	<?php if(1 == 0){ ?>
	
	<div id="dialog-confirm" title="Tree Added To Cart">
		    <p>
			<br />
			</p>
			<p>
				<span class='ui-icon ui-icon-circle-check' style='float:left; margin:0 7px 50px 0;'></span>
				You have successfully added a <?php echo $info[0]['specs'];?> <?php echo $info[0]['description'];?><br />
				Serial no:   <b><?php echo $info[0]['serial_no'];?></b> to your cart.
			</p>
			<p>
				Click on <b>My Account</b> to complete your order, or close window to browse more products. 
			</p>
	</div>
	<div id='dialog-error' title='Error - Product Not Available'>
		    <p>
			<br />
			</p>
			<p>
			 	<span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>There was an error adding this product, most likely it was tagged by another user a few seconds before you. Please choose another from our vast inventory. 
			</p>
	</div>
	
	<?php }
	?>
	
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type='text/JavaScript'>
$(document).ready(function() {
$(document).on("click","#ourTreesDynamicProductDisplayInnerDivRequestQuoteButton",function(){

var product_id = document.getElementById("productIdOfSelectedProduct").value;

$.post("<?php echo base_url()?>index.php/dir/add_product", {
					product_id:product_id
				},
		        function(response){
		            if(response.indexOf("Success") != -1 ){
						$('#dialog-confirm').dialog('open');
					} else {
						$('#dialog-error').dialog('open');
					}
		        });

 });
 
 var targetUrl = '<?php echo base_url() . "index.php/dir/myaccount/cart" ?>';
			$('#dialog-confirm').dialog({
			    autoOpen: false,
				width:500,
				height:225,
			    modal: true,
			    resizable: false,
				buttons: {
				           'Add More Products': function() {
							//	parent.$.fancybox.close();
								window.parent.location.href = window.parent.location.href;	 
				         },
						   'Check Out': function(){
							window.parent.location.href = targetUrl;
						 }
						
				},
				close: function() {
					parent.$.fancybox.close(); 	
				}
				
			});	
			
			$('#dialog-error').dialog({
			    autoOpen: false,
				width:500,
				height:225,
			    modal: true,
			    resizable: false,
				buttons: {
				           'Okay': function() {
								parent.$.fancybox.close(); 	 
				         }
						
				}
				
			});
 
});
</script>
</div>

