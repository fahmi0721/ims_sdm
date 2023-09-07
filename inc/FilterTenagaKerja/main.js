$(document).ready(function(){
	Clear();
	LoadData(1);

	
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
	var key = getUrl('key');
	var TitleForm = getUrl('TitleForm');
	var Rolback = atob(getUrl('rolback'));
	$("#BtnControl").find("a.btn-roolback").attr("href","index.php?page="+Rolback);
	$.ajax({
		type: "POST",
		url: "inc/FilterTenagaKerja/proses.php?proses=DetailData",
		data: "RowPage=" + RowPage + "&Page=" + page + "&Key=" + key,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			$("#ShowData").hide();
			var result = JSON.parse(res);
			console.log(result);
			var html = "";
			if (parseInt(result['total_data']) > 0) {
				$("#Title").html(TitleForm);
				$("#JumL").html(result['JumPria']);
				$("#JumP").html(result['JumWaninta']);
				html += "<div class='row'>";
				for (var i = 0; i < result['total_data']; i++){
					var iData = result['data'][i];
					html += "<div class='col-md-3 col-sm-6 col-xs-12'>";
						html += "<div class='panel'>";
							html += "<div class='panel-body'>";
								html += "<div class='box-tk-utama'>";
									html += "<div class='box-unit-kerja'><label class='label bg-purple' data-toggle='tooltip' title='" + iData['NamaCabang']+"'><i class='fa fa-bank'></i> " + iData['UnitKerja'] +"</label></div>";
									html += "<div class='box-image'><img class='img-responsive' src='img/" + iData['Foto']+"'></div><hr>";
									html += "<div class='box-caption'>";
										html += "<h4 data-toggle='tooltip' title='"+iData['NamaS']+"'>" + iData['Nama']+"</h4>";
										html += "<label class='label bg-teal'  data-toggle='tooltip' title='Jabatan/Seksi'><i class='fa fa-tag'></i> " + iData['Seksi']+"</label>";
									html += "</div>";
								html += "</div>";
							html += "</div>";
							html += "<div class='box-button-detail'>";
					html += "<a href='index.php?page=DetailTk&Id=" + btoa(iData['NoKtp']) + "' class='btn btn-success btn-block btn-flat' data-toggle='tooltip' title='Detail Data Tenaga Kerja'><i class='fa fa-eye'></i> Lihat Detail</a>";
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

function Clear(){
	$("#Title").html("Detail Data");
	//$("#close_modal").trigger('click');
	$("#ShowData").show();
	
	
}

$("#FormData").submit(function (e) {
	e.preventDefault();
	LoadData(1);
})