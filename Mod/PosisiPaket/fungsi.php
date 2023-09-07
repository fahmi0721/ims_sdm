<?php
    function TkPkt(){
        $db = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_master_biodata WHERE KodeSubDivisi != '002' AND KodeSubDivisi != '001' ";
        $query = $db->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['tot'];
    }

    function TkSc(){
        $db = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_master_biodata WHERE KodeSubDivisi = '001'";
        $query = $db->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['tot'];
    }

    function TkCs(){
        $db = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_master_biodata WHERE KodeSubDivisi = '002'";
        $query = $db->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['tot'];
    }

    function LoadData(){
        $BulanIni = date("Y-m");
        $r = array();
        $r['TkPkt'] = rupiah1(TkPkt($BulanIni));
        $r['TkSc'] = rupiah1(TkSc($BulanIni));
        $r['TkCs'] = rupiah1(TkCs());
        return $r;
        
    }

    


    

?>