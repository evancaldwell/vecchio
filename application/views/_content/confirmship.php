
    	<h2>Please Confirm Shipping Location</h2>
		<h3>Click And Drag Green Marker If Necessary</h3>
           <div id="contact">
           		<div id="map_canvas"  style="width:604px; height:600px; float:left; border: 3px solid #986C47;"></div>
		   		<div id="control_panel" style="width:200px; float:right;text-align:left;padding-top:20px">
					<div id="directions_panel" style="margin:20px; clear:both;"></div>
					<div id="direction_details" style="margin:20px; clear:both;"></div>
					<div id="side_bar_header" style="margin:20px 20px 0px 20px;">Click To View Location</div>
					<div id="side_bar" style="margin:0px 20px 20px 20px"></div>
					<div id="confirm" style="margin:20px;">
							<?php form_open('vecchio_order/shipping_comfirmed'); ?>
							<input id="shipDist" name="shipDist" value="" type="hidden" />
							<h5 style="margin:5px 0px 5px 0px;">Please Enter Any Special Instructions</h5>
							<textarea rows="6" cols="30" name="instructions"></textarea>
							<br /><br />
							<input type="submit" value="CONFIRM SHIPPING" name="submit" />
							</form>
					</div>
				</div>
           </div>


