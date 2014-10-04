<script type="text/javascript">

$(document).ready(function() {
    /// make loader hidden in start
    $('#Loading').hide(); 
    $("input[type=submit]").attr("disabled", "disabled");

    $("#ComboBox_product").change(function() {
	  // Use ajax to send the value of the combobox selected 
	    if($("#ComboBox_product").val() != 'no_selected'){
		$.post("<?php echo base_url()?>index.php/vecchio_admin_products/check_prod_id", {
			prod_id: $("#ComboBox_product").val()
		}, function(response){
			  $("#s_part2").html(unescape(response));
			  $("#s_part2_hidden").val(unescape(response));
		    	check_serial();
		});
		return false;
		
		
		}
	// then insert the value into a span + hidden input box or reg input box
	 });


    $("#ComboBox_grow_yard").change(function() { 
	  // Use ajax to send the value of the combobox selected 
	 if($("#ComboBox_grow_yard").val() != 'no_selected'){
		$.post("<?php echo base_url()?>index.php/vecchio_admin_products/check_short", {
			grow_id : $("#ComboBox_grow_yard").val()
		}, function(response){
               $("#s_part1").html(unescape(response)); 
 			   $("#s_part1_hidden").val(unescape(response)); 
			   check_serial();
		});
		return false;
		
		
	}
	// then insert the value into a span + hidden input box or reg input box
	
	});
	
	$("#box").change(function() { 
	
	 	if($("#box").val() != 'no_selected'){
               $("#s_part3").html($("#box").val()); 
 			   $("#s_part3_hidden").val($("#box").val());
			   check_serial(); 
		}
	
	});
	
	$("#row").change(function() { 
	
	 	if($("#row").val() != 'no_selected'){
               $("#s_part4").html($("#row").val()); 
 			   $("#s_part4_hidden").val($("#row").val());
			   check_serial(); 
		}
	
	});
	
	$("#tree").change(function() { 
		

	
	 	if($("#tree").val() != 'no_selected'){
               $("#s_part5").html($("#tree").val()); 
 			   $("#s_part5_hidden").val($("#tree").val());
			   check_serial();
		}
		
	
	});
	
	function check_serial(){
		var part1 = $("#s_part1_hidden").val();
		var part2 = $("#s_part2_hidden").val();
		var part3 = $("#s_part3_hidden").val();
		var part4 = $("#s_part4_hidden").val();
		var part5 = $("#s_part5_hidden").val();
		var part6 = $("#s_part6_hidden").val();
		$("input[type=submit]").attr("disabled", "disabled");
		var serial =  part1  + '-' + part2 + "-" + part3 + "-" + part4 + "-" + part5 + "-" + part6
		if(part1 && part2 && part3 && part4 && part5 && part6){
			$('#Loading').show();
			$.post("<?php echo base_url()?>index.php/vecchio_admin_products/check_serial", {
				serial_no: serial
			}, function(response){
                    //#emailInfo is a span which will show you message
					$('#Loading').hide();
					$('#Loading').html(unescape(response));
	    			$('#Loading').fadeIn();
	                if(response == 'Serial number available'){
					 $("input[type=submit]").removeAttr("disabled"); 
					 $('#whole_serial').val(serial);   
					}
				});
				return false;
		}
	}
	
 
    $('#s_part4').blur(function(){
	// sweet email filter in javascript, don't need.
	//var a = $("#serial_no").val();
	//var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
       // check if email is valid
    
	//if(filter.test(a)){
                // show loader 
        
//	}
});
});
</script>
<h2>Add a New Product</h2>
<?php echo form_open_multipart('vecchio_admin_products/create');?>

<div>
	<label>Grow Yard</label>
	<?php
	 	$grow_options = array();
	    $grow_options['no_selected'] = "Please Choose a Grow Yard";
		if(isset($grow_yards)) : 
			foreach($grow_yards as $row) : 
				$grow_options[$row->id] = $row->grow_yard_name . " - " . $row->city;
			endforeach;
		else :
			$grow_options[''] = 'On no, there are no grow yards in the grow_yards table. Call the web guys!';
		endif; 
		$endid = 'id = "ComboBox_grow_yard"';
		echo form_dropdown('grow_yard_id', $grow_options, '', $endid);
	?>
