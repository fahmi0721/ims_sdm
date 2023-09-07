<?php
// include_once 'config/config.php';

// $starttime = explode(' ', microtime());
// $starttime = $starttime[1] + $starttime[0];
// UpdateMasterBiodata();
// $load = microtime();
// $loadtime = explode(' ', microtime()); 
// $loadtime = $loadtime[0]+$loadtime[1]-$starttime;
// echo "Page generated in ".$load." seconds";
// echo " | ";
// echo "Peak memory usage: ".round(memory_get_peak_usage()/1048576, 2), "MB";

// function getNamaMaster($Kode){
//   $sql = "SELECT Nama FROM ims_master_pendidikan_formal WHERE Kode = '$Kode'";
//   $query = $GLOBALS['db']->query($sql);
//   $r = $query->fetch(PDO::FETCH_ASSOC);
//   return $r['Nama'];
// }

// function getSubNamaMaster($Kode){
//   $sql = "SELECT Nama FROM ims_master_sub_pendidikan_formal WHERE Kode = '$Kode'";
//   $query = $GLOBALS['db']->query($sql);
//   $r = $query->fetch(PDO::FETCH_ASSOC);
//   return $r['Nama'];
// }

// function UpdateMasterBiodata(){
//   try {
//       $query = $GLOBALS['db']->query("SELECT * FROM ims_pendidikan_formal GROUP BY NoKtp ORDER BY TahunMulai DESC");
//       while($r = $query->fetch(PDO::FETCH_ASSOC)){
//         $KodePendidikanFormal = $r['KodeMaster'];
//         $KodeSubPendidikanFormal = $r['KodeSubMaster'];
//         $r['NamaPendidikan'] = getNamaMaster($r['KodeMaster']);
//         $r['NamaJurusan'] = getSubNamaMaster($r['KodeSubMaster']);
//         $rs = json_encode($r);
//         $rs = base64_encode($rs);
//         $sql = "UPDATE ims_master_biodata SET PendidikanFormal = '$rs', KodePendidikanFormal = '$KodePendidikanFormal', KodeSubPendidikanFormal = '$KodeSubPendidikanFormal' WHERE NoKtp = '".$r['NoKtp']."'";
//         $GLOBALS['db']->query($sql); 
      
//       }
//   } catch (PDOException $e) {
//       return $e->getMessage();
//   }
  
// }
    
?>