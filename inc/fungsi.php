<?php

    function GetNewTenagaKerja(){
        $koneksi = $GLOBALS['db'];
        $BulanNow = date("Y-m");
        try {
            $sql = "SELECT COUNT(Id) as tot FROM ims_tenaga_kerja WHERE DATE_FORMAT(TMT,'%Y-%m') = '$BulanNow' AND `Status` = '0'";
            $query = $koneksi->query($sql);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data['tot'];
        } catch (PDOExeption $e) {
            return $e->getMessage();
        }
    }

    function GetTenagaKerjaKeluar(){
        $koneksi = $GLOBALS['db'];
        $BulanNow = date("Y");
        try {
            $sql = "SELECT COUNT(a.Id) as tot FROM ims_tenaga_kerja a INNER JOIN ims_tenaga_kerja_keluar b ON a.Id = b.IdTenagaKerja WHERE DATE_FORMAT(b.TMTKeluar,'%Y') = '$BulanNow'";
            $query = $koneksi->query($sql);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data['tot'];
        } catch (PDOExeption $e) {
            return $e->getMessage();
        }
    }

    function GetSumTenagaKerja(){
        $koneksi = $GLOBALS['db'];
        $BulanNow = date("Y-m");
        try {
            $sql = "SELECT COUNT(Id) as tot FROM ims_tenaga_kerja WHERE  `Status` = '0'";
            $query = $koneksi->query($sql);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data['tot'];
        } catch (PDOExeption $e) {
            return $e->getMessage();
        }
    }

    function LoadMenu(){
        $MenuRoot = getMenuRoot();
        $MenuSub = getMenuSub();
        $res['root']=$MenuRoot;
        $res['sub']=$MenuSub;
        return $res;
    }

    function getListMenu($Dir){
        $res = array();
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Direktori FROM ims_menu WHERE ItemRoot = :Dir";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam('Dir', $Dir, PDO::PARAM_STR);
        $exc->execute();
        while($r = $exc->fetch(PDO::FETCH_ASSOC)){
            $res[] = $r['Direktori'];
        }
        return $res;
    }

    function CekMenu($Dir){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_menu WHERE ItemRoot = :Dir";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam('Dir', $Dir, PDO::PARAM_STR);
        $exc->execute();
        $r = $exc->fetch(PDO::FETCH_ASSOC);
        return $r['tot'];
    }

    function getMenuRoot(){
        $koneksi = $GLOBALS['db'];
        $IdUser = $_SESSION['Id'];
        $Status = 1;
        $ItemRoot = 'Menu-Root';
        $sql = "SELECT a.Direktori, a.Icon, a.NamaMenu FROM ims_menu a INNER JOIN ims_menu_level b ON a.Id = b.IdMenu WHERE a.Status = :Statuss AND a.ItemRoot = :ItemRoot AND b.IdUser = :IdUser";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam('Statuss', $Status, PDO::PARAM_STR);
        $exc->bindParam('ItemRoot', $ItemRoot, PDO::PARAM_STR);
        $exc->bindParam('IdUser', $IdUser, PDO::PARAM_INT);
        $exc->execute();
        $rows = $exc->rowCount();
        $data = array();
        $res = array();
        while($r = $exc->fetch(PDO::FETCH_ASSOC)){
            $cekMenu = CekMenu($r['Direktori']);
            if($cekMenu > 0){
                $res['NamaMenu'] = $r['NamaMenu'];
                $res['Icon'] = $r['Icon'];
                $res['Direktori'] = $r['Direktori'];
                $res['Tipe'] = "multi";
            }else{
                $res['NamaMenu'] = $r['NamaMenu'];
                $res['Icon'] = $r['Icon'];
                $res['Direktori'] = $r['Direktori'];
                $res['Tipe'] = "single";
            }
            $data[] = $res;
        }

        return $data;

    }

    function getMenuSub(){
        $koneksi = $GLOBALS['db'];
        $IdUser = $_SESSION['Id'];
        $Status = 1;
        $ItemRoot = 'Menu-Root';
        $sql = "SELECT a.Direktori, a.Icon, a.NamaMenu,a.ItemRoot FROM ims_menu a INNER JOIN ims_menu_level b ON a.Id = b.IdMenu WHERE a.Status = :Statuss AND a.ItemRoot != :ItemRoot AND b.IdUser = :IdUser";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam('Statuss', $Status, PDO::PARAM_STR);
        $exc->bindParam('ItemRoot', $ItemRoot, PDO::PARAM_STR);
        $exc->bindParam('IdUser', $IdUser, PDO::PARAM_INT);
        $exc->execute();
        $data = array();
        $res = array();
        while($r = $exc->fetch(PDO::FETCH_ASSOC)){
            $res['NamaMenu'] = $r['NamaMenu'];
            $res['Icon'] = $r['Icon'];
            $res['Direktori'] = $r['Direktori'];
            $res['ItemRoot'] = $r['ItemRoot'];
            $data[] = $res;
        }
        return $data;

    }

    function getIdMenu($page){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Id FROM ims_menu WHERE Direktori = :pages";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam('pages', $page, PDO::PARAM_STR);
        $exc->execute();
        $r = $exc->fetch(PDO::FETCH_ASSOC);
        $Id = !empty($r['Id']) ? $r['Id'] : "";
        return $Id;
    }

    function cekMenuAkes($page){
        $koneksi = $GLOBALS['db'];
        $IdMenu = getIdMenu($page);
        $IdUser = $_SESSION['Id'];
        $Aktif = 1;
        $sql = "SELECT COUNT(Id) as tot FROM ims_menu_level WHERE Aktif = :Aktif AND IdMenu = :IdMenu AND IdUser = :IdUser";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam('Aktif', $Aktif, PDO::PARAM_STR);
        $exc->bindParam('IdMenu', $IdMenu, PDO::PARAM_INT);
        $exc->bindParam('IdUser', $IdUser, PDO::PARAM_INT);
        $exc->execute();
        $r = $exc->fetch(PDO::FETCH_ASSOC);
        if($r['tot'] > 0){
            return $r['tot'];
        }else{
            return $r['tot'];
        }

        
    }

    function getPageLevel(){
        $koneksi = $GLOBALS['db'];
        $IdMenu = getIdMenu($_SESSION['page']);
        $IdUser = $_SESSION['Id'];
        if(!empty($_SESSION['page'])){
            $sql = "SELECT `Status` FROM ims_menu_level WHERE IdMenu = :IdMenu AND IdUser = :IdUser";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('IdMenu', $IdMenu, PDO::PARAM_INT);
            $exc->bindParam('IdUser', $IdUser, PDO::PARAM_INT);
            $exc->execute();
            $r = $exc->fetch(PDO::FETCH_ASSOC);
            return !empty($r['Status']) ? $r['Status'] : ""; 
        }else{
            return "";
        }
    }

    function UpdateNotif(){
        NotifModuTempTenagaKerjaBaru();
        NotifModuTempTenagaKerjaUpdate();
        NotifModuTempTenagaKerjaDelete();

        //Notif DPLK
        NotifModuTempDplkBaru();
        NotifModuTempDplkUpdate();
        NotifModuTempDplkDelete();
        return true;
    }
    function NotifModuTempTenagaKerjaBaru(){
        $koneksi = $GLOBALS['db'];
        $Status = 0;
        $aksi = 'insert';
        $sql = "SELECT COUNT(Id) as tot FROM ims_tenaga_kerja_temp WHERE `Status` = :Statuss AND StatusNotif = :StatusNotif AND aksi = :aksi GROUP BY aksi";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("Statuss", $Status, PDO::PARAM_STR);
        $exec->bindParam("StatusNotif", $Status, PDO::PARAM_STR);
        $exec->bindParam("aksi", $aksi, PDO::PARAM_STR);
        $exec->execute();
        if($exec){
            $r = $exec->fetch(PDO::FETCH_ASSOC);
            $Pesan = $r['tot']. " Tenaga Kerja Baru Siap Diapprove.";
            if($r['tot'] > 0){
                $IdMenu = getIdMenu('TenagaKerja');
                $IdUser = GetListApproval($IdMenu);
                if($IdUser){
                    foreach($IdUser as $Id){
                        $data = array(
                            "Pesan" => $Pesan,
                            "UserTujuan" => $Id,
                            "Modul" => "TenagaKerja"
                        );  
                        KirimNotif($data);
                        $data1 = array(
                            "value" => "insert",
                            "key" => "aksi",
                            "table" => "ims_tenaga_kerja_temp"
                        ); 
                        UdateNotif($data1);
                    }
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
        
    }

    function NotifModuTempTenagaKerjaUpdate(){
        $koneksi = $GLOBALS['db'];
        $Status = 0;
        $aksi = 'update';
        $sql = "SELECT COUNT(Id) as tot FROM ims_tenaga_kerja_temp WHERE `Status` = :Statuss AND StatusNotif = :StatusNotif AND aksi = :aksi GROUP BY aksi";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("Statuss", $Status, PDO::PARAM_STR);
        $exec->bindParam("StatusNotif", $Status, PDO::PARAM_STR);
        $exec->bindParam("aksi", $aksi, PDO::PARAM_STR);
        $exec->execute();
        if($exec){
            $r = $exec->fetch(PDO::FETCH_ASSOC);
            $Pesan = $r['tot']. " Data Tenaga Kerja Telah Diupdate Siap Diapprove.";
            if($r['tot'] > 0){
                $IdMenu = getIdMenu('TenagaKerja');
                $IdUser = GetListApproval($IdMenu);
                if($IdUser){
                    foreach($IdUser as $Id){
                        $data = array(
                            "Pesan" => $Pesan,
                            "UserTujuan" => $Id,
                            "Modul" => "TenagaKerja"
                        );  
                        KirimNotif($data);
                        $data1 = array(
                            "value" => "update",
                            "key" => "aksi",
                            "table" => "ims_tenaga_kerja_temp"
                        ); 
                        UdateNotif($data1);
                    }
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
        
    }

    function NotifModuTempTenagaKerjaDelete(){
        $koneksi = $GLOBALS['db'];
        $Status = 0;
        $aksi = 'delete';
        $sql = "SELECT COUNT(Id) as tot FROM ims_tenaga_kerja_temp WHERE `Status` = :Statuss AND StatusNotif = :StatusNotif AND aksi = :aksi GROUP BY aksi";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("Statuss", $Status, PDO::PARAM_STR);
        $exec->bindParam("StatusNotif", $Status, PDO::PARAM_STR);
        $exec->bindParam("aksi", $aksi, PDO::PARAM_STR);
        $exec->execute();
        if($exec){
            $r = $exec->fetch(PDO::FETCH_ASSOC);
            $Pesan = $r['tot']. " Data Tenaga Kerja Telah Diupdate Siap Diapprove.";
            if($r['tot'] > 0){
                $IdMenu = getIdMenu('TenagaKerja');
                $IdUser = GetListApproval($IdMenu);
                if($IdUser){
                    foreach($IdUser as $Id){
                        $data = array(
                            "Pesan" => $Pesan,
                            "UserTujuan" => $Id,
                            "Modul" => "TenagaKerja"
                        );  
                        KirimNotif($data);
                        $data1 = array(
                            "value" => "delete",
                            "key" => "aksi",
                            "table" => "ims_tenaga_kerja_temp"
                        ); 
                        UdateNotif($data1);
                    }
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
        
    }

    function UdateNotif($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $sql = "UPDATE ".$data['table']." SET StatusNotif = '1' WHERE ".$data['key']." = '".$data['value']."'";
            $exc = $koneksi->query($sql);
            if($exc){
                return true;
            }else{
                return false;
            }
            
        }else{
            return false;
        }
    }

    function GetListApproval($IdMenu){
        $koneksi = $GLOBALS['db'];
        if($IdMenu){
            $sql = "SELECT `IdUser` FROM ims_list_approval WHERE IdMenu = :IdMenu";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('IdMenu', $IdMenu, PDO::PARAM_INT);
            $exc->execute();
            $row = $exc->rowCount();
            while($r = $exc->fetch(PDO::FETCH_ASSOC)){
                $res[] = $r['IdUser'];
            }
            if($row > 0){
                return $res;
            }else{
                return false;
            }
            
        }else{
            return false;
        }
    }

    function KirimNotif($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $sql = "INSERT INTO ims_notif SET Pesan = :Pesan, Modul = :Modul, UserTujuan = :UserTujuan";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam("Pesan",$data['Pesan'],PDO::PARAM_STR);
            $exc->bindParam("Modul",$data['Modul'],PDO::PARAM_STR);
            $exc->bindParam("UserTujuan",$data['UserTujuan'],PDO::PARAM_STR);
            $exc->execute();
            if($exc){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function LoadCountNotif($IdUser){
        $koneksi = $GLOBALS['db'];
        if($IdUser){
            $sql = "SELECT COUNT(Id) as tot FROM ims_notif WHERE UserTujuan = :IdUser AND StatusBaca = :StatusBaca";
            $StatusBaca = 0;
            $exc = $koneksi->prepare($sql);
            $exc->bindParam("IdUser",$IdUser,PDO::PARAM_INT);
            $exc->bindParam("StatusBaca",$StatusBaca,PDO::PARAM_STR);
            $exc->execute();
            if($exc){
                $res = $exc->fetch(PDO::FETCH_ASSOC);
                return $res['tot'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function NotifListTenagaKerja($IdUser){
        $data = array();
        $koneksi = $GLOBALS['db'];
        $StatusBaca = 0;
        $sql = "SELECT * FROM ims_notif WHERE UserTujuan = :IdUser AND StatusBaca = :StatusBaca";
        $StatusBaca = 0;
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("IdUser",$IdUser,PDO::PARAM_INT);
        $exc->bindParam("StatusBaca",$StatusBaca,PDO::PARAM_STR);
        $exc->execute();
        if($exc){
            while($res = $exc->fetch(PDO::FETCH_ASSOC)){
                $r['Pesan'] = $res['Pesan'];
                $r['Modul'] = $res['Modul'];
                $r['Icon'] = "fa fa-users";
                $data[] = $r;
            }
        
            return $data;
            
        }else{
            return false;
        }
    }

    function LoadListNotif($IdUser){
        $koneksi = $GLOBALS['db'];
        if($IdUser){
            $data = NotifListTenagaKerja($IdUser);
            return $data;
        }else{
            return false;
        }
    }

    function ReadNotif($Modul){
        $koneksi = $GLOBALS['db'];
        $IdUser = $_SESSION['Id'];
        $StatusBaca = 1;
        $sql = "UPDATE ims_notif SET StatusBaca = :StatusBaca WHERE UserTujuan = :IdUser AND Modul = :Modul";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("StatusBaca",$StatusBaca,PDO::PARAM_STR);
        $exc->bindParam("IdUser",$IdUser,PDO::PARAM_INT);
        $exc->bindParam("Modul",$Modul,PDO::PARAM_STR);
        $exc->execute();
        if($exc){
            return true;
        }else{
            return false;
        }

    }
    
    function LogsAdmin($page){
        $koneksi = $GLOBALS['db'];
        $Id = $_SESSION['Id'];
        $rowPage = 10;
        $offset=($page - 1) * $rowPage;
        try {
            $Now = date("Y-m-d");
            $sql = "SELECT b.Nama, a.Logs, DATE_FORMAT(a.TglCreate, '%H:%i:%s') as Jam FROM ims_logs a INNER JOIN ims_users b ON a.IdUser = b.Id WHERE DATE_FORMAT(a.TglCreate,'%Y-%m-%d') = '$Now'  ORDER BY a.TglCreate DESC";
            $exec = $koneksi->query($sql);
            $rows = $exec->rowCount();
            $sql1 = $sql." LIMIT ".$offset.", ".$rowPage;
            $total_page = ceil($rows / $rowPage);
            $exec1 = $koneksi->query($sql1);
            if($rows > 0){
                while($r = $exec1->fetch(PDO::FETCH_ASSOC)){
                    $res['Uraian'] = "<b>".$r['Nama']."</b> ".$r['Logs'];
                    $res['Time'] = $r['Jam'];
                    $data['item'][] = $res;
                }
                $data['status'] = 0;
                $data['total_page'] = $total_page;
            }else{
                $data['status'] = $Id;
                $data['item'] = "";
            }
        } catch (PDOException $e) {
            $data['status'] = 1;
            $data['item'] = $e->getMessage();;
        }
        return $data;
    }

    function LogsMember($page){
        $koneksi = $GLOBALS['db'];
        $Id = $_SESSION['Id'];
        $rowPage = 10;
        $offset=($page - 1) * $rowPage;

        try {
        	$Now = date("Y-m-d");
            $sql = "SELECT Logs, DATE_FORMAT(TglCreate, '%H:%i:%s') as Jam FROM ims_logs WHERE IdUser = '$Id' AND  DATE_FORMAT(a.TglCreate,'%Y-%m-%d') = '$Now' ORDER BY TglCreate DESC";
            $exec = $koneksi->query($sql);
            $rows = $exec->rowCount();
            $sql1 = $sql." LIMIT ".$offset.", ".$rowPage;
            $total_page = ceil($rows / $rowPage);
            $exec1 = $koneksi->query($sql1);
            if($rows > 0){
                while($r = $exec1->fetch(PDO::FETCH_ASSOC)){
                    $res['Uraian'] = $r['Logs'];
                    $res['Time'] = $r['Jam'];
                    $data['item'][] = $res;
                }
                $data['status'] = 0;
                $data['total_page'] = $total_page;
            }else{
                $data['status'] = $Id;
                $data['item'] = "";
            }
        } catch (PDOException $e) {
            $data['status'] = 1;
            $data['item'] = $e->getMessage();;
        }
        return $data;
    }


    function NotifModuTempDplkBaru(){
        $koneksi = $GLOBALS['db'];
        $Status = 0;
        $aksi = 'insert';
        $sql = "SELECT COUNT(Id) as tot FROM ims_dplk_temp WHERE `Status` = :Statuss AND StatusNotif = :StatusNotif AND aksi = :aksi GROUP BY aksi";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("Statuss", $Status, PDO::PARAM_STR);
        $exec->bindParam("StatusNotif", $Status, PDO::PARAM_STR);
        $exec->bindParam("aksi", $aksi, PDO::PARAM_STR);
        $exec->execute();
        if($exec){
            $r = $exec->fetch(PDO::FETCH_ASSOC);
            $Pesan = $r['tot']. " Data  DPLK Baru Siap Diapprove.";
            if($r['tot'] > 0){
                $IdMenu = getIdMenu('Dplk');
                $IdUser = GetListApproval($IdMenu);
                if($IdUser){
                    foreach($IdUser as $Id){
                        $data = array(
                            "Pesan" => $Pesan,
                            "UserTujuan" => $Id,
                            "Modul" => "Dplk"
                        );  
                        KirimNotif($data);
                        $data1 = array(
                            "value" => "insert",
                            "key" => "aksi",
                            "table" => "ims_dplk_temp"
                        ); 
                        UdateNotif($data1);
                    }
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
        
    }

    function NotifModuTempDplkUpdate(){
        $koneksi = $GLOBALS['db'];
        $Status = 0;
        $aksi = 'update';
        $sql = "SELECT COUNT(Id) as tot FROM ims_dplk_temp WHERE `Status` = :Statuss AND StatusNotif = :StatusNotif AND aksi = :aksi GROUP BY aksi";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("Statuss", $Status, PDO::PARAM_STR);
        $exec->bindParam("StatusNotif", $Status, PDO::PARAM_STR);
        $exec->bindParam("aksi", $aksi, PDO::PARAM_STR);
        $exec->execute();
        if($exec){
            $r = $exec->fetch(PDO::FETCH_ASSOC);
            $Pesan = $r['tot']. " Data DPLK Telah Diupdate Siap Diapprove.";
            if($r['tot'] > 0){
                $IdMenu = getIdMenu('Dplk');
                $IdUser = GetListApproval($IdMenu);
                if($IdUser){
                    foreach($IdUser as $Id){
                        $data = array(
                            "Pesan" => $Pesan,
                            "UserTujuan" => $Id,
                            "Modul" => "Dplk"
                        );  
                        KirimNotif($data);
                        $data1 = array(
                            "value" => "update",
                            "key" => "aksi",
                            "table" => "ims_dplk_temp"
                        ); 
                        UdateNotif($data1);
                    }
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
        
    }

    function NotifModuTempDplkDelete(){
        $koneksi = $GLOBALS['db'];
        $Status = 0;
        $aksi = 'delete';
        $sql = "SELECT COUNT(Id) as tot FROM ims_dplk_temp WHERE `Status` = :Statuss AND StatusNotif = :StatusNotif AND aksi = :aksi GROUP BY aksi";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("Statuss", $Status, PDO::PARAM_STR);
        $exec->bindParam("StatusNotif", $Status, PDO::PARAM_STR);
        $exec->bindParam("aksi", $aksi, PDO::PARAM_STR);
        $exec->execute();
        if($exec){
            $r = $exec->fetch(PDO::FETCH_ASSOC);
            $Pesan = $r['tot']. " Data DPLK Telah Dihapus Siap Diapprove.";
            if($r['tot'] > 0){
                $IdMenu = getIdMenu('Dplk');
                $IdUser = GetListApproval($IdMenu);
                if($IdUser){
                    foreach($IdUser as $Id){
                        $data = array(
                            "Pesan" => $Pesan,
                            "UserTujuan" => $Id,
                            "Modul" => "Dplk"
                        );  
                        KirimNotif($data);
                        $data1 = array(
                            "value" => "delete",
                            "key" => "aksi",
                            "table" => "ims_dplk_temp"
                        ); 
                        UdateNotif($data1);
                    }
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
        
    }

    function getJumlahKaryawan1($Jabatan){
        $Jabatan = explode(",",$Jabatan);
        $res = array();
        foreach($Jabatan as $key => $r){
            $res[] = "'".$r."'";
        }
        $ress = implode(",",$res);
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_tenaga_kerja WHERE `Status` = '0' AND Jabatan IN (".$ress.")";
        $query = $koneksi->query($sql);
        $ressa = $query->fetch(PDO::FETCH_ASSOC);
        return $ressa['tot'];
    }

    function GetGroup1(){
        $db = $GLOBALS['db'];
        $sql = "SELECT Id, Nama, Jabatan,Keterangan FROM ims_group";
        $query = $db->query($sql);
        $JumRow = $query->rowCount();
        if($JumRow > 0){
            while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                $row['Nama'] = $res['Nama'];
                $row['Keterangan'] = $res['Keterangan'];
                $row['Jumlah'] = getJumlahKaryawan1($res['Jabatan']);
                $result['data'][] = $row;
            }
        }else{
            $result['data']='';
        }
        return $result; 
    }


?>