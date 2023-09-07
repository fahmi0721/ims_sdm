<?php

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


function LoadFileterLnikAA($datas){
    $search = array();
    foreach($datas as $key => $data){
        if(!empty($data)){
            $search[] = $key." = '".$data."'";
        }
    }
    return empty($search) ? "" : "AND ".implode(" AND ",$search);
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
    $Fileter = LoadFileterLnikAA($data);
    $sql = "SELECT Biodata, Rekening, BpjsKes, Dplk, BpjsTk, PendidikanFormal, PendidikanNonFormal, UkuranBaju, SpkPengangkatan, Flag FROM ims_master_biodata WHERE (SpkKeluar IS NULL OR SpkKeluar = '') $Fileter";
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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

function LoadDataPegawaiLinkC($data){
    $db = $GLOBALS['db'];
    $datas['b.Agama'] = $data['Agama'];
    $datas['a.KodeCabang'] = $data['KodeCabang'];
    $Fileter = LoadFileterLnikA($datas);
    $sql = "SELECT a.Biodata, a.Rekening, a.BpjsKes, a.Dplk, a.BpjsTk, a.PendidikanFormal, a.PendidikanNonFormal, a.UkuranBaju, a.SpkPengangkatan, a.Flag FROM ims_master_biodata a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp $Fileter GROUP BY a.NoKtp";
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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

function LoadDataPegawaiLinkD($data){
    $db = $GLOBALS['db'];
    $datas['b.JenisKelamin'] = $data['JenisKelamin'];
    $datas['a.KodeCabang'] = $data['KodeCabang'];
    $Fileter = LoadFileterLnikA($datas);
    $sql = "SELECT a.Biodata, a.Rekening, a.BpjsKes, a.Dplk, a.BpjsTk, a.PendidikanFormal, a.PendidikanNonFormal, a.UkuranBaju, a.SpkPengangkatan, a.Flag FROM ims_master_biodata a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp $Fileter GROUP BY a.NoKtp";
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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

function LoadDataPegawaiLinkE($data){
    $db = $GLOBALS['db'];
    $datas['b.KodeMaster'] = $data['KodeMaster'];
    $datas['b.KodeSubMaster'] = $data['KodeSubMaster'];
    $datas['a.KodeCabang'] = $data['KodeCabang'];
    $Fileter = LoadFileterLnikA($datas);
    $sql = "SELECT a.Biodata, a.Rekening, a.BpjsKes, a.Dplk, a.BpjsTk, a.PendidikanFormal, a.PendidikanNonFormal, a.UkuranBaju, a.SpkPengangkatan, a.Flag, c.Nama as NamaS, d.Nama as NamaSS, b.TahunSelesai, b.TahunMulai FROM ims_master_biodata a INNER JOIN ims_pendidikan_formal b ON a.NoKtp = b.NoKtp INNER JOIN ims_master_pendidikan_formal c ON b.KodeMaster = c.Kode LEFT JOIN ims_master_sub_pendidikan_formal d ON b.KodeSubMaster = d.Kode $Fileter GROUP BY a.NoKtp";
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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
        $res['PendidikanFormal'] = array();
        $res['PendidikanFormal']['NamaPendidikan'] = $res['NamaS'];
        $res['PendidikanFormal']['NamaJurusan'] = $res['NamaSS'];
        $res['PendidikanFormal']['Periode'] = $res['TahunMulai']." s/d ".$res['TahunSelesai'];
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


function LoadDataPegawaiLinkF($data){
    $db = $GLOBALS['db'];
    $datas['b.KodeMaster'] = $data['KodeMaster'];
    $datas['a.KodeCabang'] = $data['KodeCabang'];
    $Fileter = LoadFileterLnikA($datas);
    $sql = "SELECT a.Biodata, a.Rekening, a.BpjsKes, a.Dplk, a.BpjsTk, a.PendidikanFormal, a.PendidikanNonFormal, a.UkuranBaju, a.SpkPengangkatan, a.Flag, c.Nama as NamaS, b.Dari, b.Sampai FROM ims_master_biodata a INNER JOIN ims_pendidikan_nonformal b ON a.NoKtp = b.NoKtp INNER JOIN ims_master_pendidikan_nonformal c ON b.KodeMaster = c.Kode  $Fileter GROUP BY a.NoKtp";
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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
        $res['PendidikanNonFormal'] = array();
        $res['PendidikanNonFormal']['NamaPendidikan'] = !empty($res['NamaS']) ? $res['NamaS'] : "";
        $res['PendidikanNonFormal']['Periode'] = !empty($res['Dari']) ? tgl_indo($res['Dari'])." s/d ".tgl_indo($res['Sampai']) : "";
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

function LoadDataPegawaiLinkG($data){
    $db = $GLOBALS['db'];
    $TglNow = date("Y-m-d");
    $Fileter = empty($data['Usia']) ? "" : " AND round(DATEDIFF('".$TglNow."',b.TglLahir) / 365) = ".$data['Usia'];
    $Fileter .= empty($data['KodeCabang']) ? "" : " AND a.KodeCabang = '".$data['KodeCabang']."'";
    $sql = "SELECT a.Biodata, a.Rekening, a.BpjsKes, a.Dplk, a.BpjsTk, a.PendidikanFormal, a.PendidikanNonFormal, a.UkuranBaju, a.SpkPengangkatan, a.Flag FROM ims_master_biodata a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp   $Fileter GROUP BY a.NoKtp ORDER BY b.Nama";
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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
        $res['PendidikanNonFormal'] = array();
        $res['PendidikanNonFormal']['NamaPendidikan'] = !empty($res['NamaS']) ? $res['NamaS'] : "";
        $res['PendidikanNonFormal']['Periode'] = !empty($res['Dari']) ? tgl_indo($res['Dari'])." s/d ".tgl_indo($res['Sampai']) : "";
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

function LoadDataPegawaiLinkH($data){
    $db = $GLOBALS['db'];
    $TglNow = date("Y-m-d");
    if($data['MasaKerja'] == "0"){
        $Fileter = " AND round(DATEDIFF('".$TglNow."',b.Tmt) / 365) = 0";
    }elseif($data['MasaKerja'] == ""){
        $Fileter = "";
    }else{
        $Fileter = " AND round(DATEDIFF('".$TglNow."',b.Tmt) / 365) = ".$data['MasaKerja'];
    }
    
    $Fileter .= empty($data['KodeCabang']) ? "" : " AND a.KodeCabang = '".$data['KodeCabang']."'";
    $sql = "SELECT a.Biodata, a.Rekening, a.BpjsKes, a.Dplk, a.BpjsTk, a.PendidikanFormal, a.PendidikanNonFormal, a.UkuranBaju, a.SpkPengangkatan, a.Flag FROM ims_master_biodata a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp   $Fileter GROUP BY a.NoKtp ORDER BY b.Nama";
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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
        $res['PendidikanNonFormal'] = array();
        $res['PendidikanNonFormal']['NamaPendidikan'] = !empty($res['NamaS']) ? $res['NamaS'] : "";
        $res['PendidikanNonFormal']['Periode'] = !empty($res['Dari']) ? tgl_indo($res['Dari'])." s/d ".tgl_indo($res['Sampai']) : "";
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

function LoadDataPegawaiLinkI($data){
    $db = $GLOBALS['db'];
    $TglNow = date("Y-m-d");
    if($data['Periode'] == ""){
        $Fileter = "";
    }else{
        $Fileter = " WHERE DATE_FORMAT(a.Periode, '%Y-%m') = '".$data['Periode']."' ";
    }
    
    $Fileter .= empty($data['KodeCabang']) ? "" : " AND a.KodeCabang = '".$data['KodeCabang']."'";
    $sql = "SELECT a.Biodata, a.Rekening, a.BpjsKes, a.Dplk, a.BpjsTk, a.PendidikanFormal, a.PendidikanNonFormal, a.UkuranBaju, a.SpkPengangkatan, a.Flag FROM ims_master_biodata_bulan a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp  $Fileter GROUP BY a.NoKtp ORDER BY b.Nama";
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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
        $res['PendidikanNonFormal'] = array();
        $res['PendidikanNonFormal']['NamaPendidikan'] = !empty($res['NamaS']) ? $res['NamaS'] : "";
        $res['PendidikanNonFormal']['Periode'] = !empty($res['Dari']) ? tgl_indo($res['Dari'])." s/d ".tgl_indo($res['Sampai']) : "";
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

function LoadDataPegawaiLinkJ($data){
    $db = $GLOBALS['db'];
    unset($data['aksi']);
    $Fileter = LoadFileterLnikAA($data);
    $sql = "SELECT Biodata, Rekening, BpjsKes, Dplk, BpjsTk, PendidikanFormal, PendidikanNonFormal, UkuranBaju, SpkPengangkatan, Flag,SpkKeluar FROM ims_master_biodata WHERE SpkKeluar != '' $Fileter";
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
        $SpkKeluar = json_decode(base64_decode($res['SpkKeluar']),true);//json_decode(base64_decode($res['SpkKeluar']), true);
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
        $res['Biodata']['Agama'] = $res['Biodata']['Agama'];
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
        /**  SpkKeluar */
        $res['SpkKeluar'] = $SpkKeluar;
        $res['SpkKeluar']['Tmt'] =!empty($res['SpkKeluar']['Tmt']) ? tgl_indo($res['SpkKeluar']['Tmt']) : "";
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


function ExportLinkA($data){
    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'JENJANG PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    
    $iData = LoadDataPegawaiLinkA($data);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Data Tenaga Kerja');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA TENAGA KERJA');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();
    // ob_clean(); //Emptying the cache
    // ob_end_clean()

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-RekapDataTenagaKerja.xls"');
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
}

function ExportLinkB($data){

    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'JENJANG PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    $Id = $data['Id'];
    $Filter = json_decode(base64_decode($Id), true);
    unset($Filter['From']);
    $iData = LoadDataPegawaiLinkA($Filter);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA TENAGA KERJA');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Tenaga-Kerja-Berdasarkan-Penempatan.xls"');
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
}

function ExportLinkC($data){

    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'JENJANG PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    $Id = $data['Id'];
    $Filter = json_decode(base64_decode($Id), true);
    unset($Filter['From']);
    $iData = LoadDataPegawaiLinkC($Filter);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA TENAGA KERJA');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Tenaga-Kerja-Berdasarkan-Agama.xls"');
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
}

function ExportLinkD($data){

    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'JENJANG PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    $Id = $data['Id'];
    $Filter = json_decode(base64_decode($Id), true);
    unset($Filter['From']);
    $iData = LoadDataPegawaiLinkD($Filter);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA TENAGA KERJA');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Tenaga-Kerja-Berdasarkan-Agama.xls"');
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
}

function ExportLinkE($data){

    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'NAMA PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    $Id = $data['Id'];
    $Filter = json_decode(base64_decode($Id), true);
    unset($Filter['From']);
    $iData = LoadDataPegawaiLinkE($Filter);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA TENAGA KERJA');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Tenaga-Kerja-Berdasarkan-Pendidikan-Formal.xls"');
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
}

function ExportLinkF($data){

    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'NAMA PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    $Id = $data['Id'];
    $Filter = json_decode(base64_decode($Id), true);
    unset($Filter['From']);
    $iData = LoadDataPegawaiLinkF($Filter);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA TENAGA KERJA');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Tenaga-Kerja-Berdasarkan-Sertifikasi.xls"');
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
}

function ExportLinkG($data){

    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'NAMA PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    $Id = $data['Id'];
    $Filter = json_decode(base64_decode($Id), true);
    unset($Filter['From']);
    
    $iData = LoadDataPegawaiLinkG($Filter);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA TK USIA '.$Filter['Usia']." TAHUN");


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Tenaga-Kerja-Berdasarkan-Usia.xls"');
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
}

function ExportLinkH($data){

    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'NAMA PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    $Id = $data['Id'];
    $Filter = json_decode(base64_decode($Id), true);
    unset($Filter['From']);
    
    $iData = LoadDataPegawaiLinkH($Filter);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA MASA KERJA '.$Filter['MasaKerja']." TAHUN");


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Tenaga-Kerja-Berdasarkan-Masa-Kerja.xls"');
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
}

function ExportLinkI($data){

    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AI2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AI2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'NAMA PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI');
    
    
    $Id = $data['Id'];
    $Filter = json_decode(base64_decode($Id), true);
    unset($Filter['From']);
    
    $iData = LoadDataPegawaiLinkI($Filter);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AI'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('PERIODE '.getBulan(substr($Filter['Periode'],-2))." ".substr($Filter['Periode'],0,4));


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-Rekap-Data-Tenaga-Kerja-Berdasarkan-Periode.xls"');
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
}

function ExportLinkJ($data){
    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("PT INTAN SEJAHTERA UTAMA")
                ->setLastModifiedBy("PT INTAN SEJAHTERA UTAMA")
                ->setTitle("Data Karyawan")
                ->setSubject("Data Karyawan")
                ->setDescription("Data Karyawan")
                ->setKeywords("Data Karyawan")
                ->setCategory("Data Karyawan");
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    /** SET MASTER STYLE */
    $alignHorizontalCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    $StyleTitleFont = array(
        'font' => array(
            'bold' => true,
            'size'  => 11,
            'name'  => 'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleBorder = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );

    /** BACKROUND COLOR */
    $bgBlue = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '1261A0')
        )
    );

    $bgOrange = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FD6A02')
        )
    );
    $bgRed = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
    
    /** SET AUTO FIT */
    $Celling = LoadCell(34);
    foreach($Celling as $key => $Cels){
        $sheet->getColumnDimension($Cels)->setAutoSize(true);
    }

    /** BIODATA */
    $sheet->setCellValue('A1', 'NO')
        ->setCellValue('B1', 'NIK')
        ->setCellValue('C1', 'NO KTP')
        ->setCellValue('D1', 'NAMA')
        ->setCellValue('E1', 'TEMPAT LAHIR')
        ->setCellValue('F1', 'TANGGAL LAHIR')
        ->setCellValue('G1', 'STATUS KAWIN')
        ->setCellValue('H1', 'JK')
        ->setCellValue('I1', 'AGAMA')
        ->setCellValue('J1', 'NPWP')
        ->setCellValue('K1', 'GOL DARAH')
        ->setCellValue('L1', 'NO HP')
        ->setCellValue('M1', 'TMT')
        ->setCellValue('N1', 'ALAMAT');
    /** MARGE CELL */
    $Celling = LoadCell(14);
    foreach($Celling as $key => $Cels){
        $sheet->mergeCells($Cels.'1:'.$Cels.'2');
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($alignHorizontalCenter);
        $sheet->getStyle($Cels.'1:'.$Cels.'2')->applyFromArray($bgBlue);
    }
    $sheet->mergeCells('AJ1:AJ2');
    $sheet->getStyle('O1:AE2')->applyFromArray($bgOrange);
    $sheet->getStyle('AF1:AJ2')->applyFromArray($bgRed);
    $sheet->mergeCells('S1:S2');
    $sheet->getStyle('S1:S2')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('O1:P1');
    $sheet->getStyle('O1:P1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Q1:R1');
    $sheet->getStyle('Q1:R1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('T1:U1');
    $sheet->getStyle('T1:U1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('V1:X1');
    $sheet->getStyle('V1:X1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('Y1:Z1');
    $sheet->getStyle('Y1:Z1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AA1:AE1');
    $sheet->getStyle('AA1:AE1')->applyFromArray($StyleTitleFont);
    $sheet->mergeCells('AF1:AI1');
    $sheet->getStyle('AF1:AI1')->applyFromArray($StyleTitleFont);
    $sheet->getStyle('A1:AJ2')->applyFromArray($StyleTitleFont);

    /**
     * BORDER
     */
    
    /** REKENING */
    $sheet->setCellValue('O1', 'REKENING')
        ->setCellValue('O2', 'NAMA BANK')
        ->setCellValue('P2', 'NO REKENING');
    /** BPJS KES */
    $sheet->setCellValue('Q1', 'BPJS KESEHATAN')
        ->setCellValue('Q2', 'NO JKN')
        ->setCellValue('R2', 'STATUS PESERTA')
        ->setCellValue('S1', 'NO BPJS TK');
    /** DPLK */
    $sheet->setCellValue('T1', 'DPLK')
        ->setCellValue('T2', 'NO CIF')
        ->setCellValue('U2', 'NO ACCOUNT');
    /** PENDIDIKAN FORMAL */
    $sheet->setCellValue('V1', 'PENDIDIKAN FORMAL')
        ->setCellValue('V2', 'JENJANG PENDIDIKAN')
        ->setCellValue('W2', 'JURUSAN')
        ->setCellValue('X2', 'PERIODE');
    /** PENDIDIKAN NON FORMAL */
    $sheet->setCellValue('Y1', 'PENDIDIKAN NON FORMAL')
        ->setCellValue('Y2', 'NAMA PENDIDIKAN')
        ->setCellValue('Z2', 'PERIODE');
    /** PERLENGKAPAN KERJA */
    $sheet->setCellValue('AA1', 'UKURAN PERLENGKAPAN KERJA')
        ->setCellValue('AA2', 'BAJU')
        ->setCellValue('AB2', 'CELANA')
        ->setCellValue('AC2', 'SEPATU')
        ->setCellValue('AD2', 'TOPI')
        ->setCellValue('AE2', 'PED');
    /** PENEMPATAN */
    $sheet->setCellValue('AF1', 'PENEMPATAN')
        ->setCellValue('AF2', 'UNIT KERJA')
        ->setCellValue('AG2', 'DIVISI')
        ->setCellValue('AH2', 'SUB DIVISI')
        ->setCellValue('AI2', 'SEKSI')
        ->setCellValue('AJ1', 'TMT KELUAR');
    
    
    
    $iData = LoadDataPegawaiLinkJ($data);
    $Awal = 3;
    $No = 1;
    foreach($iData as $key => $r){
        /** BIODATA */
        $Biodata = $r['Biodata'];
        $sheet->setCellValue('A'.$Awal, $No)
            ->setCellValueExplicit('B'.$Awal, $Biodata['Nik'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('C'.$Awal, $Biodata['NoKtp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('D'.$Awal, $Biodata['Nama'])
            ->setCellValue('E'.$Awal, $Biodata['TptLahir'])
            ->setCellValue('F'.$Awal, $Biodata['TglLahir'])
            ->setCellValue('G'.$Awal, $Biodata['StatusKawin'])
            ->setCellValue('H'.$Awal, $Biodata['JenisKelamin'])
            ->setCellValue('I'.$Awal, $Biodata['Agama'])
            ->setCellValueExplicit('J'.$Awal, $Biodata['Npwp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('K'.$Awal, $Biodata['GolDarah'])
            ->setCellValueExplicit('L'.$Awal, $Biodata['NoHp'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('M'.$Awal, $Biodata['Tmt'])
            ->setCellValue('N'.$Awal, $Biodata['Alamat']);

        /** Rekening */
        $Rekening = $r['Rekening'];
        $sheet->setCellValue('O'.$Awal, $Rekening['NamaBank'])
            ->setCellValueExplicit('P'.$Awal, $Rekening['NoRek'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** BPJS KES */
        $BpjsKes = $r['BpjsKes'];
        $BpjsTk = $r['BpjsTk'];
        $sheet->setCellValue('Q'.$Awal, $BpjsKes['NoJkn'])
            ->setCellValue('R'.$Awal, $BpjsKes['StatusKepesertaan'])
            ->setCellValueExplicit('S'.$Awal, $BpjsTk['NoKpj'],PHPExcel_Cell_DataType::TYPE_STRING);
        /** DPLK */
        $Dplk = $r['Dplk'];
        $sheet->setCellValueExplicit('T'.$Awal, $Dplk['Cif'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('U'.$Awal, $Dplk['NoAccount'],PHPExcel_Cell_DataType::TYPE_STRING);

        /** PENDIDIKAN FORMAL */
        $PendidikanFormal = $r['PendidikanFormal'];
        $sheet->setCellValue('V'.$Awal, $PendidikanFormal['NamaPendidikan'])
            ->setCellValue('W'.$Awal, $PendidikanFormal['NamaJurusan'])
            ->setCellValue('X'.$Awal, $PendidikanFormal['Periode']);

        /** PENDIDIKAN NON FORMAL */
        $PendidikanNonFormal = $r['PendidikanNonFormal'];
        $sheet->setCellValue('Y'.$Awal, $PendidikanNonFormal['NamaPendidikan'])
            ->setCellValue('Z'.$Awal, $PendidikanNonFormal['Periode']);

        /** BAJU */
        $Baju = $r['UkuranBaju'];
        $sheet->setCellValue('AA'.$Awal, $Baju['Baju'])
            ->setCellValue('AB'.$Awal, $Baju['Celana'])
            ->setCellValue('AC'.$Awal, $Baju['Sepatu'])
            ->setCellValue('AD'.$Awal, $Baju['Topi'])
            ->setCellValue('AE'.$Awal, $Baju['Ped']);

        /** PENEMPATAN */
        $Penempatan = $r['Penempatan'];
        $SpkKeluar = $r['SpkKeluar'];
        $sheet->setCellValue('AF'.$Awal, $Penempatan['NamaCabang'])
            ->setCellValue('AG'.$Awal, $Penempatan['NamaDivisi'])
            ->setCellValue('AH'.$Awal, $Penempatan['NamaSubDivisi'])
            ->setCellValue('AI'.$Awal, $Penempatan['NamaSeksi'])
            ->setCellValue('AJ'.$Awal, $SpkKeluar['Tmt']);
        $Awal++;
        $No++;
    }
    $BrderAwal = $Awal - 1;
    $sheet->getStyle('A1:AJ'.$BrderAwal)->applyFromArray($styleBorder);
    
    // // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Data Tenaga Kerja');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('REKAP DATA TENAGA KERJA KELUAR');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $Time = time();

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$Time.'-RekapDataTenagaKerjaKeluar.xls"');
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
}


?>