<?php
App::build(array('Vendor' => array(APP . 'Vendor' . DS . 'stripe-php' . DS)));
error_reporting(E_ALL ^ E_NOTICE);
class UsersController extends AppController
{   
    var $helpers = array('Html', 'Form', 'Js', 'Paginator');    
    public $components = array('Paginator', 'RequestHandler', 'Stripe');
    public $uses = array('Tblcategory','Tblsubcategory','Tblkeyword','Tblpricing','Tblpricehistory','Tblpaymentgateway','Tblconfig','User','Tblshoppingcart');
    public function beforeFilter()
    {
		parent::beforeFilter();
		$this->Auth->allow('shopping_cart','navigation','navigation_user','about','contact','faq','index','category_landing','directory','selection_subcat','login_admin');
    }
	public function logout()
    {
        $this->Session->delete('description_arr');
		$this->Session->delete('subid_arr');
		$this->Session->delete('catid_arr');
		$this->Session->delete('totcount_arr');
		$this->Session->delete('totprice_arr');
		$this->Session->delete('totflow_arr');
		$this->Session->delete('subcount_arr');
		$this->Session->delete('subprice_arr');
		
		$this->redirect($this->Auth->logout());
		
    }
	function index()
	{
		$this->layout = false;
		$this->Session->delete('searchtext');
		$action=$this->request->params['action'];
		$this->set('action',$action);
        if ($this->request->is('post')) 
		{
			if($_POST['data']['User']['username']!='')
			{
				if ($this->Auth->login()) {
					
					if($this->Auth->user('Role')=='admin')
					{
						return $this->redirect( array('controller' => 'Users','action' => 'dash_users'));
					}
					else
					{
						return $this->redirect( array('controller' => 'users','action' => 'user_dashboard'));
					}
					
				} 
				else 
				{
					$login_error = 'Invalid username or password';
					$this->set('loginerror',$login_error);
				}
			}
			$userid = $this->Auth->user('Userid');
			$text = $_POST['category_search'];
			$nodataval = $_POST['nodata'];
			if($nodataval=='')
			{
				if($text!='')
				{
					$this->Session->write('searchtext', $text);
					
					return $this->redirect( array('controller' => 'users',
												  'action' => 'category_landing',
												  'param'=>'search',
												  'param1' => strtolower($text)					
												));
					
				}
			}
			else
				return false;

        }
		if ($this->request->is('ajax')) 
		{
			//for signup
			if($this->request->data['fun']=='signup')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->signup($mode,$param,$action);
				echo $return;
				exit;
			}
			
			//for forgotpassword
			if($this->request->data['fun']=='forgotpwd')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->forgotpassword($mode,$param,$action);
				echo $return;
				exit;
			}
			
