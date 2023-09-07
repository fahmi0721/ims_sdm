<?php
session_start();
include_once '../../config/config.php';
include_once 'fungsi.php';

$UserUpdate = $_SESSION['Id'];
$date = date("Y-m-d H:i:s");
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";
switch ($proses) {
	case 'DetailData':
		$row = array(); 
		$data = array(); 
		$no=1;
		$sql = "SELECT * FROM ims_menu ORDER BY Id DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$status = $res['Status'] == "0" ? "<a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Aktifkan' onclick=\"Crud('".$res['Id']."#1', 'Status')\"><i class='fa fa-lock'></i></a>" : "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Non Aktifkan' onclick=\"Crud('".$res['Id']."#0', 'Status')\"><i class='fa fa-unlock'></i></a>" ;
				$row['No'] = $no;
				$row['NamaMenu'] = $res['NamaMenu'];
				$row['Direktori'] = $res['Direktori'];
				$row['ItemRoot'] = $res['ItemRoot'];
				$row['Status'] = "<center>".$status." <a class='btn btn-xs btn-primary' data-toggle='tooltip' title='List Aprovel' onclick=\"Crud('".$res['Id']."', 'ShowApproval')\"><i class='fa fa-sort'></i></a></center>";
				$row['Aksi'] = $aksi;
				$data['data'][] = $row;
				$no++;
			}
		}else{
			$data['data']='';
		}


		echo json_encode($data);
		break;
	case 'Crud':
		$aksi = $_POST['aksi'];
		$lisNot = array("InsertListApprovel", "Status");
		if(!in_array($aksi,$lisNot)){
			$data = array(
				"NamaMenu" => $_POST['NamaMenu'],
				"Icon" => $_POST['Icon'],
				"Direktori" => $_POST['Direktori'],
				"ItemRoot" => $_POST['ItemRoot']
			);
		}
		switch ($aksi) {
			case 'insert':
				try {
					$pushdata = TambahData($data);
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Menu Berhasil Ditambahkan!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Menu  Gagal Ditambahkan!";
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				$Logs['UserId'] = $_SESSION['Id'];
				$Logs['Logs'] = $msg['pesan'];
				$Logs['Modul'] = $_SESSION['page'];
				Logs($Logs);
				echo json_encode($msg);

				break;
			case 'update':
				try {
					$data['Id'] = $_POST['Id'];
					$pushdata = UbahData($data);
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Menu Berhasil Diubah!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Menu  Gagal Diubah!";
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				$Logs['UserId'] = $_SESSION['Id'];
				$Logs['Logs'] = $msg['pesan'];
				$Logs['Modul'] = $_SESSION['page'];
				Logs($Logs);
				echo json_encode($msg);
				break;
			case 'delete':
				try {
					$Id = $_POST['Id'];
					$pushdata = HapusData($Id);
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Menu Berhasil Dihapus!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Menu  Gagal Dihapus!";
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				$Logs['UserId'] = $_SESSION['Id'];
				$Logs['Logs'] = $msg['pesan'];
				$Logs['Modul'] = $_SESSION['page'];
				Logs($Logs);
				echo json_encode($msg);
				break;
			
			case 'Status':
				try {
					$data = array(
						"Id" => $_POST['Id'],
						"Status" => $_POST['Status']
					);
					$pushdata = UbahStatus($data);
					$stsu = array("Dinonaktifkan", "Diaktifkan");
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Menu Berhasil ".$stsu[$data['Status']]."!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Menu  Gagal ".$stsu[$data['Status']]."!";;
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				$Logs['UserId'] = $_SESSION['Id'];
				$Logs['Logs'] = $msg['pesan'];
				$Logs['Modul'] = $_SESSION['page'];
				Logs($Logs);
				echo json_encode($msg);
				break;
			
			case 'InsertListApprovel':
				try {
					$data = array(
						"IdUser" => $_POST['IdUser'],
						"IdMenu" => $_POST['IdMenu']
					);
					$res = CheckDataUserAprovel($data);
					if($res){
						$msg['status'] = 1;
						$msg['pesan'] = "Data User ini telah digunakan";
						$Logs['UserId'] = $_SESSION['Id'];
						$Logs['Logs'] = $msg['pesan'];
						$Logs['Modul'] = $_SESSION['page'];
						Logs($Logs);
						echo json_encode($msg);
						exit();
					}
					$pushdata = InsertListApprovel($data);
					$stsu = array("Dinonaktifkan", "Diaktifkan");
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data ListApprovel Berhasil Ditambah!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data ListApprovel Berhasil Ditambah!";
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				$Logs['UserId'] = $_SESSION['Id'];
				$Logs['Logs'] = $msg['pesan'];
				$Logs['Modul'] = $_SESSION['page'];
				Logs($Logs);
				echo json_encode($msg);

				break;
		}
		
		break;
	case 'ShowApproval':
		$Id = $_POST['Id'];
		$res = ShowListApprovel($Id);
		echo json_encode($res);
		break;
	case 'HapusListApproval':
		$Id = $_POST['Id'];
		try {
			$pushdata = HapusListApproval($Id);
			if($pushdata){
				$msg['status'] = 0;
				$msg['pesan'] = "Data List Approvel Berhasil Dihapus!";
			}else{
				$msg['status'] = 1;
				$msg['pesan'] = "Data List Approvel Berhasil Dihapus!";
			}
		} catch (PDOException $e) {
			$msg['status'] = 1;
			$msg['pesan'] = $e->getMessage();
		}
		$Logs['UserId'] = $_SESSION['Id'];
		$Logs['Logs'] = $msg['pesan'];
		$Logs['Modul'] = $_SESSION['page'];
		Logs($Logs);
		echo json_encode($msg);
		break;
	
	case 'ShowData':
		$Id = $_POST['Id'];
		$res = ShowData($Id);
		echo json_encode($res);
		break;
	
	
}

?>