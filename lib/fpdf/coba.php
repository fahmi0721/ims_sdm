<?php
require('fpdf.php');

class PDF extends FPDF{
    
    

    function Header() { 
        $this->SetFont('Arial','B',10);
        $this->Cell(0,5,'BUKU KAS UMUM',0,1,'C');
        $this->SetFont('Arial','',8.3);
        $this->Cell(0,5,'Periode : 2018',0,0,'L');
        $this->Cell(0,5,'Mata Uang : IDR',0,1,'R');
        $header = array(
            array("label" => "Tanggal", "length" => 15, "align" => "C"),
            array("label" => "No Bukti", "length" => 15, "align" => "C"),
            array("label" => "Uraian", "length" => 70, "align" => "C"),
            array("label" => "Debet", "length" => 30, "align" => "C"),
            array("label" => "Kredit", "length" => 30, "align" => "C"),
            array("label" => "Saldo", "length" => 30, "align" => "C")
        );
        $header2 = array(
            array("label" => "1", "length" => 15, "align" => "C"),
            array("label" => "2", "length" => 15, "align" => "C"),
            array("label" => "3", "length" => 70, "align" => "C"),
            array("label" => "4", "length" => 30, "align" => "C"),
            array("label" => "5", "length" => 30, "align" => "C"),
            array("label" => "6", "length" => 30, "align" => "C")
        );


        foreach ($header as $kolom) {
            $this->Cell($kolom['length'], 5, $kolom['label'], 1, 0, $kolom['align']);
        }
        $this->Ln();
        foreach ($header2 as $kolom) {
            $this->Cell($kolom['length'], 5, $kolom['label'], 1, 0, $kolom['align']);
        }
        $this->Ln();
     } 
    function GetLine($text, $cellWidth){
        if($this->GetStringWidth($text) < $cellWidth){
            $line=1;
        }else{
            //jika ya, maka hitung ketinggian yang dibutuhkan untuk sel akan dirapikan
            //dengan memisahkan teks agar sesuai dengan lebar sel
            //lalu hitung berapa banyak baris yang dibutuhkan agar teks pas dengan sel
            
            $textLength=strlen($text);	//total panjang teks
            $errMargin=5;		//margin kesalahan lebar sel, untuk jaga-jaga
            $startChar=0;		//posisi awal karakter untuk setiap baris
            $maxChar=0;			//karakter maksimum dalam satu baris, yang akan ditambahkan nanti
            $textArray=array();	//untuk menampung data untuk setiap baris
            $tmpString="";		//untuk menampung teks untuk setiap baris (sementara)
            
            while($startChar < $textLength){ //perulangan sampai akhir teks
                //perulangan sampai karakter maksimum tercapai
                while( 
                $this->GetStringWidth( $tmpString ) < ($cellWidth-$errMargin) &&
                ($startChar+$maxChar) < $textLength ) {
                    $maxChar++;
                    $tmpString=substr($text,$startChar,$maxChar);
                }
                //pindahkan ke baris berikutnya
                $startChar=$startChar+$maxChar;
                //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                array_push($textArray,$tmpString);
                //reset variabel penampung
                $maxChar=0;
                $tmpString='';
                
            }
            //dapatkan jumlah baris
            $line=count($textArray);
            return $line;
        }
    }

    function Footer(){
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial','I',8);
        // Print centered page number
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }
}

$pdf = new PDF();

$pdf->AddPage();
$pdf->SetFont('Arial','',8.3);

//Saldo Awal
$pdf->Cell(100, 5, 'Saldo Awal', 1, 0,'C');
$pdf->Cell(30, 5, '', 1, 0);
$pdf->Cell(30, 5, '', 1, 0);
$pdf->Cell(30, 5, '1000.0000', 1, 0,'R');
$pdf->Ln();

$cellWidth=70; //lebar sel
$cellHeight=5; //tinggi sel satu baris normal

for($j=0; $j < 50; $j++){

$text = "TRANSFER DARI PEMINDAHAN DARI 376534808 Bpk ABDUL MUNIR FAISA";
$line = $pdf->GetLine($text,$cellWidth);

$pdf->Cell(7.5, ($line * $cellHeight), '01', 1, 0,'C');
$pdf->Cell(7.5, ($line * $cellHeight), '03', 1, 0,'C');
$pdf->Cell(15, ($line * $cellHeight), '001', 1, 0,'C');
//memanfaatkan MultiCell sebagai ganti Cell
//atur posisi xy untuk sel berikutnya menjadi di sebelahnya.
//ingat posisi x dan y sebelum menulis MultiCell
$xPos=$pdf->GetX();
$yPos=$pdf->GetY();
$pdf->MultiCell($cellWidth,$cellHeight,$text,1);
$pdf->SetXY($xPos + $cellWidth , $yPos);
$pdf->Cell(30, ($line * $cellHeight), '1000.0000', 1, 0,'R');
$pdf->Cell(30, ($line * $cellHeight), '1000.0000', 1, 0,'R');
$pdf->Cell(30, ($line * $cellHeight), '1000.0000', 1, 0,'R');
$pdf->Ln();
}
$pdf->Cell(100, 5, '', 0, 0,'R');
$pdf->Cell(30, 5, '1000.0000', 1, 0,'R');
$pdf->Cell(30, 5, '1000.0000', 1, 0,'R');
$pdf->Cell(30, 5, '1000.0000', 1, 0,'R');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(20, 5, '', 0, 0);
$pdf->Cell(115, 5, 'Mengetahui', 0, 0,'L');
$pdf->Cell(55, 5, 'Sorong, ', 0, 1,'L');
$pdf->Cell(20, 3, '', 0, 0);
$pdf->Cell(115, 3, 'Kuasa Pengguna Anggaran', 0, 0,'L');
$pdf->Cell(55, 3, 'Bendahara Pengeluaran', 0, 1,'L');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','U');
$pdf->Cell(20, 5, '', 0, 0);
$pdf->Cell(115, 5, 'Capt. WISNU RISIANTO,MM', 0, 0,'L');
$pdf->Cell(55, 5, 'I MADE MARIASA', 0, 1,'L');
$pdf->Cell(20, 2, '', 0, 0);
$pdf->SetFont('Arial','');
$pdf->Cell(115, 2, 'NIP. 19710202 199808 1 001', 0, 0,'L');
$pdf->Cell(55, 2, 'NIP. 19890416 201402 1 004', 0, 1,'L');
$pdf->Output();