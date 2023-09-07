<?php 
$page = isset($_GET['page']) ? $_GET['page'] : null;
$PageKhusus = array("Users","AksesMenu","Menu","Logs","LogUpload","Dashboard","UploadNrp");
if($page != null){
	$page = str_replace("../", "", addslashes($page));
	$files = "inc/".$page."/detail.php";
	if(file_exists($files)){
		if(in_array($page,$PageKhusus)){
			if($_SESSION['level'] == 0){
				include $files;
			}else{
				echo "<div class='error-page'>
					<h2 class='headline text-yellow' style='margin-top:-15px;'> 404</h2>

					<div class='error-content'>
					<h2><i class='fa fa-warning text-yellow'></i> Oops! Page not found.</h2>
					<h5>Halaman Yang Anda Pilih Tidak Ditemukan Oleh Sistem. Silahkan Hubungi Administrator.</h5>
					</div>
				</div>";
			}
		}else{
			$CekAkses = cekMenuAkes($page);
			if($CekAkses){
				include $files;
			}else{
				echo "<div class='error-page'>
					<h2 class='headline text-yellow' style='margin-top:-15px;'> 404</h2>

					<div class='error-content'>
					<h2><i class='fa fa-warning text-yellow'></i> Oops! Page not found.</h2>
					<h5>Halaman Yang Anda Pilih Tidak Ditemukan Oleh Sistem. Silahkan Hubungi Administrator.</h5>
					</div>
				</div>";
			}
		}
		
	}else{
		echo "<div class='error-page'>
	        <h2 class='headline text-yellow' style='margin-top:-15px;'> 404</h2>

	        <div class='error-content'>
	          <h2><i class='fa fa-warning text-yellow'></i> Oops! Page not found.</h2>
	          <h5>Halaman Yang Anda Pilih Tidak Ditemukan Oleh Sistem. Silahkan Hubungi Administrator.</h5>
	        </div>
	      </div>";
	}
}else{
	//include_once 'inc/home.php';
	$sql = "SELECT Direktori FROM ims_dashboard WHERE Flag = '1' ORDER BY Urutan ASC";
	$query = $db->query($sql);
	$row = $query->rowCount();
	if($row > 0){
		while($r = $query->fetch(PDO::FETCH_ASSOC)){
			$ds = "Mod/".$r['Direktori']."/detail.php";
			if(file_exists($ds)){
				include $ds;
			}
			$js = "Mod/".$r['Direktori']."/main.js";
			if(file_exists($js)){
				echo "<script src='".$js."'></script>";
			}
		}
	}else{
		echo "<div class='error-page'>
				<h2 class='headline text-green' style='margin-top:-15px;'> 200</h2>

				<div class='error-content'>
				<h2><i class='fa fa-check text-green'></i> Oops! Page not active.</h2>
				<h5>Halaman Dashboard belum diaktifkan. Silahkan Hubungi Administrator.</h5>
				</div>
			</div>";
	}
}
?>