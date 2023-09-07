<?php

    function FilterData($data){
        $Filter = array();
        foreach($data as $key => $val){
            if(!empty($val)){
                $Filter[] = $key." = '".$val."'";
            }
        }
        if(!empty($data['KodeBranch']) ||!empty($data['KodeDivisi']) || !empty($data['KodeSubDivisi']) || !empty($data['KodeSeksi'])){
            return " AND ".implode(" AND ",$Filter);
        }else{
            return "";
        }
    }

    function generateLink($data){
        $KeluarkanData = array("Total","SpkPengangkatan","NamaCabang","NamaCabangTitle",);
        foreach($KeluarkanData as $key => $Id){
            unset($data[$Id]);
        }
        $res = base64_encode(json_encode($data));
        return $res;
    }

    function RekapData($data){
        $Filter = FilterData($data);
        $sql = "SELECT COUNT(Id) as Total, KodeCabang, SpkPengangkatan FROM ims_master_biodata WHERE Flag = '1' AND (SpkKeluar IS NULL OR SpkKeluar = '') $Filter  GROUP BY KodeCabang ASC";
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
            $r['From'] = "Penempatan";
            $r['KodeBranch'] = $data['KodeBranch'];
            $r['KodeDivisi'] = $data['KodeDivisi'];
            $r['KodeSubDivisi'] = $data['KodeSubDivisi'];
            $r['KodeSeksi'] = $data['KodeSeksi'];
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

    function InsertLogs($msg){
        $Logs['UserId'] = $_SESSION['Id'];
        $Logs['Logs'] = $msg;
        $Logs['Modul'] = $_SESSION['page'];
        Logs($Logs);
    }

    function Branch(){
        $r = array();
         $sql = "SELECT Kode, Nama FROM ims_master_branch WHERE Flag = '1' ORDER BY Nama ASC";
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

   function Divisi(){
       $r = array();
        $sql = "SELECT Kode, NamaDivisi FROM ims_master_divisi WHERE Flag = '1' ORDER BY NamaDivisi ASC";
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

    function SubDivisi(){
       $r = array();
        $sql = "SELECT Kode, NamaSubDivisi FROM ims_master_subdivisi WHERE Flag = '1' ORDER BY NamaSubDivisi ASC";
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

    function Seksi(){
       $r = array();
        $sql = "SELECT Kode, NamaSeksi FROM ims_master_seksi WHERE Flag = '1' ORDER BY NamaSeksi ASC";
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

   

    
    

    

?>