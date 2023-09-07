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
							 ->setTitle("Master Data")
							 ->setSubject("Master Data")
							 ->setDescription("Master Seksi")
							 ->setKeywords("Master Seksi")
							 ->setCategory("Master Seksi");


$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'KODE')
            ->setCellValue('C1', 'NAMA SEKSI / JABATAN')
            ->setCellValue('D1', 'STATUS');
            
$Row = 2;
$Data = LoadDataMasterSeksi();
if($Data['Row'] > 0){
    $No=1;
    foreach($Data['Data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$Row, $No)
            ->setCellValue('B'.$Row, $r['Kode'])
            ->setCellValue('C'.$Row, $r['NamaSeksi'])
            ->setCellValue('D'.$Row, $r['Flag']);
            $No++;
            $Row++;
    }
}



$Num = $Row-1;
$border_style= array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:D".$Num)->applyFromArray($border_style);
$sheet = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFF00')
        ));

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:D1")->applyFromArray($sheet);

 $styleArray = array(
   'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FFFFFF'),
        'size'  => 11,
        'name'  => 'Calibri'
    ));  
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Master Seksi');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$Time = time();

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$Time.'-Master Sub Seksi.xls"');
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
