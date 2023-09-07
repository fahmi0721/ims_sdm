
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Upload Data Mutasi</h3>
        <div class='pull-right box-tools'>
            <div class='btn-group' id='BtnControl'>
                <a href="Export/FormatUploadMutasi.php" target='_blank' class='btn btn-sm btn-success' title='Dowload Template' data-toggle='tooltip'><i class='fa fa-download'></i> Download Template</a>
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
                            <label class="control-label">Daftar Mutasi <span class='text-danger'>*</span></label>
                            <div class='input-group'>
                                <input type='file'  class='form-control FormInput' name='File' id='File' accept='image/*, .pdf'  />
                                <span class='input-group-addon'><i class='fa fa-file-o'></i></span>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <label class="control-label">File Upload Excel <span class='text-danger'>*</span></label>
                            <div class='input-group'>
                                <input type='file'  class='form-control FormInput' name='FileUpload' id='FileUpload' accept='.xls,.xlxs'  />
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

