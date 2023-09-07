$(document).ready(function(){
	Clear();
	LoadData();

	
});

function getUrl(sParam){
	const queryString = window.location.search;
	var str = queryString.substr(1, queryString.length);
	var sURLVariables = str.split('&');
	
	for (var i = 0; i < sURLVariables.length; i++) {
		
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam) {
			return decodeURIComponent(sParameterName[1]);
		}
	}
}

function LoadData() {
	var NoKtp = getUrl('Id');
	$("#cv_download").attr("href","cetak/cv/cetak_cv.php?NoKtp="+NoKtp);
	LoadBiodata(NoKtp);
	LoadPendidikanFormal(NoKtp);
	LoadPendidikanNonFormal(NoKtp);
	LoadKeluarga(NoKtp);
	RiwayatKerja(NoKtp);
	NomorRekening(NoKtp);
}
function NomorRekening(NoKtp) {
	$.ajax({
		type: "POST",
		url: "inc/DetailTk/proses.php?proses=LoadData",
		data: "rule=NomorRekening&NoKtp=" + NoKtp,
		success: function (r) {
			var res = JSON.parse(r);
			var html = "";
			if (res.length > 0) {
				var No = 1;
				var Status = ["<label class='label label-danger'>Tidak Aktif</label>","<label class='label label-success'>Aktif</label>"];
				for (var i = 0; i < res.length; i++) {
					var iData = res[i];
					html += "<tr>";
					html += "<td>" + No + "</td>";
					html += "<td>" + iData['NamaBank'] + "</td>";
					html += "<td>" + iData['NoRek'] + "</td>";
					html += "<td>" + Status[iData['Flag']] + "</td>";
					html += "<td>" + iData['File'] + "</td>";
					html += "</tr>";
					No++;
				}
			} else {
				html += "<tr>";
				html += "<td colspan='7' class='text-center'>Belum ada data</td>";
				html += "</tr>";
			}
			$("#TampilNoRek").html(html);
		},
		error: function (er) {
			console.log(er);
		}

	})
}
function RiwayatKerja(NoKtp) {
	$.ajax({
		type: "POST",
		url: "inc/DetailTk/proses.php?proses=LoadData",
		data: "rule=RiwayatKerja&NoKtp=" + NoKtp,
		success: function (r) {
			var res = JSON.parse(r);
			var html = "";
			$("#JumPen").html(res.length);
			if (res.length > 0) {
				var No = 1;
				for (var i = 0; i < res.length; i++) {
					var iData = res[i];
					html += "<tr>";
					html += "<td>" + No + "</td>";
					html += "<td>" + iData['NamaCabang'] + "</td>";
					html += "<td>" + iData['NamaDivisi'] + "</td>";
					html += "<td>" + iData['NamaSubDivisi'] + "</td>";
					html += "<td>" + iData['NamaSeksi'] + "</td>";
					html += "<td>" + iData['TanggalMulai'] + " -  " + iData['TanggalSelesai']+"</td>";
					html += "<td>" + iData['File'] + "</td>";
					html += "</tr>";
					No++;
				}
			} else {
				html += "<tr>";
				html += "<td colspan='7' class='text-center'>Belum ada data</td>";
				html += "</tr>";
			}
			$("#TampilRiwayatKerja").html(html);
		},
		error: function (er) {
			console.log(er);
		}

	})
}

function LoadKeluarga(NoKtp) {
	$.ajax({
		type: "POST",
		url: "inc/DetailTk/proses.php?proses=LoadData",
		data: "rule=Keluarga&NoKtp=" + NoKtp,
		success: function (r) {
			var res = JSON.parse(r);
			var html = "";
			if (res.length > 0) {
				var No = 1;
				for (var i = 0; i < res.length; i++) {
					var iData = res[i];
					html += "<tr>";
					html += "<td>" + No + "</td>";
					html += "<td>" + iData['Nama'] + "</td>";
					html += "<td>" + iData['StatusKeluarga'] + "</td>";
					html += "<td>" + iData['Pendidikan'] + "</td>";
					html += "<td>" + iData['Pekerjaan'] + "</td>";
					html += "<td>" + iData['NoHp'] + "</td>";
					html += "<td>" + iData['Alamat'] + "</td>";
					html += "</tr>";
					No++;
				}
			} else {
				html += "<tr>";
				html += "<td colspan='7' class='text-center'>Belum ada data</td>";
				html += "</tr>";
			}
			$("#TampilKeluarga").html(html);
		},
		error: function (er) {
			console.log(er);
		}

	})
}

