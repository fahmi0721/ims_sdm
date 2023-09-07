<?php
	$options = [
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_CASE => PDO::CASE_NATURAL,
	    PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
	];
	try{
		$db=new PDO('mysql:host=localhost;dbname=ims_2021','admin','!Nt4n2020MK$',$options);
	}catch(PDOException $e) {
	    die("Database connection failed: " . $e->getMessage());
	}
?>
