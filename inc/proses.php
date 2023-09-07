<?php 
session_start();
include "../config/config.php";
include "fungsi.php";
$proses=isset($_GET['proses'])?$_GET['proses']:null;

switch($proses){
    case 'GetLoadLogs':
        $Level = $_SESSION['level'];
        $Page = $_POST['page'];
        if($Level == 0){
            $res = LogsAdmin($Page);
        }else{
            $res = LogsMember($Page);
        }

        echo json_encode($res);
        break;
}

?>


