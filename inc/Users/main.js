$(document).ready(function(){
    Clear();
   	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax": "inc/Users/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data": "Nama" },
			{ "data" : "Jabatan" },
			{ "data": "Level" },
			{ "data": "Username" },
			{ "data": "Status" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});


}


function UpdateStatus(Id,Status){
	$.ajax({
		type: "POST",
		url: "inc/Users/proses.php?proses=Crud",
		data: "aksi=UpdateStatus&Id="+Id+"&Status="+Status,
		beforeSend: function () {
			StartLoad();
		},
		success: function (result) {
			console.log(result);
			var res = JSON.parse(result);
			var Table = $("#TableData").DataTable();
			if (res['status'] == 0) {
				Clear();
				Customsukses("User", '002', res['pesan'], 'proses');
				Table.ajax.reload();
				StopLoad();
			} else {
				Customerror("User", "002", res['pesan'], 'proses');
				Clear();
				StopLoad();
			}
		},
		error: function (er) {
			console.log(er);
		}
	});
}



function Clear(){
	$("#Title").html("Tampil Data Users");
	$("#close_modal").trigger('click');
	
	$("#FormData").hide();
	$("#DetailData").show();
	$("#proses").html("");
	$("#aksi").val("");
	$(".FormInput").val("");
	$(".FormInput").prop("readonly", false);
	$(".FormInput").prop("disabled", false);
	
	
}

function Crud(Id,Status){
	Clear();
	$("#proses").html("");
	if(Id){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/Users/proses.php?proses=ShowData",
				data : "Id="+Id,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					$("#Title").html("Ubah Data Users");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");
					var iForm = ['Id','Nama','Jabatan','Level','Username'];
					for(var i=0; i < iForm.length; i++){
						$("#" + iForm[i]).val(data[iForm[i]]);
					}
					$("#Username").prop("readonly", true);
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
		$("#Title").html("Tambah Data Users");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#proses").html("");
		$("#CostCenter").focus();
		$("#aksi").val("insert");

	}

}

function Validasi(){
	var aksi = $("#aksi").val();
	var iForm = ["Nama", "Jabatan",'Username','Level'];
	var KetiForm = ["Nama Lengkap", "Jabatan", "Username", "Level"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if (aksi != "delete") {
			if ($("#" + iForm[i]).val() == "") { error("Users", KodeError + i, KetiForm[i] + " Belum Lengkap!"); $("#" + iForm[i]).focus(); scrolltop(); return false; }
		}
	}
	if(aksi != "delete" && aksi != "update"){
		if ($("#Password").val() == "") { error("Users", 5, "Password Belum Lengkap!"); $("#Password").focus(); scrolltop(); return false; }
	}
}

$("#FormData").submit(function(e){
	e.preventDefault();
	SubmitData();
})

function SubmitData(){
	if (Validasi() != false){
		var data = $("#FormData").serialize();
		$.ajax({
			type : "POST",
			url : "inc/Users/proses.php?proses=Crud",
			data : data,
			beforeSend: function() {
				StartLoad();
			},
			success: function(result){
				var res = JSON.parse(result);
				var Table = $("#TableData").DataTable();
				if (res['status'] == 0){
					Clear();
					Customsukses("User", '002', res['pesan'], 'proses');
					Table.ajax.reload();
					StopLoad();
				} else if (res['status'] == 2){
					Customerror("User", "002", res['pesan'], 'proses');
					$("#modal").modal("hide");
					StopLoad();
				}else{
					Customerror("User", "002", res['pesan'], 'proses');
					Clear();
					StopLoad();
				}
			},
			error : function(er){
				console.log(er);
			}
		});
	}
	

}