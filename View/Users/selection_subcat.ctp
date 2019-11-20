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
		addtocart();
	} );
	</script>
  </head>
  <body>
	<!---<div class="title-bar" data-responsive-toggle="example-animated-menu" data-hide-for="medium">
      <button class="menu-icon" type="button" data-toggle></button>
      <div class="title-bar-title">Menu</div>
    </div>--->
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
          <div class="cell large-4">
		    <div class="sidenav-info">
              <nav aria-label="You are here:" role="navigation">
                <ul class="breadcrumbs">
					<li>
					  <?php echo $this->Html->link('Directory', array('controller' => 'users','action' => 'selection_subcat','slug'=>'directory'));?>
					</li>
					<li>
					  <?php echo $this->Html->link($category_txt, array('controller' => 'users','action' => 'selection_subcat','slug' =>strtolower($category_txt)));?>
					</li>
					<li>
					  <span class="show-for-sr">Current: </span><?php echo $subcat_txt;?>
					</li>
				</ul>
              </nav>
              <aside class="callout">
                <h2><?php echo ucfirst($category_txt).' Categories';?></h2>
                <div class="grid-x">
                  <div class="cell">
                    <ul class="vertical menu">
                      <?php					  
					  if(count($subcatarr)>0)
					  {
						for($i=0; $i<count($subcatarr);$i++)
						{
							$categoryid = $subcatarr[$i]['c']['Categoryid'];
							$category = $subcatarr[$i]['c']['Category'];
							$subcategory = $subcatarr[$i]['s']['Subcategory'];
							$subcategoryid = $subcatarr[$i]['s']['Subcategoryid'];
							$arrcategory = explode("/",$category);
							
							$rep_category = str_replace(' ','',$arrcategory[0]);
							$arrsubcategory = explode("/",$subcategory);
														
							$rep_subcategory = str_replace(' ','-',$arrsubcategory[0]);
							$keycount = $subcatarr[$i][0]['keycount'];
							$noofcount += $keycount;
							$kw_count += $subcatarr[$i][0]['totalvol'];
								
							echo '<li>';
								echo $this->Html->link($subcategory.'<span>'.$keycount.'</span>',array('controller' => 'users', 'action' => 'category_landing','param' =>strtolower($rep_category),'param1'=>strtolower($rep_subcategory)),array('escape' => FALSE));
							echo '</li>';							
						}

					  }?>
                     
                    </ul>					
                  </div>
                </div>
              </aside>
            </div>
          </div>
          <div class="cell large-8 category-landing-info-setup">
            <table class="directory-keywords lock" id="keyTable">
              <thead>
                <tr>
                  <th>Keywords<a href="#" class="sort"></a></th>
                  <th>US Volume<a href="#" class="sort"></a></th>
				  <th>G Volume<a href="#" class="sort"></a></th>
                </tr>
              </thead>
              <tbody>
                <?php
					for($k=0; $k<count($allkwarr); $k++)
					{
						$keyword = $allkwarr[$k]['k']['Keyword'];
						$usvol = $allkwarr[$k]['k']['US_Volume'];
						$gvol = $allkwarr[$k]['k']['G_Volume'];
						echo '<tr>';
								echo '<td>'.$keyword.'</td>';
								echo '<td><span>'.number_format($usvol).'</span></td>';
								echo '<td><span>'.number_format($gvol).'</span></td>';
						echo '</tr>';
					}
					if (count($subarr)>0)
					{
						$noofcount = $subarr[0]['s']['Keyword_count'];
						$kw_count = $subarr[0][0]['totalvol'];						
					}
				?>
              </tbody>
            </table>
			<?php
			if($subcat_txt=='') 
				$text = $category_txt;
			else 
				$text = $subcat_txt;
			?>
			<div class="category-landing-info category-landing-info-modal">
              <aside class="callout">
                <h2 id=""><?php echo ucfirst($text);?></h2>				
				  <span class="keyword-results"><?php echo number_format($noofcount).' ';?></span>
				  <span class="keywords-search-per-month"><?php echo number_format($kw_count);?> </span>				  
				  <span class="price"><?php echo number_format($price,2);?></span>
				 <a class="button expanded" id="btnaddtocart">Add To Cart</a>				 
              </aside>
            </div>
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
      </div>
    </div>
		<?php
		/**** get category, subcategory data ******/
		if(count($subcat_arr)>0)
		{
			$k = 1;
			for($j=0; $j<count($subcat_arr);$j++)
			{
				$subcat = $subcat_arr[$j]['s']['Subcategory'];
				$count_subid = $subcat_arr[$j]['s']['Subcategoryid'];
				$count_catid = $subcat_arr[$j]['c']['Categoryid'];
				$count = $subcat_arr[$j]['s']['Keyword_count'];
				$subcountprice = $subprice[$j];
				if($k==count($subcat_arr))
					$concat = '';
				else
					$concat = ',';
				$subcatid.= $count_subid.$concat;
				$catid.= $count_catid.$concat;
				$subcount.= $count.$concat;
				$subcount_price.=$subcountprice.$concat;
				$k++;
			}
		}
	?>
	<!--  ********** hidden values *****************-->
	<div id='hidden'>
		<form name='landingfrm' id='landingfrm'>
			<input type="hidden" name="action" id="action" value="<?php echo $action;?>">
			<input type="hidden" name="subcattxt" id="subcattxt" value="<?php echo $text;?>">
			<input type="hidden" name="subcatid" id="subcatid" value="<?php echo $subcatid;?>">
			<input type="hidden" name="catid" id="catid" value="<?php echo $catid;?>">
			<input type="hidden" name="totcount" id="totcount" value="<?php echo $totalcount;?>">
			<input type="hidden" name="subcount" id="subcount" value="<?php echo $subcount;?>">
			<input type="hidden" name="totprice" id="totprice" value="<?php echo $price;?>">
			<input type="hidden" name="flow" id="flow" value="<?php echo $flowval;?>">
			<input type="hidden" name="subcount_price" id="subcount_price" value="<?php echo $subcount_price;?>">
			<input type="hidden" name="userid" id="userid" value="<?php echo $userid;?>">
		</form>
	</div>
	<?php include('footer.ctp'); ?>
  </body>
</html>
