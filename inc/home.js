$(document).ready(function () {
    //LoadMap();
   // LoadLogs();
});



function LoadLogs(page){
    page = page == undefined ? 1 : page;
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "inc/proses.php?proses=GetLoadLogs",
        chace : false,
        data : "page="+page,
        success: function(res){
            console.log(res);
            if(res['status'] == 0){
                var html = "";
                for(var i=0; i < res['item'].length; i++){
                    var data = res['item'][i];
                    html += "<tr>";
                    html += "<td>" + data['Uraian'] + "</td>";
                    html += "<td><label class='text-info'>" + data['Time'] + "</label></td>";
                    html += "</tr>";
                }
                $("#LoadLogs").html(html);
                pagination(page, res['total_page']);
            }else{
                $("#LoadLogs").html("<tr><td colspan='3'>Tidak ada aktifitas hari ini.</td></tr>");
            }
        },
        error : function(er){
            console.log(er);
        }
    });
}

function pagination(page_num, total_page) {
    page_num = parseInt(page_num);
    total_page = parseInt(total_page);
    var paging = "<ul class='pagination btn-xs'>";
    if (page_num > 1) {
        var prev = page_num - 1;
        paging += "<li><a href='javascript:void(0);' onclick='LoadLogs(" + prev + ")'>Prev</a></li>";
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
                paging += "<li class='javascript:void(0)'><a onclick='LoadLogs(" + page + ")'>" + aktif + "</a></li>";
            }
            show_page = page;
        }
    }

    if (page_num < total_page) {
        var next = page_num + 1;
        paging += "<li><a href='javascript:void(0)' onclick='LoadLogs(" + next + ")'>Next</a></li>";
    } else {
        paging += "<li class='disabled'><a>Next</a></li>";
    }
    $("#PagingLogs").html(paging);
}