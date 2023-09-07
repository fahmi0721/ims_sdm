$(document).ready(function(){
    Clear();
	LoadData(1);
	
});

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
		url: "inc/MasterPendidikanFormal/proses.php?proses=DetailData",
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
					html += "<td>" + r['Kode'] + "</td>";
					html += "<td>" + r['Nama'] + "</td>";
					html += "<td>" + r['Flag'] + "</td>";
					html += "<td class='text-center'>" + r['Aksi'] + "</td>";
					html += "</tr>";
				}
			} else {
				html = "<tr><td class='text-center' colspan='5'>No data availible in table.</td></tr>";
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

function Clear(){
	$("#Title").html("Tampil Data Master Pendidikan Formal");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$(".FormInput").val("");
	$('#BtnControl').show();
	
}

function Crud(Id,Status){
	Clear();
	$("#proses").html("");
	if(Id){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/MasterPendidikanFormal/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Master Pendidikan Formal");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");
					$('#BtnControl').hide();
					var iForm = ['Id','Nama', 'Flag'];
					for(var i=0; i < iForm.length; i++){
						if(iForm[i] == "Flag"){
							$("#Flag" + data[iForm[i]]).prop("checked", true);
						}else{
							$("#" + iForm[i]).val(data[iForm[i]]);
						}
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
		$("#Title").html("Tambah Data Master Pendidikan Formal");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#proses").html("");
		$("#Kode").focus();
		$('#BtnControl').hide();
		$("#aksi").val("insert");

	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ["Nama"];
	var KetiForm = ["Nama Pendidikan belum lengkap"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete") {
			if ($("#" + iForm[i]).val() == "") { Customerror("Master Pendidikan Formal", "007", KetiForm[i], 'ProsesCrud'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
		}
	}
}

$("#FormData").submit(function(e){
	e.preventDefault();
	SubmitData();
	
})

function SubmitData(){
	var aksi = $("#aksi").val();
	if (Validasi() != false) {
		var data = $("#FormData").serialize();
		$.ajax({
			type: "POST",
			url: "inc/MasterPendidikanFormal/proses.php?proses=Crud",
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				if (res['status'] == 'sukses') {
					Clear();
					Customsukses("Master Pendidikan Formal", '007', res['pesan'],'proses');
					LoadData();
					StopLoad();
				}else{
					Customerror("Master Pendidikan Formal", "007", res['pesan'], 'ProsesCrud');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
			}
		});
	}
}