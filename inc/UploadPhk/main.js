$(document).ready(function(){
    Clear();
	$("[data-toggle='tolltip']").tooltip();
	
});

function Clear(){
	$("#Title").html("Upload Data PHK");
	$("#close_modal").trigger('click');
	$("#aksi").val("");
	$(".FormInput").val("");
	
}

function Validasi(){
	var iForm = ['File','FileUpload'];
	var iKet = ["File daftar belum di pilih","File Upload Excel belum dipilih"];
	for(var i=0; i < iForm.length; i++){
		if($("#"+iForm[i]).val() == ""){
			if ($("#" + iForm[i]).val() == "") { Customerror("Upload PHK", "002", iKet[i], 'proses'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
		}
	}
}


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
	html += "<th class='text-center'>Kategori</td>";
	html += "<th class='text-center'>Tanggal PHK</td>";
	html += "<th class='text-center'>Keterangan</td>";
	html += "</tr>";
	html += "</thead>";
	html += "<tbody>";
	if(data.length < 0){
		html += "<tr>";
		html += "<td colspan='7' class='text-center'>Data not availible in table</td>";
		html += "</tr>";
	}else{
		for(var i=0; i < data.length; i++){
			html += "<tr>";
			html += "<td>"+No+"</td>";
			html += "<td>"+data[i]['NoKtp']+"</td>";
			html += "<td>"+data[i]['Nama']+"</td>";
			html += "<td>"+data[i]['NoDokumen']+"</td>";
			html += "<td>"+data[i]['Kategori']+"</td>";
			html += "<td>"+data[i]['Tmt']+"</td>";
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
	html = "<h4>Data PHK Gagal Di Upload</h4>";
	html += "<hr>";
	html += "<table class='table table-striped table-bordered'>";
	html += "<thead>";
	html += "<tr>";
	html += "<th class='text-center'>No</td>";
	html += "<th class='text-center'>No KTP</td>";
	html += "<th class='text-center'>Nama</td>";
	html += "<th class='text-center'>No Dokumen</td>";
	html += "<th class='text-center'>Kategori</td>";
	html += "<th class='text-center'>Tanggal PHK</td>";
	html += "<th class='text-center'>Keterangan</td>";
	html += "</tr>";
	html += "</thead>";
	html += "<tbody>";
	if(data.length < 0){
		html += "<tr>";
		html += "<td colspan='7' class='text-center'>Data not availible in table</td>";
		html += "</tr>";
	}else{
		for(var i=0; i < data.length; i++){
			html += "<tr>";
			html += "<td>"+No+"</td>";
			html += "<td>"+data[i]['NoKtp']+"</td>";
			html += "<td>"+data[i]['Nama']+"</td>";
			html += "<td>"+data[i]['NoDokumen']+"</td>";
			html += "<td>"+data[i]['Kategori']+"</td>";
			html += "<td>"+data[i]['Tmt']+"</td>";
			html += "<td>"+data[i]['Keterangan']+"</td>";
			html += "</tr>";
			No++;
		}
	}
	html += "</tbody>";
	html += "</table>";

	Id.html(html);
}


$("#FormData").submit(function(e){
	e.preventDefault();
	SubmitData();
	
})

function SubmitData(){
	if (Validasi() != false) {
		var data = new FormData($("#FormData")[0]);
		$.ajax({
			type: "POST",
			url: "inc/UploadPhk/proses.php?proses=Upload",
			processData: false,
			contentType: false,
			chace: false,
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				console.log(result);
				if (res['status'] == 'sukses') {
					berhasil_data(res['data']['berhasil']);
					gagal_data(res['data']['gagal']);
					Clear();
					Customsukses("Upload SK Pemberhentian", '007', res['pesan'],'proses');
					StopLoad();
				}else{
					Customerror("Upload SK Pemberhentian", "007", res['pesan'], 'proses');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
			}
		});
	}
}