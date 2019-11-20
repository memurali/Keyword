//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
	if (response.error)
	{
		$('#popup1').css('display','block');
		$('#response').css("display","block");
		$('#alert').html("<label><center>"+response.error.message+"</center></label>");
		$('#payBtn').css("display", "inline-block");
		$('#loading').css("display", "none");
	} 
	else 
	{		
		var form$ = $("#paymentFrm");
		var token = response['id'];
		form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
		cartformsubmit();
	}
}		
function cartformsubmit()
{
	var formdata = $('#paymentFrm').serialize();
	$.ajax
	({
		url : 'shopping_cart',
		type: 'POST',
		async: false,
		data:{mode:'formsubmit',formvals:formdata},
		success : function(response)
		{
			$('#loading').css("display", "none");
			$('#popup2').css("display", "block");
			$('#completeOrder').css("display", "block");
			$('#payBtn').css("display", "inline-block");
			$('#pay_status').html(response);
		}
	});
}
function cartradiocheck()
{
	var card_data = $('#card_data').val();
	if(card_data=='exist')
	{
		$('#update_card').val('update');
		$('#card_data').val('new');
	}
}
function cartradio_prev_check()
{
	var card_data = $('#card_data').val();
	if(card_data=='new')
	{
		$('#update_card').val('');
		$('#card_data').val('exist');
	}
}
/***** for shopping_cart page ******/
function removecart(index)
{
	var indexval = index;
	$.ajax
	({
		url : 'shopping_cart',
		type: 'POST',
		async: false,
		data:{mode:'removecart',index:indexval},
		success : function(response)
		{
			$('#popup1').css('display','block');
			$('#response').css("display","block");
			$('#alert').html("<label><center>Removed successfully</center></label>");
			var json = jQuery.parseJSON(response); 
			var size = json.size;
			var lidata = json.litxt;
			var price = json.cartprice
			$(".cart-number").text(size);
			$("#btncart").text(size);
			$("#ul_cart").html(lidata);
			$("#cart_price").text(price);
		}
	});
}

/*** edit gateway *********/
function editgateway(id)
{
	var gatewayid = id;
	$.ajax
	({
		url : 'dash_gateway',
		type: 'POST',
		async: false,
		data:{mode:'edit',gatewayid:gatewayid},
		success : function(response)
		{
			var result = $.parseJSON(response);					
			$("#editgatewayName").val(result['gateway_name']);
			$("#edituserName").val(result['username']);
			$("#editPassword").val(result['password']);			
			if(result['active']=='checked')
			{
				$("#gatewaySwitch").attr('checked','checked');	
			}				
			if(result['active']=='')
			{
				$("#gatewaySwitch").attr('checked',false);
			}
				
			$("#editgatewayURL").val(result['gateway_url']);
			$("#editgatewayid").val(result['gateway_id']);
		}
	});
}

/**********cartdata filter by order admin***********/
function getcartdata_admin(id)
{
	var orderid = id;
	$.ajax
	({
		url : 'dash_orders',
		type: 'POST',
		async: false,
		data:{orderid:orderid},
		success : function(response)
		{
			var result = $.parseJSON(response);					
			$("#orderdata").html(result['response']);
			$(".noofkw").css('display',result['style']);
			$("#orderid").html(result['orderid']);
		}
	});
}

/**** for admin orders update expdate page ****/
function expdate_change(num)
{
	var date = $('#expdate'+num).val();
	var id = $('#orderid'+num).val();
	$.ajax
	({
		url : 'dash_orders',
		type: 'POST',
		async: false,
		data:{mode:'update_expdate',date:date,id:id},
		success : function(response)
		{
			$('#popup1').css("display", "block");
			$('#response').css("display","block");
			$('#alert').html('<label><center>'+response+'</center></label><br><center><button onclick=reloadfun(); class="button secondary">Ok</button></center>');
		}
	});
}	
/******* for admin users edit user*********/
function edituserdata(id)
{
	var userid =id;
	$.ajax
	({
		url : 'dash_users',
		type: 'POST',
		async: false,
		data:{mode:'edituser',userid:userid},
		success : function(response)
		{
			var result = $.parseJSON(response);					
			$("#usrname").val(result['usrname']);
			$("#usremail").val(result['usremail']);
			$("#usrcompany").val(result['usrcompany']);
			$("#usrid").val(result['usrid']);
			if(result['usract']=='checked')
			{
				$("#yes-no").attr('checked','checked');	
			}				
			if(result['usract']=='')
			{
				$("#yes-no").attr('checked',false);
			}
		}
	});
}
/*********** for price page***********/
//for rowspan
function mergerow(stop)
{
	if(stop==true)
	{
	   var span = 1;
	   var prevTD = "";
	   var prevTDVal = "";
	   $("#myTable tr td:first-child").each(function() { //for each first td in every tr
		  var $this = $(this);
		  if ($this.text() == prevTDVal) { // check value of previous td text
			 span++;
			 if (prevTD != "") {
				prevTD.attr("rowspan", span); // add attribute to previous td
				$this.remove(); // remove current td
			 }
		  } else {
			 prevTD     = $this; // store current td 
			 prevTDVal  = $this.text();
			 span       = 1;
		  }
	   });
	}			
  }
  //for filter dropdown
