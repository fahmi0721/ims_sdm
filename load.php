<?php
include "config/config.php";
include "inc/fungsi.php";
$proses=isset($_GET['proses'])?$_GET['proses']:null;
if ($proses == "getDataItemRoot"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$sql = "SELECT Direktori,NamaMenu FROM ims_menu WHERE  NamaMenu LIKE '%".$searchTerm."%'  ORDER BY NamaMenu ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = $result['NamaMenu'];
		$row['ItemRoot'] = $result['Direktori'];
		$data[] = $row;
		
	}
	echo json_encode($data);
}else if ($proses == "getDataUsers"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$sql = "SELECT Id,Nama,Jabatan FROM ims_users WHERE  Nama LIKE '%".$searchTerm."%'  ORDER BY Nama ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = $result['Nama'];
		$row['IdUser'] = $result['Id'];
		$row['Jabatan'] = $result['Jabatan'];
		$data[] = $row;
		
	}
	echo json_encode($data);
}else if ($proses == "getDataMenu"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$sql = "SELECT Id,NamaMenu FROM ims_menu WHERE  NamaMenu LIKE '%".$searchTerm."%'  ORDER BY NamaMenu ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = $result['NamaMenu'];
		$row['IdMenu'] = $result['Id'];
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses == "getDataCabang"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$sql = "SELECT Id,NamaCabang, Lat, Lng, Alamat, Kontak FROM ims_cabang WHERE  NamaCabang LIKE '%".$searchTerm."%'  ORDER BY NamaCabang ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['NamaCabang']);
		$row['Id'] = $result['Id'];
		$row['Lat'] = $result['Lat'];
		$row['Lng'] = $result['Lng'];
		$row['Alamat'] = $result['Alamat'];
		$row['Kontak'] = $result['Kontak'];
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses =="LoadNotif"){
	UpdateNotif();
	echo "sukses";
}elseif($proses == "CountNotif"){
	$IdUser = $_POST['IdUser'];
	$res = LoadCountNotif($IdUser);
	if($res > 0){
		$data['status'] = 0;
		$data['row'] = $res;
	}else{
		$data['status'] = 1;
		$data['row'] = 0;
	}
	echo json_encode($data);
}elseif($proses == "ListNotif"){
	session_start();
	$IdUser = $_SESSION['Id'];
	$res = LoadListNotif($IdUser);
	echo json_encode($res);
}elseif($proses == "getDataPendidkan"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$filter = $_GET['IdCabang'] == "" ? "" : "AND IdCabang = '$_GET[IdCabang]'";
	$sql = "SELECT Pendidikan FROM ims_tenaga_kerja WHERE `Status` = '0' AND  Pendidikan LIKE '%".$searchTerm."%' $filter GROUP BY Pendidikan  ORDER BY Pendidikan ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['Pendidikan']);
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses == "getDataJabatan"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$filter = $_GET['IdCabang'] == "" ? "" : "AND IdCabang = '$_GET[IdCabang]'";
	$sql = "SELECT Jabatan FROM ims_tenaga_kerja WHERE `Status` = '0' AND  Jabatan LIKE '%".$searchTerm."%' $filter GROUP BY Jabatan  ORDER BY Jabatan ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['Jabatan']);
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses == "getDataAgama"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$filter = $_GET['IdCabang'] == "" ? "" : "AND IdCabang = '$_GET[IdCabang]'";
	$sql = "SELECT Agama FROM ims_tenaga_kerja WHERE `Status` = '0' AND  Agama LIKE '%".$searchTerm."%' $filter GROUP BY Agama  ORDER BY Agama ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['Agama']);
		$data[] = $row;
		
	}
	echo json_encode($data);

}elseif($proses == "getDataNikUsers"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$filter = $_GET['IdCabang'] == "" ? "" : "AND IdCabang = '$_GET[IdCabang]'";
	$sql = "SELECT Nama,NoKtp,Jabatan,Pendidikan,TglLahir,TptLahir,TMT,Agama FROM ims_tenaga_kerja WHERE `Status` = '0' AND NoKtp LIKE '%".$searchTerm."%' $filter ORDER BY Nama ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = $result['NoKtp'];
		$row['Nama'] = strtoupper($result['Nama']);
		$row['Jabatan'] = strtoupper($result['Jabatan']);
		$row['Pendidikan'] = strtoupper($result['Pendidikan']);
		$row['TglLahir'] = $result['TglLahir'];
		$row['TMT'] = $result['TMT'];
		$row['TptLahir'] = strtoupper($result['TptLahir']);
		$row['Agama'] = strtoupper($result['Agama']);
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses == "getDataCifPeserta"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$filter = $_GET['IdCabang'] == "" ? "" : "AND IdCabang = '$_GET[IdCabang]'";
	$sql = "SELECT CifPeserta FROM ims_dplk WHERE `Status` = '1' AND  CifPeserta LIKE '%".$searchTerm."%' $filter   ORDER BY CifPeserta ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['CifPeserta']);
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses == "getDataNamaDplk"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$filter = $_GET['IdCabang'] == "" ? "" : "AND IdCabang = '$_GET[IdCabang]'";
	$sql = "SELECT Nama FROM ims_dplk WHERE `Status` = '1' AND  Nama LIKE '%".$searchTerm."%' $filter   ORDER BY Nama ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['Nama']);
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses == "getDataNikDplk"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$filter = $_GET['IdCabang'] == "" ? "" : "AND IdCabang = '$_GET[IdCabang]'";
	$sql = "SELECT NoKtp FROM ims_dplk WHERE `Status` = '1' AND  NoKtp LIKE '%".$searchTerm."%'  $filter   ORDER BY NoKtp ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['NoKtp']);
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses == "getDataTenagaKerjaDplk"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$sql = "SELECT a.Id, a.Nama, a.CifPeserta,a.NoAkunDplk, b.NamaCabang  FROM ims_dplk a INNER JOIN ims_cabang b ON a.IdCabang = b.Id WHERE a.Status = '1' AND  ( a.Nama LIKE '%".$searchTerm."%' OR a.CifPeserta LIKE '%".$searchTerm."%' OR a.NoAkunDplk LIKE '%".$searchTerm."%')  ORDER BY Nama ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['Nama']);
		$row['IdDplk'] = $result['Id'];
		$row['CifPeserta'] = $result['CifPeserta'];
		$row['NoAkunDplk'] = $result['NoAkunDplk'];
		$row['UnitKerja'] = $result['NamaCabang'];
		$data[] = $row;
		
	}
	echo json_encode($data);
}elseif($proses == "getDataCifPesertaAll"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$filter = $_GET['IdCabang'] == "" ? "" : "AND IdCabang = '$_GET[IdCabang]'";
	$sql = "SELECT Nama, CifPeserta FROM ims_dplk WHERE `Status` = '1' AND  (CifPeserta LIKE '%".$searchTerm."%' OR Nama LIKE '%".$searchTerm."%' OR NoAkunDplk LIKE '%".$searchTerm."%' ) $filter   ORDER BY CifPeserta ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['CifPeserta']);
		$row['Nama'] = strtoupper($result['Nama']);
		$data[] = $row;
		
	}
	echo json_encode($data);

}elseif($proses == "getDataTenagaMutasi"){
	$row=array();
	$data=array();
	$searchTerm=isset($_GET['term'])?$_GET['term']:null;
	$sql = "SELECT a.Id, a.Nama, a.TptLahir, a.TglLahir, a.Jabatan, b.NamaCabang, a.IdCabang, a.Pendidikan, a.NoKtp FROM ims_tenaga_kerja a INNER JOIN ims_cabang b ON a.IdCabang = b.Id WHERE a.Status = '0' AND (a.NoKtp LIKE '$searchTerm%' OR a.Nama LIKE '%$searchTerm%') ORDER BY a.Nama ASC LIMIT 10";
	$query = $db->query($sql);
	while($result = $query->fetch(PDO::FETCH_ASSOC)){
		$row['label'] = strtoupper($result['NoKtp']);
		$row['Id'] = $result['Id'];
		$row['IdCabang'] = $result['IdCabang'];
		$row['Nama'] = $result['Nama'];
		$row['Jabatan'] = $result['Jabatan'];
		$row['NamaCabang'] = $result['NamaCabang'];
		$row['Pendidikan'] = $result['Pendidikan'];
		$row['TTL'] = $result['TptLahir'].", ".tgl_indo($result['TglLahir']);
		$data[] = $row;
		
	}
	echo json_encode($data);
}



?>