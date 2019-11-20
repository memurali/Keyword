<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
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
  </head>
  <!-- USER INFO MODAL BEGIN -->
    <div class="reveal tiny" id="userInfo" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>User Info</h2>
          </div>
          <div class="cell">
            <form id="edituserfrm" name="edituserfrm" method="POST">
              <div class="grid-x">
                <div class="cell">
                  <label>Name
                    <input type="text" id="usrname" name="usrname">
                  </label>
                </div>
                <div class="cell">
                  <label>Email
                    <input type="text" id="usremail" name="usremail">
                  </label>
                </div>
              </div>
              <div class="grid-x grid-padding-x">
                <div class="cell medium-8">
                  <label>Company
                    <input type="text" id="usrcompany" name="usrcompany">
                  </label>
                </div>
                <div class="cell medium-4">
                  <label>Active
                    <div class="switch large">
                      <input class="switch-input" id="yes-no" type="checkbox" name="yes-no">
                      <label class="switch-paddle" for="yes-no">
                        <span class="switch-active" aria-hidden="true">Yes</span>
                        <span class="switch-inactive" aria-hidden="true">No</span>
                      </label>
                    </div>
                  </label>
                </div>
              </div>
			  <input type="hidden" id="usrid" name="usrid"/>
              <div class="grid-x">
                <div class="cell">
                  <input type="button" id="update_user" class="button expanded" value="Update" />
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
    <!-- USER INFO MODAL END -->
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
					<li><a class="active" href="#">Users</a></li>					
                    <li><?php echo $this->html->link("Import",array('controller' => 'Csvs', 'action' => 'dash_import'));?></li>
                    <li><?php echo $this->html->link("View",array('controller' => 'Users', 'action' => 'dash_view'));?></li>
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
           <!-- <div class="grid-x grid-margin-x">
              <div class="cell large-3">
                <div class="small button-group">
                  <ul class="dropdown menu" data-dropdown-menu>
                    <li>
                      <a href="#" class="button secondary">Show Entries</a>
                      <ul class="menu">
                        <li><a href="#">10</a></li>
                        <li><a href="#">25</a></li>
                        <li><a href="#">50</a></li>
                        <li><a href="#">100</a></li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="cell large-9 search-container">
                <div class="input-group">
                  <input class="input-group-field" type="text">
                  <div class="input-group-button">
                    <!-- FORM SHOULD AUTOFILL
                    <input type="submit" class="button large" value="Search">
                  </div>
                </div>
              </div>
            </div>-->
            <table id="datatbl">
              <thead>
                <tr>
                  <th>Name<a class="sort"></a></th>
                  <th>Email<a class="sort"></a></th>
                  <th>Company<a class="sort"></a></th>
                  <th>Active<a class="sort"></a></th>
                  <th>Sign Up Date<a class="sort"></a></th>
                  <th width="70">Order<a class="sort"></a></th>
                </tr>  
              </thead>
              <tbody>
				<?php
                for($u=0; $u<count($user_arr); $u++)
				{
					$status = $user_arr[$u]['tblusers']['Active']=='Y'?'Active':'Pending';
					$user = $user_arr[$u]['tblusers']['Userid'];
					echo '<tr>';
						echo '<td><a data-open="userInfo" onclick="edituserdata('.$user.')">'.$user_arr[$u]['tblusers']['Name'].'</a></td>';
						echo '<td>'.$user_arr[$u]['tblusers']['Email'].'</td>';
						echo '<td>'.$user_arr[$u]['tblusers']['Company'].'</td>';
						echo '<td>'.$status.'</td>';
						echo '<td>'.date('m-d-Y',strtotime($user_arr[$u]['tblusers']['DateCreated'])).'</td>';						
						echo '<td>'.$this->html->link("View",array('controller' => 'Users', 'action' => 'dash_orders','param'=>'Users','param1'=>'dash_orders','param2' =>$user_arr[$u]['tblusers']['Userid']),array("class"=>"button tiny expanded")).'</td>';
					echo '</tr>';
				}
				?>                 
              </tbody>
            </table>
            <!---<div class="grid-x">
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
            </div>--->
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
