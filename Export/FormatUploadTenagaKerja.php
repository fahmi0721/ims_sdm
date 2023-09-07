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
            ->setCellValue('E2', 'MASTER CABANG')
            ->setCellValue('E3', 'NO')
            ->setCellValue('F3', 'KODE')
            ->setCellValue('G3', 'NAMA CABANG')
            ->setCellValue('H3', 'STATUS');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:H3")->applyFromArray($Orange);
$RowE = 4;  
$DataCabang = LoadDataMasterCabang();
if($DataCabang['Row'] > 0){
    $No=1;
    foreach($DataCabang['Data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$RowE, $No)
            ->setCellValue('F'.$RowE, $r['Kode'])
            ->setCellValue('G'.$RowE, $r['NamaCabang'])
            ->setCellValue('H'.$RowE, $r['Flag']);
            $No++;
            $RowE++;
    }
}
$BatasRowE = $RowE -1;
/** SET BORDER MASTER CABANG */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E3:H".$BatasRowE)->applyFromArray($border_style);


/**  BRANCH */
$RowBranchTitle = $RowE +1;
$RowBranchTitleTable = $RowE +2;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$RowBranchTitle, 'MASTER BRANCH')
            ->setCellValue('E'.$RowBranchTitleTable, 'NO')
            ->setCellValue('F'.$RowBranchTitleTable, 'KODE')
            ->setCellValue('G'.$RowBranchTitleTable, 'NAMA BRANCH')
            ->setCellValue('H'.$RowBranchTitleTable, 'STATUS');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E".$RowBranchTitleTable.":H".$RowBranchTitleTable)->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.$RowBranchTitle.':H'.$RowBranchTitle);
$RowBranch = $RowBranchTitleTable + 1;
$DataBranch = LoadDataBranch();
if($DataBranch['Row'] > 0){
    $No=1;
    foreach($DataBranch['Data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$RowBranch, $No)
            ->setCellValue('F'.$RowBranch, $r['Kode'])
            ->setCellValue('G'.$RowBranch, $r['Nama'])
            ->setCellValue('H'.$RowBranch, $r['Flag']);
            $No++;
            $RowBranch++;
    }
}
$BatasRowBranch = $RowBranch -1;
/** SET BORDER MASTER BRANCH */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E".$RowBranchTitleTable.":H".$BatasRowBranch)->applyFromArray($border_style);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.$RowBranchTitle.':H'.$RowBranchTitle);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E".$RowBranchTitle.":H".$RowBranchTitle)->applyFromArray($Orange);


/** SET DATA DIVISI */
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J2', 'MASTER DIVISI')
            ->setCellValue('J3', 'NO')
            ->setCellValue('K3', 'KODE')
            ->setCellValue('L3', 'NAMA DIVISI')
            ->setCellValue('M3', 'STATUS');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("J3:M3")->applyFromArray($Orange);
$RowDivisi = 4;
$DataDivisi = LoadDataMasterDivisi();
if($DataDivisi['Row'] > 0){
    $No=1;
    foreach($DataDivisi['Data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$RowDivisi, $No)
            ->setCellValue('K'.$RowDivisi, $r['Kode'])
            ->setCellValue('L'.$RowDivisi, $r['NamaDivisi'])
            ->setCellValue('M'.$RowDivisi, $r['Flag']);
            $No++;
            $RowDivisi++;
    }
}
$BatasRowDivisi = $RowDivisi -1;
/** SET BORDER MASTER DIVISI */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("J3:M".$BatasRowDivisi)->applyFromArray($border_style);

/** SUB DIVISI */
$RowSubDivisiTitle = $RowDivisi +1;
$RowSubDivisiTitleTable = $RowDivisi +2;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$RowSubDivisiTitle, 'MASTER SUB DIVISI')
            ->setCellValue('J'.$RowSubDivisiTitleTable, 'NO')
            ->setCellValue('K'.$RowSubDivisiTitleTable, 'KODE')
            ->setCellValue('L'.$RowSubDivisiTitleTable, 'NAMA SUB DIVISI')
            ->setCellValue('M'.$RowSubDivisiTitleTable, 'STATUS');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("J".$RowSubDivisiTitleTable.":M".$RowSubDivisiTitleTable)->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.$RowSubDivisiTitle.':M'.$RowSubDivisiTitle);
