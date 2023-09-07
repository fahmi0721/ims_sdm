$(document).ready(function(){
    Clear();
	$("[data-toggle='tolltip']").tooltip();
	
});



function Clear(){
	$("#Title").html("Upload Data  Mutasi");
	$(".FormInput").val("");
	// ClearModal();
	
}

function Validasi(){
	var iForm = ['File','FileUpload'];
	var iKet = ["File daftar belum di pilih","File Upload Excel belum dipilih"];
	for(var i=0; i < iForm.length; i++){
		if($("#"+iForm[i]).val() == ""){
			if ($("#" + iForm[i]).val() == "") { Customerror("Upload Mutasi", "002", iKet[i], 'proses'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
		}
	}
}

$("#FormData").submit(function(e){
	e.preventDefault();
	SubmitData();
	
})

function berhasil_data(data){
	var No = 1;
	var Id = $("#DataBerhasil");
	html = "<h4>Data Mutasi Berhasil Di Upload</h4>";
	html += "<hr>";
	html += "<table class='table table-striped table-bordered'>";
	html += "<thead>";
	html += "<tr>";
	html += "<th class='text-center'>No</td>";
	html += "<th class='text-center'>No KTP</td>";
	html += "<th class='text-center'>Nama</td>";
	html += "<th class='text-center'>No Dokumen</td>";
	html += "<th class='text-center'>Kode Cabang</td>";
	html += "<th class='text-center'>Kode Branch</td>";
	html += "<th class='text-center'>Kode Divisi</td>";
	html += "<th class='text-center'>Kode Sub Divisi</td>";
	html += "<th class='text-center'>Kode Seksi</td>";
	html += "<th class='text-center'>Tanggal Mutasi</td>";
	html += "<th class='text-center'>Keterangan</td>";
	html += "</tr>";
	html += "</thead>";
	html += "<tbody>";
	if(data.length < 0){
		html += "<tr>";
		html += "<td colspan='11' class='text-center'>Data not availible in table</td>";
		html += "</tr>";
	}else{
		for(var i=0; i < data.length; i++){
			html += "<tr>";
			html += "<td>"+No+"</td>";
			html += "<td>"+data[i]['NoKtp']+"</td>";
			html += "<td>"+data[i]['Nama']+"</td>";
			html += "<td>"+data[i]['NoDokumen']+"</td>";
			html += "<td>"+data[i]['KodeCabang']+"</td>";
			html += "<td>"+data[i]['KodeBranch']+"</td>";
			html += "<td>"+data[i]['KodeDivisi']+"</td>";
			html += "<td>"+data[i]['KodeSubDivisi']+"</td>";
			html += "<td>"+data[i]['KodeSeksi']+"</td>";
			html += "<td>"+data[i]['TanggalMulai']+"</td>";
			html += "<td>"+data[i]['Keterangan']+"</td>";
			html += "</tr>";
			No++;
		}
	}
	html += "</tbody>";
	html += "</table>";

	Id.html(html);
}


function gagal_data(data){
	var No = 1;
	var Id = $("#DataGagal");
	html = "<h4>Data Mutasi Gagal Di Upload</h4>";
	html += "<hr>";
	html += "<table class='table table-striped table-bordered'>";
	html += "<thead>";
	html += "<tr>";
	html += "<th class='text-center'>No</td>";
	html += "<th class='text-center'>No KTP</td>";
	html += "<th class='text-center'>Nama</td>";
	html += "<th class='text-center'>No Dokumen</td>";
	html += "<th class='text-center'>Kode Cabang</td>";
	html += "<th class='text-center'>Kode Branch</td>";
	html += "<th class='text-center'>Kode Divisi</td>";
	html += "<th class='text-center'>Kode Sub Divisi</td>";
	html += "<th class='text-center'>Kode Seksi</td>";
	html += "<th class='text-center'>Tanggal Mutasi</td>";
	html += "<th class='text-center'>Keterangan</td>";
	html += "</tr>";
	html += "</thead>";
	html += "<tbody>";
	if(data.length <= 0){
		html += "<tr>";
		html += "<td colspan='11' class='text-center'>Data not availible in table</td>";
		html += "</tr>";
	}else{
		for(var i=0; i < data.length; i++){
			html += "<tr>";
			html += "<td>"+No+"</td>";
			html += "<td>"+data[i]['NoKtp']+"</td>";
			html += "<td>"+data[i]['Nama']+"</td>";
			html += "<td>"+data[i]['NoDokumen']+"</td>";
			html += "<td>"+data[i]['KodeCabang']+"</td>";
			html += "<td>"+data[i]['KodeBranch']+"</td>";
			html += "<td>"+data[i]['KodeDivisi']+"</td>";
			html += "<td>"+data[i]['KodeSubDivisi']+"</td>";
			html += "<td>"+data[i]['KodeSeksi']+"</td>";
			html += "<td>"+data[i]['TanggalMulai']+"</td>";
			html += "<td>"+data[i]['Keterangan']+"</td>";
			html += "</tr>";
			No++;
		}
	}
	html += "</tbody>";
	html += "</table>";

	Id.html(html);
}

function SubmitData(){
	if (Validasi() != false) {
		var data = new FormData($("#FormData")[0]);
		$.ajax({
			type: "POST",
			url: "inc/UploadMutasi/proses.php?proses=Upload",
			processData: false,
			contentType: false,
			chace: false,
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				console.log(res);
				if (res['status'] == 'sukses') {
					berhasil_data(res['data']['berhasil']);
					gagal_data(res['data']['gagal']);
					Clear();
					Customsukses("SK Pemberhentian", '007', res['pesan'],'proses');
					StopLoad();
				}else{
					Customerror("SK Pemberhentian", "007", res['pesan'], 'ProsesCrud');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
				StopLoad();
			}
		});
	}
}