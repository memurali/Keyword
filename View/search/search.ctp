<?php 

echo $this->Form->create('Article', array(
	'url' => array_merge(
			array(
				'action' => 'find'
			),
			$this->params['pass']
		)
	)
);
echo $this->Form->input('title', array(
		'div' => false
	)
);
echo $this->Form->input('year', array(
		'div' => false
	)
);
echo $this->Form->input('blog_id', array(
		'div' => false,
		'options' => $blogs
	)
);
echo $this->Form->input('status', array(
		'div' => false,
		'multiple' => 'checkbox',
		'options' => array(
			'open', 'closed'
		)
	)
);
echo $this->Form->input('username', array(
		'div' => false
	)
);
echo $this->Form->submit(__('Search'), array(
		'div' => false
	)
);
echo $this->Form->end();
?>