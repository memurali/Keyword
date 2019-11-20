<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
	
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
		var url      = window.location.href;
		var ind = url.indexOf('all');
		if(ind!='-1')
		{
			testlogin();
		}
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
    <!-- ORDER ID MODAL BEGIN -->
    <div class="reveal small" id="orderID" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>OrderId <span id="orderid"></span></h2>
          </div>
          <div class="cell">
            <table>
              <thead>
                <tr>
                  <th>Subcategory</th>
                  <th>No of Keywords</th>
				  <th class="noofkw">New keywords</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody id="orderdata">
                <!--------- Ajax data prints here------>
              </tbody>
            </table>
          </div>
        </div>  
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- ORDER ID MODAL MODAL END -->
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
                    <li><a class="active" href="#">Orders<span><?php echo $total_orders;?></span></a></li>
                    <li><?php echo $this->html->link("Users",array('controller' => 'Users', 'action' => 'dash_users'));?></li>
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
            <!--<div class="grid-x grid-margin-x">
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
			<form name='editdatefrm' id='editdatefrm' method="POST">
				<table id="datatbl">
				  <thead>
					<tr>
					  <th>Name<a class="sort"></a></th>
					  <th>Email<a class="sort"></a></th>
					  <th>Order ID<a class="sort"></a></th>
					  <th>Total Keyword<a class="sort"></a></th>
					  <th>Invoice Amount<a class="sort"></a></th>
					  <th>Expiration Date<a class="sort"></a></th>
					  <th>Date of Purchase<a class="sort"></a></th>
					  <th></th>
					</tr>  
				  </thead>
				  <tbody>					
					<?php
					foreach($viewarr as $val)
					{
						$orderidarr[] = $val['tblshoppingcart']['Orderid'];
					}
					for($o=0; $o<count($order_arr); $o++)
					{
						$orderid = $order_arr[$o]['a']['Orderid'];
						$class = '';
						if(in_array($orderid, $orderidarr))
						{
							$class = 'new-order';
						}
						else
						{
							$class = '';
						}
						$purchasedate = date('m-d-Y',strtotime($order_arr[$o]['a']['Datecreated']));
						echo '<tr class='.$class.'>';
							echo '<td>'.$order_arr[$o]['b']['Name'].'</td>';
							echo '<td>'.$order_arr[$o]['b']['Email'].'</td>';
							echo '<td><a data-open="orderID" onclick=getcartdata_admin('.$order_arr[$o]['a']['Orderid'].')>'.$order_arr[$o]['a']['Orderid'].'</td>';
							echo '<td>'.$order_arr[$o][0]['keycount'].'</td>';
							echo '<td>'.$order_arr[$o][0]['totprice'].'</td>';
							echo '<td>
									<input type=date name=expdate'.$o.' id=expdate'.$o.' value='.$order_arr[$o]['a']['Expiration_Date'].'>
									<input type=hidden name=orderid'.$o.' id=orderid'.$o.' value='.$order_arr[$o]['a']['Orderid'].'>
								 </td>';
							echo '<td>'.$purchasedate.'</td>';
							echo "<td><input type='button' class='button tiny'  value='Save' onclick='expdate_change(".$o.");'></td>";
						echo '</tr>';
					} ?>
				  </tbody>
				</table> 
			</form>		
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
