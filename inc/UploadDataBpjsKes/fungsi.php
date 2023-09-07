<?php
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
                    $res['NoKtp'] = $objPHPExcel->getActiveSheet()->getCell("B$sheet_start2")->getValue(); 
                    $res['NoJkn'] = $objPHPExcel->getActiveSheet()->getCell("C$sheet_start2")->getValue(); 
                    $res['StatusKepesertaan'] = $objPHPExcel->getActiveSheet()->getCell("D$sheet_start2")->getValue(); 
                    $res['Thn'] = $objPHPExcel->getActiveSheet()->getCell("E$sheet_start2")->getValue(); 
                    $res['Bln'] = $objPHPExcel->getActiveSheet()->getCell("F$sheet_start2")->getValue(); 
                    $res['Tgl'] = $objPHPExcel->getActiveSheet()->getCell("G$sheet_start2")->getValue(); 
                    $res['Flag'] = $objPHPExcel->getActiveSheet()->getCell("H$sheet_start2")->getValue(); 
                    if($res['NoKtp'] != "" && $res['NoJkn'] != "" && $res['StatusKepesertaan'] != "" && $res['Thn'] != "" && $res['Bln'] != "" && $res['Tgl'] != "" && $res['Flag'] != "" ){
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
                $error = "";
                $data['error'] = $error;
                $data['msg'] = "sukses";
                return $data;
            }else{
                $error = "data BPJS KESEHATAN dengan no ktp ".$NoKtp." gagal di proses. data ini belum ada dalam sistem";
                $data['error'] = $error;
                $data['msg'] = "gagal";
                return $data;
            }
        }
        
        
    }

    function LogsUpload($Logs,$status){
        try {
            $TglCreate = date("Y-m-d H:i:s");
            $modul = $_SESSION['page'];
            $UserId = $_SESSION['Id'];
            $sql = "INSERT INTO ims_log_upload_data SET Modul = :Modul, `Data` = :Datas, `Status` = :Statuss, TglCreate = :TglCreate, UserId = :UserId";
            $query = $GLOBALS['db']->prepare($sql);
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
                        $TglDaftar = $r['Thn']."-".$r['Bln']."-".$r['Tgl'];
                        $sql = "INSERT INTO ims_bpjs_kesehatan SET NoKtp = :NoKtp, NoJkn = :NoJkn, StatusKepesertaan = :StatusKepesertaan, TglDaftar = :TglDaftar, Flag = :Flag, TglCreate = :TglCreate, UserId = :UserId";
                        $stmt = $GLOBALS['db']->prepare($sql);
                        $stmt->bindParam("NoKtp", $r['NoKtp'],PDO::PARAM_STR);
                        $stmt->bindParam("NoJkn", $r['NoJkn'],PDO::PARAM_STR);
                        $stmt->bindParam("StatusKepesertaan", $r['StatusKepesertaan'],PDO::PARAM_STR);
                        $stmt->bindParam("TglDaftar", $TglDaftar,PDO::PARAM_STR);
                        $stmt->bindParam("Flag", $Flag,PDO::PARAM_STR);
                        $stmt->bindParam("TglCreate", $TglCreate,PDO::PARAM_STR);
                        $stmt->bindParam("UserId", $UserId,PDO::PARAM_STR);
                        $stmt->execute();
                        if($stmt){
                            $NoKtpMaster[] = $r['NoKtp'];
                        }
                        $succes[] = "data BPJS KESEHATAN dengan no ktp ".$r['NoKtp']." berhasil diupload";

                    } catch (PDOException $e) {
                        $error[] = "data BPJS KESEHATAN dengan no ktp ".$r['NoKtp']." gagal diuplaod. Pesan error : ".$e->getMessage();
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

    function UpdateBiodataMaster($NoKtp){
        try {
            foreach ($NoKtp as $key => $Ktp) {
                TambahMasterBiodata($Ktp);
            }
            return true;
        } catch (Exception $th) {
            return $th->getMessage();
        }
        
    }

    function TambahMasterBiodata($NoKtp){
        try {
            $r = $GLOBALS['db']->query("SELECT * FROM ims_bpjs_kesehatan WHERE NoKtp = '$NoKtp' ORDER BY Flag DESC, TglDaftar DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            $rs = json_encode($r);
            $rs = base64_encode($rs);
            $sql = "UPDATE ims_master_biodata SET  BpjsKes = '$rs' WHERE NoKtp = '$NoKtp'";
            $GLOBALS['db']->query($sql); 
            
            $res['status'] = "sukses";
            $res['pesan'] = "BPJS Kesehatan berhasil diupdate ke master biodata denga No KTP ".$NoKtp;
            InsertLogs($res['pesan']);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }

    

    

?>