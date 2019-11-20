<?php echo $form->create('Post',array('action'=>'search'));?>
	<fieldset>
 		<legend><?php __('Post Search');?></legend>
	<?php
		echo $form->input('Search.keywords');
		echo $form->input('Search.id');
		echo $form->input('Search.name',array('after'=>__('wildcard is *',true)));
		echo $form->input('Search.body',array('after'=>__('wildcard is *',true)));
		echo $form->input('Search.active',array(
			'empty'=>__('Any',true),
			'options'=>array(
				0=>__('Inactive',true),
				1=>__('Active',true),
			),
		));
		echo $form->input('Search.created', array('after'=>'eg: >= 2 weeks ago'));
		echo $form->input('Search.category_id');
		echo $form->input('Search.tag');
		echo $form->input('Search.tag_id');
		echo $form->submit('Search');
	?>
	</fieldset>
<?php echo $form->end();?>
