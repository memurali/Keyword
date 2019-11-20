<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keyword GPS</title>
    <?php
		
		echo $this->Html->css('foundation.css');
		echo $this->Html->css('app.css');
		echo $this->Html->css("common.css");
		
		echo $this->Html->script('jquery.js');
		echo $this->Html->script('what-input.js');
		echo $this->Html->script('foundation.js');
		echo $this->Html->script('app.js');
		echo $this->Html->script('common.js');
		echo $this->Html->script('common_function.js');
		if($userid!='')
			include('navigation_user.ctp');
		else
			include('navigation.ctp');
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
	} );
	</script>
  </head>
  <body id="faq" class="marketing">
    <div class="hero">
      <div class="grid-container text-center">
        <div class="grid-x grid-padding-x">
          <div class="cell">
            <h1>Contact</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <section id="faq">
        <div class="grid-container">
          <div class="grid-x grid-padding-x text-center">
            <div class="cell">
              <h2>Questions?</h2>
            </div>
          </div>
          <div class="grid-x grid-padding-x">
            <div class="cell large-8">
			   <form data-abide novalidate id="contactdata" name="contactdata" method="POST">
                <div class="grid-container">
                  <div class="grid-x grid-margin-x">
                    <div class="cell small-12">
                      <h3>Shoot Us A Message!</h3>
                      <label id="namelbl">Your Name
                        <input type="text" id="name" name="name" placeholder="Name" aria-describedby="example1Hint1" aria-errormessage="example1Error1" required>
                        <span class="form-error" id="name_err">
                          Please enter your name
                        </span>
                      </label>
                    </div>
                    <div class="cell small-12">
                      <label id="emaillbl">Your email
                        <input type="email" id="emailval" name="emailval" placeholder="Email" aria-describedby="example1Hint2" aria-errormessage="example1Error2" required >
                        <span class="form-error" id="email_err">
                          Please enter your email
                        </span>
                      </label>
                    </div>
					<div>
						<span class="form-error is-visible" id="error_msg"></span>
					</div>
                    <div class="cell small-12">
                      <label id="messagelbl">Your Message
                        <textarea id="message" name="message" placeholder="" aria-describedby="example1Hint3" aria-errormessage="example1Error3" required></textarea>
                        <span class="form-error" id="message_err">
                          Please enter your message
                        </span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="grid-container">
                  <div class="grid-x grid-margin-x">
                    <fieldset class="cell large-6">
                      <button class="button" type="button"  onclick="contact();" value="Submit">Sent Message</button>
                    </fieldset>
                  </div>
                </div>
              </form>
            </div>
            <div class="cell large-4">
              <h3>Contact Info</h3>
              <ul class="no-bullet contact-info">
                <li class="phone"><p>012-345-6789</p></li>
                <li class="email"><p>Email</p></li>
                <li class="location"><p>Location</p></li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <section class="search-callout">
        <div class="grid-container text-center">
		  <?php echo $this->Form->create(false, array(
						'url' => array('controller' => 'Users', 'action' => 'index'),
						'id' => 'searchbar',
						'method'=>'POST'
						));
		  ?>
          <div class="grid-x grid-padding-x search-container center-grid">
            <div class="cell">
              <p class="lead">Start Your Keyword Search!</p>
			  <div class="input-group">
              <input class="input-group-field" id="category_search" name="category_search" type="text" onkeyup="search(this.value);" onclick="search(this.value);" autocomplete='off'>
			  <input type='hidden' name='nodata' id='nodata' value=''>
			  <div class="suggestionsBoxcrp" id="suggestionscrp" style="display:none;margin-top: 50px;">
					<img src=<?php echo $this->webroot."/assets/upArrow.png"?> style="position: relative; top: -59px; left: -120px; alt="upArrow" />
					<div class="suggestionListcrp" id="autoSuggestionsListcrp">
						&nbsp;
					</div>
			  </div>
			  <div class="input-group-button">
                <!-- FORM SHOULD AUTOFILL -->
                <button  type='submit' class="button large" id='btnsearch'>Search</button>
              </div>
            </div>
            </div>
          </div>
		  <?php echo $this->Form->end(); ?>
          <div class="grid-x">
            <div class="cell">
              <?php echo $this->Html->link("Browse By Category", array('controller' => 'users','action' => 'selection_subcat','slug'=>'directory'),array('class'=>'browse'));?>
            </div>
          </div>
        </div>
      </section>
    </div>
	<?php include('footer.ctp'); ?>
  <!---<footer>
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell large-6">
           <a href="index.html" class="logo">Keyword GPS</a></li>
          </div>
          <div class="cell large-6">
            <ul class="menu align-right">
              <li><a href="#">Home</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Contact</a></li>
              <li><a href="#">FAQ</a></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>--->
  </body>
</html>
