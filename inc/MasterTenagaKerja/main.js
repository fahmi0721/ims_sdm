$(document).ready(function(){
    Clear();
	LoadData(1);
	SearchStatus();
	SearchAgama();
	GetAgama();
	TglPicker();
	
});

function TglPicker(){
	$("#TglLahir").datepicker({ format: "yyyy-mm-dd", autoclose: true });
	$("#Tmt").datepicker({ format: "yyyy-mm-dd", autoclose: true });
}

function SearchStatus() {
	$('.select-status').select2({
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Status Kawin',
	});
}

function SearchAgama() {
	$('.select-agama').select2({
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Agama',
	});
}

function GetAgama(){
	$.ajax({
		type : "GET",
		url : "inc/MasterTenagaKerja/proses.php?proses=GetAgama",
		chace: false,
		beforeSend : function(){
			StartLoad();
		},
		success : function(res){
			var r = JSON.parse(res);
			var Opt = "";
			if(r['status'] == "sukses"){
				for(var i = 0; i < r['data'].length; i++){
					var iData = r['data'][i];
					Opt += "<option value='" + iData['Kode']+"'>"+iData['Agama']+"</option>";
				}
				$("#Agama").html(Opt);
			}
		},
		error : function(er){
			console.log(er);
		}
	})
}

function pagination(page_num, total_page) {
	page_num = parseInt(page_num);
	total_page = parseInt(total_page);
	var paging = "<ul class='pagination btn-sm'>";
	if (page_num > 1) {
		var prev = page_num - 1;
		paging += "<li><a href='javascript:void(0);' onclick='LoadData(" + prev + ")'>Prev</a></li>";
	} else {
		paging += "<li class='disabled'><a>Prev</a></li>";
	}
	var show_page = 0;
	for (var page = 1; page <= total_page; page++) {
		if (((page >= page_num - 3) && (page <= page_num + 3)) || (page == 1) || page == total_page) {
			if ((show_page == 1) && (page != 2)) {
				paging += "<li class='disabled'><a>...</a></li>";
			}
			if ((show_page != (total_page - 1)) && (page == total_page)) {
				paging += "<li class='disabled'><a>...</a></li>";
			}

			if (page == page_num) {
				var aktif = formatRupiah(page);
				paging += "<li class='active'><a>" + aktif + "</a></li>";
			} else {
				var aktif = formatRupiah(page);
				paging += "<li class='javascript:void(0)'><a onclick='LoadData(" + page + ")'>" + aktif + "</a></li>";
			}
			show_page = page;
		}
	}

	if (page_num < total_page) {
		var next = page_num + 1;
		paging += "<li><a href='javascript:void(0)' onclick='LoadData(" + next + ")'>Next</a></li>";
	} else {
		paging += "<li class='disabled'><a>Next</a></li>";
	}
	$("#Paging").html(paging);
}

