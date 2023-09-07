<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Poltekpel Sorong")
							 ->setLastModifiedBy("Poltekpel Sorong")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(3);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(2);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(36);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(18);


//Rows 1
$style = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	),
	'font' => [
        'size' => 16
    ]
);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:P1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:P1')->applyFromArray($style);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', 'BUKU KAS UMUM');

//Rows 3
$objPHPExcel->getActiveSheet()->getStyle('B1:P1')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B3', 'Periode : Nopember 2019')
			->setCellValue('P3', 'Mata Uang : IDR');

//Rows 4
$style = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);
$border_style= array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
	);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B4:P5")->applyFromArray($border_style);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D4:M4');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:P4')->applyFromArray($style);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B4', 'Tanggal')
            ->setCellValue('C4', 'Nomor Bukti')
            ->setCellValue('D4', 'Uraian')
            ->setCellValue('N4', 'Debet')
            ->setCellValue('O4', 'Kredit')
            ->setCellValue('P4', 'Saldo');


//Rows 5
$style = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D5:M5');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:P5')->applyFromArray($style);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B5', '1')
            ->setCellValue('C5', '2')
            ->setCellValue('D5', '3')
            ->setCellValue('N5', '4')
            ->setCellValue('O5', '5')
			->setCellValue('P5', '6');
			

//Rows 6 SALDO AWAL
$style = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);

$Border = array(
  'borders' => array(
    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
  ),
);
$colmn = array("B","C","N","O","P");
for($i=0; $i<count($colmn); $i++){
	$objPHPExcel->setActiveSheetIndex(0)->getStyle($colmn[$i]."6")->applyFromArray($Border);
}
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B6:C6')->applyFromArray($style);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B6', '1/11/19')
            ->setCellValue('C6', '001')
            ->setCellValue('D6', 'Saldo Awal')
            ->setCellValue('N6', '215891')
            ->setCellValue('O6', '0')
            ->setCellValue('P6', '215891');




// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('BKU');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
