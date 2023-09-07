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

    function UploadData($data){
        try {
            $target = basename($data['name']);
            move_uploaded_file($data['tmp_name'], $target);
            chmod($target,0777);
            $inputFileName = $target;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
            $no=1;
            foreach ($objWorksheet->getRowIterator() as $row) {
                $sheet_start2 = $no++;
                if($sheet_start2 > 2){
                    $res['NoKtp'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("B$sheet_start2")->getValue()); 
                    $res['Nama'] = anti_injection(strtoupper($objPHPExcel->getActiveSheet()->getCell("C$sheet_start2")->getValue())); 
                    $res['TptLahir'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("D$sheet_start2")->getValue()); 
                    $res['ThnLahir'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("E$sheet_start2")->getValue()); 
                    $res['BlnLahir'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("F$sheet_start2")->getValue()); 
                    $res['TglLahir'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("G$sheet_start2")->getValue()); 
                    $res['StatusKawin'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("H$sheet_start2")->getValue()); 
                    $res['JenisKelamin'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("I$sheet_start2")->getValue()); 
                    $res['Agama'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("J$sheet_start2")->getValue()); 
                    $res['Npwp'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("K$sheet_start2")->getValue()); 
                    $res['GolDarah'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("L$sheet_start2")->getValue()); 
                    $res['NoHp'] = angka(anti_injection($objPHPExcel->getActiveSheet()->getCell("M$sheet_start2")->getValue())); 
                    $res['ThnTmt'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("N$sheet_start2")->getValue()); 
                    $res['BlnTmt'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("O$sheet_start2")->getValue()); 
                    $res['TglTmt'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("P$sheet_start2")->getValue()); 
                    $res['Alamat'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("Q$sheet_start2")->getValue()); 
                    $res['NoDokumen'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("R$sheet_start2")->getValue()); 
                    $res['KodeCabang'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("S$sheet_start2")->getValue()); 
                    $res['KodeDivisi'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("T$sheet_start2")->getValue()); 
                    $res['KodeSubDivisi'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("U$sheet_start2")->getValue()); 
                    $res['KodeSeksi'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("V$sheet_start2")->getValue()); 
                    $res['KodeBranch'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("W$sheet_start2")->getValue()); 
                    if($res['NoKtp'] != ""){
                        $dt[] = $res;
                    }
                    
                }
            }
            unlink($target);
            $InsertData = TambahByUploadData($dt);
            return $InsertData;
        } catch (Exception $th) {
            return $th->getMessage();
        }
    }

    function CekNoKtp($NoKtp){
        $error = "";
        if(strlen($NoKtp) != 16){
            $error = "data dengan no ktp ".$NoKtp." gagal di proses. apakah nomor ktp yang dimasukka sudah benar";
            $data['error'] = $error;
            $data['msg'] = "gagal";
            return $data;
        }else{
            $sql = "SELECT COUNT(Id) as tot FROM ims_master_tenaga_kerja WHERE NoKtp = '$NoKtp'";
            $query = $GLOBALS['db']->query($sql);
            $r = $query->fetch(PDO::FETCH_ASSOC);
            if($r['tot'] > 0){
                $error = "data dengan no ktp ".$NoKtp." gagal di proses. data ini telah ada dalam sistem";
                $data['error'] = $error;
                $data['msg'] = "gagal";
                return $data;
            }else{
                $data['error'] = $error;
                $data['msg'] = "sukses";
                return $data;
            }
        }
        
        
    }

    function LogsUpload($Logs,$status){
        try {
            $TglCreate = date("Y-m-d H:i:s");
            $modul = $_SESSION['page'];
            $UserId = $_SESSION['Id'];
            $time = time()."-".date("Ymd");
            $sql = "INSERT INTO ims_log_upload_data SET KodeError = :KodeError, Modul = :Modul, `Data` = :Datas, `Status` = :Statuss, TglCreate = :TglCreate, UserId = :UserId";
            $query = $GLOBALS['db']->prepare($sql);
            $query->bindParam("KodeError", $time,PDO::PARAM_STR);
            $query->bindParam("Modul", $modul,PDO::PARAM_STR);
            $query->bindParam("Datas", $Logs,PDO::PARAM_STR);
            $query->bindParam("Statuss", $status,PDO::PARAM_STR);
            $query->bindParam("TglCreate", $TglCreate,PDO::PARAM_STR);
            $query->bindParam("UserId", $UserId,PDO::PARAM_STR);
            $query->execute();
            InsertLogs("Berhasil dimaksukkan ke log upload");
        } catch (PDOException $e) {
            InsertLogs($e->getMessage());
        }
        
    }

    function TambahByUploadData($data){
        try {
            $error = array();
            $succes = array();
            $TglCreate = date("Y-m-d H:i:s");
            $UserId = $_SESSION['Id'];
            $Flag =1;
            $NoKtpMaster = array();
            foreach($data as $key => $r){
                $CekNoKtp = CekNoKtp($r['NoKtp']);
                if(!empty($CekNoKtp['error'])) { $error[] = $CekNoKtp['error']; }
                if($CekNoKtp['msg'] == "sukses"){
                    try {
                        $TglLahir = $r['ThnLahir']."-".$r['BlnLahir']."-".$r['TglLahir'];
                        $Tmt = $r['ThnTmt']."-".$r['BlnTmt']."-".$r['TglTmt'];
                        $sql = "INSERT INTO ims_master_tenaga_kerja SET NoKtp = :NoKtp, Nama = :Nama, TptLahir = :TptLahir, TglLahir = :TglLahir, StatusKawin = :StatusKawin, JenisKelamin = :JenisKelamin, Agama =:Agama, Npwp = :Npwp, GolDarah = :GolDarah, NoHp = :NoHp, Tmt = :Tmt, Alamat = :Alamat, Flag = :Flag, TglCreate = :TglCreate, UserId = :UserId";
                        $stmt = $GLOBALS['db']->prepare($sql);
                        $stmt->bindParam("NoKtp", $r['NoKtp'],PDO::PARAM_STR);
                        $stmt->bindParam("Nama", $r['Nama'],PDO::PARAM_STR);
                        $stmt->bindParam("TptLahir", $r['TptLahir'],PDO::PARAM_STR);
                        $stmt->bindParam("TglLahir", $TglLahir,PDO::PARAM_STR);
                        $stmt->bindParam("StatusKawin", $r['StatusKawin'],PDO::PARAM_STR);
                        $stmt->bindParam("JenisKelamin", $r['JenisKelamin'],PDO::PARAM_STR);
                        $stmt->bindParam("Agama", $r['Agama'],PDO::PARAM_STR);
                        $stmt->bindParam("Npwp", $r['Npwp'],PDO::PARAM_STR);
                        $stmt->bindParam("GolDarah", $r['GolDarah'],PDO::PARAM_STR);
                        $stmt->bindParam("NoHp", $r['NoHp'],PDO::PARAM_STR);
                        $stmt->bindParam("Tmt", $Tmt,PDO::PARAM_STR);
                        $stmt->bindParam("Alamat", $r['Alamat'],PDO::PARAM_STR);
                        $stmt->bindParam("Flag", $Flag,PDO::PARAM_STR);
                        $stmt->bindParam("TglCreate", $TglCreate,PDO::PARAM_STR);
                        $stmt->bindParam("UserId", $UserId,PDO::PARAM_STR);
                        $stmt->execute();
                        if($stmt){
                            /** SK Pengangkatan */
                            $r_up['TanggalMulai'] = $Tmt;
                            $r_up['TglCreate'] = $TglCreate;
                            $r_up['Keterangan'] = "Upload Data Pegawai";
                            $r_up['NoKtp'] = $r['NoKtp'];
                            $r_up['UserId'] = $UserId;
                            $r_up['NoDokumen'] = $r['NoDokumen'];
                            $r_up['KodeBranch'] = $r['KodeBranch'];
                            $r_up['KodeCabang'] = $r['KodeCabang'];
                            $r_up['KodeDivisi'] = $r['KodeDivisi'];
                            $r_up['KodeSubDivisi'] = $r['KodeSubDivisi'];
                            $r_up['KodeSeksi'] = $r['KodeSeksi'];
                            $r_up['Kategori'] = 0;
                            TambahSkPengankatan($r_up);
                            $NoKtpMaster[] = $r_up['NoKtp'];
                        }
                        $succes[] = "data dengan no ktp ".$r['NoKtp']." berhasil diupload";

                    } catch (PDOException $e) {
                        $error[] = "data dengan no ktp ".$r['NoKtp']." gagal diuplaod. Pesan error : ".$e->getMessage();
                        continue;
                    }
                }
            }
            $JumlahError = count($error);
            $JumlahSuccess = count($succes);
            $succes = base64_encode(json_encode($succes));
            $error = base64_encode(json_encode($error));
            if($JumlahError > 0){
                LogsUpload($error,"error");  
            }
            if($JumlahSuccess > 0){
                LogsUpload($succes,"success");    
            }
            if(count($NoKtpMaster) > 0){
                UpdateBiodataMaster($NoKtpMaster);
            }
            
            $res['status'] = "sukses";
            $res['pesan'] = "Pesan Sistem (".$JumlahSuccess." berhasil dan ".$JumlahError." gagal)";
            InsertLogs($res['pesan']);
            return $res;
            exit();
        } catch (PDOException $e) {
            $res['status'] = "gagal";
            $res['pesan'] = $e->getMessage();
            InsertLogs($e->getMessage());
            return $res;
        }
    }

    function TambahSkPengankatan($data){
        try {
            $sql = "INSERT INTO ims_sk_pengangkatan SET NoKtp = :NoKtp, NoDokumen = :NoDokumen, KodeBranch = :KodeBranch, KodeCabang = :KodeCabang, KodeDivisi = :KodeDivisi, KodeSubDivisi = :KodeSubDivisi, KodeSeksi = :KodeSeksi, TanggalMulai = :TanggalMulai, Keterangan = :Keterangan, Kategori = :Kategori, TglCreate = :TglCreate, UserId = :UserId";
            $query = $GLOBALS['db']->prepare($sql);
            $query->bindParam("NoKtp", $data['NoKtp']);
            $query->bindParam("NoDokumen", $data['NoDokumen'], PDO::PARAM_STR);
            $query->bindParam("KodeBranch", $data['KodeBranch'], PDO::PARAM_STR);
            $query->bindParam("KodeCabang", $data['KodeCabang'], PDO::PARAM_STR);
            $query->bindParam("KodeDivisi", $data['KodeDivisi'], PDO::PARAM_STR);
            $query->bindParam("KodeSubDivisi", $data['KodeSubDivisi'], PDO::PARAM_STR);
            $query->bindParam("KodeSeksi", $data['KodeSeksi'], PDO::PARAM_STR);
            $query->bindParam("TanggalMulai", $data['TanggalMulai'], PDO::PARAM_STR);
            $query->bindParam("Keterangan", $data['Keterangan'], PDO::PARAM_STR);
            $query->bindParam("Kategori", $data['Kategori'], PDO::PARAM_STR);
            $query->bindParam("TglCreate", $data['TglCreate'], PDO::PARAM_STR);
            $query->bindParam("UserId", $data['UserId'], PDO::PARAM_STR);
            $query->execute();
            
            $res['status'] = "sukses";
            $res['pesan'] = "Penambahan data SK PKWT dengan No KTP <b>".$data['NoKtp']."</b> berhasil!";
            InsertLogs($res['pesan']);
            
            return true;
        } catch (PDOException $e) {
            $res['status'] = "gagal";
            $res['pesan'] = $e->getMessage();
            InsertLogs($e->getMessage());
            return false;
            
        }
        
    }

    function UpdateBiodataMaster($NoKtp){
        try {
            foreach ($NoKtp as $key => $Ktp) {
                TambahMasterBiodata($Ktp);
                UpdateMasterBiodataSkPengangkatan($Ktp);
            }
            return true;
        } catch (Exception $th) {
            return $th->getMessage();
        }
        
    }

    function TambahMasterBiodata($NoKtp){
        try {
            $r = $GLOBALS['db']->query("SELECT * FROM ims_master_tenaga_kerja WHERE NoKtp = '$NoKtp' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            $r['Agama'] = getAgama($r['Agama']);
            $rs = json_encode($r);
            $rs = base64_encode($rs);
            $sql = "INSERT INTO ims_master_biodata SET  Biodata = '$rs', Flag = '$r[Flag]', NoKtp = '$NoKtp'";
            $GLOBALS['db']->query($sql); 
            
            $res['status'] = "sukses";
            $res['pesan'] = "Biodata berhasil diupdate ke master biodata denga No KTP ".$NoKtp;
            InsertLogs($res['pesan']);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }

    function UpdateMasterBiodataSkPengangkatan($NoKtp){
        try {
            $r = $GLOBALS['db']->query("SELECT * FROM ims_sk_pengangkatan WHERE NoKtp = '$NoKtp' ORDER BY TanggalMulai DESC")->fetch(PDO::FETCH_ASSOC);
            $r['NamaBranch'] = getNameBranch($r['KodeBranch']);
            $r['NamaCabang'] = getNameUnitKeraja($r['KodeCabang']);
            $r['NamaDivisi'] = getNameDivisi($r['KodeDivisi']);
            $r['NamaSubDivisi'] = getNameSubDivisi($r['KodeSubDivisi']);
            $r['NamaSeksi'] = getNameSeksi($r['KodeSeksi']);
            $rs = json_encode($r);
            $rs = base64_encode($rs);
            $sql = "UPDATE ims_master_biodata SET  SpkPengangkatan = '$rs', KodeBranch = '$r[KodeBranch]', KodeCabang = '$r[KodeCabang]', KodeDivisi = '$r[KodeDivisi]', KodeSubDivisi = '$r[KodeSubDivisi]', KodeSeksi = '$r[KodeSeksi]' WHERE NoKtp = '$NoKtp'";
            $GLOBALS['db']->query($sql); 
            $res['status'] = "sukses";
            $res['pesan'] = "Sk Pengankatan berhasil diupdate ke master biodata denga No KTP ".$NoKtp;
            InsertLogs($res['pesan']);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    function getAgama($Kode){
        $sql = "SELECT Nama FROM ims_agama WHERE Kode = '$Kode'";
        $query = $GLOBALS['db']->query($sql);
        $r = $query->fetch(PDO::FETCH_ASSOC);
        return $r['Nama'];
    }

    function getNameBranch($Kode){
        $sql = "SELECT Nama FROM ims_master_branch WHERE Kode = '$Kode'";
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


    

?>