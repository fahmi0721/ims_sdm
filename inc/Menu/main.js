$(document).ready(function(){
    Clear();
	LoadData();
	
	$("#MenuItemRoot").autocomplete({
		source: "load.php?proses=getDataItemRoot",
		select: function (event, ui) {
			$("#MenuItemRoot").val(ui.item.label);
			$("#ItemRoot").val(ui.item.ItemRoot)
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul); };
	

});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax": "inc/Menu/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data": "NamaMenu" },
			{ "data" : "Direktori" },
			{ "data": "ItemRoot" },
			{ "data": "Status", "sClass": "text-center" },
			{ "data" : "Aksi", "sClass" : "text-center" }
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
	$("#Title").html("Tampil Data Menu");
	$("#close_modal").trigger('click');
	$("#close_modal1").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	$(".FormInput").val("");
	
}

function LoadUser(){
	$("#NamaUser").autocomplete({
		source: "load.php?proses=getDataUsers",
		focus: function (event, ui) {
			console.log(event);
		},
		select: function (event, ui) {
			$("#NamaUser").val(ui.item.label);
			$("#IdUser").val(ui.item.IdUser)
		},
		appendTo: $("#FormApprovel")
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.Jabatan + "</div>").appendTo(ul); };
}

function Crud(Id,Status){
	Clear();
	$("#proses").html("");
	if(Id){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/Menu/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					$("#Title").html("Ubah Data Menu");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");
					var iForm = ['Id', 'NamaMenu', 'Direktori', 'Icon', 'ItemRoot',"MenuItemRoot"];
					for(var i=0; i < iForm.length; i++){
						$("#" + iForm[i]).val(data[iForm[i]]);
					}
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		} else if(Status == "Status") {
			var Pisah = Id.split("#");
			var IdMenu = Pisah[0];
			var St = Pisah[1];
			$.ajax({
				type: "POST",
				url: "inc/Menu/proses.php?proses=Crud",
				data: "aksi=Status&Id="+IdMenu+"&Status="+St,
				beforeSend: function () {
					StartLoad();
				},
				success: function (result) {
					var res = JSON.parse(result);
					var Table = $("#TableData").DataTable();
					if (res['status'] == '0') {
						Clear();
						Customsukses("Menu", '001', res['pesan'], 'proses');
						Table.ajax.reload();
						StopLoad();
					} else {
						Customerror("Menu", "001", res['pesan'], 'proses');
						Clear();
						StopLoad();
					}
				},
				error: function (er) {
					console.log(er);
				}
			});
		} else if (Status == "ShowApproval") {
			CelarListApproval();
			jQuery("#ListApprovel").modal('show', { backdrop: 'static' });
			$("#IdMenu").val(Id);
			LoadListApprovel(Id);
			LoadUser();
				
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#Id").val(Id)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Menu");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#proses").html("");
		$("#CostCenter").focus();
		$("#aksi").val("insert");

	}
}


$("#FormApprovel").submit(function(e){
	e.preventDefault();
	if (ValidasiListApproval() != false){
		data = "aksi=InsertListApprovel&IdMenu="+$("#IdMenu").val()+"&IdUser="+$("#IdUser").val();
		$.ajax({
			type: "POST",
			url: "inc/Menu/proses.php?proses=Crud",
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				if (res['status'] == '0') {
					var Id = $("#IdMenu").val();
					CelarListApproval();
					Customsukses("Menu", '001', res['pesan'], 'ProsesList');
					LoadListApprovel(Id);
					StopLoad();
				} else {
					Customerror("Menu", "001", res['pesan'], 'ProsesList');
					CelarListApproval();
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
			}
		});
	}
});

 function ValidasiListApproval(){
	 var iForm = ['NamaUser', 'IdUser'];
	 var iKet = ['Nama User Masih Kosong','Masukan User yang benar!'];
	 for(var i=0; i < iForm.length; i++){
		 if ($("#" + iForm[i]).val() == "") { Customerror("Menu", "001", iKet[i], 'ProsesList'); $("#NamaUser").focus(); return false; }
	 }
 }

function LoadListApprovel(Id){
	$.ajax({
		type: "POST",
		dataType: 'json',
		url: "inc/Menu/proses.php?proses=ShowApproval",
		data: "Id=" + Id,
		beforeSend: function () {
			StartLoad();
		},
		success: function (result) {
			if(result['status'] == 0){
				var rows = parseInt(result['rows']);
				if (rows > 0 ) {
					var html ="";
					var No=1;
					for(var i=0; i < rows; i++){
						var dt = result['item'][i];
						html += "<tr>";
						html += "<td class='text-center'>" + No + "</td>";
						html += "<td>" + dt['Nama'] + "</td>";
						html += "<td>" + dt['Posisi'] + "</td>";
						html += "<td><a href='javascript:void(0)' onclick=\"HapusListApproval('"+dt['Id']+"')\" class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></td>";
						html += "<tr>";
						No++;
					}
					$("#ShowDataApprovel").html(html);
				} else {
					$("#ShowDataApprovel").html("<tr><td colspan='4' class='text-center'>data availible in table</td></tr>");
				}
			}else{
				$("#ShowDataApprovel").html("<tr><td colspan='4' class='text-center'>"+result['pesan']+"</td></tr>");
			}
			StopLoad();

		},
		error: function (er) {
			console.log(er);
		}
	});
}

function HapusListApproval(Id){
	$.ajax({
		type: "POST",
		url: "inc/Menu/proses.php?proses=HapusListApproval",
		data: "Id=" + Id,
		beforeSend: function () {
			StartLoad();
		},
		success: function (result) {
			console.log(result);
			var res = JSON.parse(result);
			if (res['status'] == '0') {
				var Id = $("#IdMenu").val();
				CelarListApproval();
				Customsukses("Menu", '001', res['pesan'], 'ProsesList');
				LoadListApprovel(Id);
				StopLoad();
			} else {
				Customerror("Menu", "001", res['pesan'], 'ProsesList');
				Clear();
				StopLoad();
			}

		},
		error: function (er) {
			console.log(er);
		}
	});
}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ["NamaMenu", "Direktori",'Icon'];
	var KetiForm = ["Nama Menu", "Direktori", "Icon"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete") {
			if ($("#" + iForm[i]).val() == "") { error("Menu", KodeError + i, KetiForm[i] + " Belum Lengkap!"); $("#" + iForm[i]).focus(); scrolltop(); return false; }
		}
	}
}

$("#FormData").submit(function(e){
	e.preventDefault();
	SubmitData();
	
})


function CelarListApproval(){
	$("#IdUser").val("");
	$("#NamaUser").val("");
	$("#NamaUser").val("");
}

function SubmitData(){
	var aksi = $("#aksi").val();
	if (Validasi() != false) {
		var data = $("#FormData").serialize();
		$.ajax({
			type: "POST",
			url: "inc/Menu/proses.php?proses=Crud",
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				var Table = $("#TableData").DataTable();
				if (res['status'] == '0') {
					Clear();
					Customsukses("Menu", '001', res['pesan'],'proses');
					Table.ajax.reload();
					StopLoad();
				}else{
					Customerror("Menu", "001", res['pesan'], 'proses');
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