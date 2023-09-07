<?php
session_start();
include_once '../../config/config.php';
include_once 'fungsi.php';
$_SESSION['Id'] =1;
$UserUpdate = $_SESSION['Id'];
$date = date("Y-m-d H:i:s");
$proses = isset($_REQUEST['proses']) ? $_REQUEST['proses'] : "";
switch ($proses) {
	default :
		echo json_encode("ss");
		break;
	case 'DetailData':
		$data = array(
			"ShowTampil" => $_POST['ShowTampil'],
			"Search" => $_POST['Search'],
			"Page" => $_POST['Page']
		);
		$result = DetailData($data);
		echo json_encode($result);

		break;
	case 'Crud':
		$aksi = $_POST['aksi'];
		
		switch ($aksi) {
			case 'insert':
				try {
					$data = array(
						"IdTenagaKerja" => strtoupper($_POST['IdTenagaKerja']),
						"IdCabangLama" => $_POST['IdCabangLama'],
						"Nama" => $_POST['Nama'],
						"IdCabangBaru" => $_POST['IdCabangBaru'],
						"JabatanLama" => strtoupper($_POST['JabatanLama']),
						"JabatanBaru" => strtoupper($_POST['JabatanBaru']),
						"TMTLama" => $_POST['TMTLama'],
						"TMTBaru" => $_POST['TMTBaru'],
						"File" => $_FILES['Sk'],
						"Direktori" => "../../File/Sk/"
					);
					$pushdata = TambahData($data);
					if($pushdata['status'] == "sukses"){
						$msg['status'] = 0;
						$msg['pesan'] = $pushdata['pesan'];
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = $pushdata['pesan'];
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				echo json_encode($msg);

				break;
			case 'update':
				try {
					$data = array(
						"Id" => $_POST['Id'],
						"IdCabang" => $_POST['IdCabang'],
						"Nama" => $_POST['Nama'],
						"Jabatan" => strtoupper($_POST['Jabatan']),
						"TanggalMulai" => $_POST['TanggalMulai'],
						"TanggalSelesai" => $_POST['TanggalSelesai']
					);
					if($_FILES['Sk']['error'] == 0){
						$data['File'] = $_FILES['Sk'];
						$data['Direktori'] = "../../File/Sk/";
					}
					$pushdata = UbahData($data);
					if($pushdata['status'] == "sukses"){
						$msg['status'] = 0;
						$msg['pesan'] = $pushdata['pesan'];
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = $pushdata['pesan'];
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				
				echo json_encode($msg);
				break;
			case 'delete':
				try {
					$data = array(
						"Id" => $_POST['Id'],
						"Direktori" => "../../File/Sk/"
					);
					$pushdata = HapusData($data);
					if($pushdata['status'] == "sukses"){
						$msg['status'] = 0;
						$msg['pesan'] = $pushdata['pesan'];
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = $pushdata['pesan'];
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				echo json_encode($msg);
				break;
		}
		break;

	case 'ShowData':
		$Id = $_POST['Id'];
		$res = ShowData($Id);
		echo json_encode($res);
		break;
	
	
}

?>