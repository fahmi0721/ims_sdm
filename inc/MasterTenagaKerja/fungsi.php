<?php

    function Filter($str){
        if(empty($str)){
            return "";
        }else{
            return "WHERE (NoKtp LIKE '%".$str."%' OR Nama LIKE '%".$str."%')";
        }
    }

    function getAgama($Kode){
        $sql = "SELECT Nama FROM ims_agama WHERE Kode = '$Kode'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['Nama'];
    }

    function getJabatan($NoKtp){
        error_reporting(0);
        $sql = "SELECT FROM_BASE64(SpkPengangkatan) as dt FROM ims_master_biodata WHERE NoKtp = '$NoKtp'" ;
        $query = $GLOBALS['db']->query($sql);
        $dt = $query->fetch(PDO::FETCH_ASSOC);
        $dt = json_decode($dt['dt'],true);
        return $dt;
    }

    function getPendidikan($NoKtp){
        error_reporting(0);
        $sql = "SELECT FROM_BASE64(PendidikanFormal) as dt FROM ims_master_biodata WHERE NoKtp = '$NoKtp'" ;
        $query = $GLOBALS['db']->query($sql);
        $dt = $query->fetch(PDO::FETCH_ASSOC);
        $dt =  !empty($dt['dt']) ? json_decode($dt['dt'],true) : "";
        $pend = !empty($dt) ? $dt['NamaPendidikan'] : "belum diisi";
        return $pend;
    }

    function getNamaBank($KodeBank){
        $sql = "SELECT Nama FROM ims_master_bank WHERE Kode = '$KodeBank'";
        $query = $GLOBALS['db']->query($sql);
        $dt = $query->fetch(PDO::FETCH_ASSOC);
        // $dt =  !empty($dt['dt']) ? $dt['Nama'] : $KodeBank;
        return $dt['Nama'];
    }

    function getRekening($NoKtp){
        error_reporting(0);
        $sql = "SELECT FROM_BASE64(Rekening) as dt FROM ims_master_biodata WHERE NoKtp = '$NoKtp'" ;
        $query = $GLOBALS['db']->query($sql);
        $dt = $query->fetch(PDO::FETCH_ASSOC);
        $dt =  !empty($dt['dt']) ? json_decode($dt['dt'],true) : "";
        $rek = !empty($dt) ? "<b>".$dt['NoRek']."</b><br><small>Bank : ".getNamaBank($dt['KodeBank'])."</small>" : "belum diisi";
        return $rek;
    }

    function CekSkPemberhentian($NoKtp){
        $khusus = array("7106062411700001");
        if(!in_array($NoKtp,$khusus)){
            $sql = "SELECT COUNT(Id) as tot FROM ims_sk_pemberhentian WHERE NoKtp = '$NoKtp' GROUP BY Id";
                $query = $GLOBALS['db']->query($sql);
                $dt = $query->fetch(PDO::FETCH_ASSOC);
                $tot = !empty($dt['tot']) ? $dt['tot'] : 0;
                return $tot;
        }else{
            return 0;
        }
    }

    function ExtractJson($str){
        $str = base64_decode($str);
        $res = json_decode($str,true);
        return $res;
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
            $sql = "SELECT Nama, Id,Nik,NoKtp, TptLahir, FotoKtp, TglLahir, StatusKawin, JenisKelamin, Agama, Tmt, GolDarah,NoHp, Tmt, Alamat, Foto,Flag FROM ims_master_tenaga_kerja $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY Id DESC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            $Flag = array("0"=>"<center><label class='label label-danger'>Tidak Aktif<label></center>","1"=>"<center><label class='label label-success'>Aktif<label></center>");
            $StatusKawin = array(1 => "Belum Menikah", 2 => "Sudah Menikah", 3 => "Janda / Duda");
            if($JumRow > 0){
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $aksi = !empty($res['Foto']) ? "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Lihat Foto' onclick=\"Crud('".$res['Foto']."', 'foto')\"><i class='fa fa-image'></i></a>" : "";
                    $aksi = !empty($res['FotoKtp']) ? $aksi." <a class='btn btn-xs btn-success' data-toggle='tooltip' title='Lihat KTP' onclick=\"Crud('".$res['FotoKtp']."', 'foto2')\"><i class='fa fa-image'></i></a>" : $aksi;
                    $CekSkKeluar = CekSkPemberhentian($res['NoKtp']);
                    if($CekSkKeluar <= 0){
                        $aksi .= "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
                    }
                    $jb = getJabatan($res['NoKtp']);
                    $Mp = getPendidikan($res['NoKtp']);
                    $jb = empty($jb) ? "data belum dinput" : $jb['NamaCabang']."<br><small><b>".$jb['NamaSeksi']."</b></small>";
                    $row['No'] = $no;
                    $row['NoKtp'] = $res['NoKtp'];
                    $row['Nik'] = empty($res['Nik']) ? "-" : $res['Nik'];
                    $row['Jabatan'] = $jb;
                    $row['Rekening'] = getRekening($res['NoKtp']);
                    $row['NoHp'] = $res['NoHp'];
                    $row['Tmt'] = tgl_indo($res['Tmt']);
                    $row['Pendidikan'] = $Mp;
                    $row['Alamat'] = $res['Alamat'];
                    $row['Nama'] = $res['Nama'];
                    $row['Agama'] = getAgama($res['Agama']);
                    $row['Ttl'] = "<b>".$res['TptLahir']."</b> <br><small>".tgl_indo($res['TglLahir'])."</small>";
                    $row['JenisKelamin'] = $res['JenisKelamin'] == "L" ? "LAKI-LAKI" : "PEREMPUAN";
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

    function CekKode($NoKtp,$Id=null){
        if(!empty($Id)){
            $sql = "SELECT COUNT(Id) as tot FROM ims_master_tenaga_kerja WHERE NoKtp = '$NoKtp' AND Id != '$Id'";
            $query = $GLOBALS['db']->query($sql);
            $r = $query->fetch(PDO::FETCH_ASSOC);
            if($r['tot'] > 0){
                $pesan = "No KTP Telah Terdaftar.";
                InsertLogs($pesan);
                return false;
            }else{
                return true;
            }
        }else{
            $sql = "SELECT COUNT(Id) as tot FROM ims_master_tenaga_kerja WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            $r = $query->fetch(PDO::FETCH_ASSOC);
            if($r['tot'] > 0){
                $pesan = "No KTP Telah Terdaftar.";
                InsertLogs($pesan);
                return false;
            }else{
                return true;
            }
        }
    }

    function getExtensi($NamaGambar){
        $lower = strtolower($NamaGambar);
        $explo = explode(".",$lower);
        $extensi  = end($explo);
        return $extensi;
    }

    function ValidasiGambar($Foto,$Dir){
        
        $ExtensArr = array("jpg","jpeg","png");
        $NamaGambar = $Foto['name'];
        $TmpGambar = $Foto['tmp_name'];
        $SizeGambar = round($Foto['size']/1024,0);
        $Extensi = getExtensi($NamaGambar);
        
        
        if(!in_array($Extensi,$ExtensArr)){
            $pesan = "File yang dimsukkan bukan gambar.";
            InsertLogs($pesan);
            $r['status'] = "error";
            $r['NewName'] = $pesan;
            return $r;
        }else{
            if($SizeGambar > 2048){
                $pesan = "File yang dimsukkan terlalu besar. Maksimal file adalah 2 mb";
                InsertLogs($pesan);
                $r['status'] = "error";
                $r['NewName'] = $pesan;
                return $r;
                exit();
            }else{
                $NewName = rand(0,9999).time().".".$Extensi;
                if(!move_uploaded_file($TmpGambar,$Dir.$NewName)){
                    InsertLogs($pesan);
                    $r['status'] = "error";
                    $r['NewName'] = "Gagal Upload";
                    return $r;
                    exit();
                }else{
                    $r['status'] = "sukses";
                    $r['NewName'] = $NewName;
                    return $r;
                }
            }
        }
    }

    function TambahMasterBiodata($NoKtp,$Biodata){
        try {
            $Flag = 1;
            $sql = "INSERT INTO ims_master_biodata SET NoKtp = '$NoKtp', Biodata = '$Biodata', Flag = '$Flag'";
            $GLOBALS['db']->query($sql); 
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }

    function UpdateMasterBiodata($NoKtp){
        try {
            $r = $GLOBALS['db']->query("SELECT * FROM ims_master_tenaga_kerja WHERE NoKtp = '$NoKtp'")->fetch(PDO::FETCH_ASSOC);
            $rs = json_encode($r);
            $rs = base64_encode($rs);
            $sql = "UPDATE ims_master_biodata SET  Biodata = '$rs', Flag = '$r[Flag]' WHERE NoKtp = '$NoKtp'";
            $GLOBALS['db']->query($sql); 
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $data['TglCreate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                if(CekKode($data['NoKtp']) == true){
                    if(isset($data['Foto']) && !empty($data['Foto']['name'])){
                        $Img = ValidasiGambar($data['Foto'],$data['Dir']);
                        $Ktp = ValidasiGambar($data['Ktp'],$data['Dir2']);
                        $FotoKtp = $Ktp['status'] == "sukses" ? $Ktp['NewName'] : "";
                        if($Ktp['status'] != "sukses" && !empty($data['Ktp']['name'])) { 
                            $msg['pesan'] = "Error-File KTP : ".$Ktp['NewName'];
                            $msg['status'] = "gagal";
                            return $msg;
                        } 
                        if($Img['status'] == "sukses"){
                            $sql = "INSERT INTO ims_master_tenaga_kerja SET Nama = :Nama, NoKtp = :NoKtp, TptLahir = :TptLahir, TglLahir = :TglLahir, StatusKawin = :StatusKawin, JenisKelamin = :JenisKelamin, Agama = :Agama, Npwp = :Npwp, GolDarah = :GolDarah, NoHp = :NoHp, Alamat = :Alamat, Tmt = :Tmt, Foto = :Foto, FotoKtp = :FotoKtp,  Flag = :Flag,  TglCreate = :TglCreate,  UserId = :UserId"; 
                            $exc = $koneksi->prepare($sql);
                            $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                            $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                            $exc->bindParam('TptLahir', $data['TptLahir'], PDO::PARAM_STR);
                            $exc->bindParam('TglLahir', $data['TglLahir'], PDO::PARAM_STR);
                            $exc->bindParam('StatusKawin', $data['StatusKawin'], PDO::PARAM_STR);
                            $exc->bindParam('JenisKelamin', $data['JenisKelamin'], PDO::PARAM_STR);
                            $exc->bindParam('Agama', $data['Agama'], PDO::PARAM_STR);
                            $exc->bindParam('Npwp', $data['Npwp'], PDO::PARAM_STR);
                            $exc->bindParam('GolDarah', $data['GolDarah'], PDO::PARAM_STR);
                            $exc->bindParam('NoHp', $data['NoHp'], PDO::PARAM_STR);
                            $exc->bindParam('Alamat', $data['Alamat'], PDO::PARAM_STR);
                            $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                            $exc->bindParam('Foto', $Img['NewName'], PDO::PARAM_STR);
                            $exc->bindParam('FotoKtp', $FotoKtp, PDO::PARAM_STR);
                            $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                            $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                            $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                            $exc->execute();
                            $msg['pesan'] = "Berhasil menambah data master Tenaga Kerja";
                            $rMsg = "Berhasil menambah data master paket dengan nama tenaga kerja <b>".$data['Nama']."</b>";
                            $msg['status'] = "sukses";
                            InsertLogs($rMsg);
                            unset($data['Foto']);
                            unset($data['Dir']);
                            unset($data['Dir2']);
                            unset($data['Ktp']);
                            $data['File'] = $Img['NewName'];
                            $data['Agama'] = getAgama($data['Agama']);
                            $Excripc = json_encode($data);
                            $Excripc = base64_encode($Excripc);
                            TambahMasterBiodata($data['NoKtp'],$Excripc);
                            return $msg;
                        }else{
                            if($Img['status'] != "sukses") { 
                                $msg['pesan'] = "Error-File Foto : ".$Img['NewName'];
                                $msg['status'] = "gagal";
                                return $msg;
                            } 
                        }
                    }else{
                        $Ktp = ValidasiGambar($data['Ktp'],$data['Dir2']);
                        $FotoKtp = $Ktp['status'] == "sukses" ? $Ktp['NewName'] : NULL;
                        if($Ktp['status'] != "sukses") { 
                            $msg['pesan'] = "Error-File KTP : ".$Ktp['NewName'];
                            $msg['status'] = "gagal";
                            return $msg;
                        }
                        $sql = "INSERT INTO ims_master_tenaga_kerja SET Nama = :Nama, NoKtp = :NoKtp, TptLahir = :TptLahir, TglLahir = :TglLahir, StatusKawin = :StatusKawin, JenisKelamin = :JenisKelamin, Agama = :Agama, Npwp = :Npwp, GolDarah = :GolDarah, NoHp = :NoHp, Alamat = :Alamat, Tmt = :Tmt, Flag = :Flag, FotoKtp = :FotoKtp,  TglCreate = :TglCreate,  UserId = :UserId"; 
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('TptLahir', $data['TptLahir'], PDO::PARAM_STR);
                        $exc->bindParam('TglLahir', $data['TglLahir'], PDO::PARAM_STR);
                        $exc->bindParam('StatusKawin', $data['StatusKawin'], PDO::PARAM_STR);
                        $exc->bindParam('JenisKelamin', $data['JenisKelamin'], PDO::PARAM_STR);
                        $exc->bindParam('Agama', $data['Agama'], PDO::PARAM_STR);
                        $exc->bindParam('Npwp', $data['Npwp'], PDO::PARAM_STR);
                        $exc->bindParam('GolDarah', $data['GolDarah'], PDO::PARAM_STR);
                        $exc->bindParam('NoHp', $data['NoHp'], PDO::PARAM_STR);
                        $exc->bindParam('Alamat', $data['Alamat'], PDO::PARAM_STR);
                        $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                        $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                        $exc->bindParam('FotoKtp', $FotoKtp, PDO::PARAM_STR);
                        $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                        $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil menambah data master Tenaga Kerja";
                        $rMsg = "Berhasil menambah data master paket dengan nama tenaga kerja <b>".$data['Nama']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        unset($data['Foto']);
                        unset($data['Dir']);
                        $data['Agama'] = getAgama($data['Agama']);
                        $Excripc = json_encode($data);
                        $Excripc = base64_encode($Excripc);
                        TambahMasterBiodata($data['NoKtp'],$Excripc);
                        return $msg;
                    }
                }else{
                    $msg['status'] = "gagal";
                    $msg['pesan'] = "No KTP yang di entri telah di gunakan.";
                    return $msg;
                }
                
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
    
    function HapusFile($Img,$Dir){
        $Source = $Dir.$Img;
        if(!empty($Img) && file_exists($Source)){
            unlink($Source);
        }else{
            
        }
    }

    function LoadDataLama($Id){
        $sql = "SELECT NoKtp FROM ims_master_tenaga_kerja WHERE Id = '$Id'";
        $query = $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['NoKtp'];
    }

    function UbahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            $NoKtpLama = LoadDataLama($data['Id']);
            try {
                $data['TglUpdate'] = date("Y-m-d H:i:s");
                $data['UserId'] = $_SESSION['Id'];
                if(CekKode($data['NoKtp'],$data['Id']) == true){
                    if(isset($data['Foto']) && !empty($data['Foto']['name'])){
                        $dtLama = ShowData($data['Id']);
                        $Img = ValidasiGambar($data['Foto'],$data['Dir']);
                        if(!empty($data['Ktp']['name'])){
                            $Ktp = ValidasiGambar($data['Ktp'],$data['Dir2']);
                            $FotoKtp = $Ktp['status'] == "sukses" ? $Ktp['NewName'] : "";
                            if($Ktp['status'] === "sukses") { HapusFile($dtLama['FotoKtp'],$data['Dir2']); } 
                            if($Ktp['status'] != "sukses") { 
                                $msg['pesan'] = "Error-File KTP : ".$Ktp['NewName'];
                                $msg['status'] = "gagal";
                                return $msg;
                            } 
                        }else{
                            $FotoKtp = $dtLama['FotoKtp'];
                        }
                        if($Img['status'] == "sukses"){
                            HapusFile($dtLama['Foto'],$data['Dir']);
                            $sql = "UPDATE ims_master_tenaga_kerja SET Nama = :Nama, NoKtp = :NoKtp, TptLahir = :TptLahir, TglLahir = :TglLahir, StatusKawin = :StatusKawin, JenisKelamin = :JenisKelamin, Agama = :Agama, Npwp = :Npwp, GolDarah = :GolDarah, NoHp = :NoHp, Alamat = :Alamat, Tmt = :Tmt, Foto = :Foto, FotoKtp = :FotoKtp, Flag = :Flag,  TglUpdate = :TglUpdate WHERE Id = :Id"; 
                            $exc = $koneksi->prepare($sql);
                            $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                            $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                            $exc->bindParam('TptLahir', $data['TptLahir'], PDO::PARAM_STR);
                            $exc->bindParam('TglLahir', $data['TglLahir'], PDO::PARAM_STR);
                            $exc->bindParam('StatusKawin', $data['StatusKawin'], PDO::PARAM_STR);
                            $exc->bindParam('JenisKelamin', $data['JenisKelamin'], PDO::PARAM_STR);
                            $exc->bindParam('Agama', $data['Agama'], PDO::PARAM_STR);
                            $exc->bindParam('Npwp', $data['Npwp'], PDO::PARAM_STR);
                            $exc->bindParam('GolDarah', $data['GolDarah'], PDO::PARAM_STR);
                            $exc->bindParam('NoHp', $data['NoHp'], PDO::PARAM_STR);
                            $exc->bindParam('Alamat', $data['Alamat'], PDO::PARAM_STR);
                            $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                            $exc->bindParam('Foto', $Img['NewName'], PDO::PARAM_STR);
                            $exc->bindParam('FotoKtp', $FotoKtp, PDO::PARAM_STR);
                            $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                            $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                            $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                            $exc->execute();
                            $msg['pesan'] = "Berhasil mengubah data master Tenaga Kerja";
                            $rMsg = "Berhasil mengubah data master paket dengan nama tenaga kerja <b>".$data['Nama']."</b>";
                            $msg['status'] = "sukses";
                            InsertLogs($rMsg);
                            UpdateDataPendukung($data['NoKtp'],$NoKtpLama);
                            UpdateMasterBiodata($data['NoKtp']);
                            return $msg;
                        }else{
                            if($Img['status'] != "sukses") { 
                                $msg['pesan'] = "Error-File Foto : ".$Img['NewName'];
                                $msg['status'] = "gagal";
                                return $msg;
                            } 
                        }
                    }else{
                        $dtLama = ShowData($data['Id']);
                        if(!empty($data['Ktp']['name'])){
                            $Ktp = ValidasiGambar($data['Ktp'],$data['Dir2']);
                            $FotoKtp = $Ktp['status'] == "sukses" ? $Ktp['NewName'] : "";
                            if($Ktp['status'] === "sukses") { HapusFile($dtLama['FotoKtp'],$data['Dir2']); } 
                            if($Ktp['status'] != "sukses") { 
                                $msg['pesan'] = "Error-File KTP : ".$Ktp['NewName'];
                                $msg['status'] = "gagal";
                                return $msg;
                            } 
                        }else{
                            $FotoKtp = $dtLama['FotoKtp'];
                        }
                        $sql = "UPDATE ims_master_tenaga_kerja SET Nama = :Nama, NoKtp = :NoKtp, TptLahir = :TptLahir, TglLahir = :TglLahir, StatusKawin = :StatusKawin, JenisKelamin = :JenisKelamin, Agama = :Agama, Npwp = :Npwp, GolDarah = :GolDarah, NoHp = :NoHp, Alamat = :Alamat, Tmt = :Tmt, Flag = :Flag, FotoKtp = :FotoKtp,  TglUpdate = :TglUpdate WHERE Id = :Id"; 
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('Nama', $data['Nama'], PDO::PARAM_STR);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('TptLahir', $data['TptLahir'], PDO::PARAM_STR);
                        $exc->bindParam('TglLahir', $data['TglLahir'], PDO::PARAM_STR);
                        $exc->bindParam('StatusKawin', $data['StatusKawin'], PDO::PARAM_STR);
                        $exc->bindParam('JenisKelamin', $data['JenisKelamin'], PDO::PARAM_STR);
                        $exc->bindParam('Agama', $data['Agama'], PDO::PARAM_STR);
                        $exc->bindParam('Npwp', $data['Npwp'], PDO::PARAM_STR);
                        $exc->bindParam('GolDarah', $data['GolDarah'], PDO::PARAM_STR);
                        $exc->bindParam('NoHp', $data['NoHp'], PDO::PARAM_STR);
                        $exc->bindParam('Alamat', $data['Alamat'], PDO::PARAM_STR);
                        $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                        $exc->bindParam('Flag', $data['Flag'], PDO::PARAM_STR);
                        $exc->bindParam('FotoKtp', $FotoKtp, PDO::PARAM_STR);
                        $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                        $exc->bindParam('Id', $data['Id'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "Berhasil mengubah data master Tenaga Kerja";
                        $rMsg = "Berhasil mengubah data master paket dengan nama tenaga kerja <b>".$data['Nama']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        UpdateDataPendukung($data['NoKtp'],$NoKtpLama);
                        UpdateMasterBiodata($data['NoKtp']);
                        return $msg;
                    }
                }else{
                    $msg['status'] = "gagal";
                    $msg['pesan'] = "No KTP yang di entri telah di gunakan.";
                    return $msg;
                }
                
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

    function HapusFileDataPendukung($File,$Dir){
        $Source = $Dir.$File;
        if(file_exists($Source) && $File != ""){
            unlink($Source);
            return true;
        }
        return true;
    }

    function HapusDataDplk($NoKtp){
        try {
            $sql = "SELECT Id, `File` FROM ims_master_dplk WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../File/Dplk/");
                $GLOBALS['db']->query("DELETE FROM ims_master_dplk WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataPendidikanFormal($NoKtp){
        try {
            $sql = "SELECT Id, `File` FROM ims_pendidikan_formal WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../File/PendidikanFormal/");
                $GLOBALS['db']->query("DELETE FROM ims_pendidikan_formal WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }
    function HapusDataNonPendidikanFormal($NoKtp){
        try {
            $sql = "SELECT Id, `File` FROM ims_pendidikan_nonformal WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../File/PendidikanNonFormal/");
                $GLOBALS['db']->query("DELETE FROM ims_pendidikan_nonformal WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataBpjsTK($NoKtp){
        try {
            $sql = "SELECT Id, `File` FROM ims_bpjs_tk WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../File/BpjsTk/");
                $GLOBALS['db']->query("DELETE FROM ims_bpjs_tk WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataBpjsKes($NoKtp){
        try {
            $sql = "SELECT Id, `File` FROM ims_bpjs_kesehatan WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../File/BpjsKesehatan/");
                $GLOBALS['db']->query("DELETE FROM ims_bpjs_kesehatan WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataKeluarga($NoKtp){
        try {
           $GLOBALS['db']->query("DELETE FROM ims_data_keluarga WHERE NoKtp = '$NoKtp'");
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataUkuranBaju($NoKtp){
        try {
           $GLOBALS['db']->query("DELETE FROM ims_ukuran_apd WHERE NoKtp = '$NoKtp'");
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataSkPengangkatan($NoKtp){
        try {
            $sql = "SELECT Id, `File` FROM ims_sk_pengangkatan WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../File/SkPengangkatan/");
                HapusFileDataPendukung($r['File'],"../../File/SkMutasi/");
                $GLOBALS['db']->query("DELETE FROM ims_sk_pengangkatan WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataSkPemberhentian($NoKtp){
        try {
            $sql = "SELECT Id, `File` FROM ims_sk_pemberhentian WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../File/SkPemberhentian/");
                $GLOBALS['db']->query("DELETE FROM ims_sk_pemberhentian WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataRekening($NoKtp){
        try {
            $sql = "SELECT Id, `File` FROM ims_rekening WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../img/FileRekening/");
                $GLOBALS['db']->query("DELETE FROM ims_rekening WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataMasterBiodata($NoKtp){
        try {
           $GLOBALS['db']->query("DELETE FROM ims_master_biodata WHERE NoKtp = '$NoKtp'");
           $GLOBALS['db']->query("DELETE FROM ims_master_biodata_bulan WHERE NoKtp = '$NoKtp'");
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusDataPendukung($NoKtp){
        try {
            HapusDataDplk($NoKtp);
            HapusDataPendidikanFormal($NoKtp);
            HapusDataNonPendidikanFormal($NoKtp);
            HapusDataBpjsTK($NoKtp);
            HapusDataBpjsKes($NoKtp);
            HapusDataKeluarga($NoKtp);
            HapusDataUkuranBaju($NoKtp);
            HapusDataSkPengangkatan($NoKtp);
            HapusDataSkPemberhentian($NoKtp);
            HapusDataRekening($NoKtp);
            HapusDataMasterBiodata($NoKtp);
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    /**
     * UPDATE DATA PENDUKUNG
     */

    function UpdateDataDplk($NoKtp,$NoKtpLama){
        try {
            $sql = "UPDATE ims_master_dplk SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'";
            $query = $GLOBALS['db']->query($sql);
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataPendidikanFormal($NoKtp,$NoKtpLama){
        try {
            $sql = "UPDATE ims_pendidikan_formal SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'";
            $query = $GLOBALS['db']->query($sql);
           
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataNonPendidikanFormal($NoKtp,$NoKtpLama){
        try {
            $sql = "UPDATE ims_pendidikan_nonformal SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'";
            $query = $GLOBALS['db']->query($sql);
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataBpjsTK($NoKtp,$NoKtpLama){
        try {
            $sql = "UPDATE ims_bpjs_tk SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'";
            $query = $GLOBALS['db']->query($sql);
            while($r = $query->fetch(PDO::FETCH_ASSOC)){
                HapusFileDataPendukung($r['File'],"../../File/BpjsTk/");
                $GLOBALS['db']->query("DELETE FROM ims_bpjs_tk WHERE Id = '$r[Id]'");
            }
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataBpjsKes($NoKtp,$NoKtpLama){
        try {
            $sql = "UPDATE ims_bpjs_kesehatan SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'";
            $query = $GLOBALS['db']->query($sql);
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataKeluarga($NoKtp,$NoKtpLama){
        try {
           $GLOBALS['db']->query("UPDATE ims_data_keluarga SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'");
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataUkuranBaju($NoKtp,$NoKtpLama){
        try {
           $GLOBALS['db']->query("UPDATE ims_ukuran_apd SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'");
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataSkPengangkatan($NoKtp,$NoKtpLama){
        try {
            $sql = "UPDATE ims_sk_pengangkatan SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'";
            $query = $GLOBALS['db']->query($sql);
            
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataSkPemberhentian($NoKtp,$NoKtpLama){
        try {
            $sql = "UPDATE ims_sk_pemberhentian SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataRekening($NoKtp,$NoKtpLama){
        try {
            $sql = "UPDATE ims_rekening SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'";
            $query = $GLOBALS['db']->query($sql);
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function UpdateDataMasterBiodata($NoKtp,$NoKtpLama){
        try {
           $GLOBALS['db']->query("UPDATE ims_master_biodata SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'");
           $GLOBALS['db']->query("UPDATE ims_master_biodata_bulan SET NoKtp = '$NoKtp' WHERE NoKtp = '$NoKtpLama'");
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }


    function UpdateDataPendukung($NoKtp,$NoKtpLama){
        try {
            UpdateDataDplk($NoKtp,$NoKtpLama);
            UpdateDataPendidikanFormal($NoKtp,$NoKtpLama);
            UpdateDataNonPendidikanFormal($NoKtp,$NoKtpLama);
            UpdateDataBpjsTK($NoKtp,$NoKtpLama);
            UpdateDataBpjsKes($NoKtp,$NoKtpLama);
            UpdateDataKeluarga($NoKtp,$NoKtpLama);
            UpdateDataUkuranBaju($NoKtp,$NoKtpLama);
            UpdateDataSkPengangkatan($NoKtp,$NoKtpLama);
            UpdateDataSkPemberhentian($NoKtp,$NoKtpLama);
            UpdateDataRekening($NoKtp,$NoKtpLama);
            UpdateDataMasterBiodata($NoKtp,$NoKtpLama);
            return true;
        } catch (PDOException $e) {
            $rMsg = $e->getMessage();
            InsertLogs($rMsg);
            return false;
        }
    }

    function HapusData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $rs = ShowData($data['Id']);
                HapusFile($rs['Foto'], $data['Dir']);
                HapusFile($rs['FotoKtp'], $data['Dir2']);
                HapusDataPendukung($rs['NoKtp']);
                $sql = "DELETE FROM ims_master_tenaga_kerja WHERE Id = :Id";
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                $msg['pesan'] = "Berhasil menghapus data master Tenaga Kerja </b>";
                $rMsg = "Berhasil menghapus data master Tenaga Kerja dengan nama <b>".$rs['Nama']."</b>";
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

    function ShowData($Id){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT * FROM ims_master_tenaga_kerja WHERE Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

    function ShowAgama(){
        $r = array();
        $sql = "SELECT Kode, Nama as Agama FROM ims_agama WHERE Flag = '1'";
        $query = $GLOBALS['db']->query($sql);
        $row = $query->rowCount();
        if($row > 0){
            while($res = $query->fetch(PDO::FETCH_ASSOC)){
                $r['data'][] = $res;
            }
            $r['status'] = "sukses";
            return $r;
        }

    }

    
    

    

?>
