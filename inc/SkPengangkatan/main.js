$(document).ready(function(){
    Clear();
	SearchForm();
	LoadDataForm();
	$("[data-toggle='tolltip']").tooltip();
	$("#TanggalMulai,#TanggalSelesai,#TanggalMulaiSearch").datepicker({format : "yyyy-mm-dd", autoclose : true});
	
});

function SearchForm() {
	$('.select-no-ktp').select2({
		minimumInputLength: 3,
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Tenaga Kerja',
	});

	$('.select-branch').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Branch',
	});

	$('.select-unit-kerja').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Unit Kerja',
	});

	$('.select-divisi').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Unit Divisi',
	});

	$('.select-sub-divisi').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Sub Divisi',
	});

	$('.select-seksi').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Seksi',
	});
}

function LoadDataForm(){
	LoadTK();
	LoadBranch();
	LoadUnitKerja();
	LoadDivisi();
	LoadSubDivisi();
	LoadSeksi();
}

function LoadTK(){
	$.ajax({
		type : 'POST',
		url: 'inc/SkPengangkatan/proses.php?proses=LoadData',
		data : "rule=TenagaKerja",
		beforeSend : function(){
			StartLoad();
		},
		success : function(res){
			var r = JSON.parse(res);
			var html = "<option value=''>Pilih Tenaga Kerja</option>";
			for(var i=0; i < r.length; i++){
				var iData = r[i];
				html += "<option value='"+iData['NoKtp']+"'>"+iData['NoKtp']+" - "+iData['Nama']+"</option>";
			}
			$("#NoKtp,#NoKtpSearch").html(html);
		},
		error : function(er){
			console.log(er);
		}
	})
}

function LoadBranch() {
	$.ajax({
		type: 'POST',
		url: 'inc/SkPengangkatan/proses.php?proses=LoadData',
		data: "rule=Branch",
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var r = JSON.parse(res);
			var html = "<option value=''>Pilih Branch</option>";
			for (var i = 0; i < r.length; i++) {
				var iData = r[i];
				html += "<option value='" + iData['Kode'] + "'>" + iData['Nama'] + "</option>";
			}
			$("#KodeBranch").html(html);
		},
		error: function (er) {
			console.log(er);
		}
	})
}

function LoadUnitKerja() {
	$.ajax({
		type: 'POST',
		url: 'inc/SkPengangkatan/proses.php?proses=LoadData',
		data: "rule=UnitKerja",
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var r = JSON.parse(res);
			var html = "<option value=''>Pilih Unit Kerja</option>";
			for (var i = 0; i < r.length; i++) {
				var iData = r[i];
				html += "<option value='" + iData['Kode'] + "'>" + iData['Nama'] + "</option>";
			}
			$("#KodeCabang").html(html);
		},
		error: function (er) {
			console.log(er);
		}
	})
}

function LoadDivisi() {
	$.ajax({
		type: 'POST',
		url: 'inc/SkPengangkatan/proses.php?proses=LoadData',
		data: "rule=Divisi",
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var r = JSON.parse(res);
			var html = "<option value=''>Pilih Unit Divisi</option>";
			for (var i = 0; i < r.length; i++) {
				var iData = r[i];
				html += "<option value='" + iData['Kode'] + "'>" + iData['Nama'] + "</option>";
			}
			$("#KodeDivisi").html(html);
		},
		error: function (er) {
			console.log(er);
		}
	})
}

function LoadSubDivisi(st) {
	$.ajax({
		type: 'POST',
		url: 'inc/SkPengangkatan/proses.php?proses=LoadData',
		data: "rule=SubDivisi",
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var r = JSON.parse(res);
			var html = "<option value=''>Pilih Unit Sub Divisi</option>";
			for (var i = 0; i < r.length; i++) {
				var iData = r[i];
				html += "<option value='" + iData['Kode'] + "'>" + iData['Nama'] + "</option>";
			}
			$("#KodeSubDivisi").html(html);
			if(st != undefined){
				$(".select-sub-divisi").val(st).trigger('change');
			}
		},
		error: function (er) {
			console.log(er);
		}
	})
}

function LoadSeksi(st) {
	$.ajax({
		type: 'POST',
		url: 'inc/SkPengangkatan/proses.php?proses=LoadData',
		data: "rule=Seksi",
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var r = JSON.parse(res);
			var html = "<option value=''>Pilih Unit Sub Seksi</option>";
			for (var i = 0; i < r.length; i++) {
				var iData = r[i];
				html += "<option value='" + iData['Kode'] + "'>" + iData['Nama'] + "</option>";
			}
			$("#KodeSeksi").html(html);
			if(st != undefined){
				$(".select-seksi").val(st).trigger("change");
			}
			StopLoad();
		},
		error: function (er) {
			console.log(er);
		}
	})
}

$("#FormDataSearch").submit(function(e){
	e.preventDefault();
	LoadData();	
})

