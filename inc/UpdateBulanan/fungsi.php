<?php

    function FilterData($data){
        $Filter = array();
        $TglNow = date("Y-m-d");
        if(!empty($data['Usia'])){
            $re = " AND round(DATEDIFF('".$TglNow."',TglLahir) / 365) = '".$data['Usia']."'";
            return $re;
        }else{
            return "";
        }
       
    }

    function generateLink($data){
        $KeluarkanData = array("Total","SpkPengangkatan","NamaCabang","NamaCabangTitle");
        foreach($KeluarkanData as $key => $Id){
            unset($data[$Id]);
        }
        $res = base64_encode(json_encode($data));
        return $res;
    }

    function RekapData($data){
        $Filter = FilterData($data);
        $sql = "SELECT COUNT(a.Id) as Total, a.KodeCabang, a.SpkPengangkatan FROM ims_master_biodata a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp WHERE a.Flag = '1' $Filter  GROUP BY a.KodeCabang ASC";
        $query = $GLOBALS['db']->query($sql);
        $result = array();
        $BgColor = array("primary","navy","teal","purple","orange","maroon","black");
        $posisiWarna = 0;
        
        $TotalOC = 0;
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            $TotalOC = $TotalOC + $r['Total'];
            $Biodata = json_decode(base64_decode($r['SpkPengangkatan']),true);
            $r['NamaCabang'] = strlen($Biodata['NamaCabang']) > 35 ? substr($Biodata['NamaCabang'],0,35)."...." : $Biodata['NamaCabang'];;
            $r['NamaCabangTitle'] = $Biodata['NamaCabang'];
            $r['From'] = "Usia";
            $r['KodeCabang'] = $Biodata['KodeCabang'];
            $r['Usia'] = $data['Usia'];
            $r['link-data'] = generateLink($r);
            $r['Total'] = rupiah1($r['Total']);
            $r['bg-color'] = $BgColor[$posisiWarna];
            $posisiWarna++;
            if($posisiWarna == count($BgColor)){ $posisiWarna =0; }
            $BgColorKhusus = $BgColor[$posisiWarna];
            $result['Data'][] = $r;
            
        }
        $result['TotalData'] = $TotalOC;
        $result['BgColorKhusus'] = isset($BgColorKhusus) ? $BgColorKhusus : "";
        $result['JumRow'] = isset($result['Data']) ?  count($result['Data']) : 0;
        
        return $result;
    }

    function DetailData($data){
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
                $result['BgColorKhusus']=$rData['BgColorKhusus'];
                $akhir = microtime(true);
                $lama = $akhir - $awal;
                $result['Waktu'] = round($lama,3);
                $result['total_data'] = rupiah1($rData['TotalData']);
                return $result; 
            }else{
                $result['BgColorKhusus']=$rData['BgColorKhusus'];
                $result['data']=array();
                $akhir = microtime(true);
                $lama = $akhir - $awal;
                $result['Waktu'] = round($lama,3);
                $result['total_data'] = $rData['TotalData'];
                return $result; 
            }
            
        }
        
    }

    function CekData($Periode){
        $r = $GLOBALS['db']->query("SELECT COUNT(Id) as tot FROM ims_master_biodata_bulan WHERE DATE_FORMAT(Periode, '%Y-%m') = '$Periode'")->fetch(PDO::FETCH_ASSOC);
        if($r['tot'] > 0){
            return "ada";
        }else{
            return "tidak ada";
        }
    }

    function Periode(){
        $r = array();
        $PeriodeAwal = "2021-01";
        $TglNow = date("Y-m");
        $TglAkhir = $TglNow != $PeriodeAwal ? date("Y-m", strtotime('-1 month', strtotime($TglNow))) : $TglNow;
        for($i=$PeriodeAwal; $i <= $TglAkhir; $i++){
            $CekData = CekData($i);
            if($CekData != "ada"){
                $Data['Periode'] = $i."-01";
                $Data['PeriodeNama'] = getBulan(substr($i,-2))." ".substr($i,0,4);
                $r['Data'][] = $Data;
            }
        }
        $r['Row'] = COUNT($r['Data']);
        if($r['Row'] <= 0){
            $r['Data'] = array();
        }
        return $r;
   }

   function CountPeriode(){
        $r['Data'] = array();
        $PeriodeAwal = "2020-09";
        $TglNow = date("Y-m");
        $TglAkhir = $TglNow != $PeriodeAwal ? date("Y-m", strtotime('-1 month', strtotime($TglNow))) : $TglNow;
        for($i=$PeriodeAwal; $i <= $TglAkhir; $i++){
            $CekData = CekData($i);
            if($CekData != "ada"){
                $data = getBulan(substr($i,-2))." ".substr($i,0,4);
                $r['Data'][] = $data;
            }
        }
        return $r;
   }

    function InsertLogs($msg){
        $Logs['UserId'] = $_SESSION['Id'];
        $Logs['Logs'] = $msg;
        $Logs['Modul'] = $_SESSION['page'];
        Logs($Logs);
    }

    function TambahDataBulan($Periode){
        $sql = "CALL TambahDataBulanan('".$Periode."', 1)";
        $query = $GLOBALS['db']->query($sql);
        if($query){
            $rMsg = "Periode ".getBulan(substr($Periode,5,2))." ".substr($Periode,0,4)." berhasil di backup";
            $msg['pesan'] = $rMsg;
            $msg['status'] = "sukses";
            InsertLogs($rMsg);
            return $msg;
        }else{
            $msg['pesan'] = "Gagal membackup data";
            $msg['status'] = "gagal";
            return $msg;
        }
    }

   

    
    

    

?>