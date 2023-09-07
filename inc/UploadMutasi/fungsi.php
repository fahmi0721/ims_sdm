<?php

    function anti_injection($data){
        $filter = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
        return $filter;

    }

    function InsertLogs($msg){
        $Logs['UserId'] = $_SESSION['Id'];
        $Logs['Logs'] = $msg;
        $Logs['Modul'] = $_SESSION['page'];
        Logs($Logs);
    }

    function getNameUnitKeraja($Kode){
        $sql = "SELECT NamaCabang as Nama FROM ims_master_cabang WHERE Kode = '$Kode'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['Nama'];
    }

    function getNameBranch($Kode){
        $sql = "SELECT  Nama FROM ims_master_branch WHERE Kode = '$Kode'";
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

    function UpdateMasterBiodata($NoKtp){
        try {
            $qr = $GLOBALS['db']->query("SELECT * FROM ims_sk_pengangkatan WHERE NoKtp = '$NoKtp' ORDER BY TanggalMulai DESC, TglCreate DESC LIMIT 1");
            $rt = $qr->fetch(PDO::FETCH_ASSOC);
            if(empty($rt)){
                $rs = null;
            }else{
                $rt['NamaCabang'] = getNameUnitKeraja($rt['KodeCabang']);
                $rt['NamaBranch'] = getNameUnitKeraja($rt['KodeBranch']);
                $rt['NamaDivisi'] = getNameDivisi($rt['KodeDivisi']);
                $rt['NamaSubDivisi'] = getNameSubDivisi($rt['KodeSubDivisi']);
                $rt['NamaSeksi'] = getNameSeksi($rt['KodeSeksi']);
                $rs = json_encode($rt);
                $rs = base64_encode($rs);
            }
            $sql = "UPDATE ims_master_biodata SET  SpkPengangkatan = '$rs', KodeCabang = '$rt[KodeCabang]', KodeDivisi = '$rt[KodeDivisi]', KodeSubDivisi = '$rt[KodeSubDivisi]', KodeSeksi = '$rt[KodeSeksi]' WHERE NoKtp = '$NoKtp'";
            $GLOBALS['db']->query($sql); 
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    function cekKtp($NoKtp){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT NoKtp FROM ims_master_tenaga_kerja WHERE NoKTP = '$NoKtp'";
        $query = $koneksi->query($sql);
        $res = $query->rowCount();
        return $res;
    }

    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $CekKtp = cekKtp($data['NoKtp']);
                if($CekKtp > 0){
                    $data['TglCreate'] = date("Y-m-d H:i:s");
                    $data['UserId'] = $_SESSION['Id'];
                    $sql = "INSERT INTO ims_sk_pengangkatan SET NoKtp = :NoKtp, KodeCabang = :KodeCabang, KodeBranch = :KodeBranch, KodeDivisi = :KodeDivisi, KodeSubDivisi = :KodeSubDivisi, KodeSeksi = :KodeSeksi, TanggalMulai = :TanggalMulai, NoDokumen = :NoDokumen, Keterangan = :Keterangan, Kategori = :Kategori, TglCreate = :TglCreate,  UserId = :UserId,`File` = :File ";
                    $exc = $koneksi->prepare($sql);
                    $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                    $exc->bindParam('KodeCabang', $data['KodeCabang'], PDO::PARAM_STR);
                    $exc->bindParam('KodeBranch', $data['KodeBranch'], PDO::PARAM_STR);
                    $exc->bindParam('KodeDivisi', $data['KodeDivisi'], PDO::PARAM_STR);
                    $exc->bindParam('KodeSubDivisi', $data['KodeSubDivisi'], PDO::PARAM_STR);
                    $exc->bindParam('KodeSeksi', $data['KodeSeksi'], PDO::PARAM_STR);
                    $exc->bindParam('TanggalMulai', $data['TanggalMulai'], PDO::PARAM_STR);
                    $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                    $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                    $exc->bindParam('Kategori', $data['Kategori'], PDO::PARAM_STR);
                    $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                    $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                    $exc->bindParam('File', $data['File'], PDO::PARAM_STR);
                    $exc->execute();
                    $msg['pesan'] = "Berhasil menambah data SK Mutasi";
                    $rMsg = "Berhasil menambah data SK Mutasi dengan no ktp <b>".$data['NoKtp']."</b>";
                    $msg['status'] = "sukses";
                    InsertLogs($rMsg);
                    return $msg;
                }else{
                    $msg['pesan'] = "No Ktp :".$data['NoKtp']." tidak terdaftar dalam databse.";
                    $rMsg = "gagal menambah data SK Mutasi dengan no ktp <b>".$data['NoKtp']." karena tidak terdaftar</b>";
                    $msg['status'] = "error";
                    InsertLogs($rMsg);
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

    function UploadData($data,$Lampiran){
        $rs['berhasil'] = array();
        $rs['gagal'] = array();
        if(isset($Lampiran['File']) && !empty($Lampiran['File']['name'])){
            $lampiran['Dir'] = "../../File/SkMutasi/";
            $Validasi = ValidasiFile($Lampiran['File'],$lampiran['Dir']);
            if($Validasi['msg'] == "sukses"){
                try {
                    $target = basename($data['name']);
                    move_uploaded_file($data['tmp_name'], $target);
                    chmod($target,0777);
                    $inputFileName = $target;
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
                    $no=2;
                    foreach ($objWorksheet->getRowIterator() as $row) {
                        $sheet_start2 = $no++;
                        if($sheet_start2 > 2){
                            $res['File'] = $Validasi['pesan'];
                            $res['NoKtp'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("B$sheet_start2")->getValue()); 
                            $res['Nama'] = anti_injection(strtoupper($objPHPExcel->getActiveSheet()->getCell("C$sheet_start2")->getValue())); 
                            $res['NoDokumen'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("D$sheet_start2")->getValue()); 
                            $res['KodeCabang'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("E$sheet_start2")->getValue()); 
                            $res['KodeDivisi'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("F$sheet_start2")->getValue()); 
                            $res['KodeBranch'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("G$sheet_start2")->getValue()); 
                            $res['KodeSubDivisi'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("H$sheet_start2")->getValue()); 
                            $res['KodeSeksi'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("I$sheet_start2")->getValue()); 
                            $Tahun = anti_injection($objPHPExcel->getActiveSheet()->getCell("J$sheet_start2")->getValue()); 
                            $Bulan = anti_injection($objPHPExcel->getActiveSheet()->getCell("K$sheet_start2")->getValue()); 
                            $Tanggal = anti_injection($objPHPExcel->getActiveSheet()->getCell("L$sheet_start2")->getValue()); 
                            $res['Keterangan'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("M$sheet_start2")->getValue()); 
                            $res['TanggalMulai'] = $Tahun."-".$Bulan."-".$Tanggal;
                            $res['Kategori'] = "1";
                            if($res['NoKtp'] != ""){
                                $TambahMutasi = TambahData($res);
                                if($TambahMutasi['status'] == "sukses"){
                                    UpdateMasterBiodata($res['NoKtp']);
                                    $res['ket'] = $TambahMutasi['pesan'];
                                    $rs['berhasil'][] = $res;
                                }else{
                                    $res['Keterangan'] = $TambahMutasi['pesan'];
                                    $res['ket'] = "Periksa data yang diupload";
                                    $rs['gagal'][] = $res;
                                }
                                
                            }
                            
                        }
                    }
                    unlink($target);
                    $msg['status'] = "sukses";
                    $msg['pesan'] = count($rs['berhasil'])." data mutasi berhasil diupload dan ".count($rs['gagal'])." data mutasi gagal diupload";
                    $msg['data'] = $rs;
                    return $msg;
                } catch (Exception $th) {
                    return $th->getMessage();
                }
            }else{
                $msg['status'] = "gagal";
                $msg['pesan'] = "Upload data daftar mutasi gagal";
                $msg['data'] = array();
                return $msg;
            }
        }else{
            $msg['status'] = "gagal";
            $msg['pesan'] = "Anda belum memilih file lampiran";
            $msg['data'] = array();
            return $msg;
        }
    }

    

    

?>