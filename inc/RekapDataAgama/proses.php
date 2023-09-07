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
				"Agama" => $_POST['Agama']
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
			case 'Agama':
				try {
					$LoadData = Agama();
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			
			
		}
		break;
	
}

?>