<?php

    function Filter($str){
        if(empty($str)){
            return "";
        }else{
            return "WHERE (a.NoDokumen LIKE '%".$str."%' OR b.Nama LIKE '%".$str."%')";
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
            $sql = "SELECT a.Id, a.NoKtp, a.NoDokumen, a.Kategori, a.File, a.Tmt, b.Nama as TK FROM ims_sk_pemberhentian a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp  $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY a.Id DESC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            $Kategori = array("Pemberhentian","Memundurkan Diri","Pensiun");
            if($JumRow > 0){
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $extensi = getExtensi($res['File']);
                    $icon = $extensi == "pdf" ? "fa-file-pdf-o" : "fa-picture-o";
                    if(empty($res['File'])){
                        $aksi = "";
                    }else{
                        if($extensi == "pdf"){
                             $aksi = "<a href='File/SkPemberhentian/".$res['File']."' class='btn btn-xs btn-success' data-toggle='tooltip' title='Lihat Dokumen' target='blank'><i class='fa ".$icon."'></i></a> ";
                             
                        }else{
                            $aksi = "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Lihat Dokumen' onclick=\"Crud('".$res['File']."#".$extensi."', 'file')\"><i class='fa ".$icon."'></i></a> ";
                        }
                    }
                    
                    $aksi .= "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
                    $row['No'] = $no;
                    $row['TK'] = $res['TK']."<br><small><b>No KTP : ".$res['NoKtp']."</b></small>";
                    $row['Dokumen'] = $res['NoDokumen']."<br><small><b>TMT : ".tgl_indo($res['Tmt'])."</b></small>";
                    $row['Kategori'] = $Kategori[$res['Kategori']];
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
            $qr = $GLOBALS['db']->query("SELECT * FROM ims_sk_pemberhentian WHERE NoKtp = '$NoKtp' ORDER BY Tmt DESC, TglCreate DESC LIMIT 1");
            $rt = $qr->fetch(PDO::FETCH_ASSOC);
            if(empty($rt)){
                $rs = null;
            }else{
                $rs = json_encode($rt);
                $rs = base64_encode($rs);
            }
            $sql = "UPDATE ims_master_biodata SET  SpkKeluar = '$rs' WHERE NoKtp = '$NoKtp'";
            $GLOBALS['db']->query($sql); 
            return true;
        } catch (PDOException $e) {
            return $e->errorMessage();
        }
    }

    function UpdateDataMasterTenagaKerja($NoKtp, $ST){
        try {
            $Flag = $ST == "delete" ? 1 : 0;
            $sql = "UPDATE ims_master_tenaga_kerja SET Flag = :Flag WHERE NoKtp = :NoKtp";
            $query = $GLOBALS['db']->prepare($sql);
            $query->bindParam('Flag', $Flag, PDO::PARAM_STR);
            $query->bindParam('NoKtp', $NoKtp, PDO::PARAM_STR);
            $query->execute();
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
                        $sql = "INSERT INTO ims_sk_pemberhentian SET NoKtp = :NoKtp, Tmt = :Tmt,NoDokumen = :NoDokumen,`File` = :Files, Kategori = :Kategori, Keterangan = :Keterangan,  TglCreate = :TglCreate,  UserId = :UserId";
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                        $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                        $exc->bindParam('Files', $File, PDO::PARAM_STR);
                        $exc->bindParam('Kategori', $data['Kategori'], PDO::PARAM_STR);
                        $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                        $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                        $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil menambah data SK Pemberhentian";
                        $rMsg = "Berhasil menambah data SK Pemberhentian dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        UpdateMasterBiodata($data['NoKtp']);
                        UpdateDataMasterTenagaKerja($data['NoKtp'],"insert");
                        return $msg;
                    }else{
                        return $Validasi;
                    }
                }else{
                    $sql = "INSERT INTO ims_sk_pemberhentian SET NoKtp = :NoKtp, Tmt = :Tmt,NoDokumen = :NoDokumen,Kategori = :Kategori, Keterangan = :Keterangan,  TglCreate = :TglCreate,  UserId = :UserId";
                    $exc = $koneksi->prepare($sql);
                    $exc = $koneksi->prepare($sql);
                    $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                    $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                    $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                    $exc->bindParam('Kategori', $data['Kategori'], PDO::PARAM_STR);
                    $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                    $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                    $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                    $exc->execute();
                    $msg['pesan'] = "Berhasil menambah data SK Pemberhentian";
                    $rMsg = "Berhasil menambah data SK Pemberhentian dengan no ktp <b>".$data['NoKtp']."</b>";
                    $msg['status'] = "sukses";
                    InsertLogs($rMsg);
                    UpdateMasterBiodata($data['NoKtp']);
                    UpdateDataMasterTenagaKerja($data['NoKtp'],"insert");
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
                        $Files = $Validasi['pesan'];
                        HapusFile($File['File'],$data['Dir']);
                        $sql = "UPDATE ims_sk_pemberhentian SET NoKtp = :NoKtp, Tmt = :Tmt, NoDokumen = :NoDokumen,`File` = :Files, Kategori = :Kategori, Keterangan = :Keterangan,  TglUpdate = :TglUpdate WHERE  Id = :Id";
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                        $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                        $exc->bindParam('Files', $Files, PDO::PARAM_STR);
                        $exc->bindParam('Kategori', $data['Kategori'], PDO::PARAM_STR);
                        $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                        $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                        $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil mengubah data SK Pemberhentian";
                        $rMsg = "Berhasil mengubah data SK Pemberhentian dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        UpdateMasterBiodata($data['NoKtp']);
                        UpdateDataMasterTenagaKerja($data['NoKtp'],"update");
                        return $msg;
                    }else{
                        return $Validasi;
                    }
                }else{
                    $sql = "UPDATE ims_sk_pemberhentian SET NoKtp = :NoKtp, Tmt = :Tmt,NoDokumen = :NoDokumen, Kategori = :Kategori, Keterangan = :Keterangan,  TglUpdate = :TglUpdate WHERE  Id = :Id";
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                        $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                        $exc->bindParam('Kategori', $data['Kategori'], PDO::PARAM_STR);
                        $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                        $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                        $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil mengubah data SK Pemberhentian";
                        $rMsg = "Berhasil mengubah data SK Pemberhentian dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        UpdateMasterBiodata($data['NoKtp']);
                        UpdateDataMasterTenagaKerja($data['NoKtp'],"update");
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
                $sql = "DELETE FROM ims_sk_pemberhentian WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                HapusFile($rs['File'],$data['Dir']);
                $msg['pesan'] = "Berhasil menghapus data SK Pemberhentian </b>";
                $rMsg = "Berhasil menghapus data SK Pemberhentian dengan No KTP <b>".$rs['NoKtp']."</b>";
                $msg['status'] = "sukses";
                InsertLogs($rMsg);
                UpdateMasterBiodata($rs['NoKtp']);
                UpdateDataMasterTenagaKerja($rs['NoKtp'],"delete");
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
        $sql = "SELECT * FROM ims_sk_pemberhentian WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    
    

    

?>