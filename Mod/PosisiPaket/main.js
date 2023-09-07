$(document).ready(function(){
	LoadDataPaket();
});

function LoadDataPaket(){
	$.ajax({
		type : "POST",
		url: "Mod/PosisiPaket/proses.php?proses=LoadData",
		data :"rule=LoadData",
		success: function(res){
			console.log(r);
			var r = JSON.parse(res);
			console.log(r);
			$("#TkPkt").html(r['TkPkt']+" Orang");
			$("#TkSc").html(r['TkSc'] + " Orang");
			$("#TkCs").html(r['TkCs'] + " Orang");
		},
		error : function(er){
			console.log(er);
		}
	})
}