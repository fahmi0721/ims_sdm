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

    

    function cekKtp($NoKtp,$TglLahir){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT NoKtp FROM ims_master_tenaga_kerja WHERE NoKTP = '$NoKtp' AND DATE_FORMAT(TglLahir,'%Y-%m-%d') = '$TglLahir'";
        $query = $koneksi->query($sql);
        $res = $query->rowCount();
        return $res;
    }

    function CekNrp($Nrp){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Nik FROM ims_master_tenaga_kerja WHERE Nik = '$Nrp'";
        $query = $koneksi->query($sql);
        $res = $query->rowCount();
        return $res;
    }

    function TambahData($data){
        $koneksi = $GLOBALS['db'];
        if(is_array($data)){
            try {
                $CekKtp = cekKtp($data['NoKtp'],$data['TglLahir']);
                if($CekKtp > 0){
                    $CekNrp = CekNrp($data['Nik']);
                    if($CekNrp > 0){
                        $msg['pesan'] = "Nrp ini telah digunakan!";
                        $rMsg = "Berhasil menambah data SK Mutasi dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "error";
                        InsertLogs($rMsg);
                        return $msg;
                    }else{
                        $data['TglCreate'] = date("Y-m-d H:i:s");
                        $data['UserId'] = $_SESSION['Id'];
                        $sql = "UPDATE ims_master_tenaga_kerja SET Nik = :Nik WHERE NoKtp = :NoKtp AND DATE_FORMAT(TglLahir, '%Y-%m-%d') = :TglLahir ";
                        $exc = $koneksi->prepare($sql);
                        $exc->bindParam('Nik', $data['Nik'], PDO::PARAM_STR);
                        $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
                        $exc->bindParam('TglLahir', $data['TglLahir'], PDO::PARAM_STR);
                        $exc->execute();
                        $msg['pesan'] = "SUKSES";
                        $rMsg = "Berhasil menambah data SK Mutasi dengan no ktp <b>".$data['NoKtp']."</b>";
                        $msg['status'] = "sukses";
                        InsertLogs($rMsg);
                        return $msg;
                    }
                    
                }else{
                    $msg['pesan'] = "Gagal menambah data, Periksa No KTP dan Tgl Lahir";
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

    
    function UploadData($data){
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('max_execution_time', '0'); // for infinite time of execution 
        $rs['berhasil'] = array();
        $rs['gagal'] = array();
            try {
                $target = basename($data['name']);
                move_uploaded_file($data['tmp_name'], $target);
                chmod($target,0777);
                $inputFileName = $target;
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
                $no=1;
                foreach ($objWorksheet->getRowIterator() as $row) {
                    $sheet_start2 = $no++;
                    if($sheet_start2 > 1){
                        $res['NoKtp'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("B$sheet_start2")->getValue()); 
                        $res['Nama'] = anti_injection(strtoupper($objPHPExcel->getActiveSheet()->getCell("C$sheet_start2")->getValue())); 
                        $res['TglLahir'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("D$sheet_start2")->getValue()); 
                        $res['Nik'] = anti_injection($objPHPExcel->getActiveSheet()->getCell("E$sheet_start2")->getValue()); 
                        if($res['NoKtp'] != ""){
                            $TambahNrp = TambahData($res);
                            if($TambahNrp['status'] == "sukses"){
                                UpdateMasterBiodata($res['NoKtp']);
                                $res['Keterangan'] = $TambahNrp['pesan'];
                                $rs['berhasil'][] = $res;
                            }else{
                                $res['Keterangan'] = $TambahNrp['pesan'];
                                $rs['gagal'][] = $res;
                            }
                            
                        }
                        
                    }
                }
                // unlink($target);
                $msg['status'] = "sukses";
                $msg['pesan'] = count($rs['berhasil'])." data nrp berhasil diupload dan ".count($rs['gagal'])." data mutasi gagal diupload";
                $msg['data'] = $rs;
                return $msg;
            } catch (Exception $th) {
                return $th->getMessage();
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

    

    

?>