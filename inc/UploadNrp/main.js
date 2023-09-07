$(document).ready(function(){
    Clear();
	$("[data-toggle='tolltip']").tooltip();
	
});



function Clear(){
	$("#Title").html("Upload Data NRP");
	$(".FormInput").val("");
	// ClearModal();
	
}

function Validasi(){
	var iForm = ['FileUpload'];
	var iKet = ["File Upload Excel belum dipilih"];
	for(var i=0; i < iForm.length; i++){
		if($("#"+iForm[i]).val() == ""){
			if ($("#" + iForm[i]).val() == "") { Customerror("Upload NRP", "002", iKet[i], 'proses'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
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
	html += "<th class='text-center'>Tgl Lahir</td>";
	html += "<th class='text-center'>NRP</td>";
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
			html += "<td>"+data[i]['TglLahir']+"</td>";
			html += "<td>"+data[i]['Nik']+"</td>";
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
	html += "<th class='text-center'>Tgl Lahir</td>";
	html += "<th class='text-center'>Nrp</td>";
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
			html += "<td>"+data[i]['TglLahir']+"</td>";
			html += "<td>"+data[i]['Nik']+"</td>";
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
			url: "inc/UploadNrp/proses.php?proses=Upload",
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
					Customsukses("SK NRP", '007', res['pesan'],'proses');
					StopLoad();
				}else{
					Customerror("SK NRP", "007", res['pesan'], 'ProsesCrud');
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