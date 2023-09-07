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
	
	case 'LodaData';
		$rule = $_POST['rule'];
		switch ($rule) {
			case 'User':
				$User = getUser();
				echo json_encode($User);
				break;
			case 'Logs':
				$Id = $_POST['Id'];
				$Res = getLogs($Id);
				echo json_encode($Res);
				break;
		}

		break;
	
	
}

?>