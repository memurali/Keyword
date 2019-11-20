<?php

class UsersController extends AppController {

	var $helpers = array('Html', 'Form','Csv' ,'Js', 'Paginator'); 
	
	public $uses = array('Contact','TitleLevel');
	
	public $components = array('Paginator', 'RequestHandler');
	
   	
    public $paginate = array(
        'order' => array(
            'Contact.FirstName' => 'asc'
        )
	);
	
	//login
	public function login() {
		$this->layout = 'loginlayout';
		
		if($this->Session->check('Auth.User')){
			$this->redirect(array('action' => 'master'));		
		}
	
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->Session->setFlash(__('Welcome, '. $this->Auth->user('username')));
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Session->setFlash(__('Invalid username or password'));
			}
		} 
	}
	
	function master()
	{
	$this->loadModel('TitleLevel');
		//$this->set('tbllevel',$this->TitleLevel->find('all'));
		/*if( $this->request->is('ajax') ) 
		{
			
		/*	if(!empty($this->request->data)) 
			{
				$datacompany =$this->request->data['cname'];
				$datatitle = $this->request->data['title'];
					echo ($datacompany);
				$companydata=trim($datacompany,"|");
				$titledata=trim($datatitle,"|");
				
				$searchcompany= explode("|",$companydata);
				$searchtitle= explode("|",$titledata);
				
				if(($datacompany!='') && ($datatitle!=''))
				{
					$this->Paginator->settings =$this->paginate = array('conditions' => array('Contact.Companyname' => $searchcompany,'Contact.Title' => $searchtitle),'limit' => 8);
					$searchcondition= $this->Contact->find('all', array('conditions' => array('Contact.Companyname' => $searchcompany,'Contact.Title' => $searchtitle),'limit' => 8));
				}
				else
				{
					if($datacompany!='')
					{
						$this->Paginator->settings = $this->paginate = array('conditions' => array('Contact.Companyname' => $searchcompany),'limit' => 8 );
						$searchcondition= $this->Contact->find('all', array('conditions' => array('Contact.Companyname' => $searchcompany),'limit' => 8));	
					}
					if($datatitle!='')
					{
						$this->Paginator->settings = $this->paginate = array('conditions' => array('Contact.Title' => $searchtitle),'limit' => 8 );
						$searchcondition= $this->Contact->find('all', array('conditions' => array('Contact.Title' => $searchtitle),'limit' => 8));	
					}
				}
				print_r($searchcondition);
				$data = $this->Paginator->paginate();
				$this->set('mastercontacts', $searchcondition );
				$this->render('filter');
			}*/
			if(!empty($this->request->data)) 
			{
				$datacompany =$this->request->data['cname'];
				$datatitle = $this->request->data['title'];
					
				$companydata=trim($datacompany,"|");
				$titledata=trim($datatitle,"|");
				
				$searchcompany= explode("|",$companydata);
				$searchtitle= explode("|",$titledata);
				$titlesearch=$searchtitle[0];
				$this->Paginator->settings = $this->paginate = array('conditions' => array("OR"=>array('Contact.Companyname' => $searchcompany,'Contact.Title' => $searchtitle)),'limit' => 3 );
				$data = $this->Paginator->paginate();
				$searchcondition= $this->Contact->find('all', array('conditions' => 
				array("OR"=>array('Contact.Companyname' => $searchcompany ,	'Contact.Title' => $searchtitle ,
				'Contact.Title LIKE' => "%$titlesearch%")),'limit' => 3));			
				$this->set('mastercontacts', $searchcondition );
				$this->render('filter');
			}
		
		}*/
		//$this->Paginator->settings = $this->paginate=array('limit' => 8);
        //$data =$this->Paginator->paginate('Contact');		
        //$this->set('mastercontacts', $data);
	}
	function filter()
	{
	$this->set('mastercontacts', $data );
	}
	
	function add($count=null)
	{
		$this->set('mastercontacts', $count);
	}
	function view() {
	
	if(!empty($this->data)) 
		{
			if ($this->request->is('post')) 
			{
			
				$postdata=$this->request->data['Users'];
				$this->loadmodel('Tblsavedsearch');
				if($postdata!='')
				$this->Tblsavedsearch->saveAll($postdata);
				
			}
		}	
		 $this->loadModel('Tblsavedsearch');
			 $data = $this->Tblsavedsearch->find( 
							'all', 
							array( 
								'fields' => array('id','userid','reportname','recordcount','condition','created_ts'), 
								'order' => "Tblsavedsearch.id ASC", 
								'contain' => false 
						)); 
						$this->set('tblsavedsearchs',$data); 
					
	}
	function export($id=null,$condition=null) 
	{       
		Configure::write('debug',1);    
		$this->loadModel('contact');
		$this->set('result',$this->Contact->query($condition));
		$this->autoLayout = false;
		Configure::write('debug','0');
		
	}
	function getLastQuery() 
	{
	  $dbo = $this->getDatasource();
	  $logs = $dbo->getLog();
	  $lastLog = end($logs['log']);
	  return $lastLog['query'];
	}
	
	public function logout() 
	{
		$this->redirect($this->Auth->logout());
	}


}

?>