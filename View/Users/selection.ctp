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
              <input class="input-group-field" id="category_search" name="category_search" type="text">
              <div class="input-group-button">
                <!-- FORM SHOULD AUTOFILL -->
                <input type="submit" class="button large" value="Search">
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
          <div class="cell large-4">
            <div class="sidenav-info">
              <aside class="callout">
                <h2>Directory</h2>
                <div class="grid-x">
                  <div class="cell">
                    <ul class="vertical menu drilldown">
					  <?php 
					  if(count($data_cat)>0)
					  {
						for($i=0; $i<count($data_cat);$i++)
						{
							$category = $data_cat[$i]['tblcategory']['Category'];
							$categoryid = $data_cat[$i]['tblcategory']['Categoryid'];
							/*$rep_category   = preg_replace('/[\/^Â£Ã‡Â¥$%()}{#~?><>,|=_+Â¬-]/', '', $category);*/
							$arrcategory = explode("/",$category);
							$rep_category = $arrcategory[0];
							echo '<li>';
								echo $this->html->link($category,array('controller' => 'users', 'action' => 'selection_subcat','slug' =>$rep_category),array('onclick'=>'catlink('.$categoryid.')'));
								
							echo '</li>';
						}
					  }?>
                      
                    </ul>
                  </div>
                </div>
              </aside>
            </div>
          </div>
          <div class="cell large-8">
            <nav aria-label="You are here:" role="navigation">
              <ul class="breadcrumbs">
                <li>
				  <span class="show-for-sr">Current: </span> Category
				</li>
                <li>
                  <a href="#">Subcategory</a>
                </li>
              </ul>
            </nav>
            <table class="directory-keywords lock" id="keyTable">
              <thead>
                <tr>
                  <th>Keywords<a href="#" class="sort"></a></th>
                  <th>Keyword Amount<a href="#" class="sort"></a></th>
                </tr>
              </thead>
              <tbody>
                <?php
					for($k=0; $k<count($allkwarr); $k++)
					{
						$keyword = $allkwarr[$k]['tblkeyword']['Keyword'];
						$gvol = $allkwarr[$k]['tblkeyword']['G_Volume'];
						$usvol = $allkwarr[$k]['tblkeyword']['US_Volume'];
						$totvol = $gvol+$usvol;
						echo '<tr>';
								echo '<td>'.$keyword.'</td>';
								echo '<td><span>'.$totvol.'</span></td>';
						echo '</tr>';
					}
				?>
              </tbody>
            </table>
			<?php
			if($userid=='')
			{?>
				<div class="signup-callout">
				  <div class="grid-x grid-padding-x text-center">
					<div class="cell">
					  <a class="button" href="#" data-open="signUp">Sign Up To Unlock</a>
					</div>
				  </div>
				</div>
			<?php
			}?>
          </div>
        </div>
		<?php 
		//echo $this->html->link('View Price',array('controller' => 'users', 'action' => 'category_landing', 'slug'=>'ipad'),array('class'=>'keyword-group-price'));
		?>
      </div>
    </div>
	<?php include('footer.ctp'); ?>
  </body>
</html>
