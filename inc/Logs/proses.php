<?php
session_start();
include_once '../../config/config.php';
include_once 'fungsi.php';

$UserUpdate = $_SESSION['Id'];
$date = date("Y-m-d H:i:s");
$proses = isset($_REQUEST['proses']) ? $_REQUEST['proses'] : "";
switch ($proses) {
	case 'DetailData':
		try {
			$data = array(
				"Page" => $_POST['Page'],
				"RowPage" => $_POST['RowPage'],
				"IdUser" => $_POST['IdUser'],
				"Tgl" => $_POST['Tgl']
			);
			$result =DetailData($data);
		} catch (PDOException $e) {
			$result = $e->getMessage();
		}
		echo json_encode($result);
		break;
	// case 'Crud':
	// 	$aksi = $_POST['aksi'];
	// 	switch ($aksi) {
	// 		case 'insert':
	// 			try {
	// 				$data = array(
	// 					"NoKtp" => $_POST['NoKtp'],
	// 					"Cif" => $_POST['Cif'],
	// 					"NoAccount" => $_POST['NoAccount'],
	// 					"TglDaftar" => $_POST['TglDaftar'],
	// 					"Flag" => $_POST['Flag'],
	// 					"File" => $_FILES['File'],
	// 					"Dir" => "../../File/Dplk/"
	// 				);
	// 				$pushdata = TambahData($data);
	// 				echo json_encode($pushdata);
	// 			} catch (PDOException $e) {
	// 				$msg['status'] = 1;
	// 				$msg['pesan'] = $e->getMessage();
	// 				echo json_encode($msg);
	// 			}
	// 			break;
	// 		case 'update':
	// 			try {
	// 				$data = array(
	// 					"Id" => $_POST['Id'],
	// 					"NoKtp" => $_POST['NoKtp'],
	// 					"Cif" => $_POST['Cif'],
	// 					"NoAccount" => $_POST['NoAccount'],
	// 					"TglDaftar" => $_POST['TglDaftar'],
	// 					"Flag" => $_POST['Flag'],
	// 					"File" => $_FILES['File'],
	// 					"Dir" => "../../File/Dplk/"
	// 				);
	// 				$pushdata = UbahData($data);
	// 				echo json_encode($pushdata);
	// 			} catch (PDOException $e) {
	// 				$msg['status'] = 1;
	// 				$msg['pesan'] = $e->getMessage();
	// 				echo json_encode($msg);
	// 			}
	// 			break;
	// 		case 'delete':
	// 			try {
	// 				$data = array(
	// 					"Id" => $_POST['Id'],
	// 					"Dir" => "../../File/Dplk/"
	// 				);
	// 				$pushdata = HapusData($data);
	// 				echo json_encode($pushdata);
	// 			} catch (PDOException $e) {
	// 				$msg['status'] = 1;
	// 				$msg['pesan'] = $e->getMessage();
	// 				echo json_encode($msg);
	// 			}
	// 			break;
	// 	}
	// 	break;

	// case 'ShowData':
	// 	$Id = $_POST['Id'];
	// 	$res = ShowData($Id);
	// 	echo json_encode($res);
	// 	break;
	case 'LodaData';
		$rule = $_POST['rule'];
		switch ($rule) {
			case 'User':
				$User = getUser();
				echo json_encode($User);
				break;
		}

		break;
	
	
}

?>