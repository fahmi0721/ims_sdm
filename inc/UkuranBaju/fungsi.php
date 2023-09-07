<?php

    function Filter($str){
        if(empty($str)){
            return "";
        }else{
            return "WHERE (b.Nama LIKE '%".$str."%' OR a.NoKtp LIKE '%".$str."%')";
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
            $sql = "SELECT a.NoKtp, a.Id, a.Baju, a.Celana, a.Sepatu, a.Topi, a.Ped,  b.Nama as TK FROM ims_ukuran_apd a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp  $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY a.Id DESC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            if($JumRow > 0){
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $aksi = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
                    $row['No'] = $no;
                    $row['TK'] = $res['TK']."<br><small><b>No KTP : ".$res['NoKtp']."</b></small>";
                    $row['Baju'] = $res['Baju'];
                    $row['Celana'] = $res['Celana'];
                    $row['Sepatu'] = $res['Sepatu'];
                    $row['Topi'] = $res['Topi'];
                    $row['Ped'] = $res['Ped'];
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

    function getTenagaKerja(){
        $sql = "SELECT NoKtp, Nama FROM ims_master_tenaga_kerja WHERE Flag = '1'";
        $query = $GLOBALS['db']->query($sql);
        $r = array();
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $r[] = $res;
        }
        return $r;
    }

    function UpdateMasterBiodata($NoKtp){
        try {
            $qr = $GLOBALS['db']->query("SELECT * FROM ims_ukuran_apd WHERE NoKtp = '$NoKtp' ORDER BY TglCreate DESC LIMIT 1");
            $rt = $qr->fetch(PDO::FETCH_ASSOC);
            if(empty($rt)){
                $rs = null;
            }else{
                $rs = json_encode($rt);
                $rs = base64_encode($rs);
            }
            $sql = "UPDATE ims_master_biodata SET  UkuranBaju = '$rs' WHERE NoKtp = '$NoKtp'";
            $GLOBALS['db']->query($sql); 
            return true;
        } catch (PDOException $e) {
            return $e->errorMessage();
        }
    }



    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $data['TglCreate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                $sql = "INSERT INTO ims_ukuran_apd SET NoKtp = :NoKtp, Baju = :Baju,Celana = :Celana, Sepatu = :Sepatu, Topi = :Topi, Ped = :Ped, TglCreate = :TglCreate,  UserId = :UserId";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                $exc->bindParam('Baju', $data['Baju'], PDO::PARAM_STR);
                $exc->bindParam('Celana', $data['Celana'], PDO::PARAM_STR);
                $exc->bindParam('Sepatu', $data['Sepatu'], PDO::PARAM_STR);
                $exc->bindParam('Topi', $data['Topi'], PDO::PARAM_STR);
                $exc->bindParam('Ped', $data['Ped'], PDO::PARAM_STR);
                $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil menambah data Ukuran Baju";
                $rMsg = "Berhasil menambah data Ukuran Baju dengan no ktp <b>".$data['NoKtp']."</b>";
                $msg['status'] = "sukses";
                InsertLogs($rMsg);
                UpdateMasterBiodata($data['NoKtp']);
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
                $sql = "UPDATE ims_ukuran_apd SET NoKtp = :NoKtp, Baju = :Baju, Celana = :Celana, Sepatu = :Sepatu, Topi = :Topi, Ped = :Ped, TglUpdate = :TglUpdate WHERE  Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                $exc->bindParam('Baju', $data['Baju'], PDO::PARAM_STR);
                $exc->bindParam('Celana', $data['Celana'], PDO::PARAM_STR);
                $exc->bindParam('Sepatu', $data['Sepatu'], PDO::PARAM_STR);
                $exc->bindParam('Topi', $data['Topi'], PDO::PARAM_STR);
                $exc->bindParam('Ped', $data['Ped'], PDO::PARAM_STR);
                $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                $exc->execute();
                $msg['pesan'] = "Berhasil mengubah data Ukuran Baju";
                $rMsg = "Berhasil mengubah data Ukuran Baju dengan no ktp <b>".$data['NoKtp']."</b>";
                $msg['status'] = "sukses";
                InsertLogs($rMsg);
                UpdateMasterBiodata($data['NoKtp']);
                
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
                $sql = "DELETE FROM ims_ukuran_apd WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                $msg['pesan'] = "Berhasil menghapus data Ukuran Baju </b>";
                $rMsg = "Berhasil menghapus data Ukuran Baju dengan No KTP <b>".$rs['NoKtp']."</b>";
                $msg['status'] = "sukses";
                InsertLogs($rMsg);
                UpdateMasterBiodata($rs['NoKtp']);
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
        $sql = "SELECT * FROM ims_ukuran_apd WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    
    

    

?>