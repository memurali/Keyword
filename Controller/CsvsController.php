<?php
App::build(array('Vendor' => array(APP . 'Vendor' . DS . 'PHPExcel' . DS)));

error_reporting(E_ALL ^ E_NOTICE);
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel.php'));
App::import('Vendor', 'Exporttoexcel', array('file' =>'php-excel-reader' . DS . 'Exporttoexcel.php'));
App::import('Vendor', 'ExcelReaderLib', array('file' =>'php-excel-reader' . DS . 'ExcelReaderLib.php'));
App::uses('PHPExcel.php', 'Vendor');
App::uses('php-excel-reader/ExcelReaderLib.php', 'Vendor');
App::uses('php-excel-reader/Exporttoexcel.php', 'Vendor');

class CsvsController extends AppController
{   
    var $helpers = array('Html', 'Form', 'Csv', 'Js', 'Paginator');    
    public $components = array('Paginator', 'RequestHandler');
	public $uses = array('Tbltemp','Tblcategory','Tblsubcategory','Tblkeyword','User','Tblshoppingcart');
    function beforeFilter()
    {
		 $this->Auth->allow('');
	}
	function dash_import()
	{		
		set_time_limit(0);
		$this->layout = false;
		$currentdate = date('Y-m-d H:i:s');
		$total_orders=$this->totalorders();
		$this->set('total_orders',$total_orders);
		$status = array();
		try 
		{
			if ($this->request->is('post') == 1) { 
				//check the file type
				if (isset($_FILES ['data']['type']['impfile']) == "application/vnd.ms-excel") 
				{
					//check the file size
					if ($_FILES ['data']['size']['impfile'] >= 2097152) 
					{
						$status = 'File must be less than 2 MB.';
					}
					else if($_FILES['data']['size']['impfile'] == 0)
					{
						$status = 'File is empty';
					}
					else
					{
						$filename = $_FILES ['data']['tmp_name']['impfile'];
						$file     = fopen($filename, "r");
						$total    = 0;
						$this->Tbltemp->query('TRUNCATE table tbltemp');
						$data     = fgetcsv($file);
						$case_data = array_map("strtolower",$data);
						$category_index = array_search(strtolower("Department"), $case_data);
						$subcategory_index = array_search(strtolower("Category"), $case_data);
						$keyword_index   = array_search(strtolower("keyword"), $case_data);
						$gvol_index      = array_search(strtolower("Volume (global)"), $case_data);
						$usvol_index    = array_search(strtolower("Volume (USA)"), $case_data);
						$type_index    = array_search(strtolower("Type"), $case_data);
						$cpc_index    = array_search(strtolower("CPC"), $case_data);
						if ((trim($category_index) == "") || (trim($subcategory_index) == "")||
							(trim($keyword_index) == "") || (trim($gvol_index) == "")||
							(trim($usvol_index) == "") ||(trim($type_index) == "")||(trim($cpc_index) == "")){
							$status = "Please check  all fields";
							
						}
						else 
						{
							while (($data = fgetcsv($file)) !== FALSE) 
							{
								if (array(null) !== $data) 
								{ // ignore blank lines
									$category   = preg_replace('/[\^Â£Ã‡Â¥$%()}{#~?><>,|=_+Â¬-]/', '', $data[$category_index]);
                                    $subcategory   = preg_replace('/[\^Â£Ã‡Â¥$%()}{#~?><>,|=_+Â¬-]/', '', $data[$subcategory_index]);
                                    $keyword   = preg_replace('/[\^Â£Ã‡Â¥$%()}{#~?><>,|=_+Â¬-]/', '', $data[$keyword_index]);
                                    $gvolume   = preg_replace('/[\^Â£Ã‡Â¥$%()}{#~?><>,|=_+Â¬-]/', '', $data[$gvol_index]);
                                    $usvolume   = preg_replace('/[\^Â£Ã‡Â¥$%()}{#~?><>,|=_+Â¬-]/', '', $data[$usvol_index]);
                                    $type   = preg_replace('/[\^Â£Ã‡Â¥$%()}{#~?><>,|=_+Â¬-]/', '', $data[$type_index]);
									$cpc   = preg_replace('/[\^Â£Ã‡Â¥$%()}{#~?><>,|=_+Â¬-]/', '', $data[$cpc_index]);
									if($cpc=='')
										$cpc  = 0.00;										
									$insert_data = array(
													"Category"=>$category,
													"Subcategory"=>$subcategory,
													"Keyword"=>$keyword,
													"G_Volume"=>$gvolume,
													"US_Volume"=>$usvolume,
													"Type"=>$type,
													"CPC"=>$cpc);											
									$this->Tbltemp->saveAll($insert_data);
								}
								
							}
							
							/*** Remove duplicates in tbltemp ***/
							$this->Tbltemp->query("DELETE FROM tbltemp WHERE Id IN (SELECT * FROM 
												 (SELECT Id FROM tbltemp GROUP BY Category, Subcategory, Keyword, 
												 G_Volume,US_Volume,Type HAVING (COUNT(*) > 1)) AS A )");

							
							/****    Get data for preview  ****/
							$data_category = $this->preview_data('Category','tblcategory');
							$data_subcategory = $this->preview_data('Subcategory','tblsubcategory');
							$data_keyword = $this->preview_data('Keyword','tblkeyword');
							$this->set('category',$data_category);
							$this->set('subcategory',$data_subcategory);
							$this->set('keyword',$data_keyword);					
												
						}
					}
				}
				else
					$status = 'Check file type';
			}
			$path="../../uploads/".$subcategory.'_'.date('Y_m_d').".csv";
			move_uploaded_file($filename, $path);
			
			/***** Save records to their tables ***/
			if ($this->request->is('ajax')) 
			{
				if($this->request->data['proceed']!='')
				{
					$save_cat = $this->savecategory();
					$save_subcat = $this->savesubcategory();
					$save_keyword = $this->savekeyword();
					$update_volume = $this->Updatekeyword();
					
					/*** update total keyword count to subcategory table ***/
					$count_qry = "UPDATE tblsubcategory s 
								  INNER JOIN (SELECT Subcategoryid,COUNT(Keyword) as kwcount FROM tblkeyword  GROUP BY Subcategoryid) as keyword ON keyword.Subcategoryid = s.Subcategoryid 
								  SET s.Keyword_count = keyword.kwcount";
					$this->Tblsubcategory->query($count_qry);
					
					echo '<h2>Details of records are saved as follows:</h2>';
					echo '<table>';
						echo '<tr>';
							echo '<th>Data</th>';
							echo '<th>Count</th>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Category</td>';
							echo  '<td>'.$save_cat.'</td>';
						echo'</tr>';
						echo '<tr>';
							echo '<td>Subcategory</td>';
							echo  '<td>'.$save_subcat.'</td>';
						echo'</tr>';
						echo '<tr>';
							echo '<td>Keyword</td>';
							echo  '<td>'.$save_keyword.'</td>';
						echo'</tr>';
						if($update_volume[0]>0)
						{
							echo '<tr>';
								echo '<td>Others</td>';
								echo '<td>'.$update_volume[0].'</td>';
							echo '</tr>';
						}
					echo '</table>';
					if($update_volume[1]>0)
						echo '<span>'.count($update_volume[1]). 'Number of Keywords Updated with new category or subcategory'.'</span>';
					exit;
				}
				
			}
			
		}
        catch (\Exception $e) 
		{
            $status = $e->getMessage();
        }
        $this->set('result', $status);
	}
	
