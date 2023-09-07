<?php
require_once dirname(__FILE__) . '/../config/config.php';
function FilterData($data){
    $Filter = array();
    $TglNow = date("Y-m-d");
    if(!empty($data['Usia'])){
       
        return " AND round(DATEDIFF('".$TglNow."',TglLahir) / 365) = '".$data['Usia']."'";
    }else{
        return "";
    }
}



function RekapData($data){
    $Filter = FilterData($data);
    $sql = "SELECT COUNT(a.Id) as Total, a.KodeCabang, a.SpkPengangkatan FROM ims_master_biodata a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp WHERE a.Flag = '1' $Filter  GROUP BY a.KodeCabang ASC";
    $query = $GLOBALS['db']->query($sql);
    $result = array();
    $TotalOC = 0;
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $TotalOC = $TotalOC + $r['Total'];
        $Biodata = json_decode(base64_decode($r['SpkPengangkatan']),true);
        $r['NamaCabang'] = $Biodata['NamaCabang'];
        $r['Total'] = $r['Total'];
        unset($r['SpkPengangkatan']);
        $result['Data'][] = $r;
        
    }
    $result['TotalData'] = $TotalOC;
    $result['JumRow'] = count($result['Data']);
    
    return $result;
}

function DetailData($data){
    unset($data['aksi']);
    $awal = microtime(true);
    $db = $GLOBALS['db'];
    $result = array();
    $row = array(); 
    if(is_array($data)){
        $rData = RekapData($data);
        $JumRow = $rData['JumRow'];
        $result['JumRow'] = $JumRow;
        
        if($JumRow > 0){
            $result['data']=$rData['Data'];
            $akhir = microtime(true);
            $lama = $akhir - $awal;
            $result['Waktu'] = round($lama,3);
            $result['total_data'] = $rData['TotalData'];
            return $result; 
        }else{
            $result['data']=array();
            $akhir = microtime(true);
            $lama = $akhir - $awal;
            $result['Waktu'] = round($lama,3);
            $result['total_data'] = $rData['TotalData'];
            return $result; 
        }
        
    }
    
}

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

require_once dirname(__FILE__) . '/../lib/PHPExcel/Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/fungsi.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("PT ISMA")
							 ->setLastModifiedBy("PT ISMA")
							 ->setTitle("Rekap Data Agama")
							 ->setSubject("Rekap Data Agama")
							 ->setDescription("Rekap Data Agama")
							 ->setKeywords("Rekap Data Agama")
							 ->setCategory("Rekap Data Agama");

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
$styleArraya = array(
   'font'  => array(
        'bold'  => true,
        'size'  => 14,
        'name'  => 'Calibri'
    ));  
// Add some data
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:D2")->applyFromArray($styleArraya);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:D2');
$Titles = $_GET['Usia']." TAHUN";
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REKAP DATA PERCABANG BERDASARKAN USIA '.$Titles)
            ->setCellValue('A3', 'NO')
            ->setCellValue('B3', 'KODE CABANG')
            ->setCellValue('C3', 'NAMA CABANG')
            ->setCellValue('D3', 'JUMLAH OC');
            
$Row = 4;

$Data = DetailData($_GET);
if($Data['JumRow'] > 0){
    $No=1;
    foreach($Data['data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$Row, $No)
            ->setCellValue('B'.$Row, $r['KodeCabang'])
            ->setCellValue('C'.$Row, $r['NamaCabang'])
            ->setCellValue('D'.$Row, intval($r['Total']));
            $No++;
            $Row++;
    }
}
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$Row.':C'.$Row);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$Row, "TOTAL DATA OC")
            ->setCellValue('D'.$Row, intval($Data['total_data']));



$Num = $Row;
$border_style= array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A3:D".$Num)->applyFromArray($border_style);
$sheet = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFF00')
        ));

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A3:D3")->applyFromArray($sheet);

 $styleArray = array(
   'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FFFFFF'),
        'size'  => 11,
        'name'  => 'Calibri'
    ));  
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($Titles);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$Time = time();

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Percabang-Berdasarkan-Usia.xls"');
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
