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
                        <li><small>Masukkan Periode Bulan</small></li>
                        <li><small>Pastikan tidak ada data yang akan berubah setelah data di backup</small></li>
                    </ul>
                </div>
                <div class='col-sm-9 col-md-8'>
                    <div class="form-group"><div class='col-sm-12'><span id='ProsesCrud'></span></div></div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">Periode </label>
                            <select class='form-control FormInput select-periode' name='Periode' id='Periode'></select>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class='btn-group'>
                                <button type="submit" class="btn btn-sm bg-purple"><i class="fa fa-check-square"></i> Backup Data</button>
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