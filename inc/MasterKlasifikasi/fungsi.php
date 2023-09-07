<?php

    function Filter($str){
        if(empty($str)){
            return "";
        }else{
            return "WHERE (Nama LIKE '%".$str."%')";
        }
    }

    function getJabatan($str){
        $stt = array();
        foreach($str as $st){
            $stt[] = "'".$st."'";
        }
        $strs = implode(",",$stt);
        $sql = "SELECT Kode, NamaSeksi FROM ims_master_seksi WHERE Kode IN ($strs) ORDER BY NamaSeksi ASC";
        $query = $GLOBALS['db']->query($sql);
        $row = $query->rowCount();
        $result = array();
        if($row > 0){
            $No = 1;
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $r['No'] = $No;
                $result['data'][] = $r;
                $No++;
            }
            $result['total_data'] = $row;
            return base64_encode(json_encode($result));
        }else{
            $result['total_data'] = 0;
            $result['data'] = array();
            return base64_encode(json_encode($result));
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
            $sql = "SELECT Id, Nama, Klasifikasi FROM ims_master_klasifikasi $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY Id DESC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            if($JumRow > 0){
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $Jabatan = json_decode($res['Klasifikasi'],true);
                    $Jbt = getJabatan($Jabatan);
                    $aksi = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
                    $row['No'] = $no;
                    $row['Nama'] = $res['Nama'];
                    $row['Jabatan'] = "<a href='javascript:void(0)' onclick=\"DetailJabatan('".$Jbt."')\" data-toggle='tooltip' title='klik untuk melihat detail jabatan' class='label label-info'>".count($Jabatan)." Jabatan</a>";
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

    function LoadDataSeksi(){
        $sql = "SELECT Kode, NamaSeksi FROM ims_master_seksi WHERE Flag = '1' ORDER BY NamaSeksi";
        $query = $GLOBALS['db']->query($sql);
        $row = $query->rowCount();
        $result = array();
        if($row > 0){
            $No = 1;
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                $r['No'] = $No;
                $r['aksi'] = "<input type='checkbox' name='Klasifikasi[]' class='childKlasifikasi Klasifikasi".$r['Kode']."' onclick='HitungJumlahChecked()' value='".$r['Kode']."' />";
                $result['data'][] = $r;
                $No++;
            }
            $result['total_data'] = $row;
            return $result;
        }else{
            $result['total_data'] = 0;
            $result['data'] = array();
            return $result;
        }
    }

    function InsertLogs($msg){
        $Logs['UserId'] = $_SESSION['Id'];
        $Logs['Logs'] = $msg;
        $Logs['Modul'] = $_SESSION['page'];
        Logs($Logs);
    }


    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $data['TglCreate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                $sql = "INSERT INTO ims_master_klasifikasi SET Nama = :Nama, Klasifikasi = :Klasifikasi,  TglCreate = :TglCreate";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('Klasifikasi', $data['Klasifikasi'], PDO::PARAM_STR);
                $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil menambah data master klasifikasi jabatan";
                $rMsg = "Berhasil menambah data master klasifikasi jabatan dengan nama paket klasifikasi <b>".$data['Nama']."</b>";
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
                $sql = "UPDATE ims_master_klasifikasi SET Nama = :Nama, Klasifikasi = :Klasifikasi WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('Klasifikasi', $data['Klasifikasi'], PDO::PARAM_STR);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil mengubah data master klasifikasi jabatan</b>";
                $rMsg = "Berhasil mengubah data master klasifikasi jabatan dengan nama paket <b>".$data['Nama']."</b>";
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
                $sql = "DELETE FROM ims_master_klasifikasi WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                $msg['pesan'] = "Berhasil menghapus data master klasifikasi jabatan dengan nama paket <b>".$rs['Nama']."</b>";
                $rMsg = "Berhasil menghapus data master klasifikasi jabatan dengan nama paket <b>".$rs['Nama']."</b>";
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
        $sql = "SELECT * FROM ims_master_klasifikasi WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    
    

    

?>