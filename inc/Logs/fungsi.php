<?php

    function Filter($str){
        if(is_array($str)){
            if(!empty($str['IdUser']) || !empty($str['Tgl'])){
                $data = array();
                if(!empty($str['IdUser'])){ $data[] = "IdUser = '".$str['IdUser']."'"; }
                if(!empty($str['Tgl'])){ $data[] = "DATE_FORMAT(TglCreate, '%Y-%m-%d') = '".$str['Tgl']."'"; }
                $fil = implode(" AND ",$data);
                return "WHERE ".$fil;
            }else{
                return "";    
            }
        }else{
            return "";
        }
    }


    function getNamaUser($Id){
        $sql = "SELECT Username, Nama, Jabatan FROM ims_users WHERE Id = :Id";
        $query = $GLOBALS['db']->prepare($sql);
        $query->bindParam("Id", $Id, PDO::PARAM_STR);
        $query->execute();
        $r = $query->fetch(PDO::FETCH_ASSOC);
        $User = !empty($r['Nama']) ? "<b>".$r['Nama']."</b><br><small>Username : ".$r['Username']."<br>Jabatan : ".$r['Jabatan']."</small>" : "<b>Anonym</b><br><small>Username : -<br>Jabatan : -</small>";
        return $User;
    }

    function getModul($Dir){
        $sql = "SELECT NamaMenu FROM ims_menu WHERE Direktori = :Direktori";
        $query = $GLOBALS['db']->prepare($sql);
        $query->bindParam("Direktori", $Dir, PDO::PARAM_STR);
        $query->execute();
        $r = $query->fetch(PDO::FETCH_ASSOC);
        $Modul = empty($r['NamaMenu']) ? $Dir : $r['NamaMenu'];
        return $Modul;
    }

    function DetailData($data){
        $db = $GLOBALS['db'];
        $result = array();
        $row = array(); 
        if(is_array($data)){
            $Search = array();
            $Page = $data['Page'];
            $RowPage = $data['RowPage'];
            $offset=($Page - 1) * $RowPage;
            $no=$offset+1;
            $Search['IdUser'] = $data['IdUser'] != "null" ? $data['IdUser'] : "";
            $Search['Tgl'] = $data['Tgl'];
            $FilterSearch = Filter($Search);
            $sql = "SELECT IdUser, Logs, Modul, TglCreate FROM ims_logs $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY TglCreate DESC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            if($JumRow > 0){
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $row['No'] = $no;
                    $row['NamaUser'] = getNamaUser($res['IdUser']);
                    $row['Modul'] = getModul($res['Modul']);
                    $row['Waktu'] = hari_indo($res['TglCreate'])."<br><small>Tgl : ".tgl_indo($res['TglCreate'])."<br>Jam : ".jam_indo($res['TglCreate'])."</small>";
                    $row['Logs'] = $res['Logs'];
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

    function getUser(){
        $sql = "SELECT Id, Jabatan, Nama FROM ims_users ORDER BY Nama ASC";
        $query = $GLOBALS['db']->query($sql);
        $r = array();
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $r[] = $res;
        }
        return $r;
    }


    function getExtensi($str){
        $str = strtolower($str);
        $str = explode(".", $str);
        $str = end($str);
        return $str;
    }

    function ValidasiFile($File,$Dir){
        $ArrExtensi = array("jpg","png","jpeg","pdf");
        $nama = $File['name'];
        $size = $File['size'];
        $size = round($size/1024,0);
        $tmp_name = $File['tmp_name'];
        $extensi = getExtensi($nama);
        if(in_array($extensi, $ArrExtensi)){
            
            if($size <= 2048){
                $NewName = rand(0,9999).time().".".$extensi;
                if(move_uploaded_file($tmp_name,$Dir.$NewName)){
                    $r['msg'] = "sukses";
                    $r['pesan'] = $NewName;
                    return $r;
                }else{
                    $r['msg'] = "gagal";
                    $r['pesan'] = "Terjadi kesalahan ketika mengupload file";
                    return $r;
                }
            }else{
                $r['msg'] = "gagal";
                $r['pesan'] = "Size file yang dimasukkan terlalu besar. Masukkan file dengan ukuran 2 mb";
                return $r;
            }
        }else{
            $r['msg'] = "gagal";
            $r['pesan'] = "Tipe file yang dimasukkan salah masukan file gambar dan pdf";
            return $r;
        }

    }

    function UpdateMasterBiodata($NoKtp){
        try {
            $qr = $GLOBALS['db']->query("SELECT * FROM ims_master_dplk WHERE NoKtp = '$NoKtp' ORDER BY Flag DESC, TglDaftar DESC LIMIT 1");
            $rt = $qr->fetch(PDO::FETCH_ASSOC);
            if(empty($rt)){
                $rs = null;
            }else{
                $rs = json_encode($rt);
                $rs = base64_encode($rs);
            }
            $sql = "UPDATE ims_master_biodata SET  Dplk = '$rs' WHERE NoKtp = '$NoKtp'";
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
                if(isset($data['File']) && !empty($data['File']['name'])){
                    $Validasi = ValidasiFile($data['File'],$data['Dir']);
                    
                    if( $Validasi['msg'] == "sukses"){
                        $File = $Validasi['pesan'];
                        $sql = "INSERT INTO ims_master_dplk SET NoKtp = :NoKtp, Cif = :Cif,NoAccount = :NoAccount, TglDaftar = :TglDaftar,`File` = :Files, Flag = :Flag,  TglCreate = :TglCreate,  UserId = :UserId";
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('Cif', $data['Cif'], PDO::PARAM_STR);
                        $exc->bindParam('NoAccount', $data['NoAccount'], PDO::PARAM_STR);
                        $exc->bindParam('TglDaftar', $data['TglDaftar'], PDO::PARAM_STR);
                        $exc->bindParam('Files', $File, PDO::PARAM_STR);
                        $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                        $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                        $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil menambah data DPLK";
                        $rMsg = "Berhasil menambah data DPLK dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        UpdateMasterBiodata($data['NoKtp']);
                        return $msg;
                    }else{
                        return $Validasi;
                    }
                }else{
                    $sql = "INSERT INTO ims_master_dplk SET NoKtp = :NoKtp, Cif = :Cif,NoAccount = :NoAccount, TglDaftar = :TglDaftar, Flag = :Flag,  TglCreate = :TglCreate,  UserId = :UserId";
                    $exc = $koneksi->prepare($sql);
                    $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                    $exc->bindParam('Cif', $data['Cif'], PDO::PARAM_STR);
                    $exc->bindParam('NoAccount', $data['NoAccount'], PDO::PARAM_STR);
                    $exc->bindParam('TglDaftar', $data['TglDaftar'], PDO::PARAM_STR);
                    $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                    $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                    $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                    $exc->execute();
                    $msg['pesan'] = "Berhasil menambah data DPLK";
                    $rMsg = "Berhasil menambah data DPLK dengan no ktp <b>".$data['NoKtp']."</b>";
                    $msg['status'] = "sukses";
                    InsertLogs($rMsg);
                    UpdateMasterBiodata($data['NoKtp']);
                    return $msg;
                }
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
    
    function HapusFile($File,$Dir){
        $Source = $Dir.$File;
        if(file_exists($Source) && !empty($File)){
            unlink($Source);   
            return true;
        }else{
            return true;
        }
    }

    function UbahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $File = ShowData($data['Id']);
                $data['TglUpdate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                if(isset($data['File']) && !empty($data['File']['name'])){
                    $Validasi = ValidasiFile($data['File'],$data['Dir']);
                    if( $Validasi['msg'] == "sukses"){
                        HapusFile($File['File'],$data['Dir']);
                        $Files = $Validasi['pesan'];
                        $sql = "UPDATE ims_master_dplk SET NoKtp = :NoKtp, Cif = :Cif,NoAccount = :NoAccount, TglDaftar = :TglDaftar,`File` = :Files, Flag = :Flag,  TglUpdate = :TglUpdate WHERE  Id = :Id";
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('Cif', $data['Cif'], PDO::PARAM_STR);
                        $exc->bindParam('NoAccount', $data['NoAccount'], PDO::PARAM_STR);
                        $exc->bindParam('TglDaftar', $data['TglDaftar'], PDO::PARAM_STR);
                        $exc->bindParam('Files', $Files, PDO::PARAM_STR);
                        $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                        $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                        $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil mengubah data DPLK";
                        $rMsg = "Berhasil mengubah data DPLK dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        UpdateMasterBiodata($data['NoKtp']);
                        return $msg;
                    }else{
                        return $Validasi;
                    }
                }else{
                    $sql = "UPDATE ims_master_dplk SET NoKtp = :NoKtp, Cif = :Cif,NoAccount = :NoAccount, TglDaftar = :TglDaftar, Flag = :Flag,  TglUpdate = :TglUpdate WHERE  Id = :Id";
                    $exc = $koneksi->prepare($sql);
                    $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                    $exc->bindParam('Cif', $data['Cif'], PDO::PARAM_STR);
                    $exc->bindParam('NoAccount', $data['NoAccount'], PDO::PARAM_STR);
                    $exc->bindParam('TglDaftar', $data['TglDaftar'], PDO::PARAM_STR);
                    $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                    $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                    $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                    $exc->execute();
                    $msg['pesan'] = "Berhasil mengubah data DPLK";
                    $rMsg = "Berhasil mengubah data DPLK dengan no ktp <b>".$data['NoKtp']."</b>";
                    $msg['status'] = "sukses";
                    UpdateMasterBiodata($data['NoKtp']);
                    InsertLogs($rMsg);
                    return $msg;
                }
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
                $sql = "DELETE FROM ims_master_dplk WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                HapusFile($rs['File'],$data['Dir']);
                $msg['pesan'] = "Berhasil menghapus data DPLK </b>";
                $rMsg = "Berhasil menghapus data DPLK dengan No KTP <b>".$rs['NoKtp']."</b>";
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
        $sql = "SELECT * FROM ims_master_dplk WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    
    

    

?>