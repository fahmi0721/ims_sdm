<?php
require_once 'config/config.php';
require_once 'inc/fungsi.php';

$username = $_POST['username'];
$password = md5("ims".$_POST['password']);
$status =1;
$sql = "SELECT * FROM ims_users WHERE Username = :Username AND `Status` = :Statuss";
$query = $db->prepare($sql);
$query->bindParam("Username",$username,PDO::PARAM_STR);
$query->bindParam("Statuss",$status,PDO::PARAM_STR);
$query->execute();

$row = $query->rowCount();
$data = $query->fetch(PDO::FETCH_ASSOC);
if($row > 0){
	if($data['Password'] == $password){
		session_start();
		$_SESSION['username'] = $data['Username'];
		$_SESSION['nama_user'] = $data['Nama'];
		$_SESSION['level'] = $data['Level'];
		$_SESSION['Jabatan'] = $data['Jabatan'];
		$_SESSION['Id'] = $data['Id'];
		$data = array(
			"Modul" => "Login",
			"Logs" => "Berhasil masuk ke sistem",
			"UserId" => $data['Id']

		);
		Logs($data);
		header("location:index.php");
	}else{
		$data = array(
			"Modul" => "Login",
			"Logs" => "Password yang dimassukkan salah",
			"UserId" => $data['Id']
		);
		Logs($data);
		header("location:login.php?status=108&error=Password yang anda masukkan salah!.");
	}
}else{
		$data = array(
			"Modul" => "Login",
			"Logs" => "Seseorang mencoba masuk kesistem",
			"UserId" => 0
		);
		Logs($data);
	header("location:login.php?status=108&error=Uaername yang anda masukkan salah!.");
}
?>