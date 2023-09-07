<?php

    
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
                    $Filter[] = "DATE_FORMAT(".$key.", '%Y-%m') = '".$val."'";
                }else{
                    $Filter[] = $key." = '".$val."'";
                }
            }
        }
        return !empty($Filter) ? " WHERE ".implode(" AND ",$Filter) : "";
   }

    function TenagaKerjaDetail($data){
        $datas['a.NoKtp'] = $data['NoKtp'];
        $datas['a.Periode'] = $data['Periode'];
        $Filter = FilterData($datas);
        $sql = "SELECT a.Id, a.NoKtp, a.Biodata, a.SpkPengangkatan FROM ims_master_biodata_bulan a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp  $Filter GROUP BY a.NoKtp ORDER BY a.KodeCabang ASC";

        $query = $GLOBALS['db']->query($sql);
        $result = array();
        $result['Data'] = array();
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
            $r['Foto'] = getFoto($r['JenisKelamin'],$Biodata['Foto'],$data['Dir']);
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
            $RowPage = $data['RowPage'];
            $offset=($Page - 1) * $RowPage;
            $rData = TenagaKerjaDetail($data);
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

    function CekData($Id){
        $r = $GLOBALS['db']->query("SELECT Biodata FROM ims_master_biodata_bulan WHERE Id = '$Id' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $Biodata = json_decode(base64_decode($r['Biodata']),true);
        return $Biodata;
    }

    function Periode(){
        $r = array();
        $sql = "SELECT DATE_FORMAT(Periode, '%Y-%m') as Periode FROM ims_master_biodata_bulan GROUP BY DATE_FORMAT(Periode, '%Y-%m') ORDER BY DATE_FORMAT(Periode, '%Y-%m') ASC";
        $query = $GLOBALS['db']->query($sql);
        if($query->rowCount() > 0){
            $r['Row'] = $query->rowCount();
            while($dt = $query->fetch(PDO::FETCH_ASSOC)){
                $dt['PeriodeNama'] = getBulan(substr($dt['Periode'],-2))." ".substr($dt['Periode'],0,4);
                $r['Data'][] = $dt;
            }
        }else{
            $r['Row'] = 0;
            $r['Data'] = array();
        }
        return $r;
   }

   function NoKtp(){
       $r = array();
        $sql = "SELECT NoKtp, Biodata FROM ims_master_biodata_bulan GROUP BY NoKtp ORDER BY NoKtp ASC";
        $query = $GLOBALS['db']->query($sql);
        if($query->rowCount() > 0){
            $r['Row'] = $query->rowCount();
            while($dt = $query->fetch(PDO::FETCH_ASSOC)){
                $Biodata = json_decode(base64_decode($dt['Biodata']),true);
                $dt['Nama'] = $dt['NoKtp']." - ".$Biodata['Nama'];
                unset($dt['Biodata']);
                $r['Data'][] = $dt;
            }
        }else{
            $r['Row'] = 0;
            $r['Data'] = array();
        }
        return $r;
        
   }

    function InsertLogs($msg){
        $Logs['UserId'] = $_SESSION['Id'];
        $Logs['Logs'] = $msg;
        $Logs['Modul'] = $_SESSION['page'];
        Logs($Logs);
    }

    function UpdateData($Id){
        $sql = "CALL UpdateDataBulanan(".$Id.", 1)";
        $query = $GLOBALS['db']->query($sql);
        if($query){
            $iData = cekData($Id);
            $rMsg = "Data a.n ".$iData['Nama']." Berhasil di update";
            $msg['pesan'] = $rMsg;
            $msg['status'] = "sukses";
            InsertLogs($rMsg);
            return $msg;
        }else{
            $msg['pesan'] = "Gagal mengupdate data";
            $msg['status'] = "gagal";
            return $msg;
        }
    }

    function HapusData($Id){
        $iData = cekData($Id);
        $sql = "DELETE FROM ims_master_biodata_bulan WHERE Id = $Id";
        $query = $GLOBALS['db']->query($sql);
        if($query){
            
            $rMsg = "Data a.n ".$iData['Nama']." Berhasil di hapus pada daftar data bulanan";
            $msg['pesan'] = $rMsg;
            $msg['status'] = "sukses";
            InsertLogs($rMsg);
            return $msg;
        }else{
            $msg['pesan'] = "Gagal mengupdate data";
            $msg['status'] = "gagal";
            return $msg;
        }
    }

   

    
    

    

?>