$RowSubDivisi = $RowSubDivisiTitleTable + 1;
$DataSubDivisi = LoadDataMasterSubDivisi();
if($DataSubDivisi['Row'] > 0){
    $No=1;
    foreach($DataSubDivisi['Data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$RowSubDivisi, $No)
            ->setCellValue('K'.$RowSubDivisi, $r['Kode'])
            ->setCellValue('L'.$RowSubDivisi, $r['NamaSubDivisi'])
            ->setCellValue('M'.$RowSubDivisi, $r['Flag']);
            $No++;
            $RowSubDivisi++;
    }
}
$BatasRowSubDivisi = $RowSubDivisi -1;
/** SET BORDER MASTER SUB DIVISI */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("J".$RowSubDivisiTitleTable.":M".$BatasRowSubDivisi)->applyFromArray($border_style);

/** SEKSI / JABATAN */
$RowSeksiTitle = $RowSubDivisi +1;
$RowSeksiTitleTable = $RowSubDivisi +2;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$RowSeksiTitle, 'MASTER SEKSI/JABATAN')
            ->setCellValue('J'.$RowSeksiTitleTable, 'NO')
            ->setCellValue('K'.$RowSeksiTitleTable, 'KODE')
            ->setCellValue('L'.$RowSeksiTitleTable, 'NAMA SEKSI/JABATAN')
            ->setCellValue('M'.$RowSeksiTitleTable, 'STATUS');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("J".$RowSeksiTitleTable.":M".$RowSeksiTitleTable)->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.$RowSeksiTitle.':M'.$RowSeksiTitle);
$RowSeksi = $RowSeksiTitleTable + 1;
$DataSeksi = LoadDataMasterSeksi();
if($DataSeksi['Row'] > 0){
    $No=1;
    foreach($DataSeksi['Data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$RowSeksi, $No)
            ->setCellValue('K'.$RowSeksi, $r['Kode'])
            ->setCellValue('L'.$RowSeksi, $r['NamaSeksi'])
            ->setCellValue('M'.$RowSeksi, $r['Flag']);
            $No++;
            $RowSeksi++;
    }
}
$BatasRowSeksi = $RowSeksi -1;
/** SET BORDER MASTER SEKSI */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("J".$RowSeksiTitleTable.":M".$BatasRowSeksi)->applyFromArray($border_style);

/** STATUS KWAWIN */
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O2', 'STATUS KAWIN')
            ->setCellValue('O3', 'NO')
            ->setCellValue('O4', '1')
            ->setCellValue('O5', '2')
            ->setCellValue('O6', '3')
            ->setCellValue('P3', 'KODE')
            ->setCellValue('P4', '1')
            ->setCellValue('P5', '2')
            ->setCellValue('P6', '3')
            ->setCellValue('Q3', 'KETERANGAN')
            ->setCellValue('Q4', 'BELUM MENIKAH')
            ->setCellValue('Q5', 'MENIKAH')
            ->setCellValue('Q6', 'JANDA/DUDA');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("O3:Q3")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("O3:Q6")->applyFromArray($border_style);

/** JENIS KELAMIN */
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O8', 'JENIS KELAMIN')
            ->setCellValue('O9', 'KODE')
            ->setCellValue('O10', '1')
            ->setCellValue('O11', '2')
            ->setCellValue('P9', 'KODE')
            ->setCellValue('P10', 'L')
            ->setCellValue('P11', 'P')
            ->setCellValue('Q9', 'KETERANGAN')
            ->setCellValue('Q10', 'LAKI-LAKI')
            ->setCellValue('Q11', 'PEREMPUAN');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("O9:Q9")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("O9:Q11")->applyFromArray($border_style);


/** MASTER AGAMA */
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O13', 'MASTER AGAMA')
            ->setCellValue('O14', 'NO')
            ->setCellValue('P14', 'KODE')
            ->setCellValue('Q14', 'NAMA')
            ->setCellValue('R14', 'STATUS');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("O14:R14")->applyFromArray($Orange);
$RowAgama = 15;
$DataAgama = LoadDataAgama();
if($DataAgama['Row'] > 0){
    $No=1;
    foreach($DataAgama['Data'] as $key => $r){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$RowAgama, $No)
            ->setCellValue('P'.$RowAgama, $r['Kode'])
            ->setCellValue('Q'.$RowAgama, $r['Nama'])
            ->setCellValue('R'.$RowAgama, $r['Flag']);
            $No++;
            $RowAgama++;
    }
}
$BatasRowAgama = $RowAgama -1;
/** SET BORDER MASTER AGAMA */
$objPHPExcel->setActiveSheetIndex(0)->getStyle("O14:R".$BatasRowAgama)->applyFromArray($border_style);
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
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E1:G1');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('H1:H2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('I1:I2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('J1:J2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('K1:K2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('L1:L2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('M1:M2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('N1:P1');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('Q1:Q2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('R1:R2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('S1:S2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('T1:T2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('U1:U2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('V1:V2');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('W1:W2');

/** BG COLOR */
$Orange = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'E65B25')));
$BiruTua = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '3A539B')));
$BiruMuda = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '5C97BF')));
$Hijau = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '2ECC71')));

