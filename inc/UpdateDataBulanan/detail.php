<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title"></h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class='form-horizontal' action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="Id" id="Id" value="">
            <div class='row'>
                <div class='col-sm-3 col-md-4'>
                    <ul id="KetLi">
                        <li><small>Pilih Periode Bulan</small></li>
                        <li><small>Masukkan No KTP tenaga kerja yang akan diupdate/dihapus datanya</small></li>
                    </ul>
                </div>
                <div class='col-sm-9 col-md-8'>
                    <div class="form-group"><div class='col-sm-12'><span id='ProsesCrud'></span></div></div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Periode </label>
                            <select class='form-control FormInput select-periode' name='Periode' id='Periode'></select>
                        </div>

                        <div class='col-sm-6'>
                            <label class="control-label">No KTP </label>
                            <select class='form-control FormInput select-no-ktp' name='NoKtp' id='NoKtp'></select>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class='btn-group'>
                                <button type="submit" class="btn btn-sm bg-purple"><i class="fa fa-search"></i> Cari</button>
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
</div>