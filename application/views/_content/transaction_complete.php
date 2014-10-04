<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript"> 
$(document).ready(function() {
	
	 	$('#Check_Date').hide();
		$("#new_shipping").validate({
			rules: {
				ship_date: "required"
			},
			messages: {
				ship_date: "Please choose date for order shipment"
			}
		});
		function check_ship(dateText){
			$('#Check_Date').show();
			$.post("<?php echo base_url()?>index.php/vecchio_admin_products/check_shipping_date", {
				ship_date: dateText
			}, function(response){
				$('#Check_Date').hide();
				$('#Check_Date').html(unescape(response));
			    $('#Check_Date').fadeIn();
			    // check if the response said date was available
			    if(response.indexOf("Available") != -1 ){
					$('#ship_good').val('yes');
				} else {
					$('#ship_good').val('no');
				}
			});
			return false;		
		}
		
		$("#ship_date").datepicker({
   		onSelect: function(dateText, inst) {
			check_ship(dateText)
   		},
   		minDate : new Date(<?php echo $start_date ?>),
   		showOn: 'focus',
   		beforeShowDay: function (date) {
                	if (date.getDay() == 0 || date.getDay() == 1 ) {
                    	return [false, ''];
                	} else {
                    	return [true, ''];
                	}
            	}
		});
		
	});
</script>
	<br />
	<br />
	<h2><?php echo $message; ?></h2>
	<div>
     <p style="color:#6DB758; margin:25px; font-size:15px; font-weight:bold;"><?php

   if(is_array($info)){
	    foreach($info as $err){
		echo $err . "<br />";
		}
    } else {
   		echo $info;
	}

	?>
	  	 <br />
	     <br />
	
	</p>
   </div>
<?php 
$data = array(
       'id' => 'new_shipping'
);
echo form_open('vecchio_cc/enter_ship_date', $data);?>
<div><h3>Choose Shipment Date</h3>
 <label>Choose Date</label>
 <input type="text" name="ship_date" value="" id="ship_date" class="required"  />
 <span id="Check_Date"><img src="<?php echo base_url(); ?>_images/ajax-loader.gif" alt="Ajax Indicator" /></span>
 <input type="hidden" name="ship_good" id="ship_good" value="no" />
 <input type="hidden" name="order_id"  value="<?php echo $order_id; ?>" />
</div>

<br /><br />
<div>
	<?php echo form_submit('submit', 'Schedule Order Ship Date');?>
</div>
<?php echo form_close();?>


