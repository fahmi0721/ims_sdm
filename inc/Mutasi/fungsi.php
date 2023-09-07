<?php
     function DetailData($data){
        $db = $GLOBALS['db'];
        $datas = array();
        $row = array();
        if(is_array($data)){
            try {
                $page_num = !empty($data['Page']) ? $data['Page'] : 1;
                $row_page= $data['ShowTampil'];
                $search= $data['Search'];
                $offset=($page_num - 1) * $row_page;
                $no=$offset+1;
                $sql = "SELECT a.Id, a.Sk, a.TanggalMulai, a.TanggalSelesai, b.Nama, a.Jabatan, c.NamaCabang FROM ims_mutasi a INNER JOIN ims_tenaga_kerja b ON a.IdTenagaKerja = b.Id INNER JOIN ims_cabang c ON a.IdCabang = c.Id WHERE b.Status = '0' AND b.Nama LIKE '%$search%' ORDER BY a.Id DESC";
                $query = $db->query($sql);
                $JumRow = $query->rowCount();
                $total_page = ceil($JumRow / $row_page);
                $datas['total_page'] = $total_page;
                $datas['total_data'] = $JumRow;
                $datas['no_first'] = $no;
                $datas['status'] =0;
                $sql1 = $sql." LIMIT ".$offset.", ".$row_page;
                $query1 = $db->query($sql1);
                if($JumRow > 0){
                    while ($res = $query1->fetch(PDO::FETCH_ASSOC)) { 
                        $aksi = in_array($_SESSION['page_level'],$_SESSION['p-a']) ? "<a class='btn btn-xs btn-primary'  data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> " : "guest";
                        
                        if(in_array($_SESSION['page_level'],$_SESSION['p-a']) AND !empty($res['TanggalSelesai'])){
                            $aksi .= " <a  class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
                        }
                        $Sk = empty($res['Sk']) ? "" : "<a href='File/Sk/".$res['Sk']."' class='btn btn-xs btn-info'  data-toggle='tooltip' target='_bank' title='SK Mutasi' ><i class='fa fa-eye'></i></a>";
                        $TmtSelsai = empty($res['TanggalSelesai']) ? "Sekarang" : tgl_indo($res['TanggalSelesai']);
                        $row['No'] = $no;
                        $row['Nama'] = $res['Nama'];
                        $row['Jabatan'] = $res['Jabatan'];
                        $row['UnitKerja'] = $res['NamaCabang'];
                        $row['TMTMulai'] = tgl_indo($res['TanggalMulai']);
                        $row['TMTBerakhir'] = $TmtSelsai;
                        $row['Sk'] = $Sk;
                        $row['Aksi'] = $aksi;
                        $datas['data'][] = $row;
                        $no++;
                    }
                    $datas['no_last'] = $no - 1;
                    
                }else{
                    $datas['data']='';
                }
                return $datas;
            } catch (PDOException $e) {
                $datas['status'] =1;
                $datas['pesan'] = $e->getMessage();
                $datas['total_page'] =0;
                $datas['total_data'] = 0;
                $datas['no_first'] = 0;
                return $datas;
            }
        }else{
            $datas['status'] =1;
            $datas['pesan'] = "Parameter Data Bukan Array";
            $datas['total_page'] =0;
            $datas['total_data'] = 0;
            $datas['no_first'] = 0;
            return $datas;
        }
        
    }

    function convert($size,$unit){
        if($unit == "KB"){
            return $fileSize = round($size / 1024,4);	
        }
        if($unit == "MB"){
            return $fileSize = round($size / 1024 / 1024,4);	
        }
        if($unit == "GB"){
            return $fileSize = round($size / 1024 / 1024 / 1024,4);	
        }
    }

    function CekDataMutasi($Id){
        $koneksi = $GLOBALS['db'];
        if($Id){
            $sql = "SELECT COUNT(Id) as tot FROM ims_mutasi WHERE IdTenagaKerja = :Id";
            $exec = $koneksi->prepare($sql);
            $exec->bindParam("Id", $Id, PDO::PARAM_INT);
            $exec->execute();
            if($exec){
                $res = $exec->fetch(PDO::FETCH_ASSOC);
                return $res['tot'];
            }else{
                $Logs['UserId'] = $_SESSION['Id'];
                $Logs['Logs'] = "Error : 501 Terjadi kealhan sistem";
                $Logs['Modul'] = $_SESSION['page'];
                Logs($Logs);
                $data['status'] = "gagal";
                $data['pesan'] = "Error : 501 Terjadi kealhan sistem";
                return $data;
            }
        }else{
            $Logs['UserId'] = $_SESSION['Id'];
            $Logs['Logs'] = "Error : 500 Terjadi kealhan sistem";
            $Logs['Modul'] = $_SESSION['page'];
            Logs($Logs);
            $data['status'] = "gagal";
            $data['pesan'] = "Error : 500 Terjadi kealhan sistem";
            return $data;
        }
    }

    function InsertNew($data){
        $koneksi = $GLOBALS['db'];
        $OldTMT = $koneksi->query("SELECT Nama,  TMT FROM ims_tenaga_kerja WHERE Id = '$data[IdTenagaKerja]'")->fetch(PDO::FETCH_ASSOC);
        $Tmt = $OldTMT['TMT'];
        $sql = "INSERT INTO ims_mutasi SET IdTenagaKerja = :IdTenagaKerja, IdCabang = :IdCabang, TanggalMulai = :TanggalMulai, TanggalSelesai = :TanggalSelesai, Jabatan = :Jabatan, TglCreate = :TglCreate, UserId = :UserId";
        $exec = $koneksi->prepare($sql);
        $exec->bindParam("IdTenagaKerja", $data['IdTenagaKerja'], PDO::PARAM_INT);
        $exec->bindParam("IdCabang", $data['IdCabangLama'], PDO::PARAM_INT);
        $exec->bindParam("TanggalMulai", $Tmt, PDO::PARAM_STR);
        $exec->bindParam("TanggalSelesai", $data['TMTLama'], PDO::PARAM_STR);
        $exec->bindParam("Jabatan", $data['JabatanLama'], PDO::PARAM_STR);
        $exec->bindParam("TglCreate", $data['TglCreate'], PDO::PARAM_STR);
        $exec->bindParam("UserId", $data['UserId'], PDO::PARAM_INT);
        $exec->execute();
        if($exec){
            $nextSql = "INSERT INTO ims_mutasi SET IdTenagaKerja = :IdTenagaKerja, IdCabang = :IdCabang, TanggalMulai = :TanggalMulai, Sk = :Sk, Jabatan = :Jabatan, TglCreate = :TglCreate, UserId = :UserId";
            $next_exec = $koneksi->prepare($nextSql);
            $next_exec->bindParam("IdTenagaKerja", $data['IdTenagaKerja'], PDO::PARAM_INT);
            $next_exec->bindParam("IdCabang", $data['IdCabangBaru'], PDO::PARAM_INT);
            $next_exec->bindParam("TanggalMulai", $data['TMTBaru'], PDO::PARAM_STR);
            $next_exec->bindParam("Sk", $data['Sk'], PDO::PARAM_STR);
            $next_exec->bindParam("Jabatan", $data['JabatanBaru'], PDO::PARAM_STR);
            $next_exec->bindParam("TglCreate", $data['TglCreate'], PDO::PARAM_STR);
            $next_exec->bindParam("UserId", $data['UserId'], PDO::PARAM_INT);
            $next_exec->execute();
            if($next_exec){
                $UpdateTenagaKerja = $koneksi->query("UPDATE ims_tenaga_kerja SET IdCabang = '$data[IdCabangBaru]', Jabatan = '$data[JabatanBaru]' WHERE Id = '$data[IdTenagaKerja]'");
                $Logs['UserId'] = $_SESSION['Id'];
                $Logs['Logs'] = "Mutasi Jabatan a.n ".$OldTMT['Nama']." berhasil.";
                $Logs['Modul'] = $_SESSION['page'];
                Logs($Logs);
                $data['status'] = "sukses";
                $data['pesan'] = "Data Mutasi a.n".$OldTMT['Nama']." berhasil di tambah.";
                return $data;
            }else{
                $Logs['UserId'] = $_SESSION['Id'];
                $Logs['Logs'] = "Error : 503 Terjadi kealhan sistem";
                $Logs['Modul'] = $_SESSION['page'];
                Logs($Logs);
                $data['status'] = "gagal";
                $data['pesan'] = "Error : 503 Terjadi kealhan sistem";
                return $data;
            }
        }else{
            $Logs['UserId'] = $_SESSION['Id'];
            $Logs['Logs'] = "Error : 502 Terjadi kealhan sistem";
            $Logs['Modul'] = $_SESSION['page'];
            Logs($Logs);
            $data['status'] = "gagal";
            $data['pesan'] = "Error : 502 Terjadi kealhan sistem";
            return $data;
        }
    }

    function UpdateAndInsert($data){
        $koneksi = $GLOBALS['db'];
        $UpdateTenagaKerja = $koneksi->query("UPDATE ims_tenaga_kerja SET IdCabang = '$data[IdCabangBaru]', Jabatan = '$data[JabatanBaru]' WHERE Id = '$data[IdTenagaKerja]'");
        $OldDataMutasi = $koneksi->query("SELECT a.Id, b.Nama FROM ims_mutasi a INNER JOIN ims_tenaga_kerja b ON a.IdTenagaKerja = b.Id WHERE a.IdCabang = '$data[IdCabangLama]' AND a.IdTenagaKerja = '$data[IdTenagaKerja]' ORDER BY a.Id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $sql = "UPDATE ims_mutasi SET TanggalSelesai = :TanggalSelesai WHERE Id = :Id";
        $exx = $koneksi->prepare($sql);
        $exx->bindParam("TanggalSelesai", $data['TMTLama'],PDO::PARAM_STR);
        $exx->bindParam("Id", $OldDataMutasi['Id'],PDO::PARAM_STR);
        $exx->execute();
        
        if($exx){
           $nextSql = "INSERT INTO ims_mutasi SET IdTenagaKerja = :IdTenagaKerja, IdCabang = :IdCabang, TanggalMulai = :TanggalMulai, Sk = :Sk, Jabatan = :Jabatan, TglCreate = :TglCreate, UserId = :UserId";
            $next_exec = $koneksi->prepare($nextSql);
            $next_exec->bindParam("IdTenagaKerja", $data['IdTenagaKerja'], PDO::PARAM_INT);
            $next_exec->bindParam("IdCabang", $data['IdCabangBaru'], PDO::PARAM_INT);
            $next_exec->bindParam("TanggalMulai", $data['TMTBaru'], PDO::PARAM_STR);
            $next_exec->bindParam("Sk", $data['Sk'], PDO::PARAM_STR);
            $next_exec->bindParam("Jabatan", $data['JabatanBaru'], PDO::PARAM_STR);
            $next_exec->bindParam("TglCreate", $data['TglCreate'], PDO::PARAM_STR);
            $next_exec->bindParam("UserId", $data['UserId'], PDO::PARAM_INT);
            $next_exec->execute();
            if($next_exec){
                $Logs['UserId'] = $_SESSION['Id'];
                $Logs['Logs'] = "Mutasi Jabatan a.n ".$OldDataMutasi['Nama']." berhasil.".$OldDataMutasi['Id'];
                $Logs['Modul'] = $_SESSION['page'];
                Logs($Logs);
                $data['status'] = "sukses";
                $data['pesan'] = "Data Mutasi a.n".$OldDataMutasi['Nama']." berhasil di tambah.";
                return $data;
            }else{
                $Logs['UserId'] = $_SESSION['Id'];
                $Logs['Logs'] = "Error : 503 Terjadi kealhan sistem";
                $Logs['Modul'] = $_SESSION['page'];
                Logs($Logs);
                $data['status'] = "gagal";
                $data['pesan'] = "Error : 503 Terjadi kealhan sistem";
                return $data;
            }
            
        }else{
            $Logs['UserId'] = $_SESSION['Id'];
            $Logs['Logs'] = "Error : 503 Terjadi kealhan sistem";
            $Logs['Modul'] = $_SESSION['page'];
            Logs($Logs);
            $data['status'] = "gagal";
            $data['pesan'] = "Error : 503 Terjadi kealhan sistem";
            return $data;
        }
    }

    function TambahData($data){
        $result =array();
        if(is_array($data)){
            $data['TglCreate'] = date("Y-m-d H:i:s");
            $data['UserId'] = $_SESSION['Id'];
            $NamaFile  = $data['File']['name'];
            $TempFile  = $data['File']['tmp_name'];
            $SizeFile  = $data['File']['size'];
            if(convert($SizeFile,"MB") > 2){
                $result['status'] = "gagal";
                $result['pesan'] = "Size File SK erlalu besar. maksimal file 2 mb";
                $Logs['UserId'] = $_SESSION['Id'];
				$Logs['Logs'] = "Gagal Menambah Mutasi File Sk terlalu besar";
				$Logs['Modul'] = $_SESSION['page'];
				Logs($Logs);
            }else{
                $Extensi = explode(".",$NamaFile);
                $Extensi = strtolower(end($Extensi));
                $NameFile = rand().time().".".$Extensi;
                $move = move_uploaded_file($TempFile,$data['Direktori'].$NameFile);
                if($move){
                    $data['Sk'] = $NameFile;
                    $CekDataMutasi = CekDataMutasi($data['IdTenagaKerja']);
                    if($CekDataMutasi > 0){
                        $result = UpdateAndInsert($data);
                    }else{
                        $result = InsertNew($data);
                    }
                    return $result;
                }else{
                    $result['status'] = "gagal";
                    $result['pesan'] = "Gagal Mengupload File SK";
                    $Logs['UserId'] = $_SESSION['Id'];
                    $Logs['Logs'] = $result['pesan'];
                    $Logs['Modul'] = $_SESSION['page'];
                    Logs($Logs);
                    return $result;
                }
            }
            
        }else{
            $result['status'] = "gagal";
            $result['pesan'] = "Gagal Menambah Mutasi";
            $Logs['UserId'] = $_SESSION['Id'];
            $Logs['Logs'] = $result['pesan'];
            $Logs['Modul'] = $_SESSION['page'];
            Logs($Logs);
            return $result;
        }
    }

    function UbahData($data){
        $koneksi = $GLOBALS['db'];
        $result = array();
        if(is_array($data)){
            $data['TglUpdate'] = date("Y-m-d H:i:s");
            $data['UserId'] = $_SESSION['Id'];
            if(isset($data['File'])){
                $result['status'] = "sukses";
                $result['pesan'] = "Mutasi berhasil diubah";
                return $result;
            }else{
                $sql = "UPDATE ims_mutasi SET IdCabang = :IdCabang, Jabatan = :Jabatan, TanggalMulai = :TanggalMulai, TanggalSelesai = :TanggalSelesai, TglUpdate = :TglUpdate,  UserId = :UserId WHERE Id = :Id";
                $TglSelesai = empty($data['TanggalSelesai']) ? null : $data['TanggalSelesai'];
                $exc = $koneksi->prepare($sql);
                $exc->bindParam('IdCabang', $data['IdCabang'], PDO::PARAM_INT);
                $exc->bindParam('Jabatan', $data['Jabatan'], PDO::PARAM_STR);
                $exc->bindParam('TanggalMulai', $data['TanggalMulai'], PDO::PARAM_STR);
                $exc->bindParam('TanggalSelesai',$TglSelesai , PDO::PARAM_STR);
                $exc->bindParam('TglUpdate', $data['TglUpdate'], PDO::PARAM_STR);
                $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
                $exc->execute();
                if($exc){
                    $result['status'] = "sukses";
                    $result['pesan'] = "Mutasi berhasil diubah";
                    $Logs['UserId'] = $_SESSION['Id'];
                    $Logs['Logs'] = "Mutasi a.n ".$data['Nama']." berhasil diubah";
                    $Logs['Modul'] = $_SESSION['page'];
                    Logs($Logs);
                    return $result;
                }else{
                    $result['status'] = "gagal";
                    $result['pesan'] = "Gagal Menambah Mutasi";
                    $Logs['UserId'] = $_SESSION['Id'];
                    $Logs['Logs'] = $result['pesan'];
                    $Logs['Modul'] = $_SESSION['page'];
                    Logs($Logs);
                    return $result;
                }
            }
            
        }else{
            $result['status'] = "gagal";
            $result['pesan'] = "Gagal Menambah Mutasi";
            return $result;
        }
    }

    function GetFileSK($Id){
        $koneksi = $GLOBALS['db'];
        $query = $koneksi->query("SELECT a.Sk, b.Nama FROM ims_mutasi a INNER JOIN ims_tenaga_kerja b ON a.IdTenagaKerja = b.Id WHERE a.Id = '$Id'");
        $rows = $query->rowCount();
        if($rows > 0){
            return $query->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }

    function HapusData($data){
        $koneksi = $GLOBALS['db'];
        $File = GetFileSK($data['Id']);
        if(!empty($data)){
            $sql = "DELETE FROM ims_mutasi WHERE Id = :Id";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Id', $data['Id'], PDO::PARAM_INT);
            $exc->execute();
            if($exc){
                if($File){
                    if(file_exists($data['Direktori'].$File['Sk']) && $File['Sk'] != ""){
                        unlink($data['Direktori'].$File['Sk']); 
                    }
                }
                $result['status'] = "sukses";
                $result['pesan'] = "Mutasi berhasil dihapus";
                $Logs['UserId'] = $_SESSION['Id'];
                $Logs['Logs'] = "Mutasi a.n ".$File['Nama']." berhasil dihapus";
                $Logs['Modul'] = $_SESSION['page'];
                Logs($Logs);
                return $result;
            }else{
                $result['status'] = "gagal";
                $result['pesan'] = "Mutasi gagal dihapus";
                $Logs['UserId'] = $_SESSION['Id'];
                $Logs['Logs'] = "Mutasi a.n ".$File['Nama']." gagal dihapus";
                $Logs['Modul'] = $_SESSION['page'];
                Logs($Logs);
                return $result;
            }
        }else{
            $result['status'] = "gagal";
            $result['pesan'] = "Gagal Menghapus Mutasi";
            $Logs['UserId'] = $_SESSION['Id'];
            $Logs['Logs'] = $result['pesan'];
            $Logs['Modul'] = $_SESSION['page'];
            Logs($Logs);
            return $result;
        }
    }

    function ShowData($Id){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT a.Id as IdUpdate, a.IdTenagaKerja, a.IdCabang, a.TanggalMulai, a.TanggalSelesai, b.Nama as NamaUpdate, b.NoKtp as NoKtpUpdate, b.Pendidikan as PendidikanUpdate, a.Jabatan, c.NamaCabang as UnitKerja, b.TptLahir, b.TglLahir FROM ims_mutasi a INNER JOIN ims_tenaga_kerja b ON a.IdTenagaKerja = b.Id INNER JOIN ims_cabang c  ON a.IdCabang = c.Id WHERE a.Id = :Id";
        $exc = $koneksi->prepare($sql);
        $exc->bindParam("Id", $Id, PDO::PARAM_INT);
        $exc->execute();
        $dt = $exc->fetch(PDO::FETCH_ASSOC);
        $dt['TTLUpdate'] = $dt['TptLahir'].", ".tgl_indo($dt['TglLahir']);
        return $dt;
    }

    
    

    

?>