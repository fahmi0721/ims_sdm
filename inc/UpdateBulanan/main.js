$(document).ready(function(){
	Clear();
	SearchForm();
	getPeriode();
	getKet();
});

function getKet(){
	$(".appa").remove();
	$.ajax({
		type: "POST",
		url: "inc/UpdateBulanan/proses.php?proses=LoadData",
		data: "rule=CountPeriode",
		beforeSend: function () {
			StartLoad();
		},
		success: function (r) {
			var res = JSON.parse(r);
			if(res['Data'] != 0){
				$("#KetLi").append("<li class='appa'><small>Terdapat data bulan <b>" + res['Data'] +"</b> belum di backup</small></li>");
			}

		},
		error: function (er) {
			console.log(er);
		}
	})
}

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
	
}

function getPeriode(){
	$.ajax({
		type : "POST",
		url: "inc/UpdateBulanan/proses.php?proses=LoadData",
		data : "rule=Periode",
		beforeSend : function(){
			StartLoad();
		},
		success: function(r){
			var res = JSON.parse(r);
			var opt = "<option value=''>Pilih Periode</option>";
			if(parseInt(res['Row']) > 0 ){
				for(var i=0; i < res['Data'].length; i++){
					var iData = res['Data'][i];
					opt += "<option value='" + iData['Periode'] + "'>" + iData['PeriodeNama']+"</option>";
				}
			}
			$("#Periode").html(opt);
			
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
	$(".Paging").html(paging);
}


function Clear(){
	$("#Title").html("Backup Data Bulanan");
	$("#close_modal").trigger('click');
	$("#FormData").show();
	$("#ShowData").hide();
	$("#btn-export").hide();
	$(".FormInput").val("");
	
}

function BackupData() {
	$("#ShowData").hide();
	var Periode = $("#Periode").val() == undefined ? "" : $("#Periode").val();
	$.ajax({
		type: "POST",
		url: "inc/UpdateBulanan/proses.php?proses=UpdateData",
		data: "Periode=" + Periode,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var result = JSON.parse(res);
			console.log(result);
			if(result['status'] == "sukses"){
				Customsukses("Backup Data Bulanan", '007', result['pesan'], 'proses');
			}else{
				Customerror("Backup Data Bulanan", "007", result['pesan'], 'proses');
			}
			Clear();
			$(".FormInput").trigger("change");
			getPeriode();
			getKet();
			StopLoad();

		},
		error: function (er) {
			$("#proses").html(er['responseText']);
			StopLoad();
		}
	})

}

$("#FormData").submit(function (e) {
	e.preventDefault();
	BackupData();
})