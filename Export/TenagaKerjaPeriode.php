<?php
require_once '../config/config.php';
require_once '../lib/PHPExcel/Classes/PHPExcel.php';
require_once 'FungsiTenagaKerja.php';



$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : "";

if($aksi == "data_tenaga_kerja"){
    unset($_GET['aksi']);
    ExportLinkA($_GET);
}else if($aksi == "Penempatan"){
    unset($_GET['aksi']);
    ExportLinkB($_GET);
}else if($aksi == "Agama"){
    ExportLinkC($_GET);
}else if($aksi == "JenisKelamin"){
    ExportLinkD($_GET);
}else if($aksi == "PendidikanFormal"){
    ExportLinkE($_GET);
}else if($aksi == "PendidikanNonFormal"){
    ExportLinkF($_GET);
}else if($aksi == "Usia"){
    ExportLinkG($_GET);
}else if($aksi == "MasaKerja"){
    ExportLinkH($_GET);
}else{
    echo "<script>alert('Halaman Tidak Ditemukan')</script>";
    echo "<script>window.close();</script>";
}



//  function CekDplk($NoKtp){
//     $koneksi = $GLOBALS['db'];
//     $sql = "SELECT COUNT(Id) as tot FROM ims_dplk WHERE NoKtp = '$NoKtp'";
//     $exec = $koneksi->query($sql);
//     $r = $exec->fetch(PDO::FETCH_ASSOC);
//     return $r['tot'];
// }

//     // Create new PHPExcel object
// $objPHPExcel = new PHPExcel();

// // Set document properties
// $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
// 							 ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
// 							 ->setTitle("Office 2007 XLSX Test Document")
// 							 ->setSubject("Office 2007 XLSX Test Document")
// 							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
// 							 ->setKeywords("office 2007 openxml php")
//                              ->setCategory("Laporan");

// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(4);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(28);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(36);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(39);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(20);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(58);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(34);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(14);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(20);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(13);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(12);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(12);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(19);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(27);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth(14);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('P')->setWidth(23);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('Q')->setWidth(13);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('R')->setWidth(13);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('S')->setWidth(13);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('T')->setWidth(10);

