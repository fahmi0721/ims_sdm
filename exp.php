<?php
	$str ="eyJLb2RlQ2FiYW5nIjoiMDMwIiwiRnJvbSI6IkFnYW1hIiwiQWdhbWEiOiJBLTA0In0";
	$dt = base64_decode($str);
	$js = json_decode($dt,true);
	echo "<pre>";
		print_r($js);
	echo "</pre>";
?>