function removeduplicate()
{
	var usedNames = {};
	$("select[id='selectcat'] > option").each(function () {
		if(usedNames[this.text]) {
			$(this).remove();
		} else {
			usedNames[this.text] = this.value;
		}
	});
	var usedNames_sub = {};
	$("select[id='selectsubcat'] > option").each(function () {
		if(usedNames_sub[this.text]) {
			$(this).remove();
		} else {
			usedNames_sub[this.text] = this.value;
		}
	});
}
/**** category price page ****/
function cat_price(catid)
{
	var categoryid = catid;
	if(categoryid!='')
	{
		$('#tr_catprice').css('display','block');
		$.ajax
		({
			url : 'dash_config',
			type: 'POST',
			async: false,
			data:{mode:'category_price',catid:categoryid},
			success : function(response)
			{
				var json = jQuery.parseJSON(response); 
				var price = json.price;
				var priceid = json.priceid;
				$('#price_cat').val(price);
				$('#priceid_cat').val(priceid);
			}
		});
	}
	else
	{
		$('#tr_catprice').css('display','none');
		$('#price_cat').val('');
		$('#price_cat').removeAttr('required');
	}
}

function subcat_price(subcatid)
{
	var subcat_id = subcatid;
	if(subcat_id!='')
	{
		$('#tr_subcatprice').css('display','block');
		$.ajax
		({
			url : 'dash_config',
			type: 'POST',
			async: false,
			data:{mode:'subcategory_price',subcatid:subcat_id},
			success : function(response)
			{
				var json = jQuery.parseJSON(response); 
				var price = json.price;
				var priceid = json.priceid;
				$('#price_subcat').val(price);
				$('#priceid_subcat').val(priceid);
			}
		});
	}
	else
	{
		$('#tr_subcatprice').css('display','none');
		$('#price_subcat').val('');
		$('#price_subcat').removeAttr('required');
	}
}
/**********for user dashboard page get cartdata***********/
function getcartdata(id)
{
	var orderid = id;
	$.ajax
	({
		url : 'user_dashboard',
		type: 'POST',
		async: false,
		data:{orderid:orderid},
		success : function(response)
		{
			var result = $.parseJSON(response);					
			$("#cartdata").html(result['response']);
			$(".noofkw").css('display',result['style']);
			$("#orderid").html(result['orderid']);
		}
	});
}
function getsubcat(id)
{
	var idval = id;
	$.ajax
	({
		url : 'dash_view',
		type: 'POST',
		async: false,
		data:{mode:'get_subcat',catid:idval},
		success : function(response)
		{			
			$("#s_subcat").html(response);			
		}
	});
	
}
/*********** for view page add cat/subcat select mode******/
function selmode(mode)
{
	if(mode=='category')
	{
		$(".category").css('display','block');
		$(".subcategory").css('display','none');
	}
	else if(mode=='subcategory')
	{
		$(".category").css('display','none');
		$(".subcategory").css('display','block');
	}
}
/*********** for view page edit cat/subcatselect mode******/
function seleditmode(mode)
{
	if(mode=='category')
	{
		$(".editcategory").css('display','block');
		$(".editsubcategory").css('display','none');
	}
	else if(mode=='subcategory')
	{
		$(".editcategory").css('display','none');
		$(".editsubcategory").css('display','block');
	}
}
/*********** for view page edit keyword*********/
function edit(keyid)
{
	$.ajax
	({
		url : 'dash_view',
		type: 'POST',
		async: false,
		data:{mode:'editdata',keyidval:keyid},
		success : function(response)
		{
			$("#editdatafrm").html(response);
		}
	});
}
/*********** for view page update keyword*********/
function update()
{
	if($("#edit_keyword").val()=='')
	{
		$('#edit_keywordlbl').addClass('is-invalid-label');
		$('#edit_keyword').addClass('is-invalid-input');
		$('#edit_keyword_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	else if($("#edit_subcat").val()=='')
	{
		$('#edit_subcatlbl').addClass('is-invalid-label');
		$('#edit_subcat').addClass('is-invalid-input');
		$('#edit_subcat_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	else if($("#edit_g_vol").val()=='')
	{
		$('#edit_g_vollbl').addClass('is-invalid-label');
		$('#edit_g_vol').addClass('is-invalid-input');
		$('#edit_g_vol_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	else if($("#edit_us_vol").val()=='')
	{
		$('#edit_us_vollbl').addClass('is-invalid-label');
		$('#edit_us_vol').addClass('is-invalid-input');
		$('#edit_us_vol_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	else
	{
		var formdata = $("#editdatafrm").serialize();
		$.ajax
		({
			url : 'dash_view',
			type: 'POST',
			async: false,
			data:{mode:'update',editformval:formdata},
			success : function(response)
			{
				if(response=='')
				{
					$('#popup1').css("display", "block");
					$('#response').css("display","block");
					$('#popup1').css('z-index', '1');
					$('#popup4').css('z-index', '0');
					$('#alert').html('<label><center>Records are updated successfully</center></label><br><center><button onclick=reloadfun(); class="button secondary">Ok</button></center>');					
				}
				else
				{
					$('#popup1').css("display", "block");
					$('#response').css("display","block");
					$('#popup1').css('z-index', '1');
					$('#popup4').css('z-index', '0');
					$('#alert').html('<label><center>'+response+'</center></label>');
				}
			}
		});
	}
}
/*********** for view page get cat/subcat data*********/
function getcatdata(subcatid)
{
	$.ajax
	({
		url : 'dash_view',
		type: 'POST',
		async: false,
		dataType: 'text',
		data:{mode:'getcategory',subcatid:subcatid},
		success : function(response)
		{
			var json = jQuery.parseJSON(response); 
			var subcatid = json.editgetcat[0].s.Subcategoryid;
			var subcatval = json.editgetcat[0].s.Subcategory;
			var subcatactive = json.editgetcat[0].s.Active;
			var catid = json.editgetcat[0].c.Categoryid;
			var catval = json.editgetcat[0].c.Category;
			var catactive = json.editgetcat[0].c.Active;
			$("#edit_subcatid").val(subcatid);
			$("#edit_txtsubcat").val(subcatval);
			if(catactive=='Y')	
				$("#edit_active").attr('checked','checked');				
			else			
				$("#edit_active").attr('checked',false);

			if(subcatactive=='Y')			
				$("#edit_sub_active").attr('checked','checked');	
			else			
				$("#edit_sub_active").attr('checked',false);

			$("#edit_sel_cat").val(catid);
			$("#edit_catid").val(catid);
			$("#edit_txtcat").val(catval);
			
			
		}
	});	
}
/*********** for view page update cat/subcat data*********/
function update_catdata()
{
	if($("#editmodesel").val()=="")
	{
		$('#editmodesellbl').addClass('is-invalid-label');
		$('#editmodesel').addClass('is-invalid-input');
		$('#editmodesel_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	else if($("#editmodesel").val()=="category")
	{
		if($("#edit_txtcat").val()=="")
		{
			$('#edit_txtcatlbl').addClass('is-invalid-label');
			$('#edit_txtcat').addClass('is-invalid-input');
			$('#edit_txtcat_err').removeClass('form-error').addClass('form-error is-visible');
			return false;
		}
	}
	else if($("#editmodesel").val()=="subcategory")
	{
		if($("#edit_txtsubcat").val()=="")
		{
			$('#edit_txtsubcatlbl').addClass('is-invalid-label');
			$('#edit_txtsubcat').addClass('is-invalid-input');
			$('#edit_txtsubcat_err').removeClass('form-error').addClass('form-error is-visible');
			return false;
		}
		if($("#edit_sel_cat").val()=="")
		{
			$('#edit_sel_catlbl').addClass('is-invalid-label');
			$('#edit_sel_cat').addClass('is-invalid-input');
			$('#edit_sel_cat_err').removeClass('form-error').addClass('form-error is-visible');
			return false;
		}
	}
	var formdata = $("#editcatfrm").serialize();
	$.ajax
	({
		url : 'dash_view',
		type: 'POST',
		async: false,
		data:{mode:'update_cat',editcatform:formdata},
		success : function(response)
		{
			
			if(response.includes("Category")== true)
			{
				$('#popup1').css("display", "block");
				$('#response').css("display","block");
				$('#popup1').css('z-index', '1');
				$('#popup4').css('z-index', '0');
				$('#popup5').css('z-index', '0');
				$('#alert').html("<label><center>Category Updated successfully</center></label>");
				$("#edit_sel_cat").html(response);
			}
			else if(response.includes("Subcategory")== true)
			{
				$('#popup1').css("display", "block");
				$('#response').css("display","block");
				$('#popup1').css('z-index', '1');
				$('#popup4').css('z-index', '0');
				$('#popup5').css('z-index', '0');
				$('#alert').html("<label><center>Subcategory Updated successfully</center></label>");
				$("#edit_subcat").html(response);
			}
			else
			{
				$('#popup1').css("display", "block");
				$('#response').css("display","block");
				$('#popup1').css('z-index', '1');
				$('#popup4').css('z-index', '0');
				$('#popup5').css('z-index', '0');
				$('#alert').html("<label><center>"+response+"</label></center>");
			}
		}
	});
}
/************ get all category/subcategory**************/
function getall(type)
{
	var get = type;
	if(get =='Category')
	   mode='getallcategory';
    else
	  mode='getallsubcategory';
	$.ajax
	({
		url : 'dash_view',
		type: 'POST',
		async: false,
		data:{mode:mode},
		success : function(response)
		{
			if(get == 'Category')	
			$('#allcategory').html(response);
			if(get == 'Subcategory')
			$('#allsubcategory').html(response);
		}
	});
}

/*********** update the category*********/
function updatecat(id,val,check)
{
	var checked = check;
	$('#txtcategory').val(val);
	$('#txtcategoryid').val(id);
	if(checked =='Y')		
		$('#new_cat_active').prop('checked','checked');
	else
		$('#new_cat_active').prop('checked',false);
}
/*********** update the subcategory*********/
function updatesubcat(id,val,check)
{
	var subcatid = id;
	var checked = check;
	$.ajax
	({
		url : 'dash_view',
		type: 'POST',
		async: false,
		data:{mode:'getcatid',id:subcatid},
		success : function(response)
		{
			$('#txtsubcat').val(val);
			$('#txtsubcatid').val(id);
			$('#s_category').val(response);
			if(checked=='Y')
				$('#new_sub_active').prop('checked','checked');		
			else
				$('#new_sub_active').prop('checked',false);
		}
	});
	
	
}
/******* for activation code for sign up***********/
function act_submit()
{
	var ctrlaction = $('#controlaction').val();
	var url = geturl(ctrlaction);			
	var act_code = $("#act_code").val();
	if(act_code=='')
	{
		$("#msg_err").html("Enter the activation code");	
		return false;
	}
	$.ajax
	({
		url : url,
		type: 'POST',
		async: false,
		data:{fun:'signup',mode:'activate',param:act_code},
		success : function(response)
		{
			if(response.includes("Activated")== true)					
			{
				$("#msg").html("");
				$("#msg_err").html(response);				
				location.reload();
			}
			else
			{
				$("#msg_err").html(response);	
			}			
		}
	});
}
/**********forgot password***********/
function forgotpwd(act)
{
	var ctrlaction = $('#controlaction').val();
	var url = geturl(ctrlaction);
	var action = act;
	$.ajax
	({
		url : url,
		type: 'POST',
		async: false,
		data:{fun:'forgotpwd',mode:action,param:''},
		success : function(response)
		{	
			$("#forgot_pwd").html(response);
		}
	});
}
/**************** get temporary password***********/
function getpwd()
{
	var ctrlaction = $('#controlaction').val();
	var url = geturl(ctrlaction);
	var email = document.getElementById("email").value;	
	if(email=='')
	{
		$("#msg_err").html("Enter the email");	
		return false;
	}
	else
	{
		$.ajax
		({
			url : url,
			type: 'POST',
			async: false,
			data:{fun:'forgotpwd',mode:'get_password',param:email},
			success : function(response)
			{
				if(response.includes("Email Not Send")== false)
				{
					if(response.includes("Enter registered email")== false)							
					{						
						$("#forgot_pwd").html(response);
						$("#msg").html("Email sent successfully");
					}
					else
					{
						$("#msg_err").html(response);
					}
				}
				else
				{
					$("#msg_err").html(response);
				}
					
			}
		});
	}
}
/*************match password************/
function matchpwd()
{
	var ctrlaction = $('#controlaction').val();
	var url = geturl(ctrlaction);
	var pwd=document.getElementById("tmppwd").value;
	if(pwd=='')
	{
		$("#msg_err").html("Enter the password");
	}
	else
	{			
		$.ajax
		({
			url : url,
			type: 'POST',
			async: false,
			data:{fun:'forgotpwd',mode:'tmp_password',param:pwd},
			success : function(response)
			{	
				if(response.includes("Password not match")== false)	
				{
					$("#forgot_pwd").html(response);;
				}
				else
				{
					$("#msg_err").html(response);
				}					
			}
		});
	}
}
/*************reset password************/
function resetpwd()
{
	var ctrlaction = $('#controlaction').val();
	var url = geturl(ctrlaction);
	var pwd=$("#pwd").val();
	var conpwd=$("#confirmpwd").val();
	if(pwd=='')
	{
		$("#pwd_msg").html("Enter the password");
		return false;
	}
	if(conpwd=='')
	{
		$("#pwd_msg").html("");
		$("#conpwd_msg").html("Enter the confirm password");
		return false;
	}
	if(pwd!=conpwd)
	{
		$("#pwd_msg").html("");
		$("#conpwd_msg").html("Please Enter correct PassWord");
		return false;
	}
	else
	{
		$("#pwd_msg").html("");
		$("#conpwd_msg").html("");
		$.ajax
		({
			url : url,
			type: 'POST',
			async: false,
			data:{fun:'forgotpwd',mode:'reset_password',param:pwd},
			success : function(response)
			{
				if(response.includes("Password Reseted")== true) 
				{
					$("#pwd_msg").html("");
					$("#conpwd_msg").html("");
					$("#msg_err").html("Password reseted successfully");
					location.reload();
				}
				else if(response.includes("Password Not Reseted")== true) 
				{
					$("#pwd_msg").html("");
					$("#conpwd_msg").html("");
					$("#msg_err").html("Password recently used enter new password");
				}
			}
		});
	}
}

//required for signup,forgotpassword
function geturl(ctrlaction)
{
	if(ctrlaction=='index')
	{
		url = '';
		return url;
		return false;
	}
	if(ctrlaction=='category_landing')
	{
		url = "'" + ctrlaction + "'";
		return url;
		return false;
	}
	if(ctrlaction=='shopping_cart')
	{
		url = "'" + ctrlaction + "'";
		return url;
		return false;
	}
	if(ctrlaction=='directory'||'selection_subcat'||'about'||'contact'||'faq')
	{
		url = 'selection_subcat';
		return url;
		return false;
	}
}
//check email for signup
 function validateEmail(email)
 {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test(email);
}	

/*** update view_status  ***/
function testlogin()
{
	$.ajax
	({
		url : 'dash_orders',
		type: 'POST',
		async: false,
		data:{mode:'testlogin'},
		success : function(response)
		{
		}
	});
}

function proceedfun()
{
	$('#popup1').css('display','none');
	$('#response').css("display","none");
	$('#preview_div').css('display','none');
	$('#proceed_loading').css('display','block');
	setTimeout(function()
	{
		$.ajax
		({
			url : 'dash_import',
			type: 'POST',
			async: false,
			data:{proceed:'proceed'},
			success : function(response)
			{
				$('#preview_div').css('display','none');
				$('#proceed_loading').css('display','none');													
				$('#proceed_div').html(response);
			}					
		});
	}, 100);				
}	

function reloadfun()
{
	location.reload();
}

//contact
function contact()
{
	if($("#name").val()=='')
	{
		 $('#namelbl').addClass('is-invalid-label');
		 $('#name').addClass('is-invalid-input');
		 $('#name_err').removeClass('form-error').addClass('form-error is-visible');
		 return false;
	}
	if($("#emailval").val()=='')
	{
		 $('#emaillbl').addClass('is-invalid-label');
		 $('#emailval').addClass('is-invalid-input');
		 $('#email_err').removeClass('form-error').addClass('form-error is-visible');
		 return false;
	}
	if(!validateEmail($("#emailval").val()))
	{
		 $('#emaillbl').addClass('is-invalid-label');
		 $('#emailval').addClass('is-invalid-input');
		 $('#email_err').removeClass('form-error').addClass('form-error is-visible');
		 return false
	}
	if($("#message").val()=='')
	{
		 $('#messagelbl').addClass('is-invalid-label');
		 $('#message').addClass('is-invalid-input');
		 $('#message_err').removeClass('form-error').addClass('form-error is-visible');
		 return false;
	}
	else
	{
		var formdata = $("#contactdata").serialize();				
		$.ajax
		({
			url : 'contact',
			type: 'POST',
			async: false,					
			data:{mode:'contact',formval:formdata},
			success : function(response)
			{
				if(response.includes('Email sent successfully')==true)
				{					
					$('#popup1').css('display','block');
					$('#response').css("display","block");
					$('#alert').html('<label><center>'+response+'</center></label><br><center><button onclick=reloadfun(); class="button secondary">Ok</button></center>');
				}
				else
					$('#error_msg').html(response)
			}
		});	
	}
}
function configsave()
{
	if($('#s_category').val()!='')
	{
		if($('#price_cat').val()=='' ) 
		{
			$('#price_catlbl').addClass('is-invalid-label');
			$('#price_cat').addClass('is-invalid-input');
			$('#price_cat_err').removeClass('form-error').addClass('form-error is-visible');
			return false;
		}
		if($('#price_cat').val()<=0)
		{
			$('#price_catlbl').addClass('is-invalid-label');
			$('#price_cat').addClass('is-invalid-input');
			$('#price_cat_err').removeClass('form-error').addClass('form-error is-visible');
			return false;
		}
	}
	if($('#s_subcategory').val()!='')
	{
		if($('#price_subcat').val()=='' ) 
		{
			$('#price_subcatlbl').addClass('is-invalid-label');
			$('#price_subcat').addClass('is-invalid-input');
			$('#price_subcat_err').removeClass('form-error').addClass('form-error is-visible');
			return false;
		}
		if($('#price_subcat').val()<=0)
		{
			$('#price_subcatlbl').addClass('is-invalid-label');
			$('#price_subcat').addClass('is-invalid-input');
			$('#price_subcat_err').removeClass('form-error').addClass('form-error is-visible');
			return false;
		}
	}
	if($('#price').val()=='' ) 
	{
		$('#pricelbl').addClass('is-invalid-label');
		$('#price').addClass('is-invalid-input');
		$('#price_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	if($('#price').val()<=0)
	{
		$('#pricelbl').addClass('is-invalid-label');
		$('#price').addClass('is-invalid-input');
		$('#price_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	if($('#config').val()=='' ) 
	{
		$('#configlbl').addClass('is-invalid-label');
		$('#config').addClass('is-invalid-input');
		$('#config_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	if($('#config').val()<=0)
	{
		$('#configlbl').addClass('is-invalid-label');
		$('#config').addClass('is-invalid-input');
		$('#config_err').removeClass('form-error').addClass('form-error is-visible');
		return false;
	}
	else
	{
		$('#dash_configForm').submit();
	}
	
}
function search(inputstr)
{
	var ctrlaction = $('#controlaction').val();
	if(ctrlaction=='index'||'directory'||'selection_subcat'||'about'||'contact'||'faq')
	{
		url = '';
	}
	if(ctrlaction=='category_landing')
	{
		url = "'" + ctrlaction + "'";
	}	
	if(inputstr=='search_click')
	{
		inputstr = $('#category_search').val();
	}
	$.ajax
	({
		url : url,
		type: 'POST',
		async: false,					
		data:{mode:'smartsearch',textval:inputstr},
		success : function(response)
		{
			if(response.includes("No data found")== true)
			{
				$('#nodata').val('Value');
			}
			else
			{
				$('#nodata').val('');
			}
			$('#suggestionscrp').show();
			$('#autoSuggestionsListcrp').html(response);
		}
	});
}
function select_text(textval)
{
	var inputval = textval;
	var ind = textval.indexOf('/');
	if(ind!='-1')
	{
		var text_arr = textval.split('/');
		var  inputval= text_arr[0];
	}
	$("#category_search").val(inputval);
	setTimeout(function()
	{
		$('#searchbar').submit();
	}, 1000);
}

function addtocart()
{
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
			data:{mode:'checkaddtocart',formvals:formdata},
			success : function(response)
			{
				var json = jQuery.parseJSON(response); 
				var cartcheck = json.cartnotify;
				if(cartcheck=="Already added in cart")
				{
					$('#btnaddtocart').html(cartcheck);
				}
			}
		});
	}
				
			
}
