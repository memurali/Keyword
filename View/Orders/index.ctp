<!-- app/View/Orders/index.ctp -->


<div class="users form">
<h1>Report</h1>
<table>
    <thead>
		<tr>
			
			<th>Reportname</th>
			<th>ReportCount</th>
			<th>DateCreated</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>						
		<?php $count=0; ?>
		<?php 
		foreach($tblsavedsearchs as $tblsavedsearch):
		?>				
		<?php $count ++;?>
		<?php if($count % 2): echo '<tr>'; else: echo '<tr class="zebra">' ?>
		<?php 
			endif; 
		?>
			<td><?php echo $this->Form->checkbox('Tblsavedsearch.id.'.$tblsavedsearch['Tblsavedsearch']['id']); ?></td>
			<td><?php echo $this->Html->link( $tblsavedsearch['Tblsavedsearch']['reportname']  ,   array('action'=>'edit', $tblsavedsearch['Tblsavedsearch']['id']),array('escape' => false) );?></td>
			<td style="text-align: center;"><?php echo $tblsavedsearch['Tblsavedsearch']['recordcount']; ?></td>
			<td style="text-align: center;"><?php echo $this->Time->niceShort($tblsavedsearch['Tblsavedsearch']['created_ts']); ?></td>
			
			<td >
			<?php 
				echo $this->Html->link(    "Export",   array('action'=>'exportnew', $tblsavedsearch['Tblsavedsearch']['id']) ); 
			?> 
			
			</td>
		</tr>
		<?php endforeach; ?>
		<?php unset($tblsavedsearch); ?>
	</tbody>
</table>

</div>				

<br/>
<?php 
echo $this->Html->link( "Logout",   array('action'=>'logout') ); 
?>