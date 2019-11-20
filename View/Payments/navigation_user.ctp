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
    <div class="title-bar" data-responsive-toggle="example-animated-menu" data-hide-for="medium">
      <button class="menu-icon" type="button" data-toggle></button>
      <div class="title-bar-title">Menu</div>
    </div>

    <div class="top-bar" id="example-animated-menu" data-animate="hinge-in-from-top spin-out">
      <div class="top-bar-left">
        <ul class="menu">
          <li class="menu-text"><?php echo $this->Html->link("Keyword GPS", array('controller' => 'Users','action' => 'index'));?></li>
        </ul>
      </div>
      <div class="top-bar-right">
        <ul class="dropdown menu" data-dropdown-menu>
          <li>
            <a href="#" class="profile">Menu</a>
            <ul class="menu vertical">
               <li><?php echo $this->Html->link("About", array('controller' => 'users','action' => 'selection_subcat','slug' => 'about'));?></li>
			   <li><?php echo $this->Html->link("Contact", array('controller' => 'users','action' => 'selection_subcat','slug' =>'contact'));?></li>
			   <li><?php echo $this->Html->link("FAQ", array('controller' => 'users','action' => 'selection_subcat','slug' =>'faq'));?></li>
            </ul>
          </li>
          <li>
		    <?php echo $this->Html->link($this->Html->tag('span', sizeof($cart_desc),array('id'=>'btncart')), array('controller' => 'payments',
					'action' => 'shopping_cart'),array('escape'=>false ,'class'=>'cart-notification'));?>
		  </li>
        </ul>
      </div>
    </div>
 