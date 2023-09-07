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
		$sql = "SELECT a.Id, a.Nama, a.Jabatan FROM ims_users a INNER JOIN ims_menu_level b ON b.IdUser = a.Id GROUP BY b.IdUser ORDER BY Id DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$aksi = "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Akses Menu' onclick=\"Crud('".$res['Id']."', 'show')\"><i class='fa fa-eye'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Semua Akses' onclick=\"Crud('".$res['Id']."', 'hapus_semua')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['Nama'] = $res['Nama'];
				$row['Jabatan'] = $res['Jabatan'];
				$row['Menu'] = "<center>".$aksi."</center>";
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
		if($aksi != "UpdateAktif"){
			$data = array(
				"IdUser" => $_POST['IdUser'],
				"IdMenu" => $_POST['IdMenu'],
				"Status" => $_POST['Status']
			);
		}
		switch ($aksi) {
			case 'insert':
				try {
					$cekData = CekData($data);
					if($cekData > 0){
						$msg['status'] = 2;
						$msg['pesan'] = "Menu Telah Diberi Akses";
						echo json_encode($msg);
						exit();
					}
					$pushdata = TambahData($data);
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Akses Menu Berhasil Ditambahkan!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Akses Data Menu  Gagal Ditambahkan!";
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
						$msg['pesan'] = "Data Akses Menu Berhasil Diubah!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Akses Menu  Gagal Diubah!";
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
						$msg['pesan'] = "Data Akses Menu Berhasil Dihapus!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Akses Menu  Gagal Dihapus!";
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
			case 'delete_all':
				try {
					$IdUser = $_POST['Id'];
					$pushdata = HapusDataSemua($IdUser);
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Akses Menu Berhasil Dihapus Semua!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Akses Menu  Gagal Dihapus Semua!";
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
			case 'UpdateAktif':
				$data = array(
					"Id" => $_POST['Id'],
					"Aktif" => $_POST['Aktif']
				);

				try {
					$pushdata = UpdateAktif($data);
					$aktf = array("Dinonaktifkan","Diaktifkan");
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Akses Menu Berhasil ".$aktf[$data['Aktif']]."!.";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Akses Menu  Gagal ".$aktf[$data['Aktif']]."!.";
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

	case 'ShowData':
		$Id = $_POST['Id'];
		$res = ShowData($Id);
		echo json_encode($res);
		break;
	case 'DetailMenu';
		$IdUser = $_POST['IdUser'];
		try{
			$result = getDeatilMenu($IdUser);
		}catch(PDOException $e){
			$result = $e->getMessage();
		}
		echo json_encode($result);
		break;
	
	
}

?>