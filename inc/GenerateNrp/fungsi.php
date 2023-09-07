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

    function getBulanD($bulan){
        $bulan = strtolower($bulan);
        $bulan_arr = array(
            "jan" => "01",
            "feb" => "02",
            "mar" => "03",
            "apr" => "04",
            "may" => "05",
            "jun" => "06",
            "jul" => "07",
            "aug" => "08",
            "sep" => "09",
            "oct" => "10",
            "nov" => "11",
            "dec" => "12",
        );
        return $bulan_arr[$bulan];
    }

    function getChangePeriode($tmt){
        $pisah = explode(" ",$tmt);
        $bulan = getBulanD($pisah[0]);
        $tahun = $pisah[1];
        return $tahun.$bulan;
    }


    function getNoUturNrpTerakhir(){
        $db = $GLOBALS['db'];
        $sql = "SELECT RIGHT(Nik,4) as Nrp FROM ims_master_tenaga_kerja   ORDER BY RIGHT(Nik,4) DESC LIMIT 1";
        $query = $db->query($sql);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data['Nrp'];

    }

    function getTenagaKerja($Periode,$NoUturNrpTerakhir){
        $result = array();
        $NoAwal = $NoUturNrpTerakhir + 1;
        $db = $GLOBALS['db'];
        $sql = "SELECT id, NoKtp, Nama, TglLahir,CONCAT(2,DATE_FORMAT(TglLahir,'%y%m')) as nrp_ FROM ims_master_tenaga_kerja WHERE Nik is null AND DATE_FORMAT(Tmt,'%Y%m') = '".$Periode."' ORDER BY TglLahir ASC";
        $query = $db->query($sql);
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $data['Nrp'] = $data['nrp_'].$NoAwal;
            $result[] = $data;
            $NoAwal++;
        }
        return $result;
    }
    
    function Generate($Tmt){
        $rs['berhasil'] = array();
        $rs['gagal'] = array();
            try {
                $Periode = getChangePeriode($Tmt);
                $NoUturNrpTerakhir = getNoUturNrpTerakhir();
                $data_pekerja = getTenagaKerja($Periode,$NoUturNrpTerakhir);
                $msg['status'] = "sukses";
                $msg['pesan'] = COUNT($data_pekerja)." berhasil di generate";
                $msg['data'] = $data_pekerja;
                return $msg;
            } catch (Exception $th) {
                return $th->getMessage();
            }
    }

    function CekNrp($Nrp){
        $koneksi = $GLOBALS['db'];
        $sql = "SELECT Nik FROM ims_master_tenaga_kerja WHERE Nik = '$Nrp'";
        $query = $koneksi->query($sql);
        $res = $query->rowCount();
        return $res;
    }

    function Update($data){
        $koneksi = $GLOBALS['db'];
        $CekNrp = CekNrp($data['Nik']);
        if($CekNrp > 0){
            $msg['pesan'] = "Gagal menambah data NRP  dengan no ktp <b>".$data['NoKtp']."</b>";
            $msg['status'] = "error";
            return $msg;

        }else{
            $sql = "UPDATE ims_master_tenaga_kerja SET Nik = :Nik WHERE NoKtp = :NoKtp";
            $exc = $koneksi->prepare($sql);
            $exc->bindParam('Nik', $data['Nik'], PDO::PARAM_STR);
            $exc->bindParam('NoKtp', $data['NoKtp'], PDO::PARAM_STR);
            $exc->execute();
            $msg['pesan'] = "Berhasil menambah data NRP  dengan no ktp <b>".$data['NoKtp']."</b>";
            $msg['status'] = "sukses";
            return $msg;
        }
    }

    function UpdateNrp($data){
        $berhasil = 0;
        $gagal = 0;
            try {
                for($i=0; $i<count($data['Nik']); $i++){
                    $res['Nik'] = $data['Nik'][$i];
                    $res['NoKtp'] = $data['NoKtp'][$i];
                    $update = Update($res);
                    if($update['status'] == "sukses"){
                        UpdateMasterBiodata($res['NoKtp']);
                        InsertLogs($update['pesan']);
                        $berhasil++;
                    }else{
                        InsertLogs($update['pesan']);
                        $gagal++;
                    }
                }
                $msg['status'] = "sukses";
                $msg['pesan'] = $berhasil." berhasil ditambhakan NRP dan ".$gagal." gagal ditambahkan NRP";
                $msg['data'] = array();
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