/** SET BG COLOR */
$objPHPExcel->setActiveSheetIndex(1)->getStyle("N1:P2")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:G2")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("H1:J2")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("S1:T2")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("V1:V2")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("W1:W2")->applyFromArray($BiruTua);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("K1:M2")->applyFromArray($Hijau);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("Q1:R2")->applyFromArray($Hijau);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("U1:U2")->applyFromArray($BiruMuda);

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'NO KTP')
            ->setCellValue('C1', 'NAMA')
            ->setCellValue('D1', 'TEMPAT LAHIR')
            ->setCellValue('E1', 'TGL LAHIR')
            ->setCellValue('E2', 'TAHUN')
            ->setCellValue('F2', 'BULAN')
            ->setCellValue('G2', 'TANGGAL')
            ->setCellValue('H1', 'STATUS KAWIN')
            ->setCellValue('I1', 'JENIS KELAMIN')
            ->setCellValue('J1', 'AGAMA')
            ->setCellValue('K1', 'NPWP')
            ->setCellValue('L1', 'GOL DARAH')
            ->setCellValue('M1', 'NO HP')
            ->setCellValue('N1', 'TMT')
            ->setCellValue('N2', 'TAHUN')
            ->setCellValue('O2', 'BULAN')
            ->setCellValue('P2', 'TANGGAL')
            ->setCellValue('Q1', 'ALAMAT')
            ->setCellValue('R1', 'NOMOR KONTRAK / SK')
            ->setCellValue('S1', 'KODE CABANG / UNIT KERJA')
            ->setCellValue('T1', 'KODE DIVISI')
            ->setCellValue('U1', 'KODE SUB DIVISI')
            ->setCellValue('V1', 'KODE SEKSI / JABATAN')
            ->setCellValue('W1', 'KODE BRANCH');
//$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:V2")->applyFromArray($Orange);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:W2")->applyFromArray($TextTitle);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:W12")->applyFromArray($border_style);




$objPHPExcel->getActiveSheet(1)->setTitle('FORM UPLOAD TENAGA KERJA');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(1);








$Time = time();

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="FORM UPLOAD TENAGA KERJA.xls"');
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
