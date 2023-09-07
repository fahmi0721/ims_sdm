<?php

    function FilterData($data){
        $Filter = array();
        $TglNow = date("Y-m-d");
        if(!empty($data['MasaKerja']) ){
            
            $re = $data['MasaKerja'] == "ok" ? " AND round(DATEDIFF('".$TglNow."',Tmt) / 365) = '0'" : " AND round(DATEDIFF('".$TglNow."',Tmt) / 365) =".$data['MasaKerja'];
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
        $data['MasaKerja'] = $data['MasaKerja'] == 0 ? "ok" : $data['MasaKerja'];
        $Filter = FilterData($data);
        $sql = "SELECT COUNT(a.Id) as Total, a.KodeCabang, a.SpkPengangkatan FROM ims_master_biodata a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp WHERE b.Flag = '1' $Filter  GROUP BY a.KodeCabang ASC";
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
            $r['From'] = "MasaKerja";
            $r['KodeCabang'] = $Biodata['KodeCabang'];
            $r['MasaKerja'] = $data['MasaKerja'];
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

    

   function MasaKerja(){
        $r = array();
        $TglNow = date("Y-m-d");
        $sql = "SELECT round(DATEDIFF('".$TglNow."',Tmt) / 365) as MasaKerja FROM ims_master_tenaga_kerja WHERE Flag = '1' GROUP BY round(DATEDIFF('$TglNow', Tmt) / 365) ORDER BY  round(DATEDIFF('$TglNow', Tmt) / 365) ASC";
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