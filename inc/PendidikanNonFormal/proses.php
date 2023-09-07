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
						"KodeMaster" => $_POST['KodeMaster'],
						"Dari" => $_POST['Dari'],
						"Keterangan" => $_POST['Keterangan'],
						"Sampai" => $_POST['Sampai'],
						"File" => $_FILES['File'],
						"Flag" => $_POST['Flag'],
						"Dir" => "../../File/PendidikanNonFormal/"
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
						"NoKtp" => $_POST['NoKtp'],
						"KodeMaster" => $_POST['KodeMaster'],
						"Keterangan" => $_POST['Keterangan'],
						"Dari" => $_POST['Dari'],
						"Sampai" => $_POST['Sampai'],
						"File" => $_FILES['File'],
						"Flag" => $_POST['Flag'],
						"Dir" => "../../File/PendidikanNonFormal/"
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
						"Dir" => "../../File/PendidikanNonFormal/"
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
	case 'LoadData':
		$rule = $_POST['rule'];
		switch ($rule) {
			case 'TenagaKerja':
				$LoadData = getTeagaKerja();
				echo json_encode($LoadData);
				break;
			case 'MasterPendidikanNonFormal':
				$LoadData = getMasterPendidikanNonFormal();
				echo json_encode($LoadData);
				break;
		}
		break;
	
	
	
}

?>