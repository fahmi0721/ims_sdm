<?php
require_once '../config/config.php';
require_once '../lib/PHPExcel/Classes/PHPExcel.php';

/** LINK A */
function LoadFileterLnikA($datas){
    $search = array();
    foreach($datas as $key => $data){
        if(!empty($data)){
            $search[] = $key." = '".$data."'";
        }
    }
    return empty($search) ? "" : "WHERE ".implode(" AND ",$search);
}

function LoadAgama($Kode){
    $db = $GLOBALS['db'];
    $query = $db->query("SELECT Nama FROM ims_agama WHERE Kode = '$Kode'");
    $res = array();
    $r = $query->fetch(PDO::FETCH_ASSOC);
    return $r['Nama'];
}

function LoadBank(){
    $db = $GLOBALS['db'];
    $query = $db->query("SELECT Kode, Nama FROM ims_master_bank");
    $res = array();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res[$r['Kode']] = $r['Nama'];
    }
    return $res;
}

function LoadDataPegawaiLinkA($data){
    $db = $GLOBALS['db'];
    $Fileter = LoadFileterLnikA($data);
    $sql = "SELECT Biodata, Rekening, BpjsKes, Dplk, BpjsTk, PendidikanFormal, PendidikanNonFormal, UkuranBaju, SpkPengangkatan, Flag FROM ims_master_biodata $Fileter";
    $query = $db->query($sql);
    $result = array();
    $Bank = LoadBank();
    while($res = $query->fetch(PDO::FETCH_ASSOC)){
        $Biodata = json_decode(base64_decode($res['Biodata']), true);
        $SpkPengangkatan = json_decode(base64_decode($res['SpkPengangkatan']), true);
        $Rekening = json_decode(base64_decode($res['Rekening']), true);
        $BpjsKes = json_decode(base64_decode($res['BpjsKes']), true);
        $BpjsTk = json_decode(base64_decode($res['BpjsTk']), true);
        $Dplk = json_decode(base64_decode($res['Dplk']), true);
        $PendidikanFormal = json_decode(base64_decode($res['PendidikanFormal']), true);
        $PendidikanNonFormal = json_decode(base64_decode($res['PendidikanNonFormal']), true);
        $UkuranBaju = json_decode(base64_decode($res['UkuranBaju']), true);
        /** */
        $StatusBpjs = array("Belum Menikah","Sudah Menikah","Janda/Duda");
        $StatusMenikah = array("1" => "Belum Menikah","2" => "Sudah Menikah","3"=>"Janda/Duda");
        $StatusKepesertaan = array("PT ISMA","TNI/POLRI","JAMKESDA/JAMKESMAS/JAMKESTA","NON PNS","PBI APBN/APBD", "PENSIUN");
        $Jk = array("L" => "Laki-Laki","P"=>"Perempuan");
        $Status = array("1" => "Aktif","0","Tidak Aktif");
        
        /** */
        $res['Biodata'] = empty($Biodata) ? "" : $Biodata;
        $res['Biodata']['StatusKawin'] = !empty($res['Biodata']['StatusKawin']) ? $StatusMenikah[$res['Biodata']['StatusKawin']] : "";
        $res['Biodata']['TglLahir'] = tgl_indo($res['Biodata']['TglLahir']);
        $res['Biodata']['Tmt'] = tgl_indo($res['Biodata']['Tmt']);
        $res['Biodata']['JenisKelamin'] = !empty($res['Biodata']['JenisKelamin']) ? $Jk[$res['Biodata']['JenisKelamin']] : "";
        $res['Biodata']['Agama'] = LoadAgama($res['Biodata']['Agama']);
        $res['Biodata']['Flag'] = $Status[$res['Biodata']['Flag']];
        /**  Penempatan */
        $res['Penempatan'] = $SpkPengangkatan;
        $res['Penempatan']['NamaCabang'] = $res['Penempatan']['NamaCabang'];
        $res['Penempatan']['NamaDivisi'] = $res['Penempatan']['NamaDivisi'];
        $res['Penempatan']['NamaSubDivisi'] = $res['Penempatan']['NamaSubDivisi'];
        $res['Penempatan']['NamaSeksi'] = $res['Penempatan']['NamaSeksi'];
        $res['Penempatan']['TanggalMulai'] = $res['Penempatan']['TanggalMulai'];
        /**  Rekening */
        $res['Rekening'] = $Rekening;
        $res['Rekening']['NamaBank'] = !empty($res['Rekening']['KodeBank']) ? $Bank[$res['Rekening']['KodeBank']] : "";
        $res['Rekening']['NoRek'] = !empty($res['Rekening']['NoRek']) ? $res['Rekening']['NoRek'] : "";
        /**  BPJS KES */
        $res['BpjsKes'] = $BpjsKes;
        $res['BpjsKes']['NoJkn'] = !empty($res['BpjsKes']['NoJkn']) ? $res['BpjsKes']['NoJkn'] : "";
        $res['BpjsKes']['StatusKepesertaan'] = !empty($res['BpjsKes']['NoJkn']) ? $StatusKepesertaan[$res['BpjsKes']['StatusKepesertaan']] : "";
        /**  Pendidikan Formal */
        $res['PendidikanFormal'] = $PendidikanFormal;
        $res['PendidikanFormal']['NamaPendidikan'] = !empty($res['PendidikanFormal']['NamaPendidikan']) ? $res['PendidikanFormal']['NamaPendidikan'] : "";
        $res['PendidikanFormal']['NamaJurusan'] = !empty($res['PendidikanFormal']['NamaJurusan']) ? $res['PendidikanFormal']['NamaJurusan'] : "";
        $res['PendidikanFormal']['Periode'] = !empty($res['PendidikanFormal']['TahunMulai']) ? $res['PendidikanFormal']['TahunMulai']." s/d ".$res['PendidikanFormal']['TahunSelesai'] : "";
        $res['PendidikanNonFormal'] = $PendidikanNonFormal;
        $res['PendidikanNonFormal']['NamaPendidikan'] = !empty($res['PendidikanNonFormal']['NamaPendidikan']) ? $res['PendidikanNonFormal']['NamaPendidikan'] : "";
        $res['PendidikanNonFormal']['Periode'] = !empty($res['PendidikanNonFormal']['Dari']) ? tgl_indo($res['PendidikanNonFormal']['Dari'])." s/d ".tgl_indo($res['PendidikanNonFormal']['Sampai']) : "";
        /**  Ukuran Baju */
        $res['UkuranBaju'] = $UkuranBaju;
        $res['UkuranBaju']['Baju'] = !empty($res['UkuranBaju']['Baju']) ? $res['UkuranBaju']['Baju'] : "";
        $res['UkuranBaju']['Celana'] = !empty($res['UkuranBaju']['Celana']) ? $res['UkuranBaju']['Celana'] : "";
        $res['UkuranBaju']['Sepatu'] = !empty($res['UkuranBaju']['Sepatu']) ? $res['UkuranBaju']['Sepatu'] : "";
        $res['UkuranBaju']['Topi'] = !empty($res['UkuranBaju']['Topi']) ? $res['UkuranBaju']['Topi'] : "";
        $res['UkuranBaju']['Ped'] = !empty($res['UkuranBaju']['Ped']) ? $res['UkuranBaju']['Ped'] : "";
        /**  Bpjs TK */
        $res['BpjsTk'] = $BpjsTk;
        $res['BpjsTk']['NoKpj'] = !empty($res['BpjsTk']['NoKpj']) ? $res['BpjsTk']['NoKpj'] : '';
        /**  Dplk */
        $res['Dplk'] = $Dplk;
        $res['Dplk']['Cif'] = !empty($res['Dplk']['Cif']) ? $res['Dplk']['Cif'] : "";
        $res['Dplk']['NoAccount'] = !empty($res['Dplk']['NoAccount']) ? $res['Dplk']['NoAccount'] : "";
        unset($res['SpkPengangkatan']);
        $result[] = $res;
    }
    return $result;
    
}

function LoadCell($Cell){
    $alfabet = range("A","Z");
    $posisi = 0;
    $Cll = array();
    for($i=0; $i < $Cell; $i++){
        if($i < 26){
            $Cll[] = $alfabet[$i];
            
        }else{
            $selisih = $i - count($alfabet);
            $Cll[] = $alfabet[$posisi].$alfabet[$selisih];
            $selisih =count($alfabet);
        }
    }
    return $Cll;
}


$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : "";

if($aksi == "data_tenaga_kerja"){
    unset($_GET['aksi']);
    $iData = LoadDataPegawaiLinkA($_GET);
    echo json_encode($iData);
    

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