function LoadPendidikanFormal(NoKtp) {
	$.ajax({
		type: "POST",
		url: "inc/DetailTk/proses.php?proses=LoadData",
		data: "rule=PendidikanFormal&NoKtp=" + NoKtp,
		success: function (r) {
			var res = JSON.parse(r);
			$("#JumSer").html(res.length);
			var html ="";
			if(res.length > 0){
				var No =1;
				for(var i=0; i < res.length; i++){
					var iData = res[i];
					html += "<tr>";
					html += "<td>"+No+"</td>";
					html += "<td>Tingkat : <b>" + iData['NamaPendidikan'] + "</b><br>Jurusan <b>" + iData['NamaJurusan'] + "</b> </td>";
					html += "<td>Tahun Mulai : <b>" + iData['TahunMulai'] + "</b><br>Tahun Selesai <b>" + iData['TahunSelesai'] + "</b> </td>";
					html += "<td class='text-center'>" + iData['File']+"</td>";
					html += "</tr>";
					No++;
				}
			}else{
				html += "<tr>";
					html += "<td colspan='4' class='text-center'>Belum ada data</td>";
				html += "</tr>";
			}
			$("#TampilFormal").html(html);
		},
		error: function (er) {
			console.log(er);
		}

	})
}

function LoadPendidikanNonFormal(NoKtp) {
	$.ajax({
		type: "POST",
		url: "inc/DetailTk/proses.php?proses=LoadData",
		data: "rule=PendidikanNonFormal&NoKtp=" + NoKtp,
		success: function (r) {
			var res = JSON.parse(r);
			var html = "";
			if (res.length > 0) {
				var No = 1;
				for (var i = 0; i < res.length; i++) {
					var iData = res[i];
					html += "<tr>";
					html += "<td>" + No + "</td>";
					html += "<td>"+iData['Sertifikasi']+"</td>";
					html += "<td>Tahun Mulai : <b>" + iData['Dari'] + "</b><br>Tahun Selesai <b>" + iData['Sampai'] + "</b> </td>";
					html += "<td>"+iData['Keterangan']+"</td>";
					html += "<td class='text-center'>" + iData['File'] + "</td>";
					html += "</tr>";
					No++;
				}
			} else {
				html += "<tr>";
				html += "<td colspan='5' class='text-center'>Belum ada data</td>";
				html += "</tr>";
			}
			$("#TampilNonFormal").html(html);
		},
		error: function (er) {
			console.log(er);
		}

	})
}

function LoadBiodata(NoKtp){
	$.ajax({
		type : "POST",
		url	 : "inc/DetailTk/proses.php?proses=LoadData",
		data : "rule=Biodata&NoKtp="+NoKtp,
		success: function(r){
			var res = JSON.parse(r);
			IsiData(res);
		},
		error : function(er){
			console.log(er);
		}

	})
}

function IsiData(data){
	$("#Namas").html(data['Biodata']['Nama']);
	$("#Foto").prop("src",data['Biodata']['Foto']);
	$("#Jabatans").html(data['Jabatan']['NamaSeksi']);
	var Nm = data['Pendidikan']['NamaPendidikan'] != null ? data['Pendidikan']['NamaPendidikan'] : "";
	var Jr = data['Pendidikan']['NamaJurusan'] != null ? ", "+data['Pendidikan']['NamaJurusan'] : "";
	$("#Pendidikans").html(Nm + Jr);
	$("#Alamats").html(data['Biodata']['Alamat']);
	var iForm = ['NoKtp', 'Nama', 'TptLahir', 'TglLahir', 'StatusKawin', 'JenisKelamin', 'Agama', 'Npwp', 'NoHp', 'GolDarah'];
	for (var i = 0; i < iForm.length; i++) {
		$("#" + iForm[i]).val(data['Biodata'][iForm[i]]);
		
	}
	$("#BpjsTk").val(data['BpjsTk']['NoKpj']);
	$("#BpjsKes").val(data['BpjsKes']['NoJkn']);
	$("#Cif").val(data['Dplk']['Cif']);
	$("#NoAkunDplk").val(data['Dplk']['NoAccount']);
	var iForm = ['Baju', 'Celana', 'Sepatu', 'Topi', 'Ped'];
	for (var i = 0; i < iForm.length; i++) {
		$("#" + iForm[i]).val(data['UkuranBaju'][iForm[i]]);
	}
	if (data['Sertifikasi'] != ""){
		var html ="";
		var label = ['danger', 'success','info', 'warning','primary'];
		var PosLabel =0;
		for (var i = 0; i < data['Sertifikasi'].length; i++){
			var iPl = label[PosLabel];
			html += "<span class='label label-"+iPl+"'>" + data['Sertifikasi'][i]+"</span>";
			if(PosLabel == label.length){
				PosLabel = 0;
			}
			PosLabel++;
		}
		$("#SertifikasiS").html(html);
	}else{
		$("#SertifikasiS").html("");
	}
}

function Clear(){
	$("#Title").html("Detail Data");
	//$("#close_modal").trigger('click');
	$("#ShowData").show();
	
	
}

