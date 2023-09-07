<?php
session_start();
include_once '../../config/config.php';
include_once 'fungsi.php';

$UserUpdate = 1;
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
						"Nama" => strtoupper($_POST['Nama']),
						"TptLahir" => strtoupper($_POST['TptLahir']),
						"TglLahir" => $_POST['TglLahir'],
						"StatusKawin" => $_POST['StatusKawin'],
						"JenisKelamin" => $_POST['JenisKelamin'],
						"Agama" => $_POST['Agama'],
						"Npwp" => $_POST['Npwp'],
						"GolDarah" => $_POST['GolDarah'],
						"NoHp" => $_POST['NoHp'],
						"Tmt" => $_POST['Tmt'],
						"Alamat" => $_POST['Alamat'],
						"Foto" => $_FILES['Foto'],
						"Ktp" => $_FILES['Ktp'],
						"Flag" => $_POST['Flag'],
						"Dir" => "../../img/FotoTenagaKerja/",
						"Dir2" => "../../img/FotoKtp/"
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
						"Nama" => strtoupper($_POST['Nama']),
						"TptLahir" => strtoupper($_POST['TptLahir']),
						"TglLahir" => $_POST['TglLahir'],
						"StatusKawin" => $_POST['StatusKawin'],
						"JenisKelamin" => $_POST['JenisKelamin'],
						"Agama" => $_POST['Agama'],
						"Npwp" => $_POST['Npwp'],
						"GolDarah" => $_POST['GolDarah'],
						"NoHp" => $_POST['NoHp'],
						"Tmt" => $_POST['Tmt'],
						"Alamat" => $_POST['Alamat'],
						"Foto" => $_FILES['Foto'],
						"Ktp" => $_FILES['Ktp'],
						"Flag" => $_POST['Flag'],
						"Dir" => "../../img/FotoTenagaKerja/",
						"Dir2" => "../../img/FotoKtp/"
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
						"Dir" => "../../img/FotoTenagaKerja/",
						"Dir2" => "../../img/FotoKtp/"
					);
					$pushdata = HapusData($data);
					echo json_encode($pushdata);
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
					echo json_encode($msg);
				}
				break;
			default :
				echo "s";
				break;
		}
		break;

	case 'ShowData':
		$Id = $_POST['Id'];
		$res = ShowData($Id);
		echo json_encode($res);
		break;
	case 'GetAgama':
		$data = ShowAgama();
		echo json_encode($data);
		break;
	
	
	
}

?>