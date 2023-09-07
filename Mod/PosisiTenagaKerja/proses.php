<?php
session_start();
include_once '../../config/config.php';
include_once 'fungsi.php';
$date = date("Y-m-d H:i:s");
$proses = isset($_REQUEST['proses']) ? $_REQUEST['proses'] : "";
switch ($proses) {

	case 'LoadData':
		$res = LoadData();
		echo json_encode($res);
		break;
	
	
	
	
}

?>