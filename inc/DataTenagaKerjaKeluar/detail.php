<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Rekap Data Percabang</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class='form-horizontal' action="#">
            <input type="hidden" name="aksi" id="aksi" value="data_tenaga_kerja_keluar">
            <input type="hidden" name="Id" id="Id" value="">
            <div class='row'>
                <div class='col-sm-3 col-md-4'>
                    <ul>
                        <li><small>Menekan tombol cari tampa meimilih satupun akan menampilkan semua data tenaga kerja</small></li>
                        <li><small>Pilih unit kerja unutk menampilkan tenaga kerja berdasrkan unit kerja</small></li>
                    </ul>
                </div>
                <div class='col-sm-9 col-md-8'>
                    <div class="form-group"><div class='col-sm-12'><span id='ProsesCrud'></span></div></div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Unit Kerja </label>
                            <select class='form-control select-unit-kerja' name='KodeCabang' id='IdCabang'></select>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">Tenaga Kerja </label>
                            <select class='form-control select-user' name='NoKtp' id='NoKtp'></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class='btn-group'>
                                <button type="submit" class="btn btn-sm bg-purple"><i class="fa fa-search"></i> Cari</button>
                                <button id='BtnExport' type="button"   class="btn btn-sm bg-flat bg-green"><i class="fa fa-file-excel-o"></i> Export Excel</button>
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
<div id='ShowData'>
<div class='row' class='box-toll'>
    <div class='col-md-1'>
        <select class='form-control' id='RowPage' onchange='LoadData()'>
            <option value='12'>12</option>
            <option value='24'>24</option>
            <option value='48'>48</option>
            <option value='60'>60</option>
            <option value='100'>100</option>
        </select>
    </div>
    <div class='col-md-11'>
        <div class='pull-right' style='position:relative;top:-22px'>
            <div class='Paging'></div>
            
        </div>
    </div>
</div>

<div id='TampilData'></div>

<div class='row' class='box-toll'>
    <div class='col-md-12'>
        <center><div class='Paging'></div></center>
        <center><span id='PagingInfo'></span></center>
        <center><span id='PagingTime'></span></center>
    </div>
</div>
<div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>