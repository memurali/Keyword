<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
 
	<?php
		echo $this->Html->css('foundation.css');
		echo $this->Html->css('app.css');
		echo $this->Html->css('dataTables.foundation.min.css');
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
		$('#datatbl').DataTable({
			 "pageLength":10,
			 "paging":true,
			 "searching":true,
			 "info":false,
			 "lengthChange":true,
			 "aaSorting": [],
	   });
	   var i =1;
		$.each($('.reveal-overlay'), function (index, value) {
			$(this).attr('id', 'popup'+i);
			i++;
		});
	});
	</script>
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
    <!-- NEW KEYWORD MODAL BEGIN -->
    <div class="reveal" id="newKeyword" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>Add New Keyword</h2>
          </div>
          <div class="cell">
            <form data-abide novalidate name="newdatafrm" id="newdatafrm" method="POST">
              <div class="grid-x">
                <div class="cell">
                  <label id="new_keywordlbl">Keyword
                    <input type="text" name="new_keyword" id="new_keyword" value="" placeholder="Keyword" required>
                    <span class="form-error" id="new_keyword_err">Keyword can't be empty</span>
				  </label>				  
                </div>
              </div>
			  <div class="grid-x grid-padding-x">
                <div class="cell large-8">
                  <label id="s_catlbl">Category
                    <select name="s_cat" id="s_cat" onchange="getsubcat(this.value);">
						<option value="">--select---</option>
						<?php
						for($s=0; $s<count($data_cat); $s++)
						{
							$catid = $data_cat[$s]['tblcategory']['Categoryid'];
							$cat = $data_cat[$s]['tblcategory']['Category'];
							echo "<option value='".$catid."'>".$cat."</option>";
						}
						?>
					</select>
					<span class="form-error" id="s_cat_err">Please select category from dropdown or add new category</span>
                  </label>
                </div>
                <div class="cell large-4">
                  <a class="button" data-open="addCategory" onclick="getall('Category')" style="margin-top: 19px;">Add New</a>
                </div>
              </div>
              <div class="grid-x grid-padding-x">
                <div class="cell large-8">
                  <label id="s_subcatlbl">Subcategory
                    <select name="s_subcat" id="s_subcat">
						<option value="">--select---</option>						
					</select>
					 <span class="form-error" id="s_subcat_err">Please select subcategory from dropdown or add new subcategory</span>
                  </label>
                </div>
                <div class="cell large-4">
                  <a class="button" data-open="addSubcategory" onclick="getall('Subcategory')" style="margin-top: 19px;">Add New</a>
                </div>
              </div>
              <div class="grid-x">
                <div class="cell">
                  <label id="new_g_vollbl">Volume (Global)
                    <input type="number" name="new_g_vol" id="new_g_vol" value="" placeholder="Volume(Global)" required>
					<span class="form-error" id="new_g_vol_err">Global volume can't be empty</span>
				  </label>
                </div>
              </div>
              <div class="grid-x">
                <div class="cell">
                  <label id="new_us_vollbl">Volume (US)
                    <input type="number" name="new_us_vol" id="new_us_vol" value="" placeholder="Volume(US)" required>
					<span class="form-error" id="new_us_vol_err">US volume can't be empty</span>
				  </label>
                </div>
              </div>
              <div class="grid-x">
                <div class="cell">
                  <label>Type
                    <input type="text" name="new_type" id="new_type" value="" placeholder="Type">
                  </label>
                </div>
              </div>
              <div class="grid-x">
                <div class="cell">
                  <input type="button" class="button expanded" id="btnsave" Value="Save" >
                </div>
              </div>
            </div>
          </form>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- NEW KEYWORD MODAL END -->
	
    <!-- NEW CATEGORY MODAL BEGIN -->
    <div class="reveal" id="addCategory" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>Add New Category</h2>
          </div>
          <div class="cell">
            <form data-abide novalidate name="newcatfrm" id="newcatfrm" method="POST">              
                <div class="grid-x grid-padding-x">
                  <div class="cell large-8">
                    <label id="txtcategorylbl">Category
                      <input type="text" name="txtcategory" id="txtcategory" value="">
					  <input type="hidden" name="txtcategoryid" id="txtcategoryid" value="">
					  <span class="form-error" id="txtcategory_err">Enter category name</span>
                    </label>
                  </div>
                  <div class="cell large-4">
                    <label>Active
                      <div class="switch large">
                        <input class="switch-input" type="checkbox" name="new_cat_active" id="new_cat_active">
                        <label class="switch-paddle" for="new_cat_active">
                          <span class="switch-active" aria-hidden="true">Yes</span>
                          <span class="switch-inactive" aria-hidden="true">No</span>
                        </label>
                      </div>
                    </label>
                  </div>
                </div> 
              <div class="grid-x">
                <div class="cell">
                  <input type="button" class="button expanded" id="btncatsave" Value="Save">
                </div>
              </div>
			  <div id="allcategory">
				<!-----Ajax data view all category------------->
			  </div>
			</form>
		  </div>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- NEW CATEGORY MODAL END -->	
	
	<!-- EDIT KEYWORD MODAL BEGIN -->
    <div class="reveal" id="editKeyword" data-reveal style="display:none">
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>Edit Keyword</h2>
          </div>
          <div class="cell">
            <form data-abide novalidate name="editdatafrm" id="editdatafrm" method="POST">
             <!---- Ajax data----> 
            </form>
		  </div>	
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>    
	</div>
    <!--EDIT KEYWORD MODAL END -->
	
	
	<!-- EDIT CATEGORY/SUBCATEGORY MODAL BEGIN -->
    <div class="reveal" id="EditCategory" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>Edit New Category/Subcategory</h2>
          </div>
          <div class="cell">
            <form data-abide novalidate name="editcatfrm" id="editcatfrm" method="POST">
              <div class="grid-x">
                <div class="cell">
                  <label id="editmodesellbl">Category/Subcategory
                    <select name="editmodesel" id="editmodesel" onchange="seleditmode(this.value);">
						<option value="">--Select--</option>
						<option value="category">Category</option>
						<option value="subcategory">Subcategory</option>
					</select>
					<span class="form-error" id="editmodesel_err">Please select either category or subcategory from dropdown</span>
                  </label>
                </div>
              </div>
              <div class="editcategory" style="display:none">
                <div class="grid-x grid-padding-x">
                  <div class="cell large-8">
                    <label id="edit_txtcatlbl">Category
                      <input type="text" name="edit_txtcat" id="edit_txtcat" value="">
					  <input type="hidden" name="edit_catid" id="edit_catid" value="">
					  <span class="form-error" id="edit_txtcat_err">Enter category name</span>
                    </label>
                  </div>
                  <div class="cell large-4">
                    <label>Active
                      <div class="switch large">
                        <input class="switch-input" type="checkbox" name="edit_active" id="edit_active">
                        <label class="switch-paddle" for="edit_active">
                          <span class="switch-active" aria-hidden="true">Yes</span>
                          <span class="switch-inactive" aria-hidden="true">No</span>
                        </label>
                      </div>
                    </label>
                  </div>
                </div>
              </div>
              <div class="editsubcategory" style="display:none">
                <div class="grid-x">
                  <div class="cell">
                    <label id="edit_txtsubcatlbl">Subcategory
                      <input type="text" name="edit_txtsubcat" id="edit_txtsubcat" value="">
					  <input type="hidden" name="edit_subcatid" id="edit_subcatid" value="">
					  <span class="form-error" id="edit_txtsubcat_err">Enter subcategory name</span>
                    </label>
                  </div>
                </div>
                <div class="grid-x grid-padding-x">
                  <div class="cell large-8">
                    <label id="edit_sel_catlbl">Category
                      <select name="edit_sel_cat" id="edit_sel_cat">
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
					<span class="form-error" id="edit_sel_cat_err">Select category</span>
                    </label>
                  </div>
                  <div class="cell large-4">
                    <label>Active
                      <div class="switch large">
                        <input class="switch-input" type="checkbox" name="edit_sub_active" id="edit_sub_active">
                        <label class="switch-paddle" for="edit_sub_active">
                          <span class="switch-active" aria-hidden="true">Yes</span>
                          <span class="switch-inactive" aria-hidden="true">No</span>
                        </label>
                      </div>
                    </label>
                  </div>
                </div>
              </div>
              <div class="grid-x">
                <div class="cell">
                  <input type="button" class="button expanded" id="edit_btnsave" onclick="update_catdata();" Value="Save">
                </div>
              </div>
            </div>
          </form>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!--EDIT CATEGORY/SUBCATEGORY MODAL END -->
	
	<!-- NEW SUBCATEGORY MODAL BEGIN -->
    <div class="reveal" id="addSubcategory" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>Add New Subcategory</h2>
          </div>
          <div class="cell">
            <form data-abide novalidate name="newsubcatfrm" id="newsubcatfrm" method="POST">              
                 <div class="grid-x">				 
					  <div class="cell">
						<label id="s_categorylbl">Category
						  <select name="s_category" id="s_category">
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
						<span class="form-error" id="s_category_err">Select category</span>
                      </label>					  
					 </div>
                </div>			
                <div class="grid-x grid-padding-x">
                  <div class="cell large-8">					
					<label id="txtsubcatlbl">Subcategory
					  <input type="text" name="txtsubcat" id="txtsubcat" value="">
					  <input type="hidden" name="txtsubcatid" id="txtsubcatid" value="">
					  <span class="form-error" id="txtsubcat_err">Enter subcategory name</span>
					</label>						
                  </div>
                  <div class="cell large-4">
                    <label>Active
                      <div class="switch large">
                        <input class="switch-input" type="checkbox" name="new_sub_active" id="new_sub_active">
                        <label class="switch-paddle" for="new_sub_active">
                          <span class="switch-active" aria-hidden="true">Yes</span>
                          <span class="switch-inactive" aria-hidden="true">No</span>
                        </label>
                      </div>
                    </label>
                  </div>
                </div>
               <div class="grid-x">
                <div class="cell">
                  <input type="button" class="button expanded" id="btnsubcatsave" Value="Save">
                </div>
               </div> 
			  <div id="allsubcategory">
				<!-----------Ajax data view all subcategory----------->
			  </div>
			</form>
		  </div>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- NEW SUBCATEGORY MODAL END -->	
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
                    <li><a href="#" class="active">View</a></li>
                    <li><?php echo $this->html->link("Config",array('controller' => 'Users', 'action' => 'dash_config'));?></li>
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
            <div class="grid-x grid-margin-x float-fix">
              <div class="cell large-2">
                <div class="grid-x small-up-2">
                  <div class="cell">
                    <div class="small button-group">
                      <a class="button" data-open="newKeyword">Add</a>
                      <input type="button"  class="button alert" id="btndelete" value="Delete">
                    </div>
                  </div>
                </div>
              </div>
            </div>
			<form name="frmdata" id="frmdata">
            <table id="datatbl">
              <thead>
                <tr>
                  <th width="50">Delete<input type="checkbox" id="selectAll"></th>
                  <th width="70">No<a class="sort"></a></th>
                  <th>
				  Category<a class="sort"></a>
                  </th>
                  <th>
				  Subcategory<a class="sort"></a>                    
                  </th>
                  <th>Keywords<a class="sort"></a></th>
                  <th>Volume Global<a class="sort"></a></th>
                  <th>Volume USA<a class="sort"></a></th>
				  <th>Type<a class="sort"></a></th>
				  <th>Active<a class="sort"></a></th>
                </tr>  
              </thead>
              <tbody>                
                <?php 
				for($i=0; $i<count($data); $i++)
				{
					$j=$i+1;
					$cactive = $data[$i]['c']['Active'];
					$sactive = $data[$i]['s']['Active'];
					echo "<tr>";
						echo "<td><input type=checkbox id=deletekey name=deletekey class=call-checkbox value='".$data[$i]['k']['Keyid']."');></td>";
						echo "<td><a data-open='editKeyword' onclick='edit(".$data[$i]['k']['Keyid'].")'>".$j."</a></td>";
						echo "<td>".$data[$i]['c']['Category']."</td>";
						echo "<td>".$data[$i]['s']['Subcategory']."</td>";
						echo "<td>".$data[$i]['k']['Keyword']."</td>";
						echo "<td>".$data[$i]['k']['G_Volume']."</td>";
						echo "<td>".$data[$i]['k']['US_Volume']."</td>";
						echo "<td>".$data[$i]['k']['Type']."</td>";
						if(($cactive=='N')||($sactive=='N'))
						{
							echo "<td>N</td>";
						}
						else
						{
							echo "<td> </td>";
						}
					echo "</tr>";
				}
				?>
              </tbody>
            </table>
			</form> 
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
