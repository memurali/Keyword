<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gateway</title>

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
			 "lengthChange":false,
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
    <!-- ADD GATEWAY MODAL BEGIN -->
    <div class="reveal tiny" id="addGateway" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>New Gateway</h2>
          </div>
          <div class="cell">
            <form data-abide novalidate id="add_gateway" name="add_gateway" method="POST">
              <div class="cell">
                <label>Gateway Name
                <input type="text" id="addgatewayName" name="addgatewayName" aria-describedby="gatewayNamehint" aria-errormessage="gatewayNameerror" required >
                <span class="form-error" id="addgatewayName">Required</span>
                </label>
              </div>
              <div class="cell">
                <label>Username
                <input type="text" id="adduserName"  name="adduserName" aria-describedby="userNamehint" aria-errormessage="userNameerror" required >
                <span class="form-error" id="adduserName">Required</span>
                </label>
              </div>
              <div class="cell">
                <label>Password
                <input type="password" id="addPassword" name="addPassword" aria-describedby="accessPasswordhint" aria-errormessage="accessPassworderror" required >
                <span class="form-error" id="addPassword">Required</span>
                </label>
              </div>         
              <div class="grid-x grid-padding-x">
                <div class="cell medium-8">
                  <label>Gateway URL
                    <input type="text" id="addgatewayURL" name="addgatewayURL" aria-describedby="gatewayURLhint" aria-errormessage="gatewayURLerror" required >
                    <span class="form-error" id="addgatewayURL">Required</span>
                  </label>
                </div>
                <div class="cell medium-4">
                  <label>Active
                    <div class="switch large">
                      <input class="switch-input" id="yes-no" type="checkbox" checked name="yes-no">
                      <label class="switch-paddle" for="yes-no">
                        <span class="switch-active" aria-hidden="true">Yes</span>
                        <span class="switch-inactive" aria-hidden="true">No</span>
                      </label>
                    </div>
                  </label>
                </div>
              </div>
              <div class="cell">
                <button class="button expanded" id="gateway_add" type="submit">Add</button>
              </div>
            </form>
          </div>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- ADD GATEWAY PRICE MODAL END -->
    <!-- EDIT GATEWAY MODAL BEGIN -->
    <div class="reveal tiny" id="editGateway" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>Edit Gateway</h2>
          </div>
          <div class="cell">
            <form data-abide novalidate id="edit_gateway" name="edit_gateway" method="POST" enctype="multipart/form-data">
              <div class="cell">
                <label>Gateway Name
                <input type="text" id="editgatewayName" name="editgatewayName" aria-describedby="gatewayNamehint" aria-errormessage="gatewayNameerror" required />
                <span class="form-error" id="editgatewayName">Required</span>
                </label>
              </div>
              <div class="cell">
                <label>Username
                <input type="text" id="edituserName" name="edituserName" aria-describedby="userNamehint" aria-errormessage="userNameerror" required />
                <span class="form-error" id="edituserName">Required</span>
                </label>
              </div>
              <div class="cell">
                <label>Password
                <input type="password" id="editPassword" name="editPassword" aria-describedby="accessPasswordhint" aria-errormessage="accessPassworderror" required />
                <span class="form-error" id="editPassword">Required</span>
                </label>
              </div>        
			  <input type="hidden" id="editgatewayid" name="editgatewayid"/>
              <div class="grid-x grid-padding-x">
                <div class="cell medium-8">
                  <label>Gateway URL
                    <input type="text" id="editgatewayURL" name="editgatewayURL" aria-describedby="gatewayURLhint" aria-errormessage="gatewayURLerror" required />
                    <span class="form-error" id="editgatewayURL">Required</span>
                  </label>
                </div>
                <div class="cell medium-4">
                  <label>Active
                    <div class="switch large">
                      <input class="switch-input" id="gatewaySwitch" type="checkbox" name="gatewaySwitch">
                      <label class="switch-paddle" for="gatewaySwitch">
                        <span class="switch-active" aria-hidden="true">Yes</span>
                        <span class="switch-inactive" aria-hidden="true">No</span>
                      </label>
                    </div>
                  </label>
                </div>
              </div>
              <div class="cell">
                <button class="button expanded" type="submit">Update</button>
              </div>
            </form>
          </div>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- EDIT GATEWAY PRICE MODAL END -->
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
                    <li><?php echo $this->html->link("Config",array('controller' => 'Users', 'action' => 'dash_config'));?></li>
                    <li><a href="#" class="active">Gateway</a></li>
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
              <div class="cell large-3" style="width: 100%;">
                <a href="#" class="button" data-open="addGateway">New Gateway</a>
              </div>
              <!--<div class="cell large-9 search-container">
                <div class="input-group">
                  <input class="input-group-field" id="gateway_search" name="gateway_search" type="text">
                  <div class="input-group-button">
                    <!-- FORM SHOULD AUTOFILL
                    <input type="button" id="btngateway_search" name="btngateway_search" class="button large" value="Search">
                  </div>
                </div>
              </div>-->
            </div>
			
            <table id="datatbl">
              <thead>
                <tr>
                  <th>Number<a class="sort"></a></th>
                  <th>Gateway<a class="sort"></a></th>
                  <th>Active<a class="sort"></a></th>
                </tr>  
              </thead>
              <tbody id="gateway_filter">
				<?php 
				if($gateway_val!='')
				{
					for($i=0; $i<count($gateway_val); $i++)
					{
						$gatewayid = $gateway_val[$i]['tblpaymentgateway']['Gatewayid'];
						$j = $i+1;
						echo '<tr>';
							echo '<td>'.$j.'</td>';
							echo '<td><a data-open="editGateway" onclick=editgateway('.$gatewayid.')>'.$gateway_val[$i]['tblpaymentgateway']['Gateway_name'].'</a></td>';
							echo '<td>'.$gateway_val[$i]['tblpaymentgateway']['Active'].'</td>';
						echo '</tr>';
					}
				}?>
				</tbody>
            </table>			
          </div>
        </div>
      </div>
  </body>
</html>
