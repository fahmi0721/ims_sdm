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
                    $sql = "INSERT INTO ims_sk_pemberhentian SET NoKtp = :NoKtp, Tmt = :Tmt,NoDokumen = :NoDokumen,Kategori = :Kategori, Keterangan = :Keterangan,  TglCreate = :TglCreate,  UserId = :UserId,`File` = :File";
                    $exc = $koneksi->prepare($sql);
                    $exc = $koneksi->prepare($sql);
                    $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                    $exc->bindParam('Tmt', $data['Tmt'], PDO::PARAM_STR);
                    $exc->bindParam('NoDokumen', $data['NoDokumen'], PDO::PARAM_STR);
                    $exc->bindParam('Kategori', $data['Kategori'], PDO::PARAM_STR);
                    $exc->bindParam('Keterangan', $data['Keterangan'], PDO::PARAM_STR);
                    $exc->bindParam('TglCreate', $data['TglCreate'], PDO::PARAM_STR);
                    $exc->bindParam('UserId', $data['UserId'], PDO::PARAM_STR);
                    $exc->bindParam('File', $data['File'], PDO::PARAM_STR);
                    $exc->execute();
                    $msg['pesan'] = "Berhasil menambah data SK Pemberhentian";
                    $rMsg = "Berhasil menambah data SK PHK dengan no ktp <b>".$data['NoKtp']."</b>";
                    $msg['status'] = "sukses";
                    InsertLogs($rMsg);
                    return $msg;
                }else{
                    $msg['pesan'] = "No Ktp :".$data['NoKtp']." tidak terdaftar dalam databse.";
                    $rMsg = "gagal menambah data SK PHK dengan no ktp <b>".$data['NoKtp']." karena tidak terdaftar</b>";
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
            $Dir = "../../File/SkPemberhentian/";
            $Validasi = ValidasiFile($Lampiran['File'],$Dir);
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
                            $res['Kategori'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("E$sheet_start2")->getValue()); 
                            $Tahun = anti_injection($objPHPExcel->getActiveSheet()->getCell("F$sheet_start2")->getValue()); 
                            $Bulan = anti_injection($objPHPExcel->getActiveSheet()->getCell("G$sheet_start2")->getValue()); 
                            $Tanggal = anti_injection($objPHPExcel->getActiveSheet()->getCell("H$sheet_start2")->getValue()); 
                            $res['Keterangan'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("I$sheet_start2")->getValue()); 
                            $res['Tmt'] = $Tahun."-".$Bulan."-".$Tanggal;
                            $res['Kategori'] = "1";
                            if($res['NoKtp'] != ""){
                                $TambahPhk = TambahData($res);
                                if($TambahPhk['status'] == "sukses"){
                                    UpdateMasterBiodata($res['NoKtp']);
                                    UpdateDataMasterTenagaKerja($res['NoKtp'],"insert");
                                    $res['ket'] = $TambahPhk['pesan'];
                                    $rs['berhasil'][] = $res;
                                }else{
                                    $res['Keterangan'] = $TambahPhk['pesan'];
                                    $res['ket'] = "Periksa data yang diupload";
                                    $rs['gagal'][] = $res;
                                }
                                
                            }
                            
                        }
                    }
                    unlink($target);
                    $msg['status'] = "sukses";
                    $msg['pesan'] = count($rs['berhasil'])." data phk berhasil diupload dan ".count($rs['gagal'])." data phk gagal diupload";
                    $msg['data'] = $rs;
                    return $msg;
                } catch (Exception $th) {
                    return $th->getMessage();
                }
            }else{
                $msg['status'] = "gagal";
                $msg['pesan'] =  "ok";
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