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
		echo $this->Html->script('datatables.min.js');
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
		});
	</script>
  </head>
  <body class="marketing">	
    <div class="hero">
      <div class="grid-container text-center">
        <div class="grid-x grid-padding-x">
          <div class="cell">
            <h1>About</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <section id="about">
        <div class="grid-container">
          <div class="grid-x grid-padding-x text-center">
            <div class="cell">
              <h2>Keyword GPS</h2>
            </div>
          </div>
          <div class="grid-x grid-padding-x center-grid">
            <div class="cell">
              <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
            </div>
          </div>
        </div>
      </section>
      <section id="easy">
        <div class="grid-container">
          <div class="grid-x grid-margin-x">
            <div class="cell medium-7 small-order-2 medium-order-1">
              <img src="<?php echo $this->webroot; ?>/assets/browser-example.png">
            </div>
            <div class="cell medium-5 small-order-1 medium-order-2">
              <h2 class="left-align">Making It Easy For You!</h2>
              <p>Donec ullamcorper nulla non metus auctor fringilla. Sed posuere consectetur est at lobortis. Vestibulum id ligula porta felis euismod semper. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
              <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus..</p>
            </div>
          </div>
        </div>
      </section>
      <section id="ceo">
        <div class="grid-container">
          <div class="grid-x grid-padding-x center-grid">
            <div class="cell">
              <div class="grid-x grid-padding-x">
                <div class="cell text-center">
                  <img src="<?php echo $this->webroot; ?>/assets/ceo.jpg">
                  <p class="ceo-quote">Houston Jayne</p>
                </div>
              </div>
            </div>
            <div class="cell">
              <blockquote>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vestibulum id ligula porta felis euismod semper. Maecenas sed diam eget risus varius blandit sit amet non magna.</blockquote>
            </div>
          </div>
        </div>
      </section>
      <section class="hide">
        <div class="grid-container">
          <div class="grid-x grid-padding-x small-up-1 medium-up-2 large-up-3">
            <div class="cell">
              <div class="callout">
                <h2>Save time</h2>
              </div>
            </div>
            <div class="cell">
              <div class="callout">
                <h2>No Keyword Research</h2>
              </div>
            </div>
            <div class="cell">
              <div class="callout">
                <h2>Clean & Curated KW Lists</h2>
              </div>
            </div>
            <div class="cell">
              <div class="callout">
                <h2>Use for SEO, SEM</h2>
              </div>
            </div>
            <div class="cell">
              <div class="callout">
                <h2>eCommerce Focus</h2>
              </div>
            </div>
            <div class="cell">
              <div class="callout">
                <h2>Digital marketers & Category Merchandisers</h2>
              </div>
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
    <!--<footer>
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell large-6">
           <?php echo $this->Html->link("Keyword GPS", array('controller' => 'Users','action' => 'index'),array('class'=>'logo'));?>
          </div>
          <div class="cell large-6">
            <ul class="menu align-right">
              <li><?php echo $this->Html->link("Home", array('controller' => 'Users','action' => 'index'));?></li>
              <li><?php echo $this->Html->link("About", array('controller' => 'Users','action' => 'about'));?></li>
              <li><?php echo $this->Html->link("Contact", array('controller' => 'Users','action' => 'contact'));?></li>
              <li><?php echo $this->Html->link("FAQ", array('controller' => 'Users','action' => 'faq'));?></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>--->
  </body>
</html>