function LoadData(page) {
	page = page == undefined ? 1 : page;
	var RowPage = $("#RowPage").val();
	var Search = $("#Search").val();
	$.ajax({
		type: "POST",
		url: "inc/MasterTenagaKerja/proses.php?proses=DetailData",
		data: "Search=" + Search + "&RowPage=" + RowPage + "&Page=" + page,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			// console.log(res);
			var result = JSON.parse(res);
			var html = "";
			if (result['total_data'] > 0) {
				for (var i = 0; i < result['data'].length; i++) {
					var r = result['data'][i];
					html += "<tr>";
					html += "<td class='text-center'>" + r['No'] + "</td>";
					html += "<td>" + r['Nama'] + "<br><small><b>No KTP : " + r['NoKtp'] + "<br /> NIK : " + r['Nik'] +"</b></small></td>";
					html += "<td>" + r['JenisKelamin'] + "</td>";
					html += "<td>" + r['Ttl'];
					html += "<td>" + r['Pendidikan'] + "</td>";
					html += "<td>" + r['Agama'] + "</td>";
					html += "<td>" + r['Tmt'] + "</td>";
					html += "<td>" + r['Rekening'] + "</td>";
					html += "<td>" + r['NoHp'] + "</td>";
					html += "<td>" + r['Jabatan'] + "</td>";
					html += "<td>" + r['Alamat'] + "</td>";
					html += "<td>" + r['Flag'] + "</td>";
					html += "<td class='text-center'>" + r['Aksi'] + "</td>";
					html += "</tr>";
				}
			} else {
				html = "<tr><td class='text-center' colspan='12'>No data availible in table.</td></tr>";
			}
			$("#ShowData").html(html);
			var PagingInfo = "Menampilkan " + result['data_new'] + " Ke " + result['data_last'] + " dari " + result['total_data'];
			$("#PagingInfo").html(PagingInfo);
			pagination(page, result['total_page']);
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
	$("#Title").html("Tampil Data Master Tenaga Kerja");
	$("#ProsesCrud").html("");
	$("#close_modal").trigger('click');
	$(".box-tools").show();
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$(".FormInput").val("");
	
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
				url: "inc/MasterTenagaKerja/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					
					$("#Title").html("Ubah Data Master Tenaga Kerja");
					$("#FormData").show();
					$(".box-tools").hide();
					$("#DetailData").hide();
					$("#aksi").val("update");
					var iForm = ['Id', 'NoKtp', 'Nama', 'TptLahir', 'TglLahir', 'StatusKawin', 'JenisKelamin', 'Agama', 'Npwp', 'NoHp', 'GolDarah', 'Flag','Alamat','Tmt'];
					for(var i=0; i < iForm.length; i++){
						if(iForm[i] == "Flag"){
							$("#Flag" + data[iForm[i]]).prop("checked", true);
						} else if(iForm[i] == "JenisKelamin"){
							$("#JenisKelamin" + data[iForm[i]]).prop("checked", true);
						}else{
							$("#" + iForm[i]).val(data[iForm[i]]);
						}
					}
					$(".select-status").trigger('change');
					$(".select-agama").trigger('change');
					
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else if(Status == "foto"){
			$(".modal-title").html("FOTO TENAGA KERJA");
			$("#proses_del").html("<center><img class='img-responsive' src='img/FotoTenagaKerja/"+Id+"'></center>");
			$(".modal-footer").hide();
			jQuery("#modal").modal('show', { backdrop: 'static' });
		}else if(Status == "foto2"){
			$(".modal-title").html("FOTO TENAGA KERJA");
			$("#proses_del").html("<center><img class='img-responsive' src='img/FotoKtp/"+Id+"'></center>");
			$(".modal-footer").hide();
			jQuery("#modal").modal('show', { backdrop: 'static' });
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#Id").val(Id)
			$("#proses_del").html("<div class='alert alert-danger'>Jika menghapus data ini, maka data yang terkait dengannya akan ikut terhapus, yakin ingin menghapus data ini ?. </div>");
		}
	}else{
		$("#Title").html("Tambah Data Master Tenaga Kerja");
		$("#FormData").show();
		$("#DetailData").hide();
		$(".box-tools").hide();
		$("#proses").html("");
		$("#NoKtp").focus();
		$("#aksi").val("insert");

	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ['NoKtp', 'Nama', 'TptLahir', 'TglLahir', 'StatusKawin', 'Agama', "NoHp", "Tmt"];
	var KetiForm = ["No KTP belum lengkap", "Nama belum lengkap", "Tempat Lahir belum lengkap", "Tanggal Lahir belum lengkap", "Status Kawin belum lengkap", "Agama belum lengkap", "NoHp belum lengkap", "No HP belum lengkap"];
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete") {
			if(iForm[i] == 'NoKtp'){
				var KtPL = $("#NoKtp").val().length;
				if ($("#" + iForm[i]).val() != ""){
					if(KtPL != 16){
						Customerror("Master Tenaga Kerja", "007", "Masukkan No KTP yang Benar", 'ProsesCrud'); $("#" + iForm[i]).focus(); scrolltop(); return false;
					}
				}
			}
			if ($("#" + iForm[i]).val() == "") { Customerror("Master Tenaga Kerja", "007", KetiForm[i], 'ProsesCrud'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
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
			url: "inc/MasterTenagaKerja/proses.php?proses=Crud",
			processData : false,
			contentType : false,
			chace : false,
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				console.log(result);
				var res = JSON.parse(result);
				
				if (res['status'] == 'sukses') {
					Clear();
					Customsukses("Master Tenaga Kerja", '007', res['pesan'],'proses');
					LoadData();
					StopLoad();
					scrolltop();
				}else{
					Customerror("Master Tenaga Kerja", "007", res['pesan'], 'ProsesCrud');
					scrolltop();
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
			}
		});
	}
}