<?php

    function Filter($str){
        if(empty($str)){
            return "";
        }else{
            return "WHERE (Kode LIKE '%".$str."%' OR Nama LIKE '%".$str."%')";
        }
    }

    function DetailData($data){
        $db = $GLOBALS['db'];
        $result = array();
        $row = array(); 
        if(is_array($data)){
            $Search = $data['Search'];
            $Page = $data['Page'];
            $RowPage = $data['RowPage'];
            $offset=($Page - 1) * $RowPage;
            $no=$offset+1;
            $FilterSearch = Filter($Search);
            $sql = "SELECT Id, Kode, Nama, Flag FROM ims_master_pendidikan_formal $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY Id DESC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            $Flag = array("0"=>"<center><label class='label label-danger'>Tidak Aktif<label></center>","1"=>"<center><label class='label label-success'>Aktif<label></center>");
            if($JumRow > 0){
                
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $aksi = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
                    $row['No'] = $no;
                    $row['Kode'] = $res['Kode'];
                    $row['Nama'] = $res['Nama'];
                    $row['Flag'] = $Flag[$res['Flag']];
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

    function getKode(){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT MAX(Kode) as Kode FROM ims_master_pendidikan_formal";
        $query = $koneksi->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($r['Kode'])){
            return "001";
        }else{
            return sprintf("%03d",intval($r['Kode']) + 1);
        }
    }

    

    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $data['TglCreate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                $Kode = getKode();
                $sql = "INSERT INTO ims_master_pendidikan_formal SET Kode = :Kode, Nama = :Nama, Flag = :Flag,  TglCreate = :TglCreate,  UserId = :UserId";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Kode', $Kode, PDO::PARAM_STR);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil menambah data master pendidikan formal";
                $rMsg = "Berhasil menambah data master pendidikan formal dengan nama <b>".$data['Nama']."</b>";
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
                $data['TglUpdate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                $sql = "UPDATE ims_master_pendidikan_formal SET Nama = :Nama, Flag = :Flag,  TglUpdate = :TglUpdate WHERE   Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil mengubah data master pedidikan formal</b>";
                $rMsg = "Berhasil mengubah data master pedidikan formal dengan nama pedidikan <b>".$data['Nama']."</b>";
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

    function HapusData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $rs = ShowData($data['Id']);
                $sql = "DELETE FROM ims_master_pendidikan_formal WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                $msg['pesan'] = "Berhasil menghapus data master pendidikan formal </b>";
                $rMsg = "Berhasil menghapus data master pendidikan formal dengan nama pendidikan <b>".$rs['Nama']."</b>";
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
        $sql = "SELECT * FROM ims_master_pendidikan_formal WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    
    

    

?>