</div>
<div>
	<label>Product Type</label>
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
<div>
	<label>Box Size</label>
	<select id="box" name="box">
	<option value="no_selected" />Please Choose Box Size</option>
	<option value="024" />24"Box</option>
	<option value="036" />36"Box</option>
	<option value="048" />48"Box</option>
	<option value="060" />60"Box</option>
	<option value="072" />72"Box</option>
	<option value="084" />84"Box</option>
	<option value="096" />96"Box</option>
	<option value="108" />108"Box</option>	
	<option value="000" />GROUND</option>
	</select>
</div>
<div>
	<label>Row #</label>
	<select id="row" name="row">
	<option value="no_selected" />Please Select Row Number</option>
	<option value="R001" />Row001</option>
	<option value="R002" />Row002</option>
	<option value="R003" />Row003</option>
	<option value="R004" />Row004</option>
	<option value="R005" />Row005</option>
	<option value="R006" />Row006</option>
	<option value="R007" />Row007</option>
	<option value="R008" />Row008</option>
	<option value="R009" />Row009</option>
	<option value="R010" />Row010</option>
	<option value="R011" />Row011</option>
	<option value="R012" />Row012</option>
	<option value="R013" />Row013</option>
	<option value="R014" />Row014</option>
	<option value="R015" />Row015</option>
	<option value="R016" />Row016</option>
	<option value="R017" />Row017</option>
	<option value="R018" />Row018</option>
	<option value="R019" />Row019</option>
	<option value="R020" />Row020</option>
	<option value="R021" />Row021</option>
	<option value="R022" />Row022</option>
	<option value="R023" />Row023</option>
	<option value="R024" />Row024</option>
	<option value="R025" />Row025</option>
	<option value="R026" />Row026</option>
	<option value="R027" />Row027</option>
	<option value="R028" />Row028</option>
	<option value="R029" />Row029</option>
	<option value="R030" />Row030</option>
	<option value="R031" />Row031</option>
	<option value="R032" />Row032</option>
	<option value="R033" />Row033</option>
	<option value="R034" />Row034</option>
	<option value="R035" />Row035</option>
	<option value="R036" />Row036</option>
	<option value="R037" />Row037</option>
	<option value="R038" />Row038</option>
	<option value="R039" />Row039</option>
	<option value="R040" />Row040</option>
	<option value="R041" />Row041</option>
	<option value="R042" />Row042</option>
	<option value="R043" />Row043</option>
	<option value="R044" />Row044</option>
	<option value="R045" />Row045</option>
	<option value="R046" />Row046</option>
	<option value="R047" />Row047</option>
	<option value="R048" />Row048</option>
	<option value="R049" />Row049</option>
	<option value="R050" />Row050</option>
	<option value="R051" />Row051</option>
	<option value="R052" />Row052</option>
	<option value="R053" />Row053</option>
	<option value="R054" />Row054</option>
	<option value="R055" />Row055</option>
	<option value="R056" />Row056</option>
	<option value="R057" />Row057</option>
	<option value="R058" />Row058</option>
	<option value="R059" />Row059</option>
	<option value="R060" />Row060</option>
	<option value="R061" />Row061</option>
	<option value="R062" />Row062</option>
	<option value="R063" />Row063</option>
	<option value="R064" />Row064</option>
	<option value="R065" />Row065</option>
	<option value="R066" />Row066</option>
	<option value="R067" />Row067</option>
	<option value="R068" />Row068</option>
	<option value="R069" />Row069</option>
	<option value="R070" />Row070</option>
	<option value="R071" />Row071</option>
	<option value="R072" />Row072</option>
	<option value="R073" />Row073</option>
	<option value="R074" />Row074</option>
	<option value="R075" />Row075</option>
	<option value="R076" />Row076</option>
	<option value="R077" />Row077</option>
	<option value="R078" />Row078</option>
	<option value="R079" />Row079</option>
	<option value="R080" />Row080</option>
	<option value="R081" />Row081</option>
	<option value="R082" />Row082</option>
	<option value="R083" />Row083</option>
	<option value="R084" />Row084</option>
	<option value="R085" />Row085</option>
	<option value="R086" />Row086</option>
	<option value="R087" />Row087</option>
	<option value="R088" />Row088</option>
	<option value="R089" />Row089</option>
	<option value="R090" />Row090</option>
	</select>
