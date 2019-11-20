<?php
App::build(array('Vendor' => array(APP . 'Vendor' . DS . 'stripe-php' . DS)));
App::build(array('Vendor' => array(APP . 'Vendor' . DS . 'PHPExcel' . DS)));
error_reporting(E_ALL ^ E_NOTICE);
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel.php'));
App::import('Vendor', 'Exporttoexcel', array('file' =>'php-excel-reader' . DS . 'Exporttoexcel.php'));
App::import('Vendor', 'ExcelReaderLib', array('file' =>'php-excel-reader' . DS . 'ExcelReaderLib.php'));
App::uses('PHPExcel.php', 'Vendor');
App::uses('php-excel-reader/ExcelReaderLib.php', 'Vendor');
App::uses('php-excel-reader/Exporttoexcel.php', 'Vendor');
class PaymentsController extends AppController
{   
    var $helpers = array('Html', 'Form', 'Js', 'Paginator');    
    public $components = array('Paginator', 'RequestHandler', 'Stripe');
    public $uses = array('Tblpaymentauth','Tblpaymentgateway','Tblpayment','User','Tblcategory','Tblsubcategory','Tblkeyword','Tblconfig','Tblshoppingcart');
    function beforeFilter()
    {
        $this->Auth->allow('shopping_cart');
    }
	function shopping_cart()
	{
		$this->layout = false;
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
		$userid   = $this->Auth->user('Userid');
		$this->set('userid',$userid);
		$action=$this->request->params['action'];
		$this->set('action',$action);
		$currentdate = date('Y-m-d H:i:s');
		$totprice = $this->Session->read('totprice_arr');
		$price = array_sum($totprice);
				
		$Secret_Key = Configure::read('Stripe.TestSecret');
		$Public_Key = Configure::read('Stripe.TestPublic');
		$this->set('publickey',$Public_Key);
		
		if($this->request->is('ajax'))
		{
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
			if($this->request->data('mode')=='removecart')
			{
				$indexval = $this->request->data('index');
				$des_data = $this->Session->read('description_arr');
				array_splice($des_data, $indexval, 1);
				$this->Session->write('description_arr', $des_data);
					
				$subid_data = $this->Session->read('subid_arr');
				array_splice($subid_data, $indexval, 1);
				$this->Session->write('subid_arr', $subid_data);
					
				$catid_data = $this->Session->read('catid_arr');
				array_splice($catid_data, $indexval, 1);
				$this->Session->write('catid_arr', $catid_data);
					
				$totcount_data = $this->Session->read('totcount_arr');
				array_splice($totcount_data, $indexval, 1);
				$this->Session->write('totcount_arr', $totcount_data);
					
				$subcount_data = $this->Session->read('subcount_arr');
				array_splice($subcount_data, $indexval, 1);
				$this->Session->write('subcount_arr', $subcount_data);
					
				$totprice_data = $this->Session->read('totprice_arr');
				array_splice($totprice_data, $indexval, 1);
				$this->Session->write('totprice_arr', $totprice_data);
					
				$sub_price_data = $this->Session->read('subprice_arr');
				array_splice($sub_price_data, $indexval, 1);
				$this->Session->write('subprice_arr', $sub_price_data);
					
				$totflow_data = $this->Session->read('totflow_arr');
				array_splice($totflow_data, $indexval, 1);
				$this->Session->write('totflow_arr', $totflow_data);
				
				$all_desc = $this->Session->read('description_arr');
				$all_count = $this->Session->read('totcount_arr');
				$all_price = $this->Session->read('totprice_arr');
				$size = sizeof($all_desc);
				for($s=0; $s<$size; $s++)
				{
					$liresp.='<li>'.ucfirst($all_desc[$s]).'<span id="">'.number_format((float)$all_price[$s], 2, '.', '').'</span><a onclick=removecart('.$s.')>Remove</a></li>';
				}
				$cartprice = number_format(array_sum($all_price),2, '.', '');
				$myObj = new \stdClass();
				$myObj->cartprice=$cartprice;
				$myObj->size=$size;
				$myObj->litxt=$liresp;
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
			}
			if($this->request->data('mode')=='formsubmit')
			{
				parse_str( $_POST[ 'formvals' ], $formdata );
				$token  = $formdata['stripeToken'];
				
				//set api key
				$stripe = array(
					"secret_key"      => $Secret_Key,
					"publishable_key" => $Public_Key
				);
				\Stripe\Stripe::setApiKey($stripe['secret_key']);
				
				if($formdata['customerid']!='')
				{
					$customerid = $formdata['customerid'];
				}
				else
				{
					//get token, card and user info from the form
					$email   = $this->Auth->user('Email');
					$card_num = $formdata['card_num'];
					$card_cvc = $formdata['cvc'];
					$data = array(
							'email' => $email,
							'source'  => $token
					);
					$create_cus = $this->createCustomer($data);
					if($create_cus['message']!='Success')
					{
						echo $create_cus['message'];	
						exit;
					}
					else
					{
						$customerid = $create_cus['response']['id'];
					}
				}
				if($formdata['update_card']!='')
				{
					$fields=array(
						'source' => $token
					);
					$update_card = $this->updateCard($customerid, $fields);
					if($update_card['status']=='error')
					{
						echo 'Update card status  '.$update_card['status'];
					}
					
				}
				if($price!='')
				{
					/*** convert cent for stripe payment ****/
					$stripe_price = round($price*100);
					$orderid = $this->getorderid();
					$data_charge = array(
						'customer' => $customerid,
						'amount'   => $stripe_price,
						'currency' => 'usd',
						'description' => $orderid
					);
					$charge = $this->createCharge($data_charge,$customerid,$price);
					if($charge['status']=='success')
					{
						$all_desc = $this->Session->read('description_arr');
						$all_count = $this->Session->read('totcount_arr');
						$all_price = $this->Session->read('totprice_arr');
						$all_subprice = $this->Session->read('subprice_arr');
						$all_subcatid = $this->Session->read('subid_arr');
						$all_catid = $this->Session->read('catid_arr');
						$all_flow = $this->Session->read('totflow_arr');
						$all_subcount = $this->Session->read('subcount_arr');
						$size = sizeof($all_desc);
						$Expdate = $this->getexpdate();
						for($s=0; $s<$size; $s++)
						{
						
							$subcatid = explode(",",$all_subcatid[$s]);
							$catid = explode(",",$all_catid[$s]);
							$keycount =  explode(",",$all_subcount[$s]);
							$subprice =  explode(",",$all_subprice[$s]);
							for($t=0; $t<count($subcatid); $t++)
							{
								$filename =  $userid.'_'.$subcatid[$t].'.csv';
								$data_arr= array(
													"Userid" => $userid,
													"Orderid"=> $orderid,
													"Categoryid"=> $catid[$t],
													"Subcategoryid"=> $subcatid[$t],
													"Keyword_count"=> $keycount[$t],
													"Price"=> $subprice[$t],
													"Filename" => $filename,
													"Expiration_Date"=> $Expdate,
													"Datecreated"=> $currentdate
												);
									$this->Tblshoppingcart->saveAll($data_arr);
							}
						
						}
						$this->cart_export();
					}
					else
					{
						echo 'Payment status '.$charge['status'];	
					}
					
				}
				exit;
			}
		}
		if($userid!='')
		{
			$filter_arr = $this->validate_kw();
			$this->Session->write('description_arr',array_values($this->Session->read('description_arr')));
			$this->Session->write('totcount_arr',array_values($this->Session->read('totcount_arr')));
			$this->Session->write('totprice_arr',array_values($this->Session->read('totprice_arr')));
			$this->Session->write('subprice_arr',array_values($this->Session->read('subprice_arr')));
			$this->Session->write('subid_arr',array_values($this->Session->read('subid_arr')));
			$this->Session->write('catid_arr',array_values($this->Session->read('catid_arr')));
			$this->Session->write('totflow_arr',array_values($this->Session->read('totflow_arr')));
			$this->Session->write('subcount_arr',array_values($this->Session->read('subcount_arr')));
			// check customer is already exist
			$select_user = "SELECT `Authkey` FROM `tblpaymentauth` WHERE Userid='".$userid."' LIMIT 1";
			$customerid_arr = $this->Tblpaymentauth->query($select_user);
			if(count($customerid_arr)==0)
			{
				$this->set('exist','no');
			}
			else
			{
				$customerid = $customerid_arr[0]['tblpaymentauth']['Authkey'];
				$this->set('customerid',$customerid);
				$this->set('exist','yes');
			}
		}
		else
		{
			if($price<=0)
			{
				return $this->redirect(
					array('controller' => 'users', 'action' => 'user_dashboard')
				);
			}
		}
		/**** cart deatils    *******/
		$all_desc = $this->Session->read('description_arr');
		$all_count = $this->Session->read('totcount_arr');
		$all_price = $this->Session->read('totprice_arr');		
		$this->set("cart_desc",$all_desc);
		$this->set("cart_count",$all_count);
		$this->set("cart_price",$all_price);
	}
	function createCustomer($data)
	{
		$userid = $this->Auth->user('Userid');
		$currentdate = date('Y-m-d H:i:s');
		$customer = $this->Stripe->createCustomer($data);
		$customerid = 	$customer['response']['id']	;
		if($customerid!='')
		{
			$gateway_id = $this->get_gatewayid();
			$data_save = array(
					"Gatewayid" => $gateway_id,
					"Userid" => $userid,
					"Authkey" => $customerid,
					"Dateupdated"=>$currentdate
				);
				$this->Tblpaymentauth->save($data_save);
		}
		return $customer;
		
	}
	function createCharge($data_charge,$customerid,$price)
	{
		$userid = $this->Auth->user('Userid');
		$charge = $this->Stripe->charge($data_charge,$customerid);
		if($charge['status']=='success')
		{
			$currentdate = date('Y-m-d H:i:s');
			$gateway_id = $this->get_gatewayid();
			$data_save = array(
					"Userid" => $userid,
					"Gatewayid" => $gateway_id,
					"Amount" => $price,
					"Payment_status" => 'Success',
					"Dateupdated"=>$currentdate
				);
			$this->Tblpayment->save($data_save);
		}
		return $charge;
	}
	function updateCard($customerid, $fields)
	{
		$updateCard = $this->Stripe->updateCustomer($customerid, $fields);
		return $updateCard;
	}
	function get_gatewayid()
	{
		$sel_gateway = "SELECT `Gatewayid` FROM `tblpaymentgateway` WHERE `Active` = 'Y'";
		$gateway_arr = $this->Tblpaymentgateway->query($sel_gateway);
		$gateway_id = $gateway_arr[0]['tblpaymentgateway']['Gatewayid'];
		return $gateway_id;
	}
	function getorderid()
	{
		$orderid_arr = $this->Tblshoppingcart->find('first', array('fields' => array('Tblshoppingcart.Orderid'),'order' => array('Orderid' => 'DESC')));
		if(count($orderid_arr)==0)
			$orderid = '1000';
		else
		{
			$idval = $orderid_arr['Tblshoppingcart']['Orderid'];
			$orderid = $idval+1;
		}
		return $orderid;
	}
	function getexpdate()
	{
		$query = "SELECT `Expiration_duration` FROM `tblconfig` ORDER BY `Datecreated` DESC LIMIT 1";
		$expdate_arr =  $this->Tblconfig->query($query);
		$exp_dur = $expdate_arr[0]['tblconfig']['Expiration_duration'];
		$date = date('m/d/y');
		$expdate =  date('Y-m-d',strtotime('+'.$exp_dur.' days',strtotime($date)));
		return $expdate;
	}
	function cart_export()
	{		
		set_time_limit(0);
        Configure::write('debug', 1);
		$userid   = $this->Auth->user('Userid');
		$all_subcatid = $this->Session->read('subid_arr');
		for($i=0;$i<sizeof($all_subcatid);$i++)
		{
			$subcat = explode(",",$all_subcatid[$i]);
			for($j=0;$j<sizeof($subcat);$j++)
			{
				$search_qry = "SELECT k.Keyword, k.G_Volume,k.US_Volume,k.Type,k.CPC FROM tblkeyword k 
								WHERE k.Subcategoryid =".$subcat[$j]." order by k.Keyword ASC";
				$response = $this->Tblkeyword->query($search_qry);
				
				$sub_qry = "SELECT Subcategory from tblsubcategory where Subcategoryid=".$subcat[$j];
				$subcat_arr = $this->Tblsubcategory->query($sub_qry);
				$subact = $subcat_arr[0]['tblsubcategory']['Subcategory'];
				
				/*  get filename from tblshoppingcart **/
				$file_qry = "SELECT `Filename` FROM `tblshoppingcart` WHERE `Userid`=".$userid." 
							AND `Subcategoryid`=".$subcat[$j];
				$file_arr = $this->Tblshoppingcart->query($file_qry);					
				$filename = $file_arr[0]['tblshoppingcart']['Filename'];
				
				$this->set('result', $response);
				$this->set('filename',$subact);
				$this->autoLayout = false;
				Configure::write('debug', '0');
				$export = new ExporttoExcel($response,$filename);
			}
			
		}
		
		/** empty session variables ***/
		$this->Session->delete('description_arr');
		$this->Session->delete('subid_arr');
		$this->Session->delete('catid_arr');
		$this->Session->delete('totcount_arr');
		$this->Session->delete('totprice_arr');
		$this->Session->delete('totflow_arr');
		$this->Session->delete('subcount_arr');
		$this->Session->delete('subprice_arr');
		
	}
	
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
	public function validate_kw()
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
			$sess_subid_data =	$this->Session->read('subid_arr');
			$sess_subid_data1 =	$this->Session->read('subid_arr');
			$sess_desc_data	 =  $this->Session->read('description_arr');			
			$sess_catid_data =	$this->Session->read('catid_arr');
			$sess_totcount_data =	$this->Session->read('totcount_arr');
			$sess_totprice_data =  $this->Session->read('totprice_arr');
			$sess_subcount_data =	$this->Session->read('subcount_arr');
			$sess_subprice_data =	$this->Session->read('subprice_arr');
				
