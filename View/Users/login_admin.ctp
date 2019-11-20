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
		echo $this->Html->script('what-input.js');
		echo $this->Html->script('foundation.js');
		echo $this->Html->script('app.js');
		echo $this->Html->script('common.js');
		echo $this->Html->script('common_function.js');
		
	?>
	
  </head>
  <body>
	<center>
		<?php echo $this->Form->create(false, array('url' => array('controller' => 'Users', 'action' => 'login_admin'),'method'=>'POST'));?>
			<table style="width:50%">
				<tr>
					<td>Email:<input type='email' name='username' id='username'></td>
				</tr>
				<tr>
					<td>Password:<input type='password' name='password' id='password'></td>
				</tr>
				<tr>
					<td><center><input type='submit' value='Create'></center></td>
				</tr>
			</table>
		<?php echo $this->Form->end();?>
	</center>
	
  </body>
</html>