<?php
session_start();
include_once '../../config/config.php';
include_once 'fungsi.php';

$UserUpdate = $_SESSION['Id'];
$date = date("Y-m-d H:i:s");
$proses = isset($_REQUEST['proses']) ? $_REQUEST['proses'] : "";
switch ($proses) {
	case 'Generate':
		try {
			$Periode = $_POST['Periode'];
			$result = Generate($Periode);
		} catch (PDOException $e) {
			$result = $e->getMessage();
		}
		echo json_encode($result);
	break;

	case 'UpdateNrp':
		try {
			$data['NoKtp'] = $_POST['NoKtp'];
			$data['Nik'] = $_POST['Nik'];
			$result = UpdateNrp($data);
		} catch (PDOException $e) {
			$result = $e->getMessage();
		}
		echo json_encode($result);
	break;
	case 'LoadData';
		$rule = $_POST['rule'];
		switch ($rule) {
			case 'UnitKerja':
				$data = getUnitKerja();
				echo json_encode($data);
				break;
		}

		break;
	
	
}

?>