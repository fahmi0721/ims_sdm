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
							 ->setDescription("Form Data BPJS KES")
							 ->setKeywords("Form Data BPJS KES")
							 ->setCategory("Form Data BPJS KES");

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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E11:H11');

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', 'PETUNJUK PENGISIAN FORM UPLOAD DATA BPJS KESEHATAN')
            ->setCellValue('B3', 'FIELD COLOR')
            ->setCellValue('B7', 'CATATAN :')
            ->setCellValue('C7', 'DATA YANG DIUPLOAD ADALAH DATA TENAGA KERJA YANG TELAH ADA DALAM SISTEM IMS V.2')
            ->setCellValue('C3', 'KETERANGAN')
            ->setCellValue('C4', 'WAJIB DIISI')
            ->setCellValue('C5', 'WAJIB DIISI & PENGISIAN MENGGUNAKAN KODE DATA');

/** SET TITILE Data */

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E2', 'STATUS KEPESERTAAN')
            ->setCellValue('E3', 'NO')
            ->setCellValue('E4', '1')
            ->setCellValue('E5', '2')
            ->setCellValue('E6', '3')
            ->setCellValue('E7', '4')
            ->setCellValue('E8', '5')
            ->setCellValue('E9', '6')
            ->setCellValue('F3', 'KODE')
            ->setCellValue('F4', '0')
            ->setCellValue('F5', '1')
            ->setCellValue('F6', '2')
            ->setCellValue('F7', '3')
            ->setCellValue('F8', '4')
            ->setCellValue('F9', '5')
            ->setCellValue('G3', 'KETERANGAN')
            ->setCellValue('G4', 'PT ISMA')
            ->setCellValue('G5', 'TNI/POLRI')
            ->setCellValue('G6', 'JAMKESDA/JAMKESMAS/JAMKESTA')
            ->setCellValue('G7', 'NON PNS')
            ->setCellValue('G8', 'PBI APBN/APBD')
            ->setCellValue('G9', 'PENSIUNAN');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:G3")->applyFromArray($Orange);
/** SET BORDER SATATUS */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:G9")->applyFromArray($border_style);


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E11', 'STATUS')
            ->setCellValue('E12', 'NO')
            ->setCellValue('E13', '1')
            ->setCellValue('E14', '2')
            ->setCellValue('F12', 'KODE')
            ->setCellValue('F13', '0')
            ->setCellValue('F14', '1')
            ->setCellValue('G12', 'KETERANGAN')
            ->setCellValue('G13', 'TIDAK AKTIF')
            ->setCellValue('G14', 'AKTIF');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E12:G12")->applyFromArray($Orange);
/** SET BORDER SATATUS */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E12:G14")->applyFromArray($border_style);

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
            ->setCellValue('C1', 'NO JKN')
            ->setCellValue('D1', 'STATUS KEPESERTAAN')
            ->setCellValue('E1', 'TANGGAL DAFTAR')
            ->setCellValue('E2', 'TAHUN')
            ->setCellValue('F2', 'BULAN')
            ->setCellValue('G2', 'TANGGAL')
            ->setCellValue('H1', 'STATUS');
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:C2")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("E1:G2")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("D1:D2")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("H1:H2")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:H2")->applyFromArray($TextTitle);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:H12")->applyFromArray($border_style);
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:A2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('B1:B2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('C1:C2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('D1:D2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('H1:H2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E1:G1');




$objPHPExcel->getActiveSheet(1)->setTitle('FORM UPLOAD BPJS KESEHATAN');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(1);








$Time = time();

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="FORM UPLOAD DATA BPJS KESEHATAN.xls"');
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
