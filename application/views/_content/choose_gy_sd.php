<script> 
$(document).ready(function() {
 
	$('#Loading').hide(); 
	function check_ship(dateText){
		$('#Loading').show();
		$.post("<?php echo base_url()?>index.php/vecchio_admin_products/check_shipping_date", {
			ship_date: dateText,
			grow_yard: $('input[name=grow_yard]:checked', '#choose_gy').val()
		}, function(response){
			$('#Loading').hide();
			$('#Loading').html(unescape(response));
		    $('#Loading').fadeIn();
		});
		return false;	
	}
	

	$("#ship_date" ).datepicker({
	   onSelect: function(dateText, inst) {
		check_ship(dateText)
	   },
	   minDate : +3,
	   beforeShowDay: function (date) {
	                if (date.getDay() == 0 || date.getDay() == 1 ) {
	                    return [false, ''];
	                } else {
	                    return [true, ''];
	                }
	            }
	
	});
	
	$('#choose_gy').change(function() {
	  if( $("#ship_date").datepicker("getDate") != null ) {
		check_ship($('#ship_date').val());
	  }
	});

});
</script>
<?php 
$data = array(
 	    'id' => 'choose_gy',
   		);
echo form_open('vecchio_admin/new_order_tag', $data);
?>
<input type="text" name="ship_date" value="" id="ship_date" class="required"  />
<span id="Loading"><img src="<?php echo base_url();?>_images/ajax-loader.gif" alt="Ajax Indicator" /></span>
<?php
$count = count($products);
//echo "<pre>";
//print_r($products);
$product = (isset($product_id) ? '/'.$product_id : '');
for($i=1; $i<$count; $i++){
$ch = ($i == 1 ? "checked" : '');
$cp = count($products[$i]['products_in_grow_yard']);
$b = base_url();
echo "<p><input type=\"radio\" name=\"grow_yard\" value=\"".$products[$i]['grow_yard_id']."\" ".$ch." />" . $products[$i]['grow_yard_name'] . " - Aprox. <strong>" . round($products[$i]['distance'], 2) . '</strong> Miles To Ship <strong>' . $cp . '</strong> Total Products In Selected Category: '.
"<a class=\"iframes\" href=\"". $b . "index.php/image_bank/bygrowyard/" . $products[$i]['grow_yard_id'] . $product . "\">View Available Products</a></p>" ;?>
<?php } ?>
<input type="submit" value="Choose Grow Yard / Ship Date" />
</form>

