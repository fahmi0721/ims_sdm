<?php
    require_once '../../config/config.php';

    function get_data_tk($NoKtp){
        $db = $GLOBALS['db'];
        $sql = "SELECT a.id, a.Tmt, a.Nik, a.Nama, a.TptLahir, a.TglLahir, a.Alamat, a.NoHp,a.Npwp,
                    IF(JenisKelamin = 'L','LAKU-LAKI','PEREMPUAN') as JenisKelamin, 
                    timestampdiff(YEAR,TglLahir,Now()) as Usia,
                    CASE 
                    WHEN StatusKawin = '1' THEN 'Belum Menikah'
                    WHEN StatusKawin = '2' THEN 'Menikah'
                    WHEN StatusKawin = '3' THEN 'Janda/Duda'
                    END as StatusKawin,
                    b.Nama as Agama,
                    FROM_BASE64(c.SpkPengangkatan) as Jabatan,
                    FROM_BASE64(c.PendidikanFormal) as Pendidikan
                    FROM ims_master_tenaga_kerja a
                    INNER JOIN ims_agama b ON a.Agama = b.Kode
                    INNER JOIN ims_master_biodata c ON a.NoKtp = c.NoKtp
                    WHERE a.NoKtp = '$NoKtp'";
        $query = $db->query($sql);
        $dt = $query->fetch(PDO::FETCH_ASSOC);
        return $dt;
    }

?>