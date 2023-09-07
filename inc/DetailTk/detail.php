 <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" id='Foto'  alt="User profile picture">

              <h3 class="profile-username text-center" id='Namas'></h3>

              <p class="text-muted text-center" id='Jabatans'></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Jumlah Penempatan</b> <a class="pull-right" id='JumPen'>0</a>
                </li>
                <li class="list-group-item">
                  <b>Jumlah Sertifikasi</b> <a class="pull-right" id='JumSer'>0</a>
                </li>
              </ul>
              <a class='btn btn-sm bg-purple btn-block' target="_blank" id="cv_download"><i class='fa fa-download'></i> DOWNLOAD CV</a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Tentang Tenaga Kerja</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Pendidikan Terkahir</strong>

              <p class="text-muted" id='Pendidikans'></p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>

              <p class="text-muted" id='Alamats'></p>

              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Sertifikasi</strong>

              <p id="SertifikasiS"></p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#data-diri" data-toggle="tab">Data Diri</a></li>
              <li><a href="#pendidikan-formal" data-toggle="tab">Pendidikan Formal</a></li>
              <li><a href="#pendidikan-non-formal" data-toggle="tab">Pendidikan Non Formal</a></li>
              <li><a href="#data-keluarga" data-toggle="tab">Data Keluarga</a></li>
              <li><a href="#riwayat-jabatan" data-toggle="tab">Riwayat Jabatan</a></li>
              <li><a href="#daftar-rekening" data-toggle="tab">Daftar Rekening</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="data-diri">
                <form id="FormData" class="form-horizontal" action="#">
                    <div class='row'>
                        <div class='col-sm-12 col-md-12'>
                            <legend>Biodata Diri</legend>
                            <div class="form-group">
                                <div class='col-sm-6'>
                                    <label class="control-label">No KTP</label>
                                    <input readonly type='text' readonly autocomplete=off onkeyup="angka(this)" class='form-control FormInput' name='NoKtp' id='NoKtp' placeholder='No KTP' />
                                </div>
                                <div class='col-sm-6'>
                                    <label class="control-label">Nama</label>
                                    <input readonly type='text' readonly autocomplete=off class='form-control FormInput' name='Nama' id='Nama' placeholder='Nama' />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class='col-sm-6'>
                                    <label class="control-label">Tempat Lahir</label>
                                    <input readonly type='text' autocomplete=off class='form-control FormInput' name='TptLahir' id='TptLahir' placeholder='Tempat Lahir' />
                                </div>
                                <div class='col-sm-6'>
                                    <label class="control-label">Tanggal Lahir</label>
                                    <div class='input-group'>
                                        <input readonly type='text' autocomplete=off class='form-control FormInput' name='TglLahir' id='TglLahir' placeholder='Tanggal Lahir' />
                                        <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class='col-sm-6'>
                                    <label class="control-label">Status Kawin</label>
                                    <input readonly type='text' readonly autocomplete=off class='form-control FormInput' name='StatusKawin' id='StatusKawin' placeholder='Status Kawin' />
                                </div>
                                <div class='col-sm-6'>
                                    <label class="control-label">Jenis Kelamin </label></label>
                                    <input readonly type='text' readonly autocomplete=off class='form-control FormInput' name='JenisKelamin' id='JenisKelamin' placeholder='Jenis Kelamin' />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class='col-sm-6'>
                                    <label class="control-label">Agama</label>
                                    <input readonly type='text' readonly autocomplete=off class='form-control FormInput' name='Agama' id='Agama' placeholder='Agama' />
                                </div>
                                <div class='col-sm-6'>
                                    <label class="control-label">NPWP</label>
                                    <input readonly type='text' autocomplete=off class='form-control FormInput' name='Npwp' id='Npwp' placeholder='NPWP' />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class='col-sm-6'>
                                    <label class="control-label">Golongan Darah</label>
                                    <div class='input-group'>
                                        <input readonly type='text' name='GolDarah' id='GolDarah' class='form-control FormInput' placeholder='Golongan Darah'  />
                                        <span class='input-group-addon'>-</span>
                                    </div>
                                </div>
                                <div class='col-sm-6'>
                                    <label class="control-label">No HP</label>
                                    <div class='input-group'>
                                        <input readonly type='text' name='NoHp' onkeyup="angka(this)" id='NoHp' class='form-control FormInput' placeholder='No HP'  />
                                        <span class='input-group-addon'><i class='fa fa-phone'></i></span>
                                    </div>
                                </div>
                            </div>
                            <legend>BPJS</legend>
                            <div class="form-group">
                                <div class='col-sm-6'>
                                    <label class="control-label">BPJS TK </label></label>
                                    <input readonly type='text' name='BpjsTk' id='BpjsTk' class='form-control FormInput' placeholder='BPJS TK'  />
                                </div>
                                <div class='col-sm-6'>
                                    <label class="control-label">BPJS KES</label>
                                    <input readonly type='text' name='BpjsKes' id='BpjsKes' class='form-control FormInput' placeholder='BPJS KES'  />
                                </div>
                                
                            </div>
                            <legend>DPLK</legend>
                            <div class="form-group">
                                <div class='col-sm-6'>
                                    <label class="control-label">CIF DPLK</label>
                                    <input readonly type='text' name='Cif' id='Cif' class='form-control FormInput' placeholder='CIF DPLK'  />
                                </div>
                                <div class='col-sm-6'>
                                    <label class="control-label">No Akun DPLK</label>
                                    <input readonly type='text' name='NoAkunDplk' id='NoAkunDplk' class='form-control FormInput' placeholder='No Akun DPLK'  />
                                </div>
                            </div>
                            <hr>
                            <legend>Ukuran Pakaian & Perlengkapan Dinas</legend>
                            <div class="form-group">
                                <div class='col-sm-6'>
                                        <label class="control-label">Baju </label>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Size</span>
                                            <input readonly type='text' autocomplete=off  class='form-control FormInput' name='Baju' id='Baju' placeholder='Baju' />
                                        </div>
                                    </div>
                                    <div class='col-sm-6'>
                                        <label class="control-label">Celana </label>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Size</span>
                                            <input readonly type='text' autocomplete=off  class='form-control FormInput' name='Celana' id='Celana' placeholder='Celana' />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class='col-sm-6'>
                                        <label class="control-label">Sepatu </label>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Size</span>
                                            <input readonly type='text' autocomplete=off  class='form-control FormInput' name='Sepatu' id='Sepatu' placeholder='Sepatu' />
                                        </div>
                                    </div>
                                    <div class='col-sm-6'>
                                        <label class="control-label">Topi </label>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Size</span>
                                            <input readonly type='text' autocomplete=off  class='form-control FormInput' name='Topi' id='Topi' placeholder='Topi' />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class='col-sm-6'>
                                        <label class="control-label">Ped </label>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Size</span>
                                            <input readonly type='text' autocomplete=off  class='form-control FormInput' name='Ped' id='Ped' placeholder='Ped' />
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                    </div>
                
                </form>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="pendidikan-formal">
                <!-- The timeline -->
                <div class='table-responsove'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th width='5px' class='text-center'>No</th>
                                <th>Pendidikan</th>
                                <th>Periode</th>
                                <th>File Ijazah</th>
                            </tr>
                        </thead>
                        <tbody id='TampilFormal'></tbody>
                    </table>
                </div>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="pendidikan-non-formal">
                <div class='table-responsove'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th class='text-center'>No</th>
                                <th>Sertifikasi</th>
                                <th>Periode</th>
                                <th>Keterangan</th>
                                <th>File Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody id='TampilNonFormal'></tbody>
                    </table>
                </div>
              </div>

              <div class="tab-pane" id="data-keluarga">
                <div class='table-responsove'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th class='text-center'>No</th>
                                <th>Nama</th>
                                <th>Status Keluarga</th>
                                <th>Pendidikan Terakhir</th>
                                <th>Pekerjaan</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody id='TampilKeluarga'></tbody>
                    </table>
                </div>
              </div>

              <div class="tab-pane" id="riwayat-jabatan">
                <div class='table-responsove'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th class='text-center'>No</th>
                                <th>Unit Kerja</th>
                                <th>Divisi</th>
                                <th>Sub Divisi</th>
                                <th>Seksi / Jabatan</th>
                                <th>Periode</th>
                                <th>File SK</th>
                            </tr>
                        </thead>
                        <tbody id='TampilRiwayatKerja'></tbody>
                    </table>
                </div>
              </div>

              <div class="tab-pane" id="daftar-rekening">
                <div class='table-responsove'>
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th class='text-center'>No</th>
                                <th>Nama Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Status</th>
                                <th>Files</th>
                            </tr>
                        </thead>
                        <tbody id='TampilNoRek'></tbody>
                    </table>
                </div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>