	function preview_data($fields, $table)
	{
		if($table=='tblkeyword')
		{
			$temp_query = 'SELECT tbltemp.Keyword,tbltemp.G_Volume,
						   tbltemp.US_Volume,tblkeyword.Keyword,tblkeyword.G_Volume,
						   tblkeyword.US_Volume,tbltemp.Type,tblkeyword.Type,tbltemp.CPC,tblkeyword.CPC FROM `tbltemp` LEFT JOIN tblkeyword ON 
						   tblkeyword.Keyword=tbltemp.Keyword ORDER BY Id ASC';
						   
		}
		else
		{
			$temp_query ='SELECT tbltemp.'.$fields.' , '.$table.'.'.$fields.' 
						  FROM `tbltemp` LEFT JOIN '.$table.' ON '.$table.'.'.$fields.'=tbltemp.'.$fields.' 
						  ORDER BY Id ASC';	
		}
		$temp_vals = $this->Tbltemp->query($temp_query);
		return $temp_vals;
	}
	function savecategory()
	{
		$currentdate = date('Y-m-d H:i:s');
		$temp_query = 'SELECT distinct tbltemp. Category FROM `tbltemp`  LEFT OUTER JOIN 
					  tblcategory ON tbltemp.Category= tblcategory.Category WHERE 
					  tblcategory.Category IS null GROUP BY tbltemp.Subcategory 
					  ORDER BY tbltemp.Category ASC';
		$temp_vals = $this->Tbltemp->query($temp_query);
		if($temp_vals!='')
		{
			$count = count($temp_vals);
			for($j=0; $j<count($temp_vals); $j++)
			{
				$temp_val = $temp_vals[$j]['tbltemp']['Category'];
				/*** check subcategory already exists **/
				$check_subcat = "SELECT s.`Categoryid` FROM `tblsubcategory` s, tbltemp t WHERE 
								t.Subcategory=s.`Subcategory` AND t.Category = '".trim(mysql_escape_string($temp_val))."'";
				$catid = $this->Tblsubcategory->query($check_subcat);
				if(count($catid)==0)
				{
					$data = array(
							"Category" => $temp_val,
							"Active" => "Y",
							"Datecreated" => $currentdate);
				}
				else
				{
					$count = $count-1;
					echo "We can't save more than one category for same subcategory";
					echo '<br>';
					continue;
				}	
				
				$this->Tblcategory->saveAll($data);
				
			}
			return $count;
		}
	}
	function savesubcategory()
	{
		$currentdate = date('Y-m-d H:i:s');
		$temp_query = 'SELECT distinct tbltemp.Subcategory FROM `tbltemp`  LEFT OUTER JOIN 
					  tblsubcategory ON tbltemp.Subcategory = tblsubcategory.Subcategory WHERE 
					  tblsubcategory.Subcategory IS null';
		$temp_vals = $this->Tbltemp->query($temp_query);
		if($temp_vals!='')
		{
			for($j=0; $j<count($temp_vals); $j++)
			{
				$temp_val = $temp_vals[$j]['tbltemp']['Subcategory'];
								
				$catid_query = "SELECT DISTINCT c.Categoryid FROM tblcategory c, 
								 tbltemp t WHERE t.Category=c.Category and 
								 t.Subcategory='".trim(mysql_escape_string($temp_val))."' LIMIT 1";
				$catid_arr = $this->Tbltemp->query($catid_query);
				$catid = $catid_arr[0]['c']['Categoryid'];
				$data = array(
						"Categoryid" => $catid,
						"Subcategory" => $temp_val,
						"Active" => "Y",
						"Datecreated" => $currentdate);
						
				$this->Tblsubcategory->saveAll($data);
				
			}
			return count($temp_vals);
		}
	}
	
