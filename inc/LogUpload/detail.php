
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>

        <div id="DetailData">
            <div class="col-sm-1">
                <select class='form-control' id='RowPage' onchange='LoadData()'>
                    <option value='10'>10</option>
                    <option value='25'>25</option>
                    <option value='50'>50</option>
                    <option value='75'>75</option>
                    <option value='100'>100</option>
                </select>
            </div>
            <div class="col-sm-1 col-sm-offset-4">
                <button onclick='LoadData()' class='btn btn-success'><i class='fa fa-search'></i> Cari</button>
            </div>
            <div class="col-sm-3">
                <div class='input-group'>
                    <input type='text' name='Tgl' id='Tgl' class='form-control' placeholder='Tanggal' />
                    <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class='input-group'>
                    <select class='form-control FormInput select-user' name='IdUser' id='IdUser'></select>
                    <span class='input-group-addon'><i class='fa fa-user'></i></span>
                </div>
            </div>
            <div class="col-sm-12" style='margin-top:10px'>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Kode Log</th>
                            <th>User</th>
                            <th>Modul</th>
                            <th>Waktu</th>
                            <th class='text-center'>Status</th>
                            <th class='text-center'>#</th>
                        </tr>
                    </thead>
                    <tbody id='ShowData'></tbody>
                </table>
            </div>
            <div>
                <span class='pull-left' id='PagingInfo'></span>
                <span class='pull-right' id='Paging'></span>
                <span class='clearfix'></span>
            </div>
            </div>
            
        </div> 

    </div>
    

    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>

<div class='modal fade in' id='modal' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Detail Log</h5>
</div>
<div class='modal-body'>
    <div class='table-responsive'>
        <table class='table table-striped'>
            <thead>
                <tr>
                    <th class='text-center'>No</th>
                    <th>Pesan</th>
                </tr>
            </thead>
            <tbody id='DetailLogs'></tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>