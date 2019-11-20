<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
			 "pageLength":9,
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
  <!-- ORDER ID MODAL BEGIN -->
    <div class="reveal small" id="orderID" data-reveal>
      <div class="grid-container">
        <div class="grid-x grid-padding-x grid-margin-x">
          <div class="cell">
            <h2>Order Id <span id="orderid"></span></h2>
          </div>
          <div class="cell">
            <table>
              <thead>
                <tr>
                  <th>Subcategory</th>
                  <th>No of Keywords</th>
				  <th class="noofkw">New keywords</th>
                  <th>Amount</th>
				  <th>Download</th>
                </tr>
              </thead>
              <tbody id="cartdata">
				<!--- Ajax data ---->
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
              <h2>Welcome!</h2>
              <div class="grid-x">
                <div class="cell">
                  <ul class="vertical menu">
                    <li><?php echo $this->html->link("Home",array('controller' => 'Users', 'action' => 'index'));?></li>
                    <li><?php echo $this->html->link("Logout",array('controller' => 'Users', 'action' => 'logout'));?></li>
                  </ul>
                </div>
              </div>
            </aside>
          </div>
        </div>
        <div class="cell large-10">
          <!--<div class="hero">
            <div class="grid-container text-center">
              <div class="grid-x search-container">
                <div class="cell callout">
                  <div class="input-group">
                    <input class="input-group-field" id="userdash_search" name="userdash_search" type="text" placeholder="Search Keywords">
                    <div class="input-group-button">
                      <!-- FORM SHOULD AUTOFILL  
					  <input type="button" id="btnuserdash_search" name="btnuserdash_search" class="button large" value="Search">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>-->
          <div class="content">
            <table id="datatbl">
              <thead>
                <tr>
                  <th>Orderid<a class="sort"></a></th>
				  <th>Total keywords<a class="sort"></a></th>
				  <th>Invoice amount<a class="sort"></a></th>
				  <th>Expiration Date<a class="sort"></a></th>
				  <th>Date of purchase<a class="sort"></a></th>
                </tr>  
              </thead>
              <tbody>
			  <?php
				$size = sizeof($groupdata);
				for($s=0; $s<$size; $s++)
				{
					echo '<tr>';
						$orderid =$groupdata[$s]['a']['Orderid'] ;								
						echo '<td><a data-open=orderID onclick=getcartdata('.$orderid.');>'.$orderid.'</a></td>';
						echo '<td>'.$groupdata[$s][0]['keycount'].'</td>';
						echo '<td>'.'$'.$groupdata[$s][0]['totprice'].'</td>';
						echo '<td>'.date('m-d-Y',strtotime($groupdata[$s]['a']['Expiration_Date'])).'</td>';
						echo '<td>'.date('m-d-Y',strtotime($groupdata[$s]['a']['Datecreated'])).'</td>';
					echo '</tr>';
				}
				?>                
              </tbody>
            </table>
            <!--<div class="grid-x">
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
