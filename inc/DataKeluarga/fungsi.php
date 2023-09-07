<?php

    function Filter($str){
        if(empty($str)){
            return "";
        }else{
            return "WHERE (a.Nama LIKE '%".$str."%')";
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
            $sql = "SELECT a.Nama, a.Id, a.NoKtp, a.StatusKeluarga, a.Pekerjaan, a.NoHp, a.Alamat, b.Nama as NamaTk, c.Nama as Pendidikan FROM ims_data_keluarga a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp LEFT JOIN ims_master_pendidikan_formal c ON a.KodeMaster = c.Kode $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY Id DESC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            $Status = array("Suami","Istri","Anak","Ayah","Ibu");
            if($JumRow > 0){
                
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $aksi = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
                    $row['No'] = $no;
                    $row['TK'] = $res['Nama']."<br><small><b>Nama TK : ".$res['NamaTk']."<br> No KTP TK :".$res['NoKtp']."</b></small>";
                    $row['Pendidikan'] = $res['Pendidikan'];
                    $row['Pekerjaan'] = $res['Pekerjaan'];
                    $row['NoHp'] = $res['NoHp'];
                    $row['Alamat'] = $res['Alamat'];
                    $row['Status'] = $Status[$res['StatusKeluarga']];
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

    function getTenagaKerja(){
        $sql = "SELECT NoKtp, Nama FROM ims_master_tenaga_kerja WHERE Flag = '1'";
        $query = $GLOBALS['db']->query($sql);
        $r = array();
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $r[] = $res;
        }
        return $r;
    }

    function getPendidikan(){
        $r = array();
        $sql = "SELECT Kode, Nama  FROM ims_master_pendidikan_formal WHERE Flag = '1'";
        $query = $GLOBALS['db']->query($sql);
        $row = $query->rowCount();
        if($row > 0){
            while($res = $query->fetch(PDO::FETCH_ASSOC)){
                $r['data'][] = $res;
            }
            return $r;
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
                $sql = "INSERT INTO ims_data_keluarga SET NoKtp = :NoKtp, Nama = :Nama, StatusKeluarga = :StatusKeluarga,  KodeMaster = :KodeMaster, Pekerjaan = :Pekerjaan, NoHp = :NoHp, Alamat = :Alamat,   TglCreate = :TglCreate,  UserId = :UserId";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('StatusKeluarga', $data['StatusKeluarga'], PDO::PARAM_STR);
                $exc->bindParam('KodeMaster', $data['KodeMaster'], PDO::PARAM_STR);
                $exc->bindParam('Pekerjaan', $data['Pekerjaan'], PDO::PARAM_STR);
                $exc->bindParam('NoHp', $data['NoHp'], PDO::PARAM_STR);
                $exc->bindParam('Alamat', $data['Alamat'], PDO::PARAM_STR);
                $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil menambah data keluarga ";
                $rMsg = "Berhasil menambah data keluarga dengan nama <b>".$data['Nama']."</b>";
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
                $sql = "UPDATE ims_data_keluarga SET NoKtp = :NoKtp, Nama = :Nama, StatusKeluarga = :StatusKeluarga,  KodeMaster = :KodeMaster, Pekerjaan = :Pekerjaan, NoHp = :NoHp, Alamat = :Alamat,  TglUpdate = :TglUpdate WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                $exc->bindParam('StatusKeluarga', $data['StatusKeluarga'], PDO::PARAM_STR);
                $exc->bindParam('KodeMaster', $data['KodeMaster'], PDO::PARAM_STR);
                $exc->bindParam('Pekerjaan', $data['Pekerjaan'], PDO::PARAM_STR);
                $exc->bindParam('NoHp', $data['NoHp'], PDO::PARAM_STR);
                $exc->bindParam('Alamat', $data['Alamat'], PDO::PARAM_STR);
                $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil mengubah data keluarga ";
                $rMsg = "Berhasil mengubah data keluarga dengan nama <b>".$data['Nama']."</b>";
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
                $sql = "DELETE FROM ims_data_keluarga WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                $msg['pesan'] = "Berhasil menghapus data keluarga </b>";
                $rMsg = "Berhasil menghapus data keluarga dengan nama <b>".$rs['Nama']."</b>";
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
        $sql = "SELECT * FROM ims_data_keluarga WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    
    

    

?>