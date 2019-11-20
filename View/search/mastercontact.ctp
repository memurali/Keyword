<?php
echo $this->Html->css("my");
//echo $form->create("Post",array('action' => 'search')); 
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge" >
<h1>Master Contact Details <h1>
<table>
	<td>
		<table>
				<tr class='table3'>
					<td>
					<img src="<?php echo $this->webroot; ?>img/plus.jpg ...?>" onclick="companyexpand('<?php echo count($mastercontacts);?>')" /></td>
					<td>CompanyName</td>
					
				</tr>
				<tr class='table2'>
				<td colspan="2" style="max-width:40%;">
			
					<div  id="company" style="display:none;" >
					<select id="cmpid" style="max-width:40%;" size="10">
					
				<?php
					echo $this->Html->script('toggle');
					$options='<option value="">--- select ---</option>';
					foreach($mastercontacts as $post):
					$companyname=$post['Post']['Companyname'];
					$options.='<option value="'.$post['Post']['Companyname'].'">'.$companyname.'</option>'; 
					endforeach; 
					echo $options;
				?> 
				</select>
				
				
				</td>			
				</tr>
				<tr class='table3'>
					<td>
					<img src="<?php echo $this->webroot; ?>img/plus.jpg ...?>" onclick="titleexpand('<?php echo count($mastercontacts);?>')" /></td>
					<td>TitleName</td>
					
				</tr>
				<tr class='table2'>
				<td colspan="2" class='table2' style="max-width:40%;">
			
					<div  id="title" style="display:none;" >
					<select id="cmpid" style="max-width:40%;" size="10">
					
				<?php
					echo $this->Html->script('toggle');
					$options='<option value="">--- select ---</option>';
					foreach($mastercontacts as $post):
					$titlename=$post['Post']['Title'];
					$options.='<option value="'.$post['Post']['Title'].'">'.$titlename.'</option>'; 
					endforeach; 
					echo $options;
				?> 
				</select>
				
				
				</td>			
				</tr>
		</table>
	</td>
	<td>
			<table>
						<tr class='table1'>
									<th>FirstName</th>
									<th>MiddleName</th>
									<th>LastName</th>
									<th>Title</th>
									<th>Companyname</th>
									<th>Address</th>
									<th>City</th>
									<th>State</th>
									<th>Zip</th>
									<th>Country</th>
									<th>Phone</th>
									<th>Email</th>
									<th>Fax</th>
									<th>Website</th>
						</tr>
			<?php
			foreach($mastercontacts as $post):?>
				<tr class='table2'>
						<td><?php echo $post['Post']['FirstName'];?></td>
						<td><?php echo $post['Post']['MiddleName'];?></td>
						<td><?php echo $post['Post']['LastName'];?></td>
						<td><?php echo $post['Post']['Title'];?></td>
						<td><?php echo $post['Post']['Companyname'];?></td>
						<td><?php echo $post['Post']['Address'];?></td>
						<td><?php echo $post['Post']['City'];?></td>
						<td><?php echo $post['Post']['State'];?></td>
						<td><?php echo $post['Post']['Zip'];?></td>
						<td><?php echo $post['Post']['Country'];?></td>
						<td><?php echo $post['Post']['Fax'];?></td>
						<td><?php echo $post['Post']['Email'];?></td>
						<td><?php echo $post['Post']['Website'];?></td>
				</tr>
			<?php endforeach;
			 ?>
			</table>
	</td>
</table>












