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


function LoadDataSeksi(){
	$("#SeksiData").html("<tr><td class='text-center' colspan='3'>Load Data...</td></tr>");
	$.ajax({
		type: "POST",
		url: "inc/MasterKlasifikasi/proses.php?proses=LoadData",
		data: "aksi=Seksi",
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
					html += "<td>" + r['NamaSeksi'] + "</td>";
					html += "<td class='text-center'>" + r['aksi'] + "</td>";
					html += "</tr>";
				}
			} else {
				html = "<tr><td class='text-center' colspan='5'>No data availible in table.</td></tr>";
			}
			$("#SeksiData").html(html);
			$("[data-toggle='tooltip']").tooltip();
			StopLoad();
		},
		error: function (er) {
			$("#proses").html(er['responseText']);
			StopLoad();
		}
	})
}

function CheckAll(){
	if($("#AllCek").is(":checked")){
		$(".childKlasifikasi").prop("checked",true);
	}else{
		$(".childKlasifikasi").prop("checked",false);		
	}
}

function HitungJumlahChecked(){
	var tot = $(".childKlasifikasi").filter(":checked").length;
	if(tot > 0){
		$("#AllCek").prop("checked", true);
	}else{
		$("#AllCek").prop("checked", false);
	}
}

function ClearModal(){
	$(".modal-footer").hide();
	$(".modal-title").html("");
}


function LoadData(page) {
	page = page == undefined ? 1 : page;
	var RowPage = $("#RowPage").val();
	var Search = $("#Search").val();
	$.ajax({
		type: "POST",
		url: "inc/MasterKlasifikasi/proses.php?proses=DetailData",
		data: "Search=" + Search + "&RowPage=" + RowPage + "&Page=" + page,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			
			var result = JSON.parse(res);
			var html = "";
			if (result['total_data'] > 0) {
				for (var i = 0; i < result['data'].length; i++) {
					var r = result['data'][i];
					html += "<tr>";
					html += "<td class='text-center'>" + r['No'] + "</td>";
					html += "<td>" + r['Nama'] + "</td>";
					html += "<td>" + r['Jabatan'] + "</td>";
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

function DetailJabatan(str){ 	
	ClearModal();
	var dt = atob(str);
	var iData = JSON.parse(dt);
	$(".modal-title").html("Detail Jabatan");
	var html = "<div class='table-responsive'>";
		html += "<table class='table table-striped table-bordered'>";
			html += "<tr>";
				html += "<th width='5%' class='text-center'>No</th>";
				html += "<th>Kode</th>";
				html += "<th>Nama Jabatan</th>";
			html += "</tr>";
			if(iData['total_data'] > 0){
				for(var i=0; i < iData['data'].length; i++){
					var dts = iData['data'][i];
					html += "<tr>";
					html += "<td widtd='5%' class='text-center'>"+dts['No']+"</td>";
					html += "<td>"+dts['Kode']+"</td>";
					html += "<td>"+dts['NamaSeksi']+"</td>";
				html += "</tr>";
				}
			}else{
				html += "<tr>";
					html += "<td colspan='3'class='text-center'>No data availible in table</td>";
				html += "</tr>";
			}


		html += "</table>";
	html +"</div>";

	$("#proses_del").html(html);
	jQuery("#modal").modal('show', {backdrop: 'static'});
}

function Clear(){
	$("#Title").html("Tampil Data Master Klasifikasi Jabatan");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$(".FormInput").val("");
	$("#AllCek,.childKlasifikasi").prop("checked",false);
}

function Crud(Id,Status){
	Clear();
	LoadDataSeksi();
	$("#proses").html("");
	if(Id){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/MasterKlasifikasi/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					var Klsi = JSON.parse(data['Klasifikasi']);
					$("#Title").html("Ubah Data Master Klasifikasi Jabatan");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");
					var iForm = ['Id', 'Nama'];
					for(var i=0; i < iForm.length; i++){
						$("#" + iForm[i]).val(data[iForm[i]]);
					}

					for(var j=0; j < Klsi.length; j++){
						var ks = Klsi[j];
						$(".Klasifikasi"+ks).prop("checked",true);
						console.log(ks)
					}
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			$(".modal-footer").show();
			$(".modal-title").html("Komfirmasi Hapus Data");
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#Id").val(Id)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Master Klasifikasi Jabatan");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#proses").html("");
		$("#Nama").focus();
		$("#aksi").val("insert");

	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ["Nama",];
	var KetiForm = ["Nama Paket Klasifikasi belum lengkap"];
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete") {
			if ($("#" + iForm[i]).val() == "") { Customerror("Master Klasifikasi Jabatan", "001", KetiForm[i], 'ProsesCrud'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
		}
	}
	if (aksi != "delete") {
		var tot = $(".childKlasifikasi").filter(":checked").length;
		if(tot <= 0){
			Customerror("Master Klasifikasi Jabatan", "001", "Jabatan belum dipilih", 'ProsesCrud'); $("#AllCek").focus(); scrolltop(); return false; 
		}
	}
}

$("#FormData").submit(function(e){
	e.preventDefault();
	SubmitData();
	
})

function SubmitData(){
	if (Validasi() != false) {
		var data = $("#FormData").serialize();
		$.ajax({
			type: "POST",
			url: "inc/MasterKlasifikasi/proses.php?proses=Crud",
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				console.log(res);
				if (res['status'] == 'sukses') {
					Clear();
					Customsukses("Master Cabang", '001', res['pesan'],'proses');
					LoadData();
					StopLoad();
				}else{
					Customerror("Master Cabang", "001", res['pesan'], 'proses');
					Clear();
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
			}
		});
	}
}