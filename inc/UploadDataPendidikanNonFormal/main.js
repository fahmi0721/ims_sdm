$(document).ready(function(){
    Clear();
	
});


function Clear(){
	$("#Title").html("Upload Data Pendidikan Non Formal");
	$("#close_modal").trigger('click');
	$("#FormData").show();
	$("#aksi").val("");
	$(".FormInput").val("");
	
}

function Validasi(){
	if ($("#File").val() == "") { Customerror("Data Pendidikan Non", "007", "File belum dipilih", 'ProsesCrud'); $("#File").focus(); scrolltop(); return false; }
	
}

$("#FormData").submit(function(e){
	e.preventDefault();
	SubmitData();
	
})

function SubmitData(){
	if (Validasi() != false) {
		var data = new FormData($("#FormData")[0]);
		$.ajax({
			type: "POST",
			url: "inc/UploadDataPendidikanNonFormal/proses.php?proses=Upload",
			processData: false,
			contentType : false,
			chace : false,
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				console.log(res);
				if (res['status'] == 'sukses') {
					Clear();
					Customsukses("Data Pendidkan Non Formal", '007', res['pesan'],'proses');
					
					StopLoad();
				}else{
					Customerror("Data Pendidkan Non Formal", "007", res['pesan'], 'proses');
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