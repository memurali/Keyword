<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import</title>

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
		$('#datatbl').DataTable({
			"pageLength": 20,
			 "searching": false,
			 "info":false,
			 "lengthChange": false,
			 "aaSorting": [],
		});
		$('#exampleFileUpload').on("change", function(){ 
			var filename = $('input[type=file]').val().replace(/C:\\fakepath\\/i, '')
			$('#lblname').text(filename);
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
  </head>

  <body class="dash">
    <div class="title-bar" data-responsive-toggle="example-animated-menu" data-hide-for="medium">
      <button class="menu-icon" type="button" data-toggle></button>
      <div class="title-bar-title">Menu</div>
    </div>

    <!--<div class="hero">
      <div class="grid-container text-center">
        <div class="grid-x search-container">
          <div class="cell callout">
            <div class="input-group">
              <input class="input-group-field" type="text">
              <div class="input-group-button">
                <input type="submit" class="button large" value="Search">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    
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
                    <li><a href="#" class="active">Import</a></li>
                    <li><?php echo $this->html->link("View",array('controller' => 'Users', 'action' => 'dash_view'));?></li>
                    <li><?php echo $this->html->link("Config",array('controller' => 'Users', 'action' => 'dash_config'));?></li>
                    <li><?php echo $this->html->link("Gateway",array('controller' => 'Users', 'action' => 'dash_gateway'));?></li>
                    <li><?php echo $this->html->link("Home",array('controller' => 'Users', 'action' => 'index'));?></li>
					<?php echo $this->html->link("Logout",array('controller' => 'Users', 'action' => 'logout'));?></li>
                  </ul>
                </div>
              </div>
            </aside>
          </div>
        </div>
        <div class="cell large-10">
          <div class="content">
            <div class="grid-x grid-margin-x">
              <div class="cell">
                <div class="small button-group">
					<?php echo $this->Form->create(false, array(
						'url' => array('controller' => 'Csvs', 'action' => 'dash_import'),
						'id' => 'csvimport',
						'method'=>'POST',
						'type' => 'file'
						));?>
                  <label for="exampleFileUpload" class="button">Upload CVS File</label>
                  <input type="file" id="exampleFileUpload" name="data[impfile]" class="show-for-sr">
                  <button type="submit" class="button secondary">Preview</button>
				  <?php echo $this->Form->end(); ?>
                </div>
				<span id='lblname'></span>
              </div>
            </div>
			<div><center><img id="preview_loading" style="display:none;" src="<?php echo $this->webroot; ?>assets/Rolling-1s-57px.gif" /></center></div>
			
			<div id="geterror">
				<?php 
				if(!empty($result))
				{
					print_r($result);
					exit;
				}
				?>
			</div>
			<div id="preview_div">
				<?php  $size=sizeof($keyword);				
				if($size>0)
				{
					echo'<table id="datatbl">
						  <thead>
							<tr>
							  <th>Number<a class="sort"></a></th>
							  <th>Category<a class="sort"></a></th>
							  <th>Subcategory<a class="sort"></a></th>
							  <th>Keyword<a class="sort"></a></th>
							  <th>G Volume<a class="sort"></a></th>
							  <th>US Volume<a class="sort"></a></th>
							  <th>Type<a class="sort"></a></th>
							  <th>CPC<a class="sort"></a></th>
							</tr>  
						  </thead>
						  <tbody>';	
						for($i=0; $i<count($keyword); $i++)
						{
							$categoryval = $category[$i]['tblcategory']['Category'];
							$cat_span = 'exist-keyword';
							if($categoryval =='')
							{
								$categoryval = $category[$i]['tbltemp']['Category'];
								$cat_span="new-keyword";
							}
							$subcategoryval = $subcategory[$i]['tblsubcategory']['Subcategory'];
							$subcat_span="exist-keyword";
							if($subcategoryval=='')
							{
								$subcategoryval=$subcategory[$i]['tbltemp']['Subcategory'];
								$subcat_span="new-keyword";
							}
							$keywordval = $keyword[$i]['tblkeyword']['Keyword'];
							$gvolume = $keyword[$i]['tblkeyword']['G_Volume'];
							$usvolume = $keyword[$i]['tblkeyword']['US_Volume'];
							$type = $keyword[$i]['tblkeyword']['Type'];
							$cpc = $keyword[$i]['tblkeyword']['CPC'];
							$keyword_span="exist-keyword";
							$gvol_span="exist-keyword";
							$usvol_span="exist-keyword";
							$type_span="exist-keyword";
							$cpc_span="exist-keyword";
							if($keywordval=='')
							{
								$keywordval = $keyword[$i]['tbltemp']['Keyword'];
								$gvolume = $keyword[$i]['tbltemp']['G_Volume'];
								$usvolume = $keyword[$i]['tbltemp']['US_Volume'];
								$type = $keyword[$i]['tbltemp']['Type'];
								$cpc = $keyword[$i]['tbltemp']['CPC'];
								$keyword_span = 'new-keyword';
								$gvol_span="new-keyword";
								$usvol_span="new-keyword";
								$type_span="new-keyword";
								$cpc_span="new-keyword";
							}
							else
							{
								if($gvolume!=$keyword[$i]['tbltemp']['G_Volume'])
								{
									$gvolume=$keyword[$i]['tbltemp']['G_Volume'];
									$gvol_span="new-keyword";
								}
								if($usvolume!=$keyword[$i]['tbltemp']['US_Volume'])
								{
									$usvolume=$keyword[$i]['tbltemp']['US_Volume'];
									$usvol_span="new-keyword";
								}
								if($cpc!=$keyword[$i]['tbltemp']['CPC'])
								{
									$cpc=$keyword[$i]['tbltemp']['CPC'];
									$cpc_span="new-keyword";
								}
							}
											
							$j=$i+1;
							echo '<tr>';
								echo '<td>'.$j. '</td>';
								echo '<td class='.$cat_span.'>'.$categoryval.'</td>';
								echo '<td class='.$subcat_span.'>'.$subcategoryval.'</td>';
								echo '<td class='.$keyword_span.'>'.$keywordval.'</td>';
								echo '<td class='.$gvol_span.'>'.$gvolume.'</td>';
								echo '<td class='.$usvol_span.'>'.$usvolume.'</td>';
								echo '<td>'.$type.'</td>';
								echo '<td class='.$cpc_span.'>'.$cpc.'</td>';
							echo '</tr>';	
						}
					  echo '</tbody>';				
					echo '</table>';
					echo '<center><input type="button" class="button secondary" id="btnproceed" value="Proceed"></center>';
				}?>
			</div>	
			<div><center><img id="proceed_loading" style="display:none;" src="<?php echo $this->webroot; ?>assets/Rolling-1s-57px.gif" /></center></div>
			<div id="proceed_div">
			</div>
            <!-- PAGINATION 
            <div class="grid-x">
              <div class="cell">
                <nav aria-label="Pagination">
                  <ul class="pagination">
                    <li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>
                    <li class="current"><span class="show-for-sr">You're on page</span> 1</li>
                    <li><a href="#" aria-label="Page 2">2</a></li>
                    <li><a href="#" aria-label="Page 3">3</a></li>
                    <li><a href="#" aria-label="Page 4">4</a></li>
                    <li class="ellipsis" aria-hidden="true"></li>
                    <li><a href="#" aria-label="Page 12">12</a></li>
                    <li><a href="#" aria-label="Page 13">13</a></li>
                    <li class="pagination-next"><a href="#" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>
                  </ul>
                </nav>
              </div>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
