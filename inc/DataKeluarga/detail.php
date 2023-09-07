
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
                            <select class='form-control select-no-ktp' name='NoKtp' id='NoKtp'></select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Nama <span class='text-danger'>*</span></label>
                            <input type='text' autocomplete=off class='form-control FormInput' name='Nama' id='Nama' placeholder='Nama' />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Status Keluarga <span class='text-danger'>*</span></label>
                            <select class='form-control select-keluarga' name='StatusKeluarga' id='StatusKeluarga'>
                                <option value=''>Pilih Status Keluarga</option>
                                <option value='0'>Suami</option>
                                <option value='1'>Istri</option>
                                <option value='2'>Anak</option>
                                <option value='3'>Ayah</option>
                                <option value='4'>Ibu</option>
                            </select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Pendidikan Terkahir</label>
                            <select class='form-control select-pendidikan' name='KodeMaster' id='KodeMaster'></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Pekerjaan</label>
                            <input type='text' class='form-control FormInput' name='Pekerjaan' id='Pekerjaan' placeholder='Pekerjaan'>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">No HP </label>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='fa fa-phone'></i></span>
                                <input type='text' class='form-control FormInput' onkeyup='angka(this)' name='NoHp' id='NoHp' placeholder='No HP'>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class='col-sm-12'>
                            <label class="control-label">Alamat </label>
                            <textarea  class='form-control FormInput' rows='5' name='Alamat' id='Alamat' placeholder='Alamat'></textarea>
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
                    <input type='text' id='Search' onkeyup='LoadData()' data-toggle='tooltip' title='Masukkan Nama / Kode Bank' class='form-control' placeholder='Cari Nama / Kode Bank...'> 
                    <span class='input-group-addon'><i class='fa fa-search'></i></span>
                </div>
            </div>
            <div class="col-sm-12" style='margin-top:10px'>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama</th>
                            <th>Status Keluarga</th>
                            <th>Pendidikan Terakhir</th>
                            <th>Pekerjaan</th>
                            <th>No Hp</th>
                            <th>Alamat</th>
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