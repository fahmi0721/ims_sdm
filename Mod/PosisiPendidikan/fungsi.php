<?php
    function Jp(){
        $sql = "SELECT COUNT(a.KodePendidikanFormal) as tot, b.Nama FROM ims_master_biodata a INNER JOIN ims_master_pendidikan_formal b ON a.KodePendidikanFormal = b.Kode WHERE a.Flag = '1' GROUP BY a.KodePendidikanFormal";
        $query = $GLOBALS['db']->query($sql);
        $row = $query->rowCount();
        $pieData = array();
        if($row > 0){
            $color = array("#f56954","#00a65a","#f39c12","#00c0ef","#3c8dbc","#d2d6de");
            $dt = array();
            $poc = 0;
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $dt['value'] = intval($r['tot']);
                $dt['color'] = $color[$poc];
                $dt['highlight'] = $color[$poc];
                $dt['label'] = $r['Nama'];
                $pieData[] = $dt;
                $poc++;
            }
        }else{
            $pieData[0]['value'] = -1;
            $pieData[0]['color'] = "#f56954";
            $pieData[0]['highlight'] = "#f56954";
            $pieData[0]['label'] = "Data Belum ada";
        }
        
        return $pieData;
    }

    function Jurusan(){
        $sql = "SELECT COUNT(a.KodeSubPendidikanFormal) as tot, b.Nama FROM ims_master_biodata a INNER JOIN ims_master_sub_pendidikan_formal b ON a.KodeSubPendidikanFormal = b.Kode  GROUP BY a.KodeSubPendidikanFormal";
        $query = $GLOBALS['db']->query($sql);
        $row = $query->rowCount();
        $pieData = array();
        if($row > 0){
            $color = array("#f56954","#00a65a","#f39c12","#00c0ef","#3c8dbc","#d2d6de");
            $dt = array();
            $poc = 0;
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $dt['value'] = intval($r['tot']);
                $dt['color'] = $color[$poc];
                $dt['highlight'] = $color[$poc];
                $dt['label'] = $r['Nama'];
                $pieData[] = $dt;
                $poc++;
            }
        }else{
            $pieData[0]['value'] = -1;
            $pieData[0]['color'] = "#f56954";
            $pieData[0]['highlight'] = "#f56954";
            $pieData[0]['label'] = "Data Belum ada";
        }
        
        return $pieData;
    }

    function Sertifikasi(){
        $sql = "SELECT COUNT(a.KodePendidikanNonFormal) as tot, b.Nama FROM ims_master_biodata a INNER JOIN ims_master_pendidikan_nonformal b ON a.KodePendidikanNonFormal = b.Kode  GROUP BY a.KodePendidikanNonFormal";
        $query = $GLOBALS['db']->query($sql);
        $row = $query->rowCount();
        $pieData = array();
        if($row > 0){
            $color = array("#f56954","#00a65a","#f39c12","#00c0ef","#3c8dbc","#d2d6de");
            $dt = array();
            $poc = 0;
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $dt['value'] = intval($r['tot']);
                $dt['color'] = $color[$poc];
                $dt['highlight'] = $color[$poc];
                $dt['label'] = $r['Nama'];
                $pieData[] = $dt;
                $poc++;
            }
        }else{
            $pieData[0]['value'] = -1;
            $pieData[0]['color'] = "#f56954";
            $pieData[0]['highlight'] = "#f56954";
            $pieData[0]['label'] = "Data Belum ada";
        }
        
        return $pieData;
    }

    function LoadData(){
        $BulanIni = date("Y-m");
        $r = array();
        $r['Jp'] = Jp();
        $r['Jurusan'] = Jurusan();
        $r['Sertifikasi'] =Sertifikasi(); 
        return $r;
        
    }

    


    

?>