
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Upload Data NRP</h3>
        <div class='pull-right box-tools'>
            <div class='btn-group' id='BtnControl'>
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
                            <label class="control-label">File Upload Excel <span class='text-danger'>*</span></label>
                            <div class='input-group'>
                                <input type='file'  class='form-control FormInput' name='FileUpload' id='FileUpload' accept='.xls,.xlsx'  />
                                <span class='input-group-addon'><i class='fa fa-file-o'></i></span>
                            </div>
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

        <div id="DataBerhasil"></div> 
        <div id="DataGagal"></div> 

    </div>
    



    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>

