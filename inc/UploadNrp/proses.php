<?php
session_start();
include_once '../../config/config.php';
include_once 'fungsi.php';

$UserUpdate = $_SESSION['Id'];
$date = date("Y-m-d H:i:s");
$proses = isset($_REQUEST['proses']) ? $_REQUEST['proses'] : "";
switch ($proses) {
	case 'Upload':
		ini_set('memory_limit', '256M');
		ini_set("precision", "15");
		include_once '../../lib/PHPExcel/Classes/PHPExcel.php';
		include_once '../../lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
		try {
			$data = $_FILES['FileUpload'];
			
			$result = UploadData($data);
		} catch (PDOException $e) {
			$result = $e->getMessage();
		}
		echo json_encode($result);
	break;
	
	
	
}

?>