			// for smart search 
			if($this->request->data['mode']=='smartsearch')
			{
				$textval = $this->request->data['textval'];
				echo $response = $this->smartsearch($textval);
				exit;
			}
		}
		$userid = $this->Auth->user('Userid');
		$this->set('userid',$userid);		
		$role = $this->Auth->user('Role');
		$this->set('role', $role);
		$data_cat_arr = $this->allcategory('all');
		$this->set("data_cat",$data_cat_arr);
		$sel_all_qry = "SELECT s.`Subcategoryid`,s.`Subcategory`, c.`Categoryid`, c.Category 
						FROM `tblsubcategory` s, tblcategory c,tblkeyword k 
						WHERE c.`Categoryid` = s.`Categoryid` 
						AND s.Subcategoryid = k.Subcategoryid
						AND k.Subcategoryid!=''
						AND s.Active='Y' 
						AND c.Active='Y'
						GROUP by s.Subcategoryid
						order by c.Category asc, s.Subcategory ASC";
		$sel_all_arr = $this->Tblsubcategory->query($sel_all_qry);
		$this->set("alldata_arr",$sel_all_arr);
		
		/**** cart deatils    *******/
		$all_desc = $this->Session->read('description_arr');
		$this->set("cart_desc",$all_desc);

	}
	function directory()
	{
				
	}
	function selection_subcat()
	{
		set_time_limit(0);
		$this->layout = false;
		$slug = $this->params['slug'];
		$this->set('action',$slug);
		if($this->request->is('post') == 1)
		{
			if($_POST['data']['User']['username']!='')
			{
				if ($this->Auth->login()) 
				{					
					//$this->redirect($this->Auth->redirectUrl());
				} 
				else 
				{
					$login_error = 'Invalid username or password';
					$this->set('loginerror',$login_error);
				}
			}
		}
		$userid = $this->Auth->user('Userid');
		$this->set('userid',$userid);
		$all_desc = $this->Session->read('description_arr');
		$this->set('cart_desc',$all_desc);
		if ($this->request->is('ajax')) 
		{
			//for signup
			if($this->request->data['fun']=='signup')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->signup($mode,$param,$action);
				echo $return;
				exit;
			}
			//for forgotpassword
			if($this->request->data['fun']=='forgotpwd')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->forgotpassword($mode,$param,$action);
				echo $return;
				exit;
			}
			if($this->request->data('mode')=='addtocart')
			{
				parse_str( $_POST[ 'formvals' ], $formdata );
				$return = $this->addtocart($formdata);
				echo $return;
				exit;
			}		
			if($this->request->data('mode')=='checkaddtocart')
			{
				parse_str( $_POST[ 'formvals' ], $formdata );
				$return = $this->alreadyadded($formdata);
				echo $return;
				exit;
			}
			// for smart search 
			if($this->request->data['mode']=='smartsearch')
			{
				$textval = $this->request->data['textval'];
				echo $response = $this->smartsearch($textval);
				exit;
			}
		}
		if($slug=='about')
		{
			$this->render('about');
			return false;
		}
		if($slug=='faq')
		{
			$this->render('faq');
			return false;
		}
		if($slug=='contact')
		{
			if ($this->request->is('ajax')) 
			{
				if($this->request->data['mode']=='contact')
				{
					parse_str( $_POST[ 'formval' ], $formdata );
					$fromaddress = ''.$formdata['emailval'].'';
					$toaddress = Configure::read('admin_mail');
					$headers = "MIME-Version: 1.0"."\r\n";
					$headers .= "Content-type: text/html"."\r\n";
					$headers .= "From:" . $fromaddress . "\r\n";
					$subject = 'Questions!';
					$message = ''.$formdata['message'].'';
					if(mail($toaddress,$subject,$message,$headers))
						echo 'Email sent successfully';
					else
						echo 'Email not sent';
					exit;
				}
			}
			$this->render('contact');
			return false;
		}
		if($slug=='directory')
		{
			$this->set('userid',$userid);
			$this->set('role',$role);
			$this->Session->delete('searchtext');
			$data_cat_arr = $this->allcategory('all');
			$this->set("data_cat",$data_cat_arr);
			$select_all_qry = "SELECT `Keyword`,`G_Volume`,`US_Volume` 
								FROM `tblkeyword`,tblsubcategory,tblcategory 
								WHERE tblsubcategory.Active='Y' 
								AND tblcategory.Active='Y' 
								AND tblsubcategory.Subcategoryid=tblkeyword.Subcategoryid 
								AND tblcategory.Categoryid = tblsubcategory.Subcategoryid
								ORDER BY Keyword ASC LIMIT 25";
			$allkeyword_arr = $this->Tblkeyword->query($select_all_qry);
			$this->set('allkwarr',$allkeyword_arr);
			
			/**** cart deatils    *******/
			$all_desc = $this->Session->read('description_arr');
			$this->set("cart_desc",$all_desc);
			
			$this->render('directory');
			return false;
		}
				
		/***    selection_subcat function *****/
		$this->Session->delete('searchtext');
		$slug = $this->params['slug'];
		$select_all_qry = "SELECT c.Categoryid,s.Subcategoryid,k.Keyword,k.G_Volume,k.US_Volume,
							s.Keyword_count
							FROM `tblkeyword` k, tblsubcategory s,tblcategory c 
							WHERE c.Category LIKE '%".$slug."%' 
							AND c.Categoryid = s.Categoryid 
							AND s.Subcategoryid = k.`Subcategoryid` 
							AND s.Active = 'Y'
							AND c.Active = 'Y'
							ORDER by G_Volume DESC LIMIT 25";
		$allkeyword_arr = $this->Tblkeyword->query($select_all_qry);
		
		$select_valid = "SELECT c.Categoryid,s.Subcategoryid,s.Keyword_count
							FROM `tblkeyword` k, tblsubcategory s,tblcategory c 
							WHERE c.Category LIKE '%".$slug."%' 
							AND c.Categoryid = s.Categoryid 
							AND s.Subcategoryid = k.`Subcategoryid` 
							AND s.Active = 'Y'
							AND c.Active = 'Y' GROUP BY s.Subcategoryid";
		$valkeyword_arr = $this->Tblkeyword->query($select_valid);
		
		$this->set('allkwarr',$allkeyword_arr);

		$this->set('category_txt',$slug);
		$this->set('param_txt',$slug);
		if(sizeof($allkeyword_arr)>0)
		{
			$categoryid = $allkeyword_arr[0]['c']['Categoryid'];		
			$subcatdata = $this->countfun($categoryid);
			$this->set('subcatarr',$subcatdata);
		}
		$action=$this->request->params['action'];
		$this->set('action',$action);
		
		/**** cart deatils    *******/
		$all_desc = $this->Session->read('description_arr');
		$this->set("cart_desc",$all_desc);
		if($userid=='')
		{
			$this->set("subcat_arr",$valkeyword_arr);
			$price_arr = $this->getprice($valkeyword_arr);
			
		}
		else
		{
			if(sizeof($valkeyword_arr)>0)
				$filter_arr = $this->validate_kw($valkeyword_arr);
			if(sizeof($filter_arr)>0)
			{
				$this->set("subcat_arr",$filter_arr[1]);
				$price_arr = $this->getprice($filter_arr[1]);
			}
			else
			{
				$this->set("subcat_arr",$valkeyword_arr);
				$price_arr = $this->getprice($valkeyword_arr);
			}
		}
		$this->set('totalcount',$price_arr[0]);
		$this->set('price',$price_arr[1]);
		$this->set('subprice',$price_arr[2]);
		
	}
	function category_landing()
	{
		set_time_limit(0);
		$this->layout = false;
		$param = $this->params['param'];
		$param1 = str_replace('-',' ',$this->params['param1']);		
		$action=$this->request->params['action'];
		$this->set('action',$action);
		/**** cart data flow   ****/
		if($this->request->is('ajax'))
		{
			//for signup
			if($this->request->data['fun']=='signup')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->signup($mode,$param,$action);
				echo $return;
				exit;
			}
			//for forgotpassword
			if($this->request->data['fun']=='forgotpwd')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->forgotpassword($mode,$param,$action);
				echo $return;
				exit;
			}
			if($this->request->data('mode')=='addtocart')
			{
				parse_str( $_POST[ 'formvals' ], $formdata );
				$return = $this->addtocart($formdata);
				echo $return;
				exit;
			}
			if($this->request->data('mode')=='checkaddtocart')
			{
				parse_str( $_POST[ 'formvals' ], $formdata );
				$return = $this->alreadyadded($formdata);
				echo $return;
				exit;
			}
			// for smart search 
			if($this->request->data['mode']=='smartsearch')
			{
				$textval = $this->request->data['textval'];
				echo $response = $this->smartsearch($textval);
				exit;
			}
			
		}
		/** selection_subcat  function *****/
		if($param!='search')
		{
			if($this->request->is('post') == 1)
			{
				if($_POST['data']['User']['username']!='')
				{
					if ($this->Auth->login())
					{					
						//$this->redirect($this->Auth->redirectUrl());
					} 
					else 
					{
						$login_error = 'Invalid username or password';
						$this->set('loginerror',$login_error);
					}
				}
			}
			$userid = $this->Auth->user('Userid');
			$this->set('userid',$userid);
			$this->Session->delete('searchtext');
			$userid = $this->Auth->user('Userid');
			$this->set('userid',$userid);
			$role = $this->Auth->user('Role');
			$this->set('role',$role);
			$select_all_qry = "SELECT s.Categoryid,k.Keyword,k.G_Volume,k.US_Volume
							   FROM `tblkeyword` k, tblsubcategory s 
							   WHERE s.Subcategory LIKE '%".$param1."%' 
							   AND s.Subcategoryid = k.`Subcategoryid`
							   AND s.Active = 'Y'
							   ORDER by k.G_Volume DESC LIMIT 25";
			$allkeyword_arr = $this->Tblkeyword->query($select_all_qry);
			
			$select_sub_qry= "SELECT c.Categoryid,s.Subcategoryid,s.Keyword_count,
							   SUM(k.G_Volume) as totalvol
							   FROM `tblkeyword` k, tblsubcategory s,tblcategory c 
							   WHERE s.Subcategory LIKE '%".$param1."%' 
							   AND s.Subcategoryid = k.`Subcategoryid`
							   AND c.Categoryid = s.Categoryid
							   AND s.Active = 'Y'";
			$allkeyword_subarr = $this->Tblkeyword->query($select_sub_qry);	
			$this->set('subarr',$allkeyword_subarr);		
			
			$this->set('category_txt',$param);
			$this->set('subcat_txt',$param1);
			if(sizeof($allkeyword_arr)>0)
			{
				$this->set('allkwarr',$allkeyword_arr);
				$categoryid = $allkeyword_arr[0]['s']['Categoryid'];
				$subcatdata = $this->countfun($categoryid);
				$this->set('subcatarr',$subcatdata);
				$subcategory = $param1;
				$this->set('param_txt',$subcategory);
				
			}
			/**** cart deatils    *******/
			$all_desc = $this->Session->read('description_arr');
			$this->set("cart_desc",$all_desc);
			
			if($userid=='')
			{
				$this->set("subcat_arr",$allkeyword_subarr);
				$price_arr = $this->getprice($allkeyword_subarr);
				
			}
			else
			{
				if(sizeof($allkeyword_subarr)>0)
					$filter_arr = $this->validate_kw($allkeyword_subarr);
				if(sizeof($filter_arr)>0)
				{
					$this->set("subcat_arr",$filter_arr[1]);
					$price_arr = $this->getprice($filter_arr[1]);
				}
				else
				{
					$this->set("subcat_arr",$allkeyword_subarr);
					$price_arr = $this->getprice($allkeyword_subarr);
				}
			}
			$this->set('totalcount',$price_arr[0]);
			$this->set('price',$price_arr[1]);
			$this->set('subprice',$price_arr[2]);
			$this->set('flowval','link');
			$this->render('selection_subcat');
			return false;
		}
		

		
		/***  Get data to view *******/
		if($this->request->is('post') == 1)
		{
			if($_POST['data']['User']['username']!='')
			{
				if ($this->Auth->login())
				{					
					//$this->redirect($this->Auth->redirectUrl());
				} 
				else 
				{
					$login_error = 'Invalid username or password';
					$this->set('loginerror',$login_error);
				}
				if($_POST['sesstxt']!='')
				{
					$this->Session->write('searchtext', $_POST['sesstxt']);
				}
			}
			if($_POST['landing_search']!='')
			{
				$this->Session->delete('searchtext');
				$text = $_POST['landing_search'];											
				$nodataval = $_POST['nodata'];
				if($nodataval=='')
				{
					if($text!='')
					{
						$this->Session->write('searchtext', $text);
						
						return $this->redirect( array('controller' => 'users',
													  'action' => 'category_landing',
													  'param'=>'search',
													  'param1' => strtolower($text)					
													));						
					}
				}
				else
					return false;					
											
			}
		}
		$userid = $this->Auth->user('Userid');
		$this->set('userid',$userid);
		$role = $this->Auth->user('Role');
		$this->set('role',$role);
		$session_text = $this->Session->read('searchtext');
		if($session_text=='')
		{
			$param1 = str_replace('-',' ',$this->params['param1']);
			$sel_id_qry = "SELECT `Subcategoryid` FROM `tblsubcategory` 
						   WHERE `Subcategory` = '".$param1."'
						   AND Active ='Y'";
			$sel_id_arr = $this->Tblsubcategory->query($sel_id_qry);
			if(sizeof($sel_id_arr)==0)
			{
				$sel_id_qry = "SELECT `Subcategoryid` FROM `tblsubcategory` 
								WHERE `Subcategory` LIKE '%".$param1."%'
								AND Active ='Y'";
				$sel_id_arr = $this->Tblsubcategory->query($sel_id_qry);
				if(sizeof($sel_id_arr)==0)
				{
					/** when search in url (SEO)**/
					$text = $param1;
					if(strpos($text,"'")>0)
					$text = str_replace("'","",$text);
					$this->set('subcatval',$text);
					$response = $this->searchbar($text,'searchtxt');
					$this->set('flowval','search');
					$this->set('sesstxt',$text);
					$this->Session->delete('searchtext');
				}
				else
				{
					$subcatid = $sel_id_arr[0]['tblsubcategory']['Subcategoryid'];
					$response = $this->searchbar($subcatid,'subcatid');
					$this->set('subcatval',$response[0][0]['s']['Subcategory']);
					$this->set('flowval','link');
				}
				
			}
			else
			{
				$subcatid = $sel_id_arr[0]['tblsubcategory']['Subcategoryid'];
				$response = $this->searchbar($subcatid,'subcatid');
				$this->set('subcatval',$response[0][0]['s']['Subcategory']);
				$this->set('flowval','link');
			}
			
		}
		else if ($session_text!='')
		{
			$text = $session_text;
			if(strpos($text,"'")>0)
				$text = str_replace("'","",$text);
			$this->set('subcatval',$text);
			$response = $this->searchbar($text,'searchtxt');
			$this->set('flowval','search');
			$this->set('sesstxt',$text);
			$this->Session->delete('searchtext');
		}
		$subcat_data = $response[0];
		$this->Session->write('payment_subcat',$subcat_data);
		$allkw = $response[1];
		if(sizeof($response[0])>0)
		{
			foreach($response[0] as $searchval) {
				$totsearchvol += $searchval[0]['totvol'];				
			}
			$related_kw = $this->relatedkwgrp($response[0]);
			$this->set('relatedkws',$related_kw);
		}
		$totalkeycount = $response[2][0][0]['totcount'];
		$this->set('allview',$allkw);
		$this->set('kw_count',$totsearchvol);		
		$this->set('noofcount',$totalkeycount);
		if($userid=='')
		{
			$this->set("subcat_arr",$subcat_data);
			$price_arr = $this->getprice($subcat_data);
			
		}
		else
		{
			if(sizeof($subcat_data)>0)
				$filter_arr = $this->validate_kw($subcat_data);
			if(sizeof($filter_arr)>0)
			{
				$this->set("view_arr",$filter_arr[0]);
				$this->set("subcat_arr",$filter_arr[1]);
				$price_arr = $this->getprice($filter_arr[1]);
			}
			else
			{
				$this->set("subcat_arr",$subcat_data);
				$price_arr = $this->getprice($subcat_data);
			}
		}
		$this->set('totalcount',$price_arr[0]);
		$this->set('price',$price_arr[1]);
		$this->set('subprice',$price_arr[2]);
		$this->set('flowval','link');
		/**** cart deatils    *******/
		$all_desc = $this->Session->read('description_arr');
		$all_count = $this->Session->read('totcount_arr');
		$all_price = $this->Session->read('totprice_arr');
		
		$this->set("cart_desc",$all_desc);
		$this->set("cart_count",$all_count);
		$this->set("cart_price",$all_price);
		
	}
	function user_dashboard()
	{
		set_time_limit(0);
		$this->layout = false;
		$userid = $this->Auth->user('Userid');
		$select = "SELECT a.Userid,a.Orderid,a.Expiration_Date,a.Datecreated,
					SUM(a.Keyword_count) as keycount, SUM(a.Price) as totprice  
					FROM tblshoppingcart a where a.Userid = ".$userid." 
					GROUP BY Orderid ORDER BY a.Orderid DESC";
		$data = $this->Tblshoppingcart->query($select);
		$this->set("groupdata",$data);
		//view cart data
		if ($this->request->is('ajax')) 
		{
			if($this->request->data['orderid']!='')
			{
				$orderid = $this->request->data['orderid'];
				$query = "SELECT a.*, b.Subcategory FROM `tblshoppingcart` a, tblsubcategory b 
						  WHERE a.Orderid = ".$orderid."
						  AND a.`Subcategoryid` = b.Subcategoryid";					  
				$cartdata = $this->Tblshoppingcart->query($query);
				$size = sizeof($cartdata);
				$date = date('y-m-d');
				$i=0;					
				for($s=0; $s<$size; $s++)
				{				
					$subcategory = $cartdata[$s]['b']['Subcategory'];
					$subcatid = $cartdata[$s]['a']['Subcategoryid'];
					$catid = $cartdata[$s]['a']['Categoryid'];
					$noofkw = $cartdata[$s]['a']['Keyword_count'];	
					$amount = $cartdata[$s]['a']['Price'];	
					$filename = $cartdata[$s]['a']['Filename'];
					$expdate = $cartdata[$s]['a']['Expiration_Date'];
					$checkqry= "SELECT SUM(Keyword_count) as new_keycount FROM 
							`tblshoppingcart` where Orderid 
							NOT IN(".$orderid.") and Subcategoryid='".$subcatid."' and 
							Expiration_Date < '".$expdate."' and Userid='".$userid."'";
	
					$check = $this->Tblshoppingcart->query($checkqry);
					$sizecheck = sizeof($check);
					
					$exp_date = strtotime($expdate); 
					$now_date = strtotime($date);					
					$response.='<tr>';
					$response.='<td>'.$subcategory.'</td>';
					if($sizecheck>0)
					{
						if($check[0][0]['new_keycount']>0 && $check[0][0]['new_keycount']!=null)
						{
							$totalkeycount= $noofkw+$check[0][0]['new_keycount'];
							$response.='<td>'.$totalkeycount.'</td>';
							$response.='<td class=noofkw>'.$noofkw.'</td>';
							$i++;
						}
						else
						{
							$totalkeycount=$noofkw;
							$response.='<td>'.$noofkw.'</td>';
							$response.='<td class=noofkw>-</td>';
						}						
						$response.='<td>'.'$'.$amount.'</td>';
						$totcount+=$totalkeycount;
						$totprice+=$amount;
					}
					if($exp_date > $now_date)	
					{	
						$response.='<td>									  
									   <div class="cell small-6">
										<a href=../Csvs/dash_export/'.$subcatid.' class="button tiny expanded">CSV</a>
									   </div>									 
									</td>';
					}
					else
					{						
						$response.='<td>									 
									  <div class="cell small-6">
									   <a href=../Export_csv/'.$filename.' class="button tiny expanded"download="'.$cartdata[$s]['b']['Subcategory'].'.csv">CSV</a>
									  </div>									
									</td>';
					}
					$response.='</tr>';
				}
				$response.='<tr>
								<td>Total</td>
								<td>'.$totcount.'</td>
								<td class=noofkw></td>
								<td>'.'$'.number_format((float)$totprice, 2, '.', '').'</td>			
								<td></td>
							</tr>';
				
				$response.='</table>';
				$myObj = new \stdClass();
				if($i==0)
					$myObj->style = 'none';
				else			
					$myObj->style = '';			
					
				$myObj->response = $response;
				$myObj->orderid = $orderid;		
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
			}
		}
	}
	
	function dash_orders()
	{
		set_time_limit(0);		
		$this->layout = false;
		$sel_qry = "SELECT `Orderid` FROM `tblshoppingcart` 
		            WHERE `View_status` ='' 
					ORDER BY `Orderid` DESC";
		$view_data = $this->Tblshoppingcart->query($sel_qry);
		$this->set("viewarr",$view_data);
		if ($this->request->is('ajax')) 
		{
			/** update orders viewed by admin ***/
			if($this->request->data['mode']=='testlogin')
			{
				$update_qry = "UPDATE `tblshoppingcart` SET 
							  `View_status`='Viewed' 
							  WHERE `View_status`=''";
				
				$this->Tblshoppingcart->query($update_qry);
				exit;
			}
			//view cart data
			if($this->request->data['orderid']!='')
			{
				$orderid = $this->request->data['orderid'];
				$query = "SELECT a.*, b.Subcategory FROM `tblshoppingcart` a, tblsubcategory b 
						  WHERE a.Orderid = ".$orderid."
						  AND a.`Subcategoryid` = b.Subcategoryid";					  
				$cartdata = $this->Tblshoppingcart->query($query);
				$size = sizeof($cartdata);
				$date = date('y-m-d');
				$i=0;					
				for($s=0; $s<$size; $s++)
				{
					$user = $cartdata[$s]['a']['Userid'];
					$subcategory = $cartdata[$s]['b']['Subcategory'];
					$subcatid = $cartdata[$s]['a']['Subcategoryid'];
					$catid = $cartdata[$s]['a']['Categoryid'];
					$noofkw = $cartdata[$s]['a']['Keyword_count'];	
					$amount = $cartdata[$s]['a']['Price'];	
					$filename = $cartdata[$s]['a']['Filename'];
					$expdate = $cartdata[$s]['a']['Expiration_Date'];
					$checkqry= "SELECT SUM(Keyword_count) as new_keycount FROM 
							`tblshoppingcart` where Orderid 
							NOT IN(".$orderid.") and Subcategoryid='".$subcatid."' and 
							Expiration_Date < '".$expdate."' and Userid='".$user."'";
	
					$check = $this->Tblshoppingcart->query($checkqry);
					$sizecheck = sizeof($check);
					
					$exp_date = strtotime($expdate); 
					$now_date = strtotime($date);					
					$response.='<tr>';
					$response.='<td>'.$subcategory.'</td>';
					if($sizecheck>0)
					{
						if($check[0][0]['new_keycount']>0 && $check[0][0]['new_keycount']!=null)
						{
							$totalkeycount= $noofkw+$check[0][0]['new_keycount'];
							$response.='<td>'.$totalkeycount.'</td>';
							$response.='<td class=noofkw>'.$noofkw.'</td>';
							$i++;
						}
						else
						{
							$totalkeycount=$noofkw;
							$response.='<td>'.$noofkw.'</td>';
							$response.='<td class=noofkw>-</td>';
						}						
						$response.='<td>'.'$'.$amount.'</td>';
						$totcount+=$totalkeycount;
						$totprice+=$amount;
					}		
					$response.='</tr>';
				}
				$response.='<tr>
								<td>Total</td>
								<td>'.$totcount.'</td>
								<td class=noofkw></td>
								<td>'.'$'.number_format((float)$totprice, 2, '.', '').'</td>			
							</tr>';
				
				$response.='</table>';
				$myObj = new \stdClass();
				if($i==0)
					$myObj->style = 'none';
				else			
					$myObj->style = '';			
					
				$myObj->response = $response;
				$myObj->orderid = $orderid;		
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
			}
			//update expiration date
			if($this->request->data['mode']=='update_expdate')
			{
					$orderid = $this->request->data['id'];
					$new_expdate = $this->request->data['date'];
					$query = "UPDATE `tblshoppingcart` SET `Expiration_Date`='".$new_expdate."' 
						WHERE `Orderid`=".$orderid;
					$this->Tblshoppingcart->query($query);
					$affected_rows= $this->Tblshoppingcart->getAffectedRows();
					if($affected_rows>0)
						echo 'Updated successfully';
					exit;
			}				
		}
		//view orders
		$userid = $this->params['param2'];
		if($userid!='all')
		{
			$concat_qry = 'a.Userid = '.$userid.' AND ';
			$total_orders=$this->totalorders();
		}
		else
		{
			$concat_qry = '';
			$total_orders=$this->totalorders();
		}
		$order_qry = "SELECT a.Userid,a.Orderid,a.Expiration_Date,a.Datecreated,b.Email,b.Name, SUM(a.Keyword_count) as keycount, 
					  SUM(a.Price) as totprice FROM tblshoppingcart a, tblusers b where a.Userid = b.Userid AND ".$concat_qry.".b.`Role`!='admin' 
					  GROUP BY Orderid ORDER BY `Datecreated` DESC";

		$order_data = $this->Tblshoppingcart->query($order_qry);
		$this->set('order_arr',$order_data);
		$this->set('total_orders',$total_orders);
	}	
	function dash_users()
	{
		set_time_limit(0);
		$this->layout = false;
		$total_orders=$this->totalorders();
		$this->set('total_orders',$total_orders);
		if ($this->request->is('ajax')) 
		{
			//edit user
			if($this->request->data['mode']=='edituser')
			{
				$userid = $this->request->data['userid'];
				$user_qry = "SELECT * FROM `tblusers` WHERE Userid='".$userid ."'";
				$user = $this->User->query($user_qry);				
				$active=$user[0]['tblusers']['Active']=='Y'?'checked':'';
				$myObj = new \stdClass();						
				$myObj->usrname = $user[0]['tblusers']['Name'];
				$myObj->usremail = $user[0]['tblusers']['Email'];
				$myObj->usrcompany =$user[0]['tblusers']['Company'];
				$myObj->usract =$active;
				$myObj->usrid =$userid;
				$myJSON = json_encode($myObj);
				echo $myJSON;			
				exit;
			}
			//update user
			if($this->request->data['mode']=='updateuser')
			{
				$userid = $this->request->data['userid'];
				parse_str( $_POST[ 'formvals' ], $formdata );
				$active=$formdata['yes-no']=='on'?'Y':'N';
				
				$update_qry ="UPDATE `tblusers` SET `Name`='".$formdata['usrname']."',
													`Email`='".$formdata['usremail']."',
													`Company`='".$formdata['usrcompany']."',
													`Active`='".$active."' 
													WHERE `Userid`='".$userid."'";	
				$update= $this->User->query($update_qry);
				$count=$this->User->getAffectedRows();
				if($count>0)
				{
					echo 'Updated Successfully';					
				}
				exit;
			}
		}
		//view users
		$user_qry = "SELECT * FROM `tblusers` WHERE `Role`!='admin' ORDER BY `Name` ASC";
		$user_arr = $this->User->query($user_qry);
		$this->set('user_arr',$user_arr);
	}	
	function dash_view()
	{
		set_time_limit(0);
		$this->layout = false;
		$total_orders=$this->totalorders();
		$this->set('total_orders',$total_orders);
		$currentdate = date('Y-m-d H:i:s');
		if ($this->request->is('ajax')) 
		{
			if($this->request->data['mode']=='delete')
			{
				$keyid_arr = $this->request->data['keyids'];
				for($i=0; $i<count($keyid_arr); $i++)
				{
					$delete = 'DELETE FROM `tblkeyword` WHERE `Keyid`='.trim($keyid_arr[$i]);
					$this->Tblkeyword->query($delete);
					/*** update total keyword count to subcategory table ***/
					$count_qry = "UPDATE tblsubcategory s 
								  INNER JOIN (SELECT Subcategoryid,COUNT(Keyword) as kwcount FROM tblkeyword  GROUP BY Subcategoryid) as keyword ON keyword.Subcategoryid = s.Subcategoryid 
								  SET s.Keyword_count = keyword.kwcount";
					$this->Tblsubcategory->query($count_qry);
				}
			}
			if($this->request->data['mode']=='deleteall')
			{				
				$delete = "DELETE FROM `tblkeyword`";
				$this->Tblkeyword->query($delete);
				/*** update total keyword count to subcategory table ***/
				$count_qry = "UPDATE tblsubcategory s 
								  INNER JOIN (SELECT Subcategoryid,COUNT(Keyword) as kwcount FROM tblkeyword  GROUP BY Subcategoryid) as keyword ON keyword.Subcategoryid = s.Subcategoryid 
								  SET s.Keyword_count = keyword.kwcount";
				$this->Tblsubcategory->query($count_qry);
			}
			if($this->request->data['mode']=='get_subcat')
			{
				$id = $this->request->data['catid'];
				$getsubcat = "SELECT * FROM `tblsubcategory` WHERE Categoryid =".$id." AND Active='Y' order by Subcategory asc";
				$getsubcat_data = $this->Tblsubcategory->query($getsubcat);				
				if(sizeof($getsubcat_data)>0)
				{
					$response.='<option value="">--select---</option>';							
								for($s=0; $s<sizeof($getsubcat_data); $s++)
								{
									$subcatid = $getsubcat_data[$s]['tblsubcategory']['Subcategoryid'];
									$subcat = $getsubcat_data[$s]['tblsubcategory']['Subcategory'];
									$response.='<option value='.$subcatid.'>'.$subcat.'</option>';
								}
				}
				echo $response;
				exit;							
			}
			if($this->request->data['mode']=='newkeyword')
			{
				parse_str( $_POST[ 'keyvals' ], $formdata );
				$keyword=$formdata['new_keyword'];
				
				//*** check given keyword already exist ***//
				$check_key_count = $this->check_keyword($keyword);
				if($check_key_count==0)
				{
					$newdata_arr = array(
								"Subcategoryid" =>$formdata["s_subcat"],
								"Keyword"=> $keyword,
								"G_Volume" => $formdata["new_g_vol"],
								"US_Volume" => $formdata["new_us_vol"],
								"Type" => $formdata["new_type"],
								"Datecreated" => $currentdate
								);							
					$this->Tblkeyword->saveAll($newdata_arr);
					
					/*** update total keyword count to subcategory table ***/
					$count_qry = "UPDATE tblsubcategory s 
								  INNER JOIN (SELECT Subcategoryid,COUNT(Keyword) as kwcount FROM tblkeyword  GROUP BY Subcategoryid) as keyword ON keyword.Subcategoryid = s.Subcategoryid 
								  SET s.Keyword_count = keyword.kwcount";
					$this->Tblsubcategory->query($count_qry);
				}
				else
					echo 'Given keyword already exist';
				exit;
			}
			if($this->request->data['mode']=='newcat')
			{
				$myObj = new \stdClass();
				//***   save new category  ***///
				parse_str( $_POST[ 'formval' ], $formdata );					
				$txtnewcat = $formdata['txtcategory'];
				$txtnewcatid = $formdata['txtcategoryid'];
				$activecat = $formdata['new_cat_active']=='on'?'Y':'N';
				
				if($txtnewcatid=='')
				{
					//*** check given category already exist ***//
					$cat_count = $this->check_category($txtnewcat);
					if($cat_count==0)
					{
						$data_newcat = array(
										"Category" => $txtnewcat,
										"Active" => $activecat,
										"Datecreated" => $currentdate);						
						$this->Tblcategory->saveAll($data_newcat);
						
						//*** get category for keyword/subcategory popup dropdown ***/
						$data_cat = $this->allcategory('all');
						$catdrop.= '<option value="">--Select Category---</option>';
						for($c=0; $c<count($data_cat); $c++)
						{
							$catid = $data_cat[$c]['tblcategory']['Categoryid'];
							$catval = $data_cat[$c]['tblcategory']['Category'];
							$catdrop.= "<option value='".$catid."'>".$catval."</option>";
						}						
						$allcategory = $this->getallcategory();							
						$myObj->catdrop=$catdrop;						
						$myObj->allcat = $allcategory;																		
					}
					else
						$myObj->msg='Given category already exist';
				}
				else
				{
					$data_updatecat="UPDATE tblcategory SET Active = '".$activecat."',Category='".$txtnewcat."'
									WHERE Categoryid = ".$txtnewcatid."";
					$this->Tblcategory->query($data_updatecat);					
					$data_updatesubcat="UPDATE tblsubcategory SET Active = '".$activecat."'
									WHERE Categoryid = ".$txtnewcatid."";				
					$this->Tblsubcategory->query($data_updatesubcat);
					
					//*** get category for keyword/subcategory popup dropdown ***/
					$data_cat = $this->allcategory('all');
					$catdrop.= '<option value="">--Select Category---</option>';
					for($c=0; $c<count($data_cat); $c++)
					{
						$catid = $data_cat[$c]['tblcategory']['Categoryid'];
						$catval = $data_cat[$c]['tblcategory']['Category'];
						$catdrop.= "<option value='".$catid."'>".$catval."</option>";
					}						
					$allcategory = $this->getallcategory();							
					$myObj->catdrop=$catdrop;						
					$myObj->allcat = $allcategory;
				}
				$response = json_encode($myObj);
				echo $response;
				exit;			
				
			}
			
			if($this->request->data['mode']=='newsubcat')
			{
				$myObj = new \stdClass();
				//***   save new subcategory  ***///
				parse_str( $_POST[ 'formval' ], $formdata );					
				$txtnewsubcat = $formdata['txtsubcat'];
				$txtnewsubcatid = $formdata['txtsubcatid'];
				$txtnewcat = $formdata['s_category'];
				$activecat = $formdata['new_sub_active']=='on'?'Y':'N';
				if($txtnewsubcatid=='')
				{
					//check for subcategory already exist
					$subcat_count=$this->check_subcategory($txtnewsubcat);
					if($subcat_count==0)
					{
						$data_newcat = array(
										"Subcategory" => $txtnewsubcat,
										"Categoryid"=>$txtnewcat,
										"Active" => $activecat,
										"Datecreated" => $currentdate);
						
						$this->Tblsubcategory->saveAll($data_newcat);
						//*** get all category for keyword popup dropdown ***/
						$data_cat = $this->allcategory('all');
						$catdrop.= '<option value="">--Select Category---</option>';
						for($c=0; $c<count($data_cat); $c++)
						{
							$catid = $data_cat[$c]['tblcategory']['Categoryid'];
							$catval = $data_cat[$c]['tblcategory']['Category'];
							$catdrop.= "<option value='".$catid."'>".$catval."</option>";
						}
						$allsubcategory = $this->getallsubcategory();
						$myObj->catdrop = $catdrop;
						$myObj->allsubcat = $allsubcategory;
												
					}
					else						
						$myObj->msg='Given subcategory already exist';
				}
				else
				{
					$data_updatecat="UPDATE tblsubcategory  SET Active = '".$activecat."',Categoryid='".$txtnewcat."'
									WHERE Subcategoryid = ".$txtnewsubcatid."";
					$this->Tblsubcategory->query($data_updatecat);
					//*** get all category for keyword popup dropdown ***/
					$data_cat = $this->allcategory('all');
					$catdrop.= '<option value="">--Select Category---</option>';
					for($c=0; $c<count($data_cat); $c++)
					{
						$catid = $data_cat[$c]['tblcategory']['Categoryid'];
						$catval = $data_cat[$c]['tblcategory']['Category'];
						$catdrop.= "<option value='".$catid."'>".$catval."</option>";
					}					
					$allsubcategory = $this->getallsubcategory();	
					$myObj = new \stdClass();
					$myObj->catdrop = $catdrop;
					$myObj->allsubcat = $allsubcategory;
				}
				$response = json_encode($myObj);
				echo $response;
				exit;				
			}
			if($this->request->data['mode']=='editdata')
			{
				$keyid = $this->request->data['keyidval'];
				$rowdata = $this->alldata($keyid);
				$editsubcatid = $rowdata[0]['s']['Subcategoryid'];
				$response.='<div class="grid-x">
							<div class="cell">
							  <label id="edit_keywordlbl">Keyword
								<input type="text" name="edit_keyword" id="edit_keyword" value="'.$rowdata[0]['k']['Keyword'].'">
								<input type="hidden" name="edit_keyid" id="edit_keyid" value="'.$rowdata[0]['k']['Keyid'].'">
								<span class="form-error" id="edit_keyword_err">Keyword cant be empty</span>
							  </label>
							</div>
						  </div>
						  <div class="grid-x">
							<div class="cell">
							  <label id="edit_subcatlbl">Subcategory
								<select name="edit_subcat" id="edit_subcat">
									<option value="'.$rowdata[0]['s']['Subcategoryid'].'">'.$rowdata[0]['s']['Subcategory'].'</option>';
									$data_subcat = $this->allsubcategory($rowdata[0]['s']['Subcategoryid']);
									for($s=0; $s<count($data_subcat); $s++)
									{
										$subcatid = $data_subcat[$s]['tblsubcategory']['Subcategoryid'];
										$subcat = $data_subcat[$s]['tblsubcategory']['Subcategory'];
										$response.= "<option value='".$subcatid."'>".$subcat."</option>";
									}
				    $response.='</select>
								<span class="form-error" id="edit_subcat_err">Please select subcategory from dropdown or add new subcategory</span>
							  </label>
							</div>
							<!--<div class="cell large-4">
							  <a class="button" data-open="EditCategory" style="margin-top: 19px;" onclick=getcatdata('.$editsubcatid.');>Edit</a>
							</div>-->
						  </div>
						  <div class="grid-x">
							<div class="cell">
							  <label id="edit_g_vollbl">Volume (Global)
								<input type="number" name="edit_g_vol" id="edit_g_vol" value='.$rowdata[0]['k']['G_Volume'].'>
								<span class="form-error" id="edit_g_vol_err">Global volume cant be empty</span>
							  </label>
							</div>
						  </div>
						  <div class="grid-x">
							<div class="cell">
							  <label id="edit_us_vollbl">Volume (US)
								<input type="number" name="edit_us_vol" id="edit_us_vol" value='.$rowdata[0]['k']['US_Volume'].'>
								<span class="form-error" id="edit_us_vol_err">US volume cant be empty</span>
							  </label>
							</div>
						  </div>
						  <div class="grid-x">
							<div class="cell">
							  <label>Type
								<input type="text" name="edit_type" id="edit_type" value='.$rowdata[0]['k']['Type'].'>
							  </label>
							</div>
						  </div>
						  <div class="grid-x">
							<div class="cell">
							  <input type="button" class="button expanded" id="btnupdate" Value="Update" onclick="update();" >
							</div>
						  </div>
						</div>';
				echo $response;
				exit;
			}
			if($this->request->data['mode']=='update')
			{
				parse_str( $_POST[ 'editformval' ], $formdata );
				$editkeyword = $formdata['edit_keyword'];
				$edit_keyid = $formdata['edit_keyid'];
				$edit_subcatid = $formdata['edit_subcat'];
				$edit_gvol = $formdata['edit_g_vol'];
				$edit_usvol = $formdata['edit_us_vol'];
				$edit_type = $formdata['edit_type'];
				$update = "UPDATE `tblkeyword` SET `Subcategoryid`=".$edit_subcatid.",
							`G_Volume`='".$edit_gvol."',`US_Volume`='".$edit_usvol."',
							`Type`='".$edit_type."'";
				$check_key_count = $this->check_keyword($editkeyword);
				if($check_key_count==0)
				{
					$keyword_update = " ,`Keyword`='".mysql_escape_string($editkeyword)."'";				
				}
				else
					$keyword_update = " ";
				$update = 	$update.$keyword_update." WHERE `Keyid`=".$edit_keyid."";
				$this->Tblkeyword->query($update);
				$affected_rows= $this->Tblkeyword->getAffectedRows();				
				exit;
			}
			if($this->request->data['mode']=='getcategory')
			{
				$subcatid = $this->request->data['subcatid'];
				$getcat_qry = "SELECT s.`Subcategoryid`,s.`Subcategory`, s.Active ,c.`Categoryid`, 
								c.Category,c.Active FROM `tblsubcategory` s, tblcategory c WHERE 
								c.`Categoryid` = s.`Categoryid` AND s.`Subcategoryid` = ".$subcatid."";
				$getcat_arr = $this->Tblcategory->query($getcat_qry);
				$myObj = new \stdClass();
				$myObj->editgetcat=$getcat_arr;
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
			}
			if($this->request->data['mode']=='update_cat')
			{
				parse_str( $_POST[ 'editcatform' ], $formdata );
				$modesel=$formdata['editmodesel'];
				if($modesel=='category')
				{
					//***   Update category  ***///
					$txt_edit_cat = $formdata['edit_txtcat'];
					$edit_cat_id = $formdata['edit_catid'];
					$edit_cat_active = $formdata['edit_active']=='on'?'Y':'N';
					
					
					$update_qry = "UPDATE `tblcategory` SET `Active`='".$edit_cat_active."'";
					
					//*** check given category already exist ***//
					$edit_cat_count = $this->check_category($txt_edit_cat);
					if($edit_cat_count==0)
					{
						$edit_cate = ',`Category`="'.mysql_escape_string($txt_edit_cat).'" ';
					}
					else
						$edit_cate ="";
					$update_qry = $update_qry.$edit_cate." WHERE `Categoryid`=".$edit_cat_id."";
					$this->Tblcategory->query($update_qry);
					$data_updatesubcat="UPDATE tblsubcategory SET Active = '".$edit_cat_active."'
										WHERE Categoryid = ".$edit_cat_id."";				
					$this->Tblsubcategory->query($data_updatesubcat);
					$affected_rows= $this->Tblcategory->getAffectedRows();
					if($affected_rows>0)
					{	
						//*** get all category for dropdown ***/
						$data_edit_cat = $this->allcategory('all');
						$response.= '<option value="">--Select Category---</option>';
						for($c=0; $c<count($data_edit_cat); $c++)
						{
							$catid = $data_edit_cat[$c]['tblcategory']['Categoryid'];
							$catval = $data_edit_cat[$c]['tblcategory']['Category'];
							if($catid==$edit_cat_id)
							{
								$selected = "selected";
							}
							else
								$selected = "";
							$response.= "<option value='".$catid."' ".$selected.">".$catval."</option>";
						}
					}
					else
					{
						$response.= "Given name already exist";
					}
				}
				else
				{
					//***   Update subcategory  ***///
					$txt_edit_subcat = $formdata['edit_txtsubcat'];
					$edit_subid = $formdata['edit_subcatid'];
					$sel_edit_cat = $formdata['edit_sel_cat'];
					$edit_subcat_active = $formdata['edit_sub_active']=='on'?'Y':'N';
					
					$update_qry = "UPDATE `tblsubcategory` SET `Categoryid`=".$sel_edit_cat.",
										`Active`='".$edit_subcat_active."'";
										
					
					//*** check given subcategory already exist ****/
					$subcat_edit_count=$this->check_subcategory($txt_edit_subcat);
					if($subcat_edit_count==0)
					{
						$subcat_edit= ",SubCategory = '".mysql_escape_string($txt_edit_subcat)."'";
					}
					else
						$subcat_edit=" ";
					$update_qry=$update_qry.$subcat_edit."WHERE `Subcategoryid`=".$edit_subid."";
					$this->Tblsubcategory->query($update_qry);
					$affected_rows= $this->Tblsubcategory->getAffectedRows();
					if($affected_rows>0)
					{
						/*** get all subcategory for dropdown ***/
						$data_edit_subcat = $this->allsubcategory('all');
						$response.= '<option value="">--Select Subcategory---</option>';
						for($s=0; $s<count($data_edit_subcat); $s++)
						{
							$subcatid = $data_edit_subcat[$s]['tblsubcategory']['Subcategoryid'];
							$subcat = $data_edit_subcat[$s]['tblsubcategory']['Subcategory'];
							if($subcatid==$edit_subid)
							{
								$selected = "selected";
							}
							else
								$selected = "";
							$response.= "<option value='".$subcatid."' ".$selected.">".$subcat."</option>";
						}
					}
					else
					{
						$response.= "Given name already exist";
					}
					
				}
				echo $response;
				exit;
			}
			if($this->request->data['mode']=='getallcategory')
			{
				$allcategory = $this->getallcategory();					
				echo $allcategory;
				exit;				
			}
			if($this->request->data['mode']=='getallsubcategory')
			{
				$allsubcategory = $this->getallsubcategory();					
				echo $allsubcategory;
				exit;
			}
			if($this->request->data['mode']=='getcatid')
			{
				$getid = "SELECT `Categoryid` FROM `tblsubcategory` WHERE `Subcategoryid`='".$this->request->data['id']."'";	
				$getcatid = $this->Tblsubcategory->query($getid);
				echo $catid = $getcatid[0]['tblsubcategory']['Categoryid'];
				exit;
			}
		}
		$data_arr = $this->alldata('all');
		$this->set("data",$data_arr);
		$data_cat_arr = $this->allcategory('all');
		$this->set("data_cat",$data_cat_arr);
		$data_subcat_arr = $this->allsubcategory('all');
		$this->set("data_subcat",$data_subcat_arr);		
		
		$all_subcat_get = $this->allsubcategory('status');		
		$this->set("all_subcat",$all_subcat_get);
	}
	function dash_config()
	{
		set_time_limit(0);
		$this->layout = false;
		$currentdate = date('Y-m-d H:i:s');
		$total_orders=$this->totalorders();
		$this->set('total_orders',$total_orders);
		if ($this->request->is('ajax')) 
		{
			$mode = $this->request->data('mode');			
			if($mode=='category_price')
			{
				$id = $this->request->data('catid');
				$level = 'category';
				$level_price = $this->checkprice($level,$id);
				$priceval = $level_price[0]['tblpricing']['Keyword_Price'];
				$priceidval = $level_price[0]['tblpricing']['Priceid'];
				$myObj = new \stdClass();
				$myObj->price = $priceval;
				$myObj->priceid =$priceidval;
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
			}
			if($mode=='subcategory_price')
			{
				$id = $this->request->data('subcatid');
				$level = 'subcategory';
				$level_price = $this->checkprice($level,$id);
				$priceval = $level_price[0]['tblpricing']['Keyword_Price'];
				$priceidval = $level_price[0]['tblpricing']['Priceid'];
				$myObj = new \stdClass();
				$myObj->price = $priceval;
				$myObj->priceid =$priceidval;
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
			}
			if($mode=='cat_sort' ||'subcat_sort' || 'search')
			{
				if($mode=='cat_sort')
				{	
					$value = $this->request->data('sortval');
					if($value=='All')
					$concat = "where Category='Aall'";
					elseif($value!='Category')						
					$concat ="where Category='".$value."'";
					else
					$concat='';
				}
				if($mode=='subcat_sort')
				{
					$value = $this->request->data('sortval');
					if($value=='All')
						$concat = "where Subcategory='Aall'";
					elseif($value!='Subcategory')						
						$concat ="where Subcategory='".$value."'";
					else
						$concat='';
				}
				if($mode=='search')
				{	
					$value = $this->request->data('searchval');
					if($value!='')								
					$concat ="where Category like '%".$value."%' OR Subcategory like '%".$value."%'";
					else
					$concat='';
				}
				  
			    $get ="SELECT * FROM ((SELECT a.Keyword_Price,b.Category,c.Subcategory,
					  a.SubcategoryId,a.CategoryId FROM tblpricing a,tblcategory b,
				      tblsubcategory c where a.SubcategoryId = c.Subcategoryid and
				      b.CategoryId = c.Categoryid)UNION(SELECT a.Keyword_Price,
				      b.Category,'Aall',a.SubcategoryId,a.CategoryId FROM tblpricing a,
				      tblcategory b where a.CategoryId = b.CategoryId)UNION(SELECT
				      Keyword_Price,'Aall','Aall',SubcategoryId,CategoryId FROM 
				      tblpricing where CategoryId=0 and SubcategoryId=0)
				      )as i ".$concat." order by Category,Subcategory asc";

			  $get_data = $this->Tblpricing->query($get);			 
			  $size = sizeof($get_data);
			  for($s=0; $s<$size; $s++)
				{							
					$response.='<tr>';
					
						if($get_data[$s]['i']['Category']!="Aall")
							$response.='<td align="center">'.$get_data[$s]['i']['Category'].'</td>';
						else
							$response.='<td align="center">All</td>';
							
						if($get_data[$s]['i']['Subcategory']!="Aall")
							$response.='<td>'.$get_data[$s]['i']['Subcategory'].'</td>';
						else
							$response.='<td>All</td>';
							
						$response.='<td>'.'$'.$get_data[$s]['i']['Keyword_Price'].'</td>';
						
					$response.='</tr>';								
				 }
				echo $response;
				exit;
			}
				
		}
		if ($this->request->is('post') == 1)
		{
			$kw_price = $_POST['price'];
			$priceid = $_POST['priceid'];
			$caty_price = $_POST['price_cat'];
			$catyid = $_POST['s_category'];
			$catpriceid = $_POST['priceid_cat'];
			$subcaty_price = $_POST['price_subcat'];
			$subcatyid = $_POST['s_subcategory'];
			$subcatpriceid = $_POST['priceid_subcat'];
			/**** save keyword level price ****/
			
			if($priceid=='')
			{
				$data_arr = array(
							"Keyword_Price" => $kw_price,
							"Active" => 'Y',
							"Datecreated" => $currentdate
							);
				$this->Tblpricing->saveAll($data_arr);
				
				$data = $this->Tblpricing->find('first', array('fields' => array('Tblpricing.Priceid'),'order' => array('Priceid' => 'DESC')));
				$price_id = $data['Tblpricing']['Priceid'];
				$this->savepricehis($price_id,$kw_price);
			}
			else
			{
				$update_qry = "UPDATE `tblpricing` SET `Keyword_Price`='".$kw_price."' ,
								`Datecreated`='".$currentdate."' WHERE `Priceid`=".$priceid;
				$this->Tblpricing->query($update_qry);
				
				$data = $this->Tblpricing->find('first', array('fields' => array('Tblpricing.Priceid'),'order' => array('Priceid' => 'DESC')));
				$price_id = $data['Tblpricing']['Priceid'];
				$this->savepricehis($price_id,$kw_price);				
			}
					
			/**** save category level price  ***/
			if($caty_price!='')
			{
				if( $catpriceid=='')
				{
					$data_arr = array(
								"Keyword_Price" => $caty_price,
								"CategoryId" => $catyid,
								"Active" => 'Y',
								"Datecreated" => $currentdate
								);
					$this->Tblpricing->saveAll($data_arr);
					
					$data = $this->Tblpricing->find('first', array('fields' => array('Tblpricing.Priceid'),'order' => array('Priceid' => 'DESC')));
					$price_id = $data['Tblpricing']['Priceid'];
					$this->savepricehis($price_id,$caty_price);
				}
				else
				{
					$update_qry = "UPDATE `tblpricing` SET `Keyword_Price`='".$caty_price."' ,
									`Datecreated`='".$currentdate."' WHERE `Priceid`=".$catpriceid;
					$this->Tblpricing->query($update_qry);
					
					$data = $this->Tblpricing->find('first', array('fields' => array('Tblpricing.Priceid'),'order' => array('Priceid' => 'DESC')));
					$price_id = $data['Tblpricing']['Priceid'];
					$this->savepricehis($price_id,$caty_price);
				}
			}
			/*** save subcategory level price *****/
			if($subcaty_price!='')
			{
				if( $subcatpriceid=='')
				{
					$data_arr = array(
								"Keyword_Price" => $subcaty_price,
								"SubcategoryId" => $subcatyid,
								"Active" => 'Y',
								"Datecreated" => $currentdate
								);
					$this->Tblpricing->saveAll($data_arr);
					
					$data = $this->Tblpricing->find('first', array('fields' => array('Tblpricing.Priceid'),'order' => array('Priceid' => 'DESC')));
					$price_id = $data['Tblpricing']['Priceid'];
					$this->savepricehis($price_id,$subcaty_price);
				}
				else
				{
					$update_qry = "UPDATE `tblpricing` SET `Keyword_Price`='".$subcaty_price."' ,
									`Datecreated`='".$currentdate."' WHERE `Priceid`=".$subcatpriceid;
					$this->Tblpricing->query($update_qry);
					
					$data = $this->Tblpricing->find('first', array('fields' => array('Tblpricing.Priceid'),'order' => array('Priceid' => 'DESC')));
					$price_id = $data['Tblpricing']['Priceid'];
					$this->savepricehis($price_id,$subcaty_price);
				}
			}
			/**** save expiration duartion ***/
			if($_POST['configid']=='')
			{
				$conf_arr = array(
							"Expiration_duration" =>$_POST['config'],
							"Datecreated" => $currentdate
							);
				$this->Tblconfig->save($conf_arr);
			}
			else
			{
				$update_qry = "UPDATE `tblconfig` SET `Expiration_duration`=".$_POST['config'].",
								`Datecreated`='".$currentdate."' WHERE 
								`Configid`=".$_POST['configid'];
				$this->Tblconfig->query($update_qry);
			}			
		}	
			
		/**** get dbvalues onload ***/
		$price_arr = $this->checkprice('kwprice','');
		$this->set("price_val",$price_arr);
		
		$sel_config = "SELECT * FROM `tblconfig` LIMIT 1";
		$config_arr = $this->Tblconfig->query($sel_config);
		$this->set("config_data",$config_arr);
		
		$all_cat_arr = $this->allcategory('all');
		$this->set("data_cat",$all_cat_arr);

		$all_cat_subcat=" SELECT a.*,b.Category FROM `tblsubcategory` a,tblcategory b 
						 WHERE a.Active='Y' AND a.Categoryid = b.CategoryId 
						 order by b.Category,a.Subcategory ASC";
		$getsubcat_data = $this->Tblsubcategory->query($all_cat_subcat);
		$this->set("data_subcat",$getsubcat_data);
		
		//view all price
		$getsubcat ="SELECT * FROM ((SELECT a.Keyword_Price,b.Category,c.Subcategory,
			a.SubcategoryId,a.CategoryId FROM tblpricing a,tblcategory b,
			tblsubcategory c where a.SubcategoryId = c.Subcategoryid and
			b.CategoryId = c.Categoryid)UNION(SELECT a.Keyword_Price,
			b.Category,'Aall',a.SubcategoryId,a.CategoryId FROM tblpricing a,
			tblcategory b where a.CategoryId = b.CategoryId)UNION(SELECT
			Keyword_Price,'Aall','Aall',SubcategoryId,CategoryId FROM 
			tblpricing where CategoryId=0 and SubcategoryId=0)
			)as i order by Category,Subcategory asc";			
		$getsubcatdata = $this->Tblsubcategory->query($getsubcat);
		$this->set("subcat_data",$getsubcatdata);
	}
	function dash_gateway()
	{
		set_time_limit(0);
		$this->layout = false;
		$total_orders=$this->totalorders();
		$this->set('total_orders',$total_orders);
		$currentdate = date('Y-m-d H:i:s');
		if ($this->request->is('ajax')) 
		{
			//retrieve data for edit
			if($this->request->data['mode']=='edit')
			{
				$gatewayid = $this->request->data['gatewayid'];
				$select_qry = "SELECT * FROM `tblpaymentgateway` where Gatewayid=".$gatewayid;
				$gateway_arr = $this->Tblpaymentgateway->query($select_qry);
				if($gateway_arr[0]['tblpaymentgateway']['Active']=='Y')
					$checked='checked';
				else
					$checked='';				
				$myObj = new \stdClass();
				$myObj->gateway_name = $gateway_arr[0]['tblpaymentgateway']['Gateway_name'];			
				$myObj->username = $gateway_arr[0]['tblpaymentgateway']['Gateway_username'];		
				$myObj->password = $gateway_arr[0]['tblpaymentgateway']['Gateway_password'];		
				$myObj->active = $checked;		
				$myObj->gateway_url = $gateway_arr[0]['tblpaymentgateway']['Gateway_url'];		
				$myObj->gateway_id =$gateway_arr[0]['tblpaymentgateway']['Gatewayid'];
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
			}
		}
	
		if ($this->request->is('post') == 1)
		{
			if($_POST['editgatewayid']=='')
			{
				/*** add gateway ***/
				$active=$_POST['yes-no']=='on'?'Y':'N';					
				$newdata_arr = array(
								"Gateway_name" =>$_POST['addgatewayName'],
								"Gateway_username"=> $_POST['adduserName'],
								"Gateway_password" => $_POST['addPassword'],
								"Active" => $active,
								"Gateway_url" => $_POST['addgatewayURL'],
								"Datecreated" => $currentdate
								);							
				$this->Tblpaymentgateway->saveAll($newdata_arr);
			}
			else
			{
				/*** update gateway **********/
				$active=$_POST['gatewaySwitch']=='on'?'Y':'N';				
				$update_qry = "UPDATE `tblpaymentgateway` SET `Gateway_name`='".$_POST['editgatewayName']."',
									`Gateway_username`='".$_POST['edituserName']."',
									`Gateway_password`='".$_POST['editPassword']."',
									`Active`='".$active."',
									`Gateway_url`='".$_POST['editgatewayURL']."',
									`Datecreated`='".$currentdate."' WHERE 
								`Gatewayid` =".$_POST['editgatewayid'];
				$this->Tblpaymentgateway->query($update_qry);
			}			
		}
			
		//view gateway
		$select_qry = "SELECT * FROM `tblpaymentgateway` ORDER BY `Gateway_name` ASC";
		$gateway_arr = $this->Tblpaymentgateway->query($select_qry);
		$this->set("gateway_val",$gateway_arr);
	}	
	function navigation()
	{
		
	}
	function navigation_user()
	{
		
	}
	function footer()
	{
		
	}
	function about()
	{
		$this->layout = false;
		$action=$this->request->params['action'];
		$this->set('action',$action);
		if($this->request->is('post') == 1)
		{
			if($_POST['data']['User']['username']!='')
			{
				if ($this->Auth->login()) 
				{					
					//$this->redirect($this->Auth->redirectUrl());
				} 
				else 
				{
					$login_error = 'Invalid username or password';
					$this->set('loginerror',$login_error);
				}
			}
		}
		$userid = $this->Auth->user('Userid');
		$this->set('userid',$userid);
		$role = $this->Auth->user('Role');
		$this->set('role',$role);
		if($role=='user')
		{
			$all_desc = $this->Session->read('description_arr');
			$this->set('cart_desc',$all_desc);
		}
		if ($this->request->is('ajax')) 
		{
			//for signup
			if($this->request->data['fun']=='signup')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->signup($mode,$param,$action);
				echo $return;
				exit;
			}
			//for forgotpassword
			if($this->request->data['fun']=='forgotpwd')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->forgotpassword($mode,$param,$action);
				echo $return;
				exit;
			}
			// for smart search 
			if($this->request->data['mode']=='smartsearch')
			{
				$textval = $this->request->data['textval'];
				echo $response = $this->smartsearch($textval);
				exit;
			}
		}
	}
	function contact()
	{
		$this->layout = false;
		$action=$this->request->params['action'];
		$this->set('action',$action);
		if($this->request->is('post') == 1)
		{
			if($_POST['data']['User']['username']!='')
			{
				if ($this->Auth->login()) 
				{					
					//$this->redirect($this->Auth->redirectUrl());
				} 
				else 
				{
					$login_error = 'Invalid username or password';
					$this->set('loginerror',$login_error);
				}
			}
		}
		$userid = $this->Auth->user('Userid');
		$this->set('userid',$userid);
		$role = $this->Auth->user('Role');
		$this->set('role',$role);
		if($role=='user')
		{
			$all_desc = $this->Session->read('description_arr');
			$this->set('cart_desc',$all_desc);
		}
		if ($this->request->is('ajax')) 
		{
			//for signup
			if($this->request->data['fun']=='signup')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->signup($mode,$param,$action);
				echo $return;
				exit;
			}
			//for forgotpassword
			if($this->request->data['fun']=='forgotpwd')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->forgotpassword($mode,$param,$action);
				echo $return;
				exit;
			}
			// for smart search 
			if($this->request->data['mode']=='smartsearch')
			{
				$textval = $this->request->data['textval'];
				echo $response = $this->smartsearch($textval);
				exit;
			}
		}
	}
	function faq()
	{
		$this->layout = false;
		$action=$this->request->params['action'];
		$this->set('action',$action);
		if($this->request->is('post') == 1)
		{
			if($_POST['data']['User']['username']!='')
			{
				if ($this->Auth->login()) 
				{					
					//$this->redirect($this->Auth->redirectUrl());
				} 
				else 
				{
					$login_error = 'Invalid username or password';
					$this->set('loginerror',$login_error);
				}
			}
		}
		$userid = $this->Auth->user('Userid');
		$this->set('userid',$userid);
		$role = $this->Auth->user('Role');
		$this->set('role',$role);
		if($role=='user')
		{
			$all_desc = $this->Session->read('description_arr');
			$this->set('cart_desc',$all_desc);
		}
		if ($this->request->is('ajax')) 
		{
			//for signup
			if($this->request->data['fun']=='signup')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->signup($mode,$param,$action);
				echo $return;
				exit;
			}
			//for forgotpassword
			if($this->request->data['fun']=='forgotpwd')
			{
				$mode=$this->request->data['mode'];
				$param=$this->request->data['param'];
				$return = $this->forgotpassword($mode,$param,$action);
				echo $return;
				exit;
			}
			// for smart search 
			if($this->request->data['mode']=='smartsearch')
			{
				$textval = $this->request->data['textval'];
				echo $response = $this->smartsearch($textval);
				exit;
			}
		}
	}
	/************************* Extra functions    **************************/
	/**** Signup ****/
	function signup($mode,$parameter,$action)
	{
		session_start();		
		if($mode=='email')
		{
			parse_str($parameter, $formdata );
			$select_qry="select * from tblusers where Email='".trim($formdata[ 'reg_email' ])."' ";
			$select_arr = $this->User->query($select_qry);
			$date=date("Y/m/d H:i:s");
			$formdata[ 'reg_conpwd' ] = AuthComponent::password($formdata[ 'reg_conpwd' ]);
			
			//insert into table
			if(count($select_arr)==0)
			{
				$insert="INSERT INTO `tblusers`(`Name`, `Role`, `Email`, 
												`Company`, `username`,`password`, 
												`Active`, `Datecreated`) VALUES 
												('".$formdata[ 'reg_name' ]."','user','".$formdata[ 'reg_email' ]."',
												'".$formdata[ 'reg_comp' ]."','".$formdata[ 'reg_email' ]."','".$formdata[ 'reg_conpwd' ]."',
												'N','".$date."')";
												
				$insert=$this->User->query($insert);
				$length = 6;
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				$this->Session->write('act_key', $randomString);
				$this->Session->write('email', $formdata['reg_email']);
				$fromaddress = 'uxadmin@mail.com';
				$toaddress = $formdata['reg_email'];
				$headers = "MIME-Version: 1.0"."\r\n";
				$headers .= "Content-type: text/html"."\r\n";
				$headers .= "From:" . $fromaddress . "\r\n";
				$subject = 'Activation';
				$message = 'your Activation Key is <b>'.$randomString.'</b>';
				if(mail($toaddress,$subject,$message,$headers))
				{
					$response='<div>
								<span id="msg"></span>
							  </div>
							  <h2>Activation</h2>
							  <input type="hidden" id="controlaction" name="controlaction" value="'.$action.'"/>
							  <div class="cell">
								<label>Enter Your Activation Code :</label>
								<input type="text" id="act_code" name="act_code" value="" required >
							  </div>
							  <div>
								<span class="form-error is-visible" id="msg_err"></span>
							  </div>
							  <div class="cell">
								  <input type="button" class="button" id="act_submit" onclick="act_submit();" name="act_submit" value="Submit"/>
							  </div>';			
						
					echo $response;
				}
				else 
					echo "Email Not Send";
			}
			else
			{
				echo "Email Address Already Exists...";
			}					
			exit;
		}
		
		if($mode=='emailavail')
		{
			$email = $parameter;
			$select="select * from tblusers where Email='".trim($email)."' and Active='N' ";
			$selectdata= $this->User->query($select);
			$size = sizeof($selectdata);
			if($size>0)
			{
				$length = 6;
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				$this->Session->write('act_key', $randomString);
				$this->Session->write('email', $email);
				$fromaddress = 'uxadmin@mail.com';
				$toaddress = $email;
				$headers = "MIME-Version: 1.0"."\r\n";
				$headers .= "Content-type: text/html"."\r\n";
				$headers .= "From:" . $fromaddress . "\r\n";
				$subject = 'Activation';
				$message = 'your Activation Key is <b>'.$randomString.'</b>';
				if(mail($toaddress,$subject,$message,$headers))
				{					
					$response='<div>
								<span id="msg"></span>
							  </div>
							  <h2>Activation</h2>
							  <input type="hidden" id="controlaction" name="controlaction" value="'.$action.'"/>
							  <div class="cell">
								<label>Enter Your Activation Code :</label>
								<input type="text" id="act_code" name="act_code" value="" required >
							  </div>
							  <div>
								<span class="form-error is-visible" id="msg_err"></span>
							  </div>
							  <div class="cell">
								  <input type="button" class="button" id="act_submit" onclick="act_submit();" name="act_submit" value="Submit"/>
							  </div>';			
					
					echo $response;
				}
				else 
				   echo "Email Not Send";
			}
			else
				echo 'Not exist';
				exit;
		}
		if($mode=='activate')
		{
			if(trim($_SESSION["act_key"])==trim($parameter))
			{
				$update_qry="UPDATE tblusers SET Active = 'Y' where Email='".$_SESSION[ 'email' ]."'";
				$this->User->query($update_qry);
				echo 'Activated';
			}
			else
			{
				echo 'Activation code is incorrect';
			}
			exit;
		}			
	}
	/************ Forgotpassword ***********/
	function forgotpassword($mode,$parameter,$action)
	{
		session_start();	
		if($mode=='emailform')
		{
			$response='<h2>Forgot Password!</h2>
						<input type="hidden" id="controlaction" name="controlaction" value="'.$action.'">
						<div class="cell">
							<label>Enter Registered Email</label>
							<input type="email" id="email" name="email" value="" required >
						</div>
						<div>
							<span class="form-error is-visible" id="msg_err"></span>
						</div>
						<div class="cell">
							 <input type="button" class="button" id="email_submit" onclick="getpwd();" name="email_submit" value="Submit"/>
						</div>';
			echo $response;
			exit;	
		}
		//generate temporary password 
		if($mode=='get_password')
		{
			$email =$parameter;
			$select="select * from tblusers where Email='".trim($email)."' and Active='Y' ";
			$selectdata= $this->User->query($select);
			$size = sizeof($selectdata);
			if($size>0)
			{
				$length = 6;
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				$this->Session->write('tmp_key', $randomString);
				$this->Session->write('email', $email);
				$fromaddress = 'uxadmin@mail.com';
				$toaddress = $email;
				$headers = "MIME-Version: 1.0"."\r\n";
				$headers .= "Content-type: text/html"."\r\n";
				$headers .= "From:" . $fromaddress . "\r\n";
				$subject = 'Temporary Password';
				$message = 'Your temporary password is '.$randomString.'';
				if(mail($toaddress,$subject,$message,$headers))
				{													
					$response='<h2>Temporary Password!</h2>
								<input type="hidden" id="controlaction" name="controlaction" value="'.$action.'">
								<div class="cell">
									<label>Enter Password</label>
									<input type="text" id="tmppwd" name="tmppwd" value="" required >
								</div>
								<div>
									<span class="form-error is-visible" id="msg_err"></span>
								</div>
								<div class="cell">
									 <input type="button" class="button" id="tmppwd_submit" onclick="matchpwd();" name="tmppwd_submit" value="Submit"/>
								</div>';			
					echo $response;	
				}						
				else 
				{
					echo "Email Not Send";
				}
			}
			else
				echo 'Enter registered email';
			exit;
		}
		//check input password and temporary password
		if($mode=='tmp_password')
		{
			$password = $parameter;
			if($_SESSION["tmp_key"] == $password)
			{
				$response='<h2>Reset Password!</h2>
							<input type="hidden" id="controlaction" name="controlaction" value="'.$action.'">
							<div class="cell">
								<label>Password</label>
								<input type="password" id="pwd" name="pwd" value="" required >
							</div>
							<div>
								<span class="form-error is-visible" id="pwd_msg"></span>
							</div>
							<div class="cell">
								<label>Confirm Password<label>
								<input type="password" id="confirmpwd" name="confirmpwd" value="" required >
							</div>
							<div>
								<span class="form-error is-visible" id="conpwd_msg"></span>
							</div>
							<div>
								<span class="form-error is-visible" id="msg_err"></span>
							</div>
							<div class="cell">
								 <input type="button" class="button" id="pwd_submit" onclick="resetpwd();" name="pwd_submit" value="Submit"/>
							</div>';
				echo $response;		
			}
			else
				echo "Password not match";
			exit;
		}
		//reset password
		if($this->request->data['mode']=='reset_password')
		{		
			$password = AuthComponent::password($parameter);
			$update = "UPDATE tblusers SET `password` = '".$password."' 
						WHERE `Email`='".$_SESSION["email"]."'";
			$this->User->query($update);
			$count = $this->User->getAffectedRows();
			if($count>0)
				echo 'Password Reseted';
			else
				echo 'Password Not Reseted';
			exit;
		}
	}
	/** for index page ***/
	function allcategory($cond)
	{
		if($cond=='status')
		{
			$select_cat = "SELECT * FROM `tblcategory` order by Category asc";
		}
		else if($cond=='all')
		{
			$select_cat = "SELECT * FROM `tblcategory` WHERE `Active`='Y' order by Category asc";
		}		
		else
		{
			$select_cat = "SELECT * FROM `tblcategory` WHERE `Active`='Y' AND 
							Categoryid!='".$cond."' order by Category asc";
		}
		$allcat = $this->Tblcategory->query($select_cat);
		return $allcat;
	}
	function alldata($cond)
	{
		
		$select_qry_val = "SELECT c.Categoryid,c.Category, s.Subcategoryid,s.Subcategory, 
						k.Keyid,k.Keyword, k.G_Volume, k.US_Volume,k.Type,s.Active,c.Active FROM tblcategory c, 
						tblsubcategory s,tblkeyword k WHERE c.Categoryid=s.Categoryid AND 
						k.`Subcategoryid` = s.`Subcategoryid`";
		if($cond=='all')
		{
			$select_cond = "order by c.Category asc";
		}
		else 
			$select_cond ="AND k.Keyid='".$cond."'";
		$select_qry = $select_qry_val.$select_cond;
		$all_data = $this->Tblkeyword->query($select_qry);
		return $all_data;
	}
	function allsubcategory($cond)
	{
		if($cond=='status')
		{
			$select_subcat = "SELECT * FROM `tblsubcategory` order by Subcategory asc";
		}
		else if($cond=='all')
		{
			$select_subcat = "SELECT * FROM `tblsubcategory` WHERE `Active`='Y' order by Subcategory asc";
		}
		else
		{
			$select_subcat = "SELECT * FROM `tblsubcategory` WHERE `Active`='Y' AND
								Subcategoryid!='".$cond."' order by Subcategory asc";
		}
		$allsubcat = $this->Tblsubcategory->query($select_subcat);
		return $allsubcat;
	}
	/** for category_landing page start***/
	/**** searchbar function *****/
	function searchbar($text,$flow)
	{
		if($flow=='subcatid')
		{
			$concat = "k.`Subcategoryid` = ".$text." AND s.Active='Y'";
			$subcatid = $text;
			
		}
		else if($flow=='searchtxt')
		{
			$concat = "k.Keyword like '%".$text."%' 
						OR s.Subcategory LIKE '%".$text."%' 
						OR c.Category LIKE '%".$text."%'";
			
			$select_subcat_qry = "SELECT k.`Subcategoryid` FROM `tblkeyword` k
									LEFT JOIN `tblsubcategory` s ON 
									k.`Subcategoryid` = s.`Subcategoryid` LEFT JOIN 
									`tblcategory` c on s.Categoryid = c.Categoryid WHERE 
									".$concat." GROUP BY s.subcategoryid";
			$data = $this->Tblkeyword->query($select_subcat_qry);
			if(sizeof($data)>0)
			{
				foreach($data as $dataval)
				{
					$subcatidarr[] = $dataval['k']['Subcategoryid'];
				}
				$subcatid = implode(',', $subcatidarr);
			}
		}
		if($subcatid!='')
		{
			$sel_view_qry = "SELECT tblkeyword.Subcategoryid,tblkeyword.Keyword,
							tblkeyword.G_Volume,tblkeyword.US_Volume,tblkeyword.Type 
							from tblkeyword, tblsubcategory 
							where tblsubcategory.Subcategoryid IN (".$subcatid.")
							AND tblsubcategory.Subcategoryid = tblkeyword.Subcategoryid
							AND tblsubcategory.Active = 'Y'
							Order by tblkeyword.G_Volume DESC LIMIT 25";
			$keyword_arr = $this->Tblkeyword->query($sel_view_qry);
			$count_qry = "SELECT COUNT(*) as totcount 
						  from tblkeyword, tblsubcategory
						  where tblsubcategory.Subcategoryid = tblkeyword.Subcategoryid
						  AND tblsubcategory.Active = 'Y'
						  AND tblkeyword.Subcategoryid IN (".$subcatid.")";
			$count_arr = $this->Tblkeyword->query($count_qry);
			
			$search_qry ="SELECT c.Categoryid,c.Category, s.Subcategoryid,s.Subcategory, 
						  SUM(k.G_Volume) as totvol,s.Keyword_count 
						  FROM tblkeyword k LEFT JOIN `tblsubcategory` s ON 
						  k.`Subcategoryid` = s.`Subcategoryid` LEFT JOIN 
						 `tblcategory` c on s.Categoryid = c.Categoryid WHERE 
						  k.Subcategoryid IN (".$subcatid.") AND s.Active='Y' GROUP BY s.subcategory ORDER BY s.Subcategory ASC";
			$search_arr = $this->Tblkeyword->query($search_qry);
			
		}
		return array($search_arr,$keyword_arr,$count_arr);			
	}
	function relatedkwgrp($array)
	{
		foreach($array as $arrayval)
		{
			$catid[] = $arrayval['c']['Categoryid'];
		}
		$imp_catid = implode(',', $catid);
		$sel_subcat_qry = "SELECT s.`Subcategoryid`,s.`Subcategory`,s.`Keyword_count` 
						   FROM `tblsubcategory` s,tblkeyword k
						   WHERE s.`Active`='Y' 
						   AND s.Categoryid IN(".$imp_catid.") 
						   AND s.Subcategoryid = k.Subcategoryid
						   GROUP BY s.`Subcategoryid` 
						   ORDER BY s.Subcategory ASC";
		$arr_subcat = $this->Tblsubcategory->query($sel_subcat_qry);			   
		return $arr_subcat;
	}

	/**** selection page function*****/
	function countfun($catid)
	{
		$sel_qry ="SELECT c.Categoryid,c.Category, s.Subcategoryid,s.Subcategory,
					COUNT(k.Keyword) as keycount,SUM(k.G_Volume) as totalvol
					FROM tblcategory c, tblsubcategory s,tblkeyword k 
					WHERE c.Categoryid=s.Categoryid AND 
					k.`Subcategoryid` = s.`Subcategoryid` AND 
					c.Categoryid=".$catid." 
					AND s.Active ='Y'
					AND c.Active = 'Y'
					GROUP BY s.subcategory 
					ORDER BY s.Subcategory ASC";
		$get_count_arr = $this->Tblcategory->query($sel_qry);
		return $get_count_arr;
	}
	
	function savepricehis($priceid,$key_price)
	{
		$currentdate = date('Y-m-d H:i:s');
		$hisdata_arr = array(
								"Priceid" => $priceid,
								"Keyword_Price" => $key_price,
								"Datecreated" => $currentdate
								);
		$this->Tblpricehistory->saveAll($hisdata_arr);
	}
		
	/*** view page starting ****/
	function check_category($txtnewcat)
	{
		$check_cat = "SELECT COUNT(*) as count FROM `tblcategory` 
					  WHERE `Category` = '".trim(mysql_escape_string($txtnewcat))."'";
		$cat_count_arr = $this->Tblcategory->query($check_cat);
		$cat_count = $cat_count_arr[0][0]['count'];
		return $cat_count;
	}
	function check_subcategory($txtnewsubcat)
	{
		$check_subcat = "SELECT COUNT(*) as count FROM `tblsubcategory` 
								  WHERE `Subcategory` = '".trim(mysql_escape_string($txtnewsubcat))."'";
		$subcat_count_arr = $this->Tblsubcategory->query($check_subcat);
		$subcat_count = $subcat_count_arr[0][0]['count'];
		return $subcat_count;
	}
	function check_keyword($keyword)
	{
		$check_keyword = "SELECT COUNT(*) as count FROM `tblkeyword` 
								  WHERE `Keyword` = '".trim(mysql_escape_string($keyword))."'";
		$key_count_arr = $this->Tblkeyword->query($check_keyword);
		$key_count = $key_count_arr[0][0]['count'];
		return $key_count;
	}
	function getallcategory()
	{
		$all_cat = $this->allcategory('status');		
		if(sizeof($all_cat)!=0)
		{
		  $response.='<input type="hidden" name="datacount'.$i.'" id="datacount'.$i.'" value="'.sizeof($all_cat).'">';
		  for($i=0;$i<sizeof($all_cat);$i++)
		  {
			  $updatetxtcategory= $all_cat[$i]['tblcategory']['Category'];
			  $updatetxtcategoryid= $all_cat[$i]['tblcategory']['Categoryid'];
			  $update_cat_active=$all_cat[$i]['tblcategory']['Active']=='Y'?'checked':'';
			  $active = $all_cat[$i]['tblcategory']['Active'];
			  $response.='<div class="grid-x grid-padding-x">
							  <div class="cell large-8">				  
								  <a onclick=updatecat('.$updatetxtcategoryid.',(this.text),"'.$active.'"); name="updatetxtcategory'.$i.'" id="updatetxtcategory'.$i.'">'.$updatetxtcategory.'</a>
								  <input type="hidden" name="updatetxtcategoryid'.$i.'" id="updatetxtcategoryid'.$i.'" value="'.$updatetxtcategoryid.'" >                   
							  </div>
							  <div class="cell large-4"> 
								<input type="checkbox" name="update_cat_active'.$i.'" id="update_cat_active'.$i.'" '.$update_cat_active.' onclick="return false;" >
																  
							  </div>
						</div>';
		  }

		}
		return $response;
	}
	function getallsubcategory()
	{
		$all_subcat = $this->allsubcategory('status');

		if(sizeof($all_subcat)!=0)
		{
			$response.='<input type="hidden" name="subdatacount'.$i.'" id="subdatacount'.$i.'" value="'.sizeof($all_subcat).'">';
			for($i=0;$i<sizeof($all_subcat);$i++)
			{
			  $updatetxt_subcat= $all_subcat[$i]['tblsubcategory']['Subcategory'];
			  $updatetxt_subcatid= $all_subcat[$i]['tblsubcategory']['Subcategoryid'];
			  $update_subcat_active=$all_subcat[$i]['tblsubcategory']['Active']=='Y'?'checked':'unchecked';				  
			  $active = $all_subcat[$i]['tblsubcategory']['Active'];
			  $response.='<div class="grid-x grid-padding-x">
						  <div class="cell large-8">
							  <a onclick=updatesubcat('.$updatetxt_subcatid.',(this.text),"'.$active.'"); name="updatetxt_subcat'.$i.'" id="updatetxt_subcat'.$i.'">'.$updatetxt_subcat.'</a>
							  <input type="hidden" name="updatetxt_subcatid'.$i.'" id="updatetxt_subcatid'.$i.'" value="'.$updatetxt_subcatid.'" >                   
						  </div>
						  <div class="cell large-4"> 		 
							<input type="checkbox" name="update_subcat_active'.$i.'" id="update_subcat_active'.$i.'" '.$update_subcat_active.' onclick="return false;" >
											  
						  </div>
						</div>';
			}		
		}
		return $response;
	}
	/*** view page ending ****/
	//Order count
	function totalorders()
	{		
		$sel_qry = "SELECT distinct(`Orderid`) FROM `tblshoppingcart` 
		            WHERE `View_status` ='' 
					ORDER BY `Orderid` DESC";
		$view_data = $this->Tblshoppingcart->query($sel_qry);
		return count($view_data);			
	}
	function login_admin()
	{
		if ($this->request->is('post')) 
		{
			$username = $_POST[ 'username' ];
			$newpassword = AuthComponent::password($_POST[ 'password' ]);
			$select = "SELECT * FROM `tblusers` WHERE `Email`='".$username."' AND Role='admin'";
			$sel_arr = $this->User->query($select);
			if(count($sel_arr)>0)
			{
				$userid = $sel_arr[0]['tblusers']['Userid'];
				$update = "UPDATE `tblusers` SET `password`='".$newpassword."' WHERE `Userid`=".$userid;
				$this->User->query($update);
			}
			else
			{
				$insert = "INSERT INTO `tblusers`( `Role`, `Email`, `Company`, 
												   `username`, `password`, `Active`) 
							VALUES ('admin','".$username."','',
								   '".$username."','".$newpassword."','Y')";
				$this->User->query($insert);
			}
				
		}
	}
	function alreadyadded($formdata)
	{
		$description=$formdata['subcattxt'];
		$totcount=$formdata['totcount'];
		$totsubcount=$formdata['subcount'];
		$totprice=$formdata['totprice'];
		$sess_subcatid=$formdata['subcatid'];
		$sess_catid = $formdata['catid'];
		$sess_flow = $formdata['flow'];
		$sess_sub_price = $formdata['subcount_price'];
		
		/***** check if session already have same data(Subcategory) ****/
		$subid_data = $this->Session->read('subid_arr');
		$cart_check = '';
		if(count($subid_data)>0)
		{
			if(strpos($sess_subcatid,',')>0)
			{
				$exp_sess_subcatid  = explode(",",$sess_subcatid);
				$exp_sess_catid = explode(",",$sess_catid);
				$exp_totsubcount = explode(",",$totsubcount);
				$exp_sess_sub_price = explode(",",$sess_sub_price);
			}
			else
			{
				$exp_sess_subcatid  = array($sess_subcatid);
				$exp_sess_catid = array($sess_catid);
				$exp_totsubcount = array($totsubcount);
				$exp_sess_sub_price = array($sess_sub_price);
			}
			for($s=0; $s<count($subid_data); $s++)
			{
				if(strpos($subid_data[$s],',')>0)
				{
					$exp_subid_data = explode(",",$subid_data[$s]);
				}
				else
				{
					$exp_subid_data = array($subid_data[$s]);
				}
				for($e=0; $e<count($exp_subid_data); $e++)
				{
					for($se=0; $se<count($exp_sess_subcatid); $se++)
					{
						if($exp_sess_subcatid[$se]==$exp_subid_data[$e])
						{
							$totcount = $totcount-$exp_totsubcount[$se];
							$totprice = $totprice-$exp_sess_sub_price[$se];
							unset($exp_sess_subcatid[$se]);
							unset($exp_sess_catid[$se]);
							unset($exp_totsubcount[$se]);
							unset($exp_sess_sub_price[$se]);
							$exp_sess_subcatid = array_values($exp_sess_subcatid);
							$exp_sess_catid = array_values($exp_sess_catid);
							$exp_totsubcount =array_values($exp_totsubcount);
							$exp_sess_sub_price = array_values($exp_sess_sub_price);
						}
					}
				}
			}
		
			$sess_subcatid = implode(',', $exp_sess_subcatid);
			$sess_catid = implode(',', $exp_sess_catid);
			$totsubcount = implode(',', $exp_totsubcount);
			$sess_sub_price = implode(',', $exp_sess_sub_price);
		}
		/*** save in session ***/
		if($sess_subcatid=='')
		{
			$cart_check = 'Already added in cart';
		}
		$myObj = new \stdClass();
		if($cart_check!='')
		{
			$myObj->cartnotify=$cart_check;
		}
		$myJSON = json_encode($myObj);
		echo $myJSON;
	}
	function addtocart($formdata)
	{
		$description=$formdata['subcattxt'];
		$totcount=$formdata['totcount'];
		$totsubcount=$formdata['subcount'];
		$totprice=$formdata['totprice'];
		$sess_subcatid=$formdata['subcatid'];
		$sess_catid = $formdata['catid'];
		$sess_flow = $formdata['flow'];
		$sess_sub_price = $formdata['subcount_price'];
		
		/***** check if session already have same data(Subcategory) ****/
		$subid_data = $this->Session->read('subid_arr');
		$cart_check = '';
		if(count($subid_data)>0)
		{
			if(strpos($sess_subcatid,',')>0)
			{
				$exp_sess_subcatid  = explode(",",$sess_subcatid);
				$exp_sess_catid = explode(",",$sess_catid);
				$exp_totsubcount = explode(",",$totsubcount);
				$exp_sess_sub_price = explode(",",$sess_sub_price);
			}
			else
			{
				$exp_sess_subcatid  = array($sess_subcatid);
				$exp_sess_catid = array($sess_catid);
				$exp_totsubcount = array($totsubcount);
				$exp_sess_sub_price = array($sess_sub_price);
			}
			for($s=0; $s<count($subid_data); $s++)
			{
				if(strpos($subid_data[$s],',')>0)
				{
					$exp_subid_data = explode(",",$subid_data[$s]);
				}
				else
				{
					$exp_subid_data = array($subid_data[$s]);
				}
				for($e=0; $e<count($exp_subid_data); $e++)
				{
					for($se=0; $se<count($exp_sess_subcatid); $se++)
					{
						if($exp_sess_subcatid[$se]==$exp_subid_data[$e])
						{
							$totcount = $totcount-$exp_totsubcount[$se];
							$totprice = $totprice-$exp_sess_sub_price[$se];
							unset($exp_sess_subcatid[$se]);
							unset($exp_sess_catid[$se]);
							unset($exp_totsubcount[$se]);
							unset($exp_sess_sub_price[$se]);
							$exp_sess_subcatid = array_values($exp_sess_subcatid);
							$exp_sess_catid = array_values($exp_sess_catid);
							$exp_totsubcount =array_values($exp_totsubcount);
							$exp_sess_sub_price = array_values($exp_sess_sub_price);
							$cart_check = "Duplicate subcategories are removed from cart";
						}
					}
				}
			}
		
			$sess_subcatid = implode(',', $exp_sess_subcatid);
			$sess_catid = implode(',', $exp_sess_catid);
			$totsubcount = implode(',', $exp_totsubcount);
			$sess_sub_price = implode(',', $exp_sess_sub_price);
		}
		/*** save in session ***/
		if($sess_subcatid!='')
		{	
			$subid_data[] = $sess_subcatid;
			$this->Session->write('subid_arr', $subid_data);
								
			$des_data = $this->Session->read('description_arr');
			$des_data[] = $description;
			$this->Session->write('description_arr', $des_data);
			
			$catid_data = $this->Session->read('catid_arr');
			$catid_data[] = $sess_catid;
			$this->Session->write('catid_arr', $catid_data);
		
			$totcount_data = $this->Session->read('totcount_arr');
			$totcount_data[] = $totcount;
			$this->Session->write('totcount_arr', $totcount_data);
			
			$subcount_data = $this->Session->read('subcount_arr');
			$subcount_data[] = $totsubcount;
			$this->Session->write('subcount_arr', $subcount_data);
								
			$totprice_data = $this->Session->read('totprice_arr');
			$totprice_data[] = $totprice;
			$this->Session->write('totprice_arr', $totprice_data);
			
			$sub_price_data = $this->Session->read('subprice_arr');
			$sub_price_data[] = $sess_sub_price;
			$this->Session->write('subprice_arr', $sub_price_data);
			
			$totflow_data = $this->Session->read('totflow_arr');
			$totflow_data[] = $sess_flow;
			$this->Session->write('totflow_arr', $totflow_data);
		}
		$all_desc = $this->Session->read('description_arr');
		$all_count = $this->Session->read('totcount_arr');
		$all_price = $this->Session->read('totprice_arr');
		$size = sizeof($all_desc);
		$myObj = new \stdClass();
		$myObj->size=$size;
		if($cart_check!='')
		{
			$myObj->cartnotify=$cart_check;
		}
		$myJSON = json_encode($myObj);
		echo $myJSON;
	}
	function smartsearch($letters)
	{
		if($letters=='')
		{
			$sel_qry = "SELECT `Category` as field FROM `tblcategory` 
						t WHERE `Active`='Y' Order BY Category asc";
			$res = $this->Tblcategory->query($sel_qry);	
		}
		else
		{
			$sel_qry = "SELECT `Category` as field FROM tblcategory t
						WHERE Category LIKE '".$letters."%' AND
						`Active`='Y' Order BY Category asc";	
			$res = $this->Tblcategory->query($sel_qry);	
			if(count($res)==0)
			{
				$sel_qry = "SELECT `Subcategory` as field FROM tblsubcategory t
							WHERE Subcategory LIKE '".$letters."%' AND
						   `Active`='Y' Order BY Subcategory asc";	
				$res = $this->Tblsubcategory->query($sel_qry);

				if(count($res)==0)
				{
					$sel_qry = "SELECT DISTINCT(`Subcategory`) as field FROM `tblsubcategory` t ,
							  `tblkeyword` k WHERE k.`Keyword` LIKE '%".$letters."%' AND 
							   t.`Subcategoryid` = k.`Subcategoryid` AND
							   t.`Active` = 'Y' ORDER BY t.`Subcategory` asc";	
					$res = $this->Tblsubcategory->query($sel_qry);
				}
			}
		
		}
		if(count($res)>0)
		{
			for($r=0; $r<count($res); $r++)
			{
				$val = $res[$r]['t']['field'];
				?>
				<li><a onclick='select_text(this.text);' ONMOUSEOVER="this.style.backgroundColor='MediumTurquoise'"  ONMOUSEOUT="this.style.backgroundColor='#212427'" class='sugessitionboxclass'><?php echo $val;?></a></li>
			<?php
			}
		}
		else
			$response = 'No data found';
		echo $response;
	}
	function validate_kw($array)
	{
		$date = date('y-m-d');
		$userid = $this->Auth->user('Userid');
		$cart_query = "SELECT Orderid,Expiration_Date,Subcategoryid 
						FROM `tblshoppingcart` WHERE Userid=".$userid." AND 
						Datecreated IN (SELECT max(Datecreated) 
						FROM tblshoppingcart WHERE Userid=".$userid." 
						GROUP BY Subcategoryid) GROUP BY `Subcategoryid`";
		$cart_subids = $this->Tblshoppingcart->query($cart_query);
		if(count($cart_subids)>0)
		{
			for($a=0; $a<count($array); $a++)
			{
				$subcatid = $array[$a]['s']['Subcategoryid'];
				$now_count = $array[$a]['s']['Keyword_count'];
				for($e=0; $e<count($cart_subids); $e++)
				{
					$cart_subid = $cart_subids[$e]['tblshoppingcart']['Subcategoryid'];
					$expdate = $cart_subids[$e]['tblshoppingcart']['Expiration_Date'];
					$kw_count_qry = "SELECT SUM(Keyword_count) as kwcount FROM `tblshoppingcart` WHERE `Subcategoryid` = ".$cart_subid." AND Userid = ".$userid."";
					$kw_count_arr = $this->Tblshoppingcart->query($kw_count_qry);
					$cart_kw_count = $kw_count_arr[0][0]['kwcount'];
					//$cart_kw_count = $cart_subids[$e]['tblshoppingcart']['Keyword_count'];
					$exp_date = strtotime($expdate);
					$now_date = strtotime($date);
					if($subcatid==$cart_subid)
					{
						if($exp_date>$now_date)
						{
							$exist_index[] = $array[$a];
							$r[] = $a; /*** save index val in $r array r--remove **/
						}
						else
						{
							if($now_count>$cart_kw_count)
							{
								$count_diff = $now_count-$cart_kw_count;
								$diffcount_arr[]=$count_diff;
								$u[] = $a; /*** save index val in $u array u--update count **/
							}
							else
							{
								if($exp_date<$now_date)
								{
									$exist_index[] = $array[$a];
									$r[] = $a;
								}
							}
						}
					}
											
				}
			}
			if(count($r)>0)
			{
				for($x=0; $x<count($r); $x++)
				{
					unset($array[$r[$x]]);
				}
			}
			if(count($u)>0)
			{
				for($y=0; $y<count($u); $y++)
				{
					$array[$u[$y]]['s']['Keyword_count'] = $diffcount_arr[$y];
				}
				
			}
			$newarray = array_values($array); 
			return array($exist_index,$newarray);
			
		}
		
	}
	
	function getprice($array)
	{
		for($p=0; $p<count($array);$p++)
		{
			$catid = $array[$p]['c']['Categoryid']; 
			$subcatid = $array[$p]['s']['Subcategoryid'];
			$count = $array[$p]['s']['Keyword_count'];
			$totalcount+=$count;
			if($subcatid!='')
				$subcatpricearr = $this->checkprice('subcategory',$subcatid);
			if(count($subcatpricearr)>0)
			{
				$price = $subcatpricearr[0]['tblpricing']['Keyword_Price'];
				$countprice = $count * $price;
			}
			else
			{
				if($catid!='')
					$catpricearr = $this->checkprice('category',$catid);
				if(count($catpricearr)>0)
				{
					$price = $catpricearr[0]['tblpricing']['Keyword_Price'];
					$countprice = $count * $price;
				}
				else
				{
					$kwpricearr = $this->checkprice('kwprice','');
					if(count($kwpricearr)>0)
					{
						$price = $kwpricearr[0]['tblpricing']['Keyword_Price'];
						$countprice = $count * $price;
					}
				}
			}
			$countprice = number_format((float)$countprice, 2, '.', '');
			$subcountprice[] = $countprice;
			$totprice+=$countprice;
		}
		return array($totalcount,$totprice,$subcountprice);
	}
	function checkprice($level,$id)
	{
		
		if($level=='kwprice')
		{
			$query = "SELECT Priceid,`Keyword_Price` FROM `tblpricing` WHERE 
					CategoryId=0 AND SubcategoryId=0 AND `Active`='Y' LIMIT 1";
		}
		else
		{
			if($level=='category')
				$field = 'CategoryId';
			if($level=='subcategory')	
				$field = 'SubcategoryId';
			$query = "SELECT Priceid,`Keyword_Price` FROM `tblpricing` WHERE ".$field."=".$id." AND `Active`='Y'";
			
		}
		$price_arr = $this->Tblpricing->query($query);
		return $price_arr;
	}
	/*** category_landing page function end ***/
}

