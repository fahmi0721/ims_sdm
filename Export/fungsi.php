<?php 

function Cells(){
    $cell = array(
        "A",
        "B",
        "C",
        "D",
        "E",
        "F",
        "G",
        "H",
        "J",
        "K",
        "L",
        "M",
        "N",
        "O",
        "P",
        "Q",
        "R",
        "T",
        "U",
        "V",
        "W",
        "X",
        "Y",
        "Z",
    );
    return $cell;
}


/** Export Master Cabang To Excel */
function LoadDataMasterCabang(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, NamaCabang, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_cabang ORDER BY Id ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}

/** Export Master Divisi To Excel */
function LoadDataMasterDivisi(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, NamaDivisi, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_divisi ORDER BY Id ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}

/** Export Master Sub Divisi To Excel */
function LoadDataMasterSubDivisi(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, NamaSubDivisi, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_subdivisi ORDER BY Id ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}

/** Export Master Sub Divisi To Excel */
function LoadDataMasterSeksi(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, NamaSeksi, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_seksi ORDER BY Id ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}

/** Export Master Sub Divisi To Excel */
function LoadDataAgama(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, Nama, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_agama ORDER BY Id ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}
/** DATA MASTER BANK */
function LoadDataBank(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, Nama, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_bank ORDER BY Nama ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}

/** DATA MASTER BRANCH */
function LoadDataBranch(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, Nama, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_branch ORDER BY Nama ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}


/** DATA PENDIDIKAN FORMAL */
function LoadPendidikanFormal(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, Nama, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_pendidikan_formal ORDER BY Nama ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}

/** DATA SUB PENDIDIKAN FORMAL */
function LoadSubPendidikanFormal(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, Nama, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_sub_pendidikan_formal ORDER BY Nama ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}

/** DATA PENDIDIKAN Non FORMAL */
function LoadPendidikanNonFormal(){
    $res = array();
    $query = $GLOBALS['db']->query("SELECT Kode, Nama, if(Flag = '1','Aktif','Tidak Aktif') as Flag FROM ims_master_pendidikan_nonformal ORDER BY Nama ASC");
    $res['Row'] = $query->rowCount();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $res['Data'][] = $r;
    }
    return $res;
}
?>