function LoadData() {
	var iForm = $("#FormDataSearch").serialize();
	
	$.ajax({
		type: "POST",
		url: "inc/SkPengangkatan/proses.php?proses=DetailData",
		data: iForm,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			console.log(res);
			var result = JSON.parse(res);
			var html = "";
			if (result['total_data'] > 0) {
				for (var i = 0; i < result['data'].length; i++) {
					var r = result['data'][i];
					html += "<tr>";
					html += "<td class='text-center'>" + r['No'] + "</td>";
					html += "<td>" + r['TK'] + "</td>";
					html += "<td>" + r['UnitKerja'] + "</td>";
					html += "<td>" + r['NoDokumen'] + "</td>";
					html += "<td>" + r['TMT'] + "</td>";
					html += "<td>" + r['Keterangan'] + "</td>";
					html += "<td class='text-center'>" + r['Aksi'] + "</td>";
					html += "</tr>";
				}
			} else {
				html = "<tr><td class='text-center' colspan='7'>No data availible in table.</td></tr>";
			}
			$("#ShowData").html(html);
			$("#JumData").html("Total Data :"+result['total_data']);
			StopLoad();
			$("[data-toggle='tooltip']").tooltip();
		},
		error: function (er) {
			$("#proses").html(er['responseText']);
			StopLoad();
		}
	})

}

function ClearModal(){
	$(".modal-title").html("Konfirmasi Delete");
	$("#proses_del").html("");
	$(".modal-footer").show();
}

function Clear(){
	$("#Title").html("Tampil Data SK Pengangkatan");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$(".FormInput").val("");
	$(".ProsesCrud").html("");
	$(".select-no-ktp").val(null).trigger('change');
	$(".select-branch").val(null).trigger('change');
	$(".select-unit-kerja").val(null).trigger('change');
	$(".select-divisi").val(null).trigger('change');
	$(".select-sub-divisi").val(null).trigger('change');
	$(".select-seksi").val(null).trigger('change');
	ClearModal();
	
}

function Crud(Id,Status){
	Clear();
	$("#proses").html("");
	if(Id){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/SkPengangkatan/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data SK PKWT");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#TanggalSelesai").prop("disabled", false);
					$("#aksi").val("update");
					var iForm = ['Id', 'NoKtp', 'KodeBranch', 'KodeCabang', 'KodeDivisi', 'KodeSubDivisi', 'KodeSeksi', 'NoDokumen', 'TanggalMulai','TanggalSelesai', 'Keterangan'];
					for(var i=0; i < iForm.length; i++){
						$("#" + iForm[i]).val(data[iForm[i]]);
					}
					$(".select-no-ktp").trigger('change');
					$(".select-branch").trigger('change');
					$(".select-unit-kerja").trigger('change');
					$(".select-divisi").trigger('change');
					LoadSubDivisi(data['KodeSubDivisi']);
					LoadSeksi(data['KodeSeksi']);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		} else if (Status == "file") {
			jQuery("#modal").modal('show', { backdrop: 'static' });
			$(".modal-title").html("Detail Dokumen");
			var spli = Id.split("#");
			var File = spli[0];
			var Tipe = spli[1];
			if(Tipe != "pdf"){
				$("#proses_del").html("<center><img class='img-responsive' src='File/SkPengangkatan/"+File+"'></center>");
			}
			$(".modal-footer").hide();
			
			
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#Id").val(Id)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data SK PKWT");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#proses").html("");
		$("#NoKtp").focus();
		$("#TanggalSelesai").prop("disabled",true);
		$("#aksi").val("insert");
	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ["NoKtp",  "NoDokumen", "KodeBranch", "KodeCabang", "KodeDivisi", "KodeSubDivisi", "KodeSeksi","TanggalMulai"];
	var KetiForm = ["Tenaga Kerja belum lengkap", "No Dokumen belum lengkap","Branch belum lengkap","Unit Kerja belum lengkap","Divisi belum lengkap","Sub Divisi belum lengkap","Seksi belum lengkap","TMT belum lengkap"];
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete") {
			if ($("#" + iForm[i]).val() == "") { 
				if(iForm[i] == "NoKtp"){
					$(".select-no-ktp").select2("focus");
				} else if (iForm[i] == "KodeBranch"){
					$(".select-branch").select2("focus");
				} else if (iForm[i] == "KodeCabang"){
					$(".select-unit-kerja").select2("focus");
				} else if (iForm[i] == "KodeDivisi") {
					$(".select-divisi").select2("focus");
				} else if (iForm[i] == "KodeSubDivisi") {
					$(".select-sub-divisi").select2("focus");
				} else if (iForm[i] == "KodeSeksi") {
					$(".select-seksi").select2("focus");
				}
				Customerror("SK PKWT", "007", KetiForm[i], 'ProsesCrud'); $("#" + iForm[i]).focus(); scrolltop(); return false; 
			}
		}
	}
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
			url: "inc/SkPengangkatan/proses.php?proses=Crud",
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
					Clear();
					Customsukses("SK PKWT", '007', res['pesan'],'proses');
					StopLoad();
				}else{
					Customerror("SK PKWT", "007", res['pesan'], 'ProsesCrud');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
			}
		});
	}
}