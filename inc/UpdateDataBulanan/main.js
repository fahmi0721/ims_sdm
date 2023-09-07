$(document).ready(function(){
	Clear();
	SearchForm();
	getPeriode();
	getNoKtp();
});

function Export(){
	var iData = $("#FormData").serialize();
	window.open("Export/RekapDataUsia.php?" + iData, "MsgWindow", "width=200,height=100");
}
	

function SearchForm() {
	$('.select-periode').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Periode',
	});

	$('.select-no-ktp').select2({
		minimumInputLength: 3,
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Tenaga Kerja',
	});
	
}

function getNoKtp(){
	$.ajax({
		type : "POST",
		url: "inc/UpdateDataBulanan/proses.php?proses=LoadData",
		data : "rule=NoKtp",
		beforeSend : function(){
			StartLoad();
		},
		success: function(r){
			var res = JSON.parse(r);
			var opt = "<option value=''>Pilih TenagaKerja</option>";
			if(parseInt(res['Row']) > 0 ){
				for(var i=0; i < res['Data'].length; i++){
					var iData = res['Data'][i];
					opt += "<option value='" + iData['NoKtp'] + "'>" + iData['Nama']+"</option>";
				}
			}
			$("#NoKtp").html(opt);
			
		},
		error : function(er){
			console.log(er);
		}
	})
}

function getPeriode() {
	$.ajax({
		type: "POST",
		url: "inc/UpdateDataBulanan/proses.php?proses=LoadData",
		data: "rule=Periode",
		beforeSend: function () {
			StartLoad();
		},
		success: function (r) {
			var res = JSON.parse(r);
			var opt = "<option value=''>Pilih Periode</option>";
			if (parseInt(res['Row']) > 0) {
				for (var i = 0; i < res['Data'].length; i++) {
					var iData = res['Data'][i];
					opt += "<option value='" + iData['Periode'] + "'>" + iData['PeriodeNama'] + "</option>";
				}
			}
			$("#Periode").html(opt);

		},
		error: function (er) {
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
	$(".Paging").html(paging);
}


function Clear(){
	$("#Title").html("Update Data Bulanan");
	$("#close_modal").trigger('click');
	$("#FormData").show();
	$("#ShowData").hide();
	$("#btn-export").hide();
	$(".FormInput").val("").trigger("change");
	
}

function LoadData(page) {
	page = page == undefined ? 1 : page;
	var RowPage = $("#RowPage").val();
	var iData = $("#FormData").serialize();
	$.ajax({
		type: "POST",
		url: "inc/UpdateDataBulanan/proses.php?proses=DetailData",
		data: "RowPage=" + RowPage + "&Page=" + page + "&"+ iData,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			$("#ShowData").hide();
			var result = JSON.parse(res);
			var html = "";
			if (parseInt(result['total_data']) > 0) {
				html += "<div class='row'>";
				for (var i = 0; i < result['total_data']; i++) {
					var iData = result['data'][i];
					html += "<div class='col-md-3 col-sm-6 col-xs-12'>";
					html += "<div class='panel'>";
					html += "<div class='panel-body'>";
					html += "<div class='box-tk-utama'>";
					html += "<div class='box-unit-kerja'><label class='label bg-purple' data-toggle='tooltip' title='" + iData['NamaCabang'] + "'><i class='fa fa-bank'></i> " + iData['UnitKerja'] + "</label></div>";
					html += "<div class='box-image'><img class='img-responsive' src='img/" + iData['Foto'] + "'></div><hr>";
					html += "<div class='box-caption'>";
					html += "<h4 data-toggle='tooltip' title='" + iData['NamaS'] + "'>" + iData['Nama'] + "</h4>";
					html += "<label class='label bg-teal'  data-toggle='tooltip' title='Jabatan/Seksi'><i class='fa fa-tag'></i> " + iData['Seksi'] + "</label>";
					html += "</div>";
					html += "</div>";
					html += "</div>";
					html += "<div class='box-button-detail'>";
					html += "<center><a href='index.php?page=DetailTk&Id=" + btoa(iData['NoKtp']) + "' class='btn btn-success btn-flat' data-toggle='tooltip' title='Detail Data Tenaga Kerja'><i class='fa fa-eye'></i></a>";
					html += " <a href='javascript:void(0)' onclick='UpdateDataBulanan("+iData['Id']+")' class='btn btn-primary btn-flat' data-toggle='tooltip' title='Update data sesuai dengan perubahan terakhir'><i class='fa fa-edit'></i></a>";
					html += " <a href='javascript:void(0)' onclick='HapusDataBulanan(" + iData['Id'] +")' class='btn btn-danger btn-flat' data-toggle='tooltip' title='Hapus data ini'><i class='fa fa-trash'></i></a></center>";
					html += "</div>";
					html += "</div>";
					html += "</div>";
				}
				html += "</div>";
				$("#TampilData").html(html);
				var PageInfo = "Total Data : <b>" + result['JumRow'] + "</b>";
				var PagingTime = "Waktu Ekseskusinya : <b>" + result['Waktu'] + " detik</b>";
				$("#PagingInfo").html(PageInfo)
				$("#PagingTime").html(PagingTime);
				pagination(page, result['total_page']);
				StopLoad();
				$("#ShowData").show();
				$("[data-toggle='tooltip']").tooltip();
				scrolltop();
			} else {
				alert("Data tidak ditemukan");
				StopLoad();
			}

		},
		error: function (er) {
			$("#proses").html(er['responseText']);
			StopLoad();
		}
	})

}

function UpdateDataBulanan(Id){
	$.ajax({
		type: "POST",
		url: "inc/UpdateDataBulanan/proses.php?proses=UpdateData",
		data: "Id="+Id,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var r = JSON.parse(res);
			if (r['status'] == "sukses") {
				Customsukses("Update Data Bulanan", '007', r['pesan'], 'proses');
			} else {
				Customerror("Update Data Bulanan", "007", r['pesan'], 'proses');
			}
			LoadData(1);
			StopLoad();
			scrolltop();
		},
		error: function (er) {
			$("#proses").html(er['responseText']);
			StopLoad();
		}
	})
}

function HapusDataBulanan(Id) {
	if(confirm("Anda yakin menghapus data ini?")){
		$.ajax({
			type: "POST",
			url: "inc/UpdateDataBulanan/proses.php?proses=HapusData",
			data: "Id=" + Id,
			beforeSend: function () {
				StartLoad();
			},
			success: function (res) {
				var r = JSON.parse(res);
				if (r['status'] == "sukses") {
					Customsukses("Hapus Data Bulanan", '007', r['pesan'], 'proses');
				} else {
					Customerror("Hapus Data Bulanan", "007", r['pesan'], 'proses');
				}
				Clear();
				StopLoad();
				scrolltop();
			},
			error: function (er) {
				$("#proses").html(er['responseText']);
				StopLoad();
			}
		})
	}else{
		return false;
	}
}

$("#FormData").submit(function (e) {
	e.preventDefault();
	var iForm = ['Periode', 'NoKtp'];
	var KetiForm = ['Periode belum dipilih', 'No KTP belum dipilih'];
	for(var i=0; i < iForm.length; i++){
		if($("#"+iForm[i]).val() == ""){
			if (iForm[i] == "NoKtp") {
				$(".select-no-ktp").select2("focus");
			} else if (iForm[i] == "Periode") {
				$(".select-periode").select2("focus");
			}
			Customerror("Update Data Bulanan", "007", KetiForm[i], 'ProsesCrud'); $("#" + iForm[i]).focus(); scrolltop(); return false; 

		}
	}
	LoadData(1);
})