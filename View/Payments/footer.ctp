<footer>
    <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="cell medium-6">
           <?php echo $this->Html->link("Keyword GPS", array('controller' => 'Users','action' => 'index'),array('class' => 'logo'));?>
          </div>
          <div class="cell medium-6">
            <ul class="menu">
              <li><?php echo $this->Html->link("Home", array('controller' => 'Users','action' => 'index'));?></li>
              <li><?php echo $this->Html->link("About", array('controller' => 'users','action' => 'selection_subcat','slug' =>'about'));?></li>
              <li><?php echo $this->Html->link("Contact", array('controller' => 'users','action' => 'selection_subcat','slug' =>'contact'));?></li>
              <li><?php echo $this->Html->link("FAQ", array('controller' => 'users','action' => 'selection_subcat','slug' => 'faq'));?></li>
            </ul>
          </div>
        </div>
    </div>
</footer>
	
	