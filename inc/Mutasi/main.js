$(document).ready(function(){
    Clear();
	LoadData();
	$("#TMTLama,#TMTBaru,#TanggalMulai,#TanggalSelesai").datepicker({"format" : "yyyy-mm-dd", "autoclose" : true});
	AutoTenagaKerja();
	AutoUnitKerja();
	AutoJabatan();
	AutoUnitKerjaUpdate();
	AutoJabatanUpdate();
});

function AutoJabatan(IdCabang) {
	IdCabang = IdCabang == undefined ? "" : IdCabang;
	$("#JabatanBaru").autocomplete({
		source: "load.php?proses=getDataJabatan&IdCabang=" + IdCabang,
		select: function (event, ui) {
			$("#JabatanBaru").val(ui.item.label);
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul); };
}

function AutoJabatanUpdate(IdCabang) {
	IdCabang = IdCabang == undefined ? "" : IdCabang;
	$("#Jabatan").autocomplete({
		source: "load.php?proses=getDataJabatan&IdCabang=" + IdCabang,
		select: function (event, ui) {
			$("#Jabatan").val(ui.item.label);
		}
	})
		.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul); };
}

function AutoTenagaKerja() {
	$("#NoKtp").autocomplete({
		source: "load.php?proses=getDataTenagaMutasi",
		focus : function(event,ui){
			$("#IdTenagaKerja").val("");
			$("#IdCabangLama").val("");
		},
		select: function (event, ui) {
			$("#IdTenagaKerja").val(ui.item.Id);
			$("#NoKtp").val(ui.item.label);
			$("#Nama").val(ui.item.Nama);
			$("#TTL").val(ui.item.TTL);
			$("#Pendidikan").val(ui.item.Pendidikan);
			$("#UnitKerjaLama").val(ui.item.NamaCabang);
			$("#IdCabangLama").val(ui.item.IdCabang);
			$("#JabatanLama").val(ui.item.Jabatan);
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.Nama + " | " + item.Jabatan + "</div>").appendTo(ul); };
}
function AutoUnitKerja() {
	$("#UnitKerjaBaru").autocomplete({
		source: "load.php?proses=getDataCabang",
		focus: function (event, ui) {
			$("#IdCabangBaru").val("");
			$("#JabatanBaru").val("");
		},
		select: function (event, ui) {
			AutoJabatan(ui.item.Id);
			$("#UnitKerjaBaru").val(ui.item.label);
			$("#IdCabangBaru").val(ui.item.Id);
		}
	})
		.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul); };
}

function AutoUnitKerjaUpdate() {
	$("#UnitKerja").autocomplete({
		source: "load.php?proses=getDataCabang",
		focus: function (event, ui) {
			$("#IdCabang").val("");
			$("#Jabatan").val("");
		},
		select: function (event, ui) {
			AutoJabatanUpdate(ui.item.Id);
			$("#UnitKerja").val(ui.item.label);
			$("#IdCabang").val(ui.item.Id);
		}
	})
		.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul); };
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

function LoadData(Page) {
	var ShowTampil = $("#ShowTampil").val();
	var Search = $("#Search").val();
	var Page = Page == undefined ? 1 : Page;
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "inc/Mutasi/proses.php?proses=DetailData",
		data: "ShowTampil=" + ShowTampil + "&Search=" + Search + "&Page=" + Page,
		beforeSend: function () {
			StartLoad();
		},
		success: function (result) {
			console.log(result);
			var html = "";
			if (result['status'] == 0) {
				if (result['total_data'] > 0) {
					for (var i = 0; i < result['data'].length; i++) {
						var res = result['data'][i];
						html += "<tr>";
						html += "<td class='text-center'>" + res['No'] + "</td>";
						html += "<td>" + res['Nama'] + "</td>";
						html += "<td>" + res['Jabatan'] + "</td>";
						html += "<td>" + res['UnitKerja'] + "</td>";
						html += "<td>" + res['TMTMulai'] + "</td>";
						html += "<td>" + res['TMTBerakhir'] + "</td>";
						html += "<td class='text-center'>" + res['Sk'] + "</td>";
						html += "<td>" + res['Aksi'] + "</td>";
						html += "</tr>";
					}
					var sampai = parseInt(result['no_first']) + parseInt(ShowTampil) - 1;
					sampai = Page == result['total_page'] ? result['no_last'] : sampai;
					$("#Showing").html("Menampilkan " + result['no_first'] + " sampai " + sampai + " dari " + result['total_data'] + " data");
					$("#ShowData").html(html);
					$("[data-toggle='tooltip']").tooltip();
					pagination(Page, result['total_page']);
					StopLoad();
				} else {
					$("#Showing").html("");
					$("#ShowData").html("<tr><td colspan='8' class='text-center'>Data no availible in table</td></tr>");
					pagination(Page, result['total_page']);
					StopLoad();
				}
			} else {
				$("#Showing").html("");
				$("#ShowData").html("<tr><td colspan='8' class='text-center'>" + result['pesan'] + "</td></tr>");
				StopLoad();
			}
		},
		error: function (er) {
			$("#ShowData").html("<tr><td colspan='8' class='text-center'>" + er + "</td></tr>");
			StopLoad();

		}
	})

}

