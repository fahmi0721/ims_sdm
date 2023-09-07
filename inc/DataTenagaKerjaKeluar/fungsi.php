<?php


    function MasterBiodataDetail($SpkPengangkatan){
       $result = json_decode(base64_decode($SpkPengangkatan),true);
       $res['UnitKerja'] = $result['NamaCabang'];
       $res['Seksi'] = $result['NamaSeksi'];
       return $res;
   }

   function FilterData($KodeCabang,$NoKtp){
        $Filter = array();
        if(!empty($KodeCabang)){
            $Filter[] = " b.KodeCabang = '$KodeCabang' ";
        }
        if(!empty($NoKtp)){
            $Filter[] = " a.NoKtp = '$NoKtp' ";
        }
        if(!empty($KodeCabang) OR !empty($NoKtp)){
            return " AND ".implode(" AND ",$Filter);
        }else{
            return "";
        }
   }

   function getFoto($Jk,$Foto,$Dir){
        if(!empty($Foto) && file_exists($Dir.$Foto)){
            return "FotoTenagaKerja/".$Foto;
        }else{
            return $Jk."-avatar.jpg";
        }
   }

    function TenagaKerjaDetail($KodeCabang=null,$NoKtp=null,$Dir){
        $Filter = FilterData($KodeCabang,$NoKtp);
        $sql = "SELECT a.NoKtp, a.Nama, a.JenisKelamin, b.SpkPengangkatan, a.Foto,a.Flag FROM ims_master_tenaga_kerja a INNER JOIN ims_master_biodata b ON a.NoKtp = b.NoKtp WHERE a.Flag = '0' $Filter ORDER BY a.Nama ASC";
        $query = $GLOBALS['db']->query($sql);
        $result = array();
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            $Biodata = MasterBiodataDetail($r['SpkPengangkatan']);
            $r['UnitKerja'] = strlen($Biodata['UnitKerja']) > 35 ? substr($Biodata['UnitKerja'],0,35)."..." : $Biodata['UnitKerja'];
            $r['NamaCabang'] = $Biodata['UnitKerja'];
            $r['Seksi'] = $Biodata['Seksi'];
            $r['NamaS'] = $r['Nama'];
            $r['Nama'] = strlen($r['Nama']) > 20 ? substr($r['Nama'],0,20)."..." : $r['Nama'];
            $r['Foto'] = getFoto($r['JenisKelamin'],$r['Foto'],$Dir);
            $result['Data'][] = $r;
        }
        $result['JumRow'] = count($result['Data']);
        
        return $result;
   }

    function DetailData($data){
        $awal = microtime(true);
        $db = $GLOBALS['db'];
        $result = array();
        $row = array(); 
        if(is_array($data)){
            $Page = $data['Page'];
            $RowPage = $data['RowPage'];
            $KodeCabang = $data['KodeCabang'];
            $NoKtp = $data['NoKtp'];
            $offset=($Page - 1) * $RowPage;
            $rData = TenagaKerjaDetail($KodeCabang,$NoKtp,$data['Dir']);
            $JumRow = $rData['JumRow'];
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['JumRow'] = $JumRow;
            
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
        $sql = "SELECT a.NoKtp, a.Nama FROM ims_master_tenaga_kerja a INNER JOIN ims_master_biodata b ON a.NoKtp = b.NoKtp WHERE a.Flag = '0' $Filter  ORDER BY a.Nama ASC";
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