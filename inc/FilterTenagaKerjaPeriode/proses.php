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
			case 'UnitKerja':
				try {
					$LoadData = UnitKerja();
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'TenagaKerja':
				try {
					$Id = $_POST['Id'];
					$LoadData = TenagaKerja($Id);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'coba': 
				try {
					$LoadData = MasterBiodata();
					arsort($LoadData,SORT_STRING);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			
		}
		break;
	
}

?>