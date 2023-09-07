
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
        <div class='pull-right box-tools'>
            <div class='btn-group' id='BtnControl'>
                <button onclick='Crud()' class='btn btn-sm btn-primary' title='Tambah Data' data-toggle='tooltip'><i class='fa fa-plus'></i> Tambah</button>
                <button class='btn btn-sm btn-warning btn-flat' onclick="location.reload();" title='Reload' data-toggle='tooltip'><i class='fa fa-refresh'></i></button>
            </div>
        </div>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="Id" id="Id" value="">
            <div class='row'>
                <div class='col-sm-3 col-md-4'>
                    <small>Catatan:
                        <ul>
                            <li><span class='text-danger'>*)</span> Wajib diisi!</li>
                        </ul>
                    </small>
                </div>
                <div class='col-sm-9 col-md-8'>
                    <div class="form-group"><div class='col-sm-12'><span id='ProsesCrud'></span></div></div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Tenaga Kerja<span class='text-danger'>*</span></label>
                            <select class='form-control select-no-ktp' name='NoKtp' id='NoKtp'></select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">No KPJ<span class='text-danger'>*</span></label>
                            <input type='text' onkeyup='angka(this)' class='form-control FormInput' name='NoKpj' id='NoKpj' placeholder='No KPJ'>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Tanggal Daftar</label>
                            <div class='input-group'>
                                <input type='text' autocomplete=off class='form-control Tahun FormInput' name='TglDaftar' id='TglDaftar' placeholder='Tanggal Daftar' />
                                <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Flag <span class='text-danger'>*</span></label></label>
                            <div class='input-group'>
                                <span class='input-group-addon'><input type='radio' name='Flag' value='1' id='Flag1' checked  /></span>
                                <input type='text' readonly class='form-control' value='Aktif'  />
                                <span class='input-group-addon'><input type='radio' name='Flag' id='Flag0' value='0'  /></span>
                                <input type='text' readonly class='form-control' value='Tidak Aktif'  />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Kartu</label>
                            <input type='file' name='File' accept='.jpg,.png,.jpeg,.pdf' id='File' data-toggle='tooltip' title='Masukkan dokumen kartu BPJS KESEHATAN berupa file gambar atau pdf' class='form-control FormInput' placeholder='File'  />
                        </div>
                        
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class='btn-group'>
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                                <button type="button" onclick="Clear()" class="btn btn-sm btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
           
        </form>

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
            <div class="col-sm-3 col-sm-offset-8">
                <div class='input-group'>
                    <input type='text' id='Search' onkeyup='LoadData()' data-toggle='tooltip' title='Masukkan Nama / No KPJ' class='form-control' placeholder='Cari Nama / No KPJ...'> 
                    <span class='input-group-addon'><i class='fa fa-search'></i></span>
                </div>
            </div>
            <div class="col-sm-12" style='margin-top:10px'>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Tenaga Kerja</th>
                            <th>No KPJ</th>
                            <th>Tanggal Daftar</th>
                            <th width='10%'>Flag</th>
                            <th width="8%"><center>Aksi</center></th>
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
    <h5 class="modal-title">Konfirmasi Delete</h5>
</div>
<div class='modal-body'>

    <div id="proses_del"></div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="SubmitData()"><i class="fa fa-check-square"></i> &nbsp;Hapus</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="Clear()"><i class="fa fa-mail-reply"></i> &nbsp;Batal</button>
    </div>

</div>
</div>
</div>
</div>