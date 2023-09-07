$(document).ready(function(){
    Clear();
	LoadData(1);
	SearchForm();
	LoadTK();
	$("#TglDaftar").datepicker({ format : "yyyy-mm-dd", autoclose : true });
});

function SearchForm() {
	$('.select-no-ktp').select2({
		minimumInputLength: 3,
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Tenaga Kerja',
	});
}

function LoadTK(){
	$.ajax({
		type : 'POST',
		url : 'inc/Dplk/proses.php?proses=LodaData',
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
			$("#NoKtp").html(html);
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
		url: "inc/Dplk/proses.php?proses=DetailData",
		data: "Search=" + Search + "&RowPage=" + RowPage + "&Page=" + page,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var result = JSON.parse(res);
			console.log(result);
			var html = "";
			if (result['total_data'] > 0) {
				for (var i = 0; i < result['data'].length; i++) {
					var r = result['data'][i];
					html += "<tr>";
					html += "<td class='text-center'>" + r['No'] + "</td>";
					html += "<td>" + r['TK'] + "</td>";
					html += "<td>" + r['Dplk'] + "</td>";
					html += "<td>" + r['TglDaftar'] + "</td>";
					html += "<td>" + r['Flag'] + "</td>";
					html += "<td class='text-center'>" + r['Aksi'] + "</td>";
					html += "</tr>";
				}
			} else {
				html = "<tr><td class='text-center' colspan='6'>No data availible in table.</td></tr>";
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
	$("#Title").html("Tampil Data DPLK");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$(".FormInput").val("");
	$(".ProsesCrud").html("");
	$(".select-no-ktp").val(null).trigger('change');
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
				url: "inc/Dplk/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data DPLK");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");
					var iForm = ['Id', 'NoKtp', 'Cif', 'NoAccount', 'TglDaftar', 'Flag'];
					for(var i=0; i < iForm.length; i++){
						if(iForm[i] == "Flag"){
							$("#Flag" + data[iForm[i]]).prop("checked", true);
						}else{
							$("#" + iForm[i]).val(data[iForm[i]]);
						}
					}
					$(".select-no-ktp").trigger('change');
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
				$("#proses_del").html("<center><img class='img-responsive' src='File/Dplk/"+File+"'></center>");
			}
			$(".modal-footer").hide();
			
			
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#Id").val(Id)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data DPLK");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#proses").html("");
		$("#NoKtp").focus();
		$("#aksi").val("insert");

	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ["NoKtp", "Cif","NoAccount"];
	var KetiForm = ["Tenaga Kerja belum lengkap", "Cif Peserta belum lengkap","Nomor Akun DPLK belum lengkap"];
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete") {
			if ($("#" + iForm[i]).val() == "") { 
				if(iForm[i] == "NoKtp"){
					$(".select-no-ktp").select2("focus");
				}
				Customerror("DPLK", "007", KetiForm[i], 'ProsesCrud'); $("#" + iForm[i]).focus(); scrolltop(); return false; 
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
			url: "inc/Dplk/proses.php?proses=Crud",
			processData: false,
			contentType: false,
			chace: false,
			data: data,
			beforeSend: function () {
			//	StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				console.log(res);
				if (res['status'] == 'sukses') {
					Clear();
					Customsukses("DPLK", '007', res['pesan'],'proses');
					LoadData();
					StopLoad();
				}else{
					Customerror("DPLK", "007", res['pesan'], 'ProsesCrud');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
			}
		});
	}
}