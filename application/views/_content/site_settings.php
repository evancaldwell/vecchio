<h3>VECCHIO Order / Freight settings</h3>
<div>
<p>Update SMS settings for VECCHIO site. <strong>Tread lightly here as settings have global impacts to order and freight costs.</strong></p>
<p style="color:red;"><?php echo $message?></p>
<?php echo form_open('vecchio_admin/site_settings_update/'); ?>
<label>Shipments allowed per day</label>
<input type="text" name="ship_per_day" value="<?php echo $ship_per_day?>" /><br />
<label>Business days from order to ship</label>
<input type="text" name="order_to_ship" value="<?php echo $order_to_ship?>" /><br />
<label>Hours from order to expire</label>
<input type="text" name="hours_expire" value="<?php echo $hours_expire?>" /><br />
<label>Price per mile</label>
<input type="text" name="price_per_mile" value=" <?php echo $price_per_mile?>" /><br />
<label>Shipping buffer miles</label>
<input type="text" name="buffer_miles" value=" <?php echo $buffer_miles ?>" /><br />
<br />
<input type="submit" value="Update Values" />
</form>
</div>


