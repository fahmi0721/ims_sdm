<?php

    function Filter($str){
        if(empty($str)){
            return "";
        }else{
            return "AND (NoDokumen LIKE '%".$str."%' OR b.Nama LIKE '%".$str."%' OR b.NoKtp LIKE '%".$str."%')";
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
            $sql = "SELECT a.File, a.Id, a.NoKtp, a.NoDokumen, a.KodeCabang, a.KodeDivisi, a.KodeSubDivisi, a.KodeSeksi, a.TanggalSelesai, a.TanggalMulai, a.Keterangan, b.Nama FROM ims_sk_pengangkatan a INNER JOIN ims_master_tenaga_kerja b ON a.NoKtp = b.NoKtp WHERE Kategori = '1' $FilterSearch";
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
                    $extensi = getExtensi($res['File']);
                    $icon = $extensi == "pdf" ? "fa-file-pdf-o" : "fa-picture-o";
                    if(empty($res['File'])){
                        $aksi = "";
                    }else{
                        if($extensi == "pdf"){
                             $aksi = "<a href='File/SkMutasi/".$res['File']."' class='btn btn-xs btn-success' data-toggle='tooltip' title='Lihat Dokumen' target='blank'><i class='fa ".$icon."'></i></a> ";
                             
                        }else{
                            $aksi = "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Lihat Dokumen' onclick=\"Crud('".$res['File']."#".$extensi."', 'file')\"><i class='fa ".$icon."'></i></a> ";
                        }
                    }
                    
                    $aksi .= "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
                    $row['No'] = $no;
                    $row['TK'] = $res['Nama']."<br><small><b>No KTP : ".$res['NoKtp']."</b></small>";
                    $row['NoDokumen'] = $res['NoDokumen'];
                    $row['UnitKerja'] = getNameUnitKeraja($res['KodeCabang'])."<br><small><b>Divisi : ".getNameDivisi($res['KodeDivisi'])."<br>Sub Divisi : ".getNameSubDivisi($res['KodeSubDivisi'])."<br>Seksi : ".getNameSeksi($res['KodeSeksi'])."</b></small>";
                    $row['TMT'] = "Mulai : ".$res['TanggalMulai']."<br>Selesai :".$res['TanggalSelesai'];
                    $row['Keterangan'] = $res['Keterangan'];
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

    function getNameBranch($Kode){
        $sql = "SELECT Nama  FROM ims_branch WHERE Kode = '$Kode'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['Nama'];
    }

    function getNameUnitKeraja($Kode){
        $sql = "SELECT NamaCabang as Nama FROM ims_master_cabang WHERE Kode = '$Kode'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['Nama'];
    }

    function getNameDivisi($Kode){
        $sql = "SELECT NamaDivisi as Nama FROM ims_master_divisi WHERE Kode = '$Kode'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['Nama'];
    }

    function getNameSeksi($Kode){
        $sql = "SELECT NamaSeksi as Nama FROM ims_master_seksi WHERE Kode = '$Kode'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['Nama'];
    }

    function getNameSubDivisi($Kode){
        $sql = "SELECT NamaSubDivisi as Nama FROM ims_master_subdivisi WHERE Kode = '$Kode'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['Nama'];
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

    function getBranch(){
        $sql = "SELECT Kode, Nama  FROM ims_master_branch WHERE Flag = '1' ORDER BY Nama ASC";
        $query = $GLOBALS['db']->query($sql);
        $r = array();
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $r[] = $res;
        }
        return $r;
    }

    function getUnitKerja(){
        $sql = "SELECT Kode, NamaCabang as Nama FROM ims_master_cabang WHERE Flag = '1' ORDER BY NamaCabang ASC";
        $query = $GLOBALS['db']->query($sql);
        $r = array();
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $r[] = $res;
        }
        return $r;
    }

    function getDivisi(){
        $sql = "SELECT Kode, NamaDivisi as Nama FROM ims_master_divisi WHERE Flag = '1' ORDER BY NamaDivisi ASC";
        $query = $GLOBALS['db']->query($sql);
        $r = array();
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $r[] = $res;
        }
        return $r;
    }

    function getSubDivisi(){
        $sql = "SELECT Kode, NamaSubDivisi as Nama FROM ims_master_subdivisi WHERE Flag = '1' ORDER BY NamaSubDivisi ASC";
        $query = $GLOBALS['db']->query($sql);
        $r = array();
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $r[] = $res;
        }
        return $r;
    }

    function getSeksi(){
        $sql = "SELECT Kode, NamaSeksi as Nama FROM ims_master_seksi WHERE Flag = '1' ORDER BY NamaSeksi ASC";
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
            $qr = $GLOBALS['db']->query("SELECT * FROM ims_sk_pengangkatan WHERE NoKtp = '$NoKtp' ORDER BY TanggalMulai DESC, TglCreate DESC LIMIT 1");
            $rt = $qr->fetch(PDO::FETCH_ASSOC);
            if(empty($rt)){
                $rs = null;
            }else{
                $rt['NamaBranch'] = getNameUnitKeraja($rt['KodeBranch']);
                $rt['NamaCabang'] = getNameUnitKeraja($rt['KodeCabang']);
                $rt['NamaDivisi'] = getNameDivisi($rt['KodeDivisi']);
                $rt['NamaSubDivisi'] = getNameSubDivisi($rt['KodeSubDivisi']);
                $rt['NamaSeksi'] = getNameSeksi($rt['KodeSeksi']);
                $rs = json_encode($rt);
                $rs = base64_encode($rs);
            }
            $sql = "UPDATE ims_master_biodata SET  SpkPengangkatan = '$rs', KodeBranch = '$rt[KodeBranch]', KodeCabang = '$rt[KodeCabang]', KodeDivisi = '$rt[KodeDivisi]', KodeSubDivisi = '$rt[KodeSubDivisi]', KodeSeksi = '$rt[KodeSeksi]' WHERE NoKtp = '$NoKtp'";
            $GLOBALS['db']->query($sql); 
            return true;
        } catch (PDOException $e) {
            return $e->errorMessage();
        }
    }

    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        $Kategori = 1;
        if(is_array($data)){
            try {
                $data['TglCreate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                if(isset($data['File']) && !empty($data['File']['name'])){
                    $Validasi = ValidasiFile($data['File'],$data['Dir']);
                    if( $Validasi['msg'] == "sukses"){
                        $File = $Validasi['pesan'];
                        $sql = "INSERT INTO ims_sk_pengangkatan SET NoKtp = :NoKtp, KodeBranch = :KodeBranch, KodeCabang = :KodeCabang, KodeDivisi = :KodeDivisi, KodeSubDivisi = :KodeSubDivisi, KodeSeksi = :KodeSeksi, TanggalMulai = :TanggalMulai, NoDokumen = :NoDokumen,`File` = :Files,  Keterangan = :Keterangan, Kategori = :Kategori,  TglCreate = :TglCreate,  UserId = :UserId";
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('KodeBranch', $data['KodeBranch'], PDO::PARAM_STR);
                        $exc->bindParam('KodeCabang', $data['KodeCabang'], PDO::PARAM_STR);
                        $exc->bindParam('KodeDivisi', $data['KodeDivisi'], PDO::PARAM_STR);
                        $exc->bindParam('KodeSubDivisi', $data['KodeSubDivisi'], PDO::PARAM_STR);
                        $exc->bindParam('KodeSeksi', $data['KodeSeksi'], PDO::PARAM_STR);
                        $exc->bindParam('TanggalMulai', $data['TanggalMulai'], PDO::PARAM_STR);
                        $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                        $exc->bindParam('Files', $File, PDO::PARAM_STR);
                        $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                        $exc->bindParam('Kategori', $Kategori, PDO::PARAM_STR);
                        $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                        $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil menambah data SK Mutasi";
                        $rMsg = "Berhasil menambah data SK Mutasi dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        UpdateMasterBiodata($data['NoKtp']);
                        return $msg;
                    }else{
                        return $Validasi;
                    }
                }else{
                    $sql = "INSERT INTO ims_sk_pengangkatan SET NoKtp = :NoKtp, KodeBranch = :KodeBranch, KodeCabang = :KodeCabang, KodeDivisi = :KodeDivisi, KodeSubDivisi = :KodeSubDivisi, KodeSeksi = :KodeSeksi, TanggalMulai = :TanggalMulai, NoDokumen = :NoDokumen, Keterangan = :Keterangan, Kategori = :Kategori, TglCreate = :TglCreate,  UserId = :UserId";
                    $exc = $koneksi->prepare($sql);
                    $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                    $exc->bindParam('KodeBranch', $data['KodeBranch'], PDO::PARAM_STR);
                    $exc->bindParam('KodeCabang', $data['KodeCabang'], PDO::PARAM_STR);
                    $exc->bindParam('KodeDivisi', $data['KodeDivisi'], PDO::PARAM_STR);
                    $exc->bindParam('KodeSubDivisi', $data['KodeSubDivisi'], PDO::PARAM_STR);
                    $exc->bindParam('KodeSeksi', $data['KodeSeksi'], PDO::PARAM_STR);
                    $exc->bindParam('TanggalMulai', $data['TanggalMulai'], PDO::PARAM_STR);
                    $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                    $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                    $exc->bindParam('Kategori', $Kategori, PDO::PARAM_STR);
                    $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                    $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                    $exc->execute();
                    $msg['pesan'] = "Berhasil menambah data SK Mutasi";
                    $rMsg = "Berhasil menambah data SK Mutasi dengan no ktp <b>".$data['NoKtp']."</b>";
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
                    $data['TanggalSelesai'] = empty($data['TanggalSelesai']) ? null : $data['TanggalSelesai'];
                    if( $Validasi['msg'] == "sukses"){
                        $Files = $Validasi['pesan'];
                        HapusFile($File['File'],$data['Dir']);
                        $sql = "UPDATE ims_sk_pengangkatan SET NoKtp = :NoKtp, KodeBranch = :KodeBranch, KodeCabang = :KodeCabang, KodeDivisi = :KodeDivisi, KodeSubDivisi = :KodeSubDivisi, KodeSeksi = :KodeSeksi, TanggalMulai = :TanggalMulai, TanggalSelesai = :TanggalSelesai, NoDokumen = :NoDokumen,`File` = :Files,  Keterangan = :Keterangan,  TglUpdate = :TglUpdate WHERE  Id = :Id";
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('KodeBranch', $data['KodeBranch'], PDO::PARAM_STR);
                        $exc->bindParam('KodeCabang', $data['KodeCabang'], PDO::PARAM_STR);
                        $exc->bindParam('KodeDivisi', $data['KodeDivisi'], PDO::PARAM_STR);
                        $exc->bindParam('KodeSubDivisi', $data['KodeSubDivisi'], PDO::PARAM_STR);
                        $exc->bindParam('KodeSeksi', $data['KodeSeksi'], PDO::PARAM_STR);
                        $exc->bindParam('TanggalMulai', $data['TanggalMulai'], PDO::PARAM_STR);
                        $exc->bindParam('TanggalSelesai', $data['TanggalSelesai'], PDO::PARAM_STR);
                        $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                        $exc->bindParam('Files', $Files, PDO::PARAM_STR);
                        $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                        $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                        $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil menambah data SK Mutasi";
                        $rMsg = "Berhasil menambah data SK Pengangkatan dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        UpdateMasterBiodata($data['NoKtp']);
                        return $msg;
                    }else{
                        return $Validasi;
                    }
                }else{
                    
                    $data['TanggalSelesai'] = empty($data['TanggalSelesai']) ? null : $data['TanggalSelesai'];
                    $sql = "UPDATE ims_sk_pengangkatan SET NoKtp = :NoKtp, KodeBranch = :KodeBranch, KodeCabang = :KodeCabang, KodeDivisi = :KodeDivisi, KodeSubDivisi = :KodeSubDivisi, KodeSeksi = :KodeSeksi, TanggalMulai = :TanggalMulai, TanggalSelesai = :TanggalSelesai, NoDokumen = :NoDokumen, Keterangan = :Keterangan,  TglUpdate = :TglUpdate WHERE  Id = :Id";
                    $exc = $koneksi->prepare($sql);
                    $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                    $exc->bindParam('KodeBranch', $data['KodeBranch'], PDO::PARAM_STR);
                    $exc->bindParam('KodeCabang', $data['KodeCabang'], PDO::PARAM_STR);
                    $exc->bindParam('KodeDivisi', $data['KodeDivisi'], PDO::PARAM_STR);
                    $exc->bindParam('KodeSubDivisi', $data['KodeSubDivisi'], PDO::PARAM_STR);
                    $exc->bindParam('KodeSeksi', $data['KodeSeksi'], PDO::PARAM_STR);
                    $exc->bindParam('TanggalMulai', $data['TanggalMulai'], PDO::PARAM_STR);
                    $exc->bindParam('TanggalSelesai', $data['TanggalSelesai'], PDO::PARAM_STR);
                    $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                    $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                    $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                    $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                    $exc->execute();
                    $msg['pesan'] = "Berhasil mengubah data SK Mutasi";
                    $rMsg = "Berhasil mengubah data SK Mutasi dengan no ktp <b>".$data['NoKtp']."</b>";
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

    function HapusData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $rs = ShowData($data['Id']);
                $sql = "DELETE FROM ims_sk_pengangkatan WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                HapusFile($rs['File'],$data['Dir']);
                $msg['pesan'] = "Berhasil menghapus data SK Mutasi </b>";
                $rMsg = "Berhasil menghapus data SK Mutasi dengan No KTP <b>".$rs['NoKtp']."</b>";
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
        $sql = "SELECT * FROM ims_sk_pengangkatan WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    
    

    

?>