			$count_sess_subid=count($sess_subid_data);				
			for($f=0; $f<count($sess_subid_data1); $f++)
			{				
				$r=array();
				if (strpos($sess_subid_data1[$f], ',') != false ||strpos($sess_subcount_data[$f], ',') != false ||strpos($sess_catid_data[$f], ',') != false ||strpos($sess_subprice_data[$f], ',') != false)
				{
					$subcat_id = explode(",",$sess_subid_data1[$f]);
					$subcat_count = explode(",",$sess_subcount_data[$f]);
					$catid_exp = explode(",",$sess_catid_data[$f]);
					$subprice_exp = explode(",",$sess_subprice_data[$f]);
				}
				else
				{
					$subcat_id = array($sess_subid_data1[$f]);
					$subcat_count = array($sess_subcount_data[$f]);
					$catid_exp = array($sess_catid_data[$f]);
					$subprice_exp = array($sess_subprice_data[$f]);
				}						
				for($b=0; $b<count($subcat_id); $b++)
				{		
					$split_subid = $subcat_id[$b];					
									
					for($e=0; $e<count($cart_subids); $e++)
					{
						$cart_subid = $cart_subids[$e]['tblshoppingcart']['Subcategoryid'];											
						$expdate = $cart_subids[$e]['tblshoppingcart']['Expiration_Date'];
						$kw_count_qry = "SELECT SUM(c.Keyword_count) as kwcount,s.Keyword_count
										FROM `tblshoppingcart` c,`tblsubcategory` s	
										WHERE 
										c.Subcategoryid=s.Subcategoryid AND
										c.`Subcategoryid` = ".$cart_subid." AND 
										c.`Userid` = ".$userid."";
						$kw_count_arr = $this->Tblshoppingcart->query($kw_count_qry);
						$cart_kw_count = $kw_count_arr[0][0]['kwcount'];
						$exp_date = strtotime($expdate);
						$now_date = strtotime($date);
						$now_count = $kw_count_arr[0]['s']['Keyword_count'];						
						if($split_subid==$cart_subid)
						{							
							if($exp_date>$now_date)
							{
								/*** remove already purchased subcategory **/
								$r[]=$b;								
							}
							else
							{
								if($now_count>$cart_kw_count)
								{									
									$count_diff = $now_count-$cart_kw_count;									
									$new_subcat_price =  $count_diff * 
														($subprice_exp[$b]/$subcat_count[$b]);
								    $new_subcat_price = number_format((float)$new_subcat_price, 2, '.', '');
									
									$sess_totcount_data[$f] = $sess_totcount_data[$f]-$subcat_count[$b];
									$sess_totprice_data[$f] = $sess_totprice_data[$f]-$subprice_exp[$b];
									
									$subcat_count[$b] = $count_diff;
									$subprice_exp[$b] = $new_subcat_price;
									
									$sess_totcount_data[$f] = $sess_totcount_data[$f]+$subcat_count[$b];
									$sess_totprice_data[$f] = $sess_totprice_data[$f]+$subprice_exp[$b];
									
									$const_subcount_arr = array_values($subcat_count);
									$newimp_subcount = implode(',', $const_subcount_arr);
									$sess_subcount_data[$f] = $newimp_subcount;
									
									$const_subprice_arr = array_values($subprice_exp);
									$newimp_subprice = implode(',', $const_subprice_arr);
									$sess_subprice_data[$f] = $newimp_subprice;									
								}
								else
								{
									/*** remove already purchased subcategory **/
									$r[]=$b;									
								}
							}
						}
					}
				}				
				if(count($r)>0)
				{
					for($x=0; $x<count($r); $x++)
					{
						$sess_totcount_data[$f] = $sess_totcount_data[$f]-$subcat_count[$r[$x]];
						$sess_totprice_data[$f] = $sess_totprice_data[$f]-$subprice_exp[$r[$x]];
						unset($subcat_id[$r[$x]]);
						unset($subcat_count[$r[$x]]);
						unset($catid_exp[$r[$x]]);
						unset($subprice_exp[$r[$x]]);						
						if(count($subcat_id)>0)
						{
							$const_subid_arr = array_values($subcat_id);
							$newimp_subid = implode(',', $const_subid_arr);
							$sess_subid_data[$f] = $newimp_subid;
							
							$const_subcount_arr = array_values($subcat_count);
							$newimp_subcount = implode(',', $const_subcount_arr);
							$sess_subcount_data[$f] = $newimp_subcount;
							
							$const_catid_arr = array_values($catid_exp);
							$newimp_catid = implode(',', $const_catid_arr);
							$sess_catid_data[$f] = $newimp_catid;
							
							$const_subprice_arr = array_values($subprice_exp);
							$newimp_subprice = implode(',', $const_subprice_arr);
							$sess_subprice_data[$f] = $newimp_subprice;
						}
						else
						{							
							unset($sess_subid_data[$f]);							
							unset($sess_catid_data[$f]);
							unset($sess_subcount_data[$f]);
							unset($sess_subprice_data[$f]);
							unset($sess_totcount_data[$f]);
							unset($sess_totprice_data[$f]);
							unset($sess_desc_data[$f]);							
						}
					}
					
				}				
				$this->Session->write('subid_arr', $sess_subid_data);
				$this->Session->write('description_arr', $sess_desc_data);
				$this->Session->write('catid_arr', $sess_catid_data);
				$this->Session->write('totcount_arr', $sess_totcount_data);
				$this->Session->write('subcount_arr', $sess_subcount_data);
				$this->Session->write('totprice_arr', $sess_totprice_data);
				$this->Session->write('subprice_arr', $sess_subprice_data);
				
			}
			
		}
	}
}
