<?php
session_start();
include_once '../../config/config.php';
include_once 'fungsi.php';

$UserUpdate = 1;//$_SESSION['Id'];
$date = date("Y-m-d H:i:s");
$proses = isset($_REQUEST['proses']) ? $_REQUEST['proses'] : "";
switch ($proses) {
	case 'DetailData':
		try {
			$data = array(
				"Page" => $_POST['Page'],
				"Search" => $_POST['Search'],
				"RowPage" => $_POST['RowPage']
			);
			$result = DetailData($data);
		} catch (PDOException $e) {
			$result = $e->getMessage();
		}
		echo json_encode($result);
		break;
	case 'Crud':
		$aksi = $_POST['aksi'];
		
		switch ($aksi) {
			case 'insert':
				try {
					$data = array(
						"NoKtp" => $_POST['NoKtp'],
						"Tmt" => $_POST['Tmt'],
						"NoDokumen" => $_POST['NoDokumen'],
						"Kategori" => $_POST['Kategori'],
						"Keterangan" => $_POST['Keterangan'],
						"File" => $_FILES['File'],
						"Dir" => "../../File/SkPemberhentian/"
					);
					$pushdata = TambahData($data);
					echo json_encode($pushdata);
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
					echo json_encode($msg);
				}
				break;
			case 'update':
				try {
					$data = array(
						"Id" => $_POST['Id'],
						"NoKtp" => $_POST['NoKtpEdit'],
						"Tmt" => $_POST['Tmt'],
						"NoDokumen" => $_POST['NoDokumen'],
						"Kategori" => $_POST['Kategori'],
						"Keterangan" => $_POST['Keterangan'],
						"File" => $_FILES['File'],
						"Dir" => "../../File/SkPemberhentian/"
					);
					$pushdata = UbahData($data);
					echo json_encode($pushdata);
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
					echo json_encode($msg);
				}
				
				
				break;
			case 'delete':
				try {
					$data = array(
						"Id" => $_POST['Id'],
						"Dir" => "../../File/SkPemberhentian/"
					);
					$pushdata = HapusData($data);
					echo json_encode($pushdata);
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
					echo json_encode($msg);
				}
				break;
		}
		break;

	case 'ShowData':
		$Id = $_POST['Id'];
		$res = ShowData($Id);
		echo json_encode($res);
		break;
	case 'LodaData';
		$rule = $_POST['rule'];
		switch ($rule) {
			case 'TenagaKerja':
				$TenagaKerja = getTenagaKerja();
				echo json_encode($TenagaKerja);
				break;
		}

		break;
	
	
}

?>