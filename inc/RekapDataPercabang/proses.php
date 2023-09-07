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
				"KodeBranch" => $_POST['KodeBranch'],
				"KodeDivisi" => $_POST['KodeDivisi'],
				"KodeSeksi" => $_POST['KodeSeksi'],
				"KodeSubDivisi" => $_POST['KodeSubDivisi']
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
			case 'Branch':
				try {
					$LoadData = Branch();
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'Divisi':
				try {
					$LoadData = Divisi();
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'SubDivisi':
				try {
					$LoadData = SubDivisi();
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'Seksi':
				try {
					$LoadData = Seksi();
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			case 'coba': 
				try {
					$LoadData = FilterData($_POST);
					echo json_encode($LoadData);
				} catch (Throwable $e) {
					echo json_encode($e->getMessage());
				}
				break;
			
		}
		break;
	
}

?>