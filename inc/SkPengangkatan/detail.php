
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
                            <label class="control-label">Tenaga Kerja <span class='text-danger'>*</span></label>
                            <select class='form-control FormInput select-no-ktp' name='NoKtp' id='NoKtp'></select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Nomor Dokumen <span class='text-danger'>*</span></label>
                            <input type='text' autocomplete=off class='form-control FormInput' name='NoDokumen' id='NoDokumen' placeholder='NoDokumen' />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Branch <span class='text-danger'>*</span></label>
                            <select class='form-control FormInput select-branch' name='KodeBranch' id='KodeBranch'></select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Unit Kerja <span class='text-danger'>*</span></label>
                            <select class='form-control FormInput select-unit-kerja' name='KodeCabang' id='KodeCabang'></select>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Divisi <span class='text-danger'>*</span></label>
                            <select class='form-control FormInput select-divisi' name='KodeDivisi' id='KodeDivisi'></select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Sub Divisi <span class='text-danger'>*</span></label>
                            <select class='form-control FormInput select-sub-divisi' name='KodeSubDivisi' id='KodeSubDivisi'></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Seksi <span class='text-danger'>*</span></label>
                            <select class='form-control FormInput select-seksi' name='KodeSeksi' id='KodeSeksi'></select>
                        </div>

                        <div class='col-sm-6'>
                            <label class="control-label">File </label>
                            <div class='input-group'>
                                <input type='file'  class='form-control FormInput' name='File' id='File' accept='image/*, .pdf'  />
                                <span class='input-group-addon'><i class='fa fa-file-o'></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">TMT <span class='text-danger'>*</span></label>
                            <div class='input-group'>
                                <input type='text' class='form-control FormInput' name='TanggalMulai' id="TanggalMulai" placeholder="TMT" />
                                <span class='input-group-addon'><i class='fa fa-calendar-o'></i></span>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Tanggal Selesai</label>
                            <div class='input-group'>
                                <input type='text' class='form-control FormInput' name='TanggalSelesai' id="TanggalSelesai" placeholder="Tanggal Selesai" />
                                <span class='input-group-addon'><i class='fa fa-calendar-o'></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-12'>
                            <label class="control-label">Keterangan </label>
                            <textarea class='form-control FormInput' rows='4' name='Keterangan' id='Keterangan' placeholder='Keterangan'></textarea>
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
            <!-- <div class="col-sm-1">
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
                    <input type='text' id='Search' onkeyup='LoadData()' data-toggle='tooltip' title='Masukkan Nama Tenaga Kerja / Nomor Dokumen' class='form-control' placeholder='Cari Nama Tenaga Kerja / Nomor Dokumen...'> 
                    <span class='input-group-addon'><i class='fa fa-search'></i></span>
                </div>
            </div> -->
            <form id="FormDataSearch" class="form-horizontal" action="#">
                
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
                                <label class="control-label">Tenaga Kerja <span class='text-danger'>*</span></label>
                                <select class='form-control FormInput select-no-ktp' name='NoKtp' id='NoKtpSearch'></select>
                            </div>
                            <div class='col-sm-6'>
                            <label class="control-label">TMT <span class='text-danger'>*</span></label>
                            <div class='input-group'>
                                <input type='text' class='form-control FormInput' autocomplete=off name='TanggalMulai' id="TanggalMulaiSearch" placeholder="TMT" />
                                <span class='input-group-addon'><i class='fa fa-calendar-o'></i></span>
                            </div>
                        </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class='btn-group'>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            
            </form>
            <div class="col-sm-12" id='JumData'></div>
            <div class="col-sm-12" style='margin-top:10px'>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Tenaga Kerja</th>
                            <th>Unit Kerja</th>
                            <th>No Dokumen</th>
                            <th>TMT</th>
                            <th>Keterangan</th>
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