	$(function(){	
		var delay = (function(){
			var timer = 0;
			return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
			};
      	})();
		
      	$("#signIn-close").click(function() {
			$('.reveal-overlay').css('display','none');
			$('#signIn').css('display','none');
		});
		
		$("#response-close").click(function() {
			$('#popup1').css('display','none');
			$('#response').css('display','none');
		});		
		
		/**** for selection page ****/
		$('#subcategory_select').keyup(function() {
			delay(function(){
				$('#searchbar').submit();
			}, 1000 );
      	});
		
		/**** for category_landing page ****/
		$('#landing_search').keyup(function() {
			delay(function(){
				$('#searchbar').submit();
			}, 1000 );
      	});
		
		/**** for payment section ****/
		$("#payment_thank_close").click(function(event) {
			$('#popup2').css("display", "none");
			$('#completeOrder').css("display", "none");
			location.reload();
		});		

		$("#payBtn").click(function(event) {
			if($('#card_data').val()=='new')
			{
				//create single-use token to charge the user
				if($('.card-number').val()=='')
				{
					$('#card_lbl').addClass('is-invalid-label');
					$('#ccNumber').addClass('is-invalid-input');
					$('#cardnum_id').removeClass('form-error').addClass('form-error is-visible');
					$('#payBtn').css("display", "inline-block");
					return false;
				}
				if($('.card-expiry').val()=='')
				{	
					$('#expdate_lbl').addClass('is-invalid-label');
					$('#expdate').addClass('is-invalid-input');
					$('#expdate_id').removeClass('form-error').addClass('form-error is-visible');
					return false;
				}
				if($('.card-cvc').val()=='')
				{
					$('#cvc_lbl').addClass('is-invalid-label');
					$('#cvvnum').addClass('is-invalid-input');
					$('#cvvnum_id').removeClass('form-error').addClass('form-error is-visible');
					$('#payBtn').css("display", "inline-block");
					return false;
				}
				$('#payBtn').css("display", "none");
				var expiry_val = $('.card-expiry').val();
				var ind = expiry_val.indexOf('/');
				if(ind!='-1')
				{
					$('#loading').css("display", "block");
					var expiry_arr = expiry_val.split('/');
					var expmonth = expiry_arr[0];
					var expyear = expiry_arr[1];
					Stripe.createToken({
						number: $('.card-number').val(),
						cvc: $('.card-cvc').val(),
						exp_month: expmonth,
						exp_year: expyear
					}, stripeResponseHandler);
								
					//submit from callback
					//return false;
				}
				else
				{
					$('#popup1').css('display','block');
					$('#response').css("display","block");
					$('#alert').html('<label><center>Expiry month year must be in MM/YYYY format</center></label>');
					$('#payBtn').css("display", "inline-block");
					$('#loading').css("display", "none");
					$('#payBtn').css("display", "inline-block");
					return false;
				}
			}
			else
			{
				$('#loading').css("display", "block");
				setTimeout(function()
				{
					cartformsubmit();
				}, 1000);
				
			}
		});
		
				
		/**** for landing page **********/
		$('#btnaddtocart').click(function()
		{
			if(this.text!='Already added in cart')
			{
				var userid = $("#userid").val();
				var totprice = $('#totprice').val();
				var action = $('#action').val();
				var url = "'" + action + "'";
				if(totprice>0)
				{
					var formdata = $("#landingfrm").serialize();
					$.ajax
					({
						url : url,
						type: 'POST',
						async: false,
						data:{mode:'addtocart',formvals:formdata},
						success : function(response)
						{
							var json = jQuery.parseJSON(response); 
							var size = json.size;
							var cartcheck = json.cartnotify;
							if(cartcheck!= undefined)
							{
								$('#popup1').css('display','block');
								$('#response').css("display","block");
								$('#alert').html('<label><center>'+cartcheck+'</center></label>');
						
								
							}
							else
							{
								$('#popup1').css('display','block');
								$('#response').css("display","block");
								$('#alert').html('<label><center>Successfully added in cart</center></label>');
							}
							$("#btncart").text(size);
							
						}
					});
				}
				else
				{
					$('#popup1').css('display','block');
					$('#response').css("display","block");
					$('#alert').html('<label><center>Price must be greater than 0</center></label>');
				}
				
			}
		});
		
		
		/* ******* for signup*********/
		$('#reg_submit').click(function(){
			var ctrlaction = $('#controlaction').val();
			var url = geturl(ctrlaction);
			if($("#reg_name").val()=='')
			{
				 $('#namelbl').addClass('is-invalid-label');
				 $('#reg_name').addClass('is-invalid-input');
				 $('#reg_name_err').removeClass('form-error').addClass('form-error is-visible');
				 return false;
			}
			if($("#reg_comp").val()=='')
			{
				 $('#complbl').addClass('is-invalid-label');
				 $('#reg_comp').addClass('is-invalid-input');
				 $('#reg_comp_err').removeClass('form-error').addClass('form-error is-visible');
				 return false;
			}
			if($("#reg_email").val()=='')
			{
				 $('#emaillbl').addClass('is-invalid-label');
				 $('#reg_email').addClass('is-invalid-input');
				 $('#reg_email_err').removeClass('form-error').addClass('form-error is-visible');
				 return false;
			}
			if(!validateEmail($("#reg_email").val()))
			{
				 $('#emaillbl').addClass('is-invalid-label');
				 $('#reg_email').addClass('is-invalid-input');
				 $('#reg_email_err').removeClass('form-error').addClass('form-error is-visible');
				 return false;
			}
			if($("#reg_pwd").val()=='')
			{
				 $('#passlbl').addClass('is-invalid-label');
				 $('#reg_pwd').addClass('is-invalid-input');
				 $('#reg_pwd_err').removeClass('form-error').addClass('form-error is-visible');
				 return false;
			}			
			var pwd=$("#reg_pwd").val();
			var conpwd=$("#reg_conpwd").val();
			if($("#reg_conpwd").val()=='')
			{
				 $('#conpasslbl').addClass('is-invalid-label');
				 $('#reg_conpwd').addClass('is-invalid-input');
				 $('#reg_conpwd_err').removeClass('form-error').addClass('form-error is-visible');
				 return false;
			}
			if(pwd!=conpwd)
			{
				 $('#conpasslbl').addClass('is-invalid-label');
				 $('#reg_conpwd').addClass('is-invalid-input');
				 $('#reg_conpwd_err').removeClass('form-error').addClass('form-error is-visible');
				 return false;
			}
			else
			{
				var formdata = $("#frmdata").serialize();				
				$.ajax
				({
					url : url,
					type: 'POST',
					async: false,					
					data:{fun:'signup',mode:'email',param:formdata},
					success : function(response)
					{
						if(response.includes("Email Not Send")== false)
						{
							if(response.includes("Email Address Already Exists...")== false)							
							{								
								$("#signup").html(response);
								$("#msg").html("Email sent successfully");
							}
							else
							{								
								$('#error_msg').html(response)
							}
						}
						else
						{	
							$("#error_msg").html(response);						
						}
					}
				});	
			}			
			
		});
			
		$('#reg_email').on('change', function() {
			var ctrlaction = $('#controlaction').val();
			var url = geturl(ctrlaction);
			var emailval = this.value;
			if(emailval!='')
			{
				$.ajax
				({
					url : url,
					type: 'POST',
					async: false,
					data:{fun:'signup',mode:'emailavail',param:emailval},
					success : function(response)
					{
						if(response.includes("Email Not Send")== false)
						{
							if(response.includes("Not exist")== false)							
							{
								$("#signup").html(response);
								$("#msg").html('You have already registered,Activation code sent to your mail');	
							}
						}						
					}
				});
			}
		});

		/******* Config search **********/
		  $("#config_search").on('keyup',function() {
			var search_val = $("#config_search").val();
			setTimeout(function()
			{				
				$.ajax
					({
						url : 'dash_config',
						type: 'POST',
						async: false,
						data:{mode:'search', searchval:search_val},
						success : function(response)
						{
							$("#tblfilter").html(response);
							mergerow(true);														
						}
					});
			},300);
		});
		$("#btnconfig_search").on('keyup',function() {	
			var search_val = $("#config_search").val();
			setTimeout(function()
			{				
				$.ajax
					({
						url : 'dash_config',
						type: 'POST',
						async: false,
						data:{mode:'search', searchval:search_val},
						success : function(response)
						{
							$("#tblfilter").html(response);
							mergerow(true);														
						}
					});
			},300);	
		});
		
		/******* for admin user update ********/
		$("#update_user").click(function() {
			var userid =$('#usrid').val();
			var formdata = $("#edituserfrm").serialize();
			$.ajax
			({
				url : 'dash_users',
				type: 'POST',
				async: false,
				data:{mode:'updateuser',userid:userid,formvals:formdata},
				success : function(response)
				{
					$('#popup1').css('display','block');
					$('#popup1').css('z-index', '1');
					$('#popup2').css('z-index', '0');
					$('#response').css("display","block");
					$('#alert').html('<label><center>Updated Successfully</center></label><br><center><button onclick=reloadfun(); class="button secondary">Ok</button></center>');
				}
			});
		});	
		/******* for admin import ********/
		/********** for preview import data******/
		$("#csvimport").on("submit", function(){
			$('#preview_loading').css('display','block');
			$('#geterror').css('display','none');
			$('#preview_div').css('display','none');
			$('#proceed_div').css('display','none');			
		 });
		 
		/********** for proceed import data******/
		$('#btnproceed').click(function()
		{
			$('#popup1').css('display','block');
			$('#response').css("display","block");
			$('#alert').html('<label><center>Are you want to save the data?</center></label><br><center><button onclick=proceedfun(); class="button secondary">Ok</button></center>');
		});
		/********* for price page*********/
		$('#selectcat').change(function() {
			var sel_val = $('#selectcat option:selected').text();
			if(sel_val!='')
			{
				$.ajax
				({
					url : 'dash_config',
					type: 'POST',
					async: false,
					data:{mode:'cat_sort', sortval:sel_val},
					success : function(response)
					{
						$("#tblfilter").html(response);
						mergerow(true);														
					}
				});
			}					
		});	

		$('#selectsubcat').change(function() {	
			var sel_val = $('#selectsubcat option:selected').text();
			if(sel_val!='')
			{
				$.ajax
				({
					url : 'dash_config',
					type: 'POST',
					async: false,
					data:{mode:'subcat_sort', sortval:sel_val},
					success : function(response)
					{																	
						$("#tblfilter").html(response);
						mergerow(true);
					}
				});
			}			
		});
		
		/************** for view page delete all keyword**********/
		$('#selectAll').on('click', function(){
			var table = $('#datatbl').DataTable();
			var rows = table.rows({ 'search': 'applied' }).nodes();
			$('input[type="checkbox"]', rows).prop('checked', this.checked);
		});
		/************** for view page delete particular keyword**********/
		$('#btndelete').click(function(){
			if($("#selectAll").prop("checked") == true)
			{				
				mode = 'deleteall';	
			}
			else
			{
				mode = 'delete';	
			}
			var oTable = $('#datatbl').dataTable();
			var checkValues = [];
			var rowcollection =  oTable.$(".call-checkbox:checked", {"page": "all"});
			rowcollection.each(function(index,elem){
				var checkbox_value = $(elem).val();
				checkValues.push(checkbox_value);
				
			});
			if(checkValues=='')
			{
				$('#popup1').css('display','block');
				$('#response').css("display","block");
				$('#alert').html('<label><center>Check checkboxes for delete</center></label>');
				return false;
			}
			else
			{
				$.ajax
					({
						url : 'dash_view',
						type: 'POST',
						async: false,
						data:{mode:mode,keyids:checkValues},
						success : function(response)
						{
							$('#popup1').css('display','block');
							$('#response').css("display","block");
							$('#alert').html('<label><center>Records are deleted successfully</center></label><br><center><button onclick=reloadfun(); class="button secondary">Ok</button></center>');
						}
						
					});
					
				
			}
		});
		
		/********* for view page save new keyword*********/
		$('#btnsave').click(function()
		{
			if($("#new_keyword").val()=='')
			{
				$('#new_keywordlbl').addClass('is-invalid-label');
				$('#new_keyword').addClass('is-invalid-input');
				$('#new_keyword_err').removeClass('form-error').addClass('form-error is-visible');
				return false;
			}
			else if($("#s_cat").val()=='')
			{
				$('#s_catlbl').addClass('is-invalid-label');
				$('#s_cat').addClass('is-invalid-input');
				$('#s_cat_err').removeClass('form-error').addClass('form-error is-visible');
				return false;
			}
			else if($("#s_subcat").val()=='')
			{
				$('#s_subcatlbl').addClass('is-invalid-label');
				$('#s_subcat').addClass('is-invalid-input');
				$('#s_subcat_err').removeClass('form-error').addClass('form-error is-visible');
				return false;
			}
			else if($("#new_g_vol").val()=='')
			{				
				$('#new_g_vollbl').addClass('is-invalid-label');
				$('#new_g_vol').addClass('is-invalid-input');
				$('#new_g_vol_err').removeClass('form-error').addClass('form-error is-visible');
				return false;
			}
			else if($("#new_us_vol").val()=='')
			{
				$('#new_us_vollbl').addClass('is-invalid-label');
				$('#new_us_vol').addClass('is-invalid-input');
				$('#new_us_vol_err').removeClass('form-error').addClass('form-error is-visible');
				return false;
			}
			else
			{
				var formdata = $("#newdatafrm").serialize();
				$.ajax
				({
					url : 'dash_view',
					type: 'POST',
					async: false,
					data:{mode:'newkeyword',keyvals:formdata},
					success : function(response)
					{
						if(response.includes("exist")== true)
						{
							$('#new_keywordlbl').addClass('is-invalid-label');
							$('#new_keyword').addClass('is-invalid-input');
							$('#new_keyword_err').removeClass('form-error').addClass('form-error is-visible');
							$('#new_keyword_err').text(response);
						}
						else
						{
							$('#popup1').css('display','block');
							$('#response').css("display","block");
							$('#popup1').css('z-index', '1');
							$('#popup2').css('z-index', '0');
							$('#alert').html('<label><center>Records are saved successfully</center></label><br><center><button onclick=reloadfun(); class="button secondary">Ok</button></center>');
						}
					}
				});
				
			}
		});
		/******** for view page save new category****/
		$('#btncatsave').click(function()
		{	
			if($("#txtcategory").val()=='')
			{
				$('#txtcategorylbl').addClass('is-invalid-label');
				$('#txtcategory').addClass('is-invalid-input');
				$('#txtcategory_err').removeClass('form-error').addClass('form-error is-visible');
				return false;
			}				
			else
			{
				var formdata = $("#newcatfrm").serialize();
				$.ajax
				({
					url : 'dash_view',
					type: 'POST',
					async: false,
					data:{mode:'newcat',formval:formdata},
					success : function(response)
					{
						var json = jQuery.parseJSON(response);						
						if(json.catdrop!='')
						{					
							$('#popup1').css('display','block');
							$('#response').css("display","block");
							if($("#txtcategoryid").val()=='')
								$('#alert').html("<label><center>Category saved successfully</center></label>");
							else
								$('#alert').html("<label><center>Category and its Subcategory updated successfully</center></label>");
							$('#response-close').attr('id','popup1-cat');
							$("#s_category").html(json.catdrop);
							$("#s_cat").html(json.catdrop);
							$("#s_subcat").html('');
							$('#txtcategory').val('');
							$('#txtcategoryid').val('');
							$("#new_cat_active").prop('checked',false);
							$("#allcategory").html(json.allcat);
							$('#popup1').css('z-index', '1');
							$('#popup2').css('z-index', '0');
							$('#popup3').css('z-index', '0');
						}
						else
						{
							$('#popup1').css('display','block');
							$('#response').css("display","block");
							$('#alert').html(json.msg);
							$('#popup1').css('z-index', '1');
							$('#popup2').css('z-index', '0');
							$('#popup3').css('z-index', '0');
						}
						
					}
				});
				
			}
		});	
		
		/******** for view page save new subcategory****/
		$('#btnsubcatsave').click(function()
		{	
			if($("#s_category").val()=='')
			{
				$('#s_categorylbl').addClass('is-invalid-label');
				$('#s_category').addClass('is-invalid-input');
				$('#s_category_err').removeClass('form-error').addClass('form-error is-visible');
				return false;
			}		
			else if($("#txtsubcat").val()=='')
			{
				$('#txtsubcatlbl').addClass('is-invalid-label');
				$('#txtsubcat').addClass('is-invalid-input');
				$('#txtsubcat_err').removeClass('form-error').addClass('form-error is-visible');
				return false;
			}
			else
			{
				var formdata = $("#newsubcatfrm").serialize();
				$.ajax
				({
					url : 'dash_view',
					type: 'POST',
					async: false,
					data:{mode:'newsubcat',formval:formdata},
					success : function(response)
					{						
						var json = jQuery.parseJSON(response);
						if(json.subcatdrop!='')
						{					
							$('#popup1').css('display','block');
							$('#response').css("display","block");
							if($("#txtsubcatid").val()=='')
								$('#alert').html("<label><center>Subcategory saved successfully</center></label>");
							else
								$('#alert').html("<label><center>Subcategory updated successfully</center></label>");
							$('#response-close').attr('id','popup1-subcat');
							$("#s_cat").html(json.catdrop);
							$("#s_subcat").html('');
							$('#txtsubcat').val('');
							$('#txtsubcatid').val('');
							$('#s_category').val('');							
							$("#new_sub_active").prop('checked',false);
							$("#allsubcategory").html(json.allsubcat);
							$('#popup1').css('z-index', '1');
							$('#popup2').css('z-index', '0');
							$('#popup6').css('z-index', '0');
						}
						else
						{
							$('#popup1').css('display','block');
							$('#response').css("display","block");
							$('#alert').html(response);
							$('#popup1').css('z-index', '1');
							$('#popup2').css('z-index', '0');
							$('#popup6').css('z-index', '0');
						}
						
					}
				});				
			}
		});		
		
	});

	
	
	
	