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
  <body id="faq" class="marketing">	
    <div class="hero">
      <div class="grid-container text-center">
        <div class="grid-x grid-padding-x">
          <div class="cell">
            <h1>FAQ</h1>
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
            <div class="cell medium-4 small-order-2 medium-order-1">
              <h3>Looking For More?</h3>
              <p>Nullam quis risus eget urna mollis ornare vel eu leo. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet.</p>
			  <?php echo $this->Html->link("Contact Us", array('controller' => 'users','action' => 'selection_subcat','slug' => 'contact'),array('class'=>'button'));?>
            </div>
            <div class="cell medium-8 small-order-1 medium-order-2">
              <dl id="faq">
                <div class="set">
                  <dt><p class="lead">Pellentesque Vulputate Euismod Vestibulum Tellus?</p></dt>
                  <dd><p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vestibulum id ligula porta felis euismod semper. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam quis risus eget urna mollis ornare vel eu leo. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p></dd>
                </div>
                <div class="set">
                  <dt><p class="lead">Nullam Sit Sollicitudin?</p></dt>
                  <dd><p>Vestibulum id ligula porta felis euismod semper. Curabitur blandit tempus porttitor. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p></dd> 
                </div>
              </dl>
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
