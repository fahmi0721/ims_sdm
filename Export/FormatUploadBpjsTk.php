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
require_once dirname(__FILE__) . '/../config/config.php';
require_once dirname(__FILE__) . '/../lib/PHPExcel/Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/fungsi.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("PT ISMA")
							 ->setLastModifiedBy("PT ISMA")
							 ->setTitle("Format Export")
							 ->setSubject("Format Export")
							 ->setDescription("Form Data Tenaga Kerja")
							 ->setKeywords("Form Data Tenaga Kerja")
							 ->setCategory("Form Data Tenaga Kerja");

$Cell = Cells();
foreach($Cell as $key => $Cll){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($Cll)->setAutoSize(true);
}


/** SET BORDER  */
$border_style= array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));


/** SET COLOR */
/** BG COLOR */
$Orange = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'E65B25')));
$BiruTua = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '3A539B')));
$BiruMuda = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '5C97BF')));
$Hijau = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '2ECC71')));
/** TEXT COLOR */
$TextTitle = array('font'  => array('bold'  => true,'color' => array('rgb' => 'FFFFFF'),'size'  => 11,'name'  => 'Arial'));  
$TextNormal = array('font'  => array('bold'  => false,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Arial'));  



/** SET TEXT-COLOR B1 - C7 */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B1:C7")->applyFromArray($TextNormal);
/** SET BG COLOR B4-B7 */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B4")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B5")->applyFromArray($BiruTua);
/** SET BORDER B3-C7 */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B3:C5")->applyFromArray($border_style);

/** MARGE CELL */
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:C1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:H2');

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', 'PETUNJUK PENGISIAN FORM UPLOAD DATA BPJS TK')
            ->setCellValue('B3', 'FIELD COLOR')
            ->setCellValue('B7', 'CATATAN :')
            ->setCellValue('C7', 'DATA YANG DIUPLOAD ADALAH DATA TENAGA KERJA YANG TELAH ADA DALAM SISTEM IMS V.2')
            ->setCellValue('C3', 'KETERANGAN')
            ->setCellValue('C4', 'WAJIB DIISI')
            ->setCellValue('C5', 'WAJIB DIISI & PENGISIAN MENGGUNAKAN KODE DATA');

/** SET TITILE Data */

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E2', 'STATUS')
            ->setCellValue('E3', 'NO')
            ->setCellValue('E4', '1')
            ->setCellValue('E5', '2')
            ->setCellValue('F3', 'KODE')
            ->setCellValue('F4', '0')
            ->setCellValue('F5', '1')
            ->setCellValue('G3', 'KETERANGAN')
            ->setCellValue('G4', 'TIDAK AKTIF')
            ->setCellValue('G5', 'AKTIF');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:G3")->applyFromArray($Orange);
/** SET BORDER SATATUS */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:G5")->applyFromArray($border_style);

// Rename worksheet
$objPHPExcel->getActiveSheet(0)->setTitle('PETUNJUK PENGISIAN');


$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);

/** FORM UPLAOD DATA */
$Cell = Cells();
foreach($Cell as $key => $Cll){
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($Cll)->setWidth(10);
}

/** SET BG COLOR */


$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'NO KTP')
            ->setCellValue('C1', 'NO KPJ')
            ->setCellValue('D1', 'TANGGAL DAFTAR')
            ->setCellValue('D2', 'TAHUN')
            ->setCellValue('E2', 'BULAN')
            ->setCellValue('F2', 'TANGGAL')
            ->setCellValue('G1', 'STATUS');
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:G2")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("G1:G2")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:G2")->applyFromArray($TextTitle);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:G12")->applyFromArray($border_style);
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:A2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('B1:B2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('C1:C2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('G1:G2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('D1:F1');




$objPHPExcel->getActiveSheet(1)->setTitle('FORM UPLOAD BPJS TK');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(1);








$Time = time();

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="FORM UPLOAD DATA BPJS TK.xls"');
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
