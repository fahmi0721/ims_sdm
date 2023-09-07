$(document).ready(function(){
	Clear();
	SearchForm();
	getPeriode();
	
	
});

function Export(){
	var iData = $("#FormData").serialize();
	window.open("Export/RekapDataPeriode.php?" + iData, "MsgWindow", "width=200,height=100");
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
		url: "inc/RekapDataPeriode/proses.php?proses=LoadData",
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

function getUrl(sParam) {
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

function LoadData(page) {
	$("#ShowData").hide();
	var Periode = $("#Periode").val() == undefined ? "" : $("#Periode").val();
	var Pages = btoa(getUrl('page'));
	$.ajax({
		type: "POST",
		url: "inc/RekapDataPeriode/proses.php?proses=DetailData",
		data: "Periode=" + Periode,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			var result = JSON.parse(res);
			console.log(result);
			if (parseInt(result['JumRow']) > 0){
				$("#btn-export").show();
				var html = "<div class='row'>";
				for (var i = 0; i < result['JumRow']; i++ ){
					var iData = result['data'][i];
					html += "<div class='col-md-4 col-sm-6 col-xs-12'>";
						html += "<div class='small-box bg-" + iData['bg-color']+"' data-toggle='tooltip' title='" + iData['NamaCabangTitle'] +"'>";
							html += "<div class='inner'>";
								html += "<h3>" + iData['Total']+"</h3>";
								html += "<p>" + iData['NamaCabang'] +"</p>";
							html += "</div>";

							html += "<div class='icon'>";
									html += "<i class='fa fa-users'></i>";
							html += "</div>";
					html += "<a href='index.php?page=FilterTenagaKerjaPeriode&TitleForm=" + iData['NamaCabangTitle'] + "&From="+iData['From']+"&key=" + iData['link-data'] + "&rolback=" + Pages+"' class='small-box-footer'> More info <i class='fa fa-arrow-circle-right'></i></a>";
						html += "</div>";
					html += "</div>";

				}
				html += "<div class='col-lg-4 col-sm-3 col-xs-12'>";
				html += "<div class='small-box bg-" + result['BgColorKhusus'] + "'>";
				html += "<div class='inner'>";
				html += "<h3>" + result['total_data'] + "</h3>";
				html += "<p>TOTAL DATA TENAGA KERJA</p>";
				html += "</div>";

				html += "<div class='icon'>";
				html += "<i class='fa fa-users'></i>";
				html += "</div>";
				html += "<a href='javascript:void(0)' class='small-box-footer'> More info <i class='fa fa-arrow-circle-right'></i></a>";
				html += "</div>";
				html += "</div>";
				html += "</div>";
				$("#ShowData").show();
				$("#ShowData").html(html);
				$("[data-toggle='tooltip']").tooltip();
				StopLoad();
			}else{
				alert('Data belum ada');
				StopLoad();
				$("#btn-export").hide();
			}
			
			
		},
		error: function (er) {
			$("#proses").html(er['responseText']);
			StopLoad();
		}
	})

}

function Clear(){
	$("#Title").html("Berdasarkan Periode");
	$("#close_modal").trigger('click');
	$("#FormData").show();
	$("#ShowData").hide();
	$("#btn-export").hide();
	$(".FormInput").val("");
	
}

$("#FormData").submit(function (e) {
	e.preventDefault();
	LoadData(1);
})