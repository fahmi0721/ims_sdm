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
				"Key" => $_POST['Key'],
				"Page" => $_POST['Page'],
				"Dir" => "../../img/FotoTenagaKerja/",
				"RowPage" => $_POST['RowPage']
			);
			$result = DetailData($data);
		} catch (PDOException $e) {
			$result = $e->getMessage();
		}
		echo json_encode($result);
		break;
	
	
	case 'LoadData':
		$rule = $_POST['rule'];
		switch ($rule) {
			case 'Biodata':
				try {
					$NoKtp =  $_POST['NoKtp'];
					$LoadData = Biodata($NoKtp);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'PendidikanFormal':
				try {
					$NoKtp =  $_POST['NoKtp'];
					$LoadData = PendidikanFormal($NoKtp);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'PendidikanNonFormal':
				try {
					$NoKtp =  $_POST['NoKtp'];
					$LoadData = PendidikanNonFormal($NoKtp);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'Keluarga':
				try {
					$NoKtp =  $_POST['NoKtp'];
					$LoadData = Keluarga($NoKtp);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;

			case 'RiwayatKerja':
				try {
					$NoKtp =  $_POST['NoKtp'];
					$LoadData = RiwayatKerja($NoKtp);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			
			case 'NomorRekening':
				try {
					$NoKtp =  $_POST['NoKtp'];
					$LoadData = NomorRekening($NoKtp);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			
			
		}
		break;
	
}

?>