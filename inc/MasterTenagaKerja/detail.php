
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
        <div class='pull-right box-tools'>
            <div class='btn-group' id='BtnControl'>
                <button onclick='Crud()' class='btn btn-sm btn-primary' title='Tambah Data' data-toggle='tooltip'><i class='fa fa-plus'></i> Tambah</button>
                <a href='index.php?page=GenerateNrp' class='btn btn-sm btn-success' title='Generate NRP' data-toggle='tooltip'><i class='fa fa-spinner'></i> Generate NRP</a>
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
                            <label class="control-label">No KTP<span class='text-danger'>*</span></label>
                            <input type='text' autocomplete=off onkeyup="angka(this)" class='form-control FormInput' name='NoKtp' id='NoKtp' placeholder='No KTP' />
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Nama<span class='text-danger'>*</span></label>
                            <input type='text' autocomplete=off class='form-control FormInput' name='Nama' id='Nama' placeholder='Nama' />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Tempat Lahir<span class='text-danger'>*</span></label>
                            <input type='text' autocomplete=off class='form-control FormInput' name='TptLahir' id='TptLahir' placeholder='Tempat Lahir' />
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Tanggal Lahir<span class='text-danger'>*</span></label>
                            <div class='input-group'>
                                <input type='text' autocomplete=off class='form-control FormInput' name='TglLahir' id='TglLahir' placeholder='Tanggal Lahir' />
                                <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Status Kawin<span class='text-danger'>*</span></label>
                            <select class='form-control select-status FormInput' name='StatusKawin' id='StatusKawin'>
                                <option value=''>..:: Pilih Status Kwain ::..</option>
                                <option value='1'>Belum Menikah</option>
                                <option value='2'>Menikah</option>
                                <option value='3'>Janda/Duda</option>
                            </select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Jenis Kelamin <span class='text-danger'>*</span></label></label>
                            <div class='input-group'>
                                <span class='input-group-addon'><input type='radio' name='JenisKelamin' value='L' id='JenisKelaminL' checked  /></span>
                                <input type='text' readonly class='form-control' value='Laki-Laki'  />
                                <span class='input-group-addon'><input type='radio' name='JenisKelamin' id='JenisKelaminP' value='P'  /></span>
                                <input type='text' readonly class='form-control' value='Perempuan'  />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Agama<span class='text-danger'>*</span></label>
                            <select class='form-control select-agama FormInput' name='Agama' id='Agama' ></select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">NPWP</label>
                            <input type='text' autocomplete=off class='form-control FormInput' name='Npwp' id='Npwp' placeholder='NPWP' />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Golongan Darah</label>
                            <div class='input-group'>
                                <input type='text' name='GolDarah' id='GolDarah' class='form-control FormInput' placeholder='Golongan Darah'  />
                                <span class='input-group-addon'>-</span>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">No HP<span class='text-danger'>*</span></label>
                            <div class='input-group'>
                                <input type='text' name='NoHp' onkeyup="angka(this)" id='NoHp' class='form-control FormInput' placeholder='No HP'  />
                                <span class='input-group-addon'><i class='fa fa-phone'></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Flag <span class='text-danger'>*</span></label></label>
                            <div class='input-group'>
                                <span class='input-group-addon'><input type='radio' name='Flag' value='1' id='Flag1' checked  /></span>
                                <input type='text' readonly class='form-control' value='Aktif'  />
                                <span class='input-group-addon'><input type='radio' name='Flag' id='Flag0' value='0'  /></span>
                                <input type='text' readonly class='form-control' value='Tidak Aktif'  />
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">TMT<span class='text-danger'>*</span></label>
                            <div class='input-group'>
                                <input type='text' autocomplete=off class='form-control FormInput' name='Tmt' id='Tmt' placeholder='TMT' />
                                <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Foto</label>
                            <input type='file' name='Foto' accept='.jpg,.png,.jpeg' id='Foto' class='form-control FormInput' placeholder='Foto'  />
                        </div>
                         <div class='col-sm-6'>
                            <label class="control-label">KTP</label>
                            <input type='file' name='Ktp' accept='.jpg,.png,.jpeg' id='Ktp' class='form-control FormInput' placeholder='Ktp'  />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-12'>
                            <label class="control-label">Alamat</label>
                            <textarea type='text' name='Alamat' id='Alamat' rows='5' class='form-control FormInput' placeholder='Alamat'></textarea>
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
                    <input type='text' id='Search' onkeyup='LoadData()' data-toggle='tooltip' title='Masukkan Nama / No KTP' class='form-control' placeholder='Cari Nama / No KTP...'> 
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
                            <th>Jenis Kelamin</th>
                            <th>TTL</th>
                            <th>Pendidikan</th>
                            <th>Agama</th>
                            <th>TMT</th>
                            <th>Rekening</th>
                            <th>No Hp</th>
                            <th>Jabatan</th>
                            <th>Alamat</th>
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