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
		if($loginerror!='')
		{
		?>
			<script>
			$(document).ready(function() {
				$('.reveal-overlay').css('display','block');
				$('#signIn').css('display','block');
			} );
			</script>
		<?php 
		} 
		  include('navigation.ctp'); 
		?>
  </head>
  <body class="home marketing">
	<div class="hero">
      <div class="grid-container text-center">
        <div class="grid-x grid-padding-x">
          <div class="cell">
            <h1>Keyword Research Made Easy</h1>
            <p class="lead">We do the work for you so you can get on with your job.</p>
          </div>
        </div>
		<?php echo $this->Form->create(false, array(
						'url' => array('controller' => 'Users', 'action' => 'index'),
						'id' => 'searchbar',
						'method'=>'POST'
						));
		?>
		  <div class="grid-x grid-padding-x search-container">
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
		<?php echo $this->Form->end(); 
		if($loginerror!='')
		{
			echo $loginerror;
		}
		?>
        
        <div class="grid-x grid-padding-x">
          <div class="cell">
            <?php echo $this->Html->link("Browse By Category", array('controller' => 'users','action' => 'selection_subcat','slug'=>'directory'));
			?>
          </div>
        </div>  
        <div class="grid-x grid-padding-x">
          <div class="cell" style="height: 450px; overflow: hidden">
            <img src="https://assets.materialup.com/uploads/e7f1215f-664d-42f0-a540-87a03465ab8e/attachment.png"> 
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <section id="benefits">
        <div class="grid-container">
          <div class="grid-x grid-padding-x text-center">
            <div class="cell">
              <h2>Digital Marketers & Merchandisers</h2>
            </div>
          </div>
          <div class="grid-x grid-padding-x small-up-1 medium-up-2 large-up-4 text-center">
            <div class="cell top-keywords">
              <div class="callout">
                <p>Discover the <strong>top keywords</strong> customers use to search for your products.</p>
              </div>
            </div>
            <div class="cell save-time">
              <div class="callout">
                <p><strong>Save time</strong> researching keywords for SEO, SEM and internal search.</p>
              </div>
            </div>
            <div class="cell best-keywords">
              <div class="callout">
                <p>Get the <strong>best keywords</strong> that been cleaned and scrubbed for immediate use.</p>
              </div>
            </div>
            <div class="cell e-commerce">
              <div class="callout">
                <p>Keywords are organized into <strong>eCommerce</strong> categories for easy access.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section id="users">
        <div class="grid-container">
          <div class="grid-x grid-padding-x grid-margin-x"">
            <div class="cell large-7">
              <div class="content">
                <h2>Who Uses Our Keyword Lists?</h2>
                <ul><p class="lead">Digital marketers in SEO and SEM</p>
                  <li><p>Get your paid search campaigns setup quickly with pre-selected keyword lists.</p></li>
                  <li><p>Align your SEO strategy to the right topics &  keywords in highest demand.</p></li>
                </ul>
                <ul><p class="lead">Category Merchandisers</p>
                  <li><p>Discover how your customers search for your products with the keywords that are most popular. </p></li>
                </ul>
                <ul><p class="lead">Internal Search & Product Teams</p>
                  <li><p>Optimize your product recall and improve conversion by making sure customers are finding what they are looking for when doing searches on your site. </p></li>
                </ul>
              </div>
            </div>
            <div class="cell large-5 image">
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
      <section id="keywordGroup">
        <div class="grid-container">
          <div class="grid-x">
            <div class="cell text-center">
              <h2>Popular Keyword Group</h2>
            </div>
          </div>
          <div class="grid-x grid-padding-x small-up-1 medium-up-2 large-up-4" data-equalizer data-equalize-on="medium">
			<?php
				if(!empty($data_cat))
				{
					for($i=0; $i<count($data_cat); $i++)
					{
						$category = $data_cat[$i]['tblcategory']['Category'];
						$categoryid = $data_cat[$i]['tblcategory']['Categoryid'];
						?>
						<div class="cell">
						  <div class="callout" data-equalizer-watch>
							<img src="https://images.unsplash.com/photo-1546715399-bce8eb8f1d84?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1651&q=80">
							<div class="content">
							  <span id="parentCategory"><?php echo $category;?></span>
								<?php
								$k=1;
								for($j=0; $j<count($alldata_arr); $j++)
								{
									$subcategory = $alldata_arr[$j]['s']['Subcategory'];
									$subcategoryid = $alldata_arr[$j]['s']['Subcategoryid'];
									$newcatid = $alldata_arr[$j]['c']['Categoryid'];
									if($categoryid == $newcatid)
									{
										echo '<ul>';
											if($k<5)
											{
												echo '<li>';
													echo $this->Html->link($subcategory,array('controller' => 'users','action' => 'category_landing','param'=>'search','param1' => strtolower($subcategory)));
												
												
												echo '</li>';
											}
											else
											{
												echo '<li id=viewMore>';
													echo $this->Html->link('View More',array('controller' => 'users','action' => 'selection_subcat','slug' => 'directory'),array('class'=>'label expanded'));
												
												
												echo '</li>'; 
												break;
											}
										echo '</ul>';
										$k++;
									}
									
								}?>
							</div>
						  </div>
						</div>
						<?php
					}
				}
			?>
          </div>
          <div class="grid-x">
            <div class="cell text-center">
              <?php echo $this->Html->link("Browse By Category", array('controller' => 'users','action' => 'selection_subcat','slug'=>'directory'),array('class'=>'button'));?>
            </div>
          </div>
        </div>
      </section>
    </div>
	<?php include_once('footer.ctp'); ?>
    <!--<footer>
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell large-6">
            <?php echo $this->Html->link("Keyword GPS", array('controller' => 'Users','action' => 'index'),array('class'=>'logo'));?>
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
