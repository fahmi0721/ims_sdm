<?php
error_reporting(0);
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
							 ->setDescription("Form Data Upload PHK")
							 ->setKeywords("Form Data Upload PHK")
							 ->setCategory("Form Data Upload PHK");

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
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B6")->applyFromArray($Hijau);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B7")->applyFromArray($BiruMuda);
/** SET BORDER B3-C7 */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B3:C7")->applyFromArray($border_style);

/** MARGE CELL */
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:C1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:H2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J2:M2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('O2:Q2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('O8:Q8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('O13:R13');

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', 'PETUNJUK PENGISIAN FORM UPLOAD DATA TENAGA KERJA PT ISMA')
            ->setCellValue('B3', 'FIELD COLOR')
            ->setCellValue('C3', 'KETERANGAN')
            ->setCellValue('C4', 'WAJIB DIISI')
            ->setCellValue('C5', 'WAJIB DIISI & PENGISIAN MENGGUNAKAN KODE DATA')
            ->setCellValue('C6', 'BOLEH DIKOSONGKAN JIKA DATA TIDAK ADA')
            ->setCellValue('C7', 'BOLEH DIKOSONGKAN JIKA DATA TIDAK DIKETAHUI, & PENGISIAN MENGGUNAKAN KODEDATA');

/** SET TITILE Data */
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E2', 'KATEGORI')
            ->setCellValue('E3', 'NO')
            ->setCellValue('F3', 'KODE')
            ->setCellValue('G3', 'NAMA KATEGORI');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:G3")->applyFromArray($Orange);
$RowE = 4;  
$DataKategori['Data'] = array(
    array("Kode" => "0","Nama" => "Pemberhentian"),
    array("Kode" => "1", "Nama" => "Pengunduran Diri"),
    array("Kode" => "2", "Nama" => "Pensiun")
);
$DataKategori['Row'] = count($DataKategori['Data']);
if($DataKategori['Row'] > 0){
    $No=1;
    foreach($DataKategori['Data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$RowE, $No)
            ->setCellValue('F'.$RowE, $r['Kode'])
            ->setCellValue('G'.$RowE, $r['Nama']);
            $No++;
            $RowE++;
    }
}
$BatasRowE = $RowE -1;
/** SET BORDER MASTER CABANG */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:G".$BatasRowE)->applyFromArray($border_style);


// Rename worksheet
$objPHPExcel->getActiveSheet(0)->setTitle('PETUNJUK PENGISIAN');


$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);

/** FORM UPLAOD DATA */
$Cell = Cells();
foreach($Cell as $key => $Cll){
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($Cll)->setWidth(10);
}

/** MARGE CELL */
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:A2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('B1:B2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('C1:C2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('D1:D2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E1:E2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('F1:H1');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('I1:I2');

/** BG COLOR */
$Orange = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'E65B25')));
$BiruTua = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '3A539B')));
$BiruMuda = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '5C97BF')));
$Hijau = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '2ECC71')));

/** SET BG COLOR */
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:C2")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("E1:H2")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("D1:D2")->applyFromArray($Hijau);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("I1:I2")->applyFromArray($Hijau);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("F1:H2")->applyFromArray($Orange);

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'NO KTP')
            ->setCellValue('C1', 'NAMA')
            ->setCellValue('D1', 'NO DOKUMEN')
            ->setCellValue('E1', 'KATEGORI')
            ->setCellValue('F1', 'TANGGAL PHK')
            ->setCellValue('F2', 'TAHUN')
            ->setCellValue('G2', 'BULAN')
            ->setCellValue('H2', 'TANGGAL')
            ->setCellValue('I1', 'KETERANGAN');
           
//$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:V2")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:I2")->applyFromArray($TextTitle);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:I12")->applyFromArray($border_style);




$objPHPExcel->getActiveSheet(1)->setTitle('FORM UPLOAD TENAGA KERJA');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(1);








$Time = time();

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="FORM UPLOAD PHK.xls"');
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
