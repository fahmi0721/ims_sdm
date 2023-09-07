<?php
require_once '../config/config.php';
require_once '../lib/PHPExcel/Classes/PHPExcel.php';
require_once '../inc/Dplk/fungsi.php';
// function FilterString($data){
//     $res = "";
//     $i=0;
//     unset($data['Sync']);
//     unset($data['Status']);
//     foreach($data as $key => $value){
//         if(!empty($value)){
//             if($i == 0){
//                 $res .= "WHERE a.".$key." LIKE '%".$value."%'";
//             }else{
//                 $res .= " AND a.".$key." LIKE '%".$value."%'";
//             }
//             $i++;
//         }
//     }
//     return $res;
// }

// function CekNoKtpTenagaKerja($NoKtp){
//     $koneksi = $GLOBALS['db'];
//     $sql = "SELECT COUNT(Id) as tot FROM ims_tenaga_kerja WHERE NoKtp = :NoKtp";
//     $exec = $koneksi->prepare($sql);
//     $exec->bindParam("NoKtp", $NoKtp,PDO::PARAM_STR);
//     $exec->execute();
//     $r = $exec->fetch(PDO::FETCH_ASSOC);
//     return $r['tot'];
// }

    // Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
							 ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
                             ->setCategory("Laporan");

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'CIF PESERTA')
            ->setCellValue('C1', 'NAMA')
            ->setCellValue('D1', 'NO AKUN DPLK')
            ->setCellValue('E1', 'NIK')
            ->setCellValue('F1', 'Status')
            ->setCellValue('G1', 'UNIT KEEJA');
            
$Row = 2;
$data = array(
    "IdCabang" => $_GET['IdCabang'],
    "CifPeserta" => $_GET['CifPeserta'],
    "NoKtp" => $_GET['NoKtp'],
    "Sync" => $_GET['Sinc'],
    "Status" => $_GET['Status'],
    "Nama" => $_GET['Nama']
);
$res = DetailDataFilter($data);

if(count($res['item']) > 0){
    $No=1;
    $st = array("Tidak Aktif","Aktif");
    foreach($res['item'] as $key => $r){
        if(empty($data['Sync'])){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$Row, $No)
                ->setCellValue('B'.$Row, $r['CifPeserta'])
                ->setCellValue('C'.$Row, $r['Nama'])
                ->setCellValue('D'.$Row, $r['NoAkunDplk'])
                ->setCellValueExplicit('E'.$Row, $r['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('F'.$Row, $st[$r['Status1']])
                ->setCellValue('G'.$Row, $r['UnitKerja']);
                $No++;
                $Row++;
        }else{
            if($data['Sync'] == "ada"){
                if(CekNoKtpTenagaKerjaAktif($res['NoKtp']) > 0){
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$Row, $No)
                        ->setCellValue('B'.$Row, $r['CifPeserta'])
                        ->setCellValue('C'.$Row, $r['Nama'])
                        ->setCellValue('D'.$Row, $r['NoAkunDplk'])
                        ->setCellValueExplicit('E'.$Row, $r['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue('F'.$Row, $st[$r['Status1']])
                        ->setCellValue('G'.$Row, $r['UnitKerja']);
                        $No++;
                        $Row++;
                }
            }else{
                if(CekNoKtpTenagaKerjaAktif($res['NoKtp']) <= 0){
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$Row, $No)
                        ->setCellValue('B'.$Row, $r['CifPeserta'])
                        ->setCellValue('C'.$Row, $r['Nama'])
                        ->setCellValue('D'.$Row, $r['NoAkunDplk'])
                        ->setCellValueExplicit('E'.$Row, $r['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue('F'.$Row, $st[$r['Status1']])
                        ->setCellValue('G'.$Row, $r['UnitKerja']);
                        $No++;
                        $Row++;
                }
            }
        }
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
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:G".$Num)->applyFromArray($border_style);
$sheet = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '0000FF')
        ));

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:G1")->applyFromArray($sheet);

 $styleArray = array(
   'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FFFFFF'),
        'size'  => 11,
        'name'  => 'Calibri'
    ));  
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:G1")->applyFromArray($styleArray);
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Data Master DPLK BRI');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$time = date("YmdHis");
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Data DPLK '.$time.'.xls"');
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
?>