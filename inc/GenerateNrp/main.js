$(document).ready(function(){
    Clear();
	$("[data-toggle='tolltip']").tooltip();
	$('.periode').datepicker({
		changeMonth: true,
        changeYear: true,
		showButtonPanel: true,
		format: "M yyyy",
		currentText: "This Month",
		startView: "months", 
		minViewMode: "months",
		autoclose: true,
	  });
	
});


function Clear(){
	$("#Title").html("Generate NRP");
	$(".FormInput").val("");
	$("#btn-geneate").show();
	$("#btn-submit").hide();
	$("#DataBerhasil").html("");
	$("#Periode").prop("disabled",false);
	// ClearModal();
	
}

function Validasi(){
	var iForm = ['Periode'];
	var iKet = ["TMT belum dipilih. Silahkan pilih periode"];
	for(var i=0; i < iForm.length; i++){
		if($("#"+iForm[i]).val() == ""){
			if ($("#" + iForm[i]).val() == "") { Customerror("Generate NRP", "002", iKet[i], 'proses'); $("#" + iForm[i]).focus(); scrolltop(); return false; }
		}
	}
}

$("#FormData").submit(function(e){
	e.preventDefault();
	SubmitData();
	
})

function berhasil_data(data){
	var No = 1;
	var Id = $("#DataBerhasil");
	html = "<h4>Daftar NRP Baru</h4>";
	html += "<hr>";
	html += "<table class='table table-striped table-bordered'>";
	html += "<thead>";
	html += "<tr>";
	html += "<th class='text-center'>No</td>";
	html += "<th class='text-center'>No KTP</td>";
	html += "<th class='text-center'>Nama</td>";
	html += "<th class='text-center'>Tgl Lahir</td>";
	html += "<th class='text-center'>NRP</td>";
	html += "</tr>";
	html += "</thead>";
	html += "<tbody>";
	if(data.length <= 0){
		html += "<tr>";
		html += "<td colspan='11' class='text-center'>Data not availible in table</td>";
		html += "</tr>";
	}else{
		for(var i=0; i < data.length; i++){
			html += "<tr>";
			html += "<td>"+No+"</td>";
			html += "<td>"+data[i]['NoKtp']+"</td>";
			html += "<td>"+data[i]['Nama']+"</td>";
			html += "<td>"+data[i]['TglLahir']+"</td>";
			html += "<td>"+data[i]['Nrp']+"</td>";
			html += "</tr>";
			html += "<input type='hidden' name='NoKtp[]' value='"+data[i]['NoKtp']+"'>";
			html += "<input type='hidden' name='Nik[]' value='"+data[i]['Nrp']+"'>";

			No++;
		}
	}
	html += "</tbody>";
	html += "</table>";

	Id.html(html);
}

function GenerateData(){
	if (Validasi() != false) {
		var data = new FormData($("#FormData")[0]);
		$.ajax({
			type: "POST",
			url: "inc/GenerateNrp/proses.php?proses=Generate",
			processData: false,
			contentType: false,
			chace: false,
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				var res = JSON.parse(result);
				console.log(res);
				if (res['status'] == 'sukses') {
					berhasil_data(res['data']);
					if(res['data'].length > 0){
						Customsukses("SK NRP", '007', res['pesan']+", klik tombol <button class='btn btn-xs btn-primary' data-toggle='tooltip' title='bukan tombol ini, tapi yang dibawah samping tombol merah'><i class='fa fa-check-square'></i> Submit</button> untuk menyimpan",'proses');
						$("#btn-geneate").hide();
						$("#btn-submit").show();
						$("#Periode").prop("disabled",true);
					}else{
						Customsukses("SK NRP", '007',"Data tenaga kerja pada TMT ini telah digenerate",'proses');
						$("#btn-geneate").show();
						$("#btn-submit").hide();
						$("#Periode").prop("disabled",false);
					}
					$("[data-toggle='tooltip']").tooltip();
					StopLoad();
				}else{
					Customerror("SK NRP", "007", res['pesan'], 'ProsesCrud');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
				StopLoad();
			}
		});
	}
}

function SubmitData(){
	if (Validasi() != false) {
		var data = new FormData($("#FormData")[0]);
		$.ajax({
			type: "POST",
			url: "inc/GenerateNrp/proses.php?proses=UpdateNrp",
			processData: false,
			contentType: false,
			chace: false,
			data: data,
			beforeSend: function () {
				StartLoad();
			},
			success: function (result) {
				Clear();
				var res = JSON.parse(result);
				console.log(res);
				if (res['status'] == 'sukses') {
					Customsukses("SK NRP", '007', res['pesan'],'proses');
					StopLoad();
				}else{
					Customerror("SK NRP", "007", res['pesan'], 'ProsesCrud');
					StopLoad();
				}
			},
			error: function (er) {
				console.log(er);
				StopLoad();
			}
		});
	}
}