	function savekeyword()
	{
		$currentdate = date('Y-m-d H:i:s');
		$key_query = "SELECT distinct t.keyword, t.G_Volume,t.US_Volume,t.Type,t.CPC FROM `tbltemp` t 
					  LEFT OUTER JOIN tblkeyword k ON t.keyword = k.keyword WHERE k.keyword IS null";
		$key_vals = $this->Tbltemp->query($key_query);
		if($key_vals!='')
		{
			for($k=0; $k<count($key_vals); $k++)
			{
				$keyword = $key_vals[$k]['t']['keyword'];
				$gvol = $key_vals[$k]['t']['G_Volume'];
				$usvol = $key_vals[$k]['t']['US_Volume'];
				$type = $key_vals[$k]['t']['Type'];
				$cpc = $key_vals[$k]['t']['CPC'];
				$subcat_query ="SELECT s.Subcategoryid FROM tblsubcategory s, tbltemp t 
								WHERE t.Subcategory = s.Subcategory AND t.Keyword='".trim(mysql_escape_string($keyword))."'";
				$subcat_arr = $this->Tbltemp->query($subcat_query);
				$subcatid = $subcat_arr[0]['s']['Subcategoryid'];
				$data = array(
						"Subcategoryid" => $subcatid,
						"Keyword"=> $keyword,
						"G_Volume" => $gvol,
						"US_Volume" => $usvol,
						"Type" => $type,
						"CPC" => $cpc,
						"Datecreated" => $currentdate
						);
				
				$this->Tblkeyword->saveAll($data);
			}
			
			
			
			return count($key_vals);
		}
		
	}
	function Updatekeyword()
	{
		/** same keyword different volume count ***/
		$update_vol = "UPDATE tblkeyword b LEFT JOIN tbltemp a ON a.keyword = b.keyword 
					SET b.US_Volume = a.US_Volume, 
						b.G_Volume = a.G_Volume,
						b.CPC = a.CPC
					where a.US_Volume != b.US_Volume or a.G_Volume != b.G_Volume or a.CPC != b.CPC";
		$this->Tblkeyword->query($update_vol);
		$affectrows = $this->Tblkeyword->getAffectedRows();
		
		/**** Update inactive category and subcategory ****/
		$update_active = "UPDATE tblcategory c, tblsubcategory s, tblkeyword k LEFT JOIN tbltemp t ON 
						  t.keyword = k.keyword 
						  SET c.Active = 'Y', 
							  s.Active = 'Y' where 
						  k.Subcategoryid = s.Subcategoryid AND s.Categoryid = c.Categoryid AND 
						  s.Active='N' OR c.Active='N'";
		$this->Tblcategory->query($update_active);
		
		
		/*** New subcategory and Exist keyword***/
		$sel_subcat= "SELECT t.keyword, k.Keyid,t.Subcategory,s.Subcategory,
					  t.G_Volume,t.US_Volume,t.CPC FROM tbltemp t, tblkeyword k, 
					  tblsubcategory s WHERE t.Keyword = k.Keyword AND 
					  t.Subcategory!=s.Subcategory AND 
					  s.Subcategoryid = k.Subcategoryid";
		$subcatarr = $this->Tbltemp->query($sel_subcat);
		if(count($subcatarr)>0)
		{
			for($s=0; $s<count($subcatarr); $s++)
			{
				$keyid = $subcatarr[$s]['k']['Keyid'];
				$newusvol = $subcatarr[$s]['t']['US_Volume'];
				$newgvol = $subcatarr[$s]['t']['G_Volume'];
				$subcategory = $subcatarr[$s]['t']['Subcategory'];
				$cpc = $subcatarr[$s]['t']['CPC'];
				$newsubcat_arr = $this->Tblsubcategory->query("SELECT `Subcategoryid` FROM `tblsubcategory` 
								WHERE `Subcategory`='".$subcategory."'");
				$subcatid = $newsubcat_arr[0]['tblsubcategory']['Subcategoryid'];
				$update_qry = "UPDATE `tblkeyword` SET `Subcategoryid`='".$subcatid."',`G_Volume`='".$newgvol."',
							  `US_Volume`='".$newusvol."',`CPC`='".$cpc."' WHERE `Keyid`='".$keyid."'";
				$update = $this->Tblkeyword->query($update_qry);
			}
			
		}
		return array($affectrows,count($subcatarr));
	}
	function dash_export()
    {
        set_time_limit(0);
        Configure::write('debug', 1);
		$subcatid = $this->params->pass[0];
		$userid   = $this->Auth->user('Userid');
		$search_qry = "SELECT k.Keyword, k.G_Volume,k.US_Volume,k.Type,k.CPC FROM tblkeyword k 
						WHERE k.Subcategoryid =".$subcatid." order by k.Keyword ASC";
		$response = $this->Tblkeyword->query($search_qry);
		
		$sub_qry = "SELECT Subcategory from tblsubcategory where Subcategoryid=".$subcatid;
		$subcat_arr = $this->Tblsubcategory->query($sub_qry);
		$subact = $subcat_arr[0]['tblsubcategory']['Subcategory'];
		
		/**  get filename from tblshoppingcart ***/
		$file_qry = "SELECT `Filename` FROM `tblshoppingcart` WHERE `Userid`=".$userid." 
					AND `Subcategoryid`=".$subcatid;
		$file_arr = $this->Tblshoppingcart->query($file_qry);	
		$filename = $file_arr[0]['tblshoppingcart']['Filename'];
		
		$this->set('result', $response);
		$this->set('filename',$subact);
		$this->autoLayout = false;
        Configure::write('debug', '0');	
	}
	//Orders count
	function totalorders()
	{		
		$sel_qry = "SELECT distinct(`Orderid`) FROM `tblshoppingcart` 
		            WHERE `View_status` ='' 
					ORDER BY `Orderid` DESC";
		$view_data = $this->Tblshoppingcart->query($sel_qry);
		return count($view_data);	
	}
	
}