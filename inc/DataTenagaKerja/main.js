$(document).ready(function(){
	Clear();
	SearchForm();
	getUnitKerja();
	// getTenagaKerja();
	getBranch();
	
	
});

function SearchForm() {
	$('.select-unit-kerja').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Unit Kerja',
	});

	$('.select-branch').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Branch',
	});

	$('.select-branch').on("change", function (e) {
		Clear();
		getUnitKerja();
		// getTenagaKerja();
	});
}

function getUnitKerja(){
	var Id = $("#IdBranch").val() == undefined ? "" : $("#IdBranch").val();
	$.ajax({
		type : "POST",
		url : "inc/DataTenagaKerja/proses.php?proses=LoadData",
		data : "rule=UnitKerja&Id="+Id,
		beforeSend : function(){
			StartLoad();
		},
		success: function(r){
			var res = JSON.parse(r);
			var opt = "<option value=''>Pilih Unit Kerja</option>";
			if(parseInt(res['Row']) > 0 ){
				for(var i=0; i < res['Data'].length; i++){
					var iData = res['Data'][i];
					opt += "<option value='" + iData['Kode']+"'>"+iData['NamaCabang']+"</option>";
				}
			}else{
				alert('Data tenaga kerja pada Branch ini tidak ditemukan');
				StopLoad();
			}
			$("#IdCabang").html(opt);
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}

function getBranch() {
	$.ajax({
		type: "POST",
		url: "inc/DataTenagaKerja/proses.php?proses=LoadData",
		data: "rule=Branch",
		beforeSend: function () {
			StartLoad()
		},
		success: function (r) {
			var res = JSON.parse(r);
			var opt = "<option value=''>Pilih Branch</option>";
			if (parseInt(res['Row']) > 0) {
				for (var i = 0; i < res['Data'].length; i++) {
					var iData = res['Data'][i];
					opt += "<option value='" + iData['Kode'] + "'>" + iData['Nama'] + "</option>";
				}
				$("#IdBranch").html(opt);
				

			}
			
		},
		error: function (er) {
			console.log(er);
		}
	})
}

// function getTenagaKerja() {
// 	var Id = $("#IdCabang").val() == undefined ? "" : $("#IdCabang").val();
// 	$.ajax({
// 		type: "POST",
// 		url: "inc/DataTenagaKerja/proses.php?proses=LoadData",
// 		data: "rule=TenagaKerja&Id="+Id,
// 		beforeSend: function () {
// 			StartLoad()
// 		},
// 		success: function (r) {
// 			var res = JSON.parse(r);
// 			console.log(res);
// 			var opt = "<option value=''>Pilih Tenaga Kerja</option>";
// 			if (parseInt(res['Row']) > 0) {
// 				for (var i = 0; i < res['Data'].length; i++) {
// 					var iData = res['Data'][i];
// 					opt += "<option value='" + iData['NoKtp'] + "'>" + iData['Nama'] + "</option>";
// 				}
// 				$("#NoKtp").html(opt);
// 				StopLoad();
// 			}else{
// 				alert('Data tenaga kerja pada unit kerja ini tidak ditemukan');
// 				StopLoad();
// 			}
			
// 		},
// 		error: function (er) {
// 			console.log(er);
// 		}
// 	})
// }

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

function LoadData(page) {
	page = page == undefined ? 1 : page;
	var RowPage = $("#RowPage").val();
	var KodeCabang = $("#IdCabang").val() == undefined ? "" : $("#IdCabang").val();
	var KodeBranch = $("#IdBranch").val() == undefined ? "" : $("#IdBranch").val();
	$.ajax({
		type: "POST",
		url: "inc/DataTenagaKerja/proses.php?proses=DetailData",
		data: "RowPage=" + RowPage + "&Page=" + page+"&KodeCabang="+KodeCabang+"&KodeBranch="+KodeBranch,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			$("#ShowData").hide();
			var result = JSON.parse(res);
			console.log(result);
			var html = "";
			if (parseInt(result['total_data']) > 0) {
				$("#BtnExport").show();
				html += "<div class='row'>";
				for (var i = 0; i < result['total_data']; i++){
					var iData = result['data'][i];
					html += "<div class='col-md-3 col-sm-6 col-xs-12'>";
						html += "<div class='panel'>";
							html += "<div class='panel-body'>";
								html += "<div class='box-tk-utama'>";
									html += "<div class='box-unit-kerja'><label class='label bg-purple' data-toggle='tooltip' title='"+iData['NamaCabang']+"'><i class='fa fa-bank'></i> " + iData['UnitKerja'] +"</label></div>";
									html += "<div class='box-image'><img class='img-responsive' src='img/" + iData['Foto']+"'></div><hr>";
									html += "<div class='box-caption'>";
										html += "<h4 data-toggle='tooltip' title='" + iData['NamaS']+"'>" + iData['Nama']+"</h4>";
										html += "<label class='label bg-teal'  data-toggle='tooltip' title='Jabatan/Seksi'><i class='fa fa-tag'></i> " + iData['Seksi']+"</label>";
									html += "</div>";
								html += "</div>";
							html += "</div>";
							html += "<div class='box-button-detail'>";
					html += "<a href='index.php?page=DetailTk&Id=" + btoa(iData['NoKtp']) +"' class='btn btn-success btn-block btn-flat' data-toggle='tooltip' title='Detail Data Tenaga Kerja'><i class='fa fa-eye'></i> Lihat Detail</a>";
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
			} else{
				$("#BtnExport").hide();
				alert("Data tidak ditemukan");
				StopLoad();
			}
			
		},
		error: function (er) {
			console.log(er);
			$("#proses").html(er['responseText']);
			StopLoad();
		}
	})

}

$("#BtnExport").click(function(e){
	var FormData = $("#FormData").serialize();
	window.open("Export/TenagaKerja.php?"+FormData,"_blank");
})

function Clear(){
	$("#Title").html("Pencraian Cepat Tenaga Kerja");
	$("#close_modal").trigger('click');
	$("#FormData").show();
	$("#ShowData").hide();
	$("#BtnExport").hide();
	$(".FormInput").val("");
	
}

$("#FormData").submit(function (e) {
	e.preventDefault();
	LoadData(1);
})