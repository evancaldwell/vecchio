<h3>Choose A Product For This Order</h3>
<div>
	<label>Select Product for Order -- Only Products in Stock / Not Tagged Shown</label>
	<?php
		$prod_options = array();
		$prod_options['no_selected'] = "Please Choose a Product Category";
		if(isset($product_type)) : 
			foreach($product_type as $row) :
				$prod_options[$row->id] = $row->product_code. ' - '.$row->description.' - '.$row->specs;
			endforeach;
		else : 
			$prod_options[''] = 'Oh no, there are no product types in the product_type table. Call the web guys!';  
		endif; 
		$endid = 'id = "ComboBox_product"';
		echo form_dropdown('product_type_id', $prod_options, '', $endid);
	?>
</div>
<?php if(isset($reps) && !empty($reps)){?>
<h3>Customer Rep</h3>
<div>
	<?php
		$rep_options = array();
		$rep_options['no_selected'] = "Please Choose a Customer Rep";
		if(isset($reps)) : 
			foreach($reps as $row) :
				$rep_options[$row->id] = $row->fname. ' - '.$row->lname;
			endforeach;
		else : 
			$rep_options[''] = 'No Reps Set- go to "Users" and set up new users';  
		endif; 
		echo form_dropdown('rep_id', $rep_options);
	?>	
</div>
<?php } ?>