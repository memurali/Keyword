<?php
App::import('Vendor', 'PHPExcel', array('file' =>'PHPExcel' . DS . 'PHPExcel.php'));

class Exporttoexcel
{         
    function ExporttoExcel($excelData,$fileName)
		{
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Add column headers
		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', 'Keyword')
			->setCellValue('B1', 'G_Volume')
			->setCellValue('C1', 'US_Volume')
			->setCellValue('D1', 'Type');
			//->setCellValue('E1', 'CPC');
		//cell values	
		for($i=0; $i<=count($excelData); $i++)
		{
		$ii = $i+2;
		
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $excelData[$i]['k']['Keyword']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $excelData[$i]['k']['G_Volume']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $excelData[$i]['k']['US_Volume']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $excelData[$i]['k']['Type']);
		//$objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $excelData[$i]['k']['CPC']);
		}

		// Set worksheet title
		$objPHPExcel->getActiveSheet()->setTitle($fileName);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('Export_csv/'.$fileName);

		}
        
   } 




?>