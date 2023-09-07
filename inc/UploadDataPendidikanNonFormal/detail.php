
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
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
                            <li> Format upload data download disini <a href='Export/FormatUploadPendidikanNonFormal.php' target='_blank' data-toggle='tooltip' title='Unduh Format Upload' class='btn btn-xs btn-success'><i class='fa fa-download'></i></a></li>
                        </ul>
                    </small>
                </div>
                <div class='col-sm-9 col-md-8'>
                    <div class="form-group"><div class='col-sm-12'><span id='ProsesCrud'></span></div></div>
                    <div class="form-group">
                        <div class='col-sm-6'>
                            <label class="control-label">File<span class='text-danger'>*</span></label>
                            <div class="input-group">
                                <input type='file' autocomplete=off accept='.xlsx,.xls' class='form-control FormInput'name='File' id='File' />
                                <span class='input-group-addon'><i class='fa fa-file-excel-o'></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class='btn-group'>
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Submit</button>
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