// // Add some data
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A1', 'NO')
//             ->setCellValue('B1', 'NAMA KARYAWAN')
//             ->setCellValue('C1', 'JABATAN')
//             ->setCellValue('D1', 'UNIT KERJA')
//             ->setCellValue('E1', 'PENDIDIKAN')
//             ->setCellValue('F1', 'NO. KTP')
//             ->setCellValue('G1', 'ALAMAT')
//             ->setCellValue('H1', 'TEMPAT LAHIR')
//             ->setCellValue('I1', 'TANGGAL LAHIR')
//             ->setCellValue('J1', 'STATUS PERKAWINAN')
//             ->setCellValue('K1', 'JENIS KELAMIN')
//             ->setCellValue('L1', 'AGAMA')
//             ->setCellValue('M1', 'NO NPWP')
//             ->setCellValue('N1', 'NAMA IBU KANDUNG')
//             ->setCellValue('O1', 'EMAIL')
//             ->setCellValue('P1', 'HANDPHONE')
//             ->setCellValue('Q1', 'NO REKENING BRI')
//             ->setCellValue('R1', 'NO REKENING BNI')
//             ->setCellValue('S1', 'USIA PENSIUN')
//             ->setCellValue('T1', 'TMT ISMA');
// $Row = 2;
// $iSorts = empty($_GET['Jabatan']) ? "" : " AND a.Jabatan = '$_GET[Jabatan]'";
// $iSorts = empty($_GET['Pendidikan']) ? $iSorts : $iSorts." AND a.Pendidikan = '$_GET[Pendidikan]'";
// $iSorts = empty($_GET['IdCabang']) ? $iSorts : $iSorts." AND a.IdCabang = '$_GET[IdCabang]'";
// $iSorts = empty($_GET['Agama']) ? $iSorts : $iSorts." AND a.Agama = '$_GET[Agama]'";
// $sort = "WHERE a.Status = '0' ".$iSorts;
// $StatusDPLK = $_GET['StatusDPLK'];
// $sql = "SELECT a.*, b.NamaCabang FROM ims_tenaga_kerja a INNER JOIN ims_cabang b ON a.IdCabang = b.Id $sort  ORDER BY a.IdCabang ASC, a.Jabatan ASC, a.Nama ASC";
// $query = $db->query($sql);
// $rows = $query->rowCount();
// if($rows > 0){
//     $No=1;
//     while($r = $query->fetch(PDO::FETCH_ASSOC)){
//         if($StatusDPLK != ""){
//             if($StatusDPLK == "0"){
//                 if(CekDplk($r['NoKtp']) <= 0){
//                     $objPHPExcel->setActiveSheetIndex(0)
//                         ->setCellValue('A'.$Row, $No)
//                         ->setCellValue('B'.$Row, $r['Nama'])
//                         ->setCellValue('C'.$Row, $r['Jabatan'])
//                         ->setCellValue('D'.$Row, $r['NamaCabang'])
//                         ->setCellValue('E'.$Row, $r['Pendidikan'])
//                         ->setCellValueExplicit('F'.$Row, $r['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
//                         ->setCellValue('G'.$Row, $r['Alamat'])
//                         ->setCellValue('H'.$Row, $r['TptLahir'])
//                         ->setCellValue('I'.$Row, tgl_indo($r['TglLahir']))
//                         ->setCellValue('J'.$Row, $r['StatusKawin'])
//                         ->setCellValue('K'.$Row, $r['JenisKelamin'])
//                         ->setCellValue('L'.$Row, $r['Agama'])
//                         ->setCellValue('M'.$Row, $r['Npwp'])
//                         ->setCellValue('N'.$Row, $r['NamaIbu'])
//                         ->setCellValue('O'.$Row, $r['Email'])
//                         ->setCellValue('P'.$Row, $r['NoTelp'])
//                         ->setCellValue('Q'.$Row, $r['NoRekBri'])
//                         ->setCellValue('R'.$Row, $r['NoRekBni'])
//                         ->setCellValue('S'.$Row, $r['UsiaPensiun'])
//                         ->setCellValue('T'.$Row, tgl_indo($r['TMT']));
//                         $No++;
//                         $Row++;
//                 }
//             }else{
//                 if(CekDplk($r['NoKtp']) > 0){
//                     $objPHPExcel->setActiveSheetIndex(0)
//                         ->setCellValue('A'.$Row, $No)
//                         ->setCellValue('B'.$Row, $r['Nama'])
//                         ->setCellValue('C'.$Row, $r['Jabatan'])
//                         ->setCellValue('D'.$Row, $r['NamaCabang'])
//                         ->setCellValue('E'.$Row, $r['Pendidikan'])
//                         ->setCellValueExplicit('F'.$Row, $r['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
//                         ->setCellValue('G'.$Row, $r['Alamat'])
//                         ->setCellValue('H'.$Row, $r['TptLahir'])
//                         ->setCellValue('I'.$Row, tgl_indo($r['TglLahir']))
//                         ->setCellValue('J'.$Row, $r['StatusKawin'])
//                         ->setCellValue('K'.$Row, $r['JenisKelamin'])
//                         ->setCellValue('L'.$Row, $r['Agama'])
//                         ->setCellValue('M'.$Row, $r['Npwp'])
//                         ->setCellValue('N'.$Row, $r['NamaIbu'])
//                         ->setCellValue('O'.$Row, $r['Email'])
//                         ->setCellValue('P'.$Row, $r['NoTelp'])
//                         ->setCellValue('Q'.$Row, $r['NoRekBri'])
//                         ->setCellValue('R'.$Row, $r['NoRekBni'])
//                         ->setCellValue('S'.$Row, $r['UsiaPensiun'])
//                         ->setCellValue('T'.$Row, tgl_indo($r['TMT']));
//                         $No++;
//                         $Row++;
//                 }
//             }
//         }else{
//             $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A'.$Row, $No)
//             ->setCellValue('B'.$Row, $r['Nama'])
//             ->setCellValue('C'.$Row, $r['Jabatan'])
//             ->setCellValue('D'.$Row, $r['NamaCabang'])
//             ->setCellValue('E'.$Row, $r['Pendidikan'])
//             ->setCellValueExplicit('F'.$Row, $r['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
//             ->setCellValue('G'.$Row, $r['Alamat'])
//             ->setCellValue('H'.$Row, $r['TptLahir'])
//             ->setCellValue('I'.$Row, tgl_indo($r['TglLahir']))
//             ->setCellValue('J'.$Row, $r['StatusKawin'])
//             ->setCellValue('K'.$Row, $r['JenisKelamin'])
//             ->setCellValue('L'.$Row, $r['Agama'])
//             ->setCellValue('M'.$Row, $r['Npwp'])
//             ->setCellValue('N'.$Row, $r['NamaIbu'])
//             ->setCellValue('O'.$Row, $r['Email'])
//             ->setCellValue('P'.$Row, $r['NoTelp'])
//             ->setCellValue('Q'.$Row, $r['NoRekBri'])
//             ->setCellValue('R'.$Row, $r['NoRekBni'])
//             ->setCellValue('S'.$Row, $r['UsiaPensiun'])
//             ->setCellValue('T'.$Row, tgl_indo($r['TMT']));
//             $No++;
//             $Row++;
//         }
//     }
// }
// $Num = $Row-1;
// $border_style= array(
//         'borders' => array(
//             'allborders' => array(
//                 'style' => PHPExcel_Style_Border::BORDER_THIN
//             )
//         )
//     );
// $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:T".$Num)->applyFromArray($border_style);
// $sheet = array(
//         'fill' => array(
//             'type' => PHPExcel_Style_Fill::FILL_SOLID,
//             'color' => array('rgb' => '0000FF')
//         ));

// $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:T1")->applyFromArray($sheet);

//  $styleArray = array(
//    'font'  => array(
//         'bold'  => true,
//         'color' => array('rgb' => 'FFFFFF'),
//         'size'  => 11,
//         'name'  => 'Calibri'
//     ));  
// $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:T1")->applyFromArray($styleArray);
// // Rename worksheet
// $objPHPExcel->getActiveSheet()->setTitle('Data Tenaga Kerja');


// // Set active sheet index to the first sheet, so Excel opens this as the first sheet
// $objPHPExcel->setActiveSheetIndex(0);


// // Redirect output to a client’s web browser (Excel5)
// header('Content-Type: application/vnd.ms-excel');
// header('Content-Disposition: attachment;filename="DataTenagaKejaIsma.xls"');
// header('Cache-Control: max-age=0');
// // If you're serving to IE 9, then the following may be needed
// header('Cache-Control: max-age=1');

// // If you're serving to IE over SSL, then the following may be needed
// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
// header ('Pragma: public'); // HTTP/1.0

// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
// $objWriter->save('php://output');
?>