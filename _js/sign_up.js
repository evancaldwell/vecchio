$(document).ready(function() {
	$("#phone").mask("(999) 999-9999");
	$("#fax").mask("(999) 999-9999");
    $("#billing_zip").mask("99999");
	// validate signup form on keyup and submit
	
	$("#loginslidees").validate({
		rules: {
			usern_email_login: {
				required: true,
				email: true
			},
			password_login: "required"
		},
		messages :{
			usern_email_login: "Please enter email address",
			password_login: "Please enter password"
		},
		errorPlacement: function(error, element) {
	        error.appendTo('#error-' + element.attr('id'));
	    },
		submitHandler: function(form) {
			   	send_post_login();
		}
	});
		$("#signupslide").validate({
			rules: {
				fname: "required",
				lname: "required",
				company_name: "required",
				password: {
					required: true,
					minlength: 6
				},
				confirm_password: {
					required: true,
					minlength: 6,
					equalTo: "#password"
				},
				usern_email: {
					required: true,
					email: true
				},
				phone: {
					required: true
				},
				billing_address: {
					required: true
				},
				billing_city: {
					required: true
				},
				billing_state: {
					required: true
				},
				billing_zip: {
					required: true
				},
				license_number: {
					required: {
					                depends: function(element) {
					                     return $('#user_type').val() != "homeowner";
					                }
					          }
				}
				
			},
			messages: {
				fname: "Please enter your first name<br />",
				lname: "Please enter your last name",
				company_name: "Please enter company name",
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				phone:{
					required: "Please enter phone number"
				},
				confirm_password: {
					required: "Please confirm password",
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"
				},
				usern_email: "Please enter a valid email address"
			},
			submitHandler: function(form) {
			   	 send_post_ajax();
			},
			errorPlacement: function(error, element) {
		        error.appendTo('#error-' + element.attr('id'));
		    }
		});
		
      	$(".login")
					.button()
				    .click(function () {
		      			$('#newacc_container').hide();
			  			$('#login_container').show();
		
		});
		
		$(".signup")
					.button()
					.click(function () {
					$('#login_container').hide();
		    		$('#newacc_container').show();

		});
		
		$('#newacc_fail').dialog({
		    autoOpen: false,
			width:500,
			height:275,
		    modal: true,
		    resizable: false,
			buttons: {
			           'Close': function() {
							$('#usern_email').val("");
							$(this).dialog( "close" ); 
			         }
					
			}
			
		});
		

		$('#newacc_success').dialog({
		    autoOpen: false,
			width:500,
			height:275,
		    modal: true,
		    resizable: false,
			buttons: {
			           'Log In': function() {
							$('#signupslide')[ 0 ].reset();
							window.location.href = targetUrl;
			         }

			}

		});	
		
		$('#forgot_password').dialog({
		    autoOpen: false,
			width:500,
			height:275,
		    modal: true,
		    resizable: false,
			buttons: {
			           'Close': function() {
							$(this).dialog( "close" ); 
			         }

			}

		});	
		
		$( "#forgot" )
					.button()
					.click(function() {

					$( "#forgot_password" ).dialog( "open" );
		});


		

			
		
		
});

