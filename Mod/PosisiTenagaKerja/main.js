$(document).ready(function(){
	LoadData();
});

function LoadData(){
	$.ajax({
		type : "POST",
		url : "Mod/PosisiTenagaKerja/proses.php?proses=LoadData",
		data :"rule=LoadData",
		success: function(res){
			console.log(r);
			var r = JSON.parse(res);
			console.log(r);
			$("#TenagaKerjaBaru").html(r['TenagaKerjaBaru']+" Orang");
			$("#TenagaKerjaKeluar").html(r['TenagaKerjaKeluar'] + " Orang");
			$("#PosisiTenagaKerja").html(r['PosisiTenagaKerja'] + " Orang");
		},
		error : function(er){
			console.log(er);
		}
	})
}