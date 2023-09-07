<?php

    function Filter($datas){
        $res = array();
        foreach ($datas as $key => $value) {
            if(!empty($value)){
                $res[] = $key." = '".$value."'";
            }
        }
        return COUNT($res) > 0 ? "WHERE ".implode(" AND ",$res) : "";
    }
    function DetailData($data){
        $db = $GLOBALS['db'];
        $result = array();
        $row = array(); 
        if(is_array($data)){
            $Search['Nama'] = $data['Search'];
            $Page = $data['Page'];
            $RowPage = $data['RowPage'];
            $offset=($Page - 1) * $RowPage;
            $no=$offset+1;
            $FilterSearch = Filter($Search);
            $sql = "SELECT Urutan, Id, Nama, Direktori, Flag FROM ims_dashboard $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY Urutan ASC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            $Flag = array("0"=>"<center><label class='label label-danger'>Tidak Aktif<label></center>","1"=>"<center><label class='label label-success'>Aktif<label></center>");
            if($JumRow > 0){
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $aksi = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a>";
                    $row['No'] = $no;
                    $row['Nama'] = $res['Nama'];
                    $row['Flag'] = $Flag[$res['Flag']];
                    $row['Direktori'] = $res['Direktori'];
                    $row['Urutan'] = $res['Urutan'];
                    $row['Aksi'] = "<div class='btn-group'>".$aksi."</div>";
                    $result['data'][] = $row;
                    $no++;
                }
                $result['data_last'] = $no -1;
                return $result; 
            }else{
                $result['data']="";
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

    function ShowDataUrutan($Urutan){
        $query = $GLOBALS['db']->query("SELECT Id FROM ims_dashboard WHERE Urutan = '$Urutan'");
        $row = $query->rowCount();
        if($row > 0){
            $rUrutan = $query->fetch(PDO::FETCH_ASSOC);
            return $rUrutan['Id'];
        }else{
            return "kosong";
        }
    }
    function CekDataDs(){
        $query = $GLOBALS['db']->query("SELECT COUNT(Id) as tot FROM ims_dashboard");
        $rUrutan = $query->fetch(PDO::FETCH_ASSOC);
        return $rUrutan['tot'];
    }

    function UpdateUrutan($st,$Urutan,$IdLama=null){
        if($st == 0){
            if(CekDataDs() > 0){
                $Id = ShowDataUrutan($Urutan);
                if(!empty($Id)){
                    $UrutanAkhir = CekDataDs() + 1;
                    $GLOBALS['db']->query("UPDATE ims_dashboard SET Urutan = $UrutanAkhir WHERE Id = '$Id'");
                }
                return true;
            }else{
                return true;
            }
        }else{
            $Id = ShowDataUrutan($Urutan);
            $dt_lama = ShowData($IdLama);
            $GLOBALS['db']->query("UPDATE ims_dashboard SET Urutan = ".$dt_lama['Urutan']." WHERE Id = '$Id'");
            return true;
        }
    }


    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $data['TglCreate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                UpdateUrutan(0,$data['Urutan']);
                $sql = "INSERT INTO ims_dashboard SET Nama = :Nama, Direktori = :Direktori, Flag = :Flag, Urutan = :Urutan,  TglCreate = :TglCreate,  UserId = :UserId"; 
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('Direktori', $data['Direktori'], PDO::PARAM_STR);
                $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                $exc->bindParam('Urutan', $data['Urutan'], PDO::PARAM_STR);
                $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil menambah data Dashboard";
                $rMsg = $msg['pesan'];
                $msg['status'] = "sukses";
                InsertLogs($rMsg);
                return $msg;
            } catch (PDOException $e) {
                $msg['pesan'] = $e->errorMessage();
                $msg['status'] = "error";
                InsertLogs($msg['pesan']);
                return $msg;
            }
        }else{
            $msg['pesan'] = "Pengiriman data ke server gagal";
            $msg['status'] = "gagal";
            InsertLogs($msg['pesan']);
            return $msg;
        }
    }
    
    function UbahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                UpdateUrutan(1,$data['Urutan'],$data['Id']);
                $data['TglUpdate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                $sql = "UPDATE ims_dashboard SET Nama = :Nama, Direktori = :Direktori, Flag = :Flag, Urutan = :Urutan,  TglUpdate = :TglUpdate WHERE Id = :Id"; 
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('Direktori', $data['Direktori'], PDO::PARAM_STR);
                $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                $exc->bindParam('Urutan', $data['Urutan'], PDO::PARAM_STR);
                $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil mengubah data Dashboard";
                $rMsg = $msg['pesan'];
                $msg['status'] = "sukses";
                InsertLogs($rMsg);
                return $msg;
            } catch (PDOException $e) {
                $msg['pesan'] = $e->getMessage();
                $msg['status'] = "error";
                InsertLogs($msg['pesan']);
                return $msg;
            }
        }else{
            $msg['pesan'] = "Pengiriman data ke server gagal";
            $msg['status'] = "gagal";
            InsertLogs($msg['pesan']);
            return $msg;
        }
    }

    function HapusData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $sql = "DELETE FROM ims_dashboard WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                $msg['pesan'] = "Berhasil menghapus data Dashboard </b>";
                $rMsg = $msg['pesan'];
                $msg['status'] = "sukses";
                InsertLogs($rMsg);
                return $msg;
             } catch (PDOException $e) {
                $msg['pesan'] = $e->errorMessage();
                $msg['status'] = "error";
                InsertLogs($msg['pesan']);
                return $msg;
            }
        }else{
            $msg['pesan'] = "Pengiriman data ke server gagal";
            $msg['status'] = "gagal";
            InsertLogs($msg['pesan']);
            return $msg;
        }
    }

    function ShowData($Id){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT * FROM ims_dashboard WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    function Urutan(){
        $result = array();
        $sql = "SELECT Urutan FROM ims_dashboard ORDER BY Urutan ASC";
        $query = $GLOBALS['db']->query($sql);
        if($query->rowCount() > 0){
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $result['data'][] = $r;
            }
            $Akhir = count($result['data']) + 1;
            $result['data'][count($result['data'])]['Urutan'] = $Akhir;
            
        }else{
            $result['data'][0]['Urutan'] = 1;
        }
        return $result;
    }

    function UrutanUpdate(){
        $result = array();
        $sql = "SELECT Urutan FROM ims_dashboard ORDER BY Urutan ASC";
        $query = $GLOBALS['db']->query($sql);
        if($query->rowCount() > 0){
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $result['data'][] = $r;
            }
            
        }else{
            $result['data'][0]['Urutan'] = 1;
        }
        return $result;
    }


    

?>