</div>
<div>
	<label>Tree #</label>
	<select id="tree" name="tree">
	<option value="no_selected" />Please Choose Tree Number</option>
	<option value="T001" />Tree001</option>
	<option value="T002" />Tree002</option>
	<option value="T003" />Tree003</option>
	<option value="T004" />Tree004</option>
	<option value="T005" />Tree005</option>
	<option value="T006" />Tree006</option>
	<option value="T007" />Tree007</option>
	<option value="T008" />Tree008</option>
	<option value="T009" />Tree009</option>
	<option value="T010" />Tree010</option>
	<option value="T011" />Tree011</option>
	<option value="T012" />Tree012</option>
	<option value="T013" />Tree013</option>
	<option value="T014" />Tree014</option>
	<option value="T015" />Tree015</option>
	<option value="T016" />Tree016</option>
	<option value="T017" />Tree017</option>
	<option value="T018" />Tree018</option>
	<option value="T019" />Tree019</option>
	<option value="T020" />Tree020</option>
	<option value="T021" />Tree021</option>
	<option value="T022" />Tree022</option>
	<option value="T023" />Tree023</option>
	<option value="T024" />Tree024</option>
	<option value="T025" />Tree025</option>
	<option value="T026" />Tree026</option>
	<option value="T027" />Tree027</option>
	<option value="T028" />Tree028</option>
	<option value="T029" />Tree029</option>
	<option value="T030" />Tree030</option>
	<option value="T031" />Tree031</option>
	<option value="T032" />Tree032</option>
	<option value="T033" />Tree033</option>
	<option value="T034" />Tree034</option>
	<option value="T035" />Tree035</option>
	<option value="T036" />Tree036</option>
	<option value="T037" />Tree037</option>
	<option value="T038" />Tree038</option>
	<option value="T039" />Tree039</option>
	<option value="T040" />Tree040</option>
	<option value="T041" />Tree041</option>
	<option value="T042" />Tree042</option>
	<option value="T043" />Tree043</option>
	<option value="T044" />Tree044</option>
	<option value="T045" />Tree045</option>
	<option value="T046" />Tree046</option>
	<option value="T047" />Tree047</option>
	<option value="T048" />Tree048</option>
	<option value="T049" />Tree049</option>
	<option value="T050" />Tree050</option>
	<option value="T051" />Tree051</option>
	<option value="T052" />Tree052</option>
	<option value="T053" />Tree053</option>
	<option value="T054" />Tree054</option>
	<option value="T055" />Tree055</option>
	<option value="T056" />Tree056</option>
	<option value="T057" />Tree057</option>
	<option value="T058" />Tree058</option>
	<option value="T059" />Tree059</option>
	<option value="T060" />Tree060</option>
	<option value="T061" />Tree061</option>
	<option value="T062" />Tree062</option>
	<option value="T063" />Tree063</option>
	<option value="T064" />Tree064</option>
	<option value="T065" />Tree065</option>
	<option value="T066" />Tree066</option>
	<option value="T067" />Tree067</option>
	<option value="T068" />Tree068</option>
	<option value="T069" />Tree069</option>
	<option value="T070" />Tree070</option>
	<option value="T071" />Tree071</option>
	<option value="T072" />Tree072</option>
	<option value="T073" />Tree073</option>
	<option value="T074" />Tree074</option>
	<option value="T075" />Tree075</option>
	<option value="T076" />Tree076</option>
	<option value="T077" />Tree077</option>
	<option value="T078" />Tree078</option>
	<option value="T079" />Tree079</option>
	<option value="T080" />Tree080</option>
	<option value="T081" />Tree081</option>
	<option value="T082" />Tree082</option>
	<option value="T083" />Tree083</option>
	<option value="T084" />Tree084</option>
	<option value="T085" />Tree085</option>
	<option value="T086" />Tree086</option>
	<option value="T087" />Tree087</option>
	<option value="T088" />Tree088</option>
	<option value="T089" />Tree089</option>
	<option value="T090" />Tree090</option>
	<option value="T091" />Tree091</option>
	<option value="T092" />Tree092</option>
	<option value="T093" />Tree093</option>
	<option value="T094" />Tree094</option>
	<option value="T095" />Tree095</option>
	<option value="T096" />Tree096</option>
	<option value="T097" />Tree097</option>
	<option value="T098" />Tree098</option>
	<option value="T099" />Tree099</option>
	<option value="T100" />Tree100</option>
	<option value="T101" />Tree101</option>
	<option value="T102" />Tree102</option>
	<option value="T103" />Tree103</option>
	<option value="T104" />Tree104</option>
	<option value="T105" />Tree105</option>
	<option value="T106" />Tree106</option>
	<option value="T107" />Tree107</option>
	<option value="T108" />Tree108</option>
	<option value="T109" />Tree109</option>
	<option value="T110" />Tree110</option>
	<option value="T111" />Tree111</option>
	<option value="T112" />Tree112</option>
	<option value="T113" />Tree113</option>
	<option value="T114" />Tree114</option>
	<option value="T115" />Tree115</option>
	<option value="T116" />Tree116</option>
	<option value="T117" />Tree117</option>
	<option value="T118" />Tree118</option>
	<option value="T119" />Tree119</option>
	<option value="T120" />Tree120</option>
	<option value="T121" />Tree121</option>
	<option value="T122" />Tree122</option>
	<option value="T123" />Tree123</option>
	<option value="T124" />Tree124</option>
	<option value="T125" />Tree125</option>
	<option value="T126" />Tree126</option>
	<option value="T127" />Tree127</option>
	<option value="T128" />Tree128</option>
	<option value="T129" />Tree129</option>
	<option value="T130" />Tree130</option>
	<option value="T131" />Tree131</option>
	<option value="T132" />Tree132</option>
	<option value="T133" />Tree133</option>
	<option value="T134" />Tree134</option>
	<option value="T135" />Tree135</option>
	<option value="T136" />Tree136</option>
	<option value="T137" />Tree137</option>
	<option value="T138" />Tree138</option>
	<option value="T139" />Tree139</option>
	<option value="T140" />Tree140</option>
	<option value="T141" />Tree141</option>
	<option value="T142" />Tree142</option>
	<option value="T143" />Tree143</option>
	<option value="T144" />Tree144</option>
	<option value="T145" />Tree145</option>
	<option value="T146" />Tree146</option>
	<option value="T147" />Tree147</option>
	<option value="T148" />Tree148</option>
	<option value="T149" />Tree149</option>
	<option value="T150" />Tree150</option>
	<option value="T151" />Tree151</option>
	<option value="T152" />Tree152</option>
	<option value="T153" />Tree153</option>
	<option value="T154" />Tree154</option>
	<option value="T155" />Tree155</option>
	<option value="T156" />Tree156</option>
	<option value="T157" />Tree157</option>
	<option value="T158" />Tree158</option>
	<option value="T159" />Tree159</option>
	<option value="T160" />Tree160</option>
	<option value="T161" />Tree161</option>
	<option value="T162" />Tree162</option>
	<option value="T163" />Tree163</option>
	<option value="T164" />Tree164</option>
	<option value="T165" />Tree165</option>
	<option value="T166" />Tree166</option>
	<option value="T167" />Tree167</option>
	<option value="T168" />Tree168</option>
	<option value="T169" />Tree169</option>
	<option value="T170" />Tree170</option>
	<option value="T171" />Tree171</option>
	<option value="T172" />Tree172</option>
	<option value="T173" />Tree173</option>
	<option value="T174" />Tree174</option>
	<option value="T175" />Tree175</option>
	<option value="T176" />Tree176</option>
	<option value="T177" />Tree177</option>
	<option value="T178" />Tree178</option>
	<option value="T179" />Tree179</option>
	<option value="T180" />Tree180</option>
	<option value="T181" />Tree181</option>
	<option value="T182" />Tree182</option>
	<option value="T183" />Tree183</option>
	<option value="T184" />Tree184</option>
	<option value="T185" />Tree185</option>
	<option value="T186" />Tree186</option>
	<option value="T187" />Tree187</option>
	<option value="T188" />Tree188</option>
	<option value="T189" />Tree189</option>
	<option value="T190" />Tree190</option>
	<option value="T191" />Tree191</option>
	<option value="T192" />Tree192</option>
	<option value="T193" />Tree193</option>
	<option value="T194" />Tree194</option>
	<option value="T195" />Tree195</option>
	<option value="T196" />Tree196</option>
	<option value="T197" />Tree197</option>
	<option value="T198" />Tree198</option>
	<option value="T199" />Tree199</option>
	<option value="T200" />Tree200</option>
	<option value="T201" />Tree201</option>
	<option value="T202" />Tree202</option>
	<option value="T203" />Tree203</option>
	<option value="T204" />Tree204</option>
	<option value="T205" />Tree205</option>
	<option value="T206" />Tree206</option>
	<option value="T207" />Tree207</option>
	<option value="T208" />Tree208</option>
	<option value="T209" />Tree209</option>
	<option value="T210" />Tree210</option>
	<option value="T211" />Tree211</option>
	<option value="T212" />Tree212</option>
	<option value="T213" />Tree213</option>
	<option value="T214" />Tree214</option>
	<option value="T215" />Tree215</option>
	<option value="T216" />Tree216</option>
	<option value="T217" />Tree217</option>
	<option value="T218" />Tree218</option>
	<option value="T219" />Tree219</option>
	<option value="T220" />Tree220</option>
	<option value="T221" />Tree221</option>
	<option value="T222" />Tree222</option>
	<option value="T223" />Tree223</option>
	<option value="T224" />Tree224</option>
	<option value="T225" />Tree225</option>
	</select>
