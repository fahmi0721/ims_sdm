
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
        <div class='pull-right box-tools'>
            <?php if(in_array($_SESSION['page_level'],$_SESSION['p-a'])){ ?>
            <div class='btn-group' id='BtnControl'>
                <button onclick='Crud()' class='btn btn-sm btn-primary btn-flat' title='Tambah Data' data-toggle='tooltip'><i class='fa fa-plus'></i> Tambah</button>
                <!--<button class='btn btn-sm btn-success btn-flat' onclick="Crud('0','FormFilter')" title='Filter' data-toggle='tooltip'><i class='fa fa-filter'></i> Filter</button>-->
                <button class='btn btn-sm btn-warning btn-flat' onclick="location.reload();" title='Reload' data-toggle='tooltip'><i class='fa fa-refresh'></i></button>
            </div>
            <?php } ?>
        </div>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="Id" id="Id" value="">
            <input type="hidden" name="IdTenagaKerja" id="IdTenagaKerja" value="">
            <input type="hidden" name="IdCabangLama" id="IdCabangLama" value="">
            <input type="hidden" name="IdCabangBaru" id="IdCabangBaru" value="">
            <div class='col-sm-4 col-md-4'>
                <p>
                    <i>Catatan</i>
                    <ul>
                        <li><span class='text-danger'>*) </span>Wajib Diisi</li>
                        <li>Pada Kolom No KTP boleh memasukkan Nama Tenaga Kerja</li>
                        <li>Memilih Unit Kerja secara otomatis menampilkan Jabatan pada Unit Kerja trsebut</li>
                        <li>Pilih File SK yang telah di scan (*.pdf)</li>
                        <li>Pastikan mengisi data yang benar.</li>
                    </ul>
                </p>
            </div>
            <div class='col-sm-8 col-md-8'>
               <div class='form-group'><div class="col-sm-12"><div id="Proses"></div></div></div>
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>No KTP<span class='text-danger'>*</span></label>
                        <input type="text" data-toggle='tooltip' title='Press Enter To Select' id="NoKtp" class='form-control FormInput' placeholder='Masukkan No KTP / Nama'>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Nama Tenga Kerja<span class='text-danger'>*</span></label>
                        <input type="text" readonly id="Nama" name='Nama' class='form-control FormInputCustom' placeholder='Nama Tenaga Kerja'>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Tempat Tanggal Lahir<span class='text-danger'>*</span></label>
                        <input type="text" id="TTL" readonly class='form-control FormInputCustom' placeholder='Tempat Tanggal Lahir'>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Pendidikan<span class='text-danger'>*</span></label>
                        <input type="text" readonly id="Pendidikan" class='form-control FormInputCustom' placeholder='Pendidkkan'>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Unit Kerja Lama<span class='text-danger'>*</span></label>
                        <input type="text" readonly id="UnitKerjaLama" class='form-control FormInputCustom' placeholder='Unit Kerja Lama'>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Unit Kerja Baru<span class='text-danger'>*</span></label>
                        <input type="text" data-toggle='tooltip' title='Press Enter To Select' id="UnitKerjaBaru" class='form-control FormInput' placeholder='Unit Kerja Baru'>
                    </div>
                    
                </div>
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Jabatan Lama<span class='text-danger'>*</span></label>
                        <input type="text" readonly id="JabatanLama" name='JabatanLama' class='form-control FormInputCustom' placeholder='Jabatan Lama'>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Jabatan Baru<span class='text-danger'>*</span></label>
                        <input type="text"  id="JabatanBaru" name='JabatanBaru' class='form-control FormInputCustom' placeholder='Jabatan Baru'>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>TMT Berakhir Jabatan Lama<span class='text-danger'>*</span></label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                            <input type="text"   id="TMTLama" name='TMTLama' class='form-control' placeholder='TMT Berakhir'>
                        </div>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>TMT Mulai Jabatan Baru<span class='text-danger'>*</span></label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                            <input type="text"   id="TMTBaru" name='TMTBaru' class='form-control' placeholder='TMT Mulai'>
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-12 col-md-12'>
                        <label class='control-label'>SK<span class='text-danger'>*</span></label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='fa fa-file'></i></span>
                            <input type="file" accept='.pdf' id="Sk" name='Sk' class='form-control FormInput' >
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-12 col-md-12'>
                        <div class='btn-group'>
                            <button type='submit' class='btn btn-sm btn-primary'><i class='fa fa-check-square'></i> Submit</button>
                            <button type='button' onclick='Clear()' class='btn btn-sm btn-danger'><i class='fa fa-mail-reply'></i> Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="FormUpdate" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi_update" value="update">
            <input type="hidden" name="Id" id="IdUpdate" value="">
            <input type="hidden" name="IdCabang" id="IdCabang" value="">
            <div class='col-sm-4 col-md-4'>
                <p>
                    <i>Catatan</i>
                    <ul>
                        <li><span class='text-danger'>*) </span>Wajib Diisi</li>
                        <li>Memilih Unit Kerja secara otomatis menampilkan Jabatan pada Unit Kerja trsebut</li>
                        <li>Pilih File SK yang telah di scan (*.pdf)</li>
                        <li>Pastikan mengisi data yang benar.</li>
                    </ul>
                </p>
            </div>
            <div class='col-sm-8 col-md-8'>
               <div class='form-group'><div class="col-sm-12"><div id="Proses"></div></div></div>
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>No KTP<span class='text-danger'>*</span></label>
                        <input type="text" readonly id="NoKtpUpdate" class='form-control FormInputCustom' placeholder='Masukkan No KTP / Nama'>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Nama Tenga Kerja<span class='text-danger'>*</span></label>
                        <input type="text" readonly id="NamaUpdate" name='Nama' class='form-control FormInputCustom' placeholder='Nama Tenaga Kerja'>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Tempat Tanggal Lahir<span class='text-danger'>*</span></label>
                        <input type="text" id="TTLUpdate" readonly class='form-control FormInputCustom' placeholder='Tempat Tanggal Lahir'>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Pendidikan<span class='text-danger'>*</span></label>
                        <input type="text" readonly id="PendidikanUpdate" class='form-control FormInputCustom' placeholder='Pendidkkan'>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Unit Kerja Baru<span class='text-danger'>*</span></label>
                        <input type="text" data-toggle='tooltip' title='Press Enter To Select' id="UnitKerja" class='form-control FormInput' placeholder='Unit Kerja'>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>Jabatan<span class='text-danger'>*</span></label>
                        <input type="text"  id="Jabatan" name='Jabatan' class='form-control FormInput' placeholder='Jabatan'>
                    </div>
                    
                </div>
                
                <div class='form-group'>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>TMT Mulai<span class='text-danger'>*</span></label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                            <input type="text"   id="TanggalMulai" name='TanggalMulai' class='form-control FormInput' placeholder='TMT Mulai'>
                        </div>
                    </div>
                    <div class='col-sm-6 col-md-6'>
                        <label class='control-label'>TMT Berakhir</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                            <input type="text"   id="TanggalSelesai" name='TanggalSelesai' class='form-control FormInput' placeholder='TMT Beakhir'>
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-12 col-md-12'>
                        <label class='control-label'>SK</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='fa fa-file'></i></span>
                            <input type="file" accept='.pdf'  name='Sk' class='form-control FormInput' >
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-12 col-md-12'>
                        <div class='btn-group'>
                            <button type='submit' class='btn btn-sm btn-primary'><i class='fa fa-check-square'></i> Submit</button>
                            <button type='button' onclick='Clear()' class='btn btn-sm btn-danger'><i class='fa fa-mail-reply'></i> Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="DetailData">
            <div class='col-sm-1'>
                <select id='ShowTampil' onchenge='LoadData()' class='form-control'>
                    <option value='10'>10</option>
                    <option value='25'>25</option>
                    <option value='50'>50</option>
                    <option value='75'>75</option>
                    <option value='100'>100</option>
                </select>
            </div>
            <div class='col-sm-3 col-sm-offset-8' style='margin-bottom:10px'> 
                <div class='input-group'>
                    <input type='text' id='Search' onkeyup='LoadData()' class='form-control' placeholder='Masukkan Nama Tenaga Kerja'> 
                    <span class='input-group-addon'><i class='fa fa-search'></i></span>
                </div>
            </div>
            <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Unit Kerja</th>
                            <th>Mulai</th>
                            <th>Sampai</th>
                            <th width='5%'>SK</th>
                            <th width="8%"><center>Aksi</center></th>
                        </tr>
                    </thead>
                    <tbody id='ShowData'></tbody>
                </table>
            </div>
            </div>
            <div class='col-sm-12'>
                <span class='pull-left' id='Showing'></span>
                <span class='pull-right' id='Paging'></span>
                <span class='clearfix'></span>
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