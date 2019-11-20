<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category landing</title>
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
	?>
	<script>
	/********* data table function  ***/
	$(document).ready(function() {
		<?php 
		if($loginerror!='')
		{?>
			$('.reveal-overlay').css('display','block');
			$('#signIn').css('display','block');
		<?php 
		}?>
		$('#datatbl').DataTable({
			 "paging": false,
			 "pageLength":25,
			 "searching": false,
			 "info":false,
			 "lengthChange": false,
			  "aaSorting": [],
		});
		
		var i =1;
		$.each($('.reveal-overlay'), function (index, value) {
			$(this).attr('id', 'popup'+i);
			i++;
		});
		addtocart();
	} );
	
	</script>
	<?php
		if($userid=='')	
		{
			include('navigation.ctp');
		}
		else
		{
			include('navigation_user.ctp');
		}
	?>
  </head>
  
  <body>  
    <?php echo $this->Form->create(false, array('url' => array('controller' => 'users', 'action' => 'category_landing','param' => 'search','param1'=>'test'),'id' => 'searchbar','method'=>'POST'));?>
     <div class="hero">
       <div class="grid-container text-center">
         <div class="grid-x search-container">
           <div class="cell callout">
			 <div class="input-group">
              <input class="input-group-field" id="category_search" name="category_search" type="text" onkeyup="search(this.value);" onclick="search(this.value);" autocomplete='off'>
			  <input type='hidden' name='nodata' id='nodata' value=''>
			  <div class="suggestionsBoxcrp" id="suggestionscrp" style="display:none;margin-top: 50px;">
					<img src=<?php //echo $this->webroot."/assets/upArrow.png"?> style="position: relative; top: -59px; left: -120px; alt="upArrow" />
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
	<?php echo $this->Form->end();?>
    <div class="content">
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell large-4">
            <div class="category-landing-info">
              <aside class="callout">
                <h2 id=""><?php echo ucfirst($subcatval);?></h2>
                <span class="keyword-results"><?php echo number_format($noofcount).' ';?></span>
                <span class="keywords-search-per-month"><?php echo number_format($kw_count);?> </span>				
                <span class="price"><?php echo number_format($price,2);?></span>
                <a class="button expanded" id="btnaddtocart">Add To Cart</a>
                <?php echo $this->Html->link("Continue Shopping", array('controller' => 'users','action' => 'selection_subcat','slug'=>'directory'),array('class'=>'continue-shopping'));?>
				
			  </aside>
              <div class="cell large-3">
                <div class="related-categories">
                  <aside>
                    <h3>Related Categories</h3>
                    <ul class="no-bullet">
                      	<?php
						if(count($relatedkws)>0)
						{
							for($kwg=0; $kwg<count($relatedkws); $kwg++)
							{
								$relsubid = $relatedkws[$kwg]['s']['Subcategoryid'];								
								$relsubval = str_replace(' ','-',$relatedkws[$kwg]['s']['Subcategory']);
								echo '<li>'.$this->html->link($relatedkws[$kwg]['s']['Subcategory'],array('controller' => 'users', 'action' => 'category_landing','param'=>'search','param1' =>strtolower($relsubval))).'</li>';
							}
						}
						?>
                    </ul>
                  </aside>
                </div>
              </div>
            </div>
          </div>
          <div class="cell large-8">
            <table class="directory-keywords lock" id='datatbl'>
              <thead>
                <tr>
                  <th>Keyword<a href="#" class="sort"></a></th>
                  <th>US Volume<a href="#" class="sort"></a></th>
				  <th>G Volume<a href="#" class="sort"></a></th>
                  <th>Type<a href="#" class="sort"></a></th>
                </tr>
              </thead>
              <tbody>
				<?php 
				if(sizeof($allview)>0)
				{
					$subcatidarr = array();
					if(count($view_arr)>0)
					{
						foreach($view_arr as $dataval)
						{
							$subcatidarr[] = $dataval['s']['Subcategoryid'];
						}
					}
					for($i=0; $i<count($allview); $i++)
					{
						$j = $i+1;
						$keyword = $allview[$i]['tblkeyword']['Keyword'];
						$usvol = $allview[$i]['tblkeyword']['US_Volume'];
						$gvol = $allview[$i]['tblkeyword']['G_Volume'];
						$type = $allview[$i]['tblkeyword']['Type'];
						$view_subcatid=$allview[$i]['tblkeyword']['Subcategoryid'];
						if(in_array($view_subcatid, $subcatidarr))
						{
							$class = 'exist-keyword';
						}
						else
						{
							$class = '';
						}
						
						echo '<tr class='.$class.'>';
							echo '<td>'.$keyword.'</td>';
							echo '<td>'.number_format($usvol).'</td>';
							echo '<td>'.number_format($gvol).'</td>';
							echo '<td>'.$type.'</td>';
						echo '</tr>';
					}
				}?>
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
			<input type="hidden" name="subcattxt" id="subcattxt" value="<?php echo $subcatval;?>">
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
