<?php

    function Filter($str){
        if(is_array($str)){
            if(!empty($str['IdUser']) || !empty($str['Tgl'])){
                $data = array();
                if(!empty($str['IdUser'])){ $data[] = "UserId = '".$str['IdUser']."'"; }
                if(!empty($str['Tgl'])){ $data[] = "DATE_FORMAT(TglCreate, '%Y-%m-%d') = '".$str['Tgl']."'"; }
                $fil = implode(" AND ",$data);
                return "WHERE ".$fil;
            }else{
                return "";    
            }
        }else{
            return "";
        }
    }


    function getNamaUser($Id){
        $sql = "SELECT Username, Nama, Jabatan FROM ims_users WHERE Id = :Id";
        $query = $GLOBALS['db']->prepare($sql);
        $query->bindParam("Id", $Id, PDO::PARAM_STR);
        $query->execute();
        $r = $query->fetch(PDO::FETCH_ASSOC);
        $User = !empty($r['Nama']) ? "<b>".$r['Nama']."</b><br><small>Username : ".$r['Username']."<br>Jabatan : ".$r['Jabatan']."</small>" : "<b>Anonym</b><br><small>Username : -<br>Jabatan : -</small>";
        return $User;
    }

    function getModul($Dir){
        $sql = "SELECT NamaMenu FROM ims_menu WHERE Direktori = :Direktori";
        $query = $GLOBALS['db']->prepare($sql);
        $query->bindParam("Direktori", $Dir, PDO::PARAM_STR);
        $query->execute();
        $r = $query->fetch(PDO::FETCH_ASSOC);
        $Modul = empty($r['NamaMenu']) ? $Dir : $r['NamaMenu'];
        return $Modul;
    }

    function DetailData($data){
        $db = $GLOBALS['db'];
        $result = array();
        $row = array(); 
        if(is_array($data)){
            $Search = array();
            $Page = $data['Page'];
            $RowPage = $data['RowPage'];
            $offset=($Page - 1) * $RowPage;
            $no=$offset+1;
            $Search['IdUser'] = $data['IdUser'] != "null" ? $data['IdUser'] : "";
            $Search['Tgl'] = $data['Tgl'];
            $FilterSearch = Filter($Search);
            $sql = "SELECT KodeError, Id, UserId, `Status`, Modul, TglCreate FROM ims_log_upload_data $FilterSearch";
            $query = $db->query($sql);
            $JumRow = $query->rowCount();
            $total_page = ceil($JumRow / $RowPage);
            $result['total_page'] = $total_page;
            $result['total_data'] = $JumRow;
            $result['data_new'] = $no;
            $sql = $sql." ORDER BY TglCreate DESC LIMIT ".$offset.",".$RowPage;
            $query = $db->query($sql);
            if($JumRow > 0){
                
                while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
                    $st = $res['Status'] == "success" ? "<label class='label label-".$res['Status']."'>".strtoupper($res['Status'])."</label>" : "<label class='label label-danger'>ERROR</label>";
                    $aksi = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Detail Logs' onclick=\"Detail('".$res['Id']."')\"><i class='fa fa-eye'></i></a>";
                    $row['No'] = $no;
                    $row['KodeError'] = $res['KodeError'];
                    $row['NamaUser'] = getNamaUser($res['UserId']);
                    $row['Modul'] = getModul($res['Modul']);
                    $row['Waktu'] = hari_indo($res['TglCreate'])."<br><small>Tgl : ".tgl_indo($res['TglCreate'])."<br>Jam : ".jam_indo($res['TglCreate'])."</small>";
                    $row['Status'] = "<center>".$st."</center>";
                    $row['Aksi'] = "<center>".$aksi."</center>";
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

    function getUser(){
        $sql = "SELECT Id, Jabatan, Nama FROM ims_users ORDER BY Nama ASC";
        $query = $GLOBALS['db']->query($sql);
        $r = array();
        while($res = $query->fetch(PDO::FETCH_ASSOC)){
            $r[] = $res;
        }
        return $r;
    }

    function getLogs($Id){
        $r = $GLOBALS['db']->query("SELECT `Data` FROM ims_log_upload_data WHERE Id = '$Id'")->fetch(PDO::FETCH_ASSOC);
        $res = base64_decode($r['Data']);
        $res = json_decode($res,true);
        $res['Data'] = $res;
        $res['Row'] = count($res);
        return $res;
    }
    
    

    

?>