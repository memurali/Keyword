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
	<!-- SIGN UP MODAL BEGIN -->
    <div class="reveal small" id="signUp" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">		
          <div class="cell large-6" id="signup">
            <h2>Create Your Account Today!</h2>
            <p>Sign up for faster checkout and download your data anytime!</p>
            <form data-abide novalidate id="frmdata" name="frmdata" method="POST">
			<input type="hidden" id="controlaction" name="controlaction" value="<?php echo $action;?>"/>
              <div class="cell">
                <label id="namelbl">Name
                <input type="text" id="reg_name" name="reg_name" placeholder="Name" aria-describedby="suNhint" aria-errormessage="signupNameerror" required >
                <span class="form-error" id="reg_name_err">Required</span>
                </label>
              </div>
              <div class="cell">
                <label id="complbl">Company
                <input type="text" id="reg_comp" name="reg_comp" placeholder="Company" aria-describedby="suChint" aria-errormessage="signupCompanyerror" required >
                <span class="form-error" id="reg_comp_err">Required</span>
                </label>
              </div>
              <div class="cell">
                <label id="emaillbl">Email
                <input type="email" id="reg_email" name="reg_email" placeholder="Email" aria-describedby="suEhint" aria-errormessage="signupEmailerror" required >
                <span class="form-error" id="reg_email_err">Required</span>				
                </label>
              </div>
			  <div>
				<span class="form-error is-visible" id="error_msg"></span>
			  </div>
              <div class="cell">
                <label id="passlbl">Password
                <input type="password" id="reg_pwd" name="reg_pwd" placeholder="Password" aria-describedby="suPhint" aria-errormessage="signupEmailerror" required >
                <span class="form-error" id="reg_pwd_err">Required</span>
                </label>
              </div>
              <div class="cell">
                <label id="conpasslbl">Confirm Password
                <input type="password" id="reg_conpwd" name="reg_conpwd" placeholder="Confirm Password" aria-describedby="suCPhint" aria-errormessage="signupPasswordeerror" required >
                <span class="form-error" id="reg_conpwd_err">Passwords are supposed to match!</span>
                </label>
              </div>
              <div class="cell">
                <p><small>By creating an account, I agree to the <a href="#">Terms of Service</a> & <a href="#">Privacy Policy</a></small></p>
                <input class="button" type="button" id="reg_submit" value="Create Account">
                <div class="cell">
                  <p><small>Already have an account? <a href="#" data-open="signIn">Sign In!</a></small></p>
                </div>
              </div>
            </form>
          </div>
          <div class="cell large-6 info">
            <h1>Keyword GPS</h1>
            <ul>
              <li>Save Time</li>
              <li>No Keyword Research</li>
              <li>Clean & Curated KW Lists</li>
              <li>Use for SEO, SEM</li>
              <li>eCommerce Focus</li>
              <li>Digital marketers & Category Merchandisers</li>
            </ul>
          </div>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- SIGN UP MODAL END -->
	<!-- SIGN IN MODAL BEGIN -->
    <div class="reveal small" id="signIn" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell large-6" id="forgot_pwd">
            <h2>Welcome Back!</h2>
			<?php echo $this->Session->flash('auth'); ?>
           	<?php echo $this->Form->create('User',array('data-abide'=>'','novalidate')); ?>
              <div class="cell">
				<label>Email
					<input type="email" name='data[User][username]' id="signinEmail" placeholder="Email" aria-describedby="siEhint" aria-errormessage="signupEmailerror" required >
					<span class="form-error" id="signupName">Required</span>
                </label>
               </div>
              <div class="cell">
                <label>Password
					<input type="password" name='data[User][password]' id="signupConfirme" placeholder="Password" aria-describedby="siPhint" aria-errormessage="signupConfirmeerror" required >
					<span class="form-error" id="signupName">That password doesn't match! Try again?</span>
                </label>
              </div>
			  <div><span class="form-error is-visible" ><?php echo $loginerror; ?></span></div>
              <div class="cell">
				<button class="button" type="submit" value="Submit">Sign In</button>
              </div>
              <div class="cell">
                <p><small><a onclick="forgotpwd('emailform');">Forgot Your Password?</a></small></p>
              </div>
            <?php echo $this->Form->end(); ?>
          </div>
          <div class="cell large-6 image text-center">
            <h1></h1>
            <a href="#" data-open="signUp">Create An Account</a>
          </div>
        </div>  
      </div>
      <button class="close-button" id="signIn-close" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- SIGN IN MODAL END -->	
    <div class="title-bar" data-responsive-toggle="example-animated-menu" data-hide-for="medium">
      <button class="menu-icon" type="button" data-toggle></button>
      <div class="title-bar-title">Keyword GPS</div>
    </div>

    <div class="top-bar" id="example-animated-menu" data-animate="hinge-in-from-top spin-out">
      <div class="top-bar-left">
        <ul class="menu">
          <li class="menu-text hide-for-small-only"><?php echo $this->Html->link("Keyword GPS", array('controller' => 'Users','action' => 'index'));?></li>
        </ul>
      </div>
      <div class="top-bar-right">
        <ul class="dropdown menu" data-dropdown-menu>
          <li><?php echo $this->Html->link("About", array('controller' => 'users','action' => 'selection_subcat','slug' =>'about'));?></li>
          <li><?php echo $this->Html->link("Contact", array('controller' => 'users','action' => 'selection_subcat','slug' =>'contact'));?></li>
          <li><?php echo $this->Html->link("FAQ", array('controller' => 'users','action' => 'selection_subcat','slug' =>'faq'));?></li>
          <li>
            <div class="button-group small stacked-for-small">
			  <?php
			   if($userid=='')
			  {?>
				<a class="button" data-open="signUp">Sign Up</a>
			  	<a class="button hollow hide-for-small-only" data-open="signIn">Sign In</a>
			  <?php
			  }
			  else
			  {
				if($role=='admin')
					echo $this->Html->link("Dashboard", array('controller' => 'Users','action' => 'dash_users'),array('class'=>'button'));
				else	  
					echo $this->Html->link("Dashboard", array('controller' => 'users','action' => 'user_dashboard'),array('class'=>'button'));
			  }
			  ?>
			   <li>
		    <?php echo $this->Html->link($this->Html->tag('span', sizeof($cart_desc),array('id'=>'btncart')), array('controller' => 'payments',
					'action' => 'shopping_cart'),array('escape'=>false ,'class'=>'cart-notification'));?>
		  </li>
            </div>
          </li>
        </ul>
      </div>
    </div>
 