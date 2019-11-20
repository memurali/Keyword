<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price</title>
   
	<?php
		echo $this->Html->css('foundation.css');
		echo $this->Html->css('app.css');
		echo $this->Html->script('jquery.js');
		echo $this->Html->script('what-input.js');
		echo $this->Html->script('foundation.js');
		echo $this->Html->script('app.js');
		echo $this->Html->script('common.js');
		echo $this->Html->script('common_function.js');
		echo $this->Html->script('datatables.min.js');
	?>
	<script>
		$(document).ready(function() {
			mergerow(true);		
			removeduplicate();
			var i =1;
			$.each($('.reveal-overlay'), function (index, value) {
				$(this).attr('id', 'popup'+i);
				i++;
			});
		});	
		
	</script>
	<!----ALERT MODAL START-->
	 <div class="reveal small" id="response" data-reveal>
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
  <!-- KEYWORD PRICE MODAL BEGIN -->
    <div class="reveal tiny" id="paymentGateway" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>Set Keyword Price</h2>
          </div>
          <div class="cell">
            <?php 
				echo $this->Form->create(false, array('data-abide'=>'','novalidate'),array(
						'url' => array('controller' => 'Users', 'action' => 'dash_config'),
						'id' => 'dash_configForm',
						'method'=>'POST'
						
				));
				$price = $price_val[0]['tblpricing']['Keyword_Price'];
				$priceid = $price_val[0]['tblpricing']['Priceid'];
				$config = $config_data[0]['tblconfig']['Expiration_duration'];
				$configid = $config_data[0]['tblconfig']['Configid'];
			?>
			  <div class="grid-x">
                <div class="cell">
                  <label>Category
					<select name="s_category" id="s_category" onchange="cat_price(this.value)">
						<option value="">--Select Category---</option>
						<?php
							for($c=0; $c<count($data_cat); $c++)
							{
								$catid = $data_cat[$c]['tblcategory']['Categoryid'];
								$catval = $data_cat[$c]['tblcategory']['Category'];
								echo "<option value='".$catid."'>".$catval."</option>";
							}
						?>
					</select>
                  </label>
                </div>
                <div class="cell" id="tr_catprice" style="display:none;">
                  <label id='price_catlbl'>Price Per Category                    
					<input type="number" step="any" name="price_cat" id="price_cat" placeholder="$/Category" value=''>					
					<span id='price_cat_err'class="form-error" id="signupName">Price must be greater than zero</span>
				  </label>
				  <input type="hidden" name="priceid_cat" id="priceid_cat" value="">
                </div>
              </div>
              <div class="grid-x">
                <div class="cell">
                  <label>Subcategory
					<select name="s_subcategory" id="s_subcategory" onchange="subcat_price(this.value)">
						<option value="">--Select Subcategory---</option>
						<?php
							for($s=0; $s<count($data_subcat); $s++)
							{
								$subcatid = $data_subcat[$s]['a']['Subcategoryid'];
								$subcat = $data_subcat[$s]['a']['Subcategory'];
								$cat = $data_subcat[$s]['b']['Category'];
								echo "<option value='".$subcatid."'>".$cat." - ".$subcat."</option>";
							}
						?>
					</select>
                  </label>
                </div>
                <div class="cell" id="tr_subcatprice" style="display:none;">
                  <label id='price_subcatlbl'>Price Per Subcategory                    
					<input type="number" step="any" name="price_subcat" id="price_subcat" placeholder="$/Subcategory" value=""/>				
					<span id='price_subcat_err'class="form-error" id="signupName">Price must be greater than zero</span>
				  </label>
				  <input type="hidden" name="priceid_subcat" id="priceid_subcat" value=""/>
                </div>
              </div>
              
              <div class="grid-x">
                <div class="cell">
                  <label id='pricelbl'>Price Per Keyword
					<input type="number" step="any" name="price" id="price" placeholder="$/Keyword" value="<?php echo $price;?>">
					<span id='price_err'class="form-error" id="signupName">Price must be greater than zero</span>
				  </label>
				  <input type="hidden" name="priceid" id="priceid" value='<?php echo $priceid;?>'>
                </div>
              </div>
              <div class="grid-x">
                <div class="cell">
                  <label id='configlbl'>Expiration Date        
					<input type="number" name="config" id="config" value="<?php echo $config;?>" placeholder="In Days">
					<span id='config_err'class="form-error" id="signupName">Required</span>
				  </label>				  
				 <input type="hidden" name="configid" id="configid" value="<?php echo $configid;?>">
                </div>
              </div>
              <div class="grid-x">
                <div class="cell">
                  <input type="button" class="button expanded" value='Save' onclick='configsave();'>
                </div>
              </div>
            </div>
          <?php echo $this->Form->end(); ?>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- KEYWORD PRICE MODAL END -->
  </head>
  <body class="dash">
    <div class="title-bar" data-responsive-toggle="example-animated-menu" data-hide-for="medium">
      <button class="menu-icon" type="button" data-toggle></button>
      <div class="title-bar-title">Menu</div>
    </div>
    
      <div class="grid-x">
        <div class="cell large-2 sidenav">
          <div class="sidenav-info">
            <aside class="callout">
              <h2>Admin Dashboard</h2>
              <div class="grid-x">
                <div class="cell">
                  <ul class="vertical menu">      
					<li><?php echo $this->html->link("Orders <span>$total_orders</span>",array('controller' => 'Users', 'action' => 'dash_orders','param'=>'Users','param1'=>'dash_orders','param2' =>'all'), array('escape' => FALSE));?></li>
                    <li><?php echo $this->html->link("Users",array('controller' => 'Users', 'action' => 'dash_users'));?></li>
                    <li><?php echo $this->html->link("Import",array('controller' => 'Csvs', 'action' => 'dash_import'));?></li>
                    <li><?php echo $this->html->link("View",array('controller' => 'Users', 'action' => 'dash_view'));?></li>
                    <li><a href="#" class="active">Config</a></li>
					<li><?php echo $this->html->link("Gateway",array('controller' => 'Users', 'action' => 'dash_gateway'));?></li>
                    <li><?php echo $this->html->link("Home",array('controller' => 'Users', 'action' => 'index'));?></li>
					<li><?php echo $this->html->link("Logout",array('controller' => 'Users', 'action' => 'logout'));?></li>
                  </ul>
                </div>
              </div>
            </aside>
          </div>
        </div>
        <div class="cell large-10">
          <div class="content">
            <div class="grid-x grid-margin-x">
              <div class="cell large-3">
                <a class="button" data-open="paymentGateway">Set Keyword Price</a>
              </div>
              <div class="cell large-9 search-container">
                <div class="input-group">
                  <input id="config_search" name="config_search" class="input-group-field" type="text">
                  <div class="input-group-button">
                    <!-- FORM SHOULD AUTOFILL-->                  
					<input type="button" id="btnconfig_search" name="btnconfig_search" class="button large" value="Search">
                  </div>
                </div>
              </div>
            </div>
            <?php
				$size = sizeof($subcat_data);	
				if($size>0)
				{
					echo '<table id=myTable>
							<thead>	
							  <tr>';	
						echo	'<th>
								  <div class="grid-x">
									<div class="cell small-11">
                                      <label>
										<select name=selectcat id=selectcat>
											<option value=Category>Category</option>';										
												for($s=0; $s<sizeof($subcat_data); $s++)
												{
													$catid = $subcat_data[$s]['i']['CategoryId'];
													$subcatid = $subcat_data[$s]['i']['SubcategoryId'];	
													if($subcat_data[$s]['i']['Category']!="Aall")	
														$cat = $subcat_data[$s]['i']['Category'];	
													else
														$cat = 'All';
													echo '<option value='.$catid.','.$subcatid.'>'.$cat.'</option>';
												}										
									echo '</select>									
									   </label>
                                      </div>
                                      <!--<div class="cell small-1">
                                        <a class="sort"></a>
                                      </div>
									</div>-->	
								</th>';						
								
						echo	'<th>
								   <div class="grid-x">
									 <div class="cell small-11">
                                       <label>
										<select  name=selectsubcat id=selectsubcat>
											<option value=Subcategory>Subcategory</option>';										
												for($s=0; $s<sizeof($subcat_data); $s++)
												{
													$catid = $subcat_data[$s]['i']['CategoryId'];
													$subcatid = $subcat_data[$s]['i']['SubcategoryId'];												
													if($subcat_data[$s]['i']['Subcategory']!="Aall")	
														$subcat = $subcat_data[$s]['i']['Subcategory'];	
													else
														$subcat = 'All';
													echo '<option value='.$catid.','.$subcatid.'>'.$subcat.'</option>';
												}										
									echo '</select>
									   </label>
                                      </div>
                                      <!--<div class="cell small-1">
                                        <a class="sort"></a>
                                      </div>
									</div>-->	
									</div>
								</th>';
						echo	'<th>Price Per Keyword</th>';
						echo '</tr>
							</thead>';
						echo'<tbody id=tblfilter>';
					for($s=0; $s<$size; $s++)
					{							
						echo '<tr>';
						
							if($subcat_data[$s]['i']['Category']!="Aall")
								echo '<td align="center">'.$subcat_data[$s]['i']['Category'].'</td>';
							else
								echo '<td align="center">All</td>';
								
							if($subcat_data[$s]['i']['Subcategory']!="Aall")
								echo '<td>'.$subcat_data[$s]['i']['Subcategory'].'</td>';
							else
								echo '<td>All</td>';
								
							echo '<td>'.'$'.$subcat_data[$s]['i']['Keyword_Price'].'</td>';
							
						echo '</tr>';								
					}
						echo '</tbody>';
					echo '</table>';
				}?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
