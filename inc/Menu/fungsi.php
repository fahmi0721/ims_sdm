<?php
    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $data['TglCreate'] = date("Y-m-d H:i:s");
            $data['UserId'] = $_SESSION['Id'];
            $data['ItemRoot'] = empty($data['ItemRoot']) ? 'Menu-Root' : $data['ItemRoot'];
            $sql = "INSERT INTO ims_menu SET NamaMenu = :NamaMenu, Direktori = :Direktori, Icon = :Icon, ItemRoot =:ItemRoot, TglCreate = :TglCreate,  UserId = :UserId";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('NamaMenu', $data['NamaMenu'], PDO::PARAM_STR);
            $exc->bindParam('Direktori', $data['Direktori'], PDO::PARAM_STR);
            $exc->bindParam('Icon', $data['Icon'], PDO::PARAM_STR);
            $exc->bindParam('ItemRoot', $data['ItemRoot'], PDO::PARAM_STR);
            $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
            $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
            $exc->execute();
            return true;
        }else{
            return false;
        }
    }

    function UbahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $data['TglUpdate'] = date("Y-m-d H:i:s");
            $data['UserId'] = $_SESSION['Id'];
            $data['ItemRoot'] = empty($data['ItemRoot']) ? 'Menu-Root' : $data['ItemRoot'];
            $sql = "UPDATE ims_menu SET NamaMenu = :NamaMenu, Direktori = :Direktori, Icon = :Icon, ItemRoot =:ItemRoot, TglCreate = :TglCreate,  UserId = :UserId WHERE Id = :Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('NamaMenu', $data['NamaMenu'], PDO::PARAM_STR);
            $exc->bindParam('Direktori', $data['Direktori'], PDO::PARAM_STR);
            $exc->bindParam('Icon', $data['Icon'], PDO::PARAM_STR);
            $exc->bindParam('ItemRoot', $data['ItemRoot'], PDO::PARAM_STR);
            $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
            $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
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
            $sql = "DELETE FROM ims_menu WHERE Id = :Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Id', $Id, PDO::PARAM_INT);
            $exc->execute();
            return true;
        }else{
            return false;
        }
    }

    function CheckDataUserAprovel($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $sql = "SELECT COUNT(Id) as tot FROM ims_list_approval WHERE IdUser = :IdUser AND IdMenu = :IdMenu";
            $exec = $koneksi->prepare($sql);
            $exec->bindParam("IdUser", $data['IdUser'], PDO::PARAM_INT);
            $exec->bindParam("IdMenu", $data['IdMenu'], PDO::PARAM_INT);
            $exec->execute();
            if($exec){
                $r = $exec->fetch(PDO::FETCH_ASSOC);
                return $r['tot'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function UbahStatus($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $data['UserId'] = $_SESSION['Id'];
            $sql = "UPDATE ims_menu SET `Status` = :Statuss, UserId = :UserId WHERE Id = :Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Statuss', $data['Status'], PDO::PARAM_STR);
            $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
            $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
            $exc->execute();
            return true;
        }else{
            return false;
        }
    }

    function GetMenuItemRoot($ItemRoot){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT NamaMenu FROM ims_menu WHERE Direktori = :ItemRoot";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("ItemRoot", $ItemRoot, PDO::PARAM_STR);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt['NamaMenu'];
    }

    function ShowData($Id){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Id, NamaMenu, Icon, ItemRoot, Direktori FROM ims_menu WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        $dt['MenuItemRoot'] = GetMenuItemRoot($dt['ItemRoot']);
        return $dt;
    }

    function ShowListApprovel($Id){
        $koneksi = $GLOBALS['db'];
        $posisi = array("Pertama","Kedua","Ketiga","Keempat");
        try{    
            $sql = "SELECT a.Nama, b.Posisi,b.Id FROM ims_users a INNER JOIN ims_list_approval b ON a.Id = b.IdUser WHERE b.IdMenu = :Id ORDER BY b.Id ASC";
            $exec = $koneksi->prepare($sql);
            $exec->bindParam("Id", $Id, PDO::PARAM_INT);
            $exec->execute();
            $rows = $exec->rowCount();
            $data =array();
            $res =array();
            if($rows > 0){
                $data['status'] = 0;
                $data['rows'] = $rows;
                while($r = $exec->fetch(PDO::FETCH_ASSOC)){
                    $res['Nama'] = $r['Nama'];
                    $res['Posisi'] = $posisi[$r['Posisi']];
                    $res['Id'] = $r['Id'];
                    $data['item'][] = $res;
                }
            }else{
                $data['status'] = 0;
                $data['rows'] = $rows;
                $data['item'] = [];
            }
        }catch(PDOException $e){
            $data['status'] = 1;
            $data['pesan'] = $e->getMessage();
        }

        return $data;
    }

    function HapusListApproval($Id){
        $koneksi = $GLOBALS['db'];
        $sql = "DELETE FROM ims_list_approval WHERE Id = :Id";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("Id", $Id, PDO::PARAM_INT);
        $exec->execute();
        if($exec){
            return true;
        }else{
            return false;
        }
    }

    function GetEndPosisi($IdMenu){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Posisi FROM ims_list_approval WHERE IdMenu = :IdMenu";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("IdMenu", $IdMenu, PDO::PARAM_INT);
        $exec->execute();
        $row = $exec->rowCount();
        $r = $exec->fetch(PDO::FETCH_ASSOC);
        if($row > 0){
            $result = $r['Posisi'] + 1;
        }else{
            $result = 0;
        }

        return $result;
    }


    function InsertListApprovel($data){
        if(is_array($data)){
            $koneksi = $GLOBALS['db'];
            $Posisi = GetEndPosisi($data['IdMenu']);
            $sql = "INSERT INTO ims_list_approval SET IdMenu =:IdMenu, IdUser = :IdUser, Posisi = :Posisi";
            $exec = $koneksi->prepare($sql);
            $exec->bindParam('IdMenu', $data['IdMenu'], PDO::PARAM_INT);
            $exec->bindParam('IdUser', $data['IdUser'], PDO::PARAM_INT);
            $exec->bindParam('Posisi', $Posisi, PDO::PARAM_INT);
            $exec->execute();
            if($exec){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    
    

    

?>