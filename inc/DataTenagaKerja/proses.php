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
				"KodeCabang" => $_POST['KodeCabang'],
				"KodeBranch" => $_POST['KodeBranch'],
				"Dir" => "../../img/FotoTenagaKerja/",
				"RowPage" => $_POST['RowPage']
			);
			$result = DetailData($data);
		} catch (PDOException $e) {
			$result = $e->getMessage();
		}
		echo json_encode($result);
		break;
	
	// case 'ShowData':
	// 	$Id = $_POST['Id'];
	// 	$res = ShowData($Id);
	// 	echo json_encode($res);
	// 	break;
	case 'LoadData':
		$rule = $_POST['rule'];
		switch ($rule) {
			case 'Branch':
				try {
					$LoadData = Branch();
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
			break;

			case 'UnitKerja':
				try {
					$Id = $_POST['Id'];
					$LoadData = UnitKerja($Id);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
			break;
				
			// case 'TenagaKerja':
			// 	try {
			// 		$Id = $_POST['Id'];
			// 		$LoadData = TenagaKerja($Id);
			// 		echo json_encode($LoadData);
			// 	} catch (Throwable $e) {
			// 		echo json_encode($e->getMessage());
			// 	}
			// 	break;
			
		}
		break;
	
}

?>