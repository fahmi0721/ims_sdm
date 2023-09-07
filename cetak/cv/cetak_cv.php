<?php
include "fungsi_cv.php";
include "../../lib/dompdf-0.8.2/autoload.inc.php";
use Dompdf\Dompdf;


$NoKtp = base64_decode($_GET['NoKtp']);
$dt_tk = get_data_tk($NoKtp);
$pendidikan = json_decode($dt_tk['Pendidikan'],true);
$spk = json_decode($dt_tk['Jabatan'],true);
// echo "<pre>";
// print_r($dt_tk);
$html = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>CURRICULUM VITAE ".strtoupper($dt_tk['Nama'])."</title>
    <style>
        body{
            font-family: arial;
        }
        h1{
            padding:0;
            /* margin:0; */
            text-decoration:underline;
        }
        .table-wrap{
            width:80%;
            
        }
    </style>
</head>
<body>
    <center><img src='logo.png'></center>
    <center><h1>CURRICULUM VITAE</h1></center>
    <table align='center' border=0 cellpadding=5 cellspacing=0 width='80%'>
        <tr>
            <td width='40%'>NAMA</td>
            <td width='1%'>:</td>
            <td width='59%'> ".$dt_tk['Nama']."</td>
        </tr>
        <tr>
            <td>NRP</td>
            <td>:</td>
            <td> ".$dt_tk['Nik']."</td>
        </tr>
        <tr>
            <td>TEMPAT, TANGGAL LAHIR</td>
            <td>:</td>
            <td> ".$dt_tk['TptLahir'].", ".tgl_indo($dt_tk['TglLahir'])."</td>
        </tr>
        <tr>
            <td>JENIS KELAMIN</td>
            <td>:</td>
            <td> ".$dt_tk['JenisKelamin']."</td>
        </tr>
        <tr>
            <td>USIA</td>
            <td>:</td>
            <td> ".$dt_tk['Usia']."</td>
        </tr>
        <tr>
            <td>AGAMA</td>
            <td>:</td>
            <td> ".$dt_tk['Agama']."</td>
        </tr>
        <tr>
            <td>ALAMAT</td>
            <td>:</td>
            <td> ".$dt_tk['Alamat']."</td>
        </tr>
        <tr>
            <td>NO TELEPON</td>
            <td>:</td>
            <td> ".$dt_tk['NoHp']."</td>
        </tr>
        <tr>
            <td>PENDIDIKAN TERAKHIR</td>
            <td>:</td>
            <td> ".$pendidikan['NamaPendidikan']."</td>
        </tr>
        <tr>
            <td>STATUS PERNIKAHAN</td>
            <td>:</td>
            <td> ".$dt_tk['StatusKawin']."</td>
        </tr>
        <tr>
            <td>N.P.W.P</td>
            <td>:</td>
            <td> ".$dt_tk['Npwp']."</td>
        </tr>
        <tr>
            <td coslpan='3'>&nbsp;</td>
        </tr>
        <tr>
            <td>JABATAN/SEKSI</td>
            <td>:</td>
            <td> ".$spk['NamaSeksi']."</td>
        </tr>
        <tr>
            <td>UNIT KERJA</td>
            <td>:</td>
            <td> ".$spk['NamaCabang']."</td>
        </tr>
        <tr>
            <td>TMT ISMA</td>
            <td>:</td>
            <td> ".tgl_indo($dt_tk['Tmt'])."</td>
        </tr>
    </table>
    <div style='width:30%; float:right; margin-right:10%'>
        <center><p>".tgl_indo(date('Y-m-d'))."<br>HORMAT SAYA</p></center>
        <center><p>TTD</p></center>
        <br>
        <center><p>".$dt_tk['Nama']."</p></center>
    </div>
</body>
</html>";
$name_file = str_replace("_"," ",$dt_tk['Nama']);
// echo $html;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
// Setting ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'potrait');
// Rendering dari HTML Ke PDF
$dompdf->render();
// Melakukan output file Pdf
$dompdf->stream('cv_'.$name_file.'.pdf',array("Attachment" => false));
?>