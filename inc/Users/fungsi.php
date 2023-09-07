<?php
    function InsertLogs($msg){
        $Logs['UserId'] = $_SESSION['Id'];
        $Logs['Logs'] = $msg;
        $Logs['Modul'] = $_SESSION['page'];
        Logs($Logs);
    }

    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $data['TglCreate'] = date("Y-m-d H:i:s");
            $data['UserId'] = $_SESSION['Id'];
            $Pass = md5("ims".$data['Password']);
            $sql = "INSERT INTO ims_users SET Nama = :Nama, Jabatan = :Jabatan, Username = :Username, `Password` =:Passwords, `Level` = :Levels, TglCreate = :TglCreate,  UserId = :UserId";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
            $exc->bindParam('Jabatan', $data['Jabatan'], PDO::PARAM_STR);
            $exc->bindParam('Username', $data['Username'], PDO::PARAM_STR);
            $exc->bindParam('Passwords', $Pass, PDO::PARAM_STR);
            $exc->bindParam('Levels', $data['Level'], PDO::PARAM_STR);
            $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
            $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
            $exc->execute();
            $res['pesan'] = "Data user dengan nama <b>".$data['Nama']."</b> berhasil ditambahkan!";
            InsertLogs($res['pesan']);
            return true;
        }else{
            return false;
        }
    }

    

    function CekData($data){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT COUNT(Id) as tot FROM ims_users WHERE Username = :Username";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Username", $data['Username'], PDO::PARAM_STR);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt['tot'];
    }

    function UbahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $data['TglUpdate'] = date("Y-m-d H:i:s");
            $data['UserId'] = $_SESSION['Id'];;
            if(empty($data['Password'])){
                $sql = "UPDATE ims_users SET Nama = :Nama, Jabatan = :Jabatan,  TglUpdate = :TglUpdate,  UserId = :UserId, `Level` = :Levels  WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('Jabatan', $data['Jabatan'], PDO::PARAM_STR);
                $exc->bindParam('TglUpdate', $data['TglCreate'], PDO::PARAM_STR);
                $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_INT);
                $exc->bindParam('Levels', $data['Level'], PDO::PARAM_STR);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
            }else{
                $sql = "UPDATE ims_users SET Nama = :Nama, Jabatan = :Jabatan,  TglUpdate = :TglUpdate,  UserId = :UserId, `Level` = :Levels, `Password` = :Passwords  WHERE Id = :Id";
                $Pass = md5("ims".$data['Password']);
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('Jabatan', $data['Jabatan'], PDO::PARAM_STR);
                $exc->bindParam('TglUpdate', $data['TglCreate'], PDO::PARAM_STR);
                $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_INT);
                $exc->bindParam('Levels', $data['Level'], PDO::PARAM_STR);
                $exc->bindParam('Passwords', $Pass, PDO::PARAM_STR);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
            }
            $res['pesan'] = "Data user dengan nama <b>".$data['Nama']."</b> berhasil update!";
            InsertLogs($res['pesan']);
            return true;
        }else{
            return false;
        }
    }

    function HapusData($Id){
        $koneksi = $GLOBALS['db'];
        if(!empty($Id)){
            $sql = "DELETE FROM ims_users WHERE Id = :Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Id', $Id, PDO::PARAM_INT);
            $exc->execute();
            $res['pesan'] = "Data user dengan nama <b>".$data['Nama']."</b> berhasil dihapus!";
            InsertLogs($res['pesan']);
            return true;
        }else{
            return false;
        }
    }

    function UpdateStatus($Id,$Statuss){
        $koneksi = $GLOBALS['db'];
        $Status = ["Dinonaktifkan","Diaktifkan"];
        $rs = ShowData($Id);
        if(!empty($Id) && (!empty($Statuss) || $Statuss == "0")){
            $sql = "UPDATE ims_users SET `Status` = :Statuss WHERE Id = :Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam("Statuss", $Statuss, PDO::PARAM_STR);
            $exc->bindParam("Id", $Id, PDO::PARAM_INT);
            $exc->execute();
            $res['pesan'] = "Data user dengan nama <b>".$rs['Nama']."</b> ".$Status[$Statuss]." !";
            InsertLogs($res['pesan']);
            return true;
        }else{
            return true;
        }
    }

    function ShowData($Id){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Id, Nama, Jabatan, Username,`Level` FROM ims_users WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    
    

    

?>