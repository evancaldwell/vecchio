 $(document).ready(function() {	
	

	
	// box type checkd
	$(".boxcheck").change(function() {
		if ($(this).is(":checked")) {
			$.ajax({
				url: bsu+'index.php/dir/add_remove_box',
				type: 'POST',
				data: {
					order_id: $(this).attr("data-id"),
					boxed: "1"
				},
				dataType: 'json',
				success: function(response) {
					if (response.status == 1) {
						window.location.href = bsu+"index.php/dir/myaccount/cart"; 
					} else {
						window.location.href = bsu+"index.php/dir/log_in"; 
					}
				}
			});
		} else {
			$.ajax({
				url: bsu+'index.php/dir/add_remove_box',
				type: 'POST',
				data: {
					order_id: $(this).attr("data-id"),
					boxed: "0"
				},
				dataType: 'json',
				success: function(response) {
					if (response.status == 1) {
						window.location.href = bsu+"index.php/dir/myaccount/cart"; 
					} else {
							window.location.href = bsu+"index.php/dir/log_in"; 
					}
				}
			});
		}
	});
	

	$('#dwn_notice').dialog({
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
	
	function is_will_call(){
		return ($('#enter_freight_form input:radio[name=will_call]:checked').val() == '0');
	}	
	
	
	
	$("#enter_freight_form").validate({
		rules: {
			ship_zip: {
				required: {
				                depends: function(element) {
				                   return is_will_call();
								}
				},
				minlength: 5,
				maxlength: 5
			},
			ship_city : {
				required: {
					depends: function(element) {
	                   return is_will_call();
					}
				}
			}, 
			ship_address : {
				required: {
					depends: function(element) {
	                   return is_will_call();
					}
				}				
			},
			ship_state : {
				required: {
					depends: function(element) {
	                   return is_will_call();
					}
				}				
			},
			location: {
				required:true
			},
			location_phone: {
				required:true
			}
			
		},
		messages: {
			ship_zip: 'Please enter shipping zip destination',
			location: 'Please enter Job Name / Id or Name of Recipient',
			location_phone: 'Please enter phone number of Recieving Agent'
		},
		submitHandler: function(form) {
		   	 send_freight_post();
		},
		errorPlacement: function(error, element) {
	        error.appendTo('#error-' + element.attr('id'));
	    }
	});

	$("#email_quote").validate({
		rules: {
			email_to: {
				required: true,
				email:true
			}	
		},
		submitHandler: function(form) {
		   	 send_email_quote();
		},
		errorPlacement: function(error, element) {
	        error.appendTo('#error-' + element.attr('id'));
	    }
	});

	function send_email_quote(){
		$("#submit_email").attr("disabled", "disabled");
		$.post(bsu+"index.php/dir/email_quote", 
			$("#email_quote").serialize()
		, function(response){
			$("#submit_email").removeAttr("disabled"); 
			$("#sent_email").html($("#email_to").val()); 
			$("#quote_email_to").html($("#email_to").val()); 
			$('#email_to_cust').hide();
			$('#email_to_cust_resp').show();		
			$('#return_message_email').dialog('open');				
		});	
	}

	$('#return_message_email').dialog({
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

	$("#apply_promo").validate({
		rules: {
			promo_code: {
				required: true
			}	
		},
		submitHandler: function(form) {
		   	 send_promo_code();
		},
		errorPlacement: function(error, element) {
	        error.appendTo('#error-' + element.attr('id'));
	    }
	});

	function send_promo_code(){
		$.post(bsu+"index.php/dir/apply_promo", 
			$("#apply_promo").serialize()
		, function(response){
			if(response.indexOf("discount applied") != -1 ){
				location.reload();
				} else {
				$('#bad_promo').dialog('open');
				$('#bad_promo').val('');
				}			
		});	
	}

	$('#bad_promo').dialog({
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


	function send_freight_post(){
		$(".submit_ship").attr("disabled", "disabled");
		$.post(bsu+"index.php/dir/update_shipping_capture", 
			$("#enter_freight_form").serialize()
		, function(response){
			$(".submit_ship").removeAttr("disabled");   
				$('#rmessage').html(response);
				$('#return_message').dialog('open');				
		});	
	}

	$("#enter_credit_card").validate({
		rules: {
			first_name : {
				required: true
			},
			last_name : {
				required: true
			},
			card_num: {
				required: true,
				creditcard: true
			},
			exp_date: {
				required: true,
				date: true
			},
			card_code : {
				required: true,
				minlength:3,
				maxlength:4
			},
			emailcc : {
				required: true,
				email:true
			}	
		},
		errorPlacement: function(error, element) {
	        error.appendTo('#error-' + element.attr('id'));
	    },
		submitHandler: function(form) {
					var e_sig_main = $('#e_sig_main').val();
					if(e_sig_main.length > 5){
						form.submit();
					} else {
					// form requires signature, open dialog.
					$('#cof').val('credit');
					$('#creditorfax').html('I hereby authorize my credit card to be charged for this transaction');	
					$('#terms_and_warranty').dialog('open');
					}
		}
	});



	$("#enter_check_by_fax").validate({
		rules: {
			first_namefx : {
				required: true
			},
			last_namefx : {
				required: true
			},
			emailfx : {
				required: true,
				email:true
			},
			phonefx : {
				required: true
			}
		},
		errorPlacement: function(error, element) {
	        error.appendTo('#error-' + element.attr('id'));
	    },
			submitHandler: function(form) {
						var e_sig_main = $('#e_sig_mainfx').val();
						if(e_sig_main.length > 5){
							form.submit();
						} else {
						// form requires signature, open dialog.
						$('#cof').val('fax');
						$('#creditorfax').html('I hereby authorize a check to be faxed and deposited for this transaction');	
						$('#terms_and_warranty').dialog('open');
						}
			}
	});

	function check_by_fax(){
		$.post(bsu+"index.php/vecchio_cc/check_by_fax", 
			$("#enter_check_by_fax").serialize()
		, function(response){

		    // check if the response said date was available
		    if(response.indexOf("Fail") != -1 ){
				$('#dialog-error-fax').dialog('open');	
			} 
		});
		return false;
	}

	$('#dialog-check-by-fax').dialog({
	    autoOpen: false,
		width:500,
		height:275,
	    modal: true,
	    resizable: false,
		buttons: {
		           'Ok': function() {
						location.reload();
		         }
			
		}
	
	});

	$('#dialog-error-fax').dialog({
	    autoOpen: false,
		width:500,
		height:275,
	    modal: true,
	    resizable: false,
		buttons: {
		           'Close': function() {
						show_items();
						$(this).dialog('close');
		         }
			
		}
	
	});

	function order_on_credit(){
						var e_sig_main = $('#e_sig_main_oc').val();
						if(e_sig_main.length > 5){
							$('#order_on_credit').submit();
						} else {
						// form requires signature, open dialog.
							$('#cof').val('on_account');
							$('#creditorfax').html('I hereby authorize this order to be placed on account for this transaction');	
							$('#terms_and_warranty').dialog('open');
						}
	}

	$('#good_promo').dialog({
	    autoOpen: false,
		width:500,
		height:275,
	    modal: true,
	    resizable: false,
		buttons: {
		           'Close': function() {
						show_items();
						$(this).dialog('close');
		         }
			
		}
	
	});



	$('#dialog-confirm').dialog({
	    autoOpen: false,
	    height: 300,
	    width: 400,
	    modal: true,
	    resizable: false,
	    buttons: {
	        'Confirm': function(){
	    		$.post(bsu+"index.php/dir/remove_item", 
					$("#remove_items_form").serialize()
				, function(response){
					if(response.indexOf("removed from order") != -1 ){
						location.reload();
					} else {
						$('#rmessage').html(response);
						$('#return_message').dialog('open');
						$(this).dialog('close');
					}
				
				});
				            	
	        },
	        'Cancel': function(){
            
	            $(this).dialog('close');
	        }
	    }
	});

	$('#return_message').dialog({
	    autoOpen: false,
		width:400,
		height:225,
	    modal: true,
	    resizable: false,
		buttons: {
		           'Ok': function() {
					window.location.href = bsu+"index.php/dir/myaccount/cart"; 
		         }
			
		},
		close: function()  {
			window.location.href = bsu+"index.php/dir/myaccount/cart"; 
		}
	
	});


	
	$('#remove_items_form').submit(function(e){
				e.preventDefault();
				$('#dialog-confirm').dialog('open');
	});


	var t = order_expire.split(/[- :]/);

	// Apply each element to the Date function
	var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
	$('#defaultCountdown').countdown({until: d });
	$('#year').text(d .getFullYear());

	// show hide vars

	function show_freight(){
		$('#tagged_items').hide();
		$('#pay_credit_card').hide();
	//	$('#pay_e_check').hide();
		$('#show_ssl').hide();
		$('#pay_check_by_fax').hide();
		$('#download_check_by_fax').hide();
		$('#enter_freight').show();	

	}

	function show_credit_card(){
		$('#tagged_items').hide();
	//	$('#pay_e_check').hide();
		$('#enter_freight').hide();
		$('#pay_credit_card').show();
		$('#pay_check_by_fax').hide();
		$('#download_check_by_fax').hide();
		$('#show_ssl').show();	
	
	}



	function show_items(){
		$('#enter_freight').hide();
		$('#pay_credit_card').hide();
	//	$('#pay_e_check').hide();
		$('#show_ssl').hide();
		$('#pay_check_by_fax').hide();
		$('#download_check_by_fax').hide();
		$('#tagged_items').show();
	}

	function show_check_by_fax(){
		$('#enter_freight').hide();
		$('#pay_credit_card').hide();
	//	$('#pay_e_check').hide();
		$('#show_ssl').hide();
		$('#tagged_items').hide();
		if(fax_approved == 1){
			$('#pay_check_by_fax').hide();
			$('#download_check_by_fax').show();	
	 	} else { 
			$('#pay_check_by_fax').show();
			$('#download_check_by_fax').hide();
		}
	}
	
	

	$('#terms_and_warranty').dialog({
	    autoOpen: false,
		width:800,
		height:590,
	    modal: true,
	    resizable: false,
		buttons: { 
		           'Accept Terms and Complete Order': function() {
						var e_sig = $('#e_sig').val();
						if(e_sig.length > 5){
							if($('#cof').val() == 'credit'){
								$('#e_sig_main').val(e_sig);
								$('#pay_credit_card').hide();
								$('#loadingbar').show();
								$(this).dialog('close');
								$('#enter_credit_card').submit();
							} else if($('#cof').val() == 'fax') {
								$('#e_sig_mainfx').val(e_sig);
								$('#pay_check_by_fax').hide();
								$('#loadingbar').show();
								$(this).dialog('close');
								$('#enter_check_by_fax').submit();
							} else if($('#cof').val() == 'on_account') {
								$('#e_sig_main_oc').val(e_sig);
								$('#tagged_items').hide();
								$('#loadingbar').show();
								$(this).dialog('close');
								$('#order_on_credit').submit();									
							}

						} else {
							alert("Please Enter Full Name As E-Signature");
						}
					
		         }, 'Cancel': function(){
					$(this).dialog( "close" );
					}
			
			
		}
	
	});

	$('.show_terms').click(function () {
		$( "#terms_and_warranty" ).dialog( "open" );
	});

	$('#freight_info').dialog({
	    autoOpen: false,
		width:450,
		height:300,
	    modal: true,
	    resizable: false,
		buttons: {
		           'Close': function() {
						$(this).dialog( "close" );
		         }
			
		}
	
	});

	$('.show_freight_info').click(function () {
		$( "#freight_info" ).dialog( "open" );
	});

	$('#dialog-error').dialog({
	    autoOpen: false,
		width:500,
		height:275,
	    modal: true,
	    resizable: false,
		buttons: {
		           'Enter Shipping': function() {
						$("#tabs").tabs({
						selected : 1
						});
						show_freight();
						$(this).dialog( "close" ); 
		         }
			
		}
	
	});

	$('#order_expire').dialog({
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

	$('#hold_later').dialog({
	    autoOpen: false,
		width:500,
		height:275,
	    modal: true,
	    resizable: false,
		buttons: {
		           'Ok': function() {
						$(this).dialog( "close" ); 
		         }
			
		}
	
	});
	
	$('#update_po_order').dialog({
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

	$( ".update_po_order_a" )
				.click(function() {
				$( "#update_po_order" ).dialog( "open" );
	});

	$( "#comp-credit" )
				.button()
				.click(function() {
					show_credit_card();
	});

	$( "#comp-on_credit" )
				.button()
				.click(function() {
				order_on_credit();
	});



	$( ".back-cart" )
				.button()
				.click(function(e) {
					e.preventDefault();
					show_items();
	});

	$( ".enter-shipping" )
				.button()
				.effect( "pulsate", 
				    {times:6}, 'normal' )
				.click(function() {
					show_freight();
	});

	$( "#edit-shipping" )
				.button()
				.click(function() {
					show_freight();
	});


	$( "#comp-check-by-fax" )
				.button()
				.click(function() {
					show_check_by_fax();
	});

	$( "#remove_item" )
				.button()
				.click(function() {
				// show_freight();
	});

	$( "#comp-hold" )
				.button()
				.click(function() {
				
				$( "#hold_later" ).dialog( "open" );
	});

	$( "#dwn_lnk" )
				.button()
				.click(function() {
						$( "#dwn_notice" ).dialog( "open" );
	});	

	$( ".whatsthis" )
				.button()
				.click(function() {
				$( "#order_expire" ).dialog( "open" );
	});


	$( "#promo_btn" ).button();

	$( "#submit_email" )
				.button()
				.click(function() {
			//	$( "#email_notice" ).dialog( "open" );
	});

	
	$("#ship_zip").mask("99999");
	$("#location_phone").mask("(999) 999-9999");
	$("#phonefx").mask("(999) 999-9999");
	$("#faxfx").mask("(999) 999-9999");
	$('#download_check_by_fax').hide();


	$("#remove_items_form").change(function(){
	  var n = $("#remove_items_form input:checkbox:checked").length;
	  var a = $("#num_in_order").val();
	  if(n == a){
	   // they have selected all the items in their order, warn them that it ill erase their order
	   var warning = "Warning: You have selected your entire order. Deleting your entire order will drop all the information you have entered so far for this order including shipping and freight information. ";
	   $("#extra_warning").html(warning);
	
	  }
	});

}); // end