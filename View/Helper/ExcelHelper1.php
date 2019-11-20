<?php  

class ExcelHelper extends AppHelper  
{ 
    var $filename = 'arquive'; 
     
    var $rows = array(); 
     
    var $header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?\> 
<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" 
 xmlns:x=\"urn:schemas-microsoft-com:office:excel\" 
 xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" 
 xmlns:html=\"http://www.w3.org/TR/REC-html40\">"; 
  
    var $footer = "</Workbook>"; 
     
    var $worksheet_title = "Table"; 
     
    function getHeaders () { 
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8"); 
        header("Content-Disposition: inline; filename=\"" . $this->filename . ".xls\""); 
    } 
	function setWorkSheetName($workSheetName) {
      echo "\t<Worksheet ss:Name=\"".$workSheetName."\">\n";
      echo "\t\t<Table>\n";
      return;
    }
     
    function addRow ($data = array()) { 
         
        foreach($data as $key => $value) { 
            $data[$key] = "<Cell><Data ss:Type=\"String\"><![CDATA[" . $value . "]]></Data></Cell>\n"; 
        } 
         
        $this->rows[] = $data; 
    } 
     
    function setTitle ($title) { 
        $title = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $title); 
        $title = substr ($title, 0, 31); 
        $this->worksheet_title = $title; 
    } 
     
    function render ($file = null) { 
        $this->filename = ($file) ? $file : $this->filename; 
        $this->getHeaders(); 
         
        $out = array(); 
        foreach($this->rows as $row) { 
            $out[] = "<Row>\n" . implode('', $row) . "</Row>\n"; 
        } 
         
        $data = implode("\n", $out); 
         
        echo stripslashes ($this->header); 
        echo "\n<Worksheet ss:Name=\"" . $this->worksheet_title . "\">\n<Table>\n"; 
        echo "<Column ss:Index=\"1\" ss:AutoFitWidth=\"0\" ss:Width=\"110\"/>\n"; 
        echo $data; 
        echo "</Table>\n</Worksheet>\n"; 
        echo $this->footer; 
    } 
} 
?>