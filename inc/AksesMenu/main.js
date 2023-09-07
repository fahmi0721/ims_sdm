$(document).ready(function(){
    Clear();
	LoadData();
	$("#Nama").autocomplete({
		source: "load.php?proses=getDataUsers",
		select: function (event, ui) {
			$("#Nama").val(ui.item.label);
			$("#IdUser").val(ui.item.IdUser)
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.Jabatan + "</div>").appendTo(ul); };

	$("#NamaMenu").autocomplete({
		source: "load.php?proses=getDataMenu",
		select: function (event, ui) {
			$("#NamaMenu").val(ui.item.label);
			$("#IdMenu").val(ui.item.IdMenu)
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul); };
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax": "inc/AksesMenu/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data": "Nama" },
			{ "data" : "Jabatan" },
			{ "data" : "Menu", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

}

$("#Icon").keyup(function(e){
	e.preventDefault();
	var str = $(this).val();
	$("#FilterIcon").html("<i class='fa fa-"+str+"'></i>");
})

function Clear(){
	$("#Title").html("Tampil Data Akses Menu");
	$("#close_modal").trigger('click');
	$("#close_modal1").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$(".FormInput").val("");
	
}

function Crud(Id,Status){
	Clear();
	$("#proses").html("");
	if(Id){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/AksesMenu/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					$("#Title").html("Ubah Data Akses Menu");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");
					var iForm = ['Id', 'NamaMenu', 'Nama', 'Status', 'IdMenu',"IdUser"];
					for(var i=0; i < iForm.length; i++){
						$("#" + iForm[i]).val(data[iForm[i]]);
					}
					$("#Nama").prop('readonly', true);
					$("#NamaMenu").prop('readonly', true);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else if(Status == "show"){
			jQuery("#modal_data").modal('show', { backdrop: 'static' });
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "inc/AksesMenu/proses.php?proses=DetailMenu",
				data: "IdUser=" + Id,
				beforeSend: function (data) {
					StartLoad();
				},
				success: function (data) {
					var row = parseInt(data['rows']);
					var html ="";
					if(row > 0){
						var No=1;
						var Level = ['Publiser','Authors','Guest'];
						for(var i=0; i < data['item'].length; i++){
							;
							res = data['item'][i];
							html += "<tr>";
								html += "<td class='text-center'>" + No + "</td>";
								html += "<td>" + res['NamaMenu'] + "</td>";
								html += "<td>" + Level[res['Status']] + "</td>";
								html += "<td class='text-center'>" + res['aksi'] + "</td>";
							html += "</tr>";
							No++;
						}
					}else{
						html += "<tr><td class='text-center' colspan='4'>Menu Akses Belum Ada!</td></tr>";
					}
					$("#data_show").html(html);
					$('[data-toggle="tooltip"]').tooltip();
					StopLoad();
				},
				error: function (er) {
					console.log(er);
				}
			})
		} else if (Status == "locked"){
			var Pisah = Id.split("#");
			$.ajax({
				type: "POST",
				url: "inc/AksesMenu/proses.php?proses=Crud",
				data: "aksi=UpdateAktif&Id="+Pisah[0]+"&Aktif="+Pisah[1],
				beforeSend: function () {
					StartLoad();
				},
				success: function (result) {
					var res = JSON.parse(result);
					var Table = $("#TableData").DataTable();
					if (res['status'] == '0') {
						Clear();
						Customsukses("Akses Menu", '003', res['pesan'], 'proses');
						Table.ajax.reload();
						StopLoad();
					} else {
						Customerror("Menu", "003", res['pesan'], 'proses');
						Clear();
						StopLoad();
					}
				},
				error: function (er) {
					console.log(er);
				}
			});
		} else if (Status == "hapus_semua") {
			jQuery("#modal").modal('show', { backdrop: 'static' });
			$("#aksi").val('delete_all');
			$("#Id").val(Id)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus semua akses data ini ?</div>");
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#Id").val(Id)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	
	}else{
		$("#Title").html("Tambah Data Akses Menu");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#proses").html("");
		$("#CostCenter").focus();
		$("#aksi").val("insert");

	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ["Nama", "NamaMenu","Status"];
	var KetiForm = ["Nama User", "Menu", "Level"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete" && aksi != 'delete_all') {
			if ($("#" + iForm[i]).val() == "") { error("Menu", KodeError + i, KetiForm[i] + " Belum Lengkap!"); $("#" + iForm[i]).focus(); scrolltop(); return false; }
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
			url: "inc/AksesMenu/proses.php?proses=Crud",
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				
				var res = JSON.parse(result);
				var Table = $("#TableData").DataTable();
				if (res['status'] == '0') {
					Clear();
					Customsukses("Menu", '002', res['pesan'],'proses');
					Table.ajax.reload();
					StopLoad();
				}else if(res['status'] == 2){
					Customerror("Menu", "002", res['pesan'], 'proses');
					StopLoad();
				}else{
					Customerror("Menu", "002", res['pesan'], 'proses');
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