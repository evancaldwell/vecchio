<script src="<?php echo base_url(); ?>_js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>_js/jquery.form.js" type="text/javascript"></script>
<!-- label helper -->
<script type="text/javascript" src="<?php echo base_url();?>_js/jquery.infieldlabel.min.js"></script>

<script type="text/javascript" > 
	$(document).ready(function($){
	var options = { 
        //target:        '#contact_sec',   // target element(s) to be updated with server response
		beforeSubmit:  validateForm, 
        success:       showResponse  // post-submit callback 

        // other available options: 
       // url:             // override for form's 'action' attribute 
       // type:      'post'        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 

        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    };
    $('#contact_form').ajaxForm(options);
	$('#spec_txt_form').jScrollPane();
	$("label").inFieldLabels();
});
	function validateForm(){
		return $('#contact_form').validate({
				rules: {
					fname: "required",
					lname: "required",

					email: {
						required: true,
						email: true
					},
					phone: "required",
					user_type: "required"
				},
				messages: {
					fname: "Please Enter First Name",
					lname: "Please Enter Last Name",
					phone: "Please Enter Complete Phone Number",

					email: {
						required: "Please enter a valid email address",
						//email: "Please enter a valid email address"
					}
				},
				        errorElement: "div",
				        wrapper: "div",  // a wrapper around the error message
				        errorPlacement: function(error, element) {
					        element.before(error);
					        offset = element.offset();
					        error.css('left', offset.left);
					        error.css('top', offset.top - element.outerHeight());
				        }
				}).form();
	}
	function showResponse(responseText, statusText, xhr, $form)  { 
	    // for normal html responses, the first argument to the success callback 
	    // is the XMLHttpRequest object's responseText property 

	    // if the ajaxForm method was passed an Options Object with the dataType 
	    // property set to 'xml' then the first argument to the success callback 
	    // is the XMLHttpRequest object's responseXML property 

	    // if the ajaxForm method was passed an Options Object with the dataType 
	    // property set to 'json' then the first argument to the success callback 
	    // is the json data object returned by the server 

	   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
	     //   '\n\nThe output div should have already been updated with the responseText.');
			$('#spec_txt_form').hide();
	        $('#spec_txt_form').html(responseText);
	        $('#spec_txt_form').fadeIn('slow'); 
	}
</script>
<!-- jQuery handles to place the header background images --> 
<style type="text/css"> 

#contact_erj label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
	
}
div.message{
    background: transparent url(msg_arrow.gif) no-repeat scroll left center;
    padding-left: 7px;
}

div.error{
    background-color:#F3E6E6;
    border-color: #924949;
    border-style: solid solid solid solid;
    border-width: 2px;
    padding: 5px;
	margin-bottom:3px;
	z-index:1000;
}
form legend {
	color: #333;
	padding: 0 0 20px 0;
	text-transform: uppercase;
}

form {
	padding: 0 20px 20px 20px;
}

form, form fieldset input, form fieldset textarea, form label {
    font-family: "Gill Sans", Georgia, "Times New Roman", serif;
	font-size: 12pt;
}
form p { position: relative; margin: 10px 0;}
form p label { position: absolute; top: 0; left: 0;}
form p br {display: none;}


form fieldset p input,
form fieldset p textarea {
	display: block;
	padding: 4px;
	width: 300px;
	margin: 0;
}

form fieldset p label {
	width: 280px;
	display: block;
	margin: 5px 5px 5px 6px;
	padding: 0;
}

form fieldset p textarea {
	padding: 2px;
	width: 404px;
}

form fieldset p textarea,
form fieldset p input {
 border: solid 1px #ccc; 
}
form fieldset p label {
	color: #777;
}
form fieldset {
	border:0px;
}

</style>
<!--[if lte IE 6]>
	<style type="text/css" media="screen">
		form label {
				background: #fff;
		}
	</style>
<![endif]-->
<div id="headerimgs"> 
	<div id="headerimg1" class="headerimg"></div> 
	<div id="headerimg2" class="headerimg"></div> 
	<div id="contact_window">
		<div id="spec_txt_form" >
			<h3 style="color:white; margin-left:0px; padding-left:0px">Request Product Access</h3>
			<form action="<?php echo base_url();?>index.php/dir/ajax_contact/" id="contact_form" method="post" accept-charset="utf-8">
				<fieldset>
					<p>
						<label for="fname">First Name</label><br />
						<input type="text" name="fname" value="" id="fname">
					</p>
					<p>
						<label for="lname">Last Name</label><br />
						<input type="text" name="lname" value="" id="lname">
					</p>
					<p >
						<label for="phone">Phone Number</label><br />
						<input type="text" name="phone" value="" id="phone">
					</p>
						<p class="form_p">
						<label for="email">Email</label><br />
						<input type="text" name="email" value="" id="email">
					</p>
					<p>
						Title: <select name="user_type">
							   <option value="architect">Architect</option>
							   <option value="contractor">Contractor</option>
							   <option value="landscaper">Landscaper</option>
							   <option value="homeowner">Home Owner</option> 
							   </select>
					</p>
									</fieldset>
					<p><input type="submit" value="Submit &raquo;"></p>


			</form>
		</div>
	</div>
</div>
