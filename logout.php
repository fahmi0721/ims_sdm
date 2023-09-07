<?php
require_once 'config/config.php';
session_start();
$Id = $_SESSION['Id'];
$data = array(
			"Modul" => "Logout",
			"Logs" => "Berhasil Keluar Dari Sistem",
			"UserId" => $Id
		);
		Logs($data);
session_destroy();
header("location:login.php");

