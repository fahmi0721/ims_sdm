<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Rekap Data Percabang</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class='form-horizontal' action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="Id" id="Id" value="">
            <div class='row'>
                <div class='col-sm-3 col-md-4'>
                    <ul>
                        <li><small>Menekan tombol cari tampa meimilih satupun akan menampilkan semua rekapan data tenaga kerja percabang</small></li>
                        <li><small>Pilih masa kerja unutk menampilkan rekapan tenaga kerja berdasrkan masa kerja</small></li>
                    </ul>
                </div>
                <div class='col-sm-9 col-md-8'>
                    <div class="form-group"><div class='col-sm-12'><span id='ProsesCrud'></span></div></div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Masa Kerja </label>
                            <select class='form-control select-masa-kerja' name='MasaKerja' id='MasaKerja'></select>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class='btn-group'>
                                <button type="submit" class="btn btn-sm bg-purple"><i class="fa fa-search"></i> Cari</button>
                                <a href='javascript:void(0)' data-toggle='tooltip'  title='Export Data Rekapan Percabang' id='btn-export' onclick='Export()' class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Export</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
           
        </form>
    </div>
    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>
<hr />
<div id='ShowData'></div>