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
		if($userid=='')	
		{
			include('navigation.ctp');
		}
		else
		{
			include('navigation_user.ctp');
		}
		
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
		$('#keyTable').DataTable({
			 "paging": false,
			 "searching": false,
			 "info":false,
			 "lengthChange": false
		});
		var i =1;
		$.each($('.reveal-overlay'), function (index, value) {
			$(this).attr('id', 'popup'+i);
			i++;
		});
	} );
	</script>
  </head>
  <body>
	<!--<div class="title-bar" data-responsive-toggle="example-animated-menu" data-hide-for="medium">
      <button class="menu-icon" type="button" data-toggle></button>
      <div class="title-bar-title">Menu</div>
    </div>---->
	<?php 
	echo $this->Form->create(false, array(
						'url' => array('controller' => 'Users', 'action' => 'index'),
						'id' => 'searchbar',
						'method'=>'POST'
	));
	?>
    <div class="hero">
      <div class="grid-container text-center">
        <div class="grid-x search-container">
          <div class="cell callout">		
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
      </div>
    </div>
	<?php echo $this->Form->end(); ?>
    <div class="content">
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell large-12 directory-nav">
            <h2>Directory</h2>
            <p>Please select a category</p>
          </div>
        </div>
        <div class="grid-x grid-padding-x grid-margin-y small-up-1 medium-up-3">
			<?php 
			if(count($data_cat)>0)
			{
				for($i=0; $i<count($data_cat);$i++)
				{
					$category = $data_cat[$i]['tblcategory']['Category'];
					$categoryid = $data_cat[$i]['tblcategory']['Categoryid'];					
					$arrcategory = explode("/",$category);
					$rep_category = $arrcategory[0];
					echo '<div class="cell">';
						echo $this->html->link($category,array('controller' => 'users', 'action' => 'selection_subcat','slug' =>strtolower($rep_category)));
					echo '</div>';
				}
			}?>
                      
        </div>
      </div>
    </div>
	<?php  include('footer.ctp'); ?>
  </body>
</html>