</div>
<div>
	<label>Serial Number: (generated from dropdown)</label>
	<div style="margin-left:25px" />
	<span id="s_part1"></span>-<span id="s_part2"></span>-<span id="s_part3"></span>-<span id="s_part4"></span>-<span id="s_part5"></span>-<span id="s_part6"><?php echo $rand_seed;?></span>
	<!-- PRODUCT TYPE --> 
	<!-- BOX SIZE --> 
	<!-- ROW # --> 
	<!-- TREE # --> 
	 	 <!-- RAND --> 
	<span id="Loading" style="color:#0c0" ><img src="<?php echo base_url();?>_images/ajax-loader.gif" alt="Ajax Indicator" /></span>
	<input type="hidden" id="s_part1_hidden" name="s_part1" value="" />
	<input type="hidden" id="s_part2_hidden" name="s_part2" value="" />	
	<input type="hidden" id="s_part3_hidden" name="s_part3" value="" />
	<input type="hidden" id="s_part4_hidden" name="s_part4" value="" />
	<input type="hidden" id="s_part5_hidden" name="s_part5" value="" />
	<input type="hidden" id="s_part6_hidden" name="s_part6" value="<?php echo $rand_seed;?>" />
    <input type="hidden" id="whole_serial" name="whole_serial" value= "">	
	</div>	
	
</div>
<!-- 
<div>
	<label>Display to Public:</label>
	<?php echo form_radio('public_view', '1', TRUE)?> Yes
	<?php echo form_radio('public_view', '0', FALSE)?> No
</div>
-->
<h5>Choose Image</h5>
<div>
	<label>Product Image:</label>
	<?php echo form_upload('userfile','')?>

</div>
<br />
<br />
<h5>Upload</h5>
<div>
	<?php echo form_submit('submit', 'Add Item To Inventory');?>
</div>
<?php echo form_close();?>