<?php
    function LoadSertifikatName($NoKtp){
        $sql = "SELECT b.Nama FROM ims_pendidikan_nonformal a INNER JOIN ims_master_pendidikan_nonformal b ON a.KodeMaster = b.Kode WHERE NoKtp = '7314041711880003'";
        $query = $GLOBALS['db']->query($sql);
        if($query->rowCount() <= 0){
            return "";
        }else{
            $rs =array();
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $rs[] = $r['Nama'];
            }
            return $rs;
        }
    }

    function Biodata($NoKtp){
        $NoKtp = base64_decode($NoKtp);
        $sql = "SELECT Biodata,PendidikanFormal, SpkPengangkatan, BpjsTk, BpjsKes, Dplk, UkuranBaju FROM ims_master_biodata WHERE NoKtp = '$NoKtp'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        $Kawin = array("Belum Menikah","Menikah","Janda/Duda");
        $res['Biodata'] = json_decode(base64_decode($r['Biodata']),true);
        if(!empty($res['Biodata']['Foto']) && file_exists("../../img/FotoTenagaKerja/".$res['Biodata']['Foto'])){
            $res['Biodata']['Foto'] = "img/FotoTenagaKerja/".$res['Biodata']['Foto'];
        }else{
            $res['Biodata']['Foto'] = "img/".$res['Biodata']['JenisKelamin']."-avatar.jpg";
        }
        $res['Biodata']['JenisKelamin'] = $res['Biodata']['JenisKelamin']  == "L" ? "LAKI-LAKI" : "PEREMPUAN";
        $res['Biodata']['StatusKawin'] = $Kawin[$res['Biodata']['StatusKawin']];
        
        $res['Pendidikan'] = json_decode(base64_decode($r['PendidikanFormal']),true);
        $res['Pendidikan']['NamaPendidikan'] = isset($res['Pendidikan']['NamaPendidikan'])  ? $res['Pendidikan']['NamaPendidikan'] : "";
        $res['Pendidikan']['NamaJurusan'] = isset($res['Pendidikan']['NamaJurusan'])  ? $res['Pendidikan']['NamaJurusan'] : "";
        $res['Jabatan'] = json_decode(base64_decode($r['SpkPengangkatan']),true);
        $res['BpjsTk'] = json_decode(base64_decode($r['BpjsTk']),true);
        $res['BpjsTk']['NoKpj'] = isset($res['BpjsTk']['NoKpj']) ? $res['BpjsTk']['NoKpj'] : "";
        $res['BpjsKes'] = json_decode(base64_decode($r['BpjsKes']),true);
        $res['BpjsKes']['NoJkn'] = isset($res['BpjsKes']['NoJkn']) ? $res['BpjsKes']['NoJkn'] : "";
        $res['Dplk'] = json_decode(base64_decode($r['Dplk']),true);
        $res['Dplk']['NoAccount'] = isset($res['Dplk']['NoAccount']) ? $res['Dplk']['NoAccount'] : "";
        $res['Dplk']['Cif'] = isset($res['Dplk']['Cif']) ? $res['Dplk']['Cif'] : "";
        $res['UkuranBaju'] = json_decode(base64_decode($r['UkuranBaju']),true);
        $res['UkuranBaju']['Baju'] = isset($res['UkuranBaju']['Baju']) ? $res['UkuranBaju']['Baju'] : "";
        $res['UkuranBaju']['Celana'] = isset($res['UkuranBaju']['Celana']) ? $res['UkuranBaju']['Celana'] : "";
        $res['UkuranBaju']['Sepatu'] = isset($res['UkuranBaju']['Sepatu']) ? $res['UkuranBaju']['Sepatu'] : "";
        $res['UkuranBaju']['Topi'] = isset($res['UkuranBaju']['Topi']) ? $res['UkuranBaju']['Topi'] : "";
        $res['UkuranBaju']['Ped'] = isset($res['UkuranBaju']['Ped']) ? $res['UkuranBaju']['Ped'] : "";
        $res['Sertifikasi']= LoadSertifikatName($NoKtp);
        //$res['Sertifikasi'] = !is_array($res['Sertifikasi']) ? "" : $res['Sertifikasi'];
        return $res;
    }
    function PendidikanFormal($NoKtp){
        $NoKtp = base64_decode($NoKtp);
        $sql = "SELECT b.Nama as NamaPendidikan, c.Nama as NamaJurusan, a.TahunMulai, a.TahunSelesai, a.File FROM ims_pendidikan_formal a LEFT JOIN ims_master_pendidikan_formal b ON a.KodeMaster = b.Kode LEFT JOIN ims_master_sub_pendidikan_formal c ON a.KodeSubMaster = c.Kode WHERE a.NoKtp = '$NoKtp' ORDER BY a.TahunMulai ASC";
        $query = $GLOBALS['db']->query($sql);
        $res = array();
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            if(!empty($r['File']) && file_exists("../../File/PendidikanFormal/".$r['File']) ){
                $r['File'] = "<a target='_blank' class='btn btn-success btn-xs' data-toggle='tooltip' title='Lihat Data' href='File/PendidikanFormal/".$r['File']."'><i class='fa fa-eye'></i></a>";
            }else{
                $r['File'] = "";
            }
            $res[] = $r;
        }

        
        return $res;
    }

    function PendidikanNonFormal($NoKtp){
        $NoKtp = base64_decode($NoKtp);
        $sql = "SELECT b.Nama as Sertifikasi, a.Dari, a.Sampai, a.File, a.Keterangan FROM ims_pendidikan_nonformal a LEFT JOIN ims_master_pendidikan_nonformal b ON a.KodeMaster = b.Kode  WHERE a.NoKtp = '$NoKtp' ORDER BY a.Dari ASC";
        $query = $GLOBALS['db']->query($sql);
        $res = array();
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            if(!empty($r['File']) && file_exists("../../File/PendidikanNonFormal/".$r['File']) ){
                $r['File'] = "<a target='_blank' class='btn btn-success btn-xs' data-toggle='tooltip' title='Lihat Data' href='File/PendidikanNonFormal/".$r['File']."'><i class='fa fa-eye'></i></a>";
            }else{
                $r['File'] = "";
            }
            $res[] = $r;
        }

        
        return $res;
    }

    function Keluarga($NoKtp){
        $NoKtp = base64_decode($NoKtp);
        $sql = "SELECT a.*, b.Nama as Pendidikan FROM ims_data_keluarga a INNER JOIN ims_master_pendidikan_formal b ON a.KodeMaster = b.Kode WHERE a.NoKtp = '$NoKtp' ORDER BY Id ASC";
        $query = $GLOBALS['db']->query($sql);
        $res = array();
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            $res[] = $r;
        }
        return $res;
    }

    function RiwayatKerja($NoKtp){
        $NoKtp = base64_decode($NoKtp);
        $sql = "SELECT a.*, b.NamaCabang, c.NamaDivisi, d.NamaSubDivisi, e.NamaSeksi  FROM ims_sk_pengangkatan a LEFT JOIN ims_master_cabang b ON a.KodeCabang = b.Kode LEFT JOIN ims_master_divisi c ON a.KodeDivisi = c.Kode LEFT JOIN ims_master_subdivisi d ON a.KodeSubDivisi = d.Kode LEFT JOIN ims_master_seksi e ON a.KodeSeksi = e.Kode WHERE a.NoKtp = '$NoKtp'";
        $query = $GLOBALS['db']->query($sql);
        $res = array();
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            if(!empty($r['File']) && (file_exists("../../File/SkMutasi/".$r['File']) OR file_exists("../../File/SkPengangkatan/".$r['File']))){
                if(file_exists("../../File/SkMutasi/".$r['File'])){
                    $rFile = "File/SkMutasi/".$r['File'];
                    $r['File'] = "<center><a target='_blank' class='btn btn-success btn-xs' data-toggle='tooltip' title='Lihat Data' href='".$rFile."'><i class='fa fa-eye'></i></a></center>";
                    $res[] = $r;
                }else{
                    $rFile = "File/SkPengangkatan/".$r['File'];
                    $r['File'] = "<center><a target='_blank' class='btn btn-success btn-xs' data-toggle='tooltip' title='Lihat Data' href='".$rFile."'><i class='fa fa-eye'></i></a></center>";
                    $res[] = $r;
                }
            }else{
                $r['File'] = "";
                    $res[] = $r;
            }
        }
        return $res;
    }

    function NomorRekening($NoKtp){
        $NoKtp = base64_decode($NoKtp);
        $sql = "SELECT a.*, b.Nama as NamaBank FROM ims_rekening a LEFT JOIN ims_master_bank b ON a.KodeBank = b.Kode  WHERE a.NoKtp = '$NoKtp'";
        $query = $GLOBALS['db']->query($sql);
        $res = array();
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            if(!empty($r['File']) && file_exists("../../img/FileRekening/".$r['File'])){
                $r['File'] = "<a class='btn btn-xs btn-success' href='img/FileRekening/".$r['File']."'><i class='fa fa-eye'></i></a>";
                $res[] = $r;
            }else{
                $r['File'] = "-";
                $res[] = $r;
            }
        }
        return $res;
    }


    

    

?>