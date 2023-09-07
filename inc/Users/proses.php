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
		$sql = "SELECT * FROM ims_users ORDER BY Id DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$lvl = array("Admin","Member"); 
				$aksi = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['Id']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['Id']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$status = $res['Status'] == "0" ? "<a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Aktifkan' onclick=\"UpdateStatus('".$res['Id']."', 1)\"><i class='fa fa-lock'></i></a>" : "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Non Aktifkan' onclick=\"UpdateStatus('".$res['Id']."', '0')\"><i class='fa fa-unlock'></i></a>" ;
				$row['No'] = $no;
				$row['Username'] = $res['Username'];
				$row['Nama'] = $res['Nama'];
				$row['Jabatan'] = $res['Jabatan'];
				$row['Level'] = $lvl[$res['Level']];
				$row['Status'] = "<center>".$status."</center>";
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
		if($aksi != "UpdateStatus"){
			$data = array(
				"Nama" => $_POST['Nama'],
				"Jabatan" => $_POST['Jabatan'],
				"Username" => $_POST['Username'],
				"Password" => $_POST['Password'],
				"Level" => $_POST['Level']
			);
		}else{
			$data = array();
		}
		switch ($aksi) {
			case 'insert':
				try {
					$cekData = CekData($data);
					if($cekData > 0){
						$msg['status'] = 2;
						$msg['pesan'] = "Username Telah Digunakan!";
						echo json_encode($msg);
						exit();
					}
					$pushdata = TambahData($data);
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data User Berhasil Ditambahkan!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data User  Gagal Ditambahkan!";
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = "Error : ".$e->getMessage();
				}
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
				echo json_encode($msg);
				break;
			case 'delete':
				try {
					$Id = $_POST['Id'];
					$pushdata = HapusData($Id);
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Users Berhasil Dihapus!";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Users  Gagal Dihapus!";
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] =$e->getMessage();
				}
				echo json_encode($msg);
				break;
			case 'UpdateStatus': 
				try {
					$Id = $_POST['Id'];
					$Status = $_POST['Status'];
					$pushdata = UpdateStatus($Id,$Status);
					$Statuss = ["Dinonaktifkan","Diaktifkan"];
					if($pushdata){
						$msg['status'] = 0;
						$msg['pesan'] = "Data Users Berhasil ".$Statuss[$Status]." !";
					}else{
						$msg['status'] = 1;
						$msg['pesan'] = "Data Users  Gagal ".$Statuss[$Status]." !";
					}
				} catch (PDOException $e) {
					$msg['status'] = 1;
					$msg['pesan'] = $e->getMessage();
				}
				
				echo json_encode($msg);
				break;
		}
		break;

	case 'ShowData':
		$Id = $_POST['Id'];
		$res = ShowData($Id);
		echo json_encode($res);
		break;
	
	
}

?>