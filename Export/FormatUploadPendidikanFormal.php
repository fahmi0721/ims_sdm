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
							 ->setDescription("Form Data Pendidikan Formal")
							 ->setKeywords("Form Data Pendidikan Formal")
							 ->setCategory("Form Data Pendidikan Formal");

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
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E2:H2")->applyFromArray($TextNormal);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:F3")->applyFromArray($TextTitle);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("H3:I3")->applyFromArray($TextTitle);
/** SET BG COLOR B4-B7 */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B4")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:F3")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("H3:I3")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B5")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B6")->applyFromArray($BiruMuda);
/** SET BORDER B3-C7 */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B3:C6")->applyFromArray($border_style);

/** MARGE CELL */
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:C1');

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', 'PETUNJUK PENGISIAN FORM UPLOAD DATA REKENING BANK')
            ->setCellValue('B3', 'FIELD COLOR')
            ->setCellValue('C4', 'WAJIB DIISI')
            ->setCellValue('C5', 'WAJIB DIISI & PENGISIAN MENGGUNAKAN KODE DATA')
            ->setCellValue('C6', 'BOLEH DIKOSONGKAN JIKA DATA TIDAK DIKETAHUI, & PENGISIAN MENGGUNAKAN KODEDATA')
            ->setCellValue('B8', 'CATATAN :')
            ->setCellValue('C8', 'DATA YANG DIUPLOAD ADALAH DATA TENAGA KERJA YANG TELAH ADA DALAM SISTEM IMS V.2')
            ->setCellValue('C3', 'KETERANGAN');

// Add some data
$RowAwal = 4;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E2', 'PENDIDIKAN FORMAL')
            ->setCellValue('E3', 'KODE')
            ->setCellValue('F3', 'PENDIDIKAN FORMAL');

$LoadPendidikanFormal = LoadPendidikanFormal();
if($LoadPendidikanFormal['Row'] > 0){
    $i = $RowAwal;
    foreach($LoadPendidikanFormal['Data'] as $key => $IData){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$i, $IData['Kode'])
            ->setCellValue('F'.$i, strtoupper($IData['Nama']));
        $i++;
    }
    $Stop = $i -1;
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:F".$Stop)->applyFromArray($border_style);
}
            

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H2', 'SUB PENDIDIKAN FORMAL')
            ->setCellValue('H3', 'KODE')
            ->setCellValue('I3', 'PENDIDIKAN FORMAL');

$LoadSubPendidikanFormal = LoadSubPendidikanFormal();
if($LoadSubPendidikanFormal['Row'] > 0){
    $i = $RowAwal;
    foreach($LoadSubPendidikanFormal['Data'] as $key => $IDatas){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H'.$i, $IDatas['Kode'])
            ->setCellValue('I'.$i, strtoupper($IDatas['Nama']));
        $i++;
    }
    $Stop = $i -1;
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("H3:I".$Stop)->applyFromArray($border_style);
    
}

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
            ->setCellValue('C1', 'KODE PENDIDIKAN FORMAL')
            ->setCellValue('D1', 'KODE SUB PENDIDIKAN FORMAL')
            ->setCellValue('E1', 'TAHUN MULAI')
            ->setCellValue('F1', 'TAHUN SELESAI');

$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:B1")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("C1")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("D1")->applyFromArray($BiruMuda);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("E1:F1")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:F1")->applyFromArray($TextTitle);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:F12")->applyFromArray($border_style);




$objPHPExcel->getActiveSheet(1)->setTitle('FORM UPLOAD PENDIDIKAN FORMAL');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(1);








$Time = time();

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="FORM UPLOAD DATA PENDIDIKAN FORMAL.xls"');
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
