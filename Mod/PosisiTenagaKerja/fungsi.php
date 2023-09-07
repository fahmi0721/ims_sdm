<?php
    function TenagaKerjaBaru($Tmt){
        $db = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_master_tenaga_kerja WHERE DATE_FORMAT(Tmt, '%Y-%m') = '$Tmt'";
        $query = $db->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['tot'];
    }

    function TenagaKerjaKeluar($Tmt){
        $db = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_sk_pemberhentian WHERE DATE_FORMAT(Tmt, '%Y-%m') = '$Tmt'";
        $query = $db->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['tot'];
    }

    function PosisiTenagaKerja(){
        $db = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_master_tenaga_kerja WHERE Flag = '1'";
        $query = $db->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['tot'];
    }

    function LoadData(){
        $BulanIni = date("Y-m");
        $r = array();
        $r['TenagaKerjaBaru'] = rupiah1(TenagaKerjaBaru($BulanIni));
        $r['TenagaKerjaKeluar'] = rupiah1(TenagaKerjaKeluar($BulanIni));
        $r['PosisiTenagaKerja'] = rupiah1(PosisiTenagaKerja());
        return $r;
        
    }

    


    

?>