function Clear(){
	$("#Title").html("Tampil Data Mutasi");
	$("#close_modal").trigger('click');
	$("#FormData,#FormUpdate").hide();
	$("#DetailData,#BtnControl").show();
	$("#aksi").val("");
	$("#proses").html("");
	$(".FormInput,.FormInputCustom").val("");
	$(".FormInput").prop('readonly', false);
	$(".FormInput").prop('disabled', false);
	
}

function Crud(Id,Status){
	Clear();
	$("#proses").html("");
	if(Id){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/Mutasi/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					$("#Title").html("Ubah Data Mutasi");
					$("#FormUpdate").show();
					$("#DetailData,#BtnControl").hide();
					$("#aksi").val("update");
					var iForm = ['IdUpdate', 'IdCabang', 'NoKtpUpdate', 'NamaUpdate', 'TTLUpdate', 'PendidikanUpdate', 'UnitKerja', 'TanggalMulai', "TanggalSelesai","Jabatan"];
					for(var i=0; i < iForm.length; i++){
						$("#" + iForm[i]).val(data[iForm[i]]);
					}
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#Id").val(Id)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Mutasi");
		$("#FormData").show();
		$("#DetailData,#BtnControl").hide();
		$("#proses").html("");
		$("#NoKtp").focus();
		$("#aksi").val("insert");

	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ["NoKtp", "IdTenagaKerja", "UnitKerjaBaru", "IdCabangBaru", "JabatanBaru", "TMTLama", "TMTBaru","Sk"];
	var KetiForm = ["No KTP Belum Lengkap!", "Tenaga Kerja Belum Dipilih. Pastikan menekan enter ketika meimilh No KTP", "Unit KerjaBaru Belum Lengkap!", "Pastikan menekan enter ketika melimilih Unit Kerja Baru", "Jabatan Baru Belum Lengkap!", "TMT Berakhir Belum Lengkap!", "TMT Mulai Belum Lengkap!", "File SK Belum Dipilih!"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete") {
			if ($("#" + iForm[i]).val() == "") { Customerror("Mutasi", "008", KetiForm[i], 'proses'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
		}
	}
}

function ValidasiUpdate() {
	var iForm = ["UnitKerja", "IdCabang", "Jabatan", "TanggalMulai"];
	var KetiForm = ["Unit Kerja belum lengkap!", "Unit Kerja  Belum Dipilih. Pastikan menekan enter ketika meimilh Unit Kerja", "Jabatan Belum Lengkap!", "TMT Mulai Belum Lengkap!"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if ($("#" + iForm[i]).val() == "") { Customerror("Mutasi", "008", KetiForm[i], 'proses'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
	}
}

$("#FormUpdate").submit(function (e) {
	e.preventDefault();
	SubmitDataUpdate();

})

function SubmitDataUpdate() {
	var aksi = $("#aksi").val();
	if (ValidasiUpdate() != false) {
		var data = new FormData($("#FormUpdate")[0]);
		$.ajax({
			type: "POST",
			url: "inc/Mutasi/proses.php?proses=Crud",
			data: data,
			contentType: false,
			processData: false,
			chace: false,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				console.log(result);
				var res = JSON.parse(result);
				if (res['status'] == '0') {
					Clear();
					Customsukses("Mutasi", '008', res['pesan'], 'proses');
					LoadData();
					StopLoad();
				} else {
					Clear();
					Customerror("Mutasi", "008", res['pesan'], 'proses');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
				Customerror("Mutasi", "008", er['responseText'], 'proses');
			}
		});
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
			url: "inc/Mutasi/proses.php?proses=Crud",
			data: data,
			contentType: false,
			processData : false,
			chace : false,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				console.log(result);
				var res = JSON.parse(result);
				if (res['status'] == '0') {
					Clear();
					Customsukses("Cabang", '008', res['pesan'],'proses');
					LoadData();
					StopLoad();
				}else{
					Clear();
					Customerror("Cabang", "008", res['pesan'], 'proses');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
				Customerror("Cabang", "008", er['responseText'], 'proses');
			}
		});
	}
}