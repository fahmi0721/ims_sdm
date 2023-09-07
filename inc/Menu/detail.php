
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
        <div class='pull-right box-tools'>
            <div class='btn-group' id='BtnControl'>
                <button onclick='Crud()' class='btn btn-sm btn-primary' title='Tambah Data' data-toggle='tooltip'><i class='fa fa-plus'></i> Tambah</button>
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
                        <div class='col-md-6'>
                            <label class="control-label">Nama Menu</label>
                            <input type='text' class='form-control FormInput'name='NamaMenu' id='NamaMenu' placeholder='Nama Menu' />
                        </div>
                        <div class='col-md-6'>
                            <label class="control-label">Direktori</label>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='fa fa-folder'></i></span>
                                <input type='text' class='form-control FormInput' name='Direktori' id='Direktori' placeholder='Direktori' />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class='col-md-6'>
                            <label class="control-label">Icon</label>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='fa fa-circle'></i></span>
                                <input type='text' class='form-control FormInput' name='Icon' id='Icon' placeholder='Icon' />
                                <span class='input-group-addon' id='FilterIcon'></span>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <label class="control-label">Menu Item Root</label>
                            <input type='hidden' class='form-control FormInput'name='ItemRoot' id='ItemRoot' />
                            <input type='text' data-toggle='tooltip' title='Press Enter To Select' class='form-control FormInput'name='MenuItemRoot' id='MenuItemRoot' placeholder='Menu Item Root' />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                            <button type="button" onclick="Clear()" class="btn btn-sm btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
            

           
            
        </form>

        <div id="DetailData">
            <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama Menu</th>
                            <th>Direktori</th>
                            <th>Menu Item Root</th>
                            <th width="8%">Status</th>
                            <th width="8%"><center>Aksi</center></th>
                        </tr>
                    </thead>
                </table>
            </div>
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

<div class='modal fade in' id='ListApprovel' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal1" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">List Approvel</h5>
</div>
<div class='modal-body'>
    <div class='row'>
        <div class='col-sm-12' id='ProsesList'></div>
        <div class='col-sm-12'>
            <form id='FormApprovel' class='form-horizontal' action='javascript:void(0)'>
                <input type='hidden' name='IdUser' id='IdUser' />
                <input type='hidden' name='IdMenu' id='IdMenu'  />
                <div class='form-group'>
                    <div class='col-sm-12'>
                        <div class='input-group'>
                            <input type='text' name='NamaUser' id='NamaUser' placeholder='Nama User' class='form-control ' autocomplete=off />
                            <span class='input-group-btn'><button class='btn btn-primary'><i class='fa fa-plus'></i> Tambah</botton></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class='col-sm-12'>
            <div class='table-responsive'>
                <table class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center' width='5px'>No</th>
                            <th>Nama</th>
                            <th>Urutan Approval</th>
                            <th width='5px' class='text-center'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id='ShowDataApprovel'></tbody>
                </table>
            </div>
        </div>
    </div>
    
    
</div>
</div>
</div>
</div>