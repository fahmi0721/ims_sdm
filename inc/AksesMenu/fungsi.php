<?php
    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $data['TglCreate'] = date("Y-m-d H:i:s");
            $data['UserId'] = $_SESSION['Id'];
            $sql = "INSERT INTO ims_menu_level SET IdMenu = :IdMenu, IdUser = :IdUser, `Status` = :Statuss, TglCreate = :TglCreate,  UserId = :UserId";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('IdMenu', $data['IdMenu'], PDO::PARAM_INT);
            $exc->bindParam('IdUser', $data['IdUser'], PDO::PARAM_INT);
            $exc->bindParam('Statuss', $data['Status'], PDO::PARAM_STR);
            $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
            $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
            $exc->execute();
            return true;
        }else{
            return false;
        }
    }

    function CekData($data){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_menu_level WHERE IdMenu = :IdMenu AND IdUser = :IdUser";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("IdMenu", $data['IdMenu'], PDO::PARAM_INT);
        $exc->bindParam("IdUser", $data['IdUser'], PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt['tot'];
    }

    function UbahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $data['TglUpdate'] = date("Y-m-d H:i:s");
            $data['UserId'] = $_SESSION['Id'];
            $sql = "UPDATE ims_menu_level SET `Status` = :Statuss, TglUpdate = :TglUpdate,  UserId = :UserId WHERE Id =:Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Statuss', $data['Status'], PDO::PARAM_STR);
            $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
            $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_INT);
            $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
            $exc->execute();
            return true;
        }else{
            return false;
        }
    }

    function HapusData($Id){
        $koneksi = $GLOBALS['db'];
        if(!empty($Id)){
            $sql = "DELETE FROM ims_menu_level WHERE Id = :Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Id', $Id, PDO::PARAM_INT);
            $exc->execute();
            return true;
        }else{
            return false;
        }
    }

    function HapusDataSemua($IdUser){
        $koneksi = $GLOBALS['db'];
        if(!empty($IdUser)){
            $sql = "DELETE FROM ims_menu_level WHERE IdUser = :IdUser";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('IdUser', $IdUser, PDO::PARAM_INT);
            $exc->execute();
            return true;
        }else{
            return false;
        }
    }
    function GetNama($IdUser){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Nama FROM ims_users WHERE Id = :IdUser";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("IdUser", $IdUser, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt['Nama'];
    }

    function GetMenu($IdMenu){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT NamaMenu FROM ims_menu WHERE Id = :IdMenu";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("IdMenu", $IdMenu, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt['NamaMenu'];
     }

    function ShowData($Id){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Id, IdMenu, IdUser, `Status` FROM ims_menu_level WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        $dt['Nama'] = GetNama($dt['IdUser']);
        $dt['NamaMenu'] = GetMenu($dt['IdMenu']);
        return $dt;
    }

    function getDeatilMenu($IdUser){
        $koneksi = $GLOBALS['db'];
        $res = array();
        $sql = "SELECT a.Id, a.Status, b.NamaMenu,a.Aktif FROM ims_menu_level a INNER JOIN ims_menu b ON a.IdMenu = b.Id WHERE a.IdUser = :IdUser";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("IdUser", $IdUser, PDO::PARAM_INT);
        $exc->execute();
        $rows = $exc->rowCount();
        while($dt = $exc->fetch(PDO::FETCH_ASSOC)){
            $btn = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Akses' onclick=\"Crud('".$dt['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> ";
            $btn .= $dt['Aktif'] == 1 ? " <a class='btn btn-xs btn-success' data-toggle='tooltip' title='Lock Akses' onclick=\"Crud('".$dt['Id']."#0', 'locked')\"><i class='fa fa-unlock'></i></a>" : " <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Unlock Akses' onclick=\"Crud('".$dt['Id']."#1', 'locked')\"><i class='fa fa-lock'></i></a>";
            $btn .= " <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Akses' onclick=\"Crud('".$dt['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
            $dt['aksi'] = $btn;
            $res['item'][] = $dt;
        }
        $res['rows'] = $rows;

        return $res;
    }

    function UpdateAktif($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $data['UserId'] = $_SESSION['Id'];
            $sql = "UPDATE ims_menu_level SET `Aktif` = :Aktif,  UserId = :UserId WHERE Id =:Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Aktif', $data['Aktif'], PDO::PARAM_STR);
            $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_INT);
            $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
            $exc->execute();
            return true;
        }else{
            return false;
        }
    }

    
    

    

?>