<?php


    // function getNamaCabang($Kode){
    //     $sql = "SELECT NamaCabang FROM ims_master_cabang WHERE Kode = '$Kode'";
    //     $query = $GLOBALS['db']->query($sql);
    //     $r = $query->fetch(PDO::FETCH_ASSOC);
    //     return strlen($r['NamaCabang']) > 35 ? substr($r['NamaCabang'],0,35)."..." : $r['NamaCabang'];
    // }

    // function getNamaSeksi($Kode){
    //     $sql = "SELECT NamaSeksi FROM ims_master_seksi WHERE Kode = '$Kode'";
    //     $query = $GLOBALS['db']->query($sql);
    //     $r = $query->fetch(PDO::FETCH_ASSOC);
    //     return $r['NamaSeksi'];
    // }


    function MasterBiodataDetail($SpkPengangkatan,$Biodata){
       $result = json_decode(base64_decode($SpkPengangkatan),true);
       $resultB = json_decode(base64_decode($Biodata),true);
       $res['UnitKerja'] = $result['NamaCabang'];
       $res['Seksi'] = $result['NamaSeksi'];
       $res['JenisKelamin'] = $resultB['JenisKelamin'];
       $res['Nama'] = $resultB['Nama'];
       $res['Foto'] = $resultB['Foto'];
       return $res;
   }
   
   function FilterData($data){
        $Filter = array();
        foreach ($data as $key => $val) {
            if(!empty($val)){
                if($key == "a.Periode"){
                    $Filter[] = "DATE_FORMAT(".$key.",'%Y-%m') = '".$val."'";
                }else{
                    $Filter[] = $key." = '".$val."'";
                }
            }
        }
        return " AND ".implode(" AND ",$Filter);
   }

    function TenagaKerjaDetail($data,$Dir){
        $datas['a.Periode'] = $data['Periode'];
        $datas['a.KodeCabang'] = $data['KodeCabang'];
        $Filter = FilterData($datas);
        $sql = "SELECT a.NoKtp, a.Biodata, a.SpkPengangkatan FROM ims_master_biodata_bulan a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp WHERE a.Flag = '1' $Filter GROUP BY a.NoKtp ORDER BY KodeCabang ASC";
        

        $query = $GLOBALS['db']->query($sql);
        $result = array();
        $TotJKL =0;
        $TotJKP =0;
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            $Biodata = MasterBiodataDetail($r['SpkPengangkatan'],$r['Biodata']);
            $r['UnitKerja'] = strlen($Biodata['UnitKerja']) > 35 ? substr($Biodata['UnitKerja'],0,35)."..." : $Biodata['UnitKerja'];
            $r['NamaCabang'] = $Biodata['UnitKerja'];
            $r['Seksi'] = $Biodata['Seksi'];
            $r['Nama'] = strlen($Biodata['Nama']) > 20 ? substr($Biodata['Nama'],0,20)."..." : $Biodata['Nama'];
            $r['NamaS'] = $Biodata['Nama'];
            $r['JenisKelamin'] = $Biodata['JenisKelamin'];
            $r['Foto'] = getFoto($r['JenisKelamin'],$Biodata['Foto'],$Dir);
            if($r['JenisKelamin'] == "L"){
                $TotJKL = $TotJKL +1;
            }else{
                $TotJKP = $TotJKP +1;
            }
            $result['Data'][] = $r;
        }
        $result['JumRow'] = count($result['Data']);
        $result['JumPria'] = $TotJKL;
        $result['JumWaninta'] = $TotJKP;
        
        return $result;
   }
   function getFoto($Jk,$Foto,$Dir){
        if(!empty($Foto) && file_exists($Dir.$Foto)){
            return "FotoTenagaKerja/".$Foto;
        }else{
            return $Jk."-avatar.jpg";
        }
   }
    function DetailData($data){
        $awal = microtime(true);
        $db = $GLOBALS['db'];
        $result = array();
        $row = array(); 
        if(is_array($data)){
            $Page = $data['Page'];
            $Key = json_decode(base64_decode($data['Key']),true);
            $RowPage = $data['RowPage'];
            $offset=($Page - 1) * $RowPage;
            $rData = TenagaKerjaDetail($Key,$data['Dir']);
            $JumRow = $rData['JumRow'];
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['JumRow'] = $JumRow;
            $result['JumPria'] = $rData['JumPria'];
            $result['JumWaninta'] = $rData['JumWaninta'];
            
            
            if($JumRow > 0){
                $result['data']=array_slice($rData['Data'],$offset,$RowPage);
                $akhir = microtime(true);
                $lama = $akhir - $awal;
                $result['Waktu'] = round($lama,3);
                $result['total_data'] = count($result['data']);
                return $result; 
            }else{
                $result['data']=array();
                $akhir = microtime(true);
                $lama = $akhir - $awal;
                $result['Waktu'] = round($lama,3);
                $result['total_data'] = count($result['data']);
                return $result; 
            }
            
        }
        
    }

    function InsertLogs($msg){
        $Logs['UserId'] = $_SESSION['Id'];
        $Logs['Logs'] = $msg;
        $Logs['Modul'] = $_SESSION['page'];
        Logs($Logs);
    }

   function UnitKerja(){
       $r = array();
        $sql = "SELECT Kode, NamaCabang FROM ims_master_cabang WHERE Flag = '1' ORDER BY NamaCabang ASC";
        $query = $GLOBALS['db']->query($sql);
        $r['Row'] = $query->rowCount();
        if($r['Row'] > 0){
            while($rs = $query->fetch(PDO::FETCH_ASSOC)){
                $r['Data'][] = $rs;
            }
        }else{
            $r['Data'] = array();
        }
        return $r;
   }

   function TenagaKerja($Id){
        $awal = microtime(true);
        $Filter = empty($Id) ? "" : " AND b.KodeCabang = '$Id'";
        $sql = "SELECT a.NoKtp, a.Nama FROM ims_master_tenaga_kerja a INNER JOIN ims_master_biodata b ON a.NoKtp = b.NoKtp WHERE a.Flag = '1' $Filter  ORDER BY a.Nama ASC";
        $query = $GLOBALS['db']->query($sql);
        $result = array();
        if($query->rowCount() > 0){
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $result['Data'][] = $r;
            }
            $result['Row'] = count($result['Data']);
        }else{
            $result['Row'] = 0;
        }
        $akhir = microtime(true);
        $lama = $akhir - $awal;
        $result['Waktu'] = $lama;
        return $result;
   }

   function MasterBiodata($NoKtp){
       $sql = "SELECT SpkPengangkatan FROM ims_master_biodata WHERE NoKtp = '$NoKtp'";
       $query = $GLOBALS['db']->query($sql);
       $data = $query->fetch(PDO::FETCH_ASSOC);
       $res = json_decode(base64_decode($data['SpkPengangkatan']),true);
       return $res['KodeCabang'];
   }

    
    

    

?>