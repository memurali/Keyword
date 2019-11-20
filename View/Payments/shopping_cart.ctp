<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
	<?php
		
		echo $this->Html->css('foundation.css');
		echo $this->Html->css('app.css');
		
		echo $this->Html->script('jquery.js');
		echo $this->Html->script('datatables.min.js');
		echo $this->Html->script('what-input.js');
		echo $this->Html->script('foundation.js');
		echo $this->Html->script('app.js');
		echo $this->Html->script('https://js.stripe.com/v2/');
		echo $this->Html->script('common.js');
		echo $this->Html->script('common_function.js');
	?>
	<script>
		$(document).ready(function() {
			<?php 
			if($loginerror!='')
			{?>
				$('.reveal-overlay').css('display','block');
				$('#signIn').css('display','block');
			<?php 
			}?>
			var i =1;
			$.each($('.reveal-overlay'), function (index, value) {
				$(this).attr('id', 'popup'+i);
				i++;
			});
			var pkey = $('#publickey').val();
			Stripe.setPublishableKey(pkey);
		});
	</script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
	<!----ALERT MODAL START-->
	 <div class="reveal tiny" id="response" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell">
            <h1 class="text-center">Alert!</h1>
			<p id="alert"></p>			 
          </div>
        </div>  
      </div>
      <button class="close-button" id="response-close" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
     </div>
	<!----ALERT MODAL END-->
    <div class="reveal tiny" id="completeOrder" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell">
            <h1 class="text-center">Thank You!</h1>
			<p id='pay_status'></p>
			<p>Your keywords are stored in your dashboard and you can download them there!</p>
			<?php echo $this->Html->link("Go To Dashboard", array('controller' => 'users','action' => 'user_dashboard'),array('class'=>'button expanded'));?>
          </div>
        </div>  
      </div>
      <button class="close-button" id="payment_thank_close" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php 
	
	?>
  </head>
  <body class="shopping-cart">
  <?php 
  if($userid!='')
  {
	include('navigation_user.ctp');
	$style = "block";
  }
  else
  {
	  include('navigation.ctp');
	  $style = "none"; 
  }?>
  <?php 
  
		if(array_sum($cart_price)>0)
		{?>
			<div class="content" >
			  <div class="grid-container">
				<div class="grid-x">
				  <div class="cell large-12">
					<br />
					<h2>Shopping Cart</h2>
					<br />
				  </div>
				</div>
				<div class="grid-x grid-padding-x">
				  <div class="cell large-8">
					<?php 
						if($exist=='yes')
						{
							$validate = '';
							$card_data = 'exist';
							$exist_radio = 'checked';
							$credit_radio = '';
						}
						else
						{
							$validate = 'novalidate';
							$card_data = 'new';
							$exist_radio = '';
							$credit_radio = 'checked';
						}
						echo $this->Form->create(false, array(
												'url' => array('controller' => 'Payments', 'action' => 'shopping_cart'),
												'id' => 'paymentFrm',
												'method'=>'POST',
												'data-abide'=>'',
												$validate));?>
					<!--<form data-abide novalidate>--->
					   <?php
					   if($card_data=='exist')
					   {?>
						<div class="callout" style="display:<?php echo $style;?>">
						  <div class="grid-x">
							<div class="cell">
							  <fieldset>
								<input type="radio" name="payment" value="ExhistingCC" id="exhistingCC" required <?php echo $exist_radio;?> onclick='cartradio_prev_check();'><label for="exhistingCC" class="title">Use Previous Credit Card Info</label>
							  </fieldset>
							</div>
						  </div>
						</div>
					   <?php
					   }
					   ?>
					  <div class="callout credit-card" style="display:<?php echo $style;?>">
						<div class="grid-x">
						  <div class="cell">
							<fieldset>
							  <input type="radio" name="payment" value="CreditCard" id="paymentCreditCard" required <?php echo $credit_radio;?> onclick='cartradiocheck();'><label class="title" for="paymentCreditCard">Credit</label>
							</fieldset>
						  </div>
						  <div class="cell">
							<label id='card_lbl'>Credit Card Number
							  <input type="number" id="ccNumber" name='card_num' class="card-number" placeholder="Credit Card Number" aria-describedby="ccNumberhint" aria-errormessage="ccNumbererror" required >
							  <span class="form-error" id="cardnum_id">Required</span>
							</label>
						  </div>
						</div>
						<div class="grid-x grid-margin-x">
						  <div class="cell large-6">
							<label>Name of Card
							  <input type="text" id="cardnum" placeholder="Name of card" aria-describedby="ccNumberhint" aria-errormessage="ccNumbererror" required >
							  <span class="form-error" id="signupName">Required</span>
							</label>
						  </div>
						  <div class="cell large-3">
							<label id='expdate_lbl'>Expiration Date
							  <input type="text" id="expdate" class="card-expiry" placeholder="MM/YYYY" aria-describedby="ccNumberhint" aria-errormessage="ccNumbererror" required >
							  <span class="form-error" id="expdate_id">Required</span>
							</label>
						  </div>
						  <div class="cell large-3">
							<label id='cvc_lbl'>CVV Number
							  <input type="text" id="cvvnum" name="cvc" class="card-cvc" placeholder="CVV Number" aria-describedby="ccNumberhint" aria-errormessage="ccNumbererror" required >
							  <span class="form-error" id="cvvnum_id">Required</span>
							</label>
						  </div>
						  <input type='hidden' name='customerid' id='customerid' value='<?php echo $customerid;?>'>
						  <input type="hidden" id="card_data" name="card_data" value="<?php echo $card_data;?>">
						  <input type='hidden' name='update_card' id='update_card' value=''>
						  <input type='hidden' name='publickey' id='publickey' value='<?php echo $publickey;?>'>
						</div>
					  </div>
					  <center>
						<div id="loading" style="display:none;">
						 <img src="<?php echo $this->webroot; ?>assets/Rolling-1s-57px.gif" />
						</div>
					  </center>
					  <div class="grid-x small-up-2">
						<div class="cell">
						  <?php echo $this->Html->link("Continue Shopping", array('controller' => 'users','action' => 'selection_subcat','slug'=>'directory'));?>
						</div>
						<?php
						if($style=='block')
						{?>
							<div class="cell text-right">
							  <a  id='payBtn' class="button" data-open=''>Complete Order</a>
							</div>
						<?php
						}
						else
						{
							  echo '<center>
								<a  id=secureBtn class=button data-open=signUp>Secure Checkout</a>
							</center>'; 
						}?>
						<br />
						<br />
					  </div>
					<?php echo $this->Form->end(); ?>
				  </div>
				  <div class="cell large-4">
					<div class="shopping-cart-info">
					  <h2>Summary Item <span class="cart-number"><?php echo sizeof($cart_desc);?></span></h2>
					  <ul class="menu vertical" id="ul_cart">
						<?php
						for($s=0; $s<sizeof($cart_desc); $s++)
						{
							echo '<li>'.ucfirst($cart_desc[$s]).'<span id="">'.number_format((float)$cart_price[$s], 2, '.', '').'</span><a onclick=removecart('.$s.')>Remove</a></li>';
						}
						?>
					   </ul>
					  <div class="grid-x medium-up-2">
						<div class="cell">
						  <p>Total</p>
						</div>
						<div class="cell text-right">
						  <p id="cart_price" class="price"><?php echo number_format(array_sum($cart_price),2, '.', '');?></p>
						</div>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		<?php 
		}
		else
		{
			echo '<p>Already purchased you can download from dashboard </p>';
			echo $this->Html->link("Go To Dashboard", array('controller' => 'users','action' => 'user_dashboard'),array('class'=>'button expanded'));
		}
	?>
	<div id='hidden'>
		<input type="hidden" name="action" id="action" value="<?php echo $action;?>">
		<input type="hidden" name="userid" id="userid" value="<?php echo $userid;?>">
	</div>
	<?php
	include('footer.ctp'); 
	?>
  </body>
</html>
