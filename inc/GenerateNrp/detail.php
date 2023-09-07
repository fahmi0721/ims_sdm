<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Generate NRP</h3>
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
                            <label class="control-label">TMT<span class='text-danger'>*</span></label>
                            <input type='text' autocomplete=off class='form-control FormInput periode' name='Periode' id='Periode' placeholder='Periode' />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class='btn-group'>
                                <button type="button" onclick="GenerateData()" id="btn-geneate" class="btn btn-sm btn-success"><i class="fa fa-cog"></i> Generate</button>
                                <button type="submit" id="btn-submit" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                                <a type="button" href='index.php?page=MasterTenagaKerja' class="btn btn-sm btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div id="DataBerhasil"></div> 
        </form>